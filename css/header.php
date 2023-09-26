<?php 
    
    if (checkLogin()) {
        $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE id = ? AND status = 1', array($_SESSION['id']));
        $user_Info = $result->row_array();
        $result = $db->query('SELECT views.viewer, views.date, user.user_type, user.first_name, user.last_name, user.currently_working_for FROM ' . $dbPrefix . 'views as views LEFT JOIN ' . $dbPrefix . 'user as user ON views.viewer = user.id WHERE views.viewed = ' . $_SESSION['id'] . ' AND views.new = 1 AND views.viewer IS NOT NULL ORDER BY views.date DESC', array());
        $user_views = 0;
        if($result){
            $user_views = sizeof($result->result_array());
        }

    }
    
	if (isset($document['title'])) {
		$title = $document['title'] . ' | ' . SITE_TITLE;
	} else {
		$title = SITE_TITLE;
	}
    
    if (isset($_SESSION['flash'])) {
		$flashMessage = $_SESSION['flash'];
        unset($_SESSION['flash']);
	}
    
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <base href="<?php echo SITE_URL; ?>" />
        <link rel="icon" type="image/png" sizes="16x16" href="images/favi.png">
        <title><?php echo $title ?></title>
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" href="fonts/font.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="css/docs.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />

        <?php if (isset($document['style']) && is_array($document['style'])) { ?>
        <?php foreach ($document['style'] as $style) { ?>
        	<link rel="stylesheet" type="text/css" href="css/<?php echo $style; ?>" />
        <?php } } ?>
        
        <link rel="stylesheet" type="text/css" href="css/extra.css?update=dwadaw" />

        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/jquery.fancybox.js"></script>
        <script type="text/javascript" src="js/at-jquery.js" ></script>
        <script type="text/javascript" src="js/select.js" ></script>
        <script type="text/javascript" src="js/common.js" ></script>

        <?php if (isset($document['script']) && is_array($document['script'])) { ?>
        <?php foreach ($document['script'] as $script) { ?>
        	<script type="text/javascript" src="js/<?php echo $script; ?>" ></script>
        <?php } } ?>
        
        <script type="text/javascript">
            var base_url = '<?php echo SITE_URL; ?>';
        </script>

    </head>
    <body>
        
        <div class="p_header">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 p_hdr">
			    
                        <div class="mnv_menu_wrap">
                            <div class="mnv_menu_inn">
                                <a class="a_close" href="javascript:void(0)">
                                    <img src="images/close.png" alt=""/>Close
                                </a>
                                <a class="a_menu_lg" href="<?php echo getLink(); ?>" title="<?php echo SITE_TITLE; ?>"><img src="images/logo.png" alt="<?php echo SITE_TITLE; ?>"/></a>
                                <ul class="mnv_nav">
                                    <li>
										<a href="<?php echo getLink(); ?>">home</a><i class="fa fa-chevron-down"></i>
										<ul>
											<li><a href="<?php echo getLink('profile.php'); ?>">Profile</a></li>
											<?php if (!checkLogin()) { ?>
												<li><a href="<?php echo getLink('login.php'); ?>">Login</a></li>
												<li><a href="<?php echo getLink('register.php'); ?>">Register</a></li>
												 
											<?php } ?>
											<?php if (checkLogin()) { ?>
                                                <li><a href="<?php echo getLink('logout.php'); ?>">Logout</a></li>
											<?php } ?>
										</ul>
									</li>
                                    <li><a href="<?php echo getLink('players.php'); ?>">Players </a><i class="fa fa-chevron-down"></i>
										<ul>
											<li><a href="<?php echo getLink('test.php'); ?>">Tests</a></li>
										</ul>
									</li>
                                    <li><a href="<?php echo getLink('coaches_scouts.php'); ?>">Coaches and Scouts</a><i class="fa fa-chevron-down"></i>
										<ul>
                                            <li><a href="<?php echo getLink('compare.php'); ?>">Compare</a></li>
                                            <li><a href="<?php echo getLink('score_validation_request.php'); ?>">Score Validation Request</a></li>
										</ul>
									</li>
									</li>
                                    <li><a href="javascript:" class="fa">Settings </a><i class="fa fa-chevron-down"></i>
										<ul>
											<li><a href="<?php echo getLink('cms.php', 'cms_id=1'); ?>">About Us</a></li>
											<li><a href="<?php echo getLink('blog.php'); ?>">Blog</a></li>
											<li><a href="<?php echo getLink('change_password.php'); ?>">Change Password</a></li>
											<li><a href="<?php echo getLink('contact_us.php'); ?>">Contact Us</a></li>
											<li><a href="<?php echo getLink('plan.php'); ?>">Pricing and subscription</a></li>
											<li><a href="<?php echo getLink('cms.php', 'cms_id=2'); ?>">Terms & Conditions</a></li>
											
										</ul>
									</li>                                    
                                   
                                    
                                </ul>
                            </div>
                        </div>
                        
                        <div class="p_hdr_lft">
                            <div class="p_menu">
                                <a href="javascript:void(0)">
                                    <div class="p_burger">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                            </div>
                            
                            <div style="display:none;">
                            <?php if (checkLogin()) { ?>
                            <select name="is-player" class="p_ply is-player my-dropdown1">
                                <option value="<?php echo getLink('profile.php'); ?>">View Profile</option>
                                <?php /*
                                <option value="<?php echo getLink('change_password.php'); ?>">Change Password</option>
                                <option value="<?php echo getLink('logout.php'); ?>">Logout</option>
                                */ ?>
                            </select>
                            <?php } else { ?>
                            <select name="is-player" class="p_ply is-player my-dropdown1">
                                <option value="<?php echo getLink('register.php'); ?>">Register</option>
                                <option value="<?php echo getLink('login.php'); ?>">Login</option>
                                <?php /*<option value="<?php echo getLink('register.php'); ?>">Create an Account</option> */ ?>
                            </select>
                            <?php } ?>
                            </div>
                            <ul class="hdrr_links" style="display:none;">
		                       <li><a href="<?php echo getLink('players.php'); ?>">Players</a></li>
		                       <li><a href="<?php echo getLink('test.php'); ?>">Tests</a></li>
		                       <li><a href="<?php echo getLink('compare.php'); ?>">Compare</a></li>
                               <li><a href="<?php echo getLink('profile.php'); ?>">Profile</a></li>
		                    </ul>
                        </div>
                        
                        <div class="p_logo">
                            <a href="<?php echo getLink(); ?>" title="<?php echo SITE_TITLE; ?>"><img src="images/logo.png" alt="<?php echo SITE_TITLE; ?>"/> </a>
                        </div>
                        
                                <?php if (checkLogin()) { ?>
                                <a class="logbtn" href="<?php echo getLink('logout.php'); ?>">
                            <i class="glyphicon glyphicon-off"></i>
                                    Logout</a>
                                    <?php if($user_views > 0 && $_SESSION['user_type'] == 1) { ?>
                                    <a class="alertbtn" href="<?php echo getLink('profile.php', 'tab=notifications'); ?>">
                                    <?= $user_views ?>
                            <i class="glyphicon glyphicon-bell"></i></a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <a class="logbtn" href="<?php echo getLink('login.php'); ?>">
                            <i class="glyphicon glyphicon-user"></i>Login / Register</a>
                                <?php } ?>
                            
                        <div class="p_hdr_rgt">
                            <form action="<?php echo getLink('players.php'); ?>" method="" >
                                <input type="text" type="search" class="txt_hdr" placeholder="Search" name="search" value="<?php echo (isset($_GET['search'])) ? $_GET['search'] : '';  ?>" />
                                <input type="submit" class="btn_hdr" value="go" />
                            </form>
                        </div>
                        
                            
                    </div>
                </div>
            </div>
        </div>
        
        <?php include('common/breadcrumb.php'); ?>