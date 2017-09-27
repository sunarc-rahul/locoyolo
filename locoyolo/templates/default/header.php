<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?echo ($pageTitle == '' ? $pageTitle='Locoyolo' :$pageTitle );?></title>
    <script type="text/javascript" src="<?php echo JS; ?>/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="<?php echo JS; ?>/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo JS; ?>/functions.js"></script>
	<script src="<?php echo JS; ?>/all.js"></script>

    <link rel="stylesheet" href="<?php echo STYLE; ?>/bootstrap.min.css">

    <?php if($mod=='ping' && $do=='createPing' || $do=='editping' ||  $do=='editprofile')
    { ?>
               <script type="text/javascript" src="<?php echo ROOTURL; ?>/lib/bootstrap-datetimepicker-master/src/js/moment-with-locales.js"></script>
        <script type="text/javascript" src="<?php echo ROOTURL; ?>/lib/bootstrap-datetimepicker-master/src/js/bootstrap-datetimepicker.js"></script>
    <link href="<?php echo STYLE; ?>/bootstrap-datepicker3.min.css" rel="stylesheet">
   <?php }
    else
    {?>
        <script type="text/javascript" src="<?php echo ROOTURL; ?>/lib/bootstrap-daterangepicker-master/moment.js"></script>
        <script type="text/javascript" src="<?php echo ROOTURL; ?>/lib/bootstrap-daterangepicker-master/daterangepicker.js"></script>
    <link rel='stylesheet' type='text/css' href='<?php echo ROOTURL; ?>/lib/bootstrap-daterangepicker-master/daterangepicker.css' />
   <?php }?>
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/local.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/style.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/style2.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/locoyolo.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/css.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/custom_rahul.css' />
    <link rel='stylesheet' type='text/css' href='<?php echo STYLE; ?>/media.css' />
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        .timeonly.daterangepicker .calendar-table,
        .timeonly.daterangepicker .daterangepicker_input .input-mini.form-control,
        .timeonly.daterangepicker .daterangepicker_input .fa.fa-calendar.glyphicon.glyphicon-calendar{display:none}
    </style>

    <link rel='stylesheet' type='text/css' href='<?php echo ROOTURL; ?>/lib/bootstrap-daterangepicker-master/daterangepicker.css' />
    <?php if(($mod=='' || $mod=='user' || $mod=='ping' || $mod=='event')&&($do==''||$do=='Search'||$do=='eventdetails'||$do=='pingdetails')){?>

        <script src="<?php echo JS; ?>/modernizr.js"></script>
        <script>
        $(window).load(function() {
            // Animate loader off screen
            $(".se-pre-con").fadeOut("fast");
        });

    </script>
    <?php } ?>
</head>
<body class="has-footer">
<?php if(($mod=='' || $mod=='user' || $mod=='ping' || $mod=='event')&&($do==''||$do=='Search'||$do=='eventdetails'||$do=='pingdetails')){?>
<div class="se-pre-con"></div> <?php } ?>

<div id="header-wrapper" class=" logged-in logged-in-header">
        <div class="row header" id="logged-in-row-header">
            <div id="logo" class="logo_div col-md-2"><a href='<?php echo ROOTURL;?>'><img src="images/logo (height 40px).jpg" height="35" /></a></div>
        <div class="right-side-wrapper <?php if($user_id){?>col-md-10<?php }else {?> col-md-7<?php } ?>">
<?php
if($user_id != " ")
{?>
<!-------------------------------------------------------------------->

          <div class=" col-md-5 search-bar">
              <form action="<?php echo createURL('index.php',"mod=search&do=searchlist")?>" method="post" role="search">
                  <div class="col-lg-10" style="float: right; margin-top: 8px;">
                      <div class="input-group">
                          <input type="text" name="search" id="inputSearch" value="<?php echo $_REQUEST['s'] ? $_REQUEST['s']:''; echo $_REQUEST['search']?$_REQUEST['search']:'';  ?>" class="search form-control " autocomplete="off" placeholder="Search for Locoyolo">
                          <span class="input-group-btn" >
                              <button class="btn btn-default" id='searchsubmit' name="searchsubmit" type="button"><i class="glyphicon glyphicon-search" aria-hidden="true"></i></button>
                            </span>
							<div id="divResult"></div>
                      </div><!-- /input-group -->
                  </div><!-- /.col-lg-6 -->
              </form>

          </div>
          <div id="icons" class=" col-md-5 icons_div">
              <div class="col nav">
              <div class="navbar-icon icon_1 dropdown" title='Notifications'><button  class=" dropdown-toggle" type="button" id="updateStatus" data-toggle="dropdown" >N</button>
                  <ul class="dropdown-menu dropdown-menu-right">
                      <li>
<!--             dropdown for notifications         //////////////////////////////////////-->

                      <div id="event-notifications-page-wrapper">
                              <div class=" goes-on-left" id="id-for-notifications-page">
                                  <div class="row has-notifications">

                                      <!-- New Notification is added and show   -->
                                      <?php
                                      $sql = "Select count(*) as total from notifications where other_user_id=$user_id ";
                                      $notificationExist = $DB->RunSelectQuery($sql);
                                      $token = $notificationExist[0]->total;

                                        if ($token == 0)
                                        {

                                            ?>

                                            <h1 class="ArialVeryDarkGrey15 no-notification"> No Notifications to show.</h1>
                                            <?php
                                        }else {


                                            $status = "Seen";
                                            $sql = "Select * from notifications where other_user_id=$user_id and status='$status' order by id desc limit 5";
                                            $res_seen_notification = $DB->RunSelectQuery($sql);

                                           $old = count($res_seen_notification);

                                            $status = "Pending";
                                            $sql = "Select count(*) as total from notifications where other_user_id=$user_id and status='$status' order by id desc limit 5";
                                            $res_Pending_notification = $DB->RunSelectQuery($sql);
                                        $new = $res_Pending_notification[0]->total;

                                            $dateset = "";
                                        //  if no new notification for user than it will show older one
                                            if ($new == 0) {

                                                $status = "Seen";
                                                $limit = 5;
                                                showUserNotifications($status, $limit);
                                        //    if new there is notification for user than it will show new and remaining older one notification upto 5
                                            } elseif ($old - $new > 0) {

                                                $status = "Pending";
                                                showUserPendingNotifications($status);

                                              $limit = $old - $new;
                                                $status = "Seen";
                                                showUserNotifications($status, $limit);
                                            } elseif ($new > 0) {

                                                $status = "Pending";
                                                showUserPendingNotifications($status);

                                            } else {

                                            }
                                        }
                                                                              ?>

                                  </div>
                              </div>
                      </div>

                      </li>

<!--                      //////////////////////////////////////-->
                      <li><a onclick="location.href='<?php print CreateURL('index.php','mod=notification&do=notification');?>'">See all notification</a></li>
                  </ul>
                  <?php  $status = "Pending";

                   $sql = "Select * from notifications where other_user_id=$user_id and status = '$status'";
                   $res = $DB->RunSelectQuery($sql);

                  if(!is_array($res))
                  {
                      $res = array();
                  }
                  if (count($res) > 0){
                      ?>
                      <strong class="no-of-notification" id="number">
                          <?
                          echo count($res);
                          ?>
                      </strong>
<!--                      </strong> new notifications-->
                  <? } else { ?>
<!--                      Notifications-->
                  <? } ?>
              </div>

              <div class="navbar-icon icon_2" title='Ping a meetup'><button onclick="location.href='<?php print CreateURL('index.php','mod=ping&do=createPing');?>'" class="slimbuttonblue" type="button" class="btn btn-default">P</button></div>

              <div class="navbar-icon icon_3" title='Organise an event'><button onclick="location.href='<?php print CreateURL('index.php','mod=event&do=createEvent');?>'" class="slimbutton" type="button" class="btn btn-default">O</button></div>

                <div class="dropdown">
                    <div class="dropbtn">
                        <?php
                        $sql = "SELECT * from public_users where id=$user_id";
                       $before_result = $DB->RunSelectQuery($sql);
                        foreach($before_result as $result){
  						 $result = (array) $result;
  //						 print_r($result['profile_pic']);exit;
  						if ($result['profile_pic'] == null ) {
                                ?>
                                <img width="25" height="25" style="border:#CCC 2px solid; border-radius:12.5px" src="<?php echo ROOTURL;?>/images/no_profile_pic.gif" />
                            <?php }else{
                                ?>
                                <img style="border:#CCC 2px solid; border-radius:12.5px" src="<? echo ROOTURL.'/'.$result["profile_pic"]; ?>" width="25" height="25" />
                            <?php }  } ?>
                    </div>

                        <div class="dropdown-content"><a href="<?php echo CreateURL('index.php',"mod=user&do=profile"); ?>"><span class="ArialVeryDarkGrey15">My Profile</span></a><a href="<?php echo CreateURL('index.php',"mod=user&do=logout"); ?>"><span class="ArialVeryDarkGrey15">Logout</span></a></div>


                </div><!-- first  -->

              </div> <!-- Second -->
          </div> <!--Third -->

            <?php } ?>
          </div> <!--  fourth -->
      </div> <!--  fifth -->



  </div>



<?php if($user_id!=''){?>

<script type="text/javascript">

    $(function(){
        $(".search").keyup(function()
        {
            $("#divResult").html('');
            var inputSearch = $(this).val();
            var dataString = 's='+ inputSearch;
            if(inputSearch!='')
            {
                $.ajax({
                    type: "POST",
                    url: "<?php echo CreateURL('index.php',"mod=ajax&do=mainsearch");?>",
                    data: dataString,
                    cache: false,
                    success: function(html)
                    {
                        $("#divResult").html(html).show();
                    }
                });
            }return false;
        });
    $('#searchsubmit').click(function(){
        var search =  $('#inputSearch').val();
        window.location.href= '<?php echo createURL('index.php',"mod=search&do=searchlist&search=")?>'+search;
    });
        $('#inputSearch').click(function(){
            jQuery("#divResult").fadeIn();
        });
		$('span.error-close').click(function(){
		{ $('.Show-error').hide(); }
            $('.shows-errors_div').hide();
	});
    });
   $("#inputSearch").blur(function(){
        setTimeout(function(){
            $("#divResult").html('');

        },'200'); });

</script>
    <script>
        $(document).ready(function () {

            var updateStatus = document.getElementById("updateStatus");
            updateStatus.addEventListener('click', function () {
                 var userid = "<?php echo $user_id; ?>";
                $.ajax({
                    type: "POST",
                    url: "<?php echo createURL('index.php', "mod=ajax&do=fetch_notifications");?>",
                    data: {user_id: userid},
                    //dataType: 'json',
                    cache: false,
                    success: function (data) {

                        if (data == 'Y') {

                            document.getElementById('number').style.display = "none";
                        } else {
                            document.getElementById('number').style.display = "block";
                        }


                    }
                });


            });


        });

    </script>
<?php }?>
</html>