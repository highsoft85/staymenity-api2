<?php

declare(strict_types=1);

namespace App\Services\Image\Path;

use App\Models\Image;
use App\Services\Environment;
use App\Services\Image\ImageSize;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImagePathService
{
    /**
     * Директория в public, где изображнения для сущностей по умолчанию
     *
     * @var string
     */
    private $defaultDirectory = 'img/default';

    /**
     * images
     *
     * @var string
     */
    private $imagePublicDirectory;

    /**
     * @var string
     */
    private $imageStorageTmpDirectory;

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
     * @param string $file
     * @return string
     */
    private function file(string $file): string
    {
        return public_path($file);
    }

    /**
     * @param string $file
     * @param bool $onlyName
     * @return bool
     */
    private function fileExists(string $file, $onlyName = true): bool
    {
        return $onlyName ? file_exists($this->file($file)) : file_exists($file);
    }

    /**
     * @param string $key
     * @param string $size
     * @param int $id
     * @param string $filename
     * @param string $type
     * @return string
     */
    private function path(string $key, string $size, int $id, string $filename, $type): string
    {
        return $this->imagePublicDirectory . '/' . $key . '/' . $id . '/' . $type . '/' . $size . '/' . $filename;
    }

    /**
     * @param string $key
     * @param string $size
     * @param int $id
     * @param string $filename
     * @param string $type
     * @return string
     */
    public function getPath(string $key, string $size, int $id, string $filename, $type): string
    {
        return $this->path($key, $size, $id, $filename, $type);
    }

    /**
     * @param string $key
     * @param Image $oImage
     * @return string
     */
    public function getPathByImage(string $key, Image $oImage)
    {
        return public_path($this->getPath($key, 'original', $oImage->imageable_id, $oImage->filename, $oImage->type));
    }

    /**
     * @param string $key
     * @param string|null $size
     * @return string
     */
    protected function default(string $key = 'default', string $size = null)
    {
        if (!is_null($size)) {
            $file = $this->defaultDirectory . '/' . $key . '_' . $size . '.png';
            if ($this->fileExists($file)) {
                return $this->defaultKeyAsset($key . '_' . $size);
            }
        }
        $file = $this->defaultDirectory . '/' . $key . '.png';
        if (!$this->fileExists($file)) {
            $key = 'default';
        }
        return $this->defaultKeyAsset($key);
    }

    /**
     * @param string $key
     * @param string $size
     * @param Image|null $model
     * @param string|null $type
     * @return string
     */
    public function image(string $key, string $size, Image $model = null, ?string $type = null)
    {
        if (is_null($model)) {
            return $this->default($key, $size);
        }
        $filename = $model->filename;
        if (empty($filename)) {
            return $this->default($key, $size);
        }
        $path = $this->path($key, $size, $model->imageable_id, $filename, $type);

        return $this->asset($path);
    }

    /**
     * @param string $key
     * @param Image|null $model
     * @param string $type
     * @return string|null
     */
    public function imageBase64(string $key, ?Image $model, string $type)
    {
        if (is_null($model)) {
            return null;
        }
        $image = $this->path($key, ImageSize::ORIGINAL, $model->imageable_id, $model->filename, $type);
        if (!$this->fileExists($image)) {
            return null;
        }
        return base64_encode(file_get_contents($this->file($image)));
    }

    /**
     * @param string $key
     * @param string $size
     * @param Image|null $model
     * @param string|null $type
     * @return string
     */
    protected function imageWithDefault(string $key, string $size, Image $model = null, ?string $type = null)
    {
        if (is_null($model)) {
            return $this->default($key, $size);
        }
        $filename = $model->filename;
        if (empty($filename)) {
            return $this->default($key, $size);
        }
        $path = $this->path($key, $size, $model->imageable_id, $filename, $type);

        return $this->checkFile($path) ? $this->asset($path) : $this->default($key, $size);
    }

    /**
     * @param string $key
     * @param string $size
     * @param object|null $model
     * @param string|null $type
     * @return string
     */
    public function main(string $key, string $size, ?object $model = null, ?string $type = null)
    {
        $oImage = $this->getImageModel($model, $type);
        if (is_null($oImage)) {
            return $this->default($key, $size);
        }
        $filename = $oImage->filename;
        $path = $this->path($key, $size, $model->id, $filename, $type);

        return $this->checkFile($path) ? $this->asset($path) : $this->default($key, $size);
    }

    /**
     * @param string $key
     * @param string $size
     * @param object|null $model
     * @param string|null $type
     * @return bool
     */
    public function checkMain(string $key, string $size, ?object $model = null, ?string $type = null): bool
    {
        $oImage = $this->getImageModel($model, $type);
        if (is_null($oImage)) {
            return false;
        }
        $filename = $oImage->filename;
        $path = $this->path($key, $size, $model->id, $filename, $type);
        if (!$this->checkFile($path)) {
            return false;
        }
        return true;
    }

    /**
     * @param Image $oImage
     * @param string $key
     * @param string $size
     * @param object|null $model
     * @param string|null $type
     * @return bool
     */
    protected function checkOriginal(Image $oImage, string $key, string $size, ?object $model = null, ?string $type = null): bool
    {
        $filename = $oImage->filename;
        $path = $this->path($key, $size, $model->id, $filename, $type);
        if (!$this->checkFile($path)) {
            return false;
        }
        return true;
    }

    /**
     * Только для
     *
     * @param null|object $model
     * @param string|null $type
     * @return null|Image
     */
    private function getImageModel(?object $model = null, ?string $type = null)
    {
        if (is_null($model)) {
            return null;
        }
        $images = $model->modelImages;
        if (is_null($images) || empty($images) || empty($images[0])) {
            return null;
        }
        $oFile = $images->where('is_main', 1)->first();
        if (is_null($oFile)) {
            return null;
        }
        return $oFile;
    }

    /**
     * Изоюражение по умолчанию по ключу
     *
     * @param string $key {user}
     * @return string
     */
    private function defaultKeyAsset(string $key): string
    {
        return $this->asset($this->defaultDirectory . '/' . $key . '.png');
    }

    /**
     * @param string $image
     * @return string
     */
    public function imageWithDomain(string $image)
    {
        return $this->asset($image);
    }

    /**
     * @param string $path
     * @return string
     */
    private function asset(string $path): string
    {
        return $this->remoteUrl($path);
    }

    /**
     * @param string $path
     * @return bool
     */
    private function checkFile(string $path): bool
    {
        return File::exists($this->file($path));
    }

    /**
     * @param string $path
     * @return string
     */
    private function remoteUrl(string $path): string
    {
        if (config('app.env') === Environment::TESTING) {
            return $path;
        }
        if (Str::startsWith($path, '/')) {
            $path = substr($path, 1);
        }
        $hash = config('image.hash');
        $url = config('image.url') . '/' . $path;
        if ($hash) {
            $url .= '?h=' . Str::random(16);
        }
        return $url;
    }
}
