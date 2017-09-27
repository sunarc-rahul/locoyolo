<style>
.test_sec_left {text-align:left; width:100%;}
.test_head{ text-align:center; }
.test_sec_left h4{float: left; margin-bottom: 5px;margin-top: 5px; width: 49%;  text-align: center;}
.user_img {width: 35px !important;}
</style>
<?php
//print_r($testHistoryAll);
if($testHistoryAll)
{
	$totalResultP=0;
	$totalResultF=0;
	$totalTest=count($testHistoryAll);
	for($i=0; $i<$totalTest; $i=$i+1)
	{
		if($testHistoryAll[$i]->result=='P')
		{
			$totalResultP = $totalResultP+1;
		}
		else if($testHistoryAll[$i]->result=='F')
		{
			$totalResultF = $totalResultF+1;
		}
	}

	$lastLoginDate=$lastLoginData[1]->login_time;

}
else
{
	$totalResultP=0;
	$totalResultF=0;
	$totalTest=0;
	if($lastLoginData)
	{
		$lastLoginDate=$lastLoginData[1]->login_time;
	}
	else
	{
		$lastLoginDate='00-00-0000';
	}
}
?>
<div id="right_sec">
        <div id="dashbord_sec" style="border:1px solid #066a75;">
        <div  style='width:100%'><div style="height:20px;width:200px;text-align:center;background:#066a75;color:#fff;font-size:1.2em;padding:5px;font-weight:bold;box-shadow:-1px 1px 2px #666">Personal Details</div></div>
            <div style="width:880px; margin: auto">
            <div class="dash_left">
              <div class="dash_left_img">
                <?php
              $arr = parse_url($candi->pro_image);
                if(!isset($arr['host']))
                {
                    $path = ROOTURL."/panel/uploadfiles/".$candi->pro_image;
                }else if(isset($arr['host'])){

                    $path = $candi->pro_image;

                }
               if($arr['path'] == '')
                $path =  ROOTURL.'/css/images/no_image.gif';
        ?>
        <img  src = <?php echo $path; ?> />
                </div>
              <div class="dash_left_name">
                <h4><?php if($candi->first_name!='') echo ucwords($candi->first_name.' '.$candi->last_name); ?></h4>
                <h4><?php if($candi->email!='') echo $candi->email; ?></h4>
              </div>
          </div>
              <div class="dash_right">
                <div class="test_sec">
                    <div class="test_sec_left">
                      <div class="test_head">
                          Examination Test
                    </div>
                    <h4>Total Given Test: <?php echo $totalTest; ?></h4>
                    <h4>Last Login Date:<?php if(isset($lastLoginDate) && $lastLoginDate!= 0000-00-00)
                                                { echo date('d-m-Y',strtotime($lastLoginDate)); }
                                                else { echo '00-00-0000' ; }?> </h4>
                    <h4>Pass: <?php echo $totalResultP;?></h4>
                    <h4>Fail: <?php echo $totalResultF;?> </h4>
                 </div>

                <!--<div class="test_sec_right">
                     <div class="test_head">
                         Practice Test
                     </div>
                     <h4>Total Package Activated: 0</h4>
                     <h4>Total Test: 0</h4>
                </div>-->

            </div>
          </div>
        </div></div>
        <div>


    <div style='min-height:290px; border:solid 1px #066a75;width:99%; background:#fff;float:left;margin-top:5px'>
        <div  style='width:100%'><div style="height:20px;width:200px;text-align:center;background:#066a75;color:#fff;font-size:1.2em;padding:5px;font-weight:bold;box-shadow:-1px 1px 2px #666">Reports and Statistics</div>
    </div>
<?php
if($test_subject_result)
{
	$passed_subject = 0;
	$failed_subject = 0;

	$timeTrackXml = "<chart caption='Time Track' showLegend='1' showPercentValues='0' bgColor='E6E6E6,F0F0F0' bgAlpha='100,50' bgRatio='50,100' bgAngle='270' showNames='1' useEllipsesWhenOverflow='0' showBorder='1'>";

	for($index=0; $index<count($test_subject_result); $index++)
	{ 
		$label = ucfirst(stripslashes($test_subject_result[$index]['subject_name']));
		$value = $test_subject_result[$index]['solve_time'];
		$display_value = (floor($value/60)).'M '.($value%60).' S';
		$timeTrackXml .= "<set label='$label' value='$value' displayValue='$display_value' />";
		
		($test_subject_result[$index]['result'] == 'Pass') ? $passed_subject++ : $failed_subject++;


	}

	$timeTrackXml .= "</chart>";

	$marksXml = "<chart caption='Subject Result' showLegend='1' showPercentValues='0' bgColor='E6E6E6,F0F0F0' bgAlpha='100,50' bgRatio='50,100' bgAngle='270' showNames='1' useEllipsesWhenOverflow='0' showBorder='1' exportEnabled='1' html5ExportHandler='$FCExporter' exportFileName='marks_obtained'>";
	$marksXml .= "<set label='Pass' value='$passed_subject' color='55ff55' />";
	$marksXml .= "<set label='Fail' value='$failed_subject' color='ff5555' />";
	$marksXml .= "</chart>";



	$count = 0;
	$totalTime = 0;
	$quetionTimeXml = "<chart caption='Time Taken Per Question' xAxisName='Question' yAxisName='Time Taken To Solve' defaultNumberScale='S' numberScaleValue='60,60' numberScaleUnit='M,H' bgColor='E6E6E6,F0F0F0' bgAlpha='100,50' bgRatio='50,100' bgAngle='270' showNames='1' useEllipsesWhenOverflow='0' showBorder='1' >";
	foreach($questionHistory as $key => $que)
	{
		$count++;
		$totalTime += $que->solve_time;
		$quetionTimeXml .= "<set label='$count' value='$que->solve_time' link='javascript:goToQuestion(".'\"'.$que->question_id.'\"'.");' />";
	}
	$averageTime = $totalTime/$count; 
	$quetionTimeXml .= "<trendLines><line startValue='$averageTime' color='009933' displayvalue='Average' /></trendLines>";
	$quetionTimeXml .= "</chart>";
?>			<div style="width:875px; margin: auto">	
			<div class="info-block">
				<fieldset class="rounded">
				<legend>Last Test Time Track Per Question</legend>
				<div id="question-time-graph" style="text-align: center;padding: 10px;"><br/><br/></div>
				
				</fieldset>
			</div>	
			<div style="clear: both;"></div><br/>
			</div>
			<!--</div>-->
<?php }else{ ?>

	<table border="0" cellspacing="1" cellpadding="4" id="tbl_reports" width="100%" bgcolor="#e1e1e1" class="data">
		<tbody>
		<tr class='tdbggrey'>
			<td colspan="5">No Graph available.</td>
		</tr>
		</tbody>
	</table>

<?php } ?>
		</div>
			<div class='right_cat_sec'>
				<div class='green_head_wrp'><div class="green_header">Last Test Information</div></div>
		<?php	if($testdetail[0]->test_name) {
					if($testdetail[0]->test_name!='')
					{ 
						$_SESSION['maximumMarks'] = $testdetail[0]->maximum_marks;
						$_SESSION['examName'] = $testdetail[0]->exam_name; ?>
		
				<table border="0" cellspacing="1" cellpadding="4" id="tbl_reports" width="95%" bgcolor="#e1e1e1" class="data">
					<thead>
						<tr class="tblheading">
							<!--<td width="15%">Candidate Name</td>-->
							<td width="15%">Test Name</td>
							<td width="11%">Exam Date</td>
							
							<td width="11%">Total Marks</td>
							<td width="11%">Obtained Marks</td>
							<td width="11%">Percentage (%)</td>
							<td width="11%">Result</td>
						</tr>
					</thead>
					<tbody>
					  
					<tr class='tdbggrey'>
						<!--<td><?php //echo stripslashes($testdetail[0]->fname). ' '.stripslashes($testdetail[0]->lname); ?></td>-->
						<td><?php echo stripslashes($testdetail[0]->test_name); $_SESSION['lastTestName']=stripslashes($testdetail[0]->test_name); ?></td>
						<td><?php echo date('d M Y',strtotime($testdetail[0]->created_date)); ?></td>
						
						<td><?php echo $testdetail[0]->maximum_marks ; ?></td>
						<td><?php echo $testdetail[0]->marks_obtained;?></td>
						<td><?php echo (number_format(($testdetail[0]->percentage), 2, '.', '')); ?></td>
						<td><?php echo $testdetail[0]->result;?></td>
					</tr>	
					</tbody>
				</table>
				<?php 	}
					}
				else
				{ 
				?>	
	<table border="0" cellspacing="1" cellpadding="4" id="tbl_reports" width="100%" bgcolor="#e1e1e1" class="data">
		<tbody>
		<tr class='tdbggrey'>
			<td colspan="5">No updates are available.</td>
		</tr>
		</tbody>
	</table><?php 
} 
?>		
	</div>
	<script>
		FusionCharts.setCurrentRenderer('javascript');
		var myChart = new FusionCharts("<?php echo ROOTURL; ?>/lib/FusionCharts/Line.swf", 'question-time-chart', 850, 285, 0, 1);
		myChart.setXMLData("<?php echo $quetionTimeXml; ?>");
		myChart.render('question-time-graph');
	</script>
	