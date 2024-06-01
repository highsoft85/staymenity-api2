<?php

declare(strict_types=1);

use App\Models\Image;
use App\Services\Image\ImageSize;
use App\Services\Image\ImageType;
use App\Services\Image\Path\ImagePathModelService;
use App\Services\Image\Upload\ImageUploadModelService;
use App\Services\Image\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

if (!function_exists('imagePath')) {
    /**
     * @param string $key
     * @param string $size
     * @param Image $image
     * @param string $type
     * @return string|null
     */
    function imagePath(string $key, string $size, Image $image, string $type = ImageType::MODEL): ?string
    {
        if ($type === ImageType::MODEL) {
            return (new ImagePathModelService())->getImage($key, $size, $image);
        }
        return null;
    }
}

if (!function_exists('imageUpload')) {
    /**
     * @param mixed $file
     * @param mixed $oItem
     * @param string $type
     * @param array $filters
     * @param array $options
     * @param bool $setMain
     * @return null
     */
    function imageUpload($file, $oItem, string $type = ImageType::MODEL, array $filters = [], array $options = [], bool $setMain = false)
    {
        if (is_string($file)) {
            $oFile = uploadedFile($file);
        } else {
            $oFile = $file;
        }
        if (!($oFile instanceof UploadedFile)) {
            return null;
        }
        $oService = (new ImageService());
        if (empty($filters)) {
            $filters = [
                ImageSize::SQUARE => ImageSize::IMAGE_SIZE_SQUARE_CONTENT,
                ImageSize::SQUARE_XL => ImageSize::IMAGE_SIZE_SQUARE_XL_CONTENT,
                ImageSize::XS => ImageSize::IMAGE_SIZE_XS_CONTENT,
                ImageSize::XL => ImageSize::IMAGE_SIZE_XL_CONTENT,
            ];
        }
        $options = array_merge([
            'with_main' => true,
            'unique' => false,
            'filters' => $filters,
            'first_set_main' => $setMain,
        ], $options);
        $oService->upload($oItem, [$oFile], $options, $type);
        return null;
    }
}

if (!function_exists('imageUploadUser')) {
    /**
     * @param mixed $file
     * @param mixed $oItem
     * @param string $type
     * @param bool $setMain
     * @return null
     */
    function imageUploadUser($file, $oItem, string $type = ImageType::MODEL, bool $setMain = false)
    {
        $filters = $options = (new \App\Cmf\Project\User\UserController())->image[$type]['filters'];
        $options = [
            'unique' => true,
        ];
        imageUpload($file, $oItem, $type, $filters, $options, $setMain);
        return null;
    }
}

if (!function_exists('imageUploadUserIdentity')) {
    /**
     * @param mixed $file
     * @param mixed $oItem
     * @param string $type
     * @return null
     */
    function imageUploadUserIdentity($file, $oItem, string $type = ImageType::MODEL)
    {
        $options = (new \App\Cmf\Project\UserIdentity\UserIdentityController())->image[$type];
        $filters = $options['filters'];
        imageUpload($file, $oItem, $type, $filters, $options);
        return null;
    }
}

if (!function_exists('uploadedFile')) {
    /**
     * @param string $sFile
     * @return UploadedFile
     */
    function uploadedFile(string $sFile)
    {
        $filename = File::basename($sFile);
        $fileInfo = new finfo(FILEINFO_MIME_TYPE);
        return new UploadedFile($sFile, $filename, $fileInfo->file($sFile), filesize($sFile));
    }
}

if (!function_exists('imageWithDomain')) {
    /**
     * @param string $image
     * @return string
     */
    function imageWithDomain(string $image)
    {
        return (new \App\Services\Image\Path\ImagePathService())->imageWithDomain($image);
    }
}
