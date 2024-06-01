<?php

declare(strict_types=1);

namespace App\Services\Image\Upload;

use App\Models\Image;
use App\Services\Environment;
use App\Services\Image\Filters\SquareFilter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Services\Image\Filters\FilterInterface;
use Intervention\Image\ImageManagerStatic as ImageStatic;

class ImageUploadService
{
    /**
     * Ключ с оригиналами
     *
     * @var string
     */
    private $originalKey = 'original';

    /**
     * images
     *
     * @var string
     */
    protected $imagePublicDirectory;

    /**
     * @var string
     */
    protected $imageStorageTmpDirectory;

    /**
     * ImageService constructor.
     */
    public function __construct()
    {
        $this->imagePublicDirectory = config('image.public_directory');

        if (config('app.env') === Environment::TESTING) {
            $this->imagePublicDirectory = config('image.testing_directory');
        }
        $this->imageStorageTmpDirectory = 'storage/tmp';
    }

    /**
     * @param object $file
     * @param string $key
     * @param int $id
     * @param array $filters
     * @param string|null $type
     * @return string
     */
    public function uploadFile(object $file, string $key, int $id, array $filters = [], ?string $type = null): string
    {
        $path = is_null($type)
            ? $this->getPath($key, $id)
            : $this->getPath($key, $id, $type);

        $originalDir = public_path($path . '/' . $this->originalKey . '/');
        $this->checkDirectory($originalDir);

        $filename = $this->uploadOriginalFile($file, $originalDir);
        $original = $originalDir . $filename;

        foreach ($filters as $size => $filter) {
            /** @var FilterInterface $oObject */
            $oObject = new $filter['filter']($original, $filter['options']);
            $oObject->resize($path, $size, $filename);
        }
        return $filename;
    }

    /**
     * Загрузить оригинальное изображение
     * @param object $file
     * @param string $path
     * @return string
     */
    private function uploadOriginalFile($file, $path): string
    {
        $sFileName = $file->getClientOriginalName();
        $extension = $this->getExtension($sFileName);
        $sFileName = Str::random(12) . '' . $extension;
        $file instanceof \Illuminate\Http\UploadedFile
            ? $this->moveUploadedFile($file, $path, $sFileName)
            : $file->move($path, $sFileName);
        // webp
        $image = ImageStatic::make($path . $sFileName);
        $jpgFileName = str_replace($extension, '.jpg', $sFileName);
        // orientate https://www.reddit.com/r/PHPhelp/comments/abrqrq/intervention_image_not_respecting_exif_rotation/
        // чтобы jpeg не переворачивались
        $image->orientate()->save($path . $jpgFileName);
        return $jpgFileName;
    }

    /**
     * @param string $fileName
     * @return string
     */
    private function getExtension(string $fileName)
    {
        return substr(strrchr($fileName, '.'), 0);
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @param string $sFileName
     */
    private function moveUploadedFile(\Illuminate\Http\UploadedFile $file, string $path, string $sFileName): void
    {
        File::copy($file->getPathName(), $path . $sFileName);
    }

    /**
     * @param string $key
     * @param int $id
     * @param string|null $type
     * @return string
     */
    private function getPath(string $key, int $id, ?string $type = null): string
    {
        return is_null($type)
            ? $this->path() . '/' . $key . '/' . $id
            : $this->path() . '/' . $key . '/' . $id . '/' . $type;
    }

    /**
     * @return string
     */
    protected function path(): string
    {
        return $this->imagePublicDirectory;
        //return $this->isTmp ? $this->imageStorageTmpDirectory : $this->imagePublicDirectory;
    }

    /**
     * @param string $originalDir
     */
    private function checkDirectory(string $originalDir): void
    {
        if (!File::exists($originalDir)) {
            File::makeDirectory($originalDir, 0777, true);
        }
    }


    /**
     * Удалить изображение со всех папок
     * @param string $filename
     * @param string $key
     * @param int $id
     * @param array $filters
     * @param string|null $type
     */
    public function deleteImages($filename, string $key, int $id, array $filters = [], ?string $type = null)
    {
        $path = is_null($type)
            ? $this->getPath($key, $id)
            : $this->getPath($key, $id, $type);

        $aSizes[] = $this->originalKey;

        foreach ($filters as $k => $option) {
            $aSizes[] = $k;
        }
        foreach ($aSizes as $size) {
            $dir = public_path($path . '/' . $size);
            $file = public_path($path . '/' . $size . '/' . $filename);
            if (File::exists($file)) {
                File::Delete($file);
            }
        }
        $dir = public_path($path);
        if (File::exists($dir)) {
            $this->deleteDirectory($dir);
        }
    }

    /**
     * Удалить директорию, если в ней нет файлов
     *
     * @param string $dir
     */
    private function deleteDirectory(string $dir): void
    {
        if (count(File::allFiles($dir)) === 0) {
            File::deleteDirectory($dir);
        }
    }

    /**
     * @param Image $oImage
     * @param string $key
     * @param int $id
     * @param array $filters
     */
    public function resizeByOptions(Image $oImage, string $key, int $id, array $filters = []): void
    {
        $path = is_null($oImage->type)
            ? $this->getPath($key, $id)
            : $this->getPath($key, $id, $oImage->type);

        $originalDir = public_path($path . '/' . $this->originalKey . '/');

        $filename = $oImage->filename;
        $original = $originalDir . $filename;

        foreach ($filters as $size => $filter) {
            /** @var FilterInterface $oObject */
            $oObject = new $filter['filter']($original, $filter['options']);
            $oObject->resize($path, $size, $filename);
        }
    }

    /**
     * @param string $key
     * @param int $id
     * @return string
     */
    public function getDirectory(string $key, int $id): string
    {
        return public_path($this->path() . '/' . $key . '/' . $id);
    }
}
