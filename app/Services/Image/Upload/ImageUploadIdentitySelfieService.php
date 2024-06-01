<?php

declare(strict_types=1);

namespace App\Services\Image\Upload;

use App\Models\Image;
use App\Models\User;
use App\Services\Image\ImageSize;
use App\Services\Image\ImageType;

class ImageUploadIdentitySelfieService extends ImageUploadService implements ImageUploadInterface
{
    /**
     * @param object|User $oItem
     * @return bool
     */
    public function clearMain(object $oItem): bool
    {
        $oMainImages = $oItem->imagesIdentitySelfie()->where('is_main', 1)->get();
        foreach ($oMainImages as $oMainImage) {
            $oMainImage->update([
                'is_main' => 0,
            ]);
        }
        return true;
    }

    /**
     * @param object|User $oItem
     * @param Image $oImage
     * @param array $options
     * @return bool
     * @throws \Exception
     */
    public function delete(object $oItem, Image $oImage, array $options): bool
    {
        $key = $oItem->getImageTypeModel();
        $filters = isset($options['filters']) ? $options['filters'] : [];
        $this->deleteImages($oImage->filename, $key, $oItem->id, $filters, ImageType::IDENTITY_TYPE_SELFIE);
        $oImage->delete();
        return true;
    }

    /**
     * @param object $oFile
     * @param object|User $oItem
     * @param array $options
     * @return string
     */
    public function upload(object $oFile, object $oItem, array $options): string
    {
        $key = $oItem->getImageTypeModel();
        $filters = isset($options['filters']) ? $options['filters'] : [];
        $filename = $this->uploadFile($oFile, $key, $oItem->id, $filters, ImageType::IDENTITY_TYPE_SELFIE);
        $oItem->imagesIdentitySelfie()->create([
            'type' => ImageType::IDENTITY_TYPE_SELFIE,
            'filename' => $filename,
            'options' => isset($filters[ImageSize::SQUARE]['options']['crop'])
                ? json_encode($filters[ImageSize::SQUARE]['options']['crop'])
                : null,
        ]);
        return $filename;
    }
}
