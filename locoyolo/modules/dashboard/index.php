<?php
/*****************Developed by 	 :- Akshay Yadav
Module       :- Candidate Dashboard
Purpose      :- Entry file for any action, call in Dashboard module
 ***********************************************************************************/
defined("ACCESS") or die("Access Restricted");
$DB = new DBFilter();

include_once('class.dashboard.php');
$DashboardOBJ = new Dashboard();

if(!isset($_SESSION['user_id']) || ($_SESSION['user_id'] == ''))
{
    Redirect(CreateURL('index.php'));
}

$exportEnabled = true;

$do = '';
if(isset($getVars['do']))
    $do=$getVars['do'];


switch($do)
{
    default:
    case 'showinfo':
        /*********************************************************************************
         *				Developed by :- Akshay Yadav
         *				Purpose      :- To provide Social  media integration
         **********************************************************************************/


        require_once  LIB . 'facebook/src/facebook.php';
        require_once  LIB . 'google/src/Google_Client.php';
        require_once  LIB . 'google/src/contrib/Google_PlusService.php';
        require_once  LIB . 'google/src/contrib/Google_Oauth2Service.php';
        $candidate_id = $_SESSION['candidate_id'];
        $lastLoginData = $DB->SelectRecords('user_log', "candidate_id='".$candidate_id."'",'login_time','order by id desc');
        $facebook = new Facebook(array(
            'appId' => FB_APP_ID,
            'secret' => FB_APP_SECRET,
        ));

        global $apiConfig;

        // Initializing Google API global variables to the generated Google Api Credentials
        $apiConfig['oauth2_client_id'] = GOOGLE_CLIENT_ID;
        $apiConfig['oauth2_client_secret'] = GOOGLE_CLIENT_SECRET;
        $apiConfig['oauth2_redirect_uri'] = GOOGLE_REDIRECT_URL;

        $client = new Google_Client();
        $client->setAccessType('online');				//Set the Access Type to online to avoid the Google Consent box pop up issues
        $client->setRedirectUri(GOOGLE_REDIRECT_URL);	//Set the redirection path for redirect the user on the site with user's Google Data

        // Set the Client scopes to Ask user about the required Permissions
        $client->setScopes('https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email');

        $plus = new Google_PlusService($client);
        $oauth2Service = new Google_Oauth2Service($client);

        if (isset($_GET['fblogin']) && !empty($_GET['fblogin'])) {
            try {
                $uid = $facebook->getUser();
                $fb_profile = $facebook->api('/me');
            } catch (Exception $ex) {
                $code = $_REQUEST["code"];

                if (isset($code)) {
                    //Generating an Access Token
                    $token_url = "https://graph.facebook.com/oauth/access_token?client_id=" . $facebook->getAppId()
                        . "&redirect_uri=" . urlencode(FB_REDIRECT_URL)
                        . "&client_secret=" . $facebook->getAppSecret()
                        . "&code=" . $code;
                    $response = file_get_contents($token_url);
                    $params = null;
                    parse_str($response, $params);
                    $access_token = $params['access_token'];

                    $facebook->setAccessToken($access_token);
                    $fb_profile = $facebook->api('/me');
                }
            }
            $name = explode(" ", $fb_profile['name']);
            $image =  "https://graph.facebook.com/".$uid."/picture?type=large";
            $myData['first_name'] = $name[0];
            $myData['last_name'] =  $name[1];
            $myData['email'] = $fb_profile['email'];
            $myData['pro_image'] = $image;
            $myData['candidate_id'] = $fb_profile['email'];

            $password = MakeNewpassword();
            $myData['password'] = Encrypt($password);
            if($fb_profile['gender']) {
                $myData['gender'] = $fb_profile['gender'] == 'male' ? 'M' :'F';
            }
            $email = $myData['email'];
            $candidate_data = $DB->SelectRecord('candidate',"email='$email'");
            if($candidate_data->email == ''){
                $id = $DB->InsertRecord('candidate', $myData);
                $test_id = $DB->SelectRecords('test','','id');
                $exam_data = $DB->SelectRecords('examination','','id');
                $user_log_data['candidate_id']=$_SESSION['candidate_id'];
                $user_log_data['login_time']=date("Y-m-d H:i:s");
                $user_log_data['ip']=$_SERVER['REMOTE_ADDR'];
                $_SESSION['login_time']=$user_log_data['login_time'];
                $DB->InsertRecord('user_log', $user_log_data);
                Mailtocandidate($id,$password);
                Mailtoadmin($id);
                for($i=0;$i<count($exam_data);$i++)
                {
                    $course_data['exam_id'] = $exam_data[$i]->id;
                    $course_data['candidate_id'] = $id;
                    $exam_data_id = $DB->InsertRecord('exam_candidate',$course_data);
                }


                for($i=0;$i<count($test_id);$i++)
                {
                    $data['test_id'] = $test_id[$i]->id;
                    $data['candidate_id'] = $id;
                    $test_data = $DB->InsertRecord('test_candidate',$data);
                }
                $_SESSION['candidate_id'] = $id;
                $_SESSION['candidate_fname'] = $name[0];
                $_SESSION['candidate_lname'] = $name[1];
            }else{
                if($candidate_data->is_disabled==0)				{					$_SESSION['candidate_id'] = $candidate_data->id;
                    $_SESSION['candidate_fname'] = $name[0];
                    $_SESSION['candidate_lname'] = $name[1];
                    $user_log_data['candidate_id']=$_SESSION['candidate_id'];
                    $user_log_data['login_time']=date("Y-m-d H:i:s");
                    $user_log_data['ip']=$_SERVER['REMOTE_ADDR'];
                    $_SESSION['login_time']=$user_log_data['login_time'];
                    $DB->InsertRecord('user_log', $user_log_data);				}				else				{										$_SESSION['error'] = "You are not authorised to login. Please contact to system administrator.";					Redirect(CreateURL('index.php',"mod=user"));													}
            }
        }
        if (isset($_GET['googlelogin']) && !empty($_GET['googlelogin'])) {	//Condition To check for Google Login
            session_start();
            $exlogin = true;
            $client->authenticate($_GET['code']);
            $access_token = json_decode($client->getAccessToken());

            $me = $plus->people->get('me');
            $userinfo = $oauth2Service->userinfo->get();
            $name = explode(" ",$me['displayName']);
            if(isset($name[0]) && $name[0]!='')
            {
                $myData['first_name'] =  $name[0];
            }
            else
            {
                $myData['first_name'] = $userinfo['email'];
            }
            $myData['last_name'] =  $name[1];
            $myData['email'] = $userinfo['email'];
            $myData['candidate_id'] = $userinfo['email'];
            $myData['pro_image'] = $me['image']['url'];
            $myData['birth_date'] = $me['birthday'];
            $password = MakeNewpassword();
            $myData['password'] = Encrypt($password);
            if ($me['gender']) {
                $myData['sex'] = $me['gender'] == 'male' ? 'M' : 'F';
            }
            $email = $myData['email'];
            $candidate_data = $DB->SelectRecord('candidate',"email='$email'");
            if($candidate_data->email == ''){
                $id = $DB->InsertRecord('candidate', $myData);
                $test_id = $DB->SelectRecords('test','','id');
                $exam_data = $DB->SelectRecords('examination','','id');
                $user_log_data['candidate_id']=$_SESSION['candidate_id'];
                $user_log_data['login_time']=date("Y-m-d H:i:s");
                $user_log_data['ip']=$_SERVER['REMOTE_ADDR'];
                $_SESSION['login_time']=$user_log_data['login_time'];
                $DB->InsertRecord('user_log', $user_log_data);
                Mailtocandidate($id,$password);
                Mailtoadmin($id);
                for($i=0;$i<count($exam_data);$i++)
                {
                    $course_data['exam_id'] = $exam_data[$i]->id;
                    $course_data['candidate_id'] = $id;

                    $exam_data_id = $DB->InsertRecord('exam_candidate',$course_data);
                }


                for($i=0;$i<count($test_id);$i++)
                {
                    $data['test_id'] = $test_id[$i]->id;
                    $data['candidate_id'] = $id;
                    $test_data = $DB->InsertRecord('test_candidate',$data);
                }
                $_SESSION['candidate_id'] = $id;
                $_SESSION['candidate_fname'] = $name[0];
                $_SESSION['candidate_lname'] = $name[1];
            }else{
                if($candidate_data->is_disabled==0)				{
                    $_SESSION['candidate_id'] = $candidate_data->id;
                    $_SESSION['candidate_fname'] = $name[0];
                    $_SESSION['candidate_lname'] = $name[1];
                    $user_log_data['candidate_id']=$_SESSION['candidate_id'];
                    $user_log_data['login_time']=date("Y-m-d H:i:s");
                    $user_log_data['ip']=$_SERVER['REMOTE_ADDR'];
                    $_SESSION['login_time']=$user_log_data['login_time'];
//                    $DB->InsertRecord('user_log', $user_log_data);				}	else				{										$_SESSION['error'] = "You are not authorised to login. Please contact to system administrator.";					Redirect(CreateURL('index.php',"mod=user"));													}


            }
        }
        $testdetail = $DashboardOBJ->lastTestdetail();

        if($testdetail)
        {
            $candidate_id = $_SESSION['candidate_id'];
            $exam_id= $testdetail[0]->exam_id;
            $test_id = $testdetail[0]->test_id;

            $test_info = $DB->SelectRecord('test', "id=$test_id");
            $qMedia = $test_info->question_media;

            $test_paper = $DB->SelectRecord('test_paper','test_id='.$test_id);
            $paper_id = $test_paper->paper_id;
            $testQuestions = $DB->SelectRecords('test_questions',"test_id = '$test_id'");
            $totalQuestions  = count($testQuestions);
            $questionHistory = $DB->SelectRecords('candidate_question_history', "((exam_id='$exam_id') AND (test_id='$test_id') AND (candidate_id='$candidate_id')) ORDER BY `id` DESC limit $totalQuestions");
            //print_r($questionHistory);
            $testHistory = $DB->SelectRecord('candidate_test_history', "((exam_id='$exam_id') AND (test_id='$test_id') AND (candidate_id='$candidate_id'))");
            $testHistoryAll = $DB->SelectRecords('candidate_test_history', 'candidate_id='.$candidate_id);
            $candidateInfo = $DB->SelectRecord('candidate',"id = $candidate_id");
            $testInfo = $DB->SelectRecord('test',"id = $test_id");
            $examInfo = $DB->SelectRecord('examination', "id='$exam_id'");

            if($qMedia == 'subject')
            {
                $testQuestions = $DB->SelectRecords('test_questions',"test_id = '$test_id'");
            }

            $givenans = $DB->SelectRecords('candidate_question_history', "((exam_id='$exam_id') AND (test_id='$test_id') AND (candidate_id='$candidate_id'))" ,'is_answer_correct, given_answer_id' );

            $no_correctans = 0;
            $no_wrongans = 0;
            $no_skipped = 0;

            foreach($givenans as $ans)
            {
                if($ans->is_answer_correct == 'Y')
                {
                    $no_correctans++;
                }
                elseif(($ans->is_answer_correct == 'N') && ($ans->given_answer_id != '')  && ($ans->given_answer_id != 0))
                {
                    $no_wrongans++;
                }
                else
                {
                    $no_skipped++;
                }
            }


            $candidate_subject_marks = countCandidateSubjectMarks($exam_id, $test_id, $candidate_id,'');
            $test_subject_result = array();

            for($counter=0; $counter<count($candidate_subject_marks); $counter++)
            {
                $subject_id = $candidate_subject_marks[$counter]->subject_id;
                $subject_marks = $candidate_subject_marks[$counter]->subject_marks;

                if($qMedia == 'subject')
                {
                    $test_subject = $DB->SelectRecord('test_subject',"exam_id=".$exam_id." and test_id=".$test_id." and subject_id=".$subject_id);
                }

                $candidate_subject_marks_percentage = (($subject_marks/$test_subject->subject_total_marks)*100);
                $candidate_subject_marks_percentage = number_format($candidate_subject_marks_percentage, 2, '.', '');

                $exam_subject = $DB->SelectRecord('exam_subjects',"exam_id=".$exam_id." and subject_id=".$subject_id);
                $min_marks = $exam_subject->subject_min_mark;

                $test_subject_result[$counter]['subject_name'] = $candidate_subject_marks[$counter]->subject_name;
                $test_subject_result[$counter]['solve_time'] = $candidate_subject_marks[$counter]->solve_time;
                $test_subject_result[$counter]['subject_percentage'] = $candidate_subject_marks_percentage;
                $test_subject_result[$counter]['subject_min_marks'] = $exam_subject->subject_min_mark;
                $test_subject_result[$counter]['subject_max_marks'] = $exam_subject->subject_max_mark;

                if($candidate_subject_marks_percentage < $min_marks)
                {
                    $test_subject_result[$counter]['result'] = 'Fail';
                    $test_result='F';
                }
                else
                {
                    $test_subject_result[$counter]['result'] = 'Pass';
                }
            }
        }
        if(isset($_SESSION['e']) && $_SESSION['e']!='')
        {
            Redirect(CreateURL('index.php',"mod=guidelines&t=".$_SESSION['e']."&e=".$_SESSION['t']));
        }
        $candi_info = $DB->SelectRecord('candidate', "id = '".$_SESSION['candidate_id']."'");
        //echo '<pre>';print_r($candi_info); exit;
        $first_name = $candi_info->first_name;
        $birth_date = $candi_info->birth_date;

        if (filter_var($first_name, FILTER_VALIDATE_EMAIL)) {
            if($birth_date == '0000-00-00' ||  $birth_date == '1970-01-01') {
                Redirect(CreateURL('index.php',"mod=account&do=editaccount&id=".$_SESSION['candidate_id']."&show=1"));
            }
        } else {
            $CFG->template="dashboard/showinfo.php";
        }

        break;

    case 'question_xml' :
        $DashboardOBJ->getQuestionXml();
        exit;
        break;

    case 'test_xml' :
        $DashboardOBJ->getTestXml();
        exit;
        break;

    case 'candidate_xml' :
        $DashboardOBJ->getCandidateXml();
        exit;
        break;
}
function Mailtocandidate($id,$password)
{
    global $DB;
    $candidate_name = $DB->SelectRecords('candidate','id ='.$id,'candidate_id,first_name,last_name,email');

    $subject = 'Welcome To Assessall.com';

    /*$mydata['name'] = ucfirst($candidate_name[0]->first_name).' '.ucfirst($candidate_name[0]->last_name);*/
    $name = $candidate_name[0]->first_name;

    if (filter_var($name, FILTER_VALIDATE_EMAIL)) {
        $mydata['name'] = "User";
    } else {
        $mydata['name'] = ucfirst($candidate_name[0]->first_name);
    }

    $mydata['user_name'] = $candidate_name[0]->candidate_id;
    $mydata['email'] = $candidate_name[0]->email;
    $mydata['password'] = $password;
    $mydata = (object)$mydata;
    $message = "<div style='background: none repeat scroll 0% 0% rgb(241, 241, 241); border: 1px solid rgb(6, 106, 117); width: 450px; color: rgb(153, 153, 153); font-family: Georgia; height: 500px;'>
				<div style='width: 100%; background: none repeat scroll 0% 0% rgb(6, 106, 117); height: 80px;'>
				<div style='width:150px; height:60px;margin:8px; float:left'>
				<img alt='Assess all' src='http://assessall.com/wp-content/uploads/2014/07/logo.png'>
				</div>
				</div>
				  <div style='margin: auto; float: left; text-align: left; padding: 10px 10px 0px; width: 95%; height: 365px;'>
				  <h2 style='color:#999'>Hello $mydata->name,</h2>
				  
				  <h3 style='color:#999'>Thank you for your registration.</h3>
				  <br>
				  <h3 style='color:#999'>Email: $mydata->email</h3>
				  <br><br><a style='font-size: 1.2em; color: rgb(255, 255, 255); text-decoration: none; background: none repeat scroll 0% 0% rgb(6, 106, 117); padding: 10px 20px; font-weight: bold; margin-top: 10px;' href='http://assessall.com/assessall/index.php'>Login</a>
            <br><br> <p style='color:#999'>Please check your mail regularly to get the updates/information regarding your examination date and time slot</p>
				<p>  <a style='font-size: 1.2em;text-decoration: none; color:rgb(6, 106, 117); padding:10px; font-weight: bold;;' href='http://assessall.com'>www.assessall.com</a></p>
            
				  </div>
				  <div style='height:30px;padding-top:10px;float:left; width:100%;text-align:center;background:#1d5770;'>
					<span style='color:#fff;font-weight:bold'>&copy; 2014 Assess All.</span>
					
				  </div>
				</div>

";
    //$message = "<br><br>Username:$mydata->user_name <br><br><br>Password:$mydata->password <br>";

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    //$headers .= "To: $mydata->name <$mydata->email>" . "\r\n";
    $headers .= "From: Assessall.com <contact@Assessall.com>" . "\r\n";

    mail($mydata->email, $subject, $message, $headers);		// Sending a mail to the Registered User




}
function Mailtoadmin($id)
{
    global $DB;
    $candidate_name = $DB->SelectRecords('candidate','id ='.$id,'candidate_id,first_name,last_name,email');

    $subject = 'A New Member has Registered';

    /*$mydata['name'] = ucfirst($candidate_name[0]->first_name).' '.ucfirst($candidate_name[0]->last_name);*/		$mydata['name'] = ucfirst($candidate_name[0]->first_name);
    $mydata['user_name'] = $candidate_name[0]->candidate_id;
    $mydata['email'] = $candidate_name[0]->email;
    $mydata['password'] = $password;
    $mydata = (object)$mydata;
    $message = "<div style='background: none repeat scroll 0% 0% rgb(241, 241, 241); border: 1px solid rgb(6, 106, 117); width: 450px; color:#666; font-family: Georgia; height: 380px;'>
				<div style='width: 100%; background: none repeat scroll 0% 0% rgb(6, 106, 117); height: 80px;'>
				<div style='width:150px; height:60px;margin:8px; float:left'>
				<img alt='Assess all' src='http://assessall.com/wp-content/uploads/2014/07/logo.png'>
				</div>
				</div>
				  <div style='margin: auto; float: left; text-align: left; padding: 10px 10px 0px; width: 95%; height: 250px;'>
				  <h2 style='color:#666; '>Hello Admin,</h2>
				  
				  <h3 style='color:#666; '>A New Member has Registered.</h3>
				  <h3 style='color:#666; '>Below are the details of user.</h3>
				  <br>
				  <h3 style='color:#666; '>Username: $mydata->user_name</h3>
				  <h3 style='color:#666; '>Email: $mydata->email</h3>
				 <p>  <a style='font-size: 1.2em;text-decoration: none; color:rgb(6, 106, 117); padding:10px; font-weight: bold;;' href='http://assessall.com'>www.assessall.com</a></p>
            
				  </div>
				  <div style='height:30px;padding-top:10px;float:left; width:100%;text-align:center;background:#1d5770;'>
					<span style='color:#fff;font-weight:bold'>&copy; 2014 Assess All.</span>
					
				  </div>
				</div>

";
    //$message = "<br><br>Username:$mydata->user_name <br><br><br>Password:$mydata->password <br>";

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    //$headers .= "To: Admin <manoj.sharma@sunarctechnologies.com>" . "\r\n";
    $headers .= "From: Assessall.com <contact@Assessall.com>" . "\r\n";

    mail('manoj.sharma@sunarctechnologies.com', $subject, $message, $headers);		// Sending a mail to the Registered User




}
//-----------------------------



include(CURRENTTEMP."/index.php");
exit;
?>