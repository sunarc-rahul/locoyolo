<?php

include_once("../../config.php");
include_once(INC."/commonfunction.php");
include_once(INC.'/dbfilter.php');
include_once(INC.'/dbqueries.php');
include_once(INC.'/dbhelper.php');

//print_r($_POST);exit;
error_reporting (E_ALL ^ E_NOTICE);

$post = (!empty($_POST)) ? true : false;

if($post) {

    $errorcheck = false;
    $comment_id = $_POST['comment_id'];

         $deleteComment = $DB->DeleteRecord('comments', 'id="' . $comment_id . '"');
if($deleteComment)
{
    echo 'Y';
}else
{
    echo 'N';
}
}else
{
    echo 'N';
}
?>