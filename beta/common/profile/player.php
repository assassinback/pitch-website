<?php
    if (!isset($_SESSION['id']) || (isset($_SESSION['id']) && $profile_id != $_SESSION['id'])) {
        $player_parameter = '&profile_id='.$profile_id;
    } else {
        $player_parameter = '';
    }

    if($allow_edit){
        $result = $db->query('SELECT views.viewer, views.date, user.user_type, user.first_name, user.last_name, user.currently_working_for FROM ' . $dbPrefix . 'views as views LEFT JOIN ' . $dbPrefix . 'user as user ON views.viewer = user.id WHERE views.viewed = ' . $_SESSION['id'] . ' AND views.new = 1 AND views.viewer IS NOT NULL ORDER BY views.date ASC', array());
        $user_views = 0;
        if($result){
            $user_views = sizeof($result->result_array());
        }

    }
?>

<div class="p_profile_bnr">

    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-3 p_profile_img">
				<div class="p_profile_img_inn" style="background-image: url(<?= playerImageCheck($userInfo['photo']) ?>);">
                <?php 
                // echo getUserProfileImage($userInfo['photo']); 
                ?>
                <?php if($allow_edit) { ?>
                    <a class="sct-edit" href="<?php echo getLink('editprofile.php', 'type=image_info'); ?>"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                <?php } ?>
				</div>
            </div>
            <div class="col-xs-12 col-sm-8 p_profile_con">
                <div class="p_profile_con_inn">
                    <h3><?php echo $userInfo['first_name']; ?><br/><span><?php echo $userInfo['last_name']; ?></span></h3>
                    <?php if(isset($userInfo['team_id'])) { ?>
                        <h4><?php echo getClub($userInfo['team_id']); ?></h4>
                    <?php } ?>
                </div>
                
                <?php /* <a href="javascript:" class="a_sm compare <?php if (in_array($profile_id, $compare)) { ?> added <?php } ?>" data-player="<?php echo $profile_id; ?>">Compare</a> */ ?>
				
				<?php if(isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 2 || $_SESSION['user_type'] == 3)){ ?>
				<a href="javascript:" class="a_sm compare <?php if (in_array($profile_id, $compare)) { ?> added <?php } ?>" data-player="<?php echo $profile_id; ?>">Compare</a>
				<a href="javascript:void(0);" class="compare cominfo" data-toggle="tooltip" data-placement="left" title="Please select two players then go to the compare page"><i class="fa fa-info"></i></a>
                <?php } ?>
                
                <?php include('common/profile/send_message.php'); ?>
                
            </div>
        </div>
    </div>
</div>

<div class="p_profile_links">
    <div class="container">
        <div class="row">
            <div class="p_plink_dv">
                <ul>
                    <li <?php if($tab == 'info') { ?> class="active" <?php } ?>><a href="<?php echo getLink('profile.php', 'tab=info' . $player_parameter); ?>">About</a></li>
                    <?php if(isset($_SESSION['id'])){?> 
                    <li <?php if($tab == 'score') { ?> class="active" <?php } ?>><a href="<?php echo getLink('profile.php', 'tab=score' . $player_parameter); ?>">Score</a></li>
                    <li <?php if($tab == 'technical_ability') { ?> class="active" <?php } ?>><a href="<?php echo getLink('profile.php', 'tab=technical_ability' . $player_parameter); ?>">Technical Ability Video</a></li>
                    <li <?php if($tab == 'resume') { ?> class="active" <?php } ?>><a href="<?php echo getLink('profile.php', 'tab=resume' . $player_parameter); ?>">Résumé Video</a></li>
                    <?php if($allow_edit) { ?>
                    <li <?php if($tab == 'plan') { ?> class="active" <?php } ?>><a href="<?php echo getLink('profile.php', 'tab=plan' . $player_parameter); ?>">Subscription</a></li>
                    
                    <?php } if($allow_edit){ ?>
                    <li <?php if($tab == 'notifications') { ?> class="active" <?php } ?>><a href="<?php echo getLink('profile.php', 'tab=notifications' . $player_parameter); ?>">Notifications<?php if($user_views > 0){?> <span class="badge"><?= $user_views ?></span><?php } ?></a></li>
                    <?php } ?>
                    <?php } else { ?> 
                        <li class="no-login">Want to see more ? <a href="login.php">login</a> or <a href="register.php">register</a> to see the full player profile</li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="p_profile_dtl">
    <div class="container">
        <div class="row">
            
            <div class="col-xs-12 col-sm-3 player_info">
                <div class="clearfix" >
                <ul>
                    <?php 
                        $userRequierValidate = null;

						$user_type = $userInfo['user_type'];
						if($allow_edit && $user_type == 1) { 
						$user_id = $_SESSION['id'];

						$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($user_id));
						if ($result->num_rows() == 0) {
							redirect(getLink());
						}

                        $userInfo = $result->row_array();
                        
                        $userRequierValidate = 0;

						$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user_test_score WHERE user_id = ? AND require_validation = 1', array($user_id));
						if ($result->num_rows() > 0) {
							$userRequierValidate = 1;
						}
						
						$result = $db->query('SELECT test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan LEFT JOIN  ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_test_plan.user_id = ?', array($user_id));
						$userPlanInfo = $result->row_array();
					?>
					<li class="<?php echo 'plan-'.$userPlanInfo['id']; ?>">
                        <label>Subscription</label>
                        <span><?php echo $userPlanInfo['title']; ?></span>
                    </li>
					<?php } ?>
                    <li>
                        <label>Rank</label>
                        <span><?php echo $userInfo['user_rank']; ?></span>
                    </li>
                    <li>
                        <label>OVerall Score</label>
                        <span><?php echo $userInfo['overall_score']; ?></span>
                    </li>
                    <li>
                        <label>Ratings</label>
                        <?php $user_rating = round($userInfo['user_rating']); ?>    
                        <div class="plyr_star">
                            <?php for ($i = 1; $i <= $user_rating; $i++) { ?>
                             <img src="images/star1.png" alt=""/>
                            <?php } ?>
                        </div>    
                    </li>
                    <?php if ($userInfo['score_validated_by'] != null) { ?>
                    <li>
						<?php if($userRequierValidate == 1) { ?>
					         <label>Self Reported</label>
							<div class="ply_clr">
								<div class="ply_clr_inn active"></div>
								<div class="ply_clr_inn"></div>
								<div class="ply_clr_inn"></div>
							</div>
                    
						<?php } elseif ($userInfo['score_validated_by'] == 0) { ?>
                            <label>Sport Science Validated</label>
                            <div class="ply_clr">
                                <div class="ply_clr_inn"></div>
                                <div class="ply_clr_inn"></div>
                                <div class="ply_clr_inn active"></div>
                            </div>
                        <?php } else { ?>
                        
                            <?php
                                $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE id = ? AND status = 1', array($userInfo['score_validated_by']));
                                $coachInfo = $result->row_array();
                            ?>
                            <label>Coach Validated <!-- (<a href="<?php //echo getLink('profile.php', 'profile_id=' . $coachInfo['id']); ?>" ><?php //echo $coachInfo['first_name'] . ' ' . $coachInfo['last_name']; ?></a>) --> </label>
                            <div class="ply_clr">
                                <div class="ply_clr_inn"></div>
                                <div class="ply_clr_inn active"></div>
                                <div class="ply_clr_inn"></div>
                            </div>
                        <?php } ?>
                    </li>
                    <?php } elseif($userInfo['overall_score'] != 0) { ?>
                    <li>
                        <label>Self Reported</label>
                        <div class="ply_clr">
                            <div class="ply_clr_inn active"></div>
                            <div class="ply_clr_inn"></div>
                            <div class="ply_clr_inn"></div>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
                </div>
            <?php if ($tab != 'score') { ?>
            
                    <?php include('common/profile/delete_account.php'); ?>
                
                </div>
            <?Php } ?>
           
            <?php include('common/profile/tab_' . $tab . '.php'); ?>
        </div>
    </div>
</div>