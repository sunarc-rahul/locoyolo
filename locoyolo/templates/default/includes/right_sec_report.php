<style>
#record{ margin-right:10px}
input[type="submit"], input[type="button"] {width: 32%;}
input[type="submit"]{ margin-left:110px;}
</style>
<div id="right_sec">

<form action="" method="post" name="frmlist" id="frmlist">
							
							<?php 
								// Show particular Messages
								if(isset($_SESSION['error']))
								{
									echo'<table cellspacing="0" cellpadding="0" border="0" align="center" width="40%" style="border:2px #CCCCCC solid;margin-top:5px;"><tbody><tr><td align="center" style="padding:3px 3px 3px 3px;color:red;">';
									echo $_SESSION['error'];
									echo '</td></tr></tbody></table>';
									unset($_SESSION['error']);
								}
						
								if(isset($_SESSION['success']))
								{
									echo'<table cellspacing="0" cellpadding="0" border="0" align="center" width="40%" style="border:2px #CCCCCC solid;margin-top:5px;"><tbody><tr><td align="center" style="padding:3px 3px 3px 3px;color:green;">';
									echo $_SESSION['success'];
									echo '</td></tr></tbody></table>';
									unset($_SESSION['success']);
								} 
							?>




	

<div class='right_cat_sec' style="margin-top:50px; min-height:200px; overflow:hidden">
<div  class='green_head_wrp' ><div class="green_header">Result Sheet</div></div>
<div style='width:350px;margin:15px auto; font-weight:bold; text-align:center; line-height:40px'>
					
<table border="0" cellspacing="1" cellpadding="4" width="100%" style="">
<tr>
	<td align="right"><span style="color:#000000;">Course Name:</span><span class="red">*</span></td>
	<td><div class="styleSelect">
		<select name="exam_id" id="exam_id" class="rounded">
			<option value=''>Select Course</option>
			<?php
			if(is_array($exam))
			{
				for($counter=0;$counter<count($exam);$counter++)
				{
					$selected='';
					if ($exam[$counter]->id == $frmdata['exam_id'])
					{
						$selected='selected';
						$exam_name = stripslashes($exam[$counter]->exam_name);
					}
					echo '<option value="'.$exam[$counter]->id.'"'.$selected.'>'. stripslashes($exam[$counter]->exam_name).'</option>';
				}
			}
			?>
		</select>
		</div>
	</td>
</tr>
<tr>
	<td align="right"><span style="color:#000000; text-align:right; margin-left: 60px;">Year:</span><span class="red">*</span></td>
	<td><div class="styleSelect">
		<select name="year" id="year" class="rounded">
			<option value=''>Select Year</option>
			<?php
			if(is_array($testyearlist))
			{
				foreach($testyearlist as $year)
				{
					$selected='';
					if ($year == $frmdata['year'])
					{
						$selected='selected';
					}
					echo "<option value='$year' $selected >$year</option>";
				}
			}
			?>
		</select></div>
	</td>
</tr>
<tr id="tr_month" style="display: none;">
	<td align="right">Month:</td>
	<td><div class="styleSelect">
		<select name="month" id="month" class="rounded">
			<option value=''>Select Month</option>
			<?php
			//if(is_array($testmonthlist))
			{
				for($counter=1;$counter<=12;$counter++)
				{
					$selected='';
					if ($counter == $frmdata['month'])
					{
						$selected='selected';
					}
					echo '<option value="'.$counter.'"'.$selected.'>'. date('F', strtotime("01-$counter-2011")).'</option>';
				}
			}
			?>
		</select></div>
	</td>
</tr>

<tr>
	<td colspan="6" align="center">
		<input type="submit"  name="Submit" value="Show" onclick="return check_search();" />
		<input type="button"  name="clear_search" id="clear_search" value="Clear Search"	onclick="window.location='<?php echo CreateURL('index.php','mod=report&do=result_sheet'); ?>'" />
	</td>
</tr>
</table>

</div>


<?php if($showResult) { ?>

<?php
if($test_detail)
{
	$show_total_racords = count($test_detail); ?>
	
	<table width="96%" align="right" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<?php print "<span>Showing Results:&nbsp;".($frmdata['from']+1).'-</span><span id="show_total_record_span">'.($frmdata['from']+$show_total_racords)."</span>&nbsp;<span>of&nbsp;</span>&nbsp;<span id='show_total_count_span'>".$totalCount."</span>"; ?>
			</td>
			<td align="right"><?php echo getPageRecords();?></td>
		</tr>
	</table>
	<br /> <?php
} 
?> 
<br />

<div id="celebs" style="overflow: auto; overflow-y: hidden;">

<table border="0" cellspacing="1" cellpadding="4" id="tbl_reports" width="97%" bgcolor="#e1e1e1" class="data">
<?php
if($test_detail)
{ ?>
	<thead>
		<tr class="tblheading">
			<td width="1%">#</td>
			<td width="13%">Test Name</td>
			<td width="15%">Test Date</td>
	
			<?php if($_SESSION['admin_user_type'] != 'P') { ?>
			
			
			<td width="11%">Result</td>
			<?php } ?>
	
			<td width="9%">Details</td>
		</tr>
	</thead>

	<tbody>
	<?php
	$srNo=$frmdata['from'];
	$count=count($test_detail);
	
	if($_SESSION['admin_user_type'] != 'P')	$showGraph = true;
	
	$testCategory = '<categories>';
	$passedCandidates = "<dataset seriesName='Passed Students'>";
	$failedCandidates = "<dataset seriesName='Failed Students'>";
	
	for($counter=0;$counter<$count;$counter++)
	{
		$srNo=$srNo+1;
		if(($counter%2)==0)
		{
			$trClass="tdbggrey";
		}
		else
		{
			$trClass="tdbgwhite";
		} ?>
			
		<tr class='<?php echo $trClass; ?>'>
			<td><?php echo $srNo; ?></td>
			<td><?php echo ($test_name = stripslashes($test_detail[$counter]->test_name)); ?></td>
			<td><?php echo date('d-M-Y h:i A', strtotime($test_detail[$counter]->created_date)); ?></td>
			
			
			<?php if($_SESSION['admin_user_type'] != 'P') { ?>
			
			
			<td><?php echo ($failed = $test_detail[$counter]->result); ?></td>
			<?php } ?>
			
			<?php 
			$detailed_url = CreateURL('index.php','mod=report&do=view_test_result&exam_id='.$test_detail[$counter]->exam_id.'&test_id='.$test_detail[$counter]->test_id.'&ins='.$test_detail[$counter]->test_instance);
			?>
			
			<td>
				<img src="<?php echo IMAGEURL; ?>/user_info.gif" style="cursor: pointer;"
					title="Click here for view student result and generate report"
					onclick="window.location='<?php echo $detailed_url; ?>'"/>
			</td>
		</tr><?php
		/*
		$testCategory .= "<category label='$test_name' />";
		$passedCandidates .= "<set value='$passed' link='$detailed_url' />";
		$failedCandidates .= "<set value='$failed' link='$detailed_url' />"; */
	}
	/*
	$testCategory .= '</categories>';
	$passedCandidates .= "</dataset>";
	$failedCandidates .= "</dataset>";
	
	$FCExporter = ROOTURL . "/lib/FusionCharts/ExportHandlers/JavaScript/index.php";
	$graphXml = "<chart caption='$exam_name' xAxisName='Test' yAxisName='No of Students'"
				." bgColor='E6E6E6,F0F0F0' bgAlpha='100,50' bgRatio='50,100' bgAngle='270'" 
				." showNames='1' useEllipsesWhenOverflow='0' showBorder='1' exportEnabled='1'" 
				." html5ExportHandler='$FCExporter' exportFileName='Exam_Result'>";

	$graphXml .= $testCategory;
	$graphXml .= $passedCandidates;
	$graphXml .= $failedCandidates;
	$graphXml .= '</chart>';
	*/
	?>
	
	</tbody><?php
}
else
{
	echo "<tr><td colspan='7' align='center'>(0) Record found.</td></tr>";
}
?>

</table>
</div>
<!--<div id="graph" style="margin-top: 20px;margin: auto; width: 870px;text-align: center;"></div>-->

<?php } ?>

<?php // if($test_detail) { ?>
<!--<table border="0" cellspacing="5" cellpadding="1" width="100%" id="tbl_download_reports">
<tr>
	<td colspan="2">
		<?php // print "Showing Results:".($frmdata['from']+1).'-'.($frmdata['from']+$count)." of ".$totalCount; ?>
	</td>
</tr>
</table>-->
<?php// } ?>
	
						<input name="pageNumber" type="hidden" value="<?php print $frmdata['pageNumber']?>" /> 
						<input name="orderby" id="orderby" type="hidden" value="<?php print $frmdata['orderby']?>" />
						<input name="order" id="order" type="hidden" value="<?php print $frmdata['order']?>" />
					</form>
				</div><!--Div Contents closed-->
			</div><!--Div main closed-->
		</div><!--Content div closed-->
		</td>
	</tr>
</table>

</div>