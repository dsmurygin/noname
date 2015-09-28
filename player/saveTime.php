<?php
session_start();
if (!isset($_SESSION["user"])){
    exit;
}
include "../dbConnect.php";
$query = $link->query('SELECT id FROM users_saves WHERE id_user='.$_SESSION["user"].' AND id_book = '.$_REQUEST["book"]);
if ($query->num_rows == 1) {
    $link->query('UPDATE users_saves SET time ="'.$_REQUEST["time"].'" WHERE id_user="' . $_SESSION["user"] . '" AND id_book='.$_REQUEST["book"]);
    echo true;
}
