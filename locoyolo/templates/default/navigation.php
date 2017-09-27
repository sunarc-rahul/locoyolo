<?php
defined("ACCESS") or die("Access Restricted");

$mod = '';
if(isset($_GET['mod']))
{
	$mod= $_GET['mod'];
}
$do = '';
if(isset($_GET['do']))
{
	$do = $_GET['do'];
}

$admin_info = $DB->SelectRecord('candidate','id ='.$_SESSION['candidate_id']);


$module = $mod;
if ($mod=='test_master' || $mod=='examination' || $mod=='paper' || $mod=='homework')
	$module = 'exam';
if ($mod=='stream_master' || $mod=='subject_master')
	$module = 'stream';
if ($mod == 'role' || $mod == 'assignrole' || $mod == 'users')
	$module = 'role';

$current[$module] = "current";

$do = $do ? $do : 'manage';
$do = ($do != 'edit') ? $do : 'manage';
$current_sub[$mod][$do] = 'current_sub';
?>

<!--<div id="main-menu">
<ul id="dropline">-->
	<?php
	 //Call the isModuleAccessible($module,$user_id) and decide whether to show the module in navigation or not
//	 if ($isAdmin || $auth->isModuleAccessible('dashboard',$user_id)):			 
	 ?>
		<!--<li class="<?php echo $current['dashboard']; ?>"><a href="<?php print CreateURL('index.php','mod=dashboard&do=showinfo&master_nav=1'); ?>"><b>Dashboard</b></a>
			<?php if($current['dashboard']) { ?>
			<ul id="secondary">
				<li class="<?php echo $current_sub['dashboard']['showinfo']; ?>">
					<a class="last" href="<?php print CreateURL('index.php','mod=dashboard&do=showinfo'); ?>">Dashboard</a>
				</li>-->
				
				<!--<li style="float:right;">
					<a class="last">Welcome  <?php echo (($admin_info->first_name).' '.$admin_info->last_name); ?></a>
			<!--	</li>-->
			<!--</ul>
			<?php  } ?>
		</li>-->


		<?php

	//endif;

	/*if ($isAdmin ||  $auth->isModuleAccessible('parent_master',$user_id)):			
		?>
		<li class="<?php echo $current['parent_master']; ?>"><a href="<?php print CreateURL('index.php','mod=parent_master&do=manage&master_nav=1');?>"><b>Parents</b></a>
			<ul id="secondary">
				<li class="<?php echo $current_sub['parent_master']['manage']; ?>">
					<a href="<?php print CreateURL('index.php','mod=parent_master&do=manage');?>">Manage Parents</a>
				</li>
				
				<?php if($isAdmin || $auth->isAuthorisedAction($user_id, 'parent_master', 'add')): ?>
				<li class="<?php echo $current_sub['parent_master']['add']; ?>">
					<a class="last" href="<?php print CreateURL('index.php','mod=parent_master&do=add');?>">Add Parent</a>
				</li>
				<?php endif; ?>
				
			</ul>
		</li>
		<?php
	endif;
	*/
//	if ($isAdmin ||  $auth->isModuleAccessible('report',$user_id)):			
		?>
		<!--<li class="<?php echo $current['report']; ?>"><a href="<?php print CreateURL('index.php','mod=report&do=result_sheet&master_nav=1');?>"><b>Reports</b></a>
			<ul id="secondary">
				<li class="<?php echo $current_sub['report']['result_sheet']; ?>">
					<a href="<?php print CreateURL('index.php','mod=report&do=result_sheet');?>">Result Sheets</a>
				</li>-->
				
				<?php //if($isAdmin || $auth->isAuthorisedAction($user_id, 'report', 'candidate_detail')): ?>
				<!--<li class="<?php echo $current_sub['report']['candidate_detail']; ?>">
					<a href="<?php print CreateURL('index.php','mod=report&do=candidate_detail');?>">Student Details</a>
				</li>
				<?php //endif; ?>
				
				<li class="<?php echo $current_sub['report']['candidate_performance']; ?>">
					<a href="<?php print CreateURL('index.php','mod=report&do=candidate_performance');?>">Student Performance</a>
				</li>
			</ul>
		</li>

		<li class="<?php echo $current['tools']; ?>"><a href="<?php print CreateURL('index.php','mod=mailer&master_nav=1'); ?>"><b>Tools</b></a>
			<ul id="secondary">-->
				
				<?php //if($isAdmin || $auth->isModuleAccessible('mailer',$user_id)) { ?>
				<!--<li class="<?php echo $current_sub['mailer']['manage']; ?>">
					<a href="<?php print CreateURL('index.php','mod=mailer'); ?>">Send Mail</a>
				</li>
				<?php //} ?>
				
				<?php //if ($isAdmin ||  $auth->isModuleAccessible('feedback',$user_id)) { ?>
				<li class="<?php echo $current_sub['feedback']['manage']; ?>">
					<a href="<?php print CreateURL('index.php','mod=feedback&do=manage');?>">Feedback</a>
				</li>
				<?php //} ?>
				
				<?php // } ?>
				
			</ul>
		</li>-->

	
	<!--<li><a href="<?php print CreateURL('index.php','mod=admin&do=logout');?>"><b>Logout</b></a></li>
</ul>
<div id="menu-border"></div>
</div>-->