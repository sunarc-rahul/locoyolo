<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?echo ($pageTitle == '' ? $pageTitle='Locoyolo' :$pageTitle );?></title>
    <script type="text/javascript" src="<?php echo JS; ?>/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="<?php echo JS; ?>/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo JS; ?>/functions.js"></script>
  <script src="<?php echo JS; ?>/all.js"></script>
    <script type="text/javascript"
            src="<?php echo ROOTURL; ?>/lib/bootstrap-datetimepicker-master/src/js/moment-with-locales.js"></script>
    <script type="text/javascript"
            src="<?php echo ROOTURL; ?>/lib/bootstrap-datetimepicker-master/src/js/bootstrap-datetimepicker.js"></script>
    <link rel="stylesheet" href="<?php echo STYLE; ?>/bootstrap.min.css">
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/local.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/style.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/style2.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/locoyolo.css' />    
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/css.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/custom_rahul.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/media.css' />
    <link href="<?php echo STYLE; ?>/bootstrap-datepicker3.min.css" rel="stylesheet">

</head>
<body class="has-footer">
<div id="header-wrapper" class=" logged-in logged-out-header">
        <div class="row header">
            <div id="logo" class="logo_div col-md-2"><a href='<?php echo ROOTURL;?>'><img src="images/logo (height 40px).jpg" height="35" /></a></div>
        <div class="right-side-wrapper">


<?php
   include(ROOTPATH."/lib/api/Facebook/autoload.php");
    $fb = new Facebook\Facebook([
        'app_id' => '850026601828810', // Replace {app-id} with your app id
        'app_secret' => '5c7e2e63652770b90221e6051a165a89',
        'default_graph_version' => 'v2.9',
        "persistent_data_handler"=>"session"
    ]);

    $helper = $fb->getRedirectLoginHelper();
    $permissions = ['email']; // Optional permissions
    $returnurl =  createURL('index.php', 'mod=user&do=login&from=fb');
    $loginUrl = $helper->getLoginUrl($returnurl, $permissions);

    ?>
    <div class="header-facebook-button">
                <button class="loginBtn loginBtn--facebook" id='FacebookBtn'  link="<?php echo $loginUrl; ?>">Login with Facebook</button></div>
            <div class="form_wrapper">
                <form  method="post" class="header-signin-form" name="signin-form" id="signin-form">
                    <div id="header_input" class="header_input">
                        <input name="email" type="email" id="email" size="15" value="<?php echo $_POST['email']?>" placeholder="Email">
                        <input name="password" type="password" id="password" size="15" placeholder="Password">
                        <button type='submit' name='submit' value='submit' class="slimbutton"  class="btn btn-default">Login</button>
                    </div>
                </form>
                <a class="header_forgot_link" onclick='location.href="<?php print CreateURL('index.php','mod=user&do=forgotpass');?>"'>Forgotten account?</a>
            </div>        
        </div> </div>
    </div>
</body>
<script type="text/javascript">
    var signinWin;
    $('#FacebookBtn').click(function () {

        $('span.error-close').click(function(){
            { $('.Show-error').hide(); }
        });
        var link = $(this).attr('link');
        signinWin = window.open(link, "SignIn", "width=780,height=410,toolbar=0,scrollbars=0,status=0,resizable=0,location=0,menuBar=0");
        setTimeout(CheckLoginStatus, 2000);
        signinWin.focus();
        return false;
    });

    $(function(){

        $('span.error-close').click(function(){
            { $('.Show-error').hide();
                $('.shows-errors_div').hide();
            }
        });


    });
</script>
</html>


