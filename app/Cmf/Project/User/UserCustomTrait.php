<?php

declare(strict_types=1);

namespace App\Cmf\Project\User;

use App\Events\Webhook\WebhookSyncToEvent;
use App\Notifications\User\SystemNotification;
use App\Services\Hostfully\Models\Webhooks;
use App\Services\Hostfully\Webhooks\Index;
use App\Services\Model\UserServiceModel;
use App\Services\Sync\Hostfully\HostfullyWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

trait UserCustomTrait
{
    /**
     * @param Request $request
     * @param object $oModel
     * @return array
     */
    protected function validateByData(Request $request, $oModel): array
    {
        $oUsers = $this->class::where('email', $request->get('email'))->get();
        foreach ($oUsers as $oUser) {
            if ($oModel->id !== $oUser->id) {
                return responseCommon()->error([
                    'message' => 'The email has already been taken.',
                ]);
            }
        }
        return responseCommon()->success();
    }

    /**
     * Скрыть/показать сайд бар для адмики, запрос в
     * resources\assets\js\admin\template\app.js
     *
     * @param Request $request
     * @return array
     */
    public function saveSidebarToggle(Request $request): array
    {
        if ($request->exists('toggle') && $request->get('toggle') === 'true') {
            Session::put('sidebar-toggle', $request->get('toggle'));
        } else {
            Session::remove('sidebar-toggle');
        }
        return responseCommon()->success();
    }

    /**
     * Модальное окно для изменений
     *
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function getModalCommand(Request $request): array
    {
        $view = view($this->getCurrentView() . '.components.modals.command', [])->render();

        return responseCommon()->success([
            'view' => $view,
        ]);
    }

    /**
     * Модальное окно с Emoji
     *
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function actionModalMarkdownEmoji(Request $request): array
    {
        return responseCommon()->success([
            'view' => view('cmf.content.default.modals.tabs.markdown.dialogs.emoji')->render(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function actionChangePassword(Request $request, int $id)
    {
        $validation = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ($validation->fails()) {
            return responseCommon()->validationMessages($validation);
        }
        /** @var User $oUser */
        $oUser = User::find($id);

        $oUser->update([
            'password' => Hash::make(($request->get('password'))),
        ]);

        return responseCommon()->success([], 'Success');
    }


    /**
     * @param Request $request
     * @param int $id
     * @return array
     * @throws \Throwable
     */
    public function actionGetAdminToken(Request $request, int $id)
    {
        /** @var User $oItem */
        $oItem = $this->findByClass($this->class, $id);
        $token = (new UserServiceModel($oItem))->tokenGet(User::TOKEN_AUTH_ADMIN_NAME);
        return responseCommon()->success([
            'view' => view('cmf.content.user.modals.tabs.user_tokens', [
                'model' => self::NAME,
                'oItem' => $oItem,
                'token' => $token,
            ])->render(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionCustomer(Request $request, int $id)
    {
        /** @var User $oItem */
        $oItem = $this->findByClass($this->class, $id);

        $data = $request->all();
        if (is_null($oItem->details)) {
            $oItem->details()->create($data);
        }
        $oItem->details()->update($data);
        return responseCommon()->success([], 'Success');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionBanned(Request $request, int $id)
    {
        /** @var User $oItem */
        $oItem = $this->findByClass($this->class, $id);
        $banned = (int)$request->get('banned');
        if (Auth::user()->id === $oItem->id) {
            return responseCommon()->error([], 'You cannot ban yourself');
        }
        $oItem->update([
            'banned_at' => $banned === 1 ? now() : null,
            'status' => $banned === 1 ? User::STATUS_NOT_ACTIVE : User::STATUS_ACTIVE,
        ]);
        $oItem->refresh();
        return responseCommon()->success([]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionMessage(Request $request, int $id)
    {
        /** @var User $oItem */
        $oItem = $this->findByClass($this->class, $id);
        $oItem->notify(new SystemNotification($request->get('message')));
        return responseCommon()->success([], 'Success');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function actionSaveHostfully(Request $request, int $id)
    {
        /** @var User $oItem */
        $oItem = $this->findByClass($this->class, $id);
        $agencyUid = $request->get('agencyUid');

        if (is_null($oItem->details) || is_null($oItem->details->hostfully_agency_uid)) {
            if (!empty($agencyUid)) {
                (new UserServiceModel($oItem))->saveDetails([
                    'hostfully_agency_uid' => $agencyUid,
                ]);
                $oItem->refresh();
                //eventSyncQueue(WebhookSyncToEvent::class, 'Webhook Sync ' . $oItem->id, $oItem, true);
                event(new WebhookSyncToEvent($oItem, true));
            }
        }
        return responseCommon()->success([], 'Success');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function actionRemoveHostfully(Request $request, int $id)
    {
        /** @var User $oItem */
        $oItem = $this->findByClass($this->class, $id);

        if (!is_null($oItem->details) && !is_null($oItem->details->hostfully_agency_uid)) {
            event(new WebhookSyncToEvent($oItem, false));
        }
        return responseCommon()->success([], 'Success');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionDevHostfullyWebhooks(Request $request, int $id)
    {
        /** @var User $oItem */
        $oItem = $this->findByClass($this->class, $id);

        $aWebhooks = [];

        if (healthCheckHostfully()->isActive() && !is_null($oItem->details) && !is_null($oItem->details->hostfully_agency_uid)) {
            $aWebhooks = (new Index())->__invoke($oItem->details->hostfully_agency_uid);
            $aWebhooks = collect($aWebhooks)->keyBy('eventType')->toArray();
        }

        $aWebhookEvents = [
            Webhooks::EVENT_TYPE_NEW_BOOKING,
            Webhooks::EVENT_TYPE_BOOKING_UPDATED,
            Webhooks::EVENT_TYPE_BOOKING_CANCELLED,
            Webhooks::EVENT_TYPE_NEW_INQUIRY,
            Webhooks::EVENT_TYPE_NEW_BLOCKED_DATES,
        ];

        return responseCommon()->success([
            'view' => view('cmf.content.user.modals.hostfullyWebhooks', [
                'model' => self::NAME,
                'oItem' => $oItem,
                'aWebhooks' => $aWebhooks,
                'aWebhookEvents' => $aWebhookEvents,
            ])->render(),
        ]);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionDevHostfullySetWebhook(Request $request, int $id)
    {
        $setEnabled = (int)$request->get('webhook') === 1;
        $webhookUid = $request->get('webhook_uid');
        $type = $request->get('type');

        if ($setEnabled) {
            if ($id !== 0) {
                /** @var User $oItem */
                $oItem = $this->findByClass($this->class, $id);
                (new \App\Services\Hostfully\Webhooks\Store())->agency($oItem->details->hostfully_agency_uid, $type);
            } else {
                (new \App\Services\Hostfully\Webhooks\Store())->agency('', $type);
            }
        } else {
            (new \App\Services\Hostfully\Webhooks\Destroy())->__invoke($webhookUid);
        }
        if ($id === 0) {
            return responseCommon()->success();
        }
        return $this->actionDevHostfullyWebhooks($request, $id);
    }
}
