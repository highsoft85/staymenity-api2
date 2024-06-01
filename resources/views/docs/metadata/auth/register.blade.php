<p><b>Логика</b></p>
@include('docs.components.image', [
    'image' => '/docs-images/metadata/auth/register.png',
])
<p><b>Регистрация по телефону</b></p>
<ul>
    <li>Пользователь вводит телефон и отправляет запрос на POST <code>/api/auth/phone/code</code> с параметрами <code>phone={phone}&type=registration</code></li>
    <li>Вводит код и отправляет запрос на POST <code>/api/auth/phone/verify</code> c <code>phone={phone}&code={code}&type=registration</code></li>
    <li>Если 200 ответ, то показывать форму с заполнением полей http://prntscr.com/vi09eg , все поля <code>required</code></li>
    <li>Пользователь заполнил все поля и отправляет запрос на POST <code>/api/auth/register</code> параметрами в вскрытом виде <code>phone={phone}&phone_verified=1</code></li>
    <li>Если ответ 200, <code>success=true</code> и есть в data <code>token</code>, то сохранять его и кидать запрос на <code>/api/user</code></li>
</ul>
<p><b>Регистрация по email</b></p>
<ul>
    <li>Пользователь переходит на форму http://prntscr.com/vi0al7 все поля <code>required</code></li>
    <li>Вводит все данные и отправляет запрос на POST <code>/api/auth/register</code></li>
    <li>Если ответ 200 и есть в data <code>token</code>, то сохранять его и кидать запрос на <code>/api/user</code></li>
    <li>Сразу же после успешного ответа отправлять <code>/api/auth/phone/code</code> с параметрами <code>phone={phone}&type=registration</code>, если успешно, то показывать форму для ввода кода с таймером</li>
    <li>Вводит код и отправляет запрос на POST <code>/api/auth/phone/verify</code> c параметрами <code>user_id={id}&code={code}&type=registration</code></li>
    <li>Если 200 ответ, то можно опять сделать <code>/api/user</code> и удостовериться что все нормально</li>
</ul>
