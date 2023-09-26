<?php include('config.php');

if (isset($_GET['contributor_id'])) {
    $contributor_id = $_GET['contributor_id'];
} else {
    $contributor_id = 0;
}

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'contributor WHERE id = ? AND status = 1', array($contributor_id));

if ($result->num_rows() == 0) {
    redirect(getLink());
}

$contributor = $result->row_array();

$page_title = $contributor['name'];
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('contributor_detail.php', 'contributor_id=' . $contributor['id'])));
include('common/header.php');
?>

<div class="p_profile_bnr p_profile_bnr_hdr">
    <div class="container">
        <div class="row">
            <h1>The creators & contributors</h1>
        </div>
    </div>
</div>

<div class="p_tcc detail_con">
    <div class="container">
        <div class="row">
            <div class="tcc_con">
                <ul class="slideshow">
                    <li>
                        <div class="col-xs-12 col-md-6 tcc_inn">
                            <h3><?php echo $contributor['name']; ?></h3>
                            <p><?php echo $contributor['description']; ?></p>
                        </div>
                        <div class="col-xs-12 col-md-6 tcc_img">
                            <img src="<?php echo CONTRIBUTOR_URL . $contributor['image']; ?>" alt=""/>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="col-xs-12 ply_rv ply_tav blog-content-custm">
    <ul>
        <li class="btn_li">
        <a href="<?php echo getLink(); ?>"><button name="back" class="btn_lg">Back</button></a>
        </li>
    </ul>
</div>
        
    


<?php include('common/footer.php');?>