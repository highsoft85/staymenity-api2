<?php

declare(strict_types=1);

namespace App\Cmf\Project\Listing;

use App\Models\Amenity;
use App\Models\Listing;
use App\Models\Rule;
use App\Services\Geocoder\GeocoderCitiesService;
use App\Services\Hostfully\BaseHostfullyService;
use App\Services\Hostfully\HostfullyLeadsService;
use App\Services\Hostfully\Leads\Destroy;
use App\Services\Hostfully\Models\Webhooks;
use App\Services\Hostfully\Webhooks\Index;
use App\Services\Hostfully\Webhooks\Store;
use App\Services\Model\ListingServiceModel;
use App\Services\Sync\Hostfully\Listing\SyncToHostfullyListingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

trait ListingCustomTrait
{
    /**
     * @param Request $request
     * @param int $id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function actionSaveAmenities(Request $request, int $id)
    {
        /** @var Listing $oItem */
        $oItem = $this->findByClass($this->class, $id);
        $amenities = $request->get('amenities') ?? [];

        $oAmenityOther = Amenity::other()->first();
        $amenities_other = null;
        if (in_array($oAmenityOther->id, $amenities)) {
            if (!empty($request->get('amenities_other'))) {
                $amenities_other = $request->get('amenities_other');
            } else {
                return responseCommon()->validationMessages(null, [
                    'amenities_other' => __('validation.required', ['attribute' => 'amenities_other'])
                ]);
            }
        }
        $oItem->settings->update([
            'amenities' => $amenities_other,
        ]);
        $oItem->amenities()->sync($amenities);
        return responseCommon()->success([], 'Success');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function actionSaveRules(Request $request, int $id)
    {
        /** @var Listing $oItem */
        $oItem = $this->findByClass($this->class, $id);
        $rules = $request->get('rules') ?? [];

        $oRuleOther = Rule::other()->first();
        $rules_other = null;
        if (in_array($oRuleOther->id, $rules)) {
            if (!empty($request->get('rules_other'))) {
                $rules_other = $request->get('rules_other');
            } else {
                return responseCommon()->validationMessages(null, [
                    'rules_other' => __('validation.required', ['attribute' => 'rules_other'])
                ]);
            }
        }
        $oItem->settings->update([
            'rules' => $rules_other,
        ]);
        $oItem->rules()->sync($rules);
        return responseCommon()->success([], 'Success');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionBanned(Request $request, int $id)
    {
        /** @var Listing $oItem */
        $oItem = $this->findByClass($this->class, $id);
        $banned = (int)$request->get('banned');
        $oItem->update([
            'banned_at' => $banned === 1 ? now() : null,
            'status' => $banned === 1 ? Listing::STATUS_NOT_ACTIVE : Listing::STATUS_ACTIVE,
        ]);
        $oItem->refresh();
        return responseCommon()->success([]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionSync(Request $request, int $id)
    {
        /** @var Listing $oItem */
        $oItem = $this->findByClass($this->class, $id);
        $sync = (int)$request->get('sync');

        if ($sync) {
            (new SyncToHostfullyListingService($oItem->user->details->hostfully_agency_uid, $oItem))->sync();
        } else {
            if ($oItem->hostfully->uid) {
                (new \App\Services\Hostfully\Properties\Destroy())->__invoke($oItem->hostfully->uid, []);
                $oItem->hostfully()->delete();
            }
        }
        return responseCommon()->success([]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionSaveSettings(Request $request, int $id)
    {
        /** @var Listing $oItem */
        $oItem = $this->findByClass($this->class, $id);
        if (is_null($oItem->settings)) {
            $oItem->settings()->create();
        }
        $oItem->settings()->update([
            'type' => $request->get('type'),
            'amenities' => $request->get('amenities'),
            'rules' => $request->get('rules'),
            'cancellation_description' => $request->get('cancellation_description'),
        ]);
        return responseCommon()->success([], 'Success');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionSaveAddress(Request $request, int $id)
    {
        /** @var Listing $oItem */
        $oItem = $this->findByClass($this->class, $id);
        $address = $request->get('address');
        $oGeo = (new GeocoderCitiesService());
        $return = $oGeo->address($address);
        (new ListingServiceModel($oItem))->saveLocation($return[0]['place_id']);
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
        /** @var Listing $oItem */
        $oItem = $this->findByClass($this->class, $id);
        $propertyUid = $request->get('propertyUid');
        $oModel = $oItem->hostfully;

        if (empty($propertyUid) && !is_null($oModel)) {
            $oModel->delete();
            return responseCommon()->success([], 'Success');
        }
        if (!is_null($oModel)) {
            $oModel->update([
                'uid' => $propertyUid,
            ]);
        } else {
            $oItem->hostfully()->create([
                'uid' => $propertyUid,
            ]);
        }
        return responseCommon()->success([], 'Success');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionDevHostfully(Request $request, int $id)
    {
        /** @var Listing $oItem */
        $oItem = $this->findByClass($this->class, $id);

        $aLeads = [];

        if (!is_null($oItem->user->details->hostfully_agency_uid)) {
            $oService = (new HostfullyLeadsService($oItem->user->details->hostfully_agency_uid));
            $aLeads = $oService->get($oItem->hostfully->uid);
        }

        return responseCommon()->success([
            'view' => view('cmf.content.listing.modals.hostfully', [
                'model' => self::NAME,
                'oItem' => $oItem,
                'aLeads' => $aLeads,
            ])->render(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionDevHostfullyWebhooks(Request $request, int $id)
    {
        /** @var Listing $oItem */
        $oItem = $this->findByClass($this->class, $id);

        $aWebhooks = [];

        if (!is_null($oItem->user->details)) {
            $aWebhooks = (new Index())->__invoke($oItem->user->details->hostfully_agency_uid);
            $aWebhooks = collect($aWebhooks)->where('objectUid', $oItem->hostfully->uid)->keyBy('eventType')->toArray();
        }

        $aWebhookEvents = [
            Webhooks::EVENT_TYPE_NEW_BOOKING,
            Webhooks::EVENT_TYPE_BOOKING_UPDATED,
            Webhooks::EVENT_TYPE_BOOKING_CANCELLED,
            Webhooks::EVENT_TYPE_NEW_INQUIRY,
        ];

        return responseCommon()->success([
            'view' => view('cmf.content.listing.modals.hostfullyWebhooks', [
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
     * @throws \Throwable
     */
    public function actionDevHostfullyDelete(Request $request, int $id)
    {
        /** @var Listing $oItem */
        $oItem = $this->findByClass($this->class, $id);

        (new Destroy())->__invoke($request->get('uid'), []);

        $oService = (new HostfullyLeadsService($oItem->user->details->hostfully_agency_uid));
        $aLeads = $oService->get($oItem->hostfully->uid);
        return responseCommon()->success([
            'view' => view('cmf.content.listing.modals.hostfully', [
                'model' => self::NAME,
                'oItem' => $oItem,
                'aLeads' => $aLeads,
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
//        if ($id === 0) {
//            (new \App\Services\Hostfully\Webhooks\Destroy())->__invoke($webhookUid);
//            return responseCommon()->success();
//        }
        ///** @var Listing $oItem */
        //$oItem = $this->findByClass($this->class, $id);

        if ($setEnabled) {
            (new \App\Services\Hostfully\Webhooks\Store())->agency($type);
        } else {
            (new \App\Services\Hostfully\Webhooks\Destroy())->__invoke($webhookUid);
        }
        if ($id === 0) {
            return responseCommon()->success();
        }
        return $this->actionDevHostfullyWebhooks($request, $id);
    }
}
