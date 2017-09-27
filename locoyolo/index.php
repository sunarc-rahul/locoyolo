<?php
//error_reporting(E_ALL);
ini_set("display_errors",0);
ini_set("date.timezone", 'Asia/Calcutta');

if($_POST['submit']=='search')
{
    $mod = 'search';
    $do ='searchlist';
}
session_start();


include_once("start.php");
$DB= new DBFilter();
$DB->ExecuteQuery("set time_zone = '+5:30'");
$DbHelper = new DbHelper();
$user_id = '';
if(isset($user_id)) {
    $user_id = $_SESSION['user_id'];

    $email = $_SESSION['user_email'];
    $userData = $DB->SelectRecord('public_users', "email='" . $email . "'");
}

$mod = '';
if(isset($_SESSION['candidate_id']) && ($_SESSION['candidate_id']))
{
	if(isset($_GET['mod']))
		$mod = $_GET['mod'];
}
else
{

	if(isset($_GET['mod']) )
		$mod = $_GET['mod'];
}
if(isset($_GET['fblogin']))
{
	if(isset($_GET['mod']))
			$mod = $_GET['mod'];
}
if(isset($_GET['googlelogin']) && !empty($_GET['googlelogin']))
{
	if(isset($_GET['mod']))
			$mod = $_GET['mod'];
}
//check the proper mode also if there pagination string were added
if(strrpos($mod,'?'))
{
	$exact_mod=explode('?',$mod);
	$mod=$exact_mod[0];
}

forexactmod:

switch($mod) {



    case "ping":

        include_once(MOD . "/ping/index.php");
        break;
case "search":
        include_once(MOD . "/search/index.php");
        break;

    case "booking":
        include_once(MOD . "/booking/index.php");
        break;

    case "event":

        include_once(MOD . "/event/index.php");
        break;
    case "notification":

        include_once(MOD . "/notifications/index.php");
        break;

    case "changepwd":
        include_once(MOD . "/changepwd/index.php");
        break;

    case "calendar":
        include_once(MOD . "/calendar/index.php");
        break;

    case "dashboard":


        include_once(MOD . "/dashboard/index.php");
        break;
     case "ajax":
         if(!isset($_SESSION['user_id']))
         {
             $mod = '';
             $do ='';
         }
        include_once(MOD . "/ajax/index.php");
        break;

    default :
    case "user":

        include_once(MOD . "/user/index.php");
        break;

}


function checker() {

    $DB= new DBFilter();

    $candi_info = $DB->SelectRecord('public_users', "id = '".$_SESSION['user_id']."'");


    $first_name = $candi_info->firstname;

    $birth_date = $candi_info->birthdate;



    if (filter_var($first_name, FILTER_VALIDATE_EMAIL)) {

        if($birth_date == '0000-00-00' ||  $birth_date == '1970-01-01') {

            Redirect(CreateURL('index.php',"mod=account&do=editaccount&id=".$_SESSION['candidate_id']."&show=1"));

        }

    }

}

?>