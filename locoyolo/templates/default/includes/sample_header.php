	<?php defined("ACCESS") or die("Access Restricted");
	
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
				<a href='<?php echo CreateURL('index.php',"mod=dashboard&do=showinfo"); ?>'><img src="<?php echo ROOTURL;?>/images/logo.png" height='27'/></a>
			</div>
			<div id="user_detail">
				<div class="user_img">
					 <?php
                      	
                        $path =  ROOTURL.'/css/images/no_image.gif';
                		?>
                <img  height="30" width="30" src = <?php echo $path; ?> />
				</div>
				<div class="user_name">Guest User</div>
			</div>
			
		</header>
		<!------------------------header--------------------->
		<!----------------------------------->
		
		
		

		<div id='profile_sec' style="height:200px;
		 display:none; position:fixed; right:0px;
		  width:350px; top:41px; float:right;background:#fff;border:solid 2px #066a75;
		  border-top:none;margin-right:10px;  z-index:1">
 			 <div style="width:40%;height:200px;float:left">
     			 <div style="height:160px; width:100%;">
					 <?php 
                      	
                        $path =  ROOTURL.'/css/images/no_image.gif';
                		?>
                <img style="height:120px; width:100px; border:solid 2px #066a75; margin:12%" src = <?php echo $path; ?> />
      			</div>
      			<div style="height:50px;width:100%; text-align:center;">
				
         		
    		   </div>
  			</div>
  
			<div style='width:59%;height:160px;float:right; text-overflow:ellipsis '> 
     			<div>	 
					<h4> Name: Guest User</h4>
      				
					
					<h4> Birth Date: 01-01-1990
					</h4>
					
          			
				 </div>
  			</div>
   			<div style="height:50px;width:100%; text-align:center;">
          		
      		</div>
		</div>
	
		<!-------------------------------------------->