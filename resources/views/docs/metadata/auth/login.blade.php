<p><b>Вход по телефону</b></p>
<ul>
    <li>Пользователь вводит телефон и отправляет запрос на POST <code>/api/auth/phone/code</code> с параметрами <code>phone={phone}&type=login</code></li>
    <li>Вводит код и отправляет запрос на POST <code>/api/auth/phone/verify</code> c <code>phone={phone}&code={code}&type=login</code> + <code>role={role}</code> в зависимости от окна (guest|host)</li>
    <li>Если все успешно и data есть <code>token</code>, то авторизовывать пользователя в системе</li>
</ul>
<p><b>Вход по email</b></p>
<ul>
    <li>Пользователь вводит <code>email={email}&password={password}</code> в скрытом виде передавать <code>role={role}</code> в зависимости от окна (guest|host)</li>
    <li>После успешного ответа на <code>/api/auth/login</code> в data будет <code>token</code></li>
</ul>
