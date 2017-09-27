<?php

include_once(TEMPPATH . "/header.php");

 if(isset($_SESSION['error']))
{
    $_POST = $_SESSION['error_post'];
    unset($_SESSION['error_post']);
    ?>
    <div class="shows-errors_div has-error-message">
        <div class="container is-error-message">
            <div class="Show-error">
                <span class="error-close">X</span>
                            <div class="alert alert-danger">
                              <?php  echo $_SESSION['error'];

                                unset($_SESSION['error']);
                               ?>
                            </div>
            </div>
        </div>
    </div>
<?php } if(isset($_SESSION['success'])) {

?>
    <div class="shows-errors_div has-error-message">
        <div class="container is-error-message">
            <div class="Show-success">
                <span class="error-close">X</span>
                <div class="alert alert-success">
                    <?php  echo $_SESSION['success'];
                    unset($_SESSION['success']);?>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

<!---->
<!--?>  <div class="editProfile">-->
<!--    <div class="col-md-12 errors">-->
<!--        --><?php //if(isset($_SESSION['error'])||isset( $_SESSION['success']))
//        {
//            echo'<div id="msg" style="margin:0 auto; opacity: 0.7; color: #ff0000; font-weight: bold; padding: 8px; text-align: center; top:82px">';
//            echo $_SESSION['error'];
//            echo $_SESSION['success'];
//            unset($_SESSION['error']);
//            unset($_SESSION['success']);
//        }
//        ?>
<!--    </div>-->

<!--</div>-->
<div class="container fixed-footer">
    <h1>Edit your profile</h1>
    <form id="editprofile-details-form" method="post" action="" enctype="multipart/form-data">
    <div class="row edt-img-banner">
        <div class="edt-img-sec">

                <!--        <form id="editprofile-photo-form"  method="post" action="" enctype="multipart/form-data">-->
                <h2><span class="ArialOrange18">Update profile photo</h2>

                <?php

                if ($userData->profile_pic !== "") {

                    ?>
                    <img style="border-radius:100px" src="<?php echo ROOTURL."/".$userData->profile_pic ; ?>" width="200" />
                    <?
                }else{
                    ?>
                    <img style="border-radius:100px" src="images/no_profile_pic.gif" width="200" />
                    <?
                }
                ?>
                <div class="form-group">
                    <div class="uploadimg"> <input name="profile_photo" type="file"
                                                   id="profile_photo" /></div>

                    <br>
                    <input type="hidden" id="user_email_forphoto" name="user_email_forphoto"
                           value="<?php echo $userData->email; ?>"
                </div>
                <!--       <input class="btn btn-primary"  style="cursor:pointer" type="submit" id="update_photo_submit"-->
                <!--              value="Update photo" name="update_photo_submit" >-->

                <!--        </form>-->
        </div>
    </div>
    </div>
    <div class="row">


        <ul class="nav nav-tabs profile-edit-tabs">
            <li class="active"><a data-toggle="tab" href="#general">General info</a></li>
            <li><a data-toggle="tab" href="#contact">Contact Details</a></li>
            <li><a data-toggle="tab" href="#about">About</a></li>
        </ul>

        <div class="tab-content">
            <div id="general" class="tab-pane fade in active">
                 <div class="form-group">
                    <label for="email"><span class="mandatory">*</span>First name:</label>
                    <input name="firstname" type="text"class="form-control"  id="event_name4"
                           value="<?php echo $_POST['firstname']?$_POST['firstname']:$userData->firstname; ?>" size="30" />
                </div>
                <div class="form-group">
                    <label for="email"><span class="mandatory">*</span>Last name:</label>
                    <input name="lastname" type="text" class="form-control"  id="lastname"
                           value="<?php echo  $_POST['lastname']?$_POST['lastname']:$userData->lastname;?>" size="30" />
                </div>
                <div class="form-group">
                    <label for="email"><span class="ArialVeryDarkGrey15">Date of birth:</span></label>
                    <div class='input-group date' id='datetimepicker'>
<!--                        -->
                        <input type='text'  name="birthdate" value="<?php echo  $_POST['birthdate']?$_POST['birthdate']:$userData->birthdate; ?>"  id="timerange" class="form-control" />
                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
                    </div>

                </div>
                <?php $gender = $userData->gender;?>
                <div class="form-group">




                    <label  for="email"><span class="ArialVeryDarkGrey15">Gender:</span></label>

                    <div class="row">
                        <div class="col-sm-4">
                            <label class="radio-inline">
                                <input type="radio" name="gender" id="femaleRadio"
                                    <?php if(isset($_POST)&&count($_POST)>=1)
                                    {
                                        if(isset($_POST['gender'])&& $_POST['gender']=='female')
                                        {
                                            echo 'checked';
                                        }
                                    }
                                    else
                                    {
                                        if(isset($gender)&& $gender=='female')
                                        {
                                            echo 'checked';
                                        }
                                    }?>
                                       value="female">Female
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label class="radio-inline"> <input type="radio" name="gender" id="maleRadio" <?php if(isset($_POST)&&count($_POST)>=1)
                                {
                                    if(isset($_POST['gender'])&& $_POST['gender']=='male')
                                    {
                                        echo 'checked ';
                                    }
                                }
                                else
                                {
                                    if(isset($gender)&& $gender=='male')
                                    {
                                        echo 'checked ';
                                    }
                                } ?>
                                 value="male">Male
                            </label>
                        </div>
                    </div>
                   <br> <input class="btn btn-primary" style="cursor:pointer" type="submit" name="update_details_submit"
                           id="update_details_submit" value="Update details">
                </div>

            </div>
            <div id="contact" class="tab-pane fade">

                <div class="form-group number_field">

                    <label for="mobile_number">Mobile number:</label>
                      <?php
                      $number_verified = $DB->SelectRecord('user_contact_varification_status', "user_id=$userData->id and mobile_number='".$userData->contact."' and is_verified='Y'");
                      if($number_verified)
                      {
                          echo "<h5>".$userData->contact.'</h5> <b><span><img src="images/green_tick.gif"></span>  Verified</b>';
                      }else
                      {
                          ?>
                    <input name="contact" type="text"class="form-control"  id="mobile_number"
                           placeholder="Enter mobile number with country code" size="20" value="<?php  echo  $_POST['contact']?$_POST['contact']:$userData->contact;  ?>" />
                          <?php if($userData->contact){echo "<b style='color:#cb2027'>This number not verified. Please verify by following options.</b><br><br>";}?>
                    <button type="button" id="send_sms" class="btn btn-primary">Send SMS</button>
<!--                    <button type="button" id="call_me" class="btn btn-primary">Call me</button>-->
                    <?php } ?>
                </div>
                <div class="form-group verify_field" style="display:none">
                    <label for="code">Verification code:</label>
                    <input name="code" type="text"class="form-control"  id="verification_code"
                           placeholder="Enter Code" size="20" />
                    <button type="button" id="verify" class="btn btn-primary">Verify</button>
                </div>

               <br> <input class="btn btn-primary" style="cursor:pointer" type="submit" name="update_details_submit"
                       id="update_details_submit" value="Update details">
            </div>
            <div id="about" class="tab-pane fade">
                <div class="form-group">
                    <label for="email"><span class="mandatory">*</span>Achievement:</label>
                    <textarea name="achievements"  class="form-control"
                              placeholder="Achievement & Skills"><?php echo  $_POST['achievements']?$_POST['achievements']:$userData->achievements; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="email"><span class="mandatory">*</span>About me:</label>
                    <textarea name="mood_statement" cols="50" rows="10" class="form-control"
                              id="mood_statement" placeholder="Describe yourself..."><?php  echo  $_POST['mood_statement']?$_POST['mood_statement']:$userData->mood_statement; ?></textarea>
                </div>
                <br>
                <input class="btn btn-primary" style="cursor:pointer" type="submit" name="update_details_submit"
                       id="update_details_submit" value="Update details">
            </div>

        </div>

        <?php if(isset($message)){?><div id="result"><?php echo $message ?></div><?php }?>




        <input type="hidden" id="user_email_fordetails" name="user_email_fordetails"
               value="<?php $userData->email; ?>" />



    </form>
</div>


<script type="text/javascript">

    $(function () {
        $('#datetimepicker').datetimepicker({
            format: 'D/M/YYYY',
            date: '<?php echo $userData->birthdate?$userData->birthdate:date('m/d/Y') ?>'
        })
    });
    $('span.error-close').click(function(){
        {
            //$('.Show-erroe').hide();
        }
    });





    $('#send_sms').click(function()
    {
    var mobile_number = $('#mobile_number').val();
        if(mobile_number=='')
        {
            alert('Please enter mobile number.');
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo CreateURL('index.php', "mod=ajax&do=verify_mobile");?>",
            data: {number: mobile_number,verify_number:'yes'},
            //dataType: 'json',
            cache: false,
            success: function (data) {
                if (data == 'N') {
                    alert('Your number could not be verified right now. Please try again latter.');
                } else {
                    $('.number_field').hide();
                    $('.verify_field').show();
                }

                }
        });
    });

    $('#verify').click(function()
    {
        var verification_code = $('#verification_code').val();
        var mobile_number = $('#mobile_number').val();
        if(verification_code=='')
        {
            alert('Please enter verification code.');
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo CreateURL('index.php', "mod=ajax&do=verify_mobile");?>",
            data: {number: mobile_number,verification_code:verification_code,verify_code:'yes'},
            cache: false,
            success: function (data) {
                if (data == 'N') {
                    alert('Your number could not be verified right now. Please try again latter.');
                } else {
                    $('.number_field').show().append('<b><span><img src="images/green_tick.gif"></span> Verified</b>');
                    $('.verify_field,#send_sms,#call_me').remove();
                }

            }
        });
    })
</script>
    </div>