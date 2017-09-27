<?php

include_once(TEMPPATH . "/header_login.php");

include_once("../../config.php");
include_once(INC."/commonfunction.php");
include_once(INC.'/dbfilter.php');
include_once(INC.'/dbqueries.php');
include_once(INC.'/dbhelper.php');
$post = (!empty($_POST)) ? true : false;

if($post)
{

    $email = $_POST['email'];
    $sql = "SELECT * from public_users where email='$email'";
    $query =$DB->RunSelectQuery($sql);
    $numofrows = Count($query);
    $error = "";
    $message = "";
    if ($query < 1){
        $error = '<h5 class="ArialVeryDarkGrey15" style="color:#963">Your email address does not exist. Please sign up</h5>';

    }else{
        $characters = 'BJaZb0AQcdY1eFfK2ghCT3iUPj4kl5DmRSn6opE7qr8XGstI9uvHwMxNVOyWzLZ';
        $string = '';
        $requeststring = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 15; $i++) {
            $requeststring .= $characters[mt_rand(0, $max)];
        }
        $Recoverydata['email']=$_POST["email"];
        $Recoverydata['date']= date("Y-m-d H:i:s");
        $Recoverydata['requestid']=$requeststring;
        $sql =$DB->InsertRecord('account_recovery_stby',$Recoverydata);

//        if ($pdo->query($sql)) {
        $message = "<h5><strong>A password reset code has been sent to your email.</strong></h5><div style=\"height:10px\"></div>";

        $emailmessage='<html>

			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			</head>
			<body>

			<div>
					<p>Hello '.$_POST["firstname"].'!</p>
				
					<p>We have received your request to reset your LocoYolo account password. Please go to this <a href ="'.CreateURL('index.php',"mod=user&do=resetaccount&requestid=".$requeststring).'">link</a> to reset your password.</p>
					
					<p><strong>LocoYolo Team</strong></p>

			</div>
			</body>
			</html>';
        $to      = $_POST["email"];
        $subject = 'LocoYolo Account Password Reset';

        include_once(ROOTPATH.'/lib/mailer.php');
        $mail = new mailer();
        $mail->addTo($to, ucwords($touser->firstname.' '.$touser->lastname));
        $mail->setFrom($emailfrom, ucwords($fromuser->firstname.' '.$fromuser->lastname));
        $mail->setSubject($subject);
        $mail->setMessage($emailmessage);
        $mail->send();

    }
// Check name

//    }
}
?>
<div class="container forgotpass">
    <div class="col-sm-6">
        <img src="images/front_page_clip.jpg" width="90%">
    </div>
    <div class="jumbotron col-sm-6">
           <h3 style="font-weight:normal">Reset account password</h3>
           <p style="font-weight:normal">
               <span class="ArialVeryDarkGrey15">Please enter your email to receive the password reset code.</span>
           </p>
            <?php echo $error ?><?php echo $message; ?>
    <form method="post" action="">
        <div class="form-group">
            <label for="email">Email address:</label>
                &nbsp;<input name="email" type="text" class="form-control textboxbottomborder" id="email" size="40" />
        </div>
        <button type="submit" name="submit" class="btn btn-default">Submit</button>

</form>
</div>
</div>
