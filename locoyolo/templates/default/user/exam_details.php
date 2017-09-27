<?php

 /*****************Developed by :- Akshay Yadav
				Module       :- User
				Purpose      :- Template for Browse all  Test data
	***********************************************************************************/

//echo '<pre>';print_r($_SESSION);
?>
<!DOCTYPE HTML>
<html>
	<head>
	<link rel="stylesheet" href="<?php echo ROOTURL;?>/css/style.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<title>Online Examination</title>
	<link rel="stylesheet" href="<?php echo ROOTURL;?>/css/style.css" />
<script type="text/javascript">
 document.createElement('header');
 document.createElement('nav');
 document.createElement('menu');
 document.createElement('section');
 document.createElement('article');
 document.createElement('aside');
 document.createElement('footer');

</script>

	</head>
	<body>
		<?php include_once(TEMPPATH."/includes/header.php"); ?>
		
		<section id="main_sec" style='overflow:visible'>
			<?php include_once(TEMPPATH."/includes/left_sec.php"); ?>
			<div id="right_sec">
				<div class="right_cat_sec" style="margin-top:50px">
				<div class="green_head_wrp"><div class="green_header">Select Test</div></div>	<div style="width:730px; text-align:center; margin:auto">
			
			<?php if($user_test_details) 
			{
			foreach($user_test_details as $test){
					 $test_id = $test->id;
					 $exam_id = $test->exam_id;
					 ?>
					<div class="exam_box" <?php if(isset($_SESSION['testid'])&& $_SESSION['testid'] != $test_id){ ?> onClick="alert('Please complete <?php echo $_SESSION['test_name']; ?> Test first')" <?php }else{?>onClick="window.open('<?php echo CreateURL('index.php','mod=guidelines&e='.$test_id.'&t='.$exam_id); ?>', 'newwin', 'height='+screen.height+'px,width='+screen.width+'px');" <?php } ?>><?php if($test){  echo $test->test_name; } else { echo "<br/>Sorry !! NO test's availabe for this course now.";}?></div>
					<?php } }
					
					else{
					
					?>
					<h2>No Records are Available.</h2>
					<?php
					}
					?>
 			
 				</div>
				</div>
			</div>
    	</section>
	</body>
</html>
