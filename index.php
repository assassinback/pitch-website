<?php include('config.php');

$document['style'][] = 'owl.carousel.css';
$document['style'][] = 'owl.theme.css';
$document['style'][] = 'validationEngine.jquery.css';

$document['script'][] = 'bxslider.js';
$document['script'][] = 'owl.carousel.js';
$document['script'][] = 'jquery.validationEngine.js';
$document['script'][] = 'jquery.validationEngine-en.js';

include('common/header.php');

$blogs = $db->query('SELECT * FROM ' . $dbPrefix . 'blog_post WHERE status = 1 ORDER BY id DESC LIMIT 3', array());

$contributors = $db->query('SELECT * FROM ' . $dbPrefix . 'contributor WHERE status = 1 ORDER BY id DESC LIMIT 3', array());

$users = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE user_type = 1 AND status = 1 ORDER BY RAND() LIMIT 8', array());

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'cms_pages WHERE id = ? AND status = 1', array(3));
$cmsInfo = $result->row_array();

if($cmsInfo['image']!=''){
	$src = CMS_URL . $cmsInfo['image'];  
}

?>    

<div class="p_banner" style="background-image: url(images/bnr.jpg);">
    <div class="container">
        <div class="row">
            <div class="p_bn_con">
                <h5>Pitch your talent to football</h5>
                <h2 class="p_hd_1">scouts</h2>
                <h2 class="p_hd_2">coaches</h2>
                <h2 class="p_hd_3">managers</h2>
                <h4>in the UK</h4>
            </div>
            <div class="col-xs-12 col-md-7 pull-right p_bn_vd">
                <div class="p_bn_vd_inn">
                    <a href="#bnr_vd" class="a_vd">
                        <!-- <img class="img_vd" src="images/vd.jpg" alt=""/> -->
                        <img class="img_vd" src="<?php echo $src;?>" alt=""/>
                        <?php if ($videoId = getVideoId($cmsInfo['video'])) { ?>
                        <div class="p_vd_con">
                            <h2>How it w<img src="images/play.png" alt=""/>rks</h2>
                            <h3>Show your talent</h3>
                        </div>
                        <?php } ?>
                    </a>
                </div>
                <?php if ($videoId) { ?>
                <div style="display: none;">
                    <div class="p_cd_pop" id="bnr_vd">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $videoId; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="p_ribbon"><img src="images/ribbon.png" alt=""/></div>
        </div>
    </div>
</div>
<div class="p_hm_blk1">
    <div class="container">
        <div class="row">
            <div class="p_player">
                <img src="images/player.png" alt=""/>
            </div>
            <div class="col-xs-12 col-md-7 pull-right p_sys">
                <hr>
                <?php echo $cmsInfo['description']; ?>
            </div>
        </div>
    </div>
</div>
<div class="p_brp">
    <div class="container">
        <div class="row">
            <div class="brp_lft">
                <h2><span>BEST</span> Ranked Players</h2>
                <a class="a_vp" href="<?php echo getLink('players.php'); ?>">View all the players</a>
            </div>
            <div class="col-xs-12 col-md-6 pull-right brp_rgt">
                <div class="brp_ply">
                    <img src="images/ply1.jpg" alt=""/>
                    <h3>John<span>Brown</span></h3>
                </div>
                <div class="brp_ply brp_ply_bg">
                    <img src="images/ply2.jpg" alt=""/>
                    <h3>David<span>Smith</span></h3>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($users->num_rows() > 0) { ?>
<div class="p_prf">
    <div class="container">
        <div class="row">
            <h2>Players/profiles</h2>
            <div class="p_carousel">
                <div id="owl-demo" class="owl-carousel owl-theme">
                    <?php  foreach ($users->result_array() as $user) { ?>
                        <div class="item">
                            <a href="<?php echo getLink('profile.php', 'profile_id=' . $user['id']); ?>">
                                <div class="p_car_plyr" style="background-image: url(<?= playerImageCheck($user['photo']) ?>);">
                                    <?php 
                                    //echo getUserProfileImage($user['photo']); 
                                    ?>
                                    <div class="pcp_con">
                                        <div class="pcp_con_inn">
                                            <h3><?php echo $user['first_name']; ?><span><?php echo $user['last_name']; ?></span></h3>
                                            <?php if(isset($user['team_id'])) { ?>
                                            <h4><?php echo getClub($user['team_id']); ?></h4>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </a>    
                        </div>
                    <?php }  ?>
                    
                </div>
                <div class="customNavigation">
                    <a class="btn prev">Previous</a>
                    <a class="btn next">Next</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="p_test">
    <div class="container">
        <div class="row">
            <div class="p_test_ball">
                <img src="images/ball.png" alt=""/>
            </div>
            <div class="p_test_lft">
                <h2><span>the</span> tests</h2>
                <a class="a_vp" href="<?php echo getLink('test.php'); ?>">Learn more about tests</a>
            </div>
            <div class="p_test_rgt">
                <ul>
                    <li>
                        <img src="images/test1.png" alt=""/>
                        <h3>Jump test</h3>
                    </li>
                    <li>
                        <img src="images/test2.png" alt=""/>
                        <h3>Aerobic test</h3>
                    </li>
                    <li>
                        <img src="images/test3.png" alt=""/>
                        <h3>Sprint test</h3>
                    </li>
                    <li>
                        <img src="images/test4.png" alt=""/>
                        <h3>Psychology test</h3>
                    </li>
                    <li>
                        <img src="images/test5.png" alt=""/>
                        <h3>Agility Test</h3>
                    </li>
                    <li>
                        <img src="images/test6.png" alt=""/>
                        <h3>Balance Test</h3>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php if ($contributors->num_rows() > 0) { ?>
<div class="p_tcc">
    <div class="container">
        <div class="row">
            <h2>The creators<br/>& contributors</h2>
            <div class="tcc_con">
                <ul class="slideshow">
                    <?php foreach ($contributors->result_array() as $contributor) { ?>
                    <li>
                        <div class="col-xs-12 col-md-6 tcc_inn">
                            <h3><?php echo $contributor['name']; ?></h3>
                            <p><?php echo shortContent($contributor['description'], 250); ?></p>
                            <a class="a_rm" href="<?php echo getLink('contributor_detail.php', 'contributor_id='.$contributor['id']); ?>">Read more...</a>
                        </div>
                        <div class="col-xs-12 col-md-6 tcc_img">
                            <img src="<?php echo CONTRIBUTOR_URL . $contributor['image']; ?>" alt=""/>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if ($blogs->num_rows() > 0) { ?>
<div class="p_blog">
    <div class="container">
        <div class="row">
            <h2>Blog</h2>
            <div class="blog_dv">
                <ul>
                    <?php foreach ($blogs->result_array() as $blog) { ?>
                    <li class="col-xs-12 col-sm-4">
                        <div class="blog_img">
                            <img src="<?php echo BLOG_URL . $blog['image']; ?>" alt=""/>
                        </div>
                        <div class="blog_con">
                            <div class="blog_date">
                                <?php echo formatDate($blog['date_added'], BLOG_DATE_FORMAT); ?> <span><?php echo $blog['author']; ?></span>
                            </div>
                            <h3><a href="<?php echo getLink('blog_detail.php', 'blog_id='.$blog['id']); ?>"><?php echo $blog['title']; ?></a></h3>
                            <p><?php echo shortContent($blog['description'], 200); ?></p>
                            <a class="a_arr" href="<?php echo getLink('blog_detail.php', 'blog_id='.$blog['id']); ?>"><img src="images/arrow2.png" alt=""/></a>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="p_fs">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8 fs_lft">
                <div class="fs_lft_inn">
                    <h3>Are you a</h3>
                    <h2>football Player or scout?</h2>
                    <div class="fs_form">
                        <form role="form" class="validateForm" name="Admin" action="register.php" method="post" enctype="multipart/form-data">
                            <input type="text" name="email" id="email" value="" class="txt_fs" placeholder="Enter your email address" data-validation-engine="validate[required,custom[email]]" data-errormessage-value-missing="The e-mail address you entered appears to be incorrect." maxlength="70" data-errormessage-custom-error="Example: test@gmail.com" >
                            <input type="submit" name="next" class="btn_fs" value="Next" />
                        </form>    
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 fs_rgt">
                <a class="twitter-timeline" data-height="300" data-theme="light" data-link-color="#19CF86" href="https://twitter.com/RateRmt?ref_src=twsrc%5Etfw">Tweets by RateRmt</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
  
    $('.a_vd').fancybox();
    
    var slider = $('.slideshow').bxSlider({
                    auto: false,
                    mode:'fade',
                    controls: false,
                    pager: true
                });        
 
    var owl = $("#owl-demo");
 
    owl.owlCarousel({
      items : 4, 
      itemsDesktop : [1000,4], 
      itemsDesktopSmall : [900,3],
      itemsTablet: [600,2], 
      itemsMobile : [500,1],
      pagination:false
    });

    $(".next").click(function(){
        owl.trigger('owl.next');
    })
    $(".prev").click(function(){
        owl.trigger('owl.prev');
    })
}); 

$(document).ready(function(){
	$(".validateForm").validationEngine({promptPosition : "inline", scroll: true});
});
    
</script>

<?php include('common/footer.php');?>