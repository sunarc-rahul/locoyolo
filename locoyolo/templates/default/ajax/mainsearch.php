<?php
include_once("../../config.php");
include_once(INC."/commonfunction.php");
include_once(INC.'/dbfilter.php');
include_once(INC.'/dbqueries.php');
include_once(INC.'/dbhelper.php');
$search = $_REQUEST["s"];

if ($search == null) {

//    header('Location: index.php');
//    echo $message = "enter some text";

} else {
    $qry = "SELECT event_id as id, event_name,start_date, event_photo as img, 'event' as type, entry_type as data_type FROM events WHERE event_name LIKE '%" . $search . "%' limit 8";

    $result = $res_data = $DB->RunSelectQuery($qry);
     if(!is_array($result))
     {
         $result =array();
     }
    $data = array();
      if (count($result)> 0) {
        foreach($result as $row) {
			$row = (array) $row;
            $data[] = $row['event_name'];
        $link='';
		if($row['type']=='user')
		{
			$link =	$link = createURL('index.php',"mod=user&do=profile&userid=".$row['id']."&s=".$row['event_name']);
		}
		if($row['type']=='event')
		{
			if($row['data_type']=='Ping')
			{

					$link = createURL('index.php',"mod=ping&do=pingdetails&eventid=".$row['id']."&s=".$row['event_name']);
			}
			else
			{

				$link = createURL('index.php',"mod=event&do=eventdetails&eventid=".$row['id']."&s=".$row['event_name']);
			}
		}
//		if(file_exists(ROOTURL.'/'.$row['img']))
		if($row['img']!= null)
		{
			$img = ROOTURL.'/'.$row['img'];
		}
		else
		{
			$img = ROOTURL.'/images/profile_img.jpg';
		}
            if($row['data_type']=='Ping')
            {
                $img = ROOTURL.'/images/ping.jpg';
            }

       $d .='<a href="'.$link.'"><div class="display_box" align="left">';
		$str = $row['start_date'];

if($str!='0000-00-00 00:00:00') {
    $newDate = date(" d M Y h:i:s A", strtotime($str));

}else{
    $newDate =' No Time Available.';
}
                $d .= '<img src="' . $img . '" style="width:50px; height:50px; float:left; margin-right:6px;" />';
            $d .=' <span class="name">'.$row['event_name'].'<div class="search-date-time"><span class="date">'.$newDate.'</span> ' . ' </div>'.'</span></div></a>';

    }

	}
	else
	{
		$d .='<div style="border-bottom:solid 1px #ccc" class="display_box" align="left">
<span class="name">No result found!</span></div>';
		
	}
	
echo $d;exit;

}

?>