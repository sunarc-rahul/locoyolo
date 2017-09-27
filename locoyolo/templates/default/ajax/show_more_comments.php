<?php
//print_r($_POST);exit;
$loginuserData = $userData;
include_once("../../config.php");
include_once(INC."/commonfunction.php");
include_once(INC.'/dbfilter.php');
include_once(INC.'/dbqueries.php');
include_once(INC.'/dbhelper.php');

error_reporting (E_ALL ^ E_NOTICE);

$post = (!empty($_POST)) ? true : false;

if($post)
{

    $errorcheck = false;

    $number_of_records = $_POST['number_of_records'];
    $user_id = $_POST['user_id'];
    $event_id = $_POST['event_id'];
    $event_user_id =$_POST['event_user_id'];


    $sql = "Select * from comments where event_id=$event_id order by id desc limit 10 offset ".$number_of_records."";
    $query = $DB->RunSelectQuery($sql);

    foreach ($query as $data){
        $result = (array)$data;
        ?>
        <div id="comment-person" class="comment-person">

                        <div style="display:inline-block; vertical-align:top; width:35px">
                            <?
                            $userId = $result["user_id"];
                            $sql2 = "SELECT * from public_users where id=$userId";
                           $query =$DB->RunSelectQuery($sql2);
                            foreach ($query as $data) {
                                $resultuser = (array)$data;
                                $profilepic = $resultuser['profile_pic'];
                                $user_name = $resultuser['firstname'] . " " . $resultuser['lastname'];

                            }

                                if ($profilepic == "") {
                                    ?>
                                    <img width="30" height="30" valign="middle" style="border-radius:100px" src="images/no_profile_pic.gif" />
                                <?php }else{ ?>
                                    <img width="30" height="30" valign="middle" style="border-radius:100px" src="<?php echo ROOTURL . '/'.$profilepic; ?>" />
                                <?php }
                            ?>
                        </div>
                        <div style="display:inline-block; width:10px; vertical-align:top;">
                            <div style="height:5px">
                              </div>
                                <img src="images/speech_triangle.gif" width="10" />
                        </div>
            <div style="display:inline-block;">
                <div style="height:5px">                    
                </div>
                <div style=" border-radius:0px 3px 3px 3px; padding:5px" id="comment_content<?php echo $result["id"] ?>"><h5 class="ArialVeryDarkGreyBold15">
                        <?php echo $user_name;echo $userEvent_id; ?></h5>
                    <h5 class="ArialVeryDarkGrey15"> <?php echo $result["comment"] ?></h5>
                    <div style="display:inline-block;"><h5 class="ArialVeryDarkGrey15" style="color:#999; font-size:13px"> <?php echo date("j M Y", strtotime($result["entry_date"])) ?>, <?php echo date("h:i a", strtotime($result["entry_date"])) ?></h5></div>&nbsp;&nbsp;
        <?php if ($loginuserData->id == $resultuser['id'] || $loginuserData->id == $event_user_id) { ?>
            <div style="display:inline-block; cursor:pointer" onclick="delete_comment(<?php echo $result["id"] ?>, '<?php echo $user_name ?>')"><h5 class="ArialVeryDarkGrey15" style="color:#F63; font-size:13px">Delete</h5></div> <?php }?>
                </div>
            </div>


        </div>
        <?php
    } }else{ ?>
    <div style="height:40px; width:100% margin: 0 auto; text-align:center"><div style="height:20px"></div><h5 class="ArialVeryDarkGrey15">No comments yet...</h5></div>
    <?php
}
?>