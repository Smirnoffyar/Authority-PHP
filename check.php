<?php
    // Скрипт проверки
    // Соединяемся с БД
    $link=mysqli_connect("localhost", "root", "", "testtable");
    if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
    {
        $query=mysqli_query($link, "SELECT *, INET_NTOA(user_ip) AS user_ip FROM
        users WHERE user_id='".intval($_COOKIE['id'])."' LIMIT 1");
        $userdata = mysqli_fetch_assoc($query);

        if (($userdata['user_hash'] !== $_COOKIE['hash']) or ($userdata['user_id'] !== $_COOKIE['id'])
        or (($userdata['user_ip'] !== $_SERVER['REMOTE_ADDR']) and 
        ($userdata['user_ip'] !=="0")))
            {
            setcookie("id", $data['user_id'], time()-60*60*24*30, "/");
            setcookie("hash", $hash, time()-60*60*24*30, "/", null, null, true); 
            print "Что-то пошло не так)";
            }
            else {
                print "Привет, ".$userdata['user_login'].". Добро пожаловать в личный кабинет!";
            }
    }
    else {
        print "Включите куки в браузере!";
    }

?>
<hr>
<br><br><br><br>
<form action="logout.php">
    <input type="submit" value="Выход">
</form>