<?php
function ClearString($string){
    return trim(htmlspecialchars(stripslashes($string)));
}

if (isset($_REQUEST['login']) && isset($_REQUEST['password'])){
    $login=ClearString($_REQUEST['login']);
    $password=md5($_REQUEST['password']);
    if (!empty($login) && !empty($password)) {
        $query = $link->query('SELECT * FROM users WHERE user_login="' . $login . '" AND user_password="' . $password . '"');
        if ($query->num_rows == 1) {
            $row = mysqli_fetch_assoc($query);
            $_SESSION['user'] = $row['user_id'];
        } else {
            echo 'Неправильный логин или пароль';
            exit;
        }
    }
}

if (isset($_SESSION['user'])){
    $query = $link->query('SELECT * FROM users WHERE user_id = "'.$_SESSION['user'].'"');
    if ($query->num_rows==1){
        $row = mysqli_fetch_assoc($query);
        echo '<form class="userPanel">';
        if (file_exists('userImages/'.$row['user_id'])){
            echo '<img class = userImageHeader src="/userImages/'.$row['user_id'].'">';
        }
        echo '<b>'.$row['user_login'].'</b><br>';
        echo '<p><a>Сообщения: </a><br>';
        echo '<a>Закладки: '.$row['user_bookmark'].'</a><br>';
        echo '<a onclick="showContent(\'saves\',\'\',\'\');return false">Прослушивание: '.$row["user_saves"].'</a></p>';
        echo '</form>';
    }
}

else{
    echo '<form class="userlogin">
                <input type="text"  class="input" id="login" value="Логин" onfocus="clearText(this)" onblur="clearText(this)">
                <br>
                <input type="password" class="input" id="password" value="Пароль" onfocus="clearText(this)" onblur="clearText(this)">
                <br>
                <a onclick="ShowRegForm()">Регистрация</a> | <a>забыли пароль?</a>
                </form>';
}
?>
