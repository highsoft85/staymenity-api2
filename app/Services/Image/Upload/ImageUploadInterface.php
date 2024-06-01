<?php

declare(strict_types=1);

namespace App\Services\Image\Upload;

use App\Models\Image;

interface ImageUploadInterface
{
    /**
     * @param object $oItem
     * @return bool
     */
    public function clearMain(object $oItem): bool;

    /**
     * @param object $oItem
     * @param Image $oImage
     * @param array $options
     * @return bool
     * @throws \Exception
     */
    public function delete(object $oItem, Image $oImage, array $options): bool;

    /**
     * @param object $oFile
     * @param object $oItem
     * @param array $options
     * @return string
     */
    public function upload(object $oFile, object $oItem, array $options): string;
}
