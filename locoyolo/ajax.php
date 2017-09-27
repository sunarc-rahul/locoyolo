<?php
include_once("config.php");
include_once(INC."/commonfunction.php");
include_once(INC.'/dbfilter.php');
include_once(INC.'/dbqueries.php');
include_once(INC.'/dbhelper.php');

if(isset($_POST['exam_id'])){
//    header('Content-Type: application/json');
    $DB= new DBFilter();
//    $DB->ExecuteQuery("set time_zone = '+5:30'");

    $exam_id = $_POST['exam_id'];
    $exam_lists = implode(',', $exam_id);

    $query = "select COUNT(exam_id) as total, exam_id from test t WHERE t.exam_id IN(" .$exam_lists. ") group by t.exam_id";
    $get_course_name = $DB->RunSelectQuery($query);

//    print_r($get_course_name) ;

    echo json_encode($get_course_name);
//    echo json_encode($_POST['exam_id']);
}