<b>Страница Payouts</b>
<br>
• Если у юзера <code>has_payout_connect=false</code>, то показывать кнопку "Connect" в таком стиле http://prntscr.com/vyvb9a<br>
• Если у юзера <code>has_payout_connect=true</code>, то показывать кнопку "Payout Settings" в том же стиле<br>
<br>
• Если у юзера <code>phone=null || ''</code>, то блокировать кнопку "Connect" и под ней писать "Phone number is required for connect payout account. Please save your phone number in Account Settings(ссылкой на вкладку с телефоном)" <br>
• Если у юзера <code>email=null || ''</code>, то блокировать кнопку "Connect" и под ней писать "E-mail is required for connect payout account. Please save your E-mail in Account Settings(ссылкой на вкладку с email)" <br>
<br>
<b>Поле email и phone обязательны для заполнения при подключении аккаунта</b>
<br>
<b>Подключение вывода средств для хоста</b>
<br>
• По клику на кнопку "Connect" отправлять запрос GET <code>/user/payouts/stripe/connect</code><br>
• В ответе будет <code>data.redirect</code> с ссылкой на продолжение подключения вывода, отправлять юзера на этот url<br>
• Если ошибка, то message выводить под кнопкой<br>
<br>
<b>На бекенде по Connect:</b>
<br>
• Создается Express подключенный акканут для страйпа<br>
• Генерируется ссылка для анбоардинга<br>
• Если юзер был создан, но не закончил подключение, то будет просто сгенерирована ссылка для анбоардинга<br>
<br>
<b>Завершение коннекта</b>
<br>
После успешного подключения на страйпе будет кнопка <code>Submit</code>, по клику на которую будет редирект обратно на сайт<br>
• Ссылка по типу <code>/payout/connect/success?token=::token::</code><br>
• Роут необходимо перехватить и отправить точно такой же запрос на api POST <code>/payout/connect/success?token=::token::</code><br>
• Если никакой ошибки не вернулось, например если токен не верный и юзер по токену на найден, то показывать сообщение из ответа<br>
• Если юзер был авторизован, то после обновить юзера и перенести на страницу Payouts<br>
• На странице вместо "Connect" должна появится кнопка "Payout Settings"<br>
<br>
<b>Переход в личный кабинет</b>
<br>
• По клику на кнопку "Payout Settings" отправлять запрос GET <code>/user/payouts/stripe/dashboard</code><br>
• В ответе будет <code>data.redirect</code> с ссылкой на личный кабинет, отправлять юзера на этот url<br>
<br>
