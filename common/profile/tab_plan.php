<?php

if (!$allow_edit) {
    redirect(getLink('profile.php'));
}

$plan = $db->query('SELECT test_plan.*, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan INNER JOIN ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_id = ?', array($profile_id));
$planInfo = $plan->row_array();

$amenities = $db->query('SELECT user_amenities.*, amenities.title, amenities.type, amenities.user_info FROM ' . $dbPrefix . 'user_amenities as user_amenities INNER JOIN ' . $dbPrefix . 'amenities as amenities ON (amenities.id = user_amenities.amenity_id AND amenities.status = 1) WHERE user_amenities.availability = 1 AND  user_amenities.user_id = ? ORDER BY user_amenities.id ASC', array($profile_id));
$amenities = $amenities->result_array();

if ($planInfo['next_payment_date'] != "") {
    $renew = true;
} else {
    $renew = false;
}
?>

<div class="col-xs-12 col-sm-9">
    
    <div class="row">
	<div class="col-xs-12">
	<div class="well sub_sec">
        <div class="col-xs-12 col-sm-4">
            <h3><?php echo strtoupper($planInfo['title']); ?></h3>
            <?php if ($renew) { ?>
                <p class="renew">Your subscription will be renewed on <strong><?php echo formatDate($planInfo['next_payment_date']); ?></strong></p>
            <?php } ?>
        </div>
        <div class="col-xs-12 col-sm-8 text-right">
            <?php if ($renew) { ?>
                <a href="javascript:" ><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#cancelPlanModal">Cancel plan</button></a>&nbsp;
            <?php } ?>
            <a href="<?php echo getLink('plan.php'); ?>" ><button type="button" class="btn btn-primary" >Change plan</button></a>&nbsp;
            <a href="<?php echo getLink('plan.php', 'category=validation-session-plan'); ?>" ><button type="button" class="btn btn-primary" >Buy validation session plan</button></a>
        </div>
    </div>
    </div>
    </div>
    
    <div class="row">
        
        <?php foreach ($amenities as $key => $amenity) { ?>
            <div class="col-xs-12 col-sm-6 col-md-6 cusm_panel">
                <div class="panel panel-default">
                    <div class="panel-heading text-center"><b><?php echo $amenity['title']; ?></b></div>
                    <div class="panel-body text-center">
                        <?php 
                        $download_text = 'Download Receipt';
                        if ($amenity['type'] == 'sport_science_validation'){
                            $status = ($amenity['status'] == 1) ? 'Used' : 'Unused';
                        } else if ($amenity['type'] == 'potential_trial'){
                            $status = ($amenity['status'] == 1) ? 'Invited' : 'Unused';
                        } else if ($amenity['type'] == 'trial_session'){
                            $status = ($amenity['status'] == 1) ? 'Booked' : 'Select Date';
                        } else {
                            $status = ($amenity['status'] == 1) ? 'Sent' : 'Pending';
                            $download_text = 'Download Plan';
                        }
                        $amenityData = unserialize($amenity['data']);
                        ?>
                        
                        <label>Status : </label>
                        <span>
                            <?php if ($amenity['type'] == 'trial_session'){ ?>
                                <?php if ($amenity['status'] == 0){ ?>
                                    <a class="a_sm" href="javascript:" class="book-trial-session" data-toggle="modal" data-target="#trialSessionModal"><?php echo $status; ?></a>
                                    <?php include('common/profile/trial_session.php'); ?>
                                <?php } else { ?>
                                    <?php echo $status . ' - ' . formatDate($amenityData['trial_session_date']); ?>
                                <?php } ?>
                            <?php } else { ?>
                                 <?php echo $status; ?>
                            <?php } ?>
                        </span>
                        <br>
                        <?php if ($amenity['user_info'] != "") { ?>
                            <small><i><?php echo $amenity['user_info']; ?></i></small>
                            <br>
                        <?php } ?>
                        <br>
                        <?php /* if (isset($amenityData['file']) && file_exists(USER_PATH . $amenityData['file'])) { ?>
                            <a href="<?php echo USER_URL . $amenityData['file']; ?>" target="_blank"><?php echo $download_text; ?></a>
                        <?php }  */?>
                    </div>
                </div>
            </div>
            
            <?php if ($key%2 == 1) { ?>
                <div class="clearfix visible-sm visible-md visible-lg" ></div>
            <?php } ?>
        <?php } ?>
        
    </div>
    
    
</div>

<?php if ($renew) { ?>

    <div id="cancelPlanModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="<?php echo getLink('phpajax/purchase_plan.php', '', true); ?>" name="form-cancel-plan" id="form-cancel-plan" method="POST">
                    <input type="hidden" name="cancel" value="1" >
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title">Cancel Plan</h2>
                    </div>
                    <div class="modal-body">
                        <h3>Are you sure want to cancel your plan?</h3>
                        <br>
                        <br>
                        <div class="ajax-loading" >
                            <h3>Loading...</h3>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger" >Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

<?php } ?>
