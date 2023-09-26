<?php include('config.php');

$page_title = 'Permission';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('permission.php')));
include('common/header.php');
?>

<div class="p_profile_bnr p_profile_bnr_hdr">
    <div class="container">
        <div class="row">
            <h1><?php echo $page_title; ?></h1>
        </div>
    </div>
</div>
<div class="p_profile_dtl p_aero_test_dtl p_cms_dtl">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 ply_rv ply_tav">
                <div class="ply_rv_dv">
                    <div class="ply_rv_dv_rgt text-center">
                        <p>&nbsp;</p>
                        <h3 class="text-danger">Sorry you don't have permission to view this page!</h3>
                        <p>&nbsp;</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('common/footer.php');?>