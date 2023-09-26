<?php 

$tests = $db->query('SELECT user_score.*, test.* FROM ' . $dbPrefix . 'test as test LEFT JOIN  ' . $dbPrefix . 'user_test_score as user_score ON (user_score.test_id = test.id AND user_score.user_id = ?) WHERE test.status = 1 ORDER BY test.sort_order ASC', array($profile_id));
        $tests = $tests->result_array();
        $completedTests = 0;
        $avaliableTests = count($tests);
        foreach ($tests as $key => $test) {
            if(isset($test['total_score']) && $test['total_score'] != null){
                $completedTests++;
            }
        }

        $plan = $db->query('SELECT test_plan.*, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan INNER JOIN ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_id = ?', array($profile_id));
$planInfo = $plan->row_array();


?>



<?php 

$just_completed = false;

if($allow_edit && isset($_GET['registered'])) { ?>

<div class="col-xs-12 col-sm-9 alert alert-success m-2" role="alert">
  You have sucessfully registered with pitchrmt.com, you should receive an email confirming this
</div>

<?php } 

if($allow_edit && $userInfo['hidden'] == 1) { 

    if($userInfo['photo'] == '' || 
       $userInfo['what_are_your_core_values'] == '' ||
       $userInfo['what_sort_of_character_are_you'] == '' ||
       $userInfo['1st_player_position'] == 0 ||
    //    $userInfo['technical_ability_video'] == null ||
    //    $userInfo['resume_video'] == null ||
       $completedTests < 3
       ){

        ?> 
                <div class="col-xs-12 col-sm-9 alert alert-warning m-2 todoList" role="alert">
                    <h3>For your profile to become active please complete the following to do list:</h3>
                    <ul>
                    <?php if($userInfo['photo'] == '') {?><li> - A profile photo</li> <?php } ?>
                    <?php if($userInfo['what_are_your_core_values'] == '') {?><li> - Your core values and other info</li> <?php } ?>
                    <?php if($userInfo['what_sort_of_character_are_you'] == '') {?><li> - Footballing Characteristics</li> <?php } ?>
                    <?php if($userInfo['1st_player_position'] == 0) {?><li> - Your first player position</li> <?php } ?>                    
                    <?php if($completedTests < 3) {?><li> - <?= 3 - $completedTests ?> test/s need to be completed in score section found <a href="<?php echo getLink('profile.php', 'tab=score'); ?>">here</a></li> <?php } ?>
                    </ul> 
                </div>        
        <?php

    } else {
        // set to visable 
        $where ='id =' . $_SESSION['id'] .'';
        $userData = array(
            'hidden' => 0,
        );
        $update = updateData('user', $userData, $where);
        $just_completed = true;

    }
    
} else if ($allow_edit && 
            $completedTests < $avaliableTests || 
            $planInfo['test_plan_id'] == 1 ||
            $userInfo['technical_ability_video'] == "" ||
            $userInfo['resume_video'] == "" 
            ) {
        // opinional todo list
        ?>
        <div class="col-xs-12 col-sm-9 alert alert-success m-2 todoList todoList-optional" role="alert">
            <h3>Your profile is now complete but to maximise your chances of getting more scouts/coaches looking at your profile please complete the following to do list:</h3>
            <ul>
                <?php if($completedTests < $avaliableTests) {?><li>All the tests in the score section need to be completed, found <a href="<?php echo getLink('profile.php', 'tab=score'); ?>">here</a></li> <?php } ?>
                    <?php if($userInfo['technical_ability_video'] == "" || $userInfo['resume_video'] == "") {?><li>Upload a video of your technical ability <a href="<?php echo getLink('profile.php', 'tab=technical_ability'); ?>">here</a> also upload video in your resume section <a href="<?php echo getLink('profile.php', 'tab=resume'); ?>">here</a></li> <?php } ?>
                <li>Check out other player profiles <a href="<?php echo getLink('players.php'); ?>">here</a> and scout/coach profiles </li>
                <?php if($planInfo['test_plan_id'] == 1) {?><li>Upgrade subscription to Silver or Gold to increase your rank score <a href="<?php echo getLink('plan.php'); ?>">here</a></li><?php } ?>
            </ul>
        </div> 
        <?php
        

    } 

if(isset($_SESSION['id']) && isset($_GET['profile_id']) && $_SESSION['id'] != $_GET['profile_id']){
}

?>



<div class="col-xs-12 col-sm-9 ply_abt">
    <div class="ply_ip">
        <div class="ply_ip_inn">
            <h3>Personal<span>Info</span> 
                <?php if($allow_edit) { ?>
                    <a href="<?php echo getLink('editprofile.php', 'type=personal_info'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                <?php } ?>
            </h3>
            <hr>
            <ul>
                <li>
                    <label>Date of Birth</label>
                    <span><?php echo ($userInfo['date_of_birth'] != "0000-00-00 00:00:00") ? formatDate($userInfo['date_of_birth']) : " - "; ?></span>
                </li>
                <li>
                    <label>Height</label>
                    <span><?php echo $userInfo['height']; ?> cm</span>
                </li>
                <li>
                    <label>Weight</label>
                    <span><?php echo $userInfo['weight']; ?> kg</span>
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
                    <a href="<?php echo getLink('editprofile.php', 'type=playing_position'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                <?php } ?>
            </h3>
            <hr>
            <ul>
                <li>
                    <label>Highest Education Level</label>
                    <span><?php echo $userInfo['highest_education_level']; ?></span>
                </li>
                <li>
                    <label>Any Previous Injuries</label>
                    <span><?php echo ($userInfo['previous_injury'] == 1) ? 'Yes' : 'No'; ?></span>
                </li>
                <li>
                    <label>State Nature of Injury</label>
                    <span><?php echo $userInfo['nature_of_injury']; ?></span>
                    
                </li>
                <li>
                    <label>No. of Years playing Football</label>
                    <span><?php echo $userInfo['years_playing_football']; ?></span>
                </li>
                <li>
                    <label>Highest Level Played At</label>
                    <span><?php echo $userInfo['highest_level_played_at']; ?></span>
                </li>
                <li>
                    <label>Club Played At Highest Level</label>
                    <span><?php echo $userInfo['club_played_at_highest_level']; ?></span>
                </li>
            </ul>
        </div>
    </div>
    <div class="ply_pos">
        <div class="ply_pos_lft">
            <h3>Playing <span>position</span>
                <?php if($allow_edit) { ?>
                    <a href="<?php echo getLink('editprofile.php', 'type=playing_position_second'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                <?php } ?>
            </h3>
            <ul>
                <li>
                    <label>1st Playing Position</label>
                    <?php if(isset($userInfo['1st_player_position']) && $userInfo['1st_player_position'] != '') { ?>
                    <h4><?php echo getPosition($userInfo['1st_player_position']); ?></h4>
                    <?php } ?>
                </li>
                <li>
                    <label>2nd Playing Position</label>
                    <?php if(isset($userInfo['2nd_player_position']) && $userInfo['2nd_player_position'] != '') { ?>
                    <h4><?php echo getPosition($userInfo['2nd_player_position']); ?></h4>
                    <?php } ?>
                </li>
                <li>
                    <label>3rd Playing Position</label>
                    <?php if(isset($userInfo['3rd_player_position']) && $userInfo['3rd_player_position'] != '') { ?>
                    <h4><?php echo getPosition($userInfo['3rd_player_position']); ?></h4>
                    <?php } ?>
                </li>
                <li>
                    <label>Preferred Foot</label>
                    <h4><?php echo $userInfo['prefered_foot']; ?></h4>
                </li>
            </ul>
        </div>
        <div class="ply_pos_rgt">
        
            <h3>Core Values
                <?php if($allow_edit) { ?>
                    <a href="<?php echo getLink('editprofile.php', 'type=core_values'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                <?php } ?>
            </h3>
            <p><?php echo $userInfo['what_are_your_core_values']; ?></p>
                                    
            <h3>Footballing Characteristics
                <?php if($allow_edit) { ?>
                    <a href="<?php echo getLink('editprofile.php', 'type=what_sort_of_personality'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                <?php } ?>
            </h3>
            <p><?php echo $userInfo['what_sort_of_character_are_you']; ?></p>
                               
        </div>
    </div>
</div>

