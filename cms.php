<?php include('config.php');

$cms_id = isset($_GET['cms_id']) ? (int)$_GET['cms_id'] : 0;
$result = $db->query('SELECT * FROM ' . $dbPrefix . 'cms_pages WHERE id = ? AND status = 1', array($cms_id));

if ($result->num_rows() > 0) {
    $cmsInfo = $result->row_array();
} else {
    redirect(getLink());
}

$page_title = $cmsInfo['title'];
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('cms.php', 'cms_id='.$cms_id)));
include('common/header.php');
?>

<div class="p_profile_bnr p_profile_bnr_hdr">
    <div class="container">
        <div class="row">
            <h1><?php echo $cmsInfo['title']; ?></h1>
        </div>
    </div>
</div>
<div class="p_profile_dtl p_aero_test_dtl p_cms_dtl">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 ply_rv ply_tav">
                <div class="ply_rv_dv">
                    <div class="ply_rv_dv_rgt">
                        <h1><?php echo $cmsInfo['title']; ?></h1>
                        <?php echo $cmsInfo['description']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('common/footer.php');?>