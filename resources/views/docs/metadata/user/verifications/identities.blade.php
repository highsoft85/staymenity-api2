<b>Создание верификации</b>
<br>
Верификация происходит через autohost по api http://developer.autohost.ai/<br>
<br>
Отправление происходит сразу 3х(для прав) или 2х(для паспорта) фотографий, на последнем шаге.<br>
<br>
<b>После отправки на добавение:</b>
<br>
• Создается бронь. туда пишется имя/фамилия/email/phone юзера, first_name и last_name <b>ОБЯЗАТЕЛЬНЫ</b> на стороне хоста, т.е. желательно перед запросом проверить есть ли они<br>
• Загружается фронтальная фотография на сервер и потом на autohost<br>
• Загружается обратная фотография на сервер и потом на autohost<br>
• Загружается селфи на сервер и потом на autohost<br>
• Проверка статуса<br>
<br>
Если все выполнелось успешно, то перезагрузить пользователя<br>
<br>
<b>Возможные ошибки:</b>
<br>
• Ошибка валидации изображения, стоит лимит на 5мб для файла и он должен быть изображением, если ошибка, то придет <code>errors.image_front: 'text'</code><br>
• Ошибка из autohost, может быть все что угодно и если ошибка, то придет <code>errors.image_front: 'text'</code><br>
<br>
Чтобы отправить запрос и получить ошибку типа <code>errors.image_front: 'text'</code>, в запросе на создание необходимо добавить <code>example_error=1</code>, необходимо только для отладки,
т.к. автохост одну может принять, а другую нет, вариантов перейти на конкретный шаг - нет, загрузка потом начинается сначала
<br>
<br>
Так же возможна загрузка поотдельности, см. <code>api/user/verifications/identities/{id}/{step}/upload</code><br>
<b>Виды step:</b><br>
• <code>{{ \App\Models\UserIdentity::STEP_FRONT }}</code><br>
• <code>{{ \App\Models\UserIdentity::STEP_BACK }}</code><br>
• <code>{{ \App\Models\UserIdentity::STEP_SELFIE }}</code><br>
<br>
<b>Возможные ошибки:</b>
<br>
• Ошибка валидации изображения, стоит лимит на 5мб для файла и он должен быть изображением, если ошибка, то придет <code>errors.image: 'text'</code><br>
• Ошибка из autohost, может быть все что угодно и если ошибка, то придет <code>errors.image: 'text'</code><br>
<br>
Все тексты ошибок можно будет увидеть в <code>user.identity_verification_status.errors</code>, если не пустой, то вывести что есть<br>
<br>
<b>Возможные ключи в errors:</b><br>
• <code>{{ \App\Models\UserIdentity::STEP_FRONT }}</code><br>
• <code>{{ \App\Models\UserIdentity::STEP_BACK }}</code><br>
• <code>{{ \App\Models\UserIdentity::STEP_SELFIE }}</code><br>
• <code>status</code> - общий статус, если все фотографии успешно загружены, но потом на проверке что-то пришло<br>
<br>
<b>Статусы в identity_verification_status:</b><br>
• <code>{{ \App\Models\UserIdentity::staticStatusIcons()[\App\Models\UserIdentity::STATUS_NOT_VERIFIED]['name'] }}</code> - {{ \App\Models\UserIdentity::staticStatuses()[\App\Models\UserIdentity::STATUS_NOT_VERIFIED] }}<br>
• <code>{{ \App\Models\UserIdentity::staticStatusIcons()[\App\Models\UserIdentity::STATUS_PENDING]['name'] }}</code> - {{ \App\Models\UserIdentity::staticStatuses()[\App\Models\UserIdentity::STATUS_PENDING] }}<br>
• <code>{{ \App\Models\UserIdentity::staticStatusIcons()[\App\Models\UserIdentity::STATUS_SUCCESS]['name'] }}</code> - {{ \App\Models\UserIdentity::staticStatuses()[\App\Models\UserIdentity::STATUS_SUCCESS] }}<br>
• <code>{{ \App\Models\UserIdentity::staticStatusIcons()[\App\Models\UserIdentity::STATUS_FAILED]['name'] }}</code> - {{ \App\Models\UserIdentity::staticStatuses()[\App\Models\UserIdentity::STATUS_FAILED] }}<br>
<br>


