<?php
    $social_links = $db->query('SELECT * FROM ' . $dbPrefix . 'social_link WHERE status = 1 ORDER BY sort_order ASC', array());
    $social_links = $social_links->result_array();
?>
		<div class="p_footer">
		   
		    <div class="ftr_logo">
		        <a href="<?php echo getLink(); ?>" title="<?php echo SITE_TITLE; ?>"><img src="images/ftr-logo.png" alt=""/></a>
		    </div>
		    <ul class="p_social">
                <?php foreach ($social_links as $social_link) { ?>
                    <li><a href="<?php echo $social_link['link']; ?>" title="<?php echo $social_link['name']; ?>" target="_blank"><i class="fa <?php echo $social_link['class']; ?>" aria-hidden="true"></i></a></li>
                <?php } ?>
		    </ul>
		    <ul class="p_social p_lgg">
                    <li><img src="images/european_union.png" alt=""/></li>
                    <li><img src="images/northamptonshire.png" alt=""/></li>
		    </ul>
		    <ul class="ftr_links">
		        <li><a href="<?php echo getLink('players.php'); ?>">Players</a></li>
		        <li><a href="<?php echo getLink('test.php'); ?>">Tests</a></li>
		        <li><a href="<?php echo getLink('compare.php'); ?>">Compare</a></li>
                <?php if (!checkLogin()) { ?>
		        <li><a href="<?php echo getLink('login.php'); ?>">Login</a></li>
		        <li><a href="<?php echo getLink('register.php'); ?>">Register</a></li>
                <?php } else { ?>
		        <li><a href="<?php echo getLink('logout.php'); ?>">Logout</a></li>
                <?php } ?>
		        <li><a href="<?php echo getLink('cms.php', 'cms_id=2'); ?>">Terms & Conditions</a></li>
		    </ul>
		    <p><?php echo sprintf(COPYRIGHT_TEXT, date('Y')); ?> | <a href="http://www.mgtdesign.co.uk" target="_blank">Web Development</a> By <a href="http://www.mgtdesign.co.uk" target="_blank">MGT Design</a></p>
		</div>
        
        <div id="alert-notification" class="alert" style="display: none;">
            <span class="alert-msg"></span>
        </div>
        
        <?php if (isset($flashMessage)) { ?>
        <div id="alert-flash" class="alert alert-success fade in alert-dismissible" >
            <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            <strong>Success!</strong> <?php echo $flashMessage; ?>
        </div>
        <?php } ?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-166788948-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-166788948-1');
</script>

	</body>
</html>