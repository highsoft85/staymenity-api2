<?php

declare(strict_types=1);

namespace App\Cmf\Project;

use App\Cmf\Core\MainController;
use App\Http\Controllers\Controller;
use App\Jobs\QueueCommon;
use App\Models\Device;
use App\Models\Listing;
use App\Models\User;
use App\Services\Database\RedisRateService;
use App\Services\Hostfully\BaseHostfullyService;
use App\Services\Hostfully\HostfullyLeadsService;
use App\Services\Hostfully\Models\Webhooks;
use App\Services\Hostfully\Webhooks\Index;
use App\Services\Model\ReservationServiceModel;
use App\Services\Toastr\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Tests\FactoryModelTrait;

class DevController extends Controller
{
    use FactoryModelTrait;

    /**
     * @param Request $request
     * @param string $name
     * @return void
     */
    public function php(Request $request, string $name)
    {
        $method = 'php' . Str::title($name);
        method_exists($this, $method)
            ? $this->{$method}()
            : abort(500, 'Method ' . $method . ' not found');
    }

    /**
     * @param Request $request
     * @param string $name
     * @return \Illuminate\Http\RedirectResponse
     */
    public function command(Request $request, string $name): \Illuminate\Http\RedirectResponse
    {
        $command = $name;

        $aCommand = explode(' ', $command);
        foreach ($aCommand as $key => $option) {
            if (Str::startsWith($option, '--')) {
                unset($aCommand[$key]);
            }
        }
        if (!isset($aCommand[0])) {
            return redirect()->back();
        }

        $this->setCommand($command, $this->commandExists($aCommand[0]));

        return redirect()->back();
    }


    /**
     * @return \Illuminate\View\View
     */
    private function phpInfo()
    {
        return view('cmf.components.dev.phpinfo');//response()->make('', 200);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pages()
    {
        return view('cmf.components.dev.pages');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stripe()
    {
//        \Stripe\Stripe::setApiKey(config('services.stripe.test_secret_key'));
//        $customer = \Stripe\Customer::create();
//
//        \Stripe\Stripe::setApiKey(config('services.stripe.test_secret_key'));
//
//        $intent = \Stripe\PaymentIntent::create([
//            'amount' => 1099,
//            'currency' => 'usd',
//            'customer' => $customer->id,
//        ]);
        return view('cmf.components.dev.stripe', [
            //'intent' => $intent,
        ]);
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function stripePost(Request $request)
    {
        $method = $request->get('payment_method_id');

        $oHost = Auth::user();
        $oHost = User::find($oHost->id);
        $oListing = $this->factoryUserListingActive($oHost);

        $oGuest = $this->factoryGuest();
        $oReservation = $this->factoryReservationListingFromUser($oListing, $oGuest);

        $oReservationService = (new ReservationServiceModel($oReservation));
        // совершение платежа
        $result = transaction()->commitAction(function () use ($oReservationService, $oGuest, $oHost, $method) {
            $oPayment = $oReservationService->paymentByMethod($oGuest, $oHost, $method);
        });
        if (!$result->isSuccess()) {
            return responseCommon()->apiErrorBadRequest([], $result->getErrorMessage());
        }
        return responseCommon()->apiDataSuccess([], 'Payment successful');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function push()
    {
        return view('cmf.components.dev.push');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function auth()
    {
        return view('cmf.components.dev.auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function keys()
    {
        return view('cmf.components.dev.keys');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function firebase()
    {
        return view('cmf.components.dev.firebase');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notifications()
    {
        return view('cmf.components.dev.notifications');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pushToken(Request $request)
    {
        /** @var User $oUser */
        $oUser = Auth::user();
        $token = $oUser->devices()
            ->where('token', $request->get('token'))
            ->where('type', Device::TYPE_WEB)
            ->first();
        if (is_null($token)) {
            $oUser->devices()->create([
                'token' => $request->get('token'),
                'type' => Device::TYPE_WEB,
            ]);
        }
        return response()->json(['token saved successfully.']);
    }

    /**
     * @param Request $request
     */
    public function pushSend(Request $request)
    {
        /** @var User $oUser */
        $oUser = Auth::user();
        $firebaseToken = $token = $oUser->devices()
            ->pluck('token')->toArray();

        $SERVER_API_KEY = env('FIREBASE_API_KEY');

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ],
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function developer()
    {
        if (Session::exists(MainController::MODE_DEVELOPER)) {
            Session::forget(MainController::MODE_DEVELOPER);
        } else {
            Session::put(MainController::MODE_DEVELOPER, 1);
        }
        return redirect()->back();
    }

    /**
     * @param string $name
     * @return bool
     */
    private function commandExists(string $name)
    {
        return Arr::has(Artisan::all(), $name);
    }

    /**
     * @param string $name
     * @param bool $exists
     */
    private function setCommand(string $name, bool $exists): void
    {
        if ($exists) {
            Artisan::call($name);
            (new Toastr('Command php artisan ' . $name . ' run success.'))->success(false);
        } else {
            (new Toastr('Command php artisan ' . $name . ' not found.'))->error(false);
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function queue()
    {
        $aCommands = (new QueueCommon())->commands();

        foreach ($aCommands as $key => $command) {
            $aCommands[$key] = [
                'active' => QueueCommon::commandIsEnabled($key),
                'command' => config('cmf.php_alias') . ' artisan ' . $command,
            ];
        }

        return view('cmf.components.dev.queue', [
            'aCommands' => $aCommands,
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function hostfully()
    {
        $data = [
            'rater' => (new RedisRateService('hostfully', 1000))->get(),
        ];

        $aWebhookEvents = [
            Webhooks::EVENT_TYPE_NEW_BOOKING,
            Webhooks::EVENT_TYPE_BOOKING_UPDATED,
            Webhooks::EVENT_TYPE_BOOKING_CANCELLED,
            Webhooks::EVENT_TYPE_NEW_BLOCKED_DATES,
            Webhooks::EVENT_TYPE_NEW_INQUIRY,
            Webhooks::EVENT_TYPE_NEW_PROPERTY,

            Webhooks::EVENT_TYPE_CHANNEL_ACTIVATED,
            Webhooks::EVENT_TYPE_CHANNEL_DEACTIVATED,
            Webhooks::EVENT_TYPE_INTEGRATION_ACTIVATED,
            Webhooks::EVENT_TYPE_INTEGRATION_DEACTIVATED,
        ];

        //$aWebhooks = (new Index())->__invoke();
        //$cWebhooks = collect($aWebhooks)->toArray();
        $cWebhooks = [];
        $aWebhooks = [];

        if (healthCheckHostfully()->isActive()) {
            $aWebhooks = (new Index())->__invoke(null, [
                'integrationWebhooks' => true,
            ]);
            $aWebhooks = collect($cWebhooks)->merge($aWebhooks)->keyBy('eventType')->toArray();
        }

        return view('cmf.components.dev.hostfully', [
            'data' => $data,
            'aWebhooks' => $aWebhooks,
            'aWebhookEvents' => $aWebhookEvents,
        ]);
    }
}
