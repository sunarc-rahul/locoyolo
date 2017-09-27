<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
?>
<!DOCTYPE HTML>

<html>
<head>
    <title>Online Examination - course</title>
    <link rel="stylesheet" href="<?php echo ROOTURL; ?>/css/style.css"/>
    <style>
        body, #content {
            background-color: #f1f1f1;
        }

        #main {
            margin: 0 auto;
        }

        .main_h1 {
            margin-left: 50px;
        }

        .main_h1 div {
            background-color: #066a75;
            color: #FFFFFF;
            width: 220px;
            padding-left: 15px;
        }

        .course_main {
            height: auto;
            min-height: 100px;
            width: 800px;
            margin-left: 50px;
            margin: 50px auto;
            text-align: center

        }

        #link {
            width: 200px;
            height: 10%;
            margin: 20px;
            margin-left: 25px;
            pointer: cursor;
            /*border:1px solid red;*/
            float: left;
        }

        #link div:hover {
            color: #066a75;
        }

        .comp-name {
            text-align: center;
            margin: 0 auto;
            background-color: #f1f1f1;
            color: #000000;
            font-weight: bold;
            height: 30px;
            /*width:150px;
            padding-left:15px;*/
            padding-top: 15px;
            box-shadow: 0 0 2px #666;
            cursor: pointer;
            text-decoration: none;
        }

    </style>
</head>
<body>
<?php include_once(TEMPPATH . "/includes/header.php"); ?>
<section id="main_sec" style='overflow:visible'>
    <?php include_once(TEMPPATH . "/includes/left_sec.php"); ?>
    <?php //include_once(TEMPPATH."/includes/right_sec_report.php"); ?>
</section>

<div id="right_sec">


    <div id="main">
        <div class='right_cat_sec' style="margin-top:50px">
            <div class='green_head_wrp'>
                <div class="green_header">Select Package</div>
            </div>
            <span class="response"><?php
                echo $response_payment;
                ?></span>
            <span class=""><?php
                echo isset($_SESSION['success']) ? $_SESSION['success'] : '';
                ?></span>

            <div class="course_main">
                <?php
                if ($getPackageDetailsWithNameList) {
                    foreach ($getPackageDetailsWithNameList as $category) {
//                        print_r(date($category->date_of_package_expiry));
//                        echo '<br>';
//                        echo date("Y-m-d");
                        ?>
                        <?php
                        //                            if ($category->exam_name != '' && $category->status == 'TXN_SUCCESS' )
                        if ($category->status == 'TXN_SUCCESS') {
                            if (strtotime(date("Y-m-d")) > strtotime(date($category->date_of_package_expiry))) {
                                ?>
                                <div id="link">

                                    <div class="comp-name" style="border: 2px solid red;">
                                        <?php

                                        echo ucwords($category->package_name);
                                        ?>
                                    </div>
                                    <span>Its now been expired. </span>
                                </div>
                            <?php } else {
                                ?>
                                <div id="link"
                                     onclick=location.href="<?php echo CreateURL('index.php', "mod=user&do=course&package=" . $category->package_id); ?>">

                                    <div class="comp-name">
                                        <?php
                                        echo ucwords($category->package_name);
                                        ?>
                                    </div>
                                </div>

                            <?php }
                        } ?>
                        <?php
                    }


                } else {
                    ?>
                    <h2>No Records are Available now, You can buy test.</h2>
                    <?php
                }
                ?>

            </div>
        </div>
    </div>
</div>

<input id="defaultLang" value="12" type="hidden">

<script type="text/javascript">
    $(document).ready(function () {
        if ($('.response').html().length > 0) {
            window.location = window.location.href;
        }
    })
</script>
<?php
if (isset($_SESSION['success']) && empty($response_payment)) unset($_SESSION['success']);
?>
</body>
</html>