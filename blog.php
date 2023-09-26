<?php include('config.php');

$limit = 9;
if (isset($_GET['page_id'])) {
    $page_id = (int)$_GET['page_id'];
} else {
    $page_id = 1;
}

$start = ($page_id - 1) * $limit;

$sql = 'SELECT * FROM ' . $dbPrefix . 'blog_post WHERE status = 1';
$blogs = $db->query($sql, array());
$total = $blogs->num_rows();

if(isset($_REQUEST['title']) && $_REQUEST['title'] !='')
{
    $title = $_REQUEST['title'];
    $sql .= ' and title LIKE "%'.$title .'%"';
}    

$sql .= ' ORDER BY id DESC LIMIT ' . $start . ', ' . $limit;
//echo $sql; exit;
// $blogs1=$blogs->result_array();
// var_dump($blogs1);
// $blogs = $db->query($sql, array());
// echo $total;
if ($blogs->num_rows() == 0 && $page_id != 1) {
    
    redirect(getLink('blog.php'));
}

$pagination = pagination($total, $limit, $page_id, getLink('blog.php'));

$page_title = "Blog";
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('blog.php')));
include('common/header.php');

?>

<div class="p_profile_bnr p_profile_bnr_hdr">
    <div class="container">
        <div class="row">
            <h1>Blog</h1>
        </div>
    </div>
</div>

<?php

    if(isset($_REQUEST['title']) && $_REQUEST['title'] !='') {
        echo "here";
    $title = $_REQUEST['title'];    
?>
    <div class="srch_wrd_cls">
        <h3>Search Keyword : <span><?php echo $title; ?><span></h3>
    </div>
<?php } ?>

<?php 
// var_dump($blogs);

if ($blogs->num_rows() > 0) {
echo "here";
?>
<div class="p_blog">
    <div class="container">
        <div class="row">
            <div class="blog_dv">
                <ul>
                    <?php foreach ($blogs->result_array() as $key => $blog) { ?>
                    <li class="col-xs-12 col-sm-4">
                        <div class="blog_img">
                            <a href="<?php echo getLink('blog_detail.php', 'blog_id='.$blog['id']); ?>" title="<?php echo $blog['title']; ?>"><img src="<?php echo BLOG_URL . $blog['image']; ?>" alt=""/></a>
                        </div>
                        <div class="blog_con">
                            <div class="blog_date">
                                <?php echo formatDate($blog['date_added'], BLOG_DATE_FORMAT); ?> <span><?php echo $blog['author']; ?></span>
                            </div>
                            <h3><a href="<?php echo getLink('blog_detail.php', 'blog_id='.$blog['id']); ?>" title="<?php echo $blog['title']; ?>"><?php echo $blog['title']; ?></a></h3>
                            <p><?php echo shortContent($blog['description'], 200); ?></p>
                            <a class="a_arr" href="<?php echo getLink('blog_detail.php', 'blog_id='.$blog['id']); ?>" title="<?php echo $blog['title']; ?>"><img src="images/arrow2.png" alt=""/></a>
                        </div>
                    </li>
                    <?php if ($key%3 == 2) { ?>
                        <li class="clearfix visible-sm visible-md visible-lg" ></li>
                    <?php } ?>
                    <?php } ?>
                </ul>
                
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>
</div>
<?php } else { ?>
    <div class="no_blg">
        <p>No blog found.</p>
    </div>    
<?php } ?>

<?php include('common/footer.php');?>