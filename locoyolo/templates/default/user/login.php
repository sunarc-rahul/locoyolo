<?php
	include_once(TEMPPATH."/header_login.php")
?>
<style>
	@media screen and ( max-width:680px ) { 
		.display860 { display:none; } 
		.display320 { display:block; } 
	}
	@media screen and ( min-width:680px ) { 
		.display860 { display:block; }
		.display320{ display:none; }
	}
	.modal.fade.in { text-align:center; }
</style>







<div class="container main">
<?php 
	if(isset($_SESSION['error'])) {


echo'<div class="shows-errors_div has-error-message">';
echo' <div class="container is-error-message">';
echo' <div class="Show-error">
            <span class="error-close">X</span>';
echo' <div class="alert alert-danger">';
 echo $_SESSION['error'];

                unset($_SESSION['error']);
                echo'</div>';

echo'</div>';
echo'</div>';
echo'</div>';
}
	else {
		if (isset( $_SESSION['success'])) {


            echo'<div class="shows-errors_div has-error-message">';
            echo' <div class="container is-error-message">';
            echo' <div class="Show-error on-success">
            <span class="error-close on-success-close">X</span>';
            echo' <div class="alert alert-success">';
            echo $_SESSION['success'];

            unset($_SESSION['success']);
            echo'</div>';

            echo'</div>';
            echo'</div>';
            echo'</div>';

		}
	}
?>
	<div class="has-table-display">
		<div class="col-sm-6 index-image-on-left">
			<img src="images/front_page_clip.jpg" width="65%"/>
		</div>
		<div class="col-sm-6 index-image-on-right">
			<h1 class="ArialVeryDarkGrey30">Join us at <span class="logo-colored-text"> LocoYolo</span></h1>
			<h4 class="ArialVeryDarkGrey18">Whether you're at home or at work, there is always something to do near you!</h4>
			<form id="signup-form" method="post" action="" >
				<h2 class="ArialOrange18">Sign up<div id="result"></div></h2>
				<div class="row">
					<div class="col-sm-6 no-right-padding">
						<div class="form-group">
							<input name="firstname" type="text" class="form-control" id="firstname" size="15" placeholder="First name" value="<?php echo $_POST['firstname']?>" />
						</div>
					</div>
                    <div class="col-sm-6">
						<div class="form-group">
							<input name="lastname" type="text" class="form-control" id="lastname" size="15" value="<?php echo $_POST['lastname']?>" placeholder="Last name" />
                        </div>
                    </div>
                </div>
				<div class="form-group">
                    <input name="email" class="form-control" type="text" value="<?php echo $_POST['email'] ?>" id="email" size="35" placeholder="Email" />
				</div>
				<div class="form-group">
					<input name="password1" class="form-control" type="password" id="password1" size="35" placeholder="Password must be at least 8 characters, 1 lower case letter & 1 digit" />
				</div>
				<div class="form-group">
					<input name="password2" type="password" class="form-control" id="password2" size="35" placeholder="Re-enter password" />
				</div>
				<div class="form-group">
					<div class='input-group date' id='datetimepicker'>
						<input type='text'  placeholder="Date of Birth" name="birthdate" value="<?php echo $_REQUEST['birthdate']; ?>"  id="timerange" class="form-control" />
						<span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
					</div>
				</div>
                <input class="btn standardbutton no-margin" style="cursor:pointer" type="submit"  name="signUp_submit" value="Sign up" href="<?php print CreateURL('index.php','mod=user&do=login');?>">
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		$('#datetimepicker').datetimepicker({
			format: 'D/M/YYYY',
			date: '<?php echo $userEventData->start_date?$userEventData->start_date:date('m/d/Y') ?>'
		})
    });
	$('span.error-close').click(function(){
	{
		//$('.Show-erroe').hide();
	}
	});
</script>
<?php
include_once(TEMPPATH."/footer.php")
?>