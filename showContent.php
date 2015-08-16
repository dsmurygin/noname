<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once __DIR__ . '/function.php';
db::connect();
$data = new content;

if (isset($INDEX)){
    $content = [];
    ob_start();
    include __DIR__ . '/engine/view/content.php';
    $content['content'] = ob_get_contents();
    ob_end_clean();
    return $content;
}
else{
    $content = [];
    ob_start();
    include __DIR__ . '/engine/view/content.php';
    $content['content'] = ob_get_contents();
    ob_end_clean();
    echo json_encode($content);
}