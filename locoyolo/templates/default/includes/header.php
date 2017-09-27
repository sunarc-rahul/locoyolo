	<?php defined("ACCESS") or die("Access Restricted");
	$candi = $DB->SelectRecord('candidate', "id = '".$_SESSION['candidate_id']."'");
	?>
	<!------------------------header--------------------->
	<script type="text/javascript">
	 document.createElement('header');
	 document.createElement('nav');
	 document.createElement('menu');
	 document.createElement('section');
	 document.createElement('article');
	 document.createElement('aside');
	 document.createElement('footer');
	</script>
	<script>
		$( document ).ready(function() {
		$('#profile_sec').css('display','none');
		$('#user_detail').click(function(){
			 $( "#profile_sec" ).slideToggle( "slow");
		});
		$('#left_sec,#right_sec,#mainleft,#mainright').click(function(){
			 $( "#profile_sec" ).slideUp( "fast");	
			});
		
		});
		
	</script>
		
		<header>
			<div id="logo">
				<a href='<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>'><img src="<?php echo ROOTURL;?>/images/logo.png" height='27'/></a>
			</div>
			<div id="user_detail">
				<div class="user_img">
					 <?php
                      	$arr = parse_url($candi->pro_image);                  
                        if(!isset($arr['host']))
                        { $path = ROOTURL."/panel/uploadfiles/".$candi->pro_image;
                        }else if(isset($arr['host'])){
                            $path = $candi->pro_image;
                        }
                       if($arr['path'] == '')
                        $path =  ROOTURL.'/css/images/no_image.gif';
                		?>
                <img  height="30" width="30" src = <?php echo $path; ?> />
				</div>
				<div style="overflow:hidden;text-overflow:ellipsis" class="user_name"><?php echo ucwords($candi->first_name); ?></div>
				
			</div>
			
		</header>
		<!------------------------header--------------------->
		<!----------------------------------->
		
		<?php //if(isset($_GET['e']) && $_GET['e']!='')
		//{}
		//else
		//{
		?>
		<div id='profile_sec' style="height:200px;
		 display:none; position:fixed; right:0px;
		  width:350px; top:41px; float:right;background:#fff;border:solid 2px #066a75;
		  border-top:none;margin-right:10px;  z-index:1">
 			 <div style="width:40%;height:200px;float:left">
     			 <div style="height:160px; width:100%;">
					 <?php 
                      	$arr = parse_url($candi->pro_image);                  
                        if(!isset($arr['host']))
                        { $path = ROOTURL."/panel/uploadfiles/".$candi->pro_image;
                        }else if(isset($arr['host'])){
                            $path = $candi->pro_image;
                        }
                       if($arr['path'] == '')
                        $path =  ROOTURL.'/css/images/no_image.gif';
                		?>
                <img style="height:120px; width:100px; border:solid 2px #066a75; margin:12%" src = <?php echo $path; ?> />
      			</div>
      			<div style="height:50px;width:100%; text-align:center;">
				<?php if($mod!='guidelines'){ ?>
         		 <a href="<?php print CreateURL('index.php','mod=account&do=myaccount');?>" style="color:#fff;background:#066a75;padding:0.5em 1em;font-size:1em;
				 font-weight:bold;test-decortion:none;"/>View Profile</a>  
				 <?php } ?>
    		   </div>
  			</div>
  
			<div style='width:59%;height:160px;float:right; text-overflow:ellipsis '> 
     			<div style="width:100%;word-wrap:break-word">	 
					
					<h4><?php if($candi->first_name)
					{
						echo ucwords($candi->first_name.'&nbsp;'.$candi->last_name); 
					}
					else
					{
						$cname = $DB->SelectRecord('candidate','id='.$_SESSION['candidate_id']);
						echo $cname->email;
					}
					?></h4>
      				
					<?php if($candi->birth_date !='' && $candi->birth_date != 0000-00-00 ){?>
					<h4> Birth Date: <?php if($candi->birth_date !=''){echo date('d M Y',strtotime($candi->birth_date));}
											/*else if($candidateData->birth_date !=''){ echo date('d M Y',strtotime($candidateData->birth_date));}*/
											else { echo '';}
											?>
					</h4>
					<?php } ?>
          			<!--<h4> Email: <?php echo $candi->email; ?> </h4>-->
				 </div>
  			</div>
   			<div style="height:50px;width:100%; text-align:center;">
          		<a 	<?php if($mod!='guidelines'){ ?>
					href="<?php print CreateURL('index.php','mod=admin&do=logout');?>"
					<?php } else { ?>
					href="<?php print CreateURL('index.php','mod=admin&do=logout&closewin=1');?>"
					<?php } ?>
				 style="color:#fff;background:#066a75;padding:0.5em 1em;
				font-size:1em;font-weight:bold;test-decortion:none;" />Log Out</a>  
      		</div>
		</div>
	<?php //}?>
		<!-------------------------------------------->