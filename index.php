<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$INDEX = true;

require_once __DIR__ . '/function.php';
db::connect();

$categories = getAllCategories();
$top20 = getTop20();
$db = new db();
$db->className = 'post';
$content = include('showContent.php');


include __DIR__ . '/engine/view/index.php';