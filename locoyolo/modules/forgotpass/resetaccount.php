<?php
error_reporting (E_ALL ^ E_NOTICE);
$post = (!empty($_POST)) ? true : false;

$requestid = $_GET["requestid"];
//$requestid = 'UHnfX8xehcqfmlw';

$sql = "SELECT * from account_recovery_stby where requestid='$requestid'";
$stmt = $DB->RunSelectQuery($sql);
$numofrows = Count($stmt);
foreach ($stmt as $data){

    $result = (array)$data;
    $useremail = $result["email"];
}

if($post)
{
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

    $error = "";
    $message = "";

    if ($_POST["password1"] == "") {
        $error .= '<font class="ArialVeryDarkGrey15" style="color:#963">&nbsp;&nbsp;&nbsp;Please enter a valid password.</font><br>';
        $errorcheck = true;
    }else if (strlen($_POST["password1"]) <8) {
        $error .= '<font class="ArialVeryDarkGrey15" style="color:#963">&nbsp;&nbsp;&nbsp;Please enter password of at least 8 characters.</font><br>';
        $errorcheck = true;
    }
    if ($_POST["password1"] !== $_POST["password2"]) {
        $error .= '<font class="ArialVeryDarkGrey15" style="color:#963">&nbsp;&nbsp;&nbsp;You have not re-entered your password correctly.</font><br>';
        $errorcheck = true;
    }
    if (!$errorcheck){

        $frmdata = array('password' => md5($password1));
        $DB->UpdateRecord('public_users', $frmdata, 'email="' . $useremail . '"');

        $message = '<font class="ArialVeryDarkGrey15">&nbsp;&nbsp;&nbsp;Your account has been updated.</font><br><div style="height:10px"></div>';

        $DB->DeleteRecord('account_recovery_stby','requestid="'.$requestid.'"');

        $successful = "YES";
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Untitled Document</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <? include "header_login.php"; ?>
</head>

<body>
<div style="height:140px"></div>
<form method="post" action="">
    <table width="450" border="0" align="center" cellpadding="0" cellspacing="0" class="tableorangeborder">
        <tr>
            <td height="40" colspan="2"><p class="ArialRedBold15" style="font-weight:normal"><font class="ArialVeryDarkGreyBold20">&nbsp;&nbsp;Reset account password</font></p>
                <?=$error ?>
                <?=$message ?></td>
        </tr>
        <? if ($successful !== "YES"){ ?>
            <tr>
                <td height="40" align="right" valign="middle" class="ArialOrange18"><span class="ArialVeryDarkGrey15">&nbsp;Enter new password:</span></td>
                <td class="ArialOrange18">
                    &nbsp;<input name="password1" type="password" class="textboxbottomborder" id="password1" size="27" /></td>
            </tr>
            <tr>
                <td height="40" align="right" valign="middle" class="ArialOrange18"><span class="ArialVeryDarkGrey15">&nbsp;Re-enter password:</span></td>
                <td class="ArialOrange18">&nbsp;<input name="password2" type="password" class="textboxbottomborder" id="password2" size="27" /></td>
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
        <? } ?>
    </table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

</body>
</html>