<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers\Api\User;

use App\Models\Amenity;
use App\Models\Listing;
use App\Models\User;
use App\Services\Image\ImageType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use DatabaseTransactions;

    const URL = '/user';

    /**
     *
     */
    public function test()
    {
        $oUser = $this->factoryGuest();
        // проверка доступа
        $response = $this->apiGet(self::URL, [], $oUser);
        $this->assertTrue($response['success']);
        $this->assertFalse($response['data']['has_image']);

        $type = ImageType::MODEL;
        imageUploadUser(storage_path('tests/default.jpg'), $oUser, $type);

        // проверка доступа
        $response = $this->apiGet(self::URL, [], $oUser);
        $this->assertTrue($response['success']);
        $this->assertTrue($response['data']['has_image']);
    }

    /**
     *
     */
    public function testUserNotifications()
    {
        $oUser = $this->factoryGuest();
        $this->assertEmpty($oUser->routeNotificationForApn());
        $this->assertEmpty($oUser->routeNotificationForFcm());
    }
}
