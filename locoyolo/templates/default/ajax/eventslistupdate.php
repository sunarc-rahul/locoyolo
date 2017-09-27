<div class='result-wrp' width="680" align="center">
    <?php
    function limit_text($text, $limit)
    {
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos   = array_keys($words);
            $text  = substr($text, 0, $pos[$limit]) . '...';
        }
        return $text;
    }
    $SWlat      = $_POST["SWlat"];
    $SWlng      = $_POST["SWlng"];
    $NElat      = $_POST["NElat"];
    $NElng      = $_POST["NElng"];
    $getPings      = $_POST["getPings"];
    $getEvents      = $_POST["getEvents"];
    $eventType      = $_POST["eventType"];
    $start_time = $_POST["starttime"];
    $date       = explode('-', $_POST["daterange"]);
    $time       = explode('-', $_POST["timerange"]);
    $start_date = date('Y-m-d', strtotime($date[0]));
    $end_date   = date('Y-m-d', strtotime($date[1]));
    $start_time = date('H:i:s', strtotime("" . $time[0] . ""));
    $end_time   = date('H:i:s', strtotime("" . $time[1] . ""));
    $catrgoryQry=$getpingQry=$geteventQry='';

    if(($getPings==0&&$getEvents==1)||($getPings==1&&$getEvents==0))
    {
        if($getPings!=0)
        {
            $getpingQry=" and entry_type='Ping'";
        }
        if($getEvents!=0)
        {
            $geteventQry=" and entry_type=''";
        }
    }
    if($eventType!=0)
    {
        $catrgoryQry=" and event_category=".$eventType;
    }
    $return_arr = array();

    //==============================PAGINATION START SCRIPT=======================================//
    $sql = "SELECT * from events where ((event_lat >= '$SWlat' and event_lat <= '$NElat') and (event_long <= '$NElng' and event_long >= '$SWlng'))  and date(start_date) between  date('$start_date') and date('$end_date') and time(start_date) between  time('$start_time') and time('$end_time') and  date(end_date)>date(now())  $getpingQry $geteventQry $catrgoryQry";
    //$stmt = $pdo->prepare("SELECT * from event_locations where event_lat > ? and event_lat < ? and event_long < ? and event_long > ? limit 10");
    /*
    $stmt->bindValue(1, $SWlat, PDO::PARAM_STR);
    $stmt->bindValue(2, $NElat, PDO::PARAM_STR);
    $stmt->bindValue(3, $NElng, PDO::PARAM_STR);
    $stmt->bindValue(4, $SWlng, PDO::PARAM_STR);
    $stmt->bindValue(5, $start_time, PDO::PARAM_STR);
    $stmt->bindValue(6, $end_time, PDO::PARAM_STR);
    $stmt->bindValue(7, $event_date, PDO::PARAM_STR);
    $stmt->execute();*/
    $res = $DB->RunSelectQuery($sql);
    if (is_array($res)) {
        $a = count($res);
    } else {
        $a = 0;
    }


    $numrows     = $a;
    // number of rows to show per page
    $rowsperpage = 20;
    // find out total pages
    $totalpages  = ceil($numrows / $rowsperpage);

    // get the current page or set a default
    if (isset($_POST['currentpage']) && is_numeric($_POST['currentpage'])) {
        // cast var as int
        $currentpage = (int) $_POST['currentpage'];
    } else {
        // default page num
        $currentpage = 1;
    } // end if

    // if current page is greater than total pages...
    if ($currentpage > $totalpages) {
        // set current page to last page
        $currentpage = $totalpages;
    } // end if
    // if current page is less than first page...
    if ($currentpage < 1) {
        // set current page to first page
        $currentpage = 1;
    } // end if

    // the offset of the list, based on current page
    $offset = ($currentpage - 1) * $rowsperpage;
      $sql1   = "SELECT * from events where ((event_lat >= '$SWlat' and event_lat <= '$NElat') and (event_long <= '$NElng' and event_long >= '$SWlng'))  and date(start_date) between  date('$start_date') and date('$end_date') and time(start_date) between  time('$start_time') and time('$end_time') and  date(end_date)>date(now()) $getpingQry $geteventQry $catrgoryQry LIMIT $offset, $rowsperpage ";

    $res_data = $DB->RunSelectQuery($sql1);

    $i = 1;

    ?>

        <div style="background:#C33; padding:3px; border-radius:3px; color:#FFF; font-family: Arial; font-size:15px; display:inline-block; margin:0 auto;width:98%"><?
            if ($SWlat !== 0) {
                if ($a > 0) {
                    ?>Showing events and pings <strong><?php echo($offset + 1) ?> - <?
                        $lastnumber = $currentpage * $rowsperpage;
                        if ($lastnumber < $a) {
                            echo $lastnumber;
                        } else if ($lastnumber > $a) {
                            echo $a;
                        }
                        ?></strong> of <?php echo$a ?> records<?
                } else {
                    ?>No events or pings found within the displayed location area, time range and date.<br />Please search again <strong>OR</strong> see our suggested events and pings below.<?
                }
            }
            ?></div>
			<div class="result_element">
        <?php

        foreach ($res_data as $result) {
        $result = (array) $result;
        $userid = $result["user_id"];
        ?>


            <!----------------------IF EVENT IS AN EVENT---------------------------------->
            <?php
            if ($result["entry_type"] == "") {
            ?>
                <div class="res-data res-events" id="res-data">
            <a class="tableshadowbox-1" style=""  onclick="window.open('<?php
            print CreateURL('index.php', "mod=event&do=eventdetails&eventid=" . $result["event_id"]);
            ?>')" >
                <div class="detail-sec">
                    <?php
                    $sql2     = "SELECT * from public_users where id=$userid";
                    $res_user = $DB->RunSelectQuery($sql2);

                    foreach ($res_user as $resultuser) {
                        $resultuser = (array) $resultuser;
                        ?>
                        <?php

                        if (file_get_contents(ROOTURL . '/' . $result["event_photo"])) {
                            $event_image = ROOTURL . '/' . $result["event_photo"];
                        } else {
                            $event_image = ROOTURL . '/' . 'images/dummy-bg.jpg';
                        }
                        ?>
                        <div class="event-img"><img width="310" src="<?php
                            echo $event_image;
                            ?>" /></div>
                        <div class="event_detail">
                            <?php  if ($resultuser["profile_pic"] == "") {
                                ?>
                                <img width="35" height="35" style="border-radius:17.5px" class="user-img" src="images/no_profile_pic.gif" />
                                <?php
                            } else {
                                ?>
                                <img class="user-img" width="35" height="35" style="border-radius:17.5px" src="<?
                                echo ROOTURL . '/' . $resultuser["profile_pic"];
                                ?>" />
                                <?php
                            }
                            ?>
                        <?php
                        $sql3    = "SELECT * from event_types where id=" . $result["event_category"];
                        $res_cat = $DB->RunSelectQuery($sql3);
                        foreach ($res_cat as $resultcat) {
                            $resultcat = (array) $resultcat;
                            ?>


                            <span class="ArialVeryDarkGreyBold15"><?php echo trim($resultuser["firstname"] . " " . $resultuser["lastname"]);
                                ?></span>


                                <?php
                            }
                            ?>
                        </div>
                        <div class="event-price">
                            <span class="ArialOrange18" style="font-size:30px"><?
                                if ($result["event_price"] < 1) {
                                    echo "Free";
                                } else {
                                    echo "S$" . $result["event_price"];
                                }
                                ?></span></div>
                        <?php
                    }
                    ?>
                    <div class="event-name"><h3>
                            <?
                            echo $result["event_name"];
                            ?></h3>
                        <?php echo$resultcat["event_type"] ?>
                    </div>

                    <div class="event-detail-sec">
                            <div class="event-date">
                                <?php
                                $str = $result["start_date"];
                                $strEnd = $result["end_date"];

                                if( $str=='0000-00-00 00:00:00' && $strEnd =='0000-00-00 00:00:00'){

                                    echo '<p> Time not available</p>';

                                }else{
                                    if($str=='0000-00-00 00:00:00') {

                                        $startDate =' Start time not available.';
                                    }else{
                                        $startDate = date(" d M Y - h:i A", strtotime($str));
                                    }
                                    if( $strEnd =='0000-00-00 00:00:00') {

                                        $EndDate ='End time not available.';
                                    }else{
                                        $EndDate = date(" d M Y - h:i A", strtotime($strEnd));
                                    }
                                    ?>
                                    <h5 class="ArialVeryDarkGrey15">
                                        <span><?php echo  $startDate ?></span> | <span><?php echo  $EndDate ?></span>
                                    </h5>
                                <?php  }
                                ?>
                            </div>
                        <?php

                        $today = date("d-m-Y h:i:s");
                        $currentDate = strtotime($today);

                        $end = $result["end_date"];
                        $endDate = strtotime($end);


                        if($endDate < $currentDate)

                        {
                            $eventCompletedStatus = 'True';

                            ?>
                            <div class="event-completed"><img src="images/green_tick.gif" style="vertical-align:middle" width="15"/>&nbsp;<span>Event Completed</span><div>

                                </div></div>

                        <?php  }?>

                            <?
                            $sql4          = "SELECT * from event_locations where event_id=" . $result["event_id"]." limit 1";
                            $res_event_loc = $DB->RunSelectQuery($sql4);
                            foreach ($res_event_loc as $resultloc) {
                                $resultloc = (array) $resultloc;
                                ?>
                                <img src="images/marker_pin.jpg" /><div class="event-location" style="color:#666"><?php echo$resultloc["event_location"] ?></span>
                                </div>
                                <?
                            }
                            ?>

                        <span class="ArialVeryDarkGrey15">
                                <?
                                echo $result["event_description"];
                                ?></span>
                        </div>
                </div>
            </a>


                </div>

                <?php
                }
                ?>



            <?
            if ($i % 2 == 0) {
            ?>

        <?
        $i = 1;
        } else {
        ?>

<?
$i++;
}
?><?php
}foreach ($res_data as $result) {
        $result = (array) $result;
        $userid = $result["user_id"];
        ?>


            <!----------------------IF EVENT IS AN PING---------------------------------->
            <?php
            if ($result["entry_type"] == "Ping") {
                    ?>
                <div class="res-data res-pings"  id="res-data">
                <div class="ping-sect" onclick="window.open('<?php
                print CreateURL('index.php', "mod=ping&do=pingdetails&eventid=" . $result["event_id"]);
                ?>')">
                    <!--                   onclick="window.open('pingdetails.php?eventid=--><?php //= $result["event_id"]
                    ?><!--')">-->
                    <div class="ping-data"
                    <?
                    $sql5     = "SELECT * from public_users where id=$userid";
                    $res_user = $DB->RunSelectQuery($sql5);
                    foreach ($res_user as $resultuser) {
                        $resultuser = (array) $resultuser;
                        ?>
                        <div class="ping-user-data" style="cursor:pointer">

                            <?php
                            if ($resultuser["profile_pic"] == "") {
                                ?>
                                <img width="35" height="35" class="user-img" style="border-radius:17.5px" src="images/no_profile_pic.gif" />
                                <?
                            } else {
                                ?>
                                <img width="35" height="35" class="user-img"  style="border-radius:17.5px" src="<?
                                echo ROOTURL . '/' . $resultuser["profile_pic"];
                                ?>" />
                                <?
                            }
                            ?>

                            <span class="ArialVeryDarkGreyBold15">&nbsp;&nbsp;&nbsp;<?
                                echo ucfirst($resultuser["firstname"] . " " . $resultuser["lastname"]);
                                ?></span>
                            <?
                            $sql6 = "SELECT * from event_types where id=" . $result["event_category"];

                            $res_event_type = $DB->RunSelectQuery($sql6);
                            foreach ($res_event_type as $resultcat) {
                                $resultcat = (array) $resultcat;
                                ?>
                                <span class="ArialVeryDarkGrey13" style="color:#666"> has pinged a <?php echo$resultcat["event_type"] ?> meetup</span>
                                <?
                            }
                                      }
                    ?>

                    </div>
                    <h3 class="ping-name">

                            <?php
                            echo ucfirst($result["event_name"]);
                            ?>
                            </span>
                        </h3>
                        <div class="ping_start_date">
                            <?php
                            $str = $result["start_date"];
                            $strEnd = $result["end_date"];

                            if( $str=='0000-00-00 00:00:00' && $strEnd =='0000-00-00 00:00:00'){

                                echo '<p> Time not available</p>';

                            }else{
                                if($str=='0000-00-00 00:00:00') {

                                    $startDate =' Start time not available.';
                                }else{
                                    $startDate = date(" d M Y - h:i A", strtotime($str));
                                }
                                if( $strEnd =='0000-00-00 00:00:00') {

                                    $EndDate ='End time not available.';
                                }else{
                                    $EndDate = date(" d M Y - h:i A", strtotime($strEnd));
                                }
                                ?>
                                <h5 class="ArialVeryDarkGrey15">
                                    <span><?php echo  $startDate ?></span> | <span><?php echo  $EndDate ?></span>
                                </h5>
                            <?php  }
                            ?>


                            <div class="ping-location">
                                <?php
                                $sql7 = "SELECT * from ping_locations where event_id=" . $result["event_id"]." limit 1";

                                $res_ping_loc = $DB->RunSelectQuery($sql7);


                                foreach ($res_ping_loc as $resultloc) {
                                $resultloc = (array) $resultloc;
                                ?>
                                <div class="ping-location-marker"><img src="images/marker_pin.jpg" /><span class="ArialVeryDarkGrey13" style="color:#666">&nbsp;&nbsp;<?php
                                        echo $resultloc["event_location"];
                                        ?></span>
                                </div>
                            <?php
                            }
                            ?></div><br>
                        <div class="ping-join-btn"><input class="standardbutton" style="cursor:pointer" type="submit" name="joinmeetupbtn" id="joinmeetupbtn" value="Join meetup" /></br>
                            &nbsp;</div>

                    </div>
                    <?php
             ?></div>  </div><?php   }
                ?>



            <?
            if ($i % 2 == 0) {
            ?>

        <?
        $i = 1;
        } else {
        ?>

<?
$i++;
}
?><?php
}


?>

</div></div>
<?php if($totalpages>1){?>
<div class="event-pageination">
    <?php 
    if ($a > 0) {
        /******  build the pagination links ******/
        // range of num links to show
        $range = 3;

        // if not on page 1, don't show back links
        if ($currentpage > 1) {
            // show << link to go back to page 1
            //echo '<div class="tableshadowbox" style="background:#FFF; color:#333; font-family:Arial; font-size:15px; height:20px; width:90px" onclick="show_more_events(1)"><div style="height:5px"></div>First page</div> ';
            // get previous page num
            $prevpage = $currentpage - 1;
            // show < link to go back to 1 page
            echo '<div class="pagebutton" style="cursor:pointer; display:inline-block; height:25px; width:60px" onclick="show_more_events(' . $prevpage . ')"><div style="height:3px"></div>Previous</div>&nbsp;&nbsp;&nbsp;';
        } // end if

        // loop to show links to range of pages around current page
        for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
            // if it's a valid page number...
            if (($x > 0) && ($x <= $totalpages)) {
                // if we're on current page...
                if ($x == $currentpage) {
                    // 'highlight' it but don't make a link
                    echo '<div style="display:inline-block; background:#D93600; color:#FFF; font-family:Arial; font-size:15px; height:30px; width:30px; border-radius:3px;text-align: center"><div style="height:5px;text-align: center"></div>' . $x . '</div>&nbsp;&nbsp;&nbsp;';
                    // if not current page...
                } else {
                    // make it a link
                    echo '<div class="pagebutton" style="cursor:pointer; display:inline-block; height:25px; width:20px" onclick="show_more_events(' . $x . ')"><div style="height:3px"></div>' . $x . '</div>&nbsp;&nbsp;&nbsp;';
                } // end else
            } // end if
        } // end for

        // if not on last page, show forward and last page links
        if ($currentpage != $totalpages) {
            // get next page
            $nextpage = $currentpage + 1;
            // echo forward link for next page
            echo '<div class="pagebutton" style="cursor:pointer; display:inline-block; height:25px; width:90px" onclick="show_more_events(' . $nextpage . ')"><div style="height:3px"></div>More Events</div>&nbsp;&nbsp;&nbsp;';
            // echo forward link for lastpage
            //echo '<div class="tableshadowbox" style="background:#FFF; color:#333; font-family:Arial; font-size:15px; height:20px; width:70px" onclick="show_more_events('.$totalpages.')">Last page</div> ';
        } // end if
        /****** end build pagination links ******/
    }
    ?>

</div>
<?php }?>
</div>
<p></p>
<div class="event-suggestion">
    <?php
    $sql8 = "SELECT * from events where date(start_date) between  date('$start_date') and date('$end_date') and date(end_date)> date(now())order by rand() limit 3";
    $res_suggested_event = $DB->RunSelectQuery($sql8);
    if (is_array($res_suggested_event)) {
        $a = count($res_suggested_event);
    } else {
        $a = 0;
    }
    $i = 1;
    ?>
    <div class="suggestion-head"><span class="ArialVeryDarkGreyBold18">Suggested events & pings</span></div>
</div>
<div class="suggested-event-list">
    <?php
    if ($a > 0) {
    $recordcount = 0;

    foreach ($res_suggested_event as $result) {
    $result = (array) $result;
        $userid = $result["user_id"];
    $recordcount++;
        if($result["entry_type"]!='Ping')
      {

    ?>

    <div class='suggested-event' style="display:inline-block; width:220px">

        <div class="tableshadowbox" style="padding:10px" cellpadding="0" cellspacing="0" width="200" align="left" onclick="window.open('<?php print CreateURL('index.php', "mod=event&do=eventdetails&eventid=" . $result["event_id"]); ?>')">

               <img width="100%" src="<?php
                    $img =  ROOTURL.'/'.$result["event_photo"];
               if($result["event_photo"]==''||!file_get_contents($img)){echo  ROOTURL.'/'.'noeventimage.jpg';}else{echo $img;}
                    ?>" />
                    <div class="suggestion-dtl-sec">
                <?php
                $sql5  = "SELECT * from public_users where id=$userid";
                $res_user = $DB->RunSelectQuery($sql5);
                foreach ($res_user as $resultuser) {
                $resultuser = (array) $resultuser;
                ?>

                    <?
                    if ($resultuser["profile_pic"]==''|| file_get_contents(ROOTURL.$resultuser["profile_pic"])=='') {
                        ?>
                        <img width="30" height="30" style="border-radius:17.5px" src="images/no_profile_pic.gif" />
                        <?
                    } else {
                        ?>
                        <img width="30" height="30" style="border-radius:17.5px" src="<?php
                        echo ROOTURL . '/' . $resultuser["profile_pic"];
                        ?>" />
                        <?php
                    }
                    ?>

                    <span class="ArialVeryDarkGreyBold15"><?php   echo $resultuser["firstname"] . " " . $resultuser["lastname"];
                        ?></span>

            <?php
            }
            ?></div>
            <div class="sugg-event-price">
                <span class="ArialOrange18" style="font-size:20px"><?
                    /*if ($result["event_price"] < 1) {
                        echo "Free";
                    } else {
                        echo "S$" . $result["event_price"];
                    }*/
                    ?></span></div>
            <div class="sug-event_name">

                <span class="ArialVeryDarkGrey15 eventname">

                    <?php
                    echo $result["event_name"];
                    ?></span>
                <div class="suggest-event-date">
                    <?php
                    $str = $result["start_date"];
                    $strEnd = $result["end_date"];

                    if( $str=='0000-00-00 00:00:00' && $strEnd =='0000-00-00 00:00:00'){

                        echo '<p> Time not available</p>';

                    }else{
                        if($str=='0000-00-00 00:00:00') {

                            $startDate =' Start time not available.';
                        }else{
                            $startDate = date(" d M Y - h:i A", strtotime($str));
                        }
                        if( $strEnd =='0000-00-00 00:00:00') {

                            $EndDate ='End time not available.';
                        }else{
                            $EndDate = date(" d M Y - h:i A", strtotime($strEnd));
                        }
                        ?>
                        <h5 class="ArialVeryDarkGrey15">
                            <span><?php echo  $startDate ?></span> | <span><?php echo  $EndDate ?></span>
                        </h5>
                    <?php  }
                    ?>
                </div>
            <?php

            $today = date("d-m-Y h:i:s");
            $currentDate = strtotime($today);

            $end = $result["end_date"];
            $endDate = strtotime($end);


            if($endDate < $currentDate)

            {
                ?>
                <div class="event-completed"><img src="images/green_tick.gif" style="vertical-align:middle" width="15"/>&nbsp;<span>Event Completed</span><div>

                    </div></div>

                <?php  }?>
                <div class="suggested-location">
                    <?
                    $sql9      = "SELECT * from event_locations where event_id=" . $result["event_id"]." limit 1";
                    $event_loc = $DB->RunSelectQuery($sql9);
                    foreach ($event_loc as $resultloc) {
                    $resultloc = (array) $resultloc;
                    ?>
                    <div class="marker"><img src="images/marker_pin.jpg" />

                        <span class="ArialVeryDarkGrey13" style="color:#666"><?php echo $resultloc["event_location"] ?></span>

                    </div>
                        <span class="ArialVeryDarkGrey15">

                                <?
                                echo $resultloc["event_location_description"];
                                ?></span>
                <?php
                }
                ?> </div>


        </div>

            </div>
          </div>
<?php
}}

        foreach ($res_suggested_event as $result) {
            $result = (array) $result;
            $recordcount++;
            if($result["entry_type"]=='Ping')
            {

                ?>

                <div class='suggested-event Suggested-ping' style="display:inline-block; width:220px">

                    <div class="tableshadowbox" style="padding:10px" cellpadding="0" cellspacing="0" width="200" align="left" onclick="window.open('<?php  print CreateURL('index.php', "mod=event&do=eventdetails&eventid=" . $result["event_id"]);?>')">

                        <div class="suggestion-dtl-sec">
                                <?php
                                $sql5     = "SELECT * from public_users where id=$userid";
                                $res_user = $DB->RunSelectQuery($sql5);
                                foreach ($res_user as $resultuser) {
                                $resultuser = (array) $resultuser;
                                ?>

                                <?
                                if ($resultuser["profile_pic"] == "") {
                                    ?>
                                    <img width="30" height="30" style="border-radius:17.5px" src="images/no_profile_pic.gif" />
                                    <?
                                } else {
                                    ?>
                                    <img width="30" height="30" style="border-radius:17.5px" src="<?php
                                    echo ROOTURL . '/' . $resultuser["profile_pic"];
                                    ?>" />
                                    <?php
                                }
                                ?>

                                <span class="ArialVeryDarkGreyBold15"><?php   echo $resultuser["firstname"] . " " . $resultuser["lastname"];
                                    ?></span>


                        <?php
                        }
                        ?>
                        </div>
                        <div class="sugg-event-price">
                <span class="ArialOrange18" style="font-size:20px"><?
                   /* if ($result["event_price"] < 1) {
                        echo "Free";
                    } else {
                        echo "S$" . $result["event_price"];
                    }*/
                    ?></span></div>
                        <div class="sug-event_name">
                            <span class="ArialVeryDarkGrey15 eventname">

                    <?
                    echo $result["event_name"];
                    ?></span>
                            <div class="suggest-event-date">
                                <?php
                                $str = $result["start_date"];
                                $strEnd = $result["end_date"];

                                if( $str=='0000-00-00 00:00:00' && $strEnd =='0000-00-00 00:00:00'){

                                    echo '<p> Time not available</p>';

                                }else{
                                    if($str=='0000-00-00 00:00:00') {

                                        $startDate =' Start time not available.';
                                    }else{
                                        $startDate = date(" d M Y - h:i A", strtotime($str));
                                    }
                                    if( $strEnd =='0000-00-00 00:00:00') {

                                        $EndDate ='End time not available.';
                                    }else{
                                        $EndDate = date(" d M Y - h:i A", strtotime($strEnd));
                                    }
                                    ?>
                                    <h5 class="ArialVeryDarkGrey15">
                                        <span><?php echo  $startDate ?></span> | <span><?php echo  $EndDate ?></span>
                                    </h5>
                                <?php  }
                                ?>
                        </div>
               <?php

               $today = date("d-m-Y h:i:s");
               $currentDate = strtotime($today);

               $end = $result["end_date"];
               $endDate = strtotime($end);


               if($endDate < $currentDate)

               {
                                ?>
                <div class="event-completed"><img src="images/green_tick.gif" style="vertical-align:middle" width="15"/>&nbsp;<span>Event Completed</span><div>

                    </div></div>

            <?php  }?>

                            <div class="suggested-location">
                            <?
                          $sql9      = "SELECT * from event_locations where event_id=" . $result["event_id"]." limit 1";
                            $event_loc = $DB->RunSelectQuery($sql9);
                            foreach ($event_loc as $resultloc) {
                                $resultloc = (array) $resultloc;
                                ?>
                                <div class="marker">
                                    <img src="images/marker_pin.jpg" />
                                    <span class="ArialVeryDarkGrey13" style="color:#666"><?php echo $resultloc["event_location"] ?></span>

                                </div>
                                <span class="ArialVeryDarkGrey15">
                                <?
                                echo $resultloc["event_location_description"];
                                ?></span>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
</div>
                <?php
            }}
?><?
} else {
    ?>
    <div class="36"><span class="ArialVeryDarkGrey15">There are no suggested events available for the stated date.</span></div>
    <?php
}
?>
</div>