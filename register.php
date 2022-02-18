<?php
    // Страница регистрации нового пользователя

    // Соединяемся с БД
    $link = mysqli_connect("localhost", "root", "", "testtable"); // ("локальный хост", "имя пользователя БД", "пароль", "название БД")
    
    if(isset($_POST['submit']))
    {
        $err = [];

        // Проверка логина
        if(!preg_match("/^[a-zA-Z0-9]+$/", $_POST['login'])) // Только цифры и буквы английского алфавита
        {
            $err[] = "Логин может состоять только из букв английского алфавита и цифр!";
        }
            if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 32)
            {
                $err[] = "Логин должен быть не менее 3х и не более 32 символов";
            }

        // Проверяем, не существует ли пользователь с таким же именем
        $query = mysqli_query($link, "SELECT user_id FROM users WHERE user_login='".mysqli_real_escape_string($link, $_POST['login'])."'");
        if(mysqli_num_rows($query) > 0)
        {
            $err[] = "Пользователь с таким логином уже существует в бaзе данных!";
        }
        // Если нет ошибок, то добавляем в БД нового пользователя
        if(count($err) == 0)
        {
            $login = $_POST['login'];

            // Убираем лишние пробелы и делаем двойное хеширование
            $password = md5(md5(trim($_POST['password']))); // md5 позволяет хешировать информацию, trim удаляет пробелы в начале и в конце строк
            mysqli_query($link, "INSERT INTO users SET user_login='".$login."', user_password='".$password."'");
            header("Location: login.php");
            exit();
        }
        else {
            print "<b> При регистрации произошли следующие ошибки: </b><br>";
            foreach($err AS $error)
            {
                print $error."<br>";
            }
        }
    }
?>

<form method="POST">
    Логин <input name="login" type="text" required><br>
    Пароль <input name="password" name="password" required><br>
    <input type="submit" name="submit" value="Зарегистрироваться">
</form>
<br> <a href="index.php">Вернуться на главную</a>