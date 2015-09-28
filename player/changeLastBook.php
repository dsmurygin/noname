<?php
session_start();
if (isset($_SESSION["user"])){
    include "../dbConnect.php";
    $query = $link->query('SELECT time FROM users_saves WHERE id_user='.$_SESSION["user"].' AND id_book = '.$_REQUEST["book"]);
    if ($query->num_rows == 1) {
        $row = mysqli_fetch_assoc($query);
        echo $row["time"];
        $link->query('UPDATE users SET user_lastbook = '.$_REQUEST["book"].' WHERE user_id = '.$_SESSION["user"]);

    }
}
