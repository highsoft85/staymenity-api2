<?php

declare(strict_types=1);

namespace App\Cmf\Core;

use App\Http\Composers\CommonComposersTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Helper
{
    use CommonComposersTrait;

    /**
     * @var string|null
     */
    private $as = '';

    /**
     * @var string|null
     */
    private $url = '';

    /**
     * @var string|null
     */
    private $prefix = '';

    /**
     * Helper constructor.
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->url = config('cmf.url');
        $this->as = config('cmf.as');
        $this->prefix = config('cmf.prefix');

        if ($type === 'cmf') {
            $this->as = $this->as !== '' ? $this->as . '.' : '';
        }
        if ($type === 'auth') {
            $this->as = $this->as !== '' ? $this->as . '.auth.' : 'auth.';
            $this->prefix = $this->prefix !== '' ? $this->prefix . '/auth' : 'auth';
        }
    }

    /**
     * @return string|null
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * @return string|null
     */
    public function getAs(): ?string
    {
        return $this->as;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
}
