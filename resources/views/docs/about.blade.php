<p>
    <b>Роли</b>
</p>
<ul>
    <li>
        <code>{{ \App\Models\User::ROLE_ADMIN }}</code> - Админ
    </li>
{{--    <li>--}}
{{--        <code>{{ \App\Models\User::ROLE_MANAGER }}</code> - Менеджер--}}
{{--    </li>--}}
{{--    <li>--}}
{{--        <code>{{ \App\Models\User::ROLE_OWNER }}</code> - Владелец--}}
{{--    </li>--}}
    <li>
        <code>{{ \App\Models\User::ROLE_HOST }}</code> - Хост
    </li>
    <li>
        <code>{{ \App\Models\User::ROLE_GUEST }}</code> - Гость
    </li>
</ul>
<p>
    <b>Полы</b>
</p>
<ul>
    <li>
        <code>{{ \App\Models\User::GENDER_NOT_TO_SAY }}</code> - Не имеет значение / Другое
    </li>
    <li>
        <code>{{ \App\Models\User::GENDER_MALE }}</code> - Мужчина
    </li>
    <li>
        <code>{{ \App\Models\User::GENDER_FEMALE }}</code> - Женщина
    </li>
</ul>
<p>
    <b>Провайдеры аутентификации через соц.сети</b>
</p>
<ul>
    <li>
        <code>{{ \App\Services\Socialite\GoogleAccountService::NAME }}</code> - Google
    </li>
    <li>
        <code>{{ \App\Services\Socialite\FacebookAccountService::NAME }}</code> - Facebook
    </li>
    <li>
        <code>{{ \App\Services\Socialite\AppleAccountService::NAME }}</code> - Apple
    </li>
</ul>
<p>
    <b>Типы уведомлений, которые могут прийти</b>
</p>
<ul>
    <li>
        <code>{{ \App\Notifications\User\LeaveReviewNotification::NAME }}</code> - Для оставления отзыва после даты бронирования
    </li>
    <li>
        <code>{{ \App\Notifications\User\HaveNewMessageNotification::NAME }}</code> - Новое сообщение от юзера, если он не читает в данный момент этот чат
    </li>
    <li>
        <code>{{ \App\Notifications\User\Reservation\ReservationTransferNotification::NAME }}</code> - Новый трансфер, в начало бронирования
    </li>
    <li>
        <code>{{ \App\Notifications\User\Reservation\ReservationPayoutNotification::NAME }}</code> - Новый вывод, после конца бронирования
    </li>
    <li>
        <code>{{ \App\Notifications\User\Identity\UserIdentityVerificationStatusNotification::NAME }}</code> - Пришел новый статус по верификации личности
    </li>
</ul>
<p>
    <b>Типы устройств для пользователя</b>
</p>
<ul>
    <li>
        <code>{{ \App\Models\Device::TYPE_WEB }}</code> - для веба
    </li>
    <li>
        <code>{{ \App\Models\Device::TYPE_IOS }}</code> - для ios
    </li>
</ul>
<p>
    <b>Email рассылка</b>
</p>
<ul>
    <li>
        <code>{{ \App\Mail\Auth\VerifyAccountMail::NAME }}</code> - после регистрации, для подтверждения email (нельзя отключить)
    </li>
    <li>
        <code>{{ \App\Mail\Auth\ResetPasswordMail::NAME }}</code> - для сброса пароля по email (нельзя отключить)
    </li>
    <li>
        <code>{{ \App\Mail\User\UserHaveNewMessageMail::NAME }}</code> - для юзера, который не прочитал новое сообщение в течении 30 минут (если отключена рассылка, то не отправляется)
    </li>
</ul>
<p>
    <b>Типы верификации личности через autohost</b>
</p>
<ul>
    <li>
        <code>{{ \App\Models\UserIdentity::TYPE_PASSPORT }}</code> - Паспорт
    </li>
    <li>
        <code>{{ \App\Models\UserIdentity::TYPE_DRIVERS }}</code> - Права, или Identity card
    </li>
</ul>
