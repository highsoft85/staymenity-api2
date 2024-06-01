<?php

declare(strict_types=1);

namespace App\Cmf\Composers;

use App\Http\Composers\CommonComposersTrait;
use App\Models\Feedback;
use App\Models\Request;
use App\Services\GitVersion;
use App\Services\Member\MemberRegistry;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

class CmfComposer
{
    use CommonComposersTrait;

    /**
     * @var MemberRegistry|null
     */
    private $member = null;

    /**
     * @var string|null
     */
    private $view = null;

    /**
     * @var string
     */
    private $version = null;

    /**
     * @var int
     */
    private $feedbackCount = 0;

    /**
     * @var int
     */
    private $requestCount = 0;

    /**
     * @var mixed
     */
    private $roles;

    public function __construct()
    {
        $this->setCommon();
        $this->member = MemberRegistry::getInstance();
        $this->view = $this->routeView();
        $this->roles = $this->getRoles();
        $this->version = remember('cmf:version', function () {
            $version = GitVersion::getVersion();
            return !empty($version) ? config('cmf.version') . '-' . $version : config('cmf.version');
        });
        $this->feedbackCount = remember('cmf:feedback:count', function () {
            return Feedback::active()->count();
        });
        $this->requestCount = remember('cmf:request:count', function () {
            return Request::where('status', Request::STATUS_UNREAD)->count();
        });
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $this->setCommonCompose($view);

        $view->with('oComposerMember', $this->member);
        $view->with('sComposerRouteView', $this->view);
        $view->with('sComposerVersion', $this->version);
        $view->with('composerFeedbackCount', $this->feedbackCount);
        $view->with('composerRequestCount', $this->requestCount);
    }

    /**
     * @return string|null
     */
    private function routeView()
    {
        if (!is_null(Route::current())) {
            $prefix = config('cmf.as');
            $as = Route::current()->action['as'];

            if ($prefix !== '') {
                $as = str_replace($prefix . '.', '', $as);
            }
            $stristr = stristr($as, '.', true);
            if ($stristr !== false) {
                return $stristr;
            }
            return $as;
        }
        return null;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return remember('cmf:roles', function () {
            return Role::all()->toArray();
        });
    }
}
