<?php

declare(strict_types=1);

namespace App\Services\Image\Upload;

use App\Models\Image;
use App\Services\Image\ImageSize;
use App\Services\Image\ImageType;

class ImageUploadModelService extends ImageUploadService implements ImageUploadInterface
{
    /**
     * @param object $oItem
     * @return bool
     */
    public function clearMain(object $oItem): bool
    {
        $oMainImages = $oItem->modelImages()->where('is_main', 1)->get();
        foreach ($oMainImages as $oMainImage) {
            $oMainImage->update([
                'is_main' => 0,
            ]);
        }
        return true;
    }

    /**
     * @param object $oItem
     * @param Image $oImage
     * @param array $options
     * @return bool
     * @throws \Exception
     */
    public function delete(object $oItem, Image $oImage, array $options): bool
    {
        $key = $oItem->getImageTypeModel();
        $filters = isset($options['filters']) ? $options['filters'] : [];
        $this->deleteImages($oImage->filename, $key, $oItem->id, $filters, ImageType::MODEL);
        $oImage->delete();
        return true;
    }

    /**
     * @param object $oFile
     * @param object $oItem
     * @param array $options
     * @return string
     */
    public function upload(object $oFile, object $oItem, array $options): string
    {
        $key = $oItem->getImageTypeModel();
        $filters = isset($options['filters']) ? $options['filters'] : [];
        $filename = $this->uploadFile($oFile, $key, $oItem->id, $filters, ImageType::MODEL);
        $oItem->modelImages()->create([
            'type' => ImageType::MODEL,
            'filename' => $filename,
            'options' => isset($filters[ImageSize::SQUARE]['options']['crop'])
                ? json_encode($filters[ImageSize::SQUARE]['options']['crop'])
                : null,
        ]);
        return $filename;
    }
}
