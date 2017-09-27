<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Online Examination - Change Password</title>
	<link href="<?php echo ROOTURL;?>/css/exam.css" rel="stylesheet" type="text/css"/>
</head>

<body onload="document.addfrm.old_password.focus();">
<div id="outerwrapper" style="width:700px;padding: 10px 0;margin: auto;">
	<table border="0" cellspacing="0" cellpadding="0" id="tbl_outer" style="margin: auto;">
	<tr>
		<td>
			<div id="content" style="width: 650px;">
				<div id="main">
					<div id="contents">
						<form id="addfrm" name="addfrm" method="post">
							<fieldset class="rounded">
								<legend>Change Password</legend>
			<?php
				if(isset($_SESSION['error']))
				{
					echo'<table cellspacing="0" cellpadding="0" border="0" align="center" width="60%" style="border:2px #CCCCCC solid;margin-top:5px;"><tbody><tr><td align="center" style="padding:3px 3px 3px 3px;color:red;">';
					echo $_SESSION['error'];
					echo '</td></tr></tbody></table>';
					unset($_SESSION['error']);
				}
				if(isset($_SESSION['success']))
				{
					echo'<table cellspacing="0" cellpadding="0" border="0" align="center" width="60%" style="border:2px #CCCCCC solid;margin-top:5px;"><tbody><tr><td align="center" style="padding:3px 3px 3px 3px;color:green;">';
					echo $_SESSION['success'];
					echo '</td></tr></tbody></table><br/>';
					unset($_SESSION['success']);
				}
			?>
					
<table width="100%" border="0" cellpadding="4" cellspacing="0" id="tbl_preferences">
<tr>
	<td colspan="4">
		<div id="mandatory" align="left">&nbsp;&nbsp;<?php echo MANMES; ?></div>
	</td>
</tr>

<tr>
	<td align="right">Old Password:<span class="red">*</span></td>
	<td>
		<input name="old_password" id="old_password" type="password" class="rounded textfield" maxlength="25" value="" />
	</td>
</tr>

<tr>
	<td align="right">New Password:<span class="red">*</span></td>
	<td>
		<input name="new_password" id="new_password" type="password" class="rounded textfield" maxlength="25" value="" onchange="checkPassword(this);" />
	</td>
</tr>

<tr>
	<td align="right">Confirm Password:<span class="red">*</span></td>
	<td>
		<input name="confirm_password" id="confirm_password" type="password" class="rounded textfield" maxlength="25" value="" />
	</td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr>
	<td>&nbsp;</td>
	<td colspan="3">
		<div align="left">
			<input type="submit" name="changepassword" id="changepassword" value="Change" class="buttons rounded" />
			<input type="reset" name="reset" value="Reset" class="buttons" onclick="document.addfrm.old_password.focus();" />
		</div>
	</td>
</tr>

<tr><td>&nbsp;</td></tr>

</table>

						</fieldset><br/>
					</form>
		  		</div><!--Div Contents closed-->
			</div><!--Div main closed-->
		</div><!--Content div closed-->
	</td>
</tr>
</table>
	
</div><!--Outer wrapper closed-->
</body>
</html>