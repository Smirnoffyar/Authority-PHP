<?php
// Разавторизация пользователя
// Удаляем cookie
setcookie("id", $data['user_id'], time()-60*60*24*30, "/");
setcookie("hash", $hash, time()-60*60*24*30, "/", null, null, true); 

// Переадресуем браузер на главную страницу

header("Location: /"); exit;
?>