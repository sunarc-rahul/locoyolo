<?php

defined("ACCESS") or die("Access Restricted");
include_once('class.user.php');
$UserOBJ = new User();
global $DB;

$do = '';
if(isset($getVars['do']))
    $do = $getVars['do'];

if(isset($_REQUEST['update_details_submit'])){
    $UserOBJ->editprofile();
     $do = 'profile';
}


if(isset($frmdata['submit']))
	$UserOBJ->validateUser();
if(isset($frmdata['register_user']))
	$UserOBJ->registerUser();
if(isset($frmdata['changepassword']))
	$UserOBJ->changePassword();
if(isset($frmdata['signUp_submit']))
    $UserOBJ->signUp();
if(isset($_REQUEST['from'])){
    $UserOBJ->validateUserByfb();
}

if(($user_id!='' && $_POST['searchbtnstart']=='Search'&& $do!='logout')|| ($_SESSION['searchfrm'] && !$_GET['do']))
{
	$do = 'Search';
}


switch($do)
{
	default :
	case 'login':

		if(isset($_SESSION['user_id']) && ($_SESSION['user_id'] != '')){
            $pageTitle = 'Dashboard';
            $CFG->template = "user/dashboard.php";

            break;
        }else{
            $pageTitle = 'Locoyolo-Login';
            $CFG->template = "user/login.php";
        }


//
		break;
	case "resetaccount":
        $pageTitle = 'Reset account';
        $CFG->template="/user/resetaccount.php";
		break;
		case "forgotpass":

            $pageTitle="Forget password";
            $CFG->template="/user/forgotpass.php";
      	break;
	case "dashboard":
        $pageTitle = 'Dashboard';
			$CFG->template = "user/dashboard.php";
		break;

	case 'Search':
        $pageTitle = 'Search';
	    if(is_array($_SESSION['searchfrm']))
        {
            $_POST =$_REQUEST = $_SESSION['searchfrm'] ;
        }
        else {
            $_SESSION['searchfrm'] = $_POST;
        }
		$CFG->template = "user/search.php";
		break;

    case 'loginrecover':
        $pageTitle = 'Login recover';
       $CFG->template = "user/loginrecover.php";
		break;

	case 'profile':
		if(!isset($_SESSION['user_id']) || ($_SESSION['user_id'] == ''))
			Redirect(CreateURL('index.php'));

        $user_id = $_SESSION['user_id'];
        $user = $DB->SelectRecord('public_users',"id='$user_id'") ;
         $userName = $user->firstname;
        $userLastName = $user->lastname;
        $pageTitle = "$userName"." $userLastName";

		$CFG->template = "user/profile.php";
		break;

	case 'editprofile':
        $pageTitle = 'Edit profile';
		$CFG->template = "user/editprofile.php";
		break;

	case 'upload_image':
		if(isset($_GET['delete_image']) && ($_GET['delete_image'] == 1))
		{
			$UserOBJ->deleteImage();
		}
		else
		{
			$UserOBJ->uploadImage();
		}
		exit;

	case 'edit_personal_info':
		$UserOBJ->updatePersonalInfo();
		exit;

	case 'validate_username':
		$DB->SelectRecord('candidate',"candidate_id='".$frmdata['candidate_id']."'") ? 0 : 1;
		exit;

	case 'logout':
        $pageTitle = 'Locoyolo-Login';
	$UserOBJ->logoutUser();
	Redirect(ROOTURL);
	exit;

	case 'changepwd':
	$CFG->template = "user/changepwd.php";

    case 'editprofile':
	$CFG->template = "user/editprofile.php";

}

include(TEMP."/index.php");