<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentationDataSeeder extends Seeder
{
    use \Tests\FactoryModelTrait;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var User $oUser */
        $oUser = User::first();
        /** @var \App\Models\Listing $oListing */
        $oListing = $this->factoryUserListingActive($oUser);
        $oGuest = $this->factoryGuest();
        $oReservation = $this->factoryReservationListingFromUserTomorrow($oListing, $oGuest);
        $oPayment = $this->factoryPayment([
            'user_to_id' => $oUser->id,
        ]);
        $oReservation->update([
            'payment_id' => $oPayment->id,
        ]);
        $oChat = $this->factoryChatByReservation($oReservation);
        $oMessage = $this->factoryChatMessage([
            'user_id' => $oUser->id,
            'chat_id' => $oChat->id,
        ]);


        // моя резервация
        $oReservation = $this->factoryReservationListingFromUserTomorrow($oListing, $oUser);
        $oPayment = $this->factoryPayment();
        $oReservation->update([
            'payment_id' => $oPayment->id,
        ]);

        // верификация акканута
        $this->factoryUserIdentity($oUser);
    }
}
