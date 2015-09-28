<?php
session_start();
if (!isset($_SESSION["user"])){
    echo "Вы не авторизированны";
    exit;}
include "../dbConnect.php";
$query = $link->query('SELECT id FROM users_saves WHERE id_book = '.$_REQUEST["book"].' AND id_user = '.$_SESSION["user"]);
if ($query->num_rows ==0) {
    $link->query('INSERT INTO users_saves(id_book,id_user) VALUES (' . $_REQUEST["book"] . ',' . $_SESSION["user"] . ')');
    $query = $link->query('SELECT id FROM users_saves WHERE id_user = ' . $_SESSION['user']);
    $saves = $query->num_rows;
    $link->query('UPDATE users SET user_lastbook = '.$_REQUEST["book"].', user_saves = '.$saves.' WHERE user_id = '.$_SESSION["user"]);
    echo "Добавленно";
}
else{
    echo "Уже добавленно";
}