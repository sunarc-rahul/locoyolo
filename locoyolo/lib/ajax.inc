<?php
function setCountry($country) {
// Build an array of all states that fall under that country
$states = array();
$q = mysql_query("select * from states where countryid = $country order by statename");
while ($r = mysql_fetch_array($q)) {
$x = $r['stateid'];
$states[$x] = $r['statename'];
}
 
// Create a new object to handle the outgoing XML.
$o = & new xajaxResponse();
 
if (count($states) > 0) {
// addScript simply adds the javascript to the response that will be returned to the client.
$o-&gt;addScript("document.getElementById('state').disabled = false;");
$o-&gt;addScript("document.getElementById('state').options.length = 0;");
$o-&gt;addScript("addOption('state','--Select a State--','0');");
 
foreach ($states as $id =&gt; $s)
{
// We add a javascript function call to addOption. this will be defined in the main php script.
$o-&gt;addScript("addOption('state','" . htmlentities($s) . "','" . $id . "');");
}
}
 
// Return the XML to the requesting client
return $o->getXML();
}
 
// An object of the xajax class, this object will be used throughout.
$xajax =& new xajax();
 
// Switch on debug output, you can skip this step if you don't want to know what is going on in the background. Good for debugging.
$xajax->debugOn();
 
// Register the function which we will declare below which will be used in the main script.
$xajax->registerFunction("setCountry");
 
// Ask the object to handle all requests and to parse and construct XML data.
$xajax->processRequests();
?>