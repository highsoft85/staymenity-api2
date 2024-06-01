<?php

declare(strict_types=1);

namespace App\Http\Composers;

use Illuminate\Contracts\View\View;

class AppComposer
{
    use CommonComposersTrait;

    /**
     * AppComposer constructor.
     */
    public function __construct()
    {
        $this->setCommon();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $this->setCommonCompose($view);
    }
}
