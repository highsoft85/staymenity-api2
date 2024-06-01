Одно поле phone, скрытое поле или как удобно phone_verified=user.is_phone_verified ? 1: 0, и кнопка Save
1. У пользователя есть phone, пишем туда phone
2. Если новый введенный phone !== текущему, то показываем кнопку Verify
3. По клику на кнопку Verify отправляется запрос /auth/phone/code?phone={новый phone}&type=change
4. Если все успешно, то появляется модалка для ввода кода и отправляется запрос на /auth/phone/verify?phone={новый phone}&type=change&code={code}
5. Если все успешно, то кнопка превращается в иконку и Verified
6. Пользователь нажимает Save и отправляется update с phone и с phone_verified=1, теперь update для юзера не может быть с phone и phone_verified=0

1. У пользователя нет phone, то поле пустое
2. Если новый введенный phone !== пустому, то показываем кнопку Verify
3. По клику на кнопку Verify отправляется запрос /auth/phone/code?phone={новый phone}&type=change
4. Если все успешно, то появляется модалка для ввода кода и отправляется запрос на /auth/phone/verify?phone={новый phone}&type=change&code={code}
5. Если все успешно, то кнопка превращается в иконку и Verified
6. Пользователь нажимает Save и отправляется update с phone и с phone_verified=1

И получается, что если у юзера если phone, но у юзера `user.is_phone_verified=false`, то можно сразу показывать кнопку, надеюсь кейса, когда сохранен номер, но не подтвержден - не будет, если будет то сделаю type=verify для быстрого подтверждения
