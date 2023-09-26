<?php

$sendRequest = false;
    if (($allow_edit == false) && isset($_SESSION['id']) && isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 1)) {
        $result = $db->query('SELECT * FROM ' . $dbPrefix . 'score_validation WHERE status = 0 AND player_id = ? AND coach_id = ?', array($_SESSION['id'], $profile_id));
        if ($result->num_rows() == 0) {
            $sendRequest = true;
        }
    }
if(isset($_SESSION['id'])) {
    $loginuser_id = $_SESSION['id'];
} else {
    $loginuser_id = 0;
}
$getresult = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($loginuser_id));
if ($getresult->num_rows() != 0) {
	$loginuserInfo = $getresult->row_array();
	$loginuser_type = $loginuserInfo['user_type'];    
}else{
	$loginuser_type = 0;
}
?>
<div class="p_profile_bnr">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-3 p_profile_img">
            <div class="p_profile_img_inn" style="background-image: url(<?= playerImageCheck($userInfo['photo']) ?>);">
                <?php 
                // echo getUserProfileImage($userInfo['photo'], 'scout');
                 ?>
                <?php if($allow_edit) { ?>
                    <a class="sct-edit" href="<?php echo getLink('editprofilescout.php', 'type=image_info'); ?>"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                <?php } ?>
				</div>
			<div class="del_acc">
				<?php include('common/profile/delete_account.php'); ?>
			</div>
            </div>
            <div class="col-xs-12 col-sm-8 p_profile_con">
                <div class="p_profile_con_inn">
                    <h3><?php echo $userInfo['first_name']; ?><br/><span><?php echo $userInfo['last_name']; ?></span></h3>
                    
                    <?php if(isset($userInfo['currently_working_for'])) { ?>
					<?php $currently_working_for = trim($userInfo['currently_working_for']); ?>
                        <h4>Scout <?php if($currently_working_for != "")  { ?> (<?php echo $userInfo['currently_working_for']; ?>)<?php } ?></h4>
                    <?php } ?>
                </div>
                <?php 
					$result = $db->query('SELECT test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan LEFT JOIN  ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_test_plan.user_id = ?', array($loginuser_id));
					$userPlanToSendMsgInfo = $result->row_array();
					if($userPlanToSendMsgInfo['id'] != 1) { ?>
                <?php include('common/profile/send_message.php'); ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="p_profile_dtl">
    <div class="container">
        <div class="row">
        <div class="col-xs-12 col-sm-3 player_info">
                <?php if($sendRequest) { ?>
                    <form action="<?php echo getLink('phpajax/send_validate_request.php', '', true); ?>" name="form-validate-request" id="form-validate-request" method="POST" data-type="single">
                        <p>&nbsp;</p>
                        <input type="hidden" name="coach[]" value="<?php echo $profile_id; ?>">
                        <button type="submit" class="btn btn-success">Send A Score Validation Request</button>
                    </form>
                <?php } ?>
            </div>


            <div class="col-xs-12 col-sm-9 ply_abt">
            <?php 
            
            if($userInfo['photo'] == '' ||
            $userInfo['about_me'] == '' ||
            $userInfo['previously_worked_for'] == '' ||
            $userInfo['currently_working_for'] == '' ||
            $userInfo['years_of_experience'] == '' ||
            $userInfo['coaching_qualification'] == '' ||
            $userInfo['highest_education_level'] == '' ||
            $userInfo['coach_values_and_coaching_philosophy'] == '' ||
            $userInfo['recent_success'] == ''){

        ?> 
                <div class="col-xs-12 col-sm-9 alert alert-warning m-2 todoList" role="alert">
                    <h3>For your profile to become active please complete the following to do list:</h3>
                    <ul>
                    <?php if($userInfo['photo'] == '' || $userInfo['currently_working_for'] == '') {?><li> - Upload a picture of yourself and state what club you work for</li> <?php } ?>
                    <?php if($userInfo['previously_worked_for'] == '' ||
                            $userInfo['years_of_experience'] == '' ||
                            $userInfo['coaching_qualification'] == '' ||
                            $userInfo['highest_education_level'] == '') {?><li> - Complete coach info section to highlight your achievements </li> <?php } ?>
                    <?php if($userInfo['about_me'] == '' || $userInfo['coach_values_and_coaching_philosophy'] == '' || $userInfo['recent_success'] == '') {?><li> - Complete your 'About me' ‘Core Values’ and ‘Recent Successes’ section using the pencil icon </li> <?php } ?>
                    </ul> 
                </div>        
        <?php

    } else {
        ?> 
        <div class="col-xs-12 col-sm-9 alert alert-success m-2 todoList todoList-optional" role="alert">
            <h3>Your profile is now complete but to maximise your chances of finding a talented player for your club check out the player profiles <a href="<?php echo getLink('players.php'); ?>">here</a> and check out the player compare function <a href="<?php echo getLink('compare.php'); ?>">here</a></h3>
            <ul>
            <?php if($userInfo['testimonial_of_how_good_coach_i_am_video'] == '' ||
            $userInfo['testimonial_of_how_good_coach_i_am'] == '') {?><li>Complete the testimonial section where you can get a quote from another industry expert to validated who you are and your skills, abilities and professionalism <a href="<?php echo getLink('editprofilecoach.php', 'type=testimonial_of_how_good_coach_i_am'); ?>">here</a> </li><?php } ?> 
            <li>To network with other scouts and coaches please check out the coaches and scout section found <a href="<?php echo getLink('coaches_scouts.php'); ?>">here</a> </li> 
            </ul> 
        </div>        
<?php
    }
            
            ?>
                <div class="ply_ip">
                    <div class="ply_ip_inn">
                        <h3>Personal<span>Info</span>
                        
                        <?php if($allow_edit) { ?>
                                <a href="<?php echo getLink('editprofilescout.php', 'type=personal_info'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                        <?php } ?>
                        
                        </h3>
                        <hr>
                        <ul>
                            <li>
                                <label>Date of Birth</label>
                                <span><?php echo ($userInfo['date_of_birth'] != "0000-00-00 00:00:00") ? formatDate($userInfo['date_of_birth']) : " - "; ?></span>
                            </li>
                            <li>
                                <label>Country</label>
                                <?php if(isset($userInfo['country_id'])) { ?>
                                <span><?php echo getCountry($userInfo['country_id']); ?></span>
                                <?php } ?>
                            </li>
                            <li>
                                <label>County</label>
                                <?php if(isset($userInfo['county_id'])) { ?>
                                <span><?php echo getCounty($userInfo['county_id']); ?></span>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>
                    <div class="ply_ip_inn ply_ip_inn_rgt">
                        <h3>Other<span>Info</span>
                        <?php if($allow_edit) { ?>
                                <a href="<?php echo getLink('editprofilescout.php', 'type=playing_position'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                        <?php } ?>
                        </h3>
                        <ul>
                            <li>
                                <label>Highest Education Level</label>
                                <span><?php echo $userInfo['highest_education_level']; ?></span>
                            </li>
                            <li>
                                <label>Coaching Qualification</label>
                                <span><?php echo $userInfo['coaching_qualification']; ?></span>
                            </li>	
                            <li>
                                <label>Years of Experience</label>
                                <span><?php echo $userInfo['years_of_experience']; ?></span>
                            </li>
                            <li>
                                <label>Currently working for</label>
                                <span><?php echo $userInfo['currently_working_for']; ?></span>
                            </li>
                            <li>
                                <label>Previously worked for</label>
                                <span><?php echo $userInfo['previously_worked_for']; ?></span>
                            </li>
                            <li>
                                <label>DBS Number</label>
                                <?php if ($userInfo['dbs_verified'] == 1) { ?>
                                    <span class="verified_g"><i class="fa fa-check-circle"></i> Verified</span> 
                                <?php } else { ?>
                                    <span>Pending</span> 
                                <?php } ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="ply_pos">
                    <div class="ply_pos_lft">
                    </div>
                    <div class="ply_pos_rgt">
                    
                        <h3>About Me
                        <?php if($allow_edit) { ?>
                                <a href="<?php echo getLink('editprofilescout.php', 'type=about_me'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                        <?php } ?>
                        </h3>
                        
                        <?php /* if($allow_edit) { ?>
                            <p><i>Please write a few words about yourself, your character and your experiences</i></p>
                        <?php } else { ?>
                            <p></p>
                        <?php } */ ?>
                        
                        <p><?php echo $userInfo['about_me']; ?></p>
                                                
                        <h3>Core values and coaching philosophy
                        <?php if($allow_edit) { ?>
                                <a href="<?php echo getLink('editprofilescout.php', 'type=coach_values_coaching_philosophy'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                        <?php } ?>
                        </h3>
                        
                        <?php /* if($allow_edit) { ?>
                            <p><i>Please write a few words about your values and coaching ethos to give the players a better idea of how you coach, manage or scout</i></p>
                        <?php } else { ?>
                            <p></p>
                        <?php } */ ?>
                        
                        <?php if ($videoId = getVideoId($userInfo['coach_values_and_coaching_philosophy_video'])) { ?>
                        
                        <iframe src="https://www.youtube.com/embed/<?php echo $videoId; ?>" allow="autoplay; encrypted-media" allowfullscreen="" height="315" frameborder="0" width="560"></iframe>
                        <?php } ?>
                        <p><?php echo $userInfo['coach_values_and_coaching_philosophy']; ?></p>
                                                
                        <h3>Recent Successes
                        <?php if($allow_edit) { ?>
                                <a href="<?php echo getLink('editprofilescout.php', 'type=recent_success'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                        <?php } ?>
                        </h3>
                        
                        <?php /* if($allow_edit) { ?>
                            <p><i>Please write a few words about your most recent coaching or scouting successes</i></p>
                        <?php } else { ?>
                            <p></p>
                        <?php } */ ?>
                        
                        <?php if ($videoId = getVideoId($userInfo['recent_success_video'])) { ?>
                        <iframe src="https://www.youtube.com/embed/<?php echo $videoId; ?>" allow="autoplay; encrypted-media" allowfullscreen="" height="315" frameborder="0" width="560"></iframe>
                        <?php } ?>
                        <p><?php echo $userInfo['recent_success']; ?></p>
                                                
                        <h3>Testimonial
                        <?php if($allow_edit) { ?>
                                <a href="<?php echo getLink('editprofilescout.php', 'type=testimonial_of_how_good_coach_i_am'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                        <?php } ?>
                        </h3>
                        <?php if ($videoId = getVideoId($userInfo['testimonial_of_how_good_coach_i_am_video'])) { ?>
                        <p></p>
                        <iframe src="https://www.youtube.com/embed/<?php echo $videoId; ?>" allow="autoplay; encrypted-media" allowfullscreen="" height="315" frameborder="0" width="560"></iframe>
                        <?php } ?>
                        <p><?php echo $userInfo['testimonial_of_how_good_coach_i_am']; ?></p>
                                         
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>