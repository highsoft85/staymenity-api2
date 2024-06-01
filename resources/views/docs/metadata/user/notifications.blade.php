<b>Уведомления для пользователя</b>
<br>
Использоваться будет на <b>Firebase Realtime Database</b>, т.е.
на беке идут уведомления в firebase, а на фронте/ios надо будет этот канал прослушивать
<br>
<br>
<b>Пример:</b><br>
• После загрузки авторизации пользователя прослушивать канал <code>{{ (new \App\Services\Firebase\FirebaseCounterNotificationsService())->setUser(\App\Models\User::first())->getChannel() }}</code> <br>
• Если значение <code>null</code> или <code>0</code>, то не выводить индикатор "Есть новые уведомления"<br>
• Если какое-то другое число, то выводить<br>
<br>
После запроса на получение уведомлений, сущностям пропишется дата прочтения и на firebase пойдет сброс (поставится <code>0</code>), т.е. и индикатор сразу исчезнет
<br>
<br>
Все каналы будут у юзера по <code>data.firebase.counter_notifications.channel</code>, если <code>data.firebase.counter_notifications.enabled=true</code>, то включать прослушку
<br>
<b>Список доступных каналов:</b><br>
• <code>{{ (new \App\Services\Firebase\FirebaseCounterNotificationsService())->setUser(\App\Models\User::first())->getChannel() }}</code>
<br>
<br>
Если у пользователя есть канал в <code>data.firebase.last_notification_type</code> и он включен, то прослушивать значение
<br>
Если значение <code>!== null && === {{ \App\Notifications\User\Identity\UserIdentityVerificationStatusNotification::NAME }}</code>, то кинуть запрос POST <code>/api/user/verifications/verified</code> и обновить юзера
<br>
Значение в канале <code>data.firebase.last_notification_type</code> очиститься, будет <code>null</code>
