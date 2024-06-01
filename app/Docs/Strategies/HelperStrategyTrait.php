<?php

declare(strict_types=1);

namespace App\Docs\Strategies;

use Illuminate\Routing\Route;

trait HelperStrategyTrait
{
    // auth
    protected $route_auth_socialite = 'api.auth.socialite.info';
    protected $route_auth_sanctum_facebook = 'sanctum.auth.facebook.callback';
    protected $route_auth_sanctum_google = 'sanctum.auth.google.callback';
    protected $route_auth_sanctum_apple = 'sanctum.auth.apple.callback';

    protected $route_auth_socialite_facebook = 'api.auth.socialite.facebook.callback';
    protected $route_auth_socialite_google = 'api.auth.socialite.google.callback';
    protected $route_auth_socialite_apple = 'api.auth.socialite.apple.callback';

    protected $route_auth_sanctum_mock = 'api.auth.socialite.mock.callback';
    protected $route_auth_sanctum_mock_second = 'api.auth.socialite.mock-second.callback';

    protected $route_auth_login = 'api.auth.login';
    protected $route_auth_register = 'api.auth.register';
    protected $route_auth_forgot_password = 'api.auth.password.email';

    protected $route_auth_verify_failed = 'api.auth.verify.failed';
    protected $route_auth_verify_success = 'api.auth.verify.success';

    protected $route_auth_phone_code = 'api.auth.phone.code';
    protected $route_auth_phone_verify = 'api.auth.phone.verify';
    protected $route_auth_reset_password = 'api.auth.password.reset';

    protected $route_auth_password_phone = 'api.auth.password.phone';

    //protected $route_auth_socialite_google = 'auth.google.redirect';

    protected $route_data = 'api.data';
    protected $route_data_subject = 'api.data.subject';
    protected $route_faq = 'api.faq';
    protected $route_logout = 'api.logout';
    protected $route_feedback = 'api.feedback';
    protected $route_index_host_request = 'api.host-request';
    protected $route_docs = 'api.docs';
    protected $route_keys = 'api.keys';
    protected $route_payout_connect_success = 'api.payout.connect.success';
    protected $route_user = 'api.user.index';
    protected $route_user_destroy = 'api.user.destroy';
    protected $route_user_balance = 'api.user.balance';
    protected $route_user_social_destroy = 'api.user.social.destroy';

    protected $route_user_verifications_identities_store = 'api.user.verifications.identities.store';
    protected $route_user_verifications_identities_update = 'api.user.verifications.identities.update';
    protected $route_user_verifications_identities_show = 'api.user.verifications.identities.show';
    protected $route_user_verifications_identities_destroy = 'api.user.verifications.identities.destroy';
    protected $route_user_verifications_verified = 'api.user.verifications.verified';
    protected $route_user_verifications_identities_step_upload = 'api.user.verifications.identities.step.upload';

    protected $route_user_image_destroy = 'api.user.image.destroy';
    protected $route_user_update = 'api.user.update';
    protected $route_user_settings_notifications_update = 'api.user.settings.notifications.update';
    protected $route_search = 'api.search.index';
    protected $route_search_address = 'api.search.address';
    protected $route_search_place = 'api.search.place';
    protected $route_search_city = 'api.search.city';


    protected $route_listings = 'api.listings.index';
    protected $route_listings_times = 'api.listings.times';
    protected $route_listing = 'api.listings.show';
    protected $route_listings_reviews_index = 'api.listings.reviews.index';
    protected $route_listings_similar = 'api.listings.similar';
    protected $route_listings_store = 'api.listings.store';
    protected $route_listings_update = 'api.listings.update';
    protected $route_listings_image_main = 'api.listings.image.main';
    protected $route_listings_image_destroy = 'api.listings.image.destroy';
    protected $route_listings_chat = 'api.listings.chat';

    protected $route_user_listings = 'api.user.listings.index';
    protected $route_user_listing = 'api.user.listings.show';
    protected $route_user_listings_destroy = 'api.user.listings.destroy';
    protected $route_user_listings_store = 'api.user.listings.store';
    protected $route_user_listings_update = 'api.user.listings.update';
    protected $route_user_listings_image_main = 'api.user.listings.image.main';
    protected $route_user_listings_image_destroy = 'api.user.listings.image.destroy';
    protected $route_user_listings_calendar_index = 'api.user.listings.calendar.index';
    protected $route_user_listings_calendar_update = 'api.user.listings.calendar.update';
    protected $route_user_listings_images_index = 'api.user.listings.images.index';

    protected $route_user_saves_index = 'api.user.saves.index';
    protected $route_user_saves_store = 'api.user.saves.store';
    protected $route_user_saves_show = 'api.user.saves.show';
    protected $route_user_saves_destroy = 'api.user.saves.destroy';

    protected $route_user_notifications_index = 'api.user.notifications.index';
    protected $route_user_notifications_destroy = 'api.user.notifications.destroy';
    protected $route_user_notifications_clear = 'api.user.notifications.clear';

    protected $route_user_devices_index = 'api.user.devices.index';
    protected $route_user_devices_store = 'api.user.devices.store';
    protected $route_user_devices_destroy = 'api.user.devices.destroy';

    protected $route_user_favorites_index = 'api.user.favorites.index';
    protected $route_user_favorites_toggle = 'api.user.favorites.toggle';

    protected $route_reservations_store = 'api.reservations.store';
    protected $route_user_reservations_index = 'api.user.reservations.index';
    protected $route_user_reservations_store = 'api.user.reservations.store';
    protected $route_user_reservations_update = 'api.user.reservations.update';
    protected $route_user_reservations_payment = 'api.user.reservations.payment';
    protected $route_user_reservations_decline = 'api.user.reservations.decline';
    protected $route_user_reservations_cancel = 'api.user.reservations.cancel';
    protected $route_user_reservations_show = 'api.user.reservations.show';

    protected $route_user_reservations_review_index = 'api.user.reservations.review.index';
    protected $route_user_reservations_review_store = 'api.user.reservations.review.store';

    protected $route_user_payments_index = 'api.user.payments.index';
    protected $route_user_reviews_index = 'api.user.reviews.index';

    protected $route_user_payments_stripe_ephemeral = 'api.user.payments.stripe.ephemeral';

    protected $route_user_payouts_index = 'api.user.payouts.index';
    protected $route_user_payouts_stripe_connect = 'api.user.payouts.stripe.connect';
    protected $route_user_payouts_stripe_dashboard = 'api.user.payouts.stripe.dashboard';

    protected $route_user_payments_cards_index = 'api.user.payments.cards.index';
    protected $route_user_payments_cards_store = 'api.user.payments.cards.store';
    protected $route_user_payments_cards_update = 'api.user.payments.cards.update';
    protected $route_user_payments_cards_destroy = 'api.user.payments.cards.destroy';

    protected $route_user_show = 'api.user_show.show';
    protected $route_user_show_reviews_index = 'api.user_show.reviews.index';

    protected $route_host_show = 'api.host.show';
    protected $route_host_reviews_index = 'api.host.reviews.index';

    protected $route_guest_show = 'api.guest.show';
    protected $route_guest_reviews_index = 'api.guest.reviews.index';

    protected $route_user_chats_index = 'api.user.chats.index';
    protected $route_user_chats_store = 'api.user.chats.store';
    protected $route_user_chats_destroy = 'api.user.chats.destroy';

    protected $route_user_chats_messages_index = 'api.user.chats.messages.index';
    protected $route_user_chats_messages_store = 'api.user.chats.messages.store';

    /**
     * @param Route $route
     * @param string $name
     * @return bool
     */
    protected function checkRoute(Route $route, string $name)
    {
        return $route->getName() === $name;
    }

    /**
     * @param Route $route
     * @return mixed
     */
    protected function url(Route $route)
    {
        $url = $route->getAction()['uri'];
        if (substr($url, 0, 1) === '/') {
            $url = substr($url, 1);
        }
        return $url;
    }
}
