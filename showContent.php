<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once __DIR__ . '/function.php';
db::connect();
$data = new content;

$content = [];
$content ['title'] = $data->title;
$content ['description'] = $data->description;
$content ['keywords'] = $data->keywords;

if (isset($INDEX)){
    ob_start();
    if (isset($_REQUEST['book'])){
        include __DIR__ . '/engine/view/book.php';
    }
    else {
        include __DIR__ . '/engine/view/content.php';
    }
    $content['content'] = ob_get_contents();
    ob_end_clean();
    return $content;
}
else{
    if (isset($_REQUEST['book'])) {
        ob_start();
        include __DIR__ . '/engine/view/book.php';
        $content['content'] = ob_get_contents();
        ob_end_clean();
    }
    else{
        ob_start();
        include __DIR__ . '/engine/view/content.php';
        $content['content'] = ob_get_contents();
        ob_end_clean();
    }
    echo json_encode($content);
}