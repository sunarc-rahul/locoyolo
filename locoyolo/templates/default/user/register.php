<?php
		/**
		 * 	@author :  Akshay Yadav
		 * Date         :- 06 July,2014
		 * Template     :- user
		 * Purpose      :- To register a new candidate
		 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Assessall</title>

<script>
$.noConflict();
$(document).ready(function(){
	
	$('#candidate_id').change(function(){
		
		var val = $(this).val();
		if(val == '')
		{
			$('#validate_image').hide();
			return true;
		}

		$.ajax({
			url : '<?php echo CreateUrl('index.php', 'mod=user&do=validate_username'); ?>',
			type  : 'POST',
			data : $(this).serialize(),
			success : function(result){

				var src = 'wrong.gif';
				if(result == 1){
					src = 'right.gif';
				}

				src = '<?php echo ROOTURL.'/images/'; ?>' + src;
				$('#validate_image').attr('src', src).show();
			}
		});
	});
});

</script>
<style>

		@font-face {
	 	font-family: "font1";
	  	src: url("<?php echo ROOTURL;?>/css/fonts/BebasNeue.otf");
	  	src: local("?"),
		url("<?php echo ROOTURL;?>/css/fonts/BebasNeue.otf") format("woff"),
		url ("<?php echo ROOTURL;?>/css/fonts/BebasNeue.otf") format("opentype"),
		url("<?php echo ROOTURL;?>/css/fonts/BebasNeue.otf") format("svg");
	}
	{
		margin: 0;
		padding: 0;
	}
	 
	
	header, footer, aside, nav, article {
		display: block;
	}
	 
	body {
			margin: 0 auto;
			
			font: 13px/22px Helvetica, Arial, sans-serif;
			background:#f1f1f1 ;
		}
	header #top_head{
	min-height:25px; background-color:#066a75; color:#fff; font:"Times New Roman", Times, serif; font-size:100%; padding:3px;
	opacity: 0.7;
    filter: alpha(opacity=40); /* For IE8 and earlier */}	
	header #headtext{
	background:#fff; font-size:0.7em !important; padding:5px; padding-top:1px; /*font-family:font1, Times, serif;*/ width:350px;letter-spacing:1px; min-height:25px;color:#1d3c41; text-align:center; margin:auto; box-shadow:0 0 3px #666;
	}
	#register-link{ background:#066a75 !important;font-size:1em; font-weight:bold; width:80%; min-height:25px; margin:auto; margin-top:15px; color:#fff;border-radius:0px; /*font-family:font1; */padding:5px 0 !important;}
	#register-link a { color:#FFFFFF; font-size:1em}
	#signup_wrp{
	/*height:500px;*/ height:auto; min-height:460px;margin:20px auto auto; width:450px;background-color:#f7f7f7; box-shadow:0px 0px 2px #ccc; border-bottom: solid 3px #066a75}
	.signup_top{ text-align:center;}
	
	.signup_top h1{ font-size:1.6em; font-weight:normal; color:#066a75; /*font-family:font1;*/ padding-top:10px}
	 #hr2{ width:75%; height:1px; margin:auto; background:#99bcc1; margin-bottom:10px}
	 .signup_form{ width:100%; margin:auto;min-height:345px}
	 #form_wrp{ width:80%; margin:auto}
	.signup_form input[type=text],input[type=password]{ width:100%; height:25px; margin:auto; margin-bottom:10px; border:solid 2px #99bcc1;/*font-size:1.5em;*/font-weight:normal;}
	.signup_form input[type=submit]{ height:30px; width:20%; text-align:center; /*padding:5px; font-family:font1;*/ font-size:1.1em; font-weight:bold; letter-spacing:1px; margin-bottom:8px; /*margin-top:10px;*/  color:#fff; background-color:#066a75; border:none; box-shadow:none}
	.signup_form input[type=button]{ height:30px; width:20%; text-align:center; /*padding:5px; font-family:font1; */font-size:1.1em;font-weight:bold;/*margin-top:10px;*/ margin-bottom:8px; color:#fff; background-color:#066a75; border:none; box-shadow:none}
	.signup_form input[type=submit]:active,.signup_form input[type=button]:active{background-color:#fff;color:#066a75; border: solid 1px #066a75;}
	#form_wrp span{ color:#066a75; /*letter-spacing:0.5px;*/ font-weight:bold; /*font-family:font1;*/ font-size:1.1em}
	#form_wrp a { /*font-family:font1;*/ font-size:1.2em; padding-top:10px; color:#066a75}
	.login_bottom{ width:75%; min-height:25px; padding:8px; background:#066a75; margin:auto; color:#fff; font-family:font1; font-size:1.5em; text-align:center}
	.login_bottom a { color:#fff}
	.ui-grid-b{ font-family:font1; text-align:center; font-size:1em;}
	#form_wrp{ /*font-family:font1 !important */}
	#mandatory{/*font-family:font1; */ font-size: 12px;}
	#register-link a { color:#fff !important}
	@media only screen 
	and (min-device-width : 310px) 
	and (max-device-width : 359px) { 
		.signup_form { min-height:615px}
		#top_head{ text-align:center}
		#register-link{ width:80%}
		.signup_form input[type="submit"]{ width:25%}
		.signup_form input[type="button"]{ width:25%}
		#signup_wrp{ height:522px; width:250px}
		header #headtext{ width:275px; font-size:1.4em}
	}
	@media only screen 
	and (min-device-width : 321px) 
	and (max-device-width : 550px) { 
		.signup_form { min-height:265px}
		#top_head{ text-align:center}
		#signup_wrp{ width:300px; height:500px}
		.signup_form input[type="submit"]{ width:25%}
		.signup_form input[type="button"]{ width:25%}
		header #headtext{ width:270px; font-size:1.4em}
	}
	@media only screen 
	and (min-device-width : 551px) 
	and (max-device-width : 768px) { 
	
		#signup_wrp{ height:500px; width:500px}
		.header #headtext{ width:325px; font-size:1.6em}
	}
</style>
<script>
	$(document).ready(function(){
	$('#red_cross').click(function(){
	$('#msg').fadeOut('slow');
	});
	
	})	
	
</script>
<script>  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');  ga('create', 'UA-54129281-1', 'auto');  ga('send', 'pageview');</script>
</head>

<body onLoad="document.getElementById('first_name').focus()">
	<header>
			<div id="top_head">
			<img src="<?php echo ROOTURL;?>/images/logo.png" height='30'>
			</div>
			<!--<div id="headtext">
				<h1>Register to Online Examination</h1>
			</div>-->
		</header>

<section id="signup_wrp">
			
        	<div id="signup_sec">
				<div class="signup_top">
					<h1><span style="font-size:1.7em">S</span>IGN UP</h1>
				</div>
				<div id="hr2">	</div>
				<?php if(isset($_SESSION['error']))
				{
					echo'<div id="msg" style="min-height: 25px; width: 90%; background: none repeat scroll 0% 0% red; position: relative; box-shadow: 0px 0px 2px rgb(0, 0, 0); margin:0 auto; opacity: 0.7; color: rgb(255, 255, 255); font-weight: bold; padding: 8px; text-align: center;">';
					echo $_SESSION['error'];
					echo '<img id="red_cross" src="'.ROOTURL.'/css/images/3.png" style="position:absolute;top:5px;right:5px;height:20px;width:20px"/></div>';
					unset($_SESSION['error']);
				}
				
			?>
				
				
				<div class="signup_form">
			<div id="form_wrp">
						<form action="" method="post" name="form1" 
						id="form1" enctype="multipart/form-data">
					



		<div id="mandatory" align="left">Note: Fields marked by( <span style="color:#FF0000;">*</span> ) are mandatory.</div>
	




		<label><span>First Name</span><span class="red" style="color:#FF0000;">*</span>
	
		<input name="first_name" type="text" id="first_name"   value="<?php if(isset($frmdata['first_name'])) echo $frmdata['first_name']; ?>" onchange="isChar(this.value,this.id, 'first name');" maxlength="50"  />
	</label>


		<label><span>Last Name</span><span class="red" style="color:#FF0000;">*</span>
	
		<input name="last_name" type="text" id="last_name" size="25" 
		value="<?php if(isset($frmdata['last_name'])) 
		echo $frmdata['last_name']; ?>" onchange="isChar(this.value,this.id, 'last name');"
		 maxlength="50"  />
		 </label>
	<label><span>Username</span><span class="red" style="color:#FF0000;">*</span>
	<img src="" id="validate_image" style="display: none;" />
	<input name="candidate_id" type="text" id="candidate_id" size="25" 
	value="<?php if(isset($frmdata['candidate_id']))
	 echo $frmdata['candidate_id']; ?>" maxlength="50" 	 onchange="managePageCheckIsAlphaNum(this, 'username');" />
		
	</label>
		<label><span>Email</span><span class="red" style="color:#FF0000;">*</span>
		<input name="email" type="text" id="email" size="25" 
		 value="<?php if(isset($frmdata['email'])) echo $frmdata['email']; ?>"  onchange="CheckEmailId(this);" maxlength="100" /></label>
		 
<!--		<label for="state"><span>City</span>
		<input name="state" type="text" id="state" size="25"  class="ui-widget"
		value="<?php //if(isset($frmdata['state'])) echo $frmdata['state']; else echo $result_date[0]->stateID; ?>" onchange="isChar(this.value,this.id, 'state');" maxlength="50"  />	-->
		

		<label><input type="submit"  name="register_user" value="Submit" />
		</label><label>
		<input type="button"  value="Cancel"  onclick="location.href='<?php if($_GET['t']&& $_GET['e']){echo CreateUrl('index.php','mod=user&do=login&link=dir&e='.$_GET['e'].'&t='.$_GET['t']);}else{echo CreateUrl('index.php','mod=user&do=login'); }?>';"/></label>


</form>
</div>
</div>

<div id="register-link"> <a href="<?php if($_GET['t']&& $_GET['e']){echo CreateURL('index.php',"mod=user&do=login&link=dir&e=".$_GET['e']."&t=".$_GET['t']);}else{echo CreateURL('index.php',"mod=user&do=login");} ?>">Already Registered? Click Here</a>.</div>
				
				</div>
				
			

<?php include_once(TEMP."/"."footer.php"); ?>
</body>
</html>