<?php include('config.php');

$page_title = 'Thank You';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('thank_you.php')));
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
                    <div class="ply_rv_dv_rgt">
                        <h1><?php echo $page_title; ?></h1>
                        <p>Your account has been registered successfully. Please <a class="a_fp" href="<?php echo getLink('login.php'); ?>" >click here</a> to login into your account.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('common/footer.php');?>