<?php defined("ACCESS") or die("Access Restricted");?>
<script type="text/javascript">
(function() {
var s=document.createElement('script');s.type='text/javascript';s.async = true;
s.src='<?php echo  ROOTURL . "/js/share_addon.js" ?>';
var j =document.getElementsByTagName('script')[0];j.parentNode.insertBefore(s,j);
})();
</script>

<div id="left_sec">
				<ul>
					<a href="<?php print CreateURL('index.php','mod=dashboard&do=showinfo&master_nav=1'); ?>"><li id="li_1"><div class="li_text1">Dashboard</div><div class="li1"></div></li></a>
<!--					<a href="--><?php //print CreateURL('index.php','mod=user&do=course');?><!--"><li id="li_2"><div class="li_text1">Online&nbsp;Exam</div><div class="li2"></div></li></a>-->
					<a href="<?php print CreateURL('index.php','mod=user&do=package_list');?>"><li id="li_2"><div class="li_text1">Online&nbsp;Exam</div><div class="li2"></div></li></a>
					<a href="<?php print CreateURL('index.php','mod=account&do=myaccount');?>"><li id="li_3"><div class="li_text1">My&nbsp;Account</div><div class="li3"></div></li></a>
					<!--<a href="<?php //print CreateURL('index.php','mod=uploaddocs');?>"><li id="li_4"><div class="li_text1">Upload&nbsp;Documents</div><div class="li4"></div></li></a>-->
					<a href="<?php print CreateURL('index.php','mod=report&do=result_sheet&master_nav=1');?>"><li id="li_5"><div class="li_text1">Exam&nbsp;Reports</div><div class="li5"></div></li></a>
					<!--<a href="<?php //print CreateURL('index.php','mod=buyonline');?>"><li id="li_6"><div class="li_text1">Buy&nbsp;Package&nbsp;Online</div><div class="li6"></div></li></a>-->
					<!--<a href="<?php //print CreateURL('index.php','mod=message');?>"><li id="li_7"><div class="li_text1">Message</div><div class="li7"></div></li></a>-->
					<a href="<?php print CreateURL('index.php','mod=report&do=result_sheet?share');?>" title="Share Button" onclick="return sa_tellafriend('www.assessall.com')"><li id="li_8"><div class="li_text1">Share&nbsp;With&nbsp;Friends</div><div class="li8"></div></li></a>
					<!--<a href="<?php //print CreateURL('index.php','mod=latest_news');?>"><li id="li_9"><div class="li_text1">Latest&nbsp;News</div><div class="li9"></div></li></a>-->
					<!--<a href="<?php //print CreateURL('index.php','mod=download_docs');?>"><li id="li_10"><div class="li_text1">Download&nbsp;Docs</div><div class="li10"></div></li></a>-->
					<a href="<?php print CreateURL('index.php','mod=help');?>"><li id="li_11"><div class="li_text1">Help</div><div class="li11"></div></li></a>
					<a href="<?php print CreateURL('index.php','mod=buy_package&do=buy');?>"><li id="li_16"><div class="li_text1">Buy Package</div><div class="li6"></div></li></a>
<!--					<a href="--><?php //print CreateURL('index.php','mod=payment');?><!--"><li id="li_16"><div class="li_text1">Payment</div><div class="li13"></div></li></a>-->


				<ul>
			</div>
			