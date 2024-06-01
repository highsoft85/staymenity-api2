Список отсортирован по дате, новые вверху<br>
<br>
Список данных в <code>extend</code> по типу:<br>
<br>
Тип <b>{{ \App\Notifications\User\LeaveReviewNotification::NAME }}</b><br>
• <code>image</code> Аватарка<br>
• <code>reservation_id</code> ID брони<br>
<br>
Тип <b>{{ \App\Notifications\User\HaveNewMessageNotification::NAME }}</b><br>
• <code>title</code> Имя пользователя<br>
• <code>image</code> Аватарка<br>
• <code>chat_id</code> ID чата<br>
<br>
Тип <b>{{ \App\Notifications\User\Identity\UserIdentityVerificationStatusNotification::NAME }}</b><br>
Если пришел этот тип, то обновлять юзера, для iOS проверяется по Push, на вебе пока никак
<br>
Для остальных типо отображать только <code>message</code> и дату
