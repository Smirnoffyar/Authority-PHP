<?php
    // Страница авторизации

    // Функция для генерации случайной строки
    function generateCode ($length=6) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .=$chars[mt_rand(0,$clen)];
        }
        return $code;
    }

    // Соединяемся с БД
    $link = mysqli_connect("localhost", "root", "", "testtable");

    if(isset($_POST['submit']))
    {
        // Ищем в БД запись, у которой логин соответствует введённому
        $query = mysqli_query($link, "SELECT user_id, user_password FROM users WHERE user_login='".mysqli_real_escape_string($link, $_POST['login'])."' LIMIT 1");
        $data = mysqli_fetch_assoc($query);

        // Сравниваем пароли
        if($data['user_password'] === md5(md5($_POST['password'])))
        {
            // Генерируем случайное число и шифруем его
            $hash = md5(generateCode(10));

            if(!empty($_POST['not_attach_ip']))
            {
                // если пользователь выбрал привязку к IP
                // переводим IP пользователя в строку
                $insip = ", user_ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')";
            }
            // Записываем в БД новый хеш авторизации и IP
            mysqli_query($link, "UPDATE users SET user_hash='".$hash."'
            ".$insip."WHERE user_id='".$data['user_id']."'");
            // Определяем cookie
            setcookie("id", $data['user_id'], time()+60*60*24*30, "/");
            setcookie("hash", $hash, time()+60*60*24*30, "/", null, null, true);

            // Переадресовываем браузер на страницу проверки
            header("Location: check.php"); exit();
        }
        else {
            print "Вы ввели неправильный логин/пароль";
        }
    }
?>
<h1>Страница авторизации</h1>
<form method="POST">
    Логин <input type="text" name="login" required><br>
    Пароль <input type="password" name="password" required><br>
    Не прикреплять к IP (небезопасно) <input type="checkbox" name="not_attach_ip"><br>
    <input type="submit" value="Войти" name="submit">
</form>
<br> <a href="index.php">Вернуться на главную</a>