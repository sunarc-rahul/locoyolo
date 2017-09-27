<?php
$path = $CFG->template;
$mod =$_REQUEST['mod'];

switch($path) {

    default:
        include_once(TEMP . "/" . $CFG->template);
        break;

    case "user/search.php":
        $mod ='ajax';
        break;

    case "user/dashboard.php":
        $mod ='ajax';
        break;
}

switch($mod) {

    default:
        include_once(TEMP . "/" . "footer.php");
        break;

    case "ajax":
        include_once(TEMP . "/" . $CFG->template);
        break;

}

?>
