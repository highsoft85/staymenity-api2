<b>Оплата бронирования для iOS</b>
<br>
После перехода на эту страницу кидать запрос `/user/payments/stripe/ephemeral` для получения ephemeral key этого юзера<br>
<br>
После выбора карты кидать запрос с `payment_method_id`
<br>
<br>
<b>Оплата бронирования для WEB</b>
<br>
После перехода на эту страницу кидать запрос `/user/payments/cards` для получения всех карт<br>
<br>
<b>Карты есть:</b><br>
• Выводить список карт как в `Payment method`<br>
• Ниже кнопка с `Add payment method`, по клику на которую открывается форма для ввода карты и адреса<br>
<br>
<b>Карт нет:</b><br>
• Сразу показывать форму для ввода карты с адресом<br>
<br>
Из доки страйпа <a href="https://stripe.com/docs/js/payment_methods/create_payment_method" target="_blank">https://stripe.com/docs/js/payment_methods/create_payment_method</a> можно посмотреть в админке по `/dev/stripe` <br>
<br>
<b>Получение нового метода оплаты по этапам:</b><br>
• после сабмита карты получаем токен карты <code>stripe.createToken</code><br>
• после получения токена кидаем <code>stripe.createPaymentMethod</code>, туда можно и передать адрес в <code>billing_details</code><br>
• после id метода оплаты кидаем запрос на оплату брони <code>/user/reservations/:id/payment</code><br>
<br>
<b>Выбрали существующую карту:</b><br>
• `payment_method_id` - передавать только `payment_method_id` выбранной карты<br>
<br>
<b>Ввели новую карту:</b><br>
• Stripe в тестовом режиме разрешает использовать карту <b>4242 4242 4242 4242</b> дата будущая, `cvc` любой.
<br>
Из этих данных мне нужен: <br>
• `payment_method_id` - передавать как `payment_method_id` <br>
<br>



