<?php

/**
 * @author :  Akshay Yadav
 */
class User
{

    function validateUser($getError = false)
    {
        global $DB, $frmdata;

        $error = '';
        $_SESSION['user_email'] = $frmdata['email'];
        if (isset($frmdata['email']) && ($frmdata['email'] != ''))
            $email = trim($frmdata['email']);
        else
            $error .= "Please enter username.<br>";

        if (isset($frmdata['password']) && ($frmdata['password'] != ''))
            $password = $frmdata['password'];
        else
            $error .= "Please enter password.<br>";

        if ($error == '') {
            $user = $DB->SelectRecord('public_users', "email='" . $email . "'");
            if ($user && (md5($password) == $user->password)) {
                if ($user->is_disabled == 0) {
                   $this->loginUser($user);
                    Redirect(CreateURL('index.php'));
                } else {
                    $error .= "You are not authorised to login. Please contact system administrator.";
                }
            } else {
                $error .= "Please enter correct password.<br>";
            }
        }

        if ($error != '') {
            $_SESSION['error'] = $error;
        }
        if (!$getError) $error = false;
        return $error;
    }

function validateUserByfb($getError = false)
    {

       //--------------------------------------------------------------------------------------------------------------------------
        global $DB;

               if($_REQUEST['code'])
        {


            include(ROOTPATH."/lib/api/Facebook/autoload.php");
            $fb = new Facebook\Facebook([
                'app_id' => '850026601828810', // Replace {app-id} with your app id
                'app_secret' => '5c7e2e63652770b90221e6051a165a89',
                'default_graph_version' => 'v2.9',
                "persistent_data_handler"=>"session"
            ]);

            $helper = $fb->getRedirectLoginHelper();

            try {
                $accessToken = $helper->getAccessToken();
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
            $response = $fb->get('/me?fields=name,email,location,gender,birthday,hometown', $accessToken);
            $me = $response->getGraphUser();

            if (! isset($accessToken)) {
                if ($helper->getError()) {
                    //  header('HTTP/1.0 401 Unauthorized');
                    echo "Error: " . $helper->getError() . "\n";
                    echo "Error Code: " . $helper->getErrorCode() . "\n";
                    echo "Error Reason: " . $helper->getErrorReason() . "\n";
                    echo "Error Description: " . $helper->getErrorDescription() . "\n";
                } else {
                    // header('HTTP/1.0 400 Bad Request');
                    echo 'Bad request';
                }
                exit;
            }

// The OAuth 2.0 client handler helps us manage access tokens
            $oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
            $tokenMetadata = $oAuth2Client->debugToken($accessToken);

// Validation (these will throw FacebookSDKException's when they fail)
            $tokenMetadata->validateAppId('850026601828810'); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
            $tokenMetadata->validateExpiration();

            if (! $accessToken->isLongLived()) {
                // Exchanges a short-lived access token for a long-lived one
                try {
                    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                } catch (Facebook\Exceptions\FacebookSDKException $e) {
                    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                    exit;
                }


            }

            $_SESSION['fb_access_token'] = (string) $accessToken;
            $email = $me->getEmail();
            $exist = $DB->SelectRecord('public_users', "email='" . $email . "'");

		   if ($exist){

                $_SESSION["login_wrong"] == "NO";
                //header("Location: index.php");
				 $this->loginUser($exist);
                ?>
                <script>
                    window.opener.location.href="index.php";
                    self.close();
				  
                </script>
			<?php 
            }else{

                //--------
                $characters = 'ab0cd1ef2gh3ij4kl5mn6op7qr8st9uvwxyz';
                $string = '';
                $max = strlen($characters) - 1;
                for ($i = 0; $i < 8; $i++) {
                    $string .= $characters[mt_rand(0, $max)];
                }
                $requeststring = '';
                $max = strlen($characters) - 1;
                for ($i = 0; $i < 10; $i++) {
                    $requeststring .= $characters[mt_rand(0, $max)];
                }
                $firstname =$me->getName();
                $lastname='';
                $password1='locoyolo@123';
                $birthdate='';
                $userId = $me->getId();
                $profileImage = 'images/profilepics/'.$me->getId().'.jpg';
                $imgfile = "https://graph.facebook.com/".$userId."/picture?type=large";
                copy($imgfile,$profileImage)or die('cant copy');
                   $data['firstname'] = $firstname;
                   $data['lastname'] = $lastname;
                   $data['email'] = $email;
                   $data['date'] = date("Y-m-d H:i:s");
                   $data['birthdate'] = $birthdate;
                   $data['profile_pic'] = $profileImage;
                   $data['password'] = md5($password1);

                   $id = $DB->InsertRecord('public_users', $data);

                if ($id)
                {

                    $_SESSION['user_id'] = $id;
					$_SESSION['user_email'] = $email;
					$_SESSION['user_name'] = $firstname;
                    $_SESSION["login_wrong"] == "NO";

                    $emailmessage='<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			</head>
			<body>
			
			<div>
					<p>Hello '.$_POST["firstname"].' '.$_POST["lastname"].'!</p>
					<p>Thank you for registering on LocoYolo.</p>
					<p>Your temporary password for account '.$email.' is locoyolo@123. Please change it.
					</p>
			
			</div>
			</body>
			</html>';
                    $to      = $_POST["email"];
                    $subject = 'LocoYolo Account Verification';
                    $firstname = $_POST["firstname"];
                    $lastname = $_POST["lastname"];
                    include_once(ROOTPATH.'/lib/mailer.php');
                    $mail = new mailer();
                    $mail->addTo($to, ucwords($firstname.' '.$lastname));
                    $mail->setSubject($subject);
                    $mail->setMessage($emailmessage);
                    $mail->send();



                    ?>
                    <script>
                    window.opener.location.href="index.php";
                    self.close();
				  
                </script>
                    <?php
                }
            }
		}
        }




function editprofile()
{
    global $DB, $frmdata,$userData;
    $err = array();
    $email = $userData->email;
    $profilepic['profile_pic'] = $userData->profile_pic;
    //for upload profile image//
    $file = $_FILES['profile_photo'];



    if ($file['tmp_name'] == '') {

        $DB->UpdateRecord('public_users',$profilepic , 'email="' . $email . '"');

    }else{

    $ext = array('image/jpeg', 'image/jpg', 'image/gif', 'image/png');
    if (!in_array($file['type'], $ext)) {
        $err[] = "Please upload image in correct format.";
    }

    if ($file['size'] > 2000000) {
        $err[] = "Please upload image not more than 2MB.";
    }

    if (count($err) > 0) {
        $responce = array('sucess' => 0, 'message' => $err[0]);
    } else {
        $filename = uniqid() . '_' . $file['name'];
        $image_path = ROOTPATH . '/images/profilepics/' . $filename;
        $FileObject = (object)$file;

        if (is_uploaded_file($file['tmp_name'])) {
            include_once(ADMINROOT . '/lib/image.class.php');
            $img = new thumb_image;
            $img->GenerateThumbFile($file['tmp_name'], $image_path);
            if (file_exists($image_path)) {
                $imageData = array('profile_pic' => 'images/profilepics/'.$filename);
                $DB->UpdateRecord('public_users', $imageData, 'email="' . $email . '"');
            }
        }
    }

        $filename = ROOTURL."/".$userData->profile_pic;
        if (file_get_contents($filename)  ) {

            unlink(ROOTPATH."/".$userData->profile_pic);
        }
    }

        //end
    $err = '';
    if ($frmdata['firstname'] == '') {
        $err .= "Please enter first name.<br>";
    }
    if ($frmdata['lastname'] == '') {
        $err .= "Please enter last name.<br>";
    }

    if ($frmdata['mood_statement'] == '') {
        $err .= "Please enter Discription.<br>";
    }
    if ($frmdata['achievements'] == '') {
        $err .= "Please enter Achievement&Skills.<br>";
    }
    if ($frmdata['gender'] == '') {
        $err .= "Please select Gender.<br>";
    }
    if($err =='') {

         $birthdate = $frmdata['birthdate']?date('Y-m-d ', strtotime(str_replace('/','-',$frmdata['birthdate']))):'0000-00-00' ;


//        echo $birthdate; exit;
        $updateData['firstname'] = $frmdata["firstname"];
        $updateData['lastname'] = $frmdata["lastname"];
        $updateData['birthdate'] = date('Y-m-d ', strtotime($birthdate));
        $updateData['mood_statement'] = $frmdata["mood_statement"];
        $updateData['gender'] = $frmdata["gender"];
        $updateData['achievements'] = $frmdata["achievements"];
        $updateData['contact'] = $frmdata["contact"];
        $result = $DB->updateRecord('public_users', $updateData, "email='" . $email . "'");

        $message = 'Your details have been updated';
        $_SESSION['success']=$message;
        Redirect(CreateURL('index.php',"mod=user&do=editprofile"));

    }else{

        $_SESSION['error'] = $err;
        $_SESSION['error_post']= $frmdata;
//        return false;
        Redirect(CreateURL('index.php',"mod=user&do=editprofile"));


    }

}






       //--------------------------------------------------------------------------------------------------------------------------

    

    function loginUser($user)
    {
       

        global $DB;
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->firstname;
    }

    function logoutUser()
    {
        //$lastLogin=date('d-m-Y'); echo $lastLogin;
        unset($_SESSION);
        session_unset();
        session_destroy();
    }

    function signUp()
    {

        global $DB, $frmdata;
        $err = '';
        if ($frmdata['firstname']=='') {
            $err .= "Please enter first name.<br>";
        }
        if ($frmdata['lastname']=='') {
            $err .= "Please enter last name.<br>";
        }

        if ($frmdata['email']=='') {
            $err .= "Please enter email address.<br>";
        }

            if(preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z]).*$/", $frmdata["password1"]) === 0)
                $err .= '<p class="errText">Password must be at least 8 characters and must contain at least one lower case letter and one digit</p>';


        if ($frmdata['password1'] !== $frmdata['password2']) {
            $err .= "Please re-enter your password correctly.<br>";
        }

        if ($frmdata['user_id'] != '') {
            $candidate_id = $frmdata['user_id'];
            $exist = $DB->SelectRecord('public_users', "user_id='" . $candidate_id . "'");
            if ($exist->user_id != '') {
                $err .= "Username already exist.<br>";
            }
        }

        if ($err != '') {
            $_SESSION['error'] = $err;
            return false;
        }

        if ($frmdata['email'] != '') {
            if (!filter_var($frmdata['email'], FILTER_VALIDATE_EMAIL)) {
                $err .= "Please enter a valid email address.<br>";
            } else {
                $exist = $DB->SelectRecord('public_users', "email='" . $frmdata['email'] . "'");
                if ($exist && ($exist->email != '')) {
                    $err .= "This email id is already in use by another user.<br>";
                }
            }

            if ($err != '') {
                $_SESSION['error'] = $err;
                return false;
            }

            $birthdate = $frmdata['birth_year']."-".$frmdata['birth_month']."-".$frmdata['birth_day'];

            $frmdata['birthdate'] =$birthdate;
            if ($err == '') {
//                $password = MakeNewpassword();
                $frmdata['password']= md5($frmdata['password1']);
//                   print_r($frmdata);exit;
                $id = $DB->InsertRecord('public_users', $frmdata);


                $candidate_name[] = $DB->SelectRecord('public_users', 'email ="' . $frmdata['email'].'"');

                $subject = 'Welcome To Locoyolo.com';

                /*$mydata['name'] = ucfirst($candidate_name[0]->first_name).' '.ucfirst($candidate_name[0]->last_name);*/
                $mydata['name'] = ucfirst($candidate_name[0]->firstname);
                $mydata['user_name'] = $candidate_name[0]->email;
                $mydata['email'] = $candidate_name[0]->email;
                $mydata = (object)$mydata;
                $message = '<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			</head>
			<body>
			<div style="background: none repeat scroll 0% 0% rgb(241, 241, 241); border: 1px solid rgb(6, 106, 117); width: 450px; color: rgb(153, 153, 153); font-family: Georgia; height: 500px;">
				<div style="width: 100%; background: none repeat scroll 0% 0% rgb(6, 106, 117); height: 80px;">
				<div style="width:150px; height:60px;margin:8px; float:left">
				<img alt="Locoyolo" src="'.ROOTURL.'/images/logo.jpg">
				</div>
				</div>
				  <div style="margin: auto; float: left; text-align: left; padding: 10px 10px 0px; width: 95%; height: 365px;">
				  <h2 style="color:#999">Hello '.$mydata->name.',</h2>

				  <h3 style="color:#999">Thank you for your registration.</h3>
				  <h3 style="color:#999">Below are your login details for Locoyolo.</h3>
				  <br>
				  <h3 style="color:#999">Username: '.$mydata->user_name.'</h3>

				  <h3 style="color:#999">Password: '.$mydata->password.'</h3>
				  <br><br><a style="font-size: 1.2em; color: rgb(255, 255, 255); text-decoration: none; background: none repeat scroll 0% 0% rgb(6, 106, 117); padding: 10px 20px; font-weight: bold; margin-top: 10px;" href="'.ROOTURL.'">Login</a>
            <br><br> <p style="color:#999">Please check your mail regularly to get the updates/information regarding locoyolo.</p>
				<p>  <a style="font-size: 1.2em;text-decoration: none; color:rgb(6, 106, 117); padding:10px; font-weight: bold;;" href="'.ROOTURL.'">www.Locoyolo.com</a></p>

				  </div>
				  <div style="height:30px;padding-top:10px;float:left; width:100%;text-align:center;background:#1d5770;">
					<span style="color:#fff;font-weight:bold">&copy; 2017 Locoyolo.</span>

				  </div>
				</div></body></html>';
               

                include_once(ROOTPATH.'/lib/mailer.php');
                $mail = new mailer();
                $mail->addTo($mydata->email, ucwords($mydata->name));
                $mail->setSubject($subject);
                $mail->setMessage($message);
                $mail->send();


                $_SESSION['success'] = "Your account has been created successfully.<br/>Username & Password Credentials are sent to your email address.";
//                if (isset($_GET['e']) && $_GET['e'] != '') {
//                    Redirect(CreateURL('index.php', 'mod=user&link=dir&e=' . $_GET['e'] . '&t=' . $_GET['t']));
//                } else {
                 Redirect(CreateURL('index.php', '?mod=user'));
            }
        }
    }


    function Mailtoadmin($id)
    {
        global $DB;
        $candidate_name = $DB->SelectRecords('candidate', 'id =' . $id, 'candidate_id,first_name,last_name,email');

        $subject = 'A New Member has Registered';

        /*$mydata['name'] = ucfirst($candidate_name[0]->first_name).' '.ucfirst($candidate_name[0]->last_name);*/
        $mydata['name'] = ucfirst($candidate_name[0]->first_name);
        $mydata['user_name'] = $candidate_name[0]->candidate_id;
        $mydata['email'] = $candidate_name[0]->email;
        //$mydata['password'] = $password;
        $mydata = (object)$mydata;
        $message = "<div style='background: none repeat scroll 0% 0% rgb(241, 241, 241); border: 1px solid rgb(6, 106, 117); width: 450px; color:#666; font-family: Georgia; height: 380px;'>
				<div style='width: 100%; background: none repeat scroll 0% 0% rgb(6, 106, 117); height: 80px;'>
				<div style='width:150px; height:60px;margin:8px; float:left'>
				<img alt='Locoyolo' src='http://Locoyolo.com/wp-content/uploads/2014/07/logo.png'>
				</div>
				</div>
				  <div style='margin: auto; float: left; text-align: left; padding: 10px 10px 0px; width: 95%; height: 250px;'>
				  <h2 style='color:#666; '>Hello Admin,</h2>				  
				  <h3 style='color:#666; '>A New Member has Registered.</h3>
				  <h3 style='color:#666; '>Below are the details of user.</h3>
				  <br>
				  <h3 style='color:#666; '>Username: $mydata->user_name</h3>
				  <h3 style='color:#666; '>Email: $mydata->email</h3>
				 <p>  <a style='font-size: 1.2em;text-decoration: none; color:rgb(6, 106, 117); padding:10px; font-weight: bold;;' href='http://Locoyolo.com'>www.Locoyolo.com</a></p>            
				  </div>
				  <div style='height:30px;padding-top:10px;float:left; width:100%;text-align:center;background:#1d5770;'>
					<span style='color:#fff;font-weight:bold'>&copy; 2014 Locoyolo.</span>					
				  </div>
				</div>";
        //$message = "<br><br>Username:$mydata->user_name <br><br><br>Password:$mydata->password <br>";

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        //$headers .= "To: Admin <manoj.sharma@sunarctechnologies.com>" . "\r\n";
        $headers .= "From: Locoyolo.com <contact@Locoyolo.com>" . "\r\n";

        //mail('manoj.sharma@sunarctechnologies.com', $subject, $message, $headers);        // Sending a mail to the Registered User
        include_once(ROOTPATH.'/lib/mailer.php');
        $mail = new mailer();
        $mail->addTo(ADMINEMAIL, ADMINNAME);
        $mail->setSubject($subject);
        $mail->setMessage($message);
        $mail->send();

    }



    function uploadImage()
    {
        global $DB, $frmdata,$userData;
        $err = array();
        $email = $userData->email;

        $file = $_FILES['profile_photo'];

        if ($file['tmp_name'] == '') {

            $_SESSION['error'] = "Please Upload Image";

            Redirect(CreateURL('index.php',"mod=user&do=editprofile"));

        }
        if ($userData->profile_pic !== ""){

            $filename = ROOTURL."/".$userData->profile_pic;

            if (file_get_contents($filename)) {

                unlink(ROOTPATH."/".$userData->profile_pic);

            }
        }

        $ext = array('image/jpeg', 'image/jpg', 'image/gif', 'image/png');
        if (!in_array($file['type'], $ext)) {
            $err[] = "Please upload image in correct format.";
        }

        if ($file['size'] > 2000000) {
            $err[] = "Please upload image not more than 2MB.";
        }

        if (count($err) > 0) {
            $responce = array('sucess' => 0, 'message' => $err[0]);
        } else {
            $filename = uniqid() . '_' . $file['name'];
            $image_path = ROOTPATH . '/images/profilepics/' . $filename;
            $FileObject = (object)$file;

            if (is_uploaded_file($file['tmp_name'])) {
                include_once(ADMINROOT . '/lib/image.class.php');
                $img = new thumb_image;
                $img->GenerateThumbFile($file['tmp_name'], $image_path);

                if (file_exists($image_path)) {
                    $frmdata = array('profile_pic' => 'images/profilepics/'.$filename);
                    $DB->UpdateRecord('public_users', $frmdata, 'email="' . $email . '"');
//                    $DB->updateRecord('public_users', $updateDatas, "email='" . $email . "'");
                    $responce = array('success' => 1, 'image' => ADMINURL . '/images/profilepics/' . $filename);
                    Redirect(CreateURL('index.php',"mod=event&do=events&userid=".$userData->id));

                } else {

                    $responce = array('sucess' => 0, 'message' => "An error occurred while saving file.\nPlease try after some time.");
                }
            }
        }

        echo json_encode($responce);
    }


    function deleteImage()
    {
        global $DB;
        $user = $DB->SelectRecord('candidate', 'id="' . $_SESSION['candidate_id'] . '"');
        @unlink(ADMINURL . '/uploadfiles/' . $user->pro_image);

        $frmdata = array('pro_image' => '');
        $DB->UpdateRecord('candidate', $frmdata, 'id="' . $_SESSION['candidate_id'] . '"');
    }

    function updatePersonalInfo()
    {
        global $DB, $frmdata;

        $nameID = $_SESSION['candidate_id'];
        $err = '';

        if ($frmdata['first_name'] == '') {
            $err .= "Please enter first name.<br>";
        }

        if ($frmdata['email'] == '') {
            //	$err .= "Please enter email address.<br>";
        }

        if ($frmdata['email'] != '') {
            $exist = $DB->SelectRecord('candidate', "email='" . $frmdata['email'] . "' and id!=" . $nameID);
            if ($exist && ($exist->email != '')) {
                $err .= "This email id is already in use by another student.<br>";
            }
        }

        if ($err != '') {
            $responce = array('success' => 0, 'message' => $err);
        } else {
            if ($frmdata['birth_date']) {
                $frmdata['birth_date'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $frmdata['birth_date'])));
            }

            $DB->UpdateRecord('candidate', $frmdata, 'id="' . $nameID . '"');

            if ($frmdata['birth_date']) {
                $frmdata['birth_date'] = date('d/m/Y', strtotime($frmdata['birth_date']));
            }

            $responce = array('success' => 1, 'data' => $frmdata);
        }

        echo json_encode($responce);
    }

    function changePassword()
    {
        global $DB, $frmdata;
        $err = '';

        if ($frmdata['old_password'] == '') {
            $err .= "Please enter old password.<br>";
        } elseif ($frmdata['old_password'] != '') {
            $exist = $DB->SelectRecord('candidate', "id=" . $_SESSION['candidate_id']);
            if ($exist->password != Encrypt($frmdata['old_password'])) {
                $err .= "Old password is not matching with your password.<br>";
            }
        }

        if ($frmdata['new_password'] == '') {
            $err .= "Please enter new password.<br>";
        } elseif (strlen($frmdata['new_password']) < 5) {
            $err .= "Please enter new password atleast 5 characters long.<br>";
        }

        if ($frmdata['confirm_password'] == '') {
            $err .= "Please enter confirm password.<br>";
        }

        if ($frmdata['new_password'] != $frmdata['confirm_password']) {
            $err .= "Your new password doesn't match with confirm password.<br>";
        }

        if ($err == '') {
            $frmdata['password'] = Encrypt($frmdata['new_password']);
            $DB->UpdateRecord('candidate', $frmdata, 'id=' . $_SESSION['candidate_id']);
            $_SESSION['success'] = "Your password has been changed successfully.";
            $frmdata = '';
        } elseif ($err != '') {
            $_SESSION['error'] = $err;
        }
    }

    function courses()
    {
        global $DB;

        $query = "select e.* from examination as e";

        $result = $DB->RunSelectQuery($query);

        return $result;
    }
    function getPackageIdByLoggedUser()
    {
        global $DB;

        $query = "select package_id
 from student_personal_package_details WHERE candidate_id =" .$_SESSION['candidate_id'];

        $result = $DB->RunSelectQuery($query);

        return $result;
    }

    function getPackageDetails()
    {

        global $DB, $frmdata;

        // Get all alloted tests that is buyed by student package.
        $query = "
                    SELECT exm.exam_name,sppd.*,exam_id,allotted_tests,candidate_id
                    FROM student_personal_package_details sppd
                    JOIN alloted_tests_for_package atfp
                    ON sppd.package_id = atfp.package_id
                    JOIN examination exm
                    ON atfp.exam_id = exm.id

                    WHERE sppd.candidate_id =" . $_SESSION['candidate_id'] . "
                    GROUP BY exm.exam_name
                    ORDER BY exm.exam_name ASC";

        $result = $DB->RunSelectQuery($query);

        return $result;
    }

    function getCourseByPackageID($package_id_from_url)
    {

        global $DB, $frmdata;

        // Get all alloted tests that is buyed by student package.
        $query = "
                    SELECT exm.exam_name,sppd.*,exam_id,allotted_tests,candidate_id
                    FROM student_personal_package_details sppd
                    JOIN alloted_tests_for_package atfp
                    ON sppd.package_id = atfp.package_id
                    JOIN examination exm
                    ON atfp.exam_id = exm.id

                    WHERE sppd.candidate_id =" . $_SESSION['candidate_id'] . " AND sppd.package_id = " . $package_id_from_url .
                    " GROUP BY exm.exam_name
                    ORDER BY exm.exam_name ASC";

        $result = $DB->RunSelectQuery($query);
        return $result;
    }
    function getPackageDetailsWithName()
    {
        global $DB, $frmdata;

        // Get all alloted tests that is buyed by student package.
        $query = "SELECT pd.package_name,exm.exam_name,sppd.*,exam_id,allotted_tests,candidate_id
                    FROM student_personal_package_details sppd
                    JOIN alloted_tests_for_package atfp
                    ON sppd.package_id = atfp.package_id
                    JOIN examination exm
                    ON atfp.exam_id = exm.id

                    JOIN package_details pd
                    ON sppd.package_id = pd.id

                    WHERE sppd.candidate_id =" . $_SESSION['candidate_id'] . "
                    /*GROUP BY exm.exam_name*/
                    GROUP BY pd.package_name

                    ORDER BY exm.exam_name ASC";

        $result = $DB->RunSelectQuery($query);

        return $result;

    }

    function test_details($exam_id,$package_id_from_url)
    {
        global $DB, $frmdata;

        $query = "
                  SELECT sppd.package_id,sppd.date_of_buy,sppd.date_of_package_expiry, sppd.candidate_id, tst.test_name, tst.id, tst.exam_id
                  FROM alloted_tests_for_package atfp
                  
                  JOIN test tst
                  ON atfp.allotted_tests = tst.id
                    
                  JOIN student_personal_package_details sppd
                  JOIN candidate cnd
                  ON sppd.candidate_id = cnd.id
                
                  JOIN alloted_tests_for_package altp
                
                  ON atfp.package_id = sppd.package_id
                
                  WHERE sppd.candidate_id =" . $_SESSION['candidate_id'] . "
            
                  AND 
                  tst.exam_id=" . $exam_id. "
                AND
                  sppd.package_id=" . $package_id_from_url. "

                  GROUP BY atfp.allotted_tests;
                    
                ";

        $result = $DB->RunSelectQuery($query);

        return $result;
    }
//-----------------------------

    function getOrderId($package_id)
    {

        global $DB;

        $query = "SELECT id,order_id from student_personal_package_details WHERE id = " .$package_id;

        $result = $DB->RunSelectQuery($query);

        return $result;
    }

    function updateCurrentResponse($package_id,$payment_transaction_details)
    {
        global $DB;
//    $query = "update student_personal_package_details WHERE candidate_id = " .$_SESSION['candidate_id']. "AND order_id = " .$order_id ;
        $result = $DB->UpdateRecord('student_personal_package_details', $payment_transaction_details, 'id="'.$package_id.'"');
        unset($_SESSION['get_last_id']);
        unset($_SESSION['package_details']);



        return $result;
    }

}


?>