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
				
                <?php 
                $idOfUser=$userInfo["id"];
                        $query2="SELECT SUM(weightage)/10 FROM pitch_user_test_score where user_id=$idOfUser and available=1";
                        if (!$result3=$db->query($query2)) {
                            echo("Error description: " . $db -> error);
                        }
                        $userTestInfo = $result3->row_array();
                // echo getUserProfileImage($userInfo['photo']); 
                 echo '
                 <div class="stj_list_dv">
                 <ul class="players-list">
                 <li class="">
                 
                 <div class="p_car_plyr ' . (($userInfo['score_validated_date'] != null) ? (($userInfo['score_validated_by'] != 0) ? 'coach-validated' : 'validated') : 'not-validated') . '" style="background-image: url(' . playerImageCheck($userInfo['photo']) .');height:375px">
                 
                            
                            <div class="pcp_value">' . (($userTestInfo['SUM(weightage)/10']) ? $userTestInfo['SUM(weightage)/10'] : 0) . '</div>
                            <div class="pcp_validation ' . (($userInfo['score_validated_date'] != null) ? (($userInfo['score_validated_by'] != 0) ? 'coach-validated' : 'validated') : '') . '">
                                '. (($userInfo['score_validated_date']) ? (($userInfo['score_validated_by'] != 0) ? 'Coach Validated' : 'Validated') : 'Not Validated') . '
                            </div>
                        </div></li></ul></div>';
                ?>
                <?php if($allow_edit) { ?>
                    <a class="sct-edit" href="<?php echo getLink('editprofile.php', 'type=image_info'); ?>"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                <?php } ?>
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
</div>

<div class="p_profile_links">
    <div class="container">
        <div class="row">
            
            <div class="p_plink_dv">
                <ul>
                    <?php
                    $user_ids=$_SESSION['id'];
                        $query5="SELECT COUNT(*) FROM pitch_endorsement where viewed=1 and user_id=$user_ids";
                         $result11 = $db->query($query5);
					     $count=$result11->row_array();
					     $sum=$count["COUNT(*)"]+$user_views;
                    ?>
                    <li <?php if($tab == 'info') { ?> class="active" <?php } ?>><a href="<?php echo getLink('profile.php', 'tab=info' . $player_parameter); ?>">About</a></li>
                    <?php if(isset($_SESSION['id'])){?> 
                    <li <?php if($tab == 'score') { ?> class="active" <?php } ?>><a href="<?php echo getLink('profile.php', 'tab=score' . $player_parameter); ?>">Score</a></li>
                    <li <?php if($tab == 'technical_ability') { ?> class="active" <?php } ?>><a href="<?php echo getLink('profile.php', 'tab=technical_ability' . $player_parameter); ?>">Technical Ability Video</a></li>
                    <li <?php if($tab == 'resume') { ?> class="active" <?php } ?>><a href="<?php echo getLink('profile.php', 'tab=resume' . $player_parameter); ?>">Endorsement</a></li>
                    <?php if($allow_edit) { ?>
                    <li <?php if($tab == 'plan') { ?> class="active" <?php } ?>><a href="<?php echo getLink('profile.php', 'tab=plan' . $player_parameter); ?>">Subscription</a></li>
                    
                    <?php } if($allow_edit){ ?>
                    <li <?php if($tab == 'notifications') { ?> class="active" <?php } ?>><a href="<?php echo getLink('profile.php', 'tab=notifications' . $player_parameter); ?>">Notifications<?php if($sum>0){?> <span class="badge"><?= $sum ?></span><?php } ?></a></li>
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
            <?php
if(isset($_SESSION['id']) && isset($_GET['profile_id']) && $_SESSION['id'] != $_GET['profile_id']){
}

?>

<?php
 if($userRequierValidate == 1) { 
?>
<style>
@media only screen and (max-width: 767px) {
    .p_profile_links .p_plink_dv ul li {
    width: 100%;
}
.p_profile_links .p_plink_dv ul {
    display: grid!important;
    margin: 0 auto;
}
}
    .myClass1{
        color:black;
        padding:10px;
        background-color:red;
        margin-left: 10px;
        margin-right: 20px;
    }
    span.mycl {
    background-color: red;
    color:black;
    padding-top: 16px;
    padding-bottom: 16px;
        border: 1px solid black;
    border-radius: 10%;
    }
    
</style>
<div class="myClass1">
    <center><h4>Not Validated</h4></center>
</div>
<?php
}
 else if($userRequierValidate == 0 and $userInfo["score_validated_by"]==0) { 
?>
<style>
    .myClass1{
        color:black;
        padding:10px;
        background-color:#5dd05d;
        margin-left: 10px;
        margin-right: 20px;
    }
    span.mycl {
        color:black;
    background-color: #5dd05d;
    padding-top: 16px;
    padding-bottom: 16px;
        border: 1px solid black;
    border-radius: 10%;
}
    
</style>
<div class="myClass1">
    <center><h4>Validated</h4></center>
</div>
<?php
}

 else { 
?>
<style>
    .myClass1{
        color:black;
        padding:10px;
        background-color:#F87E02;
        /*background-color:#FFC000;*/
        margin-left: 10px;
        margin-right: 20px;

    }
    span.mycl {
        color:black;
    background-color: #F87E02;
    padding-top: 16px;
    padding-bottom: 16px;
        border: 1px solid black;
    border-radius: 10%;
}
    
</style>
<div class="myClass1">
    <center><h4>Coach Validated</h4></center>
</div>
<?php
}
?>
            
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
                        <span><?php 
                        $idOfUser=$userInfo["id"];
                        $query2="SELECT SUM(weightage)/10 FROM pitch_user_test_score where user_id=$idOfUser and available=1";
                        if (!$result3=$db->query($query2)) {
                            echo("Error description: " . $db -> error);
                        }
                        $userTestInfo = $result3->row_array();
                        echo $userTestInfo['SUM(weightage)/10']; ?></span>
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
                        <style>
                            .ply_clr_inn{
                                width:70px;
                            }
                        </style>
						<?php if($userRequierValidate == 1) { ?>
					         <label>Self Reported</label>
							<div class="ply_clr">
								<div class="ply_clr_inn active"></div>
								<div class="ply_clr_inn"></div>
								<div class="ply_clr_inn"></div>
							</div>
							<?php echo "<p style='display:inline'>Not Validated</p>&nbsp;&nbsp;"?>
                    
						<?php } elseif ($userInfo['score_validated_by'] == 0) { ?>
                            <label>Sport Science Validated</label>
                            <div class="ply_clr">
                                <div class="ply_clr_inn"></div>
                                <div class="ply_clr_inn"></div>
                                <div class="ply_clr_inn active"></div>
                            </div>
                            
                        <?php 
                        $date=date_create($userInfo["score_validated_date"]);
                            $formate=date_format($date,"jS-F-Y");
                        echo "<p style='display:inline'>Sports Science Validation Date:<br> ".$formate."</p>&nbsp;&nbsp;";echo '<img src="../images/dot.png" style="border-radius:50%;width: 20px;height: 20px;">';
                        ?>
                        
                        <?php
                        } else { ?>
                        
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
                            <?php 
                            $date=date_create($userInfo["score_validated_date"]);
                            $formate=date_format($date,"jS-F-Y");
                            echo "<p style='display:inline'>Coach Validation Date:<br> ".$formate."</p>&nbsp;&nbsp;";echo '<img src="../images/dot2.png" style="border-radius:50%;width: 20px;height: 20px;">'; ?>
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
<?php
    if($profile_id==$_SESSION['id'])
    {
        // $result = $db->query('UPDATE ' . $dbPrefix . 'views SET new = 0 WHERE viewed = ' . $_SESSION['id'] . '', array());
        // $result2 = $db->query('UPDATE ' . $dbPrefix . 'score_validation SET viewed = 1 WHERE coach_id = ' . $_SESSION['id'] . '', array());
        // $result3 = $db->query('UPDATE ' . $dbPrefix . 'blog_post SET viewed_users = CONCAT(viewed_users,",","'.$profile_id.'")', array());
        $result3 = $db->query('UPDATE ' . $dbPrefix . 'blog_post SET viewed_users = CONCAT(viewed_users,",","'.$profile_id.'") where viewed_users NOT LIKE "%'.$profile_id.'%"', array());
        // $result1 = $db->query('UPDATE ' . $dbPrefix . 'endorsement SET viewed = 0 WHERE user_id='.$_SESSION["id"], array());
        // $result1 = $db->query('UPDATE ' . $dbPrefix . 'endorsement_request SET viewed = 0 WHERE coach_id='.$_SESSION["id"], array());
    }
?>