<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Mail\Auth\ResetPasswordMail;
use App\Mail\TestMail;
use App\Notifications\User\TestNotification;
use App\Services\Firebase\FirebaseCounterNotificationsService;
use App\Services\Geocoder\GeocoderCitiesService;
use App\Services\Geocoder\GeocoderTimezoneService;
use App\Services\HealthCheck\Hostfully;
use App\Services\Notification\Nexmo\NexmoSendNotification;
use App\Services\Socialite\AppleAccountService;
use App\Services\Socialite\GoogleAccountService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

/**
 * Class ServicesTest
 * @package Tests\Unit\Services
 *
 * Исключается в phpunit.xml
 */
class ServicesTest extends TestCase
{
    /**
     * @group google
     * @group no_deploy
     */
    public function testGoogleMaps()
    {
        $data = (new GeocoderCitiesService())->address('New York');
        $this->assertNotEmpty($data);

        $this->assertEquals('New York', $data[0]['title']);
        $this->assertEquals('New York, NY, USA', $data[0]['description']);
        $this->assertEquals('ChIJOwg_06VPwokRYv534QaPC8g', $data[0]['place_id']);
    }

    /**
     * @group google
     * @group no_deploy
     */
    public function testGoogleMapsPlace()
    {
        //$data = (new GeocoderCitiesService())->place('EkNWYWxsZXkgVmlldyBBdmVudWUsIFlvcmJhIExpbmRhLCBDQSA5Mjg4NiwgVW5pdGVkIFN0YXRlcyBvZiBBbWVyaWNhIi4qLAoUChIJZU7yhArU3IARCmrn-Pge65sSFAoSCYNvRyrR09yAEYqwl2jaMd-A');
        //dd($data);
        $data = (new GeocoderCitiesService())->place('ChIJOwg_06VPwokRYv534QaPC8g');
        $this->assertNotEmpty($data);
    }

    /**
     * @group google
     * @group no_deploy
     */
    public function testGoogleTimezoneApi()
    {
        $result = (new GeocoderCitiesService())->timezoneByPlace('EkNWYWxsZXkgVmlldyBBdmVudWUsIFlvcmJhIExpbmRhLCBDQSA5Mjg4NiwgVW5pdGVkIFN0YXRlcyBvZiBBbWVyaWNhIi4qLAoUChIJZU7yhArU3IARCmrn-Pge65sSFAoSCYNvRyrR09yAEYqwl2jaMd-A');
        $this->assertEquals('America/Los_Angeles', $result);

        // требует Google Timezone API
        //$result = (new GeocoderTimezoneService())->byCoordinates(coordinatesLosAngeles());
        //$this->assertNotNull($result);
        //$this->assertEquals('America/Los_Angeles', $result);
    }

    /**
     * @group nexmo
     * @group no_deploy
     */
    public function testNexmo()
    {
        $response = Http::get('https://rest.nexmo.com/account/get-balance', [
            'api_key' => config('nexmo.api_key'),
            'api_secret' => config('nexmo.api_secret'),
        ]);
        $this->assertEquals(200, $response->status());

        $aBody = json_decode($response->body(), true);
        dd($aBody);
        $this->assertArrayHasKey('value', $aBody);
        $this->assertArrayHasKey('autoReload', $aBody);
    }

    /**
     * @group nexmo
     * @group no_deploy
     */
    public function testNexmoSend()
    {
        // 13474475089
        (new NexmoSendNotification())->code('13474475089', '123456');
        $this->assertTrue(true);
    }

    /**
     * @group stripe
     * @group no_deploy
     */
    public function testStripe()
    {
        $method = $this->factoryStripePaymentMethod();
        $this->assertNotNull($method);
    }

    /**
     * @group firebase
     * @group no_deploy
     */
    public function testFirebase()
    {
        //(new FirebaseCounterNotificationsService())->database()->clearCounter();

        $oUser = $this->factoryUser();
        $response = $this->apiGet('/user/notifications', [], $oUser);
        $this->assertTrue($response['success']);
        $this->assertEmpty($response['data']);

        $oUser->notify(new TestNotification());

        $response = $this->apiGet('/user/notifications', [], $oUser);
        $this->assertTrue($response['success']);
        $this->assertNotEmpty($response['data']);

        $response = $this->apiGet('/user/notifications', [], $oUser);
        $this->assertTrue($response['success']);
        $this->assertEmpty($response['data']);

        (new FirebaseCounterNotificationsService())->database()->setUser($oUser)->clearUserCounter();
    }

    /**
     * @group sentry
     * @group no_deploy
     */
    public function testSentry()
    {
        $this->artisan('sentry:test')
            ->expectsOutput('[sentry] Client DSN discovered!')
            ->assertExitCode(0);
    }

    /**
     * @group mail
     * @group no_deploy
     */
    public function testMail()
    {
        try {
            Mail::to('dmitry.poskachey@ag.digital')->send((new TestMail()));
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->assertTrue(false, $e->getMessage());
        }
    }

    /**
     * @group no_deploy
     */
    public function testHealthCheckHostfully()
    {
        $this->assertFalse((new Hostfully())->isActive());
        (new Hostfully())->check();
        $data = Cache::get(Hostfully::CACHE_NAME);

        $this->assertArrayHasKey('status', $data);
        $this->assertArrayHasKey('time', $data);
        $this->assertTrue((new Hostfully())->isActive());
    }
}
