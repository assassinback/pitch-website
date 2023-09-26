<?php include('config.php');

if (isset($_GET['blog_id'])) {
    $blog_id = $_GET['blog_id'];
} else {
    $blog_id = 0;
}

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'blog_post WHERE id = ? AND status = 1', array($blog_id));

if ($result->num_rows() == 0) {
    redirect(getLink('blog.php'));
}

$blog = $result->row_array();

$sql = 'SELECT * FROM ' . $dbPrefix . 'blog_post WHERE status = 1 ORDER BY id DESC LIMIT 5';
$blogs = $db->query($sql);

//$currentURL = 'http://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$currentURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$document['script'][] = 'jquery.shares.js';

$page_title = $blog['title'];
$document['title'] = $page_title;
$breadcrumb = array(array('title' => 'Blog', 'link' => getLink('blog.php')), array('title' => $page_title, 'link' => getLink('blog_detail.php', 'blog_id=' . $blog['id'])));
include('common/header.php');
?>

<div class="p_profile_bnr p_profile_bnr_hdr">
    <div class="container">
        <div class="row">
            <h1>Blog</h1>
        </div>
    </div>
</div>
<div class="p_profile_dtl p_aero_test_dtl p_cms_dtl">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-9 ply_rv ply_tav blog-content-custm">
                <div class="ply_rv_dv">
                    <div class="blog_img">
                        <img src="<?php echo BLOG_URL . $blog['image']; ?>" alt=""/>
                    </div>
                    <div class="ply_rv_dv_rgt">
                        <h1><?php echo $blog['title']; ?></h1>
                        <div class="blog_date">
                            <?php echo formatDate($blog['date_added'], BLOG_DATE_FORMAT); ?> <span><?php echo $blog['author']; ?></span>
                        </div>
                        <?php echo $blog['description']; ?>
                    </div>
                </div>
                <ul>
                    <li class="btn_li">
                    <a href="<?php echo getLink('blog.php'); ?>"><button name="back" class="btn_lg">Back</button></a>
                    </li>
                </ul>
                
                <div class="p_share_blg">Share
                    <a href="<?php echo $currentURL; ?>" class="share facebook"><i class="fa fa-facebook"></i></a>
                    <a href="<?php echo $currentURL; ?>" data-text="" class="share twitter"><i class="fa fa-twitter"></i></a>
                    <!-- <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>  -->
                    <!-- <a href="#"><i class="fa fa-instagram"></i></a> -->
                </div>
                
            </div>
            <div class="col-xs-12 col-sm-3 blg_dtl_sidebar">
            	<div class="ply_tav_rgt_inn">
                    <h2>Search</h2>
                    <hr>
                    <div class="aero_form">
                        <form name="" method="get" action="<?php echo getLink('blog.php'); ?>">
                            <input name="title" class="txt_aero" type="text">
                            <input value="submit" class="btn_aero" type="submit">
                        </form>    
                    </div>
				</div>
                <?php if ($blogs->num_rows() > 0) { ?>
                    <div class="ply_tav_rgt_inn">
                        <h2>Recent <span>Posts</span></h2>
                        <hr>
                        <ul>
                            <?php foreach ($blogs->result_array() as $row) { ?>
                                <li><a href="<?php echo getLink('blog_detail.php', 'blog_id='.$row['id']); ?>"><?php echo $row['title']; ?></a></li>
                            <?php } ?>   
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Script For Social Share -->
<script>
    $(document).ready(function(){
        $('a.share').shares();
    });
</script>

<?php include('common/footer.php');?>