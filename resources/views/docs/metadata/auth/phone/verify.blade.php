<b>Регистрация</b><br>
Для `type={{ \App\Models\PersonalVerificationCode::TYPE_REGISTRATION }}` будет только подтверждение телефона <br>
<br>
<b>Логин</b><br>
Для `type={{ \App\Models\PersonalVerificationCode::TYPE_LOGIN }}` в ответе вернет `token` для входа юзера <br>
<br>
<b>Смена номера</b><br>
Для `type={{ \App\Models\PersonalVerificationCode::TYPE_CHANGE }}` будет только подтверждение телефона <br>
<br>
<b>Подтверждение для брони</b><br>
Для `type={{ \App\Models\PersonalVerificationCode::TYPE_RESERVATION }}` будет только подтверждение телефона <br>
<br>
{{--<b>Подтверждение, когда раньше не был подтвержден</b><br>--}}
{{--Для `type={{ \App\Models\PersonalVerificationCode::TYPE_VERIFY }}` только для подтверждения телефона, когда раньше он не был подтвержден <br>--}}
{{--<br>--}}
