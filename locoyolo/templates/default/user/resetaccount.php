<?php
include_once(TEMPPATH . "/header_login.php");
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
        $error .= '<font class="ArialVeryDarkGrey15" style="color:#963">Please enter a valid password.</font><br>';
        $errorcheck = true;
    }else if (strlen($_POST["password1"]) <8) {
        $error .= '<font class="ArialVeryDarkGrey15" style="color:#963">Please enter password of at least 8 characters.</font><br>';
        $errorcheck = true;
    }
    if ($_POST["password1"] !== $_POST["password2"]) {
        $error .= '<font class="ArialVeryDarkGrey15" style="color:#963">You have not re-entered your password correctly.</font><br>';
        $errorcheck = true;
    }
    if (!$errorcheck){

        $frmdata = array('password' => md5($password1));
        $DB->UpdateRecord('public_users', $frmdata, 'email="' . $useremail . '"');

        $message = '<h5 class="ArialVeryDarkGrey15">Your account has been updated.</h5><br><div style="height:10px"></div>';

        $DB->DeleteRecord('account_recovery_stby','requestid="'.$requestid.'"');

        $successful = "YES";
    }
}
?>

<?php include "header_login.php"; ?>
<div class="container resetpass">
<form method="post" action="">

    <div class="row">
        <div class="col-sm-6 jumbotron">
        <h2>Reset account password</h2>
                    <?php echo $error ?>
                    <?php echo $message ?></td>

            <?php if ($successful !== "YES"){ ?>
        <div class="form-group">
            <label for="password1">Enter new password:</label>
            <input name="password1" type="password" class="form-control" id="password1" size="27" />
        </div>
         <div  class="form-group">

             <label for="password2">Re-enter password:</span></label>
             <input name="password2" type="password" class="form-control" id="password2" size="27" />
         </div>
         <button type="submit" name="submit" class="btn btn-default">Submit</button>
            <?php } ?>
        </div>
    </div>
</form>
</div>