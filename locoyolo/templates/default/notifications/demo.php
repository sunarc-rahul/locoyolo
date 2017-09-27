<?php

if ($result["notification_type"] == "Add_Buddy")
{

    $sql = "Select * from public_users where id=" . $result["user_id"];
    $res_data = $DB->RunSelectQuery($sql);
    foreach ($res_data as $data)
    {
        $resultuser = (array)$data;

        $user_name = $resultuser["firstname"] . " " . $resultuser["lastname"];
    }
    ?>
<!--  Add new commented here  -->
<div  class="is-notification">
    <!-- Added by Nitin Soni for show user image 18Aug/2017 -->
    <div class="who-sent-notification">
        <?php
        if ($resultuser['profile_pic'] == null)
        {
            ?>
            <img alt="event-image" class="image-has-radius" src="<?php echo ROOTURL; ?>/images/no_profile_pic.gif" />
            <?php
        }
        else
        {
            ?>
            <img alt="event-image" class="image-has-radius" src="<? echo ROOTURL . '/' . $resultuser["profile_pic"]; ?>" width="37" height="37" />
            <?php
        } ?>
    </div>
    <!-- Finsihed by Nitin Soni for user image -->
    <div class="notification-itself">
        <p class="notification-content"><?php echo $user_name; ?> wants to add you as a buddy <?php echo $event_type; ?> <strong><a style="color:#000000" href="<?php echo $event_link;?>"><?php
                    echo $event_name;
                    ?></a></strong>
            <?php $sql = "SELECT * from buddies where user_id=$user_id and buddy_id=" . $resultuser["id"];
            $res_data = $DB->RunSelectQuery($sql);
            if ($res_data[0]->status =='Confirmed buddy')
            {

            ?>
            <img src="images/green_tick.gif" style="vertical-align:middle" width="15" />&nbsp;<span class="ArialVeryDarkGreyBold15">Accepted</span>
        </p>
        <p id="notification-date"><span class="glyphicon glyphicon-user"></span><span class="make-space"></span><span class="has-noti-date-time"><?php echo date("j F, H:i a", strtotime($result["notification_date"])) ?></span></p>
    </div>
</div>
    <?php
}
else
{
    ?>
    <div id="confirm_add_buddy_status<?php
    echo $resultuser["id"];
    ?>" style="display:inline-block"><div  onClick="confirm_add_buddy(<?php
        echo $resultuser["id"];
        ?>)" class="slimbuttonblue" style="width:130px;">Accept request</div></div>
    <?php
}?> </p>
        <p id="notification-date"><span class="glyphicon glyphicon-calendar"></span><span class="make-space"></span><span class="has-noti-date-time">                            <?php
                echo date("j F, H:i a", strtotime($result["notification_date"]));
                ?></span></p>
    </div>
</div>
<!-- Add new task completed  -->
<?php
} ?>