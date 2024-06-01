<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Irazasyed\LaravelGAMP\Facades\GAMP;

class SeoGoogleAnalyticsApiService
{
    /**
     * @var null|GAMP|\TheIconic\Tracking\GoogleAnalytics\Analytics
     */
    private $ga = null;
    private $enabled = false;
    private $path = null;

    /**
     * SeoService constructor.
     */
    public function __construct()
    {
        $this->enabled = config('services.google_analytics.enabled');
        if ($this->enabled) {
            $this->ga = GAMP::setClientId(config('gamp.tracking_id'));
        }
    }

    /**
     *
     */
    public function run()
    {
        if (!$this->enabled) {
            return;
        }
        if (!is_null($this->path)) {
            $this->ga->setDocumentPath($this->path);
            $this->ga->setDataSource('api');
            $this->ga->sendPageview();
        }
    }

    /**
     * @param Listing $oItem
     * @return $this
     */
    public function listing(Listing $oItem)
    {
        $url = $oItem->getUrl();
        $this->path = str_replace(config('app.web_url'), '', $url);
        return $this;
    }

    /**
     * @param User $oItem
     * @return $this
     */
    public function host(User $oItem)
    {
        $url = $oItem->getHostUrl();
        $this->path = str_replace(config('app.web_url'), '', $url);
        return $this;
    }

    /**
     * @param User $oItem
     * @return $this
     */
    public function guest(User $oItem)
    {
        $url = $oItem->getGuestUrl();
        $this->path = str_replace(config('app.web_url'), '', $url);
        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function search(Request $request)
    {
        $data = $request->all();
        $this->path = route('web.search', $data, false);
        return $this;
    }
}
