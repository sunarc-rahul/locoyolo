<?php 
/**
 * Developed by :- Richa verma
 * Date         :-25 JUne 2011
 * Module       :- start.php
 * Purpose      :- It works application starting point 
 */

include_once("config.php");
include_once(INC."/commonfunction.php");
include_once(INC.'/dbfilter.php');
include_once(INC.'/dbqueries.php');
include_once(INC.'/dbhelper.php');
include_once(ADMINROOT .'/lib/Markdown/markdown.php');

$frmdata = ChangeData();
$getVars = array();
ParsingURL();
?>