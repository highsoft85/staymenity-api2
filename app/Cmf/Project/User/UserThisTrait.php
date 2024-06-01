<?php

declare(strict_types=1);

namespace App\Cmf\Project\User;

use App\Cmf\Core\Parameters\TableParameter;
use App\Events\ChangeCacheEvent;
use App\Exceptions\ResourceExceptionValidation;
use App\Models\User;
use App\Services\Model\UserServiceModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

trait UserThisTrait
{
    /**
     * @param Builder $query
     * @return Builder
     */
    protected function thisQuery(Builder $query)
    {
        return $query;//->withTrashed();
        //return $query->where('type', Reservation::TYPE_LISTING);
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function thisCreate(Request $request)
    {
        $data = $request->all();
        $validation = Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ($validation->fails()) {
            return responseCommon()->validationMessages($validation);
        }
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
            if (!(new UserServiceModel())->checkPhoneUnique($data['phone'])) {
                return responseCommon()->validationMessages(null, [
                    'phone' => __('validation.unique', ['attribute' => 'phone']),
                ]);
            }
        }
        $aRoles = Role::whereIn('id', $data['roles'])->pluck('name')->toArray();
        $data['password'] = Hash::make($data['password']);
        $data['login'] = $data['email'];
        $oUser = User::create($data);
        (new UserServiceModel($oUser))->afterCreate();
        $this->saveRelationships($oUser, $request);
        $this->afterChange([], $oUser);
        return responseCommon()->success();
    }

    /**
     * @param Request $request
     * @param object|User $oUser
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function thisUpdate(Request $request, $oUser)
    {
        $secondValidate = $this->validateByData($request, $oUser);
        if (!$secondValidate['success']) {
            return responseCommon()->jsonError([
                'email' => $secondValidate['message'],
            ]);
        }
        $data = $request->all();
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
            if (!(new UserServiceModel($oUser))->checkPhoneUnique($data['phone'])) {
                return responseCommon()->validationMessages(null, [
                    'phone' => __('validation.unique', ['attribute' => 'phone']),
                ]);
            }
        }
        if (isset($data['first_name'])) {
            $birthday = [
                'day' => $request->get('birthday_day'),
                'month' => $request->get('birthday_month'),
                'year' => $request->get('birthday_year'),
            ];
            if ($birthday['day'] !== null && $birthday['month'] !== null && $birthday['year'] !== null) {
                $data['birthday_at'] = Carbon::parse($birthday['day'] . '.' . $birthday['month'] . '.' . $birthday['year']);
            }
        }
        // профиль
        if (isset($request->first_name) && !is_null($request->first_name)) {
            $oUser->update($data);
        }
        $this->saveRelationships($oUser, $request);
        $this->afterChange([], $oUser);
        return responseCommon()->success([
            'name' => $oUser->name,
        ], 'User update successfully.');
    }

    /**
     * @param User $oUser
     * @throws \Exception
     */
    public function thisDestroy(User $oUser)
    {
        $oService = (new UserServiceModel($oUser));
        if ($oService->hasFutureReservationsForListings()) {
            throw new \Exception('Cannot delete account with active future reservations for your listings');
        }
        if ($oService->hasFutureReservations()) {
            throw new \Exception('Cannot delete account with active future reservations');
        }
        $result = (new UserServiceModel($oUser))->delete();
        if (!$result->isSuccess()) {
            slackInfo($result->getErrorMessage(), 'User Delete From Admin');
        }
//
//        if (request()->exists('force') && (int)request()->get('force') === 1) {
////            $result = (new UserServiceModel($oUser))->forceDelete();
////            if (!$result->isSuccess()) {
////                slackInfo($result->getErrorMessage(), 'User Delete From Admin');
////            }
//        } else {
//
//        }
    }

    /**
     * @param User $oUser
     */
    protected function thisAfterChange(User $oUser)
    {
        event(new ChangeCacheEvent('members:user_' . $oUser->id));
    }
}
