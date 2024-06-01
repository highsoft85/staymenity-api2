<?php

declare(strict_types=1);

namespace App\Services\Verification;

use App\Jobs\Auth\SendPhoneCodeJob;
use App\Jobs\QueueCommon;
use App\Models\PersonalVerificationCode;
use App\Models\User;
use App\Services\Environment;

class VerificationService
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var int
     */
    private $lifetimeSeconds;

    /**
     * @var null|User
     */
    private $oUser = null;

    /**
     * VerificationService constructor.
     */
    public function __construct()
    {
        $this->lifetimeSeconds = config('verification.lifetime');
    }

    /**
     * @return $this
     */
    public function registration()
    {
        $this->type = PersonalVerificationCode::TYPE_REGISTRATION;

        return $this;
    }

    /**
     * @return $this
     */
    public function resetConfirmation()
    {
        $this->type = PersonalVerificationCode::TYPE_RESET_CONFIRMATION;

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isLogin()
    {
        return $this->type === PersonalVerificationCode::TYPE_LOGIN;
    }

    /**
     * @param string $phone
     * @return bool
     */
    public function isTesting(string $phone)
    {
        if ($phone === config('verification.test_number')) {
            return true;
        }
        if (!config('nexmo.enabled')) {
            return true;
        }
        if (config('app.env') !== Environment::PRODUCTION) {
            return true;
        }
        return false;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @param User $oUser
     * @return $this
     */
    public function setUser(User $oUser)
    {
        $this->oUser = $oUser;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    private function code()
    {
        $length = config('verification.length');

        return str_pad(
            (string)rand(0, pow(10, $length) - 1),
            (int)$length,
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * @return string
     */
    public function generate()
    {
        $this->code = !$this->isTesting($this->phone) ? $this->code() : config('verification.test_code');
        PersonalVerificationCode::create([
            'user_id' => $this->oUser->id ?? null,
            'type' => $this->type,
            'phone' => $this->phone,
            'code' => $this->code,
            'expires_at' => now()->addSeconds(config('verification.lifetime')),
        ]);
        return $this->code;
    }

    /**
     * @return bool
     */
    public function hasCode()
    {
        $oVerification = $this->getLastCode();
        if (!is_null($oVerification) && !$oVerification->isExpired()) {
            return true;
        }
        return false;
    }

    /**
     * @return PersonalVerificationCode|null
     */
    private function getLastCode()
    {
        $oQuery = PersonalVerificationCode::where('type', $this->type);
        if (!is_null($this->oUser)) {
            $oQuery->where('user_id', $this->oUser->id);
        } else {
            $oQuery->whereNull('user_id');
        }
        return $oQuery
            ->where('phone', $this->phone)
            ->whereNull('verified_at')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * @return bool
     */
    public function canSend()
    {
        $oVerification = $this->getLastCode();
        if (is_null($oVerification)) {
            return true;
        }
        // если дата создание + лайфтайм < текущего, т.е. сейчас время больше выделенного под автивацию
        if ($oVerification->created_at->addSeconds($this->lifetimeSeconds) < now()) {
            return true;
        }
        return false;
    }

    /**
     * @return int|null
     */
    public function getWaitSeconds()
    {
        $oVerification = $this->getLastCode();
        if (is_null($oVerification)) {
            return null;
        }
        if ($oVerification->created_at->addSeconds($this->lifetimeSeconds) > now()) {
            return $oVerification->created_at->addSeconds($this->lifetimeSeconds)->diffInSeconds(now());
        }
        return null;
    }

    /**
     * @param string $phone
     * @param string $code
     * @return PersonalVerificationCode|null
     */
    public function get(string $phone, string $code)
    {
        $oQuery = PersonalVerificationCode::where('type', $this->type);
        if (!is_null($this->oUser)) {
            $oQuery->where('user_id', $this->oUser->id);
        } else {
            $oQuery->whereNull('user_id');
        }

        /** @var PersonalVerificationCode|null $oVerification */
        $oVerification = $oQuery
            ->where('phone', $phone)
            ->where('code', $code)
            ->orderBy('created_at', 'desc')
            ->first();
        return $oVerification;
    }

    /**
     *
     */
    public function send()
    {
        if (QueueCommon::commandSmsIsEnabled()) {
            SendPhoneCodeJob::dispatch(null, $this->phone, $this->code)->onQueue(QueueCommon::QUEUE_NAME_SMS);
        } else {
            SendPhoneCodeJob::dispatchNow(null, $this->phone, $this->code);
        }
    }

    /**
     * @return PersonalVerificationCode|null
     */
    public function getPhoneVerification()
    {
        $oQuery = PersonalVerificationCode::where('type', $this->type);
        if (!is_null($this->oUser)) {
            $oQuery->where('user_id', $this->oUser->id);
        } else {
            $oQuery->whereNull('user_id');
        }
        /** @var PersonalVerificationCode|null $oVerification */
        $oVerification = $oQuery
            ->where('phone', $this->phone)
            ->whereNotNull('verified_at')
            ->orderBy('created_at', 'desc')
            ->first();
        return $oVerification;
    }
}
