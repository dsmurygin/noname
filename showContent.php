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
$content ['eof'] = $data->eof;
$content ['found'] = $data->found;

if (isset($_REQUEST['author']) && empty($_REQUEST['author'])){
    ob_start();
    include __DIR__ . '/engine/view/listAuthors.php';
    $content['content'] = ob_get_contents();
    ob_end_clean();
}

else if (isset($_REQUEST['voice']) && empty($_REQUEST['voice'])){
    ob_start();
    include __DIR__ . '/engine/view/listVoices.php';
    $content['content'] = ob_get_contents();
    ob_end_clean();
}

else if (isset($_REQUEST['publisher']) && empty($_REQUEST['publisher'])){
    ob_start();
    include __DIR__ . '/engine/view/listPublishers.php';
    $content['content'] = ob_get_contents();
    ob_end_clean();
}

else if (isset($_REQUEST['book']) && !isset($_REQUEST['onPageLoad'])){
    ob_start();
    include __DIR__ . '/engine/view/book.php';
    $content['content'] = ob_get_contents();
    ob_end_clean();
}

else if (isset($_REQUEST['book']) && isset($_REQUEST['onPageLoad'])){
    ob_start();
    include __DIR__ . '/engine/view/middlePost.php';
    $content['content'] = ob_get_contents();
    ob_end_clean();
}

else{
    ob_start();
    include __DIR__ . '/engine/view/content.php';
    $content['content'] = ob_get_contents();
    ob_end_clean();
}

if (isset($INDEX)){
    //return $content;
}else{
    echo json_encode($content);
}
