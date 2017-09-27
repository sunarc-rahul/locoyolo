<?php
defined("ACCESS") or die("Access Restricted");

function RecordPerPage($no)
{
	if ($no)
	{
		return $no;
	}	
	else
	{
		return 10;
	}	
}

// to fetch postdata
function Changedata()
{
	global $_POST;
	return  $_POST;
}

// to parse URLS
function ParsingURL()
{
	global $getVars;
	$urlArr=@parse_url($_SERVER['REQUEST_URI']);
 	@parse_str( Decode($urlArr['query']),$getVars);
 	@parse_str( $urlArr['query'],$getVars);
}

//	Redirect a page through javascript
function Redirect($file)
{
	echo  '<script>';
	echo  'location.href="'.$file.'"';
	echo  '</script>';
	exit();
}

// to make a link
function CreateURL($url,$querystring='',$encode=false,$redirect=false)
{
	$url = ROOTURL.'/'.$url;
	if($querystring)
	{
		if($encode)
		{
			return $url.'?'.Encode($querystring);		
		}
		else
		{
			return $url.'?'.$querystring;		
		}
	}
	else
	{
		return $url;
	}
	return false;
}

/**
 * @Auther	: Niteen Acharya
 * @para	: any value
 * @return 	: encoded value
 * @Des 	: to encode value
 */

function Encode($value)
{
	return base64_encode($value);
}

function Encrypt($value)
{
	return crypt($value, 'seigolonhcet#CRANUS321$');
}

function Decode($value)
{
	return base64_decode($value);
}

/**
 * 	@author	:	Ashwini Agarwal
 * 	@desc	:	Randomly shuffle array preserving key => value.
 * 				(Used to shuffle match type question)
 */
function shuffle_assoc($list) 
{ 
  if (!is_array($list)) return $list; 

  $keys = array_keys($list); 
  shuffle($keys); 
  $random = array(); 
  foreach ($keys as $key) 
  { 
    $random[] = $list[$key]; 
  }
  return $random; 
}
//==================== Upload Image Files After Compression =============================================//
function UploadImageFile(&$SavePath,$FileObject,$FileType)
{
    $Message='';
	$Upldate=time();
	$savefile = 1;
	
    if($FileObject->name=='')
	{ 
	   return;
	}	
	
	 $FileObject->name = str_replace(" ","",$FileObject->name);
	 $pos=strpos($FileObject->name,".");
	 $SavePath=substr($FileObject->name,0,$pos);
	 
	 
	if($FileType == 'image')
	{
		$maxsize = IMAGESIZE;
		$extension = IMAGEEXT;
	}
	else
	{
		$maxsize = FILESIZE;
		$extension = FILEEXT;
    }	
	
	//================================ Check file size ===================
	   if($FileObject->size>$maxsize)
	   { 
			 $savefile = 0;
			 $Message = MAXSIZEMESSAGE;
		}
		elseif($FileObject->size <= 0)
		{
			$savefile = 0;
			$Message = MINSIZEMESSAGE;
			
		}
	//================================ Check file type ===================
	  $fileext = explode("/",$FileObject->type); // Fetch the file type
	  $extension1 = explode(",",$extension); // Change the string into array
	  $filename = $FileObject->name; // Fetch the filename of posted file
	  $pos=strrpos($FileObject->name,"."); 
	 $ext = strtolower(substr($FileObject->name,$pos+1)); // Acheive only file extionsion
	

  //##########################################################################
	 $key = array_search($ext,$extension1); // Search the received extionsion in array  
       
	   if($extension1[$key] != $ext)
		  {
			$Message = EXTMESSAGE.$extension." files.";
	        $savefile = 0;
		  } 
      		
	//================================ copy file all validations are true ===================
		if($savefile == 1)
		{
		   $SavePath.= time().".".$ext;
		   $copypath = FILEPATH.$SavePath;
		   
		
			if(is_uploaded_file($FileObject->tmp_name))
		 	{
		 		include_once(ROOT.'/lib/image.class.php');
				$img = new thumb_image;
				$img->GenerateThumbFile($FileObject->tmp_name, $copypath);
		 		
				if(!file_exists($copypath))
				{
					$Message=FAILEDCOPYMESSAGE;
					$SavePath = '';
				}
			}
			else
			{
				$Message="File not uploaded.";
				$SavePath = '';
			}
		}
		
	return $Message;
}
/*
function MakeNewpassword()
{		
	$s=rand(time()%357,time());
	srand($s);
	$san=rand((time()-$s),time());
	$san1=rand((time()-4322),time());
	 $san2=encrypt($san.$san1,time());
	$pass=encrypt(time(),$san2);
	return $pass;
}*/
//-------------------------
function MakeNewpassword($length = 6, $add_dashes = false, $available_sets = 'luds')
{
$sets = array();if(strpos($available_sets, 'l') !== false)$sets[] = 'abcdefghjkmnpqrstuvwxyz';if(strpos($available_sets, 'u') !== false)$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';if(strpos($available_sets, 'd') !== false)$sets[] = '23456789';/*if(strpos($available_sets, 's') !== false)$sets[] = '!@#$%&*?';*/ $all = '';$password = '';foreach($sets as $set){$password .= $set[array_rand(str_split($set))];$all .= $set;} $all = str_split($all);for($i = 0; $i < $length - count($sets); $i++)$password .= $all[array_rand($all)]; $password = str_shuffle($password); if(!$add_dashes)return $password; $dash_len = floor(sqrt($length));$dash_str = '';while(strlen($password) > $dash_len){$dash_str .= substr($password, 0, $dash_len) . '-';$password = substr($password, $dash_len);}$dash_str .= $password;return $dash_str;
}
//--------------------



//------------------------
function PaginationWork($option='')
{
	global $frmdata ;

	if(!isset($frmdata['record'])) $frmdata['record'] = '';

	$recordPerPage=RecordPerPage($frmdata['record']);

	//	if page number not set or search done
	if(!isset($frmdata['pageNumber']))
	{
		$frmdata['pageNumber']=1;
	}
	if($recordPerPage!='All')
	{
		// at first page
		if($frmdata['pageNumber']==1)
		{
			 $frmdata['from']=0;
			 $frmdata['to']=$recordPerPage;
		}
	   //for next pages
		else
		{
	       if($frmdata['pageNumber']<=0)
	       {
				$frmdata['pageNumber']=1;
				$frmdata['from']=0;
				$frmdata['to']=$recordPerPage;
	       }
	       else
	       {
				$frmdata['from']= $recordPerPage * ( ( (int) $frmdata['pageNumber']) - 1);
				$frmdata['to']=$recordPerPage;
	       }
		}
	}
}


//                          function to show pending notification in dropdown
 function showUserPendingNotifications($status){
    global $DB,$user_id;

    $sql = "Select * from notifications where                                                      other_user_id=$user_id and status='$status' order by id desc                                   limit 5";
    $res_data_notification = $DB->RunSelectQuery($sql);

    foreach ($res_data_notification as $result)
    {
        $notification_data = (array)$result;
        $date = date("Y-m-d", strtotime($notification_data["notification_date"]));
        ?>

        <?php
//echo $notification_data["notification_type"] ;
// Added here  by Nitin Soni for status

        /*
SHow Notification for updated event
        */
        //IF NOTIFICATION TYPE IS update event/ping
        if ($notification_data["notification_type"] == "ping_updated"|| $notification_data["notification_type"] == "event_updated")
        {

            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data_event = $DB->RunSelectQuery($sql);
            foreach ($res_data_event as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data_publicUser = $DB->RunSelectQuery($sql);
            ?>
            <div class="is-notification" id="is-notification">
                <div class="who-sent-notification">
                    <?php
                    if ($resulteventcomment['event_photo'] == null)
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                        <?php
                    }
                    else
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resulteventcomment["event_photo"]; ?>" width="37" height="37" />
                        <?php
                    }
                    ?>
                </div>

                <div class="notification-itself">
                    <p class="notification-content">
                        <?php   if ($event_type == 'ping')
                        {
                            $var = 'The';
                        }else{
                            $var = 'An';
                        } ?>
                        <?php echo $var ?> <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                                echo $event_name;
                                ?></a></strong> has been modified by organiser. Please review the changes.
                    </p>
                    <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>
            </div>

            <!--                            --><? //

        }
        /*UPdate event/ping work completed here */

        /* Cancel Event/Ping started  */
        //IF NOTIFICATION TYPE IS cancel event/ping
        if ($notification_data["notification_type"] == "Cancel Ping"|| $notification_data["notification_type"] == "Cancel Event")
        {
            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data_event = $DB->RunSelectQuery($sql);
            foreach ($res_data_event as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data_publicUser = $DB->RunSelectQuery($sql);
            foreach ($res_data_publicUser as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;
                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            }
            ?>
            <div class="is-notification" id="is-notification">
                <div class="who-sent-notification">
                    <?php
                    if ($resulteventcomment['event_photo'] == null)
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                        <?php
                    }
                    else
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resulteventcomment["event_photo"]; ?>" width="37" height="37" />
                        <?php
                    }
                    ?>
                </div>

                <div class="notification-itself">
                    <p class="notification-content">
                        <?php   if ($event_type == 'ping')
                        {
                            $var = 'The';
                        }else{
                            $var = 'An';
                        } ?>
                        <?php echo $var ?> <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                                echo $event_name;
                                ?></a></strong> has been cancelled.</p>

                    <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>
            </div>

            <!--                            --><? //

        }
        /* Completed cancel event/ping  */



        /*Reorganize event started*/
        /* Cancel Event/Ping started  */
        //IF NOTIFICATION TYPE IS cancel event/ping
        if ($notification_data["notification_type"] == "Event Reorganize" || $notification_data["notification_type"] == "Ping Reorganize")
        {

            /*echo "Hello";
            exit;*/
            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data_event = $DB->RunSelectQuery($sql);
            // 'event_status' => 'L'
            foreach ($res_data_event as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data_publicUser = $DB->RunSelectQuery($sql);
            foreach ($res_data_publicUser as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;
                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            }
            ?>
            <div class="is-notification" id="is-notification">
                <div class="who-sent-notification">
                    <?php
                    if ($resulteventcomment['event_photo'] == null)
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                        <?php
                    }
                    else
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resulteventcomment["event_photo"]; ?>" width="37" height="37" />
                        <?php
                    }
                    ?>
                </div>

                <div class="notification-itself">
                    <p class="notification-content">
                        <?php   if ($event_type == 'ping')
                        {
                            $var = 'The';
                        }else{
                            $var = 'An';
                        } ?>

                        <?php echo $var ?> <?php echo $event_type ?> <strong><a href=<?php echo  $event_link ?> style="color:#000000"><?php echo  $event_name ?></a></strong>
                        has been reorganized.
                    </p>
                    <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>
            </div>

            <!--                            --><? //

        }
        /*Completed here*/
        /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
        /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
        /*Completed event update work*/

        //IF NOTIFICATION TYPE IS A COMMENT
        if ($notification_data["notification_type"] == "C")
        {

            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data_publicUser = $DB->RunSelectQuery($sql);
            foreach ($res_data_publicUser as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;
                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            }
            ?>
            <div class="is-notification" id="is-notification">
                <div class="who-sent-notification">
                    <?php

                    $sql = "Select * from public_users where id=" . $notification_data["user_id"];
                    $before_result = $DB->RunSelectQuery($sql);
                    foreach ($before_result as $result)
                    {
                        $publicUserResult = (array)$result;
                        if ($publicUserResult['profile_pic'] == null)
                        {
                            ?>
                            <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                            <?php
                        }
                        else
                        {
                            ?>
                            <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $publicUserResult["profile_pic"]; ?>" width="37" height="37" />
                            <?php
                        }
                    } ?>
                </div>

                <div class="notification-itself">
                    <p class="notification-content"><strong><a href="<?php
                            echo createURL('index.php', "mod=user&do=profile&userid=" . $notification_data["$user_id"]);
                            ?>" style="color:#000000"><?php echo  $user_name ?></a></strong> has commented on your <?php echo $event_type ?> <strong><a href=<?php echo  $event_link ?> style="color:#000000"><?php echo  $event_name ?></a>&nbsp;</strong></p>
                    <p id="notification-date"><span class="has-noti-icon"><img alt="notifocation-image" src="images/comment_box.gif"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>
            </div>

            <!--                            --><? //

        }

//Completed work for show cancel Event notification

        //IF NOTIFICATION TYPE IS A COMMENT
        if ($notification_data["notification_type"] == "Comment")
        {
            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;
                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            }
            ?>
            <div id="is-notification" class="is-notification">
                <div class="who-sent-notification">
                    <?php
                    //Query is changed by Nitin Soni
                    /* $sql = "SELECT * from public_users where id=$user_id";*/
                    $sql = "Select * from public_users where id=" . $notification_data["user_id"];
                    $before_result = $DB->RunSelectQuery($sql);
                    foreach ($before_result as $result)
                    {
                        $PublicUserData = (array)$result;
                        if ($PublicUserData['profile_pic'] == null)
                        {
                            ?>
                            <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                            <?php
                        }
                        else
                        {
                            ?>
                            <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $PublicUserData["profile_pic"]; ?>" width="37" height="37" />
                            <?php
                        }
                    } ?>
                </div>
                <!--  <div class="who-sent-notification">
                     <img alt="event-image" src="images/profile_img.jpg" class="image-has-radius">
                 </div> -->
                <div class="notification-itself">
                    <p class="notification-content" id="is-notification"><strong><a href="<?php
                            echo createURL('index.php', "mod=user&do=profile&userid=" . $notification_data["id"]);
                            ?>" style="color:#000000"><?php echo  $user_name ?></a></strong> has commented on your <?php echo $event_type ?> <strong><a href=<?php echo  $event_link ?> style="color:#000000"><?php echo  $event_name ?></a>&nbsp;</strong></p>
                    <p id="notification-date"><span class="has-noti-icon"><img alt="notifocation-image" src="images/comment_box.gif"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>
            </div>

            <!--                            --><? //

        }
        //IF NOTIFICATION TYPE IS AN ADD BUDDY
        if ($notification_data["notification_type"] == "Add_Buddy")
        {

            $sql = "Select * from public_users where id=" . $result->user_id;
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $data)
            {
                $publicUserInfo = (array)$data;

                $user_name = $publicUserInfo["firstname"] . " " . $publicUserInfo["lastname"];
            }
            ?>
            <div class="is-notification" id="is-notification">

            <div class="who-sent-notification">
                <?php
                if ($publicUserInfo['profile_pic'] == null)
                {
                    ?>
                    <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                    <?php
                }
                else
                {
                    ?>
                    <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $publicUserInfo["profile_pic"]; ?>" width="37" height="37" />
                    <?php
                } ?>
            </div>

            <div class="notification-itself">
            <p class="notification-content">
            <strong><a href="<?php
            echo createURL('index.php', "mod=user&do=profile&userid=" . $publicUserInfo["id"]);
            ?>" style="color:#000000"><?php echo  $user_name ?></a></strong>&nbsp; wants to add you as a buddy.

            <?php //Check if current user and event_organiser are buddies
            $sql = "SELECT * from buddies where user_id=$user_id and buddy_id=" . $publicUserInfo["id"];
            $res_data = $DB->RunSelectQuery($sql);
            if ($res_data[0]->status =='Confirmed buddy')
            {

                ?>
                <img src="images/green_tick.gif" style="vertical-align:middle" width="15" />&nbsp;<span class="ArialVeryDarkGreyBold15">Accepted</span>
                </p>
                <p id="notification-date"><span class="glyphicon glyphicon-user"></span><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="accept-req-dropdown" id="confirm_add_buddy_status<?php
                echo $publicUserInfo["id"];
                ?>" style="display:inline-block"><div  onClick="confirm_add_buddy(<?php

                    ?>)" class="slimbuttonblue" style="width:130px;">Accept request</div></div>
                <p id="notification-date"><span class="glyphicon glyphicon-user"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                <?php
            } ?>
            </div>
            </div>
            <?php

        }
        //IF NOTIFICATION TYPE IS A CONFIRMED BUDDY
        if ($notification_data["notification_type"] == "Confirmed Buddy")
        {
            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resultuser)
            {
                $resultuserInfo = (array)$resultuser;
                $user_name = $resultuserInfo["firstname"] . " " . $resultuserInfo["lastname"];
            }
            ?>

            <div class="is-notification" id="is-notification">
                <div class="who-sent-notification">
                    <img width="37" height="37" src="<? echo ROOTURL . '/' . $resultuserInfo["profile_pic"]; ?>" class="image-has-radius" alt="event-image">
                </div>
                <div class="notification-itself">
                    <p class="notification-content"><strong><a href="<?php
                            echo createURL('index.php', "mod=user&do=profile&userid=" . $resultuserInfo["id"]);
                            ?>" style="color:#000000"><?php
                                echo $user_name;
                                ?></a></strong>&nbsp; has accepted your buddy request.</p>
                    <p id="notification-date"><span class="glyphicon glyphicon-user"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>

            </div>
            <?php
        }
        if ($notification_data["notification_type"] == "Booking Request")
        {
            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);
                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }


            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data = $DB->RunSelectQuery($sql);

            foreach ($res_data as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;

                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            } ?>
            <!--  Add new commented here  -->
            <div  class="is-notification" id="is-notification">
                <!-- Added by Nitin Soni for show user image 18Aug/2017 -->
                <div class="who-sent-notification">
                    <?php
                    if ($resultusercomment['profile_pic'] == null)
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                        <?php
                    }
                    else
                    {

                        ?>
                        <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resultusercomment['profile_pic']; ?>" width="37" height="37" />
                        <?php
                    }
                    ?>
                </div>
                <!-- Finsihed by Nitin Soni for user image -->
                <div class="notification-itself" >
                    <p class="notification-content"><?php echo $user_name; ?> sent you a booking request for <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                                echo $event_name;
                                ?></a></strong> </p>
                    <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time">                            <?php
                            echo date("j F, H:i a", strtotime($notification_data["notification_date"]));
                            ?></span></p>
                </div>
            </div>
            <!-- Add new task completed  -->

            <?php
        }
        if ($notification_data["notification_type"] == "Cancel Booking")
        {
            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);
                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }


            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data = $DB->RunSelectQuery($sql);

            foreach ($res_data as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;

                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            } ?>
            <!--  Add new commented here  -->
            <div  class="is-notification" id="is-notification">
                <!-- Added by Nitin Soni for show user image 18Aug/2017 -->
                <div class="who-sent-notification">
                    <?php
                    if ($resultusercomment['profile_pic'] == null)
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                        <?php
                    }
                    else
                    {

                        ?>
                        <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resultusercomment['profile_pic']; ?>" width="37" height="37" />
                        <?php
                    }
                    ?>
                </div>
                <!-- Finsihed by Nitin Soni for user image -->
                <div class="notification-itself" >
                    <p class="notification-content"><?php echo $user_name; ?> Left <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                                echo $event_name;
                                ?></a></strong> </p>
                    <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time">                            <?php
                            echo date("j F, H:i a", strtotime($notification_data["notification_date"]));
                            ?></span></p>
                </div>
            </div>
            <!-- Add new task completed  -->

            <?php
        }


        ?>
        <?php
    }

}

//                         function to show seen notifications in dropdown
 function showUserNotifications($status,$limit){
    global $DB,$user_id;
    $limit;
    $sql = "Select * from notifications where other_user_id=$user_id                               and status='$status' order by id desc limit $limit";
    $res_data_notification = $DB->RunSelectQuery($sql);

    foreach ($res_data_notification as $result)
    {
        $notification_data = (array)$result;
        $date = date("Y-m-d", strtotime($notification_data["notification_date"]));
        ?>
        <?php
// Added here  by Nitin Soni for status

        /*
SHow Notification for updated event
        */
        //IF NOTIFICATION TYPE IS update event/ping
        if ($notification_data["notification_type"] == "ping_updated"|| $notification_data["notification_type"] == "event_updated")
        {

            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data_event = $DB->RunSelectQuery($sql);
            foreach ($res_data_event as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data_publicUser = $DB->RunSelectQuery($sql);
            ?>
            <div class="is-notification">
                <div class="who-sent-notification">
                    <?php
                    if ($resulteventcomment['event_photo'] == null)
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                        <?php
                    }
                    else
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resulteventcomment["event_photo"]; ?>" width="37" height="37" />
                        <?php
                    }
                    ?>
                </div>

                <div class="notification-itself">
                    <p class="notification-content">
                        <?php   if ($event_type == 'ping')
                        {
                            $var = 'The';
                        }else{
                            $var = 'An';
                        } ?>
                        <?php echo $var ?> <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                                echo $event_name;
                                ?></a></strong> has been modified by organiser. Please review the changes.
                    </p>
                    <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>
            </div>

            <!--                            --><? //

        }
        /*UPdate event/ping work completed here */

        /* Cancel Event/Ping started  */
        //IF NOTIFICATION TYPE IS cancel event/ping
        if ($notification_data["notification_type"] == "Cancel Ping"|| $notification_data["notification_type"] == "Cancel Event")
        {
            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data_event = $DB->RunSelectQuery($sql);
            foreach ($res_data_event as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data_publicUser = $DB->RunSelectQuery($sql);
            foreach ($res_data_publicUser as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;
                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            }
            ?>
            <div class="is-notification">
                <div class="who-sent-notification">
                    <?php
                    if ($resulteventcomment['event_photo'] == null)
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                        <?php
                    }
                    else
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resulteventcomment["event_photo"]; ?>" width="37" height="37" />
                        <?php
                    }
                    ?>
                </div>

                <div class="notification-itself">
                    <p class="notification-content">
                        <?php   if ($event_type == 'ping')
                        {
                            $var = 'The';
                        }else{
                            $var = 'An';
                        } ?>
                        <?php echo $var ?> <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                                echo $event_name;
                                ?></a></strong> has been cancelled.</p>

                    <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>
            </div>

            <!--                            --><? //

        }
        /* Completed cancel event/ping  */



        /*Reorganize event started*/
        /* Cancel Event/Ping started  */
        //IF NOTIFICATION TYPE IS cancel event/ping
        if ($notification_data["notification_type"] == "Event Reorganize" || $notification_data["notification_type"] == "Ping Reorganize")
        {

            /*echo "Hello";
            exit;*/
            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data_event = $DB->RunSelectQuery($sql);
            // 'event_status' => 'L'
            foreach ($res_data_event as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data_publicUser = $DB->RunSelectQuery($sql);
            foreach ($res_data_publicUser as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;
                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            }
            ?>
            <div class="is-notification">
                <div class="who-sent-notification">
                    <?php
                    if ($resulteventcomment['event_photo'] == null)
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                        <?php
                    }
                    else
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resulteventcomment["event_photo"]; ?>" width="37" height="37" />
                        <?php
                    }
                    ?>
                </div>

                <div class="notification-itself">
                    <p class="notification-content">
                        <?php   if ($event_type == 'ping')
                        {
                            $var = 'The';
                        }else{
                            $var = 'An';
                        } ?>
                        <?php echo $var ?> <?php echo $event_type ?> <strong><a href=<?php echo  $event_link ?> style="color:#000000"><?php echo  $event_name ?></a></strong>
                        has been reorganized.
                    </p>
                    <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>
            </div>

            <!--                            --><? //

        }
        /*Completed here*/
        /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
        /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/
        /*Completed event update work*/

        //IF NOTIFICATION TYPE IS A COMMENT
        if ($notification_data["notification_type"] == "C")
        {

            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data_publicUser = $DB->RunSelectQuery($sql);
            foreach ($res_data_publicUser as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;
                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            }
            ?>
            <div class="is-notification">
                <div class="who-sent-notification">
                    <?php

                    $sql = "Select * from public_users where id=" . $notification_data["user_id"];
                    $before_result = $DB->RunSelectQuery($sql);
                    foreach ($before_result as $result)
                    {
                        $publicUserResult = (array)$result;
                        if ($publicUserResult['profile_pic'] == null)
                        {
                            ?>
                            <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                            <?php
                        }
                        else
                        {
                            ?>
                            <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $publicUserResult["profile_pic"]; ?>" width="37" height="37" />
                            <?php
                        }
                    } ?>
                </div>

                <div class="notification-itself">
                    <p class="notification-content"><strong><a href="<?php
                            echo createURL('index.php', "mod=user&do=profile&userid=" . $notification_data["$user_id"]);
                            ?>" style="color:#000000"><?php echo  $user_name ?></a></strong> has commented on your <?php echo $event_type ?><strong><a href=<?php echo  $event_link ?> style="color:#000000"><?php echo  $event_name ?></a>&nbsp;</strong></p>
                    <p id="notification-date"><span class="has-noti-icon"><img alt="notifocation-image" src="images/comment_box.gif"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>
            </div>

            <!--                            --><? //

        }

//Completed work for show cancel Event notification

        //IF NOTIFICATION TYPE IS A COMMENT
        if ($notification_data["notification_type"] == "Comment")
        {
            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);

                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }
            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;
                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            }
            ?>
            <div class="is-notification">
                <div class="who-sent-notification">
                    <?php
                    //Query is changed by Nitin Soni
                    /* $sql = "SELECT * from public_users where id=$user_id";*/
                    $sql = "Select * from public_users where id=" . $notification_data["user_id"];
                    $before_result = $DB->RunSelectQuery($sql);
                    foreach ($before_result as $result)
                    {
                        $PublicUserData = (array)$result;
                        if ($PublicUserData['profile_pic'] == null)
                        {
                            ?>
                            <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                            <?php
                        }
                        else
                        {
                            ?>
                            <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $PublicUserData["profile_pic"]; ?>" width="37" height="37" />
                            <?php
                        }
                    } ?>
                </div>
                <!--  <div class="who-sent-notification">
                     <img alt="event-image" src="images/profile_img.jpg" class="image-has-radius">
                 </div> -->
                <div class="notification-itself">
                    <p class="notification-content"><strong><a href="<?php
                            echo createURL('index.php', "mod=user&do=profile&userid=" . $notification_data["id"]);
                            ?>" style="color:#000000"><?php echo  $user_name ?></a></strong> has commented on your <?php echo $event_type ?> <strong><a href=<?php echo  $event_link ?> style="color:#000000"><?php echo  $event_name ?></a>&nbsp;</strong></p>
                    <p id="notification-date"><span class="has-noti-icon"><img alt="notifocation-image" src="images/comment_box.gif"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>
            </div>

            <!--                            --><? //

        }
        //IF NOTIFICATION TYPE IS AN ADD BUDDY
        if ($notification_data["notification_type"]=='Add_Buddy')
        {
//                  echo'hi mthree';exit;
            $sql = "Select * from public_users where id=" . $result->user_id;
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $data)
            {
                $publicUserInfo = (array)$data;

                $user_name = $publicUserInfo["firstname"] . " " . $publicUserInfo["lastname"];
            }
            ?>
            <div class="is-notification">

            <div class="who-sent-notification">
                <?php
                if ($publicUserInfo['profile_pic'] == null)
                {
                    ?>
                    <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                    <?php
                }
                else
                {
                    ?>
                    <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $publicUserInfo["profile_pic"]; ?>" width="37" height="37" />
                    <?php
                } ?>
            </div>

            <div class="notification-itself">
            <p class="notification-content"><strong><a href="<?php
            echo createURL('index.php', "mod=user&do=profile&userid=" . $publicUserInfo["id"]);
            ?>" style="color:#000000"><?php echo  $user_name ?></a></strong>&nbsp; wants to add you as a buddy.
            <?php //Check if current user and event_organiser are buddies
            $sql = "SELECT * from buddies where user_id=$user_id and buddy_id=" . $publicUserInfo["id"];
            $res_data = $DB->RunSelectQuery($sql);
            if ($res_data[0]->status =='Confirmed buddy')
            {

                ?>
                <img src="images/green_tick.gif" style="vertical-align:middle" width="15" />&nbsp;<span class="ArialVeryDarkGreyBold15">Accepted</span>
                </p>
                <p id="notification-date"><span class="glyphicon glyphicon-user"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>
                </div>
                <?php
            }
            else
            {
                ?>
                <div class="accept-req-dropdown" id="confirm_add_buddy_status<?php
                echo $publicUserInfo["id"];
                ?>" style="display:inline-block"><div  onClick="confirm_add_buddy(<?php
                    echo $publicUserInfo["id"];
                    ?>)" class="slimbuttonblue" style="width:130px;">Accept request</div></div>
                <p id="notification-date"><span class="glyphicon glyphicon-user"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                <?php
            }?>

            <?php
        }
        //IF NOTIFICATION TYPE IS A CONFIRMED BUDDY
        if ($notification_data["notification_type"] == "Confirmed Buddy")
        {
            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resultuser)
            {
                $resultuserInfo = (array)$resultuser;
                $user_name = $resultuserInfo["firstname"] . " " . $resultuserInfo["lastname"];
            }
            ?>

            <div class="is-notification">
                <div class="who-sent-notification">
                    <img width="37" height="37" src="<? echo ROOTURL . '/' . $resultuserInfo["profile_pic"]; ?>" class="image-has-radius" alt="event-image">
                </div>
                <div class="notification-itself">
                    <p class="notification-content"><strong><a href="<?php
                            echo createURL('index.php', "mod=user&do=profile&userid=" . $resultuserInfo["id"]);
                            ?>" style="color:#000000"><?php
                                echo $user_name;
                                ?></a></strong>&nbsp; has accepted your buddy request.</p>
                    <p id="notification-date"><span class="glyphicon glyphicon-user"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($notification_data["notification_date"])) ?></span></p>
                </div>

            </div>
            <?php
        }
        if ($notification_data["notification_type"] == "Booking Request")
        {
            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);
                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }


            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data = $DB->RunSelectQuery($sql);

            foreach ($res_data as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;

                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            } ?>
            <!--  Add new commented here  -->
            <div  class="is-notification">
                <!-- Added by Nitin Soni for show user image 18Aug/2017 -->
                <div class="who-sent-notification">
                    <?php
                    if ($resultusercomment['profile_pic'] == null)
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                        <?php
                    }
                    else
                    {

                        ?>
                        <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resultusercomment['profile_pic']; ?>" width="37" height="37" />
                        <?php
                    }
                    ?>
                </div>
                <!-- Finsihed by Nitin Soni for user image -->
                <div class="notification-itself">
                    <p class="notification-content"><?php echo $user_name; ?> sent you a booking request for <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                                echo $event_name;
                                ?></a></strong> </p>
                    <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time">                            <?php
                            echo date("j F, H:i a", strtotime($notification_data["notification_date"]));
                            ?></span></p>
                </div>
            </div>
            <!-- Add new task completed  -->

            <?php
        }
        if ($notification_data["notification_type"] == "Cancel Booking")
        {
            $sql = "Select * from events where event_id=" . $notification_data["event_id"];
            $res_data = $DB->RunSelectQuery($sql);
            foreach ($res_data as $resulteventcomment)
            {
                $resulteventcomment = (array)$resulteventcomment;
                $event_name = $resulteventcomment["event_name"];
                if ($resulteventcomment["entry_type"] == "")
                {
                    $event_type = "event";
                    $event_link = createURL('index.php', "mod=event&do=eventdetails&eventid=" . $resulteventcomment["event_id"]);
                }
                else if ($resulteventcomment["entry_type"] == "Ping")
                {
                    $event_type = "ping";
                    $event_link = createURL('index.php', "mod=ping&do=pingdetails&eventid=" . $resulteventcomment["event_id"]);
                }
            }


            $sql = "Select * from public_users where id=" . $notification_data["user_id"];
            $res_data = $DB->RunSelectQuery($sql);

            foreach ($res_data as $resultusercomment)
            {
                $resultusercomment = (array)$resultusercomment;

                $user_name = $resultusercomment["firstname"] . " " . $resultusercomment["lastname"];
            } ?>
            <!--  Add new commented here  -->
            <div  class="is-notification">
                <!-- Added by Nitin Soni for show user image 18Aug/2017 -->
                <div class="who-sent-notification">
                    <?php
                    if ($resultusercomment['profile_pic'] == null)
                    {
                        ?>
                        <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
                        <?php
                    }
                    else
                    {

                        ?>
                        <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resultusercomment['profile_pic']; ?>" width="37" height="37" />
                        <?php
                    }
                    ?>
                </div>
                <!-- Finsihed by Nitin Soni for user image -->
                <div class="notification-itself">
                    <p class="notification-content"><?php echo $user_name; ?> Left <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                                echo $event_name;
                                ?></a></strong> </p>
                    <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time">                            <?php
                            echo date("j F, H:i a", strtotime($notification_data["notification_date"]));
                            ?></span></p>
                </div>
            </div>
            <!-- Add new task completed  -->

            <?php
        }

        ?>
        <?php
    }
}

?>
