<?php

declare(strict_types=1);

namespace App\Services\Image\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\ImageManagerStatic as ImageStatic;
use Intervention\Image\Constraint;

class SquareFilter implements FilterInterface
{
    use FilterCommonTrait;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var string|null
     */
    private $original = null;

    /**
     * SquareFilter constructor.
     * @param string $original
     * @param array $options
     */
    public function __construct(string $original, array $options)
    {
        $this->original = $original;
        $this->options = $options;
    }

    /**
     * @param Image $image
     * @return Image|mixed
     */
    public function applyFilter(Image $image)
    {
        if (isset($this->options['dimension'])) {
            $width = $image->width();
            $height = $image->height();
            $dimension = explode(':', $this->options['dimension']);
            $q = $dimension[1] / $dimension[0];
            if ($width > $height) {
                $image = $image->fit(intval($height), intval($height * $q));
            } else {
                $image = $image->fit(intval($width), intval($width * $q));
            }
        }
        if (isset($this->options['size'])) {
            $size = intval($this->options['size']);
            $image = $this->fit($image, $size);
        }
        if (isset($this->options['width'], $this->options['height'])) {
            $image = $this->size($image, $this->options['width'], $this->options['height']);
        }
        return $image;
    }

    /**
     * @param string $path
     * @param string $size
     * @param string $filename
     */
    public function resize(string $path, string $size, string $filename)
    {
        if (isset($this->options['crop'])) {
            $image = ImageStatic::make($this->original);
            $image->crop(
                (int)$this->options['crop']['w'],
                (int)$this->options['crop']['h'],
                (int)$this->options['crop']['x'],
                (int)$this->options['crop']['y']
            );
            if (isset($this->options['crop']['rotate']) && (int)$this->options['crop']['rotate'] !== 0) {
                $image->rotate(-1 * (int)$this->options['crop']['rotate']);
            }
        } else {
            $image = ImageStatic::make($this->original);
        }
        $image = $this->applyFilter($image);
        $sPath = public_path($path . '/' . $size . '/');
        $this->checkDirectory($sPath);
        $image->save($sPath . $filename);
    }

    /**
     * @param Image $image
     * @param int $width
     * @return mixed
     */
    private function fit(Image $image, int $width)
    {
        // если изображение картинки (ширина) меньше требуемой для резайза
        if ($image->getWidth() < $width) {
            return $image;
        }
        $image = $image->resize($width, null, function (Constraint $constraint) {
            $constraint->aspectRatio();
        });

        return $image;
    }

    /**
     * @param Image $image
     * @param int $width
     * @param int $height
     * @return Image
     */
    private function size(Image $image, int $width, int $height)
    {
        $w = $image->width();
        if ($w < $width) {
            $w = $width;
            $image = $image->resize($w, null, function (Constraint $constraint) {
                $constraint->aspectRatio();
            });
        }
        $h = $image->height();
        if ($h < $height) {
            $image = $image->resize(null, $height, function (Constraint $constraint) {
                $constraint->aspectRatio();
            });
        }

        $image->fit($width, $height, function (Constraint $constraint) {
            $constraint->upsize();
        });
        return $image;
    }
}
