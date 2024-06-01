<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Events\Auth\RegisteredEvent;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Models\PersonalVerificationCode;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Rules\UserPhoneUnique;
use App\Services\Model\UserServiceModel;
use App\Services\Verification\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;

class RegisterController extends AuthController
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse|array
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        if (isset($data['phone'])) {
            $oService = (new UserServiceModel());
            if (!$oService->checkPhoneUnique($data['phone'])) {
                return responseCommon()->validationMessages(null, [
                    'phone' => __('validation.unique', ['attribute' => 'phone']),
                ]);
            }

            $phone = $this->getPhoneByDataSendVerify($data);
            if (!$this->checkPhone($phone)) {
                return responseCommon()->validationMessages(null, [
                    'phone' => __('validation.phone', ['attribute' => 'phone']),
                ]);
            }
        }
        $oUser = $this->create($data);
        return $this->registered($data, $oUser);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data): User
    {
        $oUser = (new UserServiceModel())->create($data);

        if (isset($data['role'])) {
            $role = $data['role'];
            if (in_array($role, [User::ROLE_HOST, User::ROLE_GUEST])) {
                (new UserServiceModel($oUser))->setCurrentRole($role);
            }
        } else {
            // @todo различать какую роль назначать
            $oUser->assignRole(User::ROLE_GUEST);
        }
        return $oUser;
    }

    /**
     * @param array $data
     * @param User $oUser
     * @return JsonResponse|array
     */
    protected function registered(array $data, User $oUser)
    {
        if (!is_null($oUser->phone) && isset($data['phone_verified']) && (int)$data['phone_verified'] === 1) {
            $oUser->update([
                'phone_verified_at' => now(),
                'register_by' => User::REGISTER_BY_PHONE,
            ]);
        }
        $oService = (new UserServiceModel($oUser));

        $oService->saveUserTimezoneByIp();
        $oService->afterRegister();

        $this->guard()->login($oUser);

        $token = $this->getToken($oUser);
        return responseCommon()->apiDataSuccess([
            'token' => $token,
        ], __('auth.register.success'));
    }
}
