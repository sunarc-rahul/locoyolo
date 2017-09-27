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
        $error = '<h1 class="ArialVeryDarkGrey15" style="color:#963">&nbsp;&nbsp;&nbsp;Your email address does not exist. Please sign up</h1><br /><div style="height:10px"></div>';

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
        $message = '<h1 class="ArialVeryDarkGrey15">&nbsp;&nbsp;&nbsp;<strong>A password reset code has been sent to your email.</strong></h1><div style="height:10px"></div>';

        $emailmessage='<html>

			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			</head>
			<body>

			<div>
					<p>Hello '.$_POST["firstname"].'!</p>
				
					<p>We have received your request to reset your LocoYolo account password. Please go to this <a href ="ROOTURL./.resetaccount.php?requestid='.$requeststring.'">link</a> to reset your password.</p>
					
					<p>We have received your request to reset your LocoYolo account password. Please go to this<a class="header_forgot_link" href="<?php print CreateURL(\'index.php\',\'mod=user&do=resetaccount\');?>">link</a> to reset your password.</p>
					<p><strong>LocoYolo Team</strong></p>

			</div>
			</body>
			</html>';
        $to      = $_POST["email"];
        $subject = 'LocoYolo Account Password Reset';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: mail@locoyolo.com' . "\r\n";

        mail($to, $subject, $emailmessage, $headers);

        echo "<br>".$message."<br><br>";
    }
// Check name

//    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<body>
<div style="height:140px"></div>
<form method="post" action="">
    <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="tableorangeborder">
        <tr>
            <td height="40" colspan="2"><p class="ArialRedBold15" style="font-weight:normal"><font class="ArialVeryDarkGreyBold20">&nbsp;&nbsp;Reset account password</font></p>
                <p class="ArialRedBold15" style="font-weight:normal">&nbsp;&nbsp;&nbsp;<span class="ArialVeryDarkGrey15">Please enter your email to receive the password reset code.</span><br />
                </p>
                <?=$error ?><?=$message ?></td>
        </tr>
        <tr>
            <td height="40" align="right" valign="middle" class="ArialOrange18"><span class="ArialVeryDarkGrey15">&nbsp;Email:</span></td>
            <td class="ArialOrange18">
                &nbsp;<input name="email" type="text" class="textboxbottomborder" id="email" size="40" /></td>
        </tr>
        <tr>
            <td height="40" colspan="2" align="center" class="ArialOrange18"><table align="center">
                    <tr>
                        <td height="29"><input class="standardbutton" style="cursor:pointer" type="submit" id="submit" name="submit" value="Submit"></td>
                    </tr>
                </table>
                <div style="height:10px"></div>
            </td>
        </tr>
    </table>
</form>


</body>
</html>