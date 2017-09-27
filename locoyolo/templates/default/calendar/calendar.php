<?php include_once (TEMPPATH."/header.php");?>
    <!-- JQuery -->


    <script type="text/javascript">
        // we will add our javascript code here
        function show_calendar(date){
            //Post by ajax to display events on map
            $.ajax({
                type: "POST",
                url: "calendarupdate.php",
                data: { calendardate:date },
                //dataType: 'json',
                cache: false,
                success: function(data)
                {
                    if(data == 'OK') {
                        result = data;
                    } else {
                        result = data;
                    }
                    $('#calendardisplay').html(result);
                }
            });
        }
    </script>

<div class="container">
<div class="row">
    <div class="sidebar" class="col-sm-6" id="calendarboxes" style="width: 520px; float: left; height:100%; border-right:#FFCC66 1px solid;overflow: scroll">
        <div style="height:50px"></div>
                 <!--------------SINGLE CALENDAR BOX----------------------->
                    <div style="height:15px"></div>
                    <table cellspacing="3" width="210" align="center" border="0" cellpadding="0">
                        <tr><td colspan="7" align="center" height="40"><h5 class="ArialOrange18"><?=date('F') ?></h5></td></tr>
                        <tr>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">S</td>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">M</td>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">T</td>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">W</td>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">T</td>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">F</td>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">S</td>
                        <tr>
                            <? $a = date("w");
                            $i=1;
                            if ($a>0){
                            while ($i < ($a+1)){ ?>
                                <td width="30" height="30">&nbsp;</td>
                                <? $i++; }

                            if ($i%8 == 0){ ?>
                        </tr><tr>
                            <? $i=1; }
                            }

                            for ($j=date('j'); $j<(date('t', strtotime(date('Y-m-d'))) +1); $j++){ ?>
                            <td onmouseover='this.bgColor="#EFEFEF"' onmouseout='this.bgColor="#FFF"' onclick="show_calendar('<?=date('Y-m') ?><? echo "-".$j; ?>')" width="30" height="30" style="border:#CCC 1px solid; border-radius:3px; text-align:center; vertical-align:middle; cursor:pointer"><h5 class="ArialVeryDarkGrey15"><?=$j ?></h5></td>
                            <?
                            if ($i%7 == 0){ ?>
                        </tr><tr>
                            <? $i=0; }
                            $i++;
                            }
                            ?>
                        </tr>
                    </table>

                    <!------------------------------------------>


                <!--------------REMAINING MONTH(S) CALENDAR BOXES----------------------->
        <div class="row">
                <?php $month = date('n')+1;
                $b = 2;
                while ($month < 13){ ?>
                     <div class="calendar-box col-sm-6">
                    <table cellspacing="3" width="210" align="center" border="0" cellpadding="0">
                        <tr><td colspan="7" align="center" height="40"><h5 class="ArialOrange18"><?=date('F',mktime(0, 0, 0, $month)) ?></h5></td></tr>
                        <tr>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">S</td>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">M</td>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">T</td>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">W</td>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">T</td>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">F</td>
                            <td width="20" height="20" align="center" valign="middle" class="ArialVeryDarkGrey13" style="color:#666">S</td>
                        <tr>
                            <? $a = date("w", strtotime(date("Y")."-".$month."-01"));
                            $i=1;
                            if ($a>0){
                            while ($i < ($a+1)){ ?>
                                <td width="30" height="30">&nbsp;</td>
                                <? $i++; }

                            if ($i%8 == 0){ ?>
                        </tr><tr>
                            <? $i=1; }
                            }

                            for ($j=1; $j<(date('t', strtotime(date("Y")."-".$month."-01")) +1); $j++){ ?>
                            <td onmouseover='this.bgColor="#EFEFEF"' onmouseout='this.bgColor="#FFF"' onclick="show_calendar('<?=date("Y") ?>-<?=$month ?>-<?=$j ?>')" width="30" height="30" style="border:#CCC 1px solid; border-radius:3px; text-align:center; vertical-align:middle; cursor:pointer"><font class="ArialVeryDarkGrey15"><?=$j ?></font></td>
                            <?
                            if ($i%7 == 0){ ?>
                        </tr><tr>
                            <? $i=0; }
                            $i++;
                            }
                            ?>
                        </tr>
                    </table>
</div>
                <?
                if ($b%2 == 0){	?>
           <tr>
                <?
                $b = 0;	}
                $month++; $b++;
                }
                ?>
            </tr>


    </div>
    </div>



    <div class="col-sm-6">
    <div id="calendardisplay" style="height:100%;overflow: scroll">
        <div style="height:55px"></div>
        <div style="height:25px; border-bottom:#FFCC66 1px solid; vertical-align:middle">
            <h5 class="ArialVeryDarkGreyBold18">
                <?php echo date('l', strtotime(date('Y-m-d'))) ?>, <?=date('jS F Y') ?></h5>
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <?
            //Get all events for the day and place in time array
            $today = date("Y-m-d");
            $sql = "SELECT * from event_bookings where start_date = ".$today;
            $getallbook = $DB->RunSelectQuery($sql);

            $event_start_times = array();
            $event_end_times = array();
            $event_names = array();
            $line_colours = array();

            foreach($getallbook as $resultdata ){
                $result = (array) $resultdata;
                $event_id = $result["event_id"];
                $sqlevent = "SELECT * from events where event_id = :event_id";
                $stmt2 = $pdo->prepare($sqlevent);
                $stmt2->bindParam(':event_id', $event_id, PDO::PARAM_INT);
                $stmt2->execute();
                while($resultevent = $stmt2->fetch( PDO::FETCH_ASSOC )){
                    array_push($event_start_times, date('h:i:s',$resultevent["start_date"]));
                    array_push($event_end_times, date('h:i:s',$resultevent["end_date"]));
                    array_push($event_names, $resultevent["event_name"]);
                    $random_colour = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
                    array_push($line_colours, $random_colour);
                }
            }

            $i=0;
            $a = 0;
            while ($a<24){
                $starttime =  date('H:i:s', strtotime('00:00:00 +'.$a.' hours'));
                ?>
                <tr valign="top">
                    <td width="25%" height="80" style="border-bottom:#E6E6E6 1px solid;border-right:#E6E6E6 1px solid;"><div style="height:5px"></div><div class="ArialVeryDarkGrey13" style="color:#CCC">&nbsp;&nbsp;&nbsp;<? echo date('g:i A', strtotime($starttime.' + 0 minutes'))." - ".date('g:i A', strtotime($starttime.' + 15 minutes'));
                            $start_time_compare = date('H:i:s', strtotime($starttime.' + 0 minutes'));
                            $end_time_compare = date('H:i:s', strtotime($starttime.' + 15 minutes'));
                            for ($i=0; $i < sizeof($event_start_times); $i++){
                                if ($event_start_times[$i] == $start_time_compare){ ?>
                                    <div style="height:10px"></div>
                                    <div style="height:15px; background:<?=$line_colours[$i] ?>; border-radius:3px 0 0 3px;">&nbsp;&nbsp;&nbsp;<font class="ArialVeryDarkGrey13" style="color:#FFF"><?=$event_names[$i] ?></font></div>
                                <?  }
                                if ($event_end_times[$i] > $end_time_compare && $event_start_times[$i] < $start_time_compare){ ?>
                                    <div style="height:10px"></div>
                                    <div style="height:15px; background:<?=$line_colours[$i] ?>;"></div>
                                <?  }
                                if ($event_end_times[$i] == $end_time_compare){ ?>
                                    <div style="height:10px"></div>
                                    <div style="height:15px; background:<?=$line_colours[$i] ?>; border-radius:0 3px 3px 0;"></div>
                                <?  }
                            }
                            ?></div>

                    </td>
                    <td width="25%" height="80" style="border-bottom:#E6E6E6 1px solid;border-right:#E6E6E6 1px solid;"><div style="height:5px"></div><div class="ArialVeryDarkGrey13" style="color:#CCC">&nbsp;&nbsp;&nbsp;<? echo date('g:i A', strtotime($starttime.' + 15 minutes'))." - ".date('g:i A', strtotime($starttime.' + 30 minutes'));
                            $start_time_compare = date('H:i:s', strtotime($starttime.' + 15 minutes'));
                            $end_time_compare = date('H:i:s', strtotime($starttime.' + 30 minutes'));
                            for ($i=0; $i < sizeof($event_start_times); $i++){
                                if ($event_start_times[$i] == $start_time_compare){ ?>
                                    <div style="height:10px"></div>
                                    <div style="height:15px; background:<?=$line_colours[$i] ?>; border-radius:3px 0 0 3px;">&nbsp;&nbsp;&nbsp;<font class="ArialVeryDarkGrey13" style="color:#FFF"><?=$event_names[$i] ?></font></div>
                                <?  }
                                if ($event_end_times[$i] > $end_time_compare && $event_start_times[$i] < $start_time_compare){ ?>
                                    <div style="height:10px"></div>
                                    <div style="height:15px; background:<?=$line_colours[$i] ?>;"></div>
                                <?  }
                                if ($event_end_times[$i] == $end_time_compare){ ?>
                                    <div style="height:10px"></div>
                                    <div style="height:15px; background:<?=$line_colours[$i] ?>; border-radius:0 3px 3px 0;"></div>
                                <?  }
                            }
                            ?></div></td>
                    <td width="25%" height="80" style="border-bottom:#E6E6E6 1px solid;border-right:#E6E6E6 1px solid;"><div style="height:5px"></div><div class="ArialVeryDarkGrey13" style="color:#CCC">&nbsp;&nbsp;&nbsp;<? echo date('g:i A', strtotime($starttime.' + 30 minutes'))." - ".date('g:i A', strtotime($starttime.' + 45 minutes'));
                            $start_time_compare = date('H:i:s', strtotime($starttime.' + 30 minutes'));
                            $end_time_compare = date('H:i:s', strtotime($starttime.' + 45 minutes'));
                            for ($i=0; $i < sizeof($event_start_times); $i++){
                                if ($event_start_times[$i] == $start_time_compare){ ?>
                                    <div style="height:10px"></div>
                                    <div style="height:15px; background:<?=$line_colours[$i] ?>; border-radius:3px 0 0 3px;">&nbsp;&nbsp;&nbsp;<font class="ArialVeryDarkGrey13" style="color:#FFF"><?=$event_names[$i] ?></font></div>
                                <?  }
                                if ($event_end_times[$i] > $end_time_compare && $event_start_times[$i] < $start_time_compare){ ?>
                                    <div style="height:10px"></div>
                                    <div style="height:15px; background:<?=$line_colours[$i] ?>;"></div>
                                <?  }
                                if ($event_end_times[$i] == $end_time_compare){ ?>
                                    <div style="height:10px"></div>
                                    <div style="height:15px; background:<?=$line_colours[$i] ?>; border-radius:0 3px 3px 0;"></div>
                                <?  }
                            }
                            ?></div></td>
                    <td width="25%" height="80" style="border-bottom:#E6E6E6 1px solid;border-right:#E6E6E6 1px solid;"><div style="height:5px"></div><div class="ArialVeryDarkGrey13" style="color:#CCC">&nbsp;&nbsp;&nbsp;<? echo date('g:i A', strtotime($starttime.' + 45 minutes'))." - ".date('g:i A', strtotime($starttime.' + 60 minutes'));
                            $start_time_compare = date('H:i:s', strtotime($starttime.' + 45 minutes'));
                            $end_time_compare = date('H:i:s', strtotime($starttime.' + 60 minutes'));
                            if ($end_time_compare !== "00:00:00"){
                                for ($i=0; $i < sizeof($event_start_times); $i++){
                                    if ($event_start_times[$i] == $start_time_compare){ ?>
                                        <div style="height:10px"></div>
                                        <div style="height:15px; background:<?=$line_colours[$i] ?>; border-radius:3px 0 0 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 40ch">&nbsp;&nbsp;&nbsp;<font class="ArialVeryDarkGrey13" style="color:#FFF"><?=$event_names[$i] ?></font></div>
                                    <?  }
                                    if ($event_end_times[$i] > $end_time_compare && $event_start_times[$i] < $start_time_compare){ ?>
                                        <div style="height:10px"></div>
                                        <div style="height:15px; background:<?=$line_colours[$i] ?>;"></div>
                                    <?  }
                                    if ($event_end_times[$i] == $end_time_compare){ ?>
                                        <div style="height:10px"></div>
                                        <div style="height:15px; background:<?=$line_colours[$i] ?>; border-radius:0 3px 3px 0;"></div>
                                    <?  }
                                }
                            }else{
                                for ($i=0; $i < sizeof($event_start_times); $i++){
                                    if ($event_start_times[$i] == $start_time_compare){ ?>
                                        <div style="height:10px"></div>
                                        <div style="height:15px; background:<?=$line_colours[$i] ?>; border-radius:3px 0 0 3px;">&nbsp;&nbsp;&nbsp;<font class="ArialVeryDarkGrey13" style="color:#FFF"><?=$event_names[$i] ?></font></div>
                                    <?  }
                                    if ($event_end_times[$i] == $end_time_compare){ ?>
                                        <div style="height:10px"></div>
                                        <div style="height:15px; background:<?=$line_colours[$i] ?>; border-radius:0 3px 3px 0;"></div>
                                    <?  }
                                }
                            }
                            ?></div></td>
                </tr>
                <?
                $a++;
            } ?>
        </table>
    </div>
</div>
</div>
</div>