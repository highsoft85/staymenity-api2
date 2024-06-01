<?php

declare(strict_types=1);

namespace App\Services\Image;

use App\Models\Image;
use App\Models\User;
use App\Services\Image\Path\ImagePathModelService;
use App\Services\Image\Upload\ImageUploadIdentityBackService;
use App\Services\Image\Upload\ImageUploadIdentityFrontService;
use App\Services\Image\Upload\ImageUploadIdentitySelfieService;
use App\Services\Image\Upload\ImageUploadModelService;

class ImageService
{
    /**
     * @param object $oItem
     * @param array $aFiles
     * @param array $options
     * @param string $type
     * @return bool
     * @throws \Exception
     */
    public function upload($oItem, array $aFiles, array $options, string $type): bool
    {
        $result = false;
        $files = [];
        switch ($type) {
            case ImageType::MODEL:
                $oService = $this->getServiceByType(ImageType::MODEL);
                foreach ($aFiles as $oFile) {
                    $files[] = $oService->upload($oFile, $oItem, $options);
                }
                $result = true;
                break;
            default:
                $oService = $this->getServiceByType($type);
                foreach ($aFiles as $oFile) {
                    $files[] = $oService->upload($oFile, $oItem, $options);
                }
                $result = true;
                break;
        }
        if (isset($options['unique']) && $options['unique']) {
            $oImages = Image::where('type', $type)
                ->where('imageable_type', get_class($oItem))
                ->where('imageable_id', $oItem->id)
                ->get();
            if (count($oImages) !== 0) {
                foreach ($oImages as $oImage) {
                    if ($oImage->filename === $files[0]) {
                        continue;
                    }
                    $this->delete($oItem, $oImage, $options, $type);
                }
            }
        }
        if (isset($options['with_main']) && $options['with_main']) {
            $oMainImage = Image::where('type', $type)
                ->where('imageable_type', get_class($oItem))
                ->where('imageable_id', $oItem->id)
                ->where('is_main', 1)
                ->first();
            if (is_null($oMainImage)) {
                $oImage = Image::where('type', $type)
                    ->where('imageable_type', get_class($oItem))
                    ->where('imageable_id', $oItem->id)
                    ->first();
                $oImage->update([
                    'is_main' => 1,
                ]);
                $this->thisAfterChangeEvent($oItem, $oImage, $options, $type);
            }
        }
        if (isset($options['first_set_main']) && $options['first_set_main']) {
            if (isset($files[0])) {
                $oImage = Image::where('type', $type)
                    ->where('imageable_type', get_class($oItem))
                    ->where('imageable_id', $oItem->id)
                    ->where('filename', $files[0])
                    ->first();
                if (!is_null($oImage)) {
                    $this->main($oItem, $oImage, $options, $type);
                }
            }
        }
        $this->updateNumbers($oItem, $type);
        $this->updatePriorities($oItem, $type);
        return $result;
    }

    /**
     * @param object|User $oItem
     * @param Image $oImage
     * @param array $options
     * @param string $type
     * @return bool
     * @throws \Exception
     */
    public function delete(object $oItem, Image $oImage, array $options, string $type): bool
    {
        $result = false;
        switch ($type) {
            case ImageType::MODEL:
                $oService = $this->getServiceByType(ImageType::MODEL);
                $result = $oService->delete($oItem, $oImage, $options);
                // После удаления
                $oMainImage = $oItem->modelImagesOrdered()->where('is_main', 1)->first();
                if (is_null($oMainImage)) {
                    $oMainImage = $oItem->modelImagesOrdered()->first();
                    if (!is_null($oMainImage)) {
                        $oMainImage->update([
                            'is_main' => 1,
                        ]);
                    }
                }
                break;
            case ImageType::IDENTITY_TYPE_FRONT:
            case ImageType::IDENTITY_TYPE_BACK:
            case ImageType::IDENTITY_TYPE_SELFIE:
                $oService = $this->getServiceByType($type);
                $result = $oService->delete($oItem, $oImage, $options);
                break;
        }
        $this->updateNumbers($oItem, $type);
        $this->updatePriorities($oItem, $type);
        $this->thisAfterChangeEvent($oItem, $oImage, $options, $type);
        return $result;
    }

    /**
     * @param object $oItem
     * @param Image $oImage
     * @param array $options
     * @param string $type
     * @return bool
     * @throws \Exception
     */
    public function main(object $oItem, Image $oImage, array $options, string $type): bool
    {
        $result = false;
        switch ($type) {
            case ImageType::MODEL:
                $oService = $this->getServiceByType(ImageType::MODEL);
                $result = $oService->clearMain($oItem);
                break;
        }
        // была проблема после мультисохранения
        // бралось изображение с is_main 1, стирались is_main везде и потом ему ставилось опять 1
        // и наверно т.к. значение одинаковое, то обновление не происходило
        // поэтому стоит перевыборка этого изображения
        /** @var Image $oImage */
        $oImage = Image::find($oImage->id);
        $oImage->update([
            'is_main' => 1,
        ]);
        //$this->updateNumbers($oItem, $type);
        $this->thisAfterChangeEvent($oItem, $oImage, $options, $type);
        return $result;
    }

    /**
     * @param string $type
     * @return ImageUploadModelService|null
     */
    public function getServiceByType(string $type)
    {
        $oService = null;
        switch ($type) {
            case ImageType::MODEL:
                $oService = new ImageUploadModelService();
                break;
            case ImageType::IDENTITY_TYPE_FRONT:
                $oService = new ImageUploadIdentityFrontService();
                break;
            case ImageType::IDENTITY_TYPE_BACK:
                $oService = new ImageUploadIdentityBackService();
                break;
            case ImageType::IDENTITY_TYPE_SELFIE:
                $oService = new ImageUploadIdentitySelfieService();
                break;
        }
        return $oService;
    }

    /**
     * @param string $type
     * @return ImagePathModelService|null
     */
    public function getImagePathServiceByType(string $type)
    {
        $oService = null;
        switch ($type) {
            case ImageType::MODEL:
                $oService = new ImagePathModelService();
                break;
        }
        return $oService;
    }

    /**
     * @param object $oItem
     * @param Image $oImage
     * @param array $options
     * @param string $type
     */
    private function thisAfterChangeEvent(object $oItem, Image $oImage, array $options, string $type)
    {
        if (isset($options['clear_cache']) && $options['clear_cache']) {
            if (method_exists($this, 'thisAfterChange')) {
                $this->thisAfterChange($oItem);
            }
        }
    }

    /**
     * @param object $oItem
     * @param string $type
     */
    public function updateNumbers(object $oItem, string $type)
    {
        $oImages = $this->getOrderedImagesByType($oItem, $type);
        foreach ($oImages as $key => $oImage) {
            $oImage->update([
                'number' => $key + 1,
            ]);
        }
    }

    /**
     * @param object $oItem
     * @param string $type
     */
    public function updatePriorities(object $oItem, string $type)
    {
        $oImages = $this->getOrderedImagesByType($oItem, $type);
        foreach ($oImages as $key => $oImage) {
            if ($oImage->is_main) {
                $oImage->update([
                    'priority' => 100,
                ]);
            } else {
                $oImage->update([
                    'priority' => 100 - $key,
                ]);
            }
        }
    }

    /**
     * @param User|object $oItem
     * @param string $type
     * @return Image[]
     */
    public function getOrderedImagesByType(object $oItem, string $type)
    {
        $oImages = [];
        switch ($type) {
            case ImageType::MODEL:
                $oImages = $oItem->modelImagesOrdered()->get();
                break;
            case ImageType::IDENTITY_TYPE_FRONT:
                $oImages = $oItem->imagesIdentityFront()->get();
                break;
            case ImageType::IDENTITY_TYPE_BACK:
                $oImages = $oItem->imagesIdentityBack()->get();
                break;
            case ImageType::IDENTITY_TYPE_SELFIE:
                $oImages = $oItem->imagesIdentitySelfie()->get();
                break;
        }
        return $oImages;
    }

    /**
     * @param User|object $oItem
     * @param string $type
     * @param int|null $limit
     * @return bool
     */
    public function checkLimit(object $oItem, string $type, ?int $limit = null)
    {
        if (is_null($limit)) {
            return true;
        }
        $oImages = [];
        switch ($type) {
            case ImageType::MODEL:
                $oImages = $oItem->modelImagesOrdered;
                break;
        }
        return count($oImages) < $limit;
    }
}
