<style>.row.special-row {
    display: inline-flex;
    width: 100%!important;
    --grid-side-margin: 10px!important;
}
h5.text-center.special-Heading {
    margin: 10px 10px;
}
</style>
<?php
    $sendRequest = false;
    if (($allow_edit == false) && isset($_SESSION['id']) && isset($_SESSION['user_type']) && ($_SESSION['user_type'] == 1)) {
        $result = $db->query('SELECT * FROM ' . $dbPrefix . 'score_validation WHERE status = 0 AND player_id = ? AND coach_id = ?', array($_SESSION['id'], $profile_id));
        if ($result->num_rows() == 0) {
            $sendRequest = true;
        }
    }
if(isset($_POST["validate"]))
{
	$player_id=$_POST["user_id"];
	$coach_id=$profile_id;
	$query17="UPDATE pitch_score_validation set status=1 where player_id=$player_id";
	//   echo $query17;
	if (!$result17=$db->query($query17)) {
        echo("Error description: " . $db -> error);
        }
        $t=time();
	//   $result17=$db->query($query17);
	$dateforvalid = date("Y-m-d H:i:s");
	$query18="UPDATE pitch_user set score_validated_by=$profile_id, score_validated_date='$dateforvalid' where id=$player_id";
	if (!$result18=$db->query($query18)) {
        echo("Error description: " . $db -> error);
        }
        $query19="UPDATE pitch_user_test_score set require_validation=0 where user_id=$player_id";
        if (!$result19=$db->query($query19)) {
        echo("Error description: " . $db -> error);
        }
        $query20="INSERT INTO pitch_views(viewed,viewer,table_type) VALUES($player_id,$coach_id,'validation')";
        if (!$result20=$db->query($query20)) {
        echo("Error description: " . $db -> error);
        }
        
}
if(isset($_POST["deleteValidation"]))
{
    $player_id=$_POST["user_id"];
	$coach_id=$profile_id;
	$query19="Delete from pitch_score_validation where coach_id=$coach_id and player_id=$player_id";
	if (!$result19=$db->query($query19)) {
        echo("Error description: " . $db -> error);
        }
}
if(isset($_POST["deleteNotification"]))
{
    $player_id=$_POST["viewer_id"];
	$coach_id=$profile_id;
	$date=$_POST["date"];
	$query19="Delete from pitch_views where viewed=$coach_id and viewer=$player_id and date='$date'";
	if (!$result19=$db->query($query19)) {
        echo("Error description: " . $db -> error);
        }
}
if(isset($_POST["deleteBlog"]))
{
    $player_id=$_SESSION["id"];
    $blog_id=$_POST["blog_id"];
    // $deleteDate=$_POST["deleteDate"];
    // echo $player_id."<br>";
    $query="UPDATE pitch_blog_post set deleted_blogs=CONCAT(deleted_blogs,',$player_id') where id=$blog_id";
    if (!$result2=$db->query($query)) {
        echo("Error description: " . $db -> error);
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
    header( "Location: " . getLink('login.php') . "");
}

if($userInfo['hidden'] == 1){
    $plan = $db->query('SELECT test_plan.*, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan INNER JOIN ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_id = ?', array($_SESSION['id']));
    $planInfo = $plan->row_array();
    if($planInfo['test_plan_id'] > 1 || $loginuser_type !== 1){  
    } else {
        header( "Location: " . getLink('plan.php?redirect=profile') . "");
    }
}
if(isset($_POST["deleteEndorse"]))
            {
                $user_id=$_POST["user_id"];
                $endorsement_id=$_POST["endorsement_id"];
                $query="UPDATE pitch_endorsement_request set done=1 where user_id=$user_id and endorsement_id=$endorsement_id";
                $db->query($query);
            }
            
?>

<div class="p_profile_bnr">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-3 p_profile_img">
            <div class="p_profile_img_inn" style="background-image: url(<?= playerImageCheck($userInfo['photo']) ?>);">
                <?php 
                //echo getUserProfileImage($userInfo['photo'], 'coach'); 
                ?>
                <?php if($allow_edit) { ?>
                    <a class="sct-edit" href="<?php echo getLink('editprofilecoach.php', 'type=image_info'); ?>"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                <?php } ?>
				</div>
			<div class="del_acc">
				<?php include('common/profile/delete_account.php'); ?>
			</div>
			</div>
            <div class="col-xs-12 col-sm-8 p_profile_con">
                <div class="p_profile_con_inn">
                    <h3><?php echo $userInfo['first_name']; ?><br/><span><?php echo $userInfo['last_name']; ?></span></h3>
                    
                    <?php if(trim($userInfo['currently_working_for']) != NULL) { ?>
                        <h4>Coach (<?php echo $userInfo['currently_working_for']; ?>)</h4>
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
<div class="tabbable-panel">
    <div class="container"><div class="row">
        <div class="col-xs-12 col-sm-3 p_profile_img"></div>
        <div class="col-xs-12 col-sm-9 p_profile_img">
				<div class="tabbable-line">
					<ul class="nav nav-tabs ">
						<li class="active">
							<a href="#tab_default_1" data-toggle="tab">
							About </a>
						</li>
					<?php if($profile_id==$_SESSION['id']){ 
					    $coach_id=$_SESSION['id'];
					    $query11="SELECT COUNT(*) from pitch_endorsement_request WHERE coach_id=$coach_id and done=0";
					    $result11 = $db->query($query11);
					    $count=$result11->row_array();
					   // echo $count["COUNT(*)"];
					   $query12="SELECT COUNT(*) from pitch_views WHERE viewed=$coach_id and new=1";
					   $result12 = $db->query($query12);
					    $count1=$result12->row_array();
					    $query13="SELECT COUNT(*) from pitch_score_validation WHERE coach_id=$coach_id and status=0";
					   $result13 = $db->query($query13);
					    $count2=$result13->row_array();
					    $query19="SELECT COUNT(*) from pitch_blog_post where viewed_users NOT LIKE '%$profile_id%' and status=1";
					    $result19 = $db->query($query19);
					    $count3=$result19->row_array();
					    $combine=$count["COUNT(*)"]+$count1["COUNT(*)"];
					?>
						<!--<li>-->
						<!--	<a href="#tab_default_2" data-toggle="tab">-->
							
						<!--	Notifications (<?php //echo $combine; ?>)</a>-->
						<!--</li>-->
					    <li>
					        <style>
							    .row{
							        margin-left:0px;
							    }
							</style>
							<a href="#tab_default_6" data-toggle="tab">
							Notifications (<?php echo $combine; ?>)</a> </a>
						</li>
						<!--<li>-->
							<!--<a href="#tab_default_3" data-toggle="tab">-->
							<!--Endorsement Request (<?php //echo $count["COUNT(*)"]; ?>) </a>-->
						<!--</li>-->
						<li>
							<a href="#tab_default_4" data-toggle="tab">
							Validation Requests (<?php echo $count2["COUNT(*)"]; ?>) </a>
						</li>
						
						
					<?php } ?> 
					</ul>
			
            
        <div>
        <style>
        .col-xs-12.col-sm-9.alert.alert-success.m-2.todoList.todoList-optional {
            width: 100% !important;
        }
        .col-xs-12.col-sm-9.alert.alert-warning.m-2.todoList {
            width: 100% !important;
        }
        </style><?php
    if(isset($_POST["endorse"]))
    {
        // echo "here";
        $user_id=$_POST["user_id"];
        $endorsement_id=$_POST["endorsement_id"];
        $coachid=$_SESSION['id'];
        $query7="SELECT * from pitch_endorsement where user_id=$user_id and endorsement_id=$endorsement_id";
        $result7=$db->query($query7);
		$row7=$result7->result_array();
		$j=0;
		
		foreach($row7 as $rows7)
		{
		    $coaches=$rows7['endorsment_user_id'];
		    $coaches=explode(",",$coaches);
		    $k=0;
		    $l=false;
		    while($k<sizeof($coaches))
		    {
		        if($coaches[$k]==$coachid)
		        {
		            $l=true;
		            
		            
		        }
		        $k++;
    		    
		    }
		    if(!$l)
		    {
		        $query8="UPDATE pitch_endorsement set endorsment_user_id=CONCAT(endorsment_user_id, ',$coachid'), endorsement_count=endorsement_count+1, endorsement_points=endorsement_points+20 where user_id=$user_id and endorsement_id=$endorsement_id";
    		    $result8=$db->query($query8);
    		    $query9="UPDATE pitch_user set endorsement_count=endorsement_count+1 where user_id=$user_id";
    		    $result9=$db->query($query9);
    		  //  $query10="UPDATE pitch_endorsement_request set done=1 where user_id=$user_id and coach_id=$coachid and endorsement_id=$endorsement_id";
    		  //  if($result10=$db->query($query10))
		      //  {
		      //      echo "<script>alert(1);</script>";
		      //  }
		      //  else{
		      //      echo "<script>alert(1);</script>";
		      //      echo "Error: " . $db->error;
		      //  }
    		    ?>
                                                <div class="row">
                                                  
                                                  <div class="alert alert-success">
                                                    <strong>Success!</strong> Player endorsed successfully
                                                  </div>
                                                </div>
                                        <?php
		    }
		    
		  //  $row8=$result8->result_array();
		    $j++;
		}
		if($j==0)
		{
		    $query8="INSERT into pitch_endorsement (user_id,endorsement_id,endorsement_count,endorsment_user_id,endorsement_points) VALUES($user_id,$endorsement_id,1,'$coachid',20)";
		    
		    if($result8=$db->query($query8))
		    {
		        $query9="UPDATE pitch_user set endorsement_count=endorsement_count+1 where id=$user_id";
    		    $result9=$db->query($query9);
    		    ?>
                                                <div class="container">
                                                  
                                                  <div class="alert alert-success">
                                                    <strong>Success!</strong> Player endorsed successfully
                                                  </div>
                                                </div>
                                        <?php
        //         $query10="UPDATE pitch_endorsement_request set done=1 where user_id=$user_id and coach_id=$coachid and endorsement_id=$endorsement_id";
    		  //  if($result10=$db->query($query10))
		      //  {
		      //      echo "<script>alert(1);</script>";
		      //  }
		      //  else{
		      //      echo "<script>alert(1);</script>";
		      //      echo "Error: " . $db->error;
		      //  }
		    }
		    else{
		        echo "Error: " . $db->error;
		    }
		}
		$query10="UPDATE pitch_endorsement_request set done=1 where user_id=$user_id and coach_id=$coachid and endorsement_id=$endorsement_id";
    		    if($result10=$db->query($query10))
		        {
		          //  echo "<script>alert(1);</script>";
		        }
		        else{
		          //  echo "<script>alert(2);</script>";
		            echo "Error: " . $db->error;
		        }
    }
?></div>
					<div class="tab-content">
					    
						<div class="tab-pane active" id="tab_default_1">
						    <?php 
            
            if($profile_id == $_SESSION['id'])
            {
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
                            $userInfo['highest_education_level'] == '') {?><li> - Complete other info section to highlight your achievements </li> <?php } ?>
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
            }  
            
            ?>
						<div class="ply_ip">
                    <div class="ply_ip_inn">
                        
                        <h3>Personal<span>Info</span>
                        
                        <?php if($allow_edit) { ?>
                                <a href="<?php echo getLink('editprofilecoach.php', 'type=personal_info'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
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
                        <h3>Coach<span>Info</span>
                        <?php if($allow_edit) { ?>
                                <a href="<?php echo getLink('editprofilecoach.php', 'type=playing_position_second_coach'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
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
                                <a href="<?php echo getLink('editprofilecoach.php', 'type=about_me'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                        <?php } ?>
                        </h3>
                        <p><?php echo $userInfo['about_me']; ?></p>
                                                
                        <h3>Core values and coaching philosophy 
                        <?php if($allow_edit) { ?>
                                <a href="<?php echo getLink('editprofilecoach.php', 'type=coach_values_coaching_philosophy'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                        <?php } ?>
                        </h3>
                        <?php if ($videoId = getVideoId($userInfo['coach_values_and_coaching_philosophy_video'])) { ?>
                        <p></p>
                        <iframe src="https://www.youtube.com/embed/<?php echo $videoId; ?>" allow="autoplay; encrypted-media" allowfullscreen="" height="315" frameborder="0" width="560"></iframe>
                        <?php } ?>
                        <p><?php echo $userInfo['coach_values_and_coaching_philosophy']; ?></p>
                                                
                        <h3>Recent Successes
                            <?php if($allow_edit) { ?>
                                <a href="<?php echo getLink('editprofilecoach.php', 'type=recent_success'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
                        <?php } ?>
                        </h3>
                        <?php if ($videoId = getVideoId($userInfo['recent_success_video'])) { ?>
                        <p></p>
                        <iframe src="https://www.youtube.com/embed/<?php echo $videoId; ?>" allow="autoplay; encrypted-media" allowfullscreen="" height="315" frameborder="0" width="560"></iframe>
                        <?php } ?>
                        <p><?php echo $userInfo['recent_success']; ?></p>
                                                
                        <h3>Testimonial
                            <?php if($allow_edit) { ?>
                                <a href="<?php echo getLink('editprofilecoach.php', 'type=testimonial_of_how_good_coach_i_am'); ?>" class="edit-link"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></a>
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
						<div class="tab-pane" id="tab_default_2">
						    <?php
						    $query2="SELECT * FROM pitch_blog_post where status=1";
            if (!$result2=$db->query($query2)) {
                echo("Error description: " . $db -> error);
            }
            $row2=$result2->result_array();
            foreach($row2 as $rows2)
            {
                $author=$rows2["author"];
                $title=$rows2["title"];
                $blog_id=$rows2["id"];
                echo "<div class='row'>
               
		                            
                <img class='img-responsive' src='./uploads/user/WhatsApp Image 2021-02-10 at 11.40.53 AM.jpeg' style='width:50px;height:50px;margin-right:10px;'>A new blog has been published by $author, title $title, <a href='./blog_detail.php?blog_id=$blog_id'>Click here to read more</a></div><br>";   
            }
						    $curid=$_SESSION['id'];
						        if($profile_id!=$_SESSION['id'])
						        {
						            $query1="INSERT into pitch_views(viewed,viewer) values($profile_id,$curid)";
						            $result1 = $db->query($query1);
						            if (!$result1=$db->query($query1)) {
                                       echo("Error description: " . $db -> error);
                                    }   
						        }
						        else
						        {
						            $done=false;
							    $query2="SELECT * from pitch_endorsement_request ORDER BY id DESC";
							    $result2=$db->query($query2);
							    $row2=$result2->result_array();
							    foreach($row2 as $rows2)
							    {
							        if($rows2["done"]==0 and $_SESSION["id"]==$rows2["coach_id"])
							        {
							        $done=false;
							        $name='';
							        $endorsement_id=$rows2['endorsement_id'];
							     //   echo $endorsement_id;
							        $user_id=$rows2['user_id'];
							        $query3="SELECT * from pitch_user where id=$user_id";
							        $result3=$db->query($query3);
							        $row3=$result3->result_array();
							        
							        foreach($row3 as $rows3)
							        {
							            $query4="SELECT endo.user_id,endorsment_user_id,pitch_endorsement_type.endorsement_name,pitch_endorsement_type.id FROM `pitch_endorsement` as endo LEFT join pitch_endorsement_type on endo.endorsement_id=pitch_endorsement_type.id where endo.user_id=$user_id and pitch_endorsement_type.id=$endorsement_id";
							            $result4=$db->query($query4);
							            $row4=$result4->result_array();
							         //   var_dump($row4);
							            foreach($row4 as $rows4)
							            {
							                $name=$rows4["endorsement_name"];
							                $coaches=explode(",",$rows4["endorsment_user_id"]);
							                $i=0;
							                while($i<sizeof($coachies))
							                {
							                    if($_SESSION['id']==$coaches[$i])
							                    {
							                        $done=true;                    
							                        
							                    }
							                    $i++;
							                }
							            }
							            if(!$done)
							            {
							             //   echo $name."here123";
							                if($name=="" or $name=='')
							                {
							                 //   echo "here";
							                    $query6="SELECT endorsement_name from pitch_endorsement_type where id=$endorsement_id";
							                    $result6=$db->query($query6);
							                    $row6=$result6->result_array();
							                 //   var_dump($row6);
							                    foreach($row6 as $rows6)
							                    {
							                     //   echo "here";
							                     //echo $rows6["endorsement_name"];
							                        $name=$rows6["endorsement_name"];
							                    }
							                }
    							        ?>
    							        
    						            <!--<div class="col-md-3 " style="height:auto;">-->
    						            <div class="row special-row">
    						            <div class="p_profile_img_inn img-responsive" style="background-image: url(<?= playerImageCheck( $rows3['photo']) ?>);width:50px;height:50px;display:inline;">
    		                            </div>
    						           
                                           <h5 class="text-center special-Heading" style="height: 50px;clear: both;"><a href="<?= getLink('profile.php', 'profile_id='.$rows3['id']) ?>"><?= $rows3['first_name'] ?> <?= $rows3['last_name'] ?></a> Requested Endorsement on <?php echo $name; ?></h5> 
                                            <form method="POST" style="margin-bottom:5px;">
                                                <input type="hidden" value='<?php echo $user_id; ?>' name="user_id">
                                                <input type="hidden" value='<?php echo $endorsement_id; ?>' name="endorsement_id">

                                                <input type="submit" value="Delete?" class="btn-sm btn-success" name="deleteEndorse">
                                                                                                <input type="submit" value="Endorse?" class="btn-sm btn-success" name="endorse" style="margin-right: -12px;">
                                            </form>
                                       </div>
                                        <!--</div>-->
                                            
                                            
                                        <?php
							            }
							        }
							    }
							    }
							    
						            $result1 = $db->query('SELECT views.viewer, views.date, user.user_type, user.first_name, user.photo, user.last_name, user.currently_working_for FROM ' . $dbPrefix . 'views as views LEFT JOIN ' . $dbPrefix . 'user as user ON views.viewer = user.id WHERE views.viewed = ' . $_SESSION['id'] . ' AND views.viewer IS NOT NULL ORDER BY views.date DESC', array());
						            $user_views = $result1->result_array();
						            ?>
						            <div class="row">
						            <?php
						            foreach ($user_views as $key => $view) { 
						                
						            ?>
						            <div class="row" style="margin-bottom:10px;">
						            <div class="p_profile_img_inn img-responsive" style="background-image: url(<?= playerImageCheck( $view['photo']) ?>);width:50px;height:50px;">
		                            </div>
						            <div class="col-xs-9 col-sm-9">
						            <div class="row">
                                        <a href="<?= getLink('profile.php', 'profile_id='.$view['viewer']) ?>"><?= $view['first_name'] ?> <?= $view['last_name'] ?></a> viewed your profile
                                    </div>
                                    </div>
                                        </div>
                                    <?php
						            }
						          //  $query1="SELECT * from pitch_views where viewed=$curid";
						          //  $result1 = $db->query($query1);
						          //  if (!$result1=$db->query($query1)) {
                //                         echo("Error description: " . $db -> error);
                //                     }
                //                     $row1=$result1->result_array();
                //                     foreach ($row1 as $rows1)
                //                     {
                //                         echo $rows1["viewed"]."<br>";
                //                     }
						        }
						        
						    ?>
						    </div>
						</div>
						
						<!--<div class="tab-pane" id="tab_default_3">-->
						<!--    <div class='container'>-->
						<!--        <div class='row'>-->
							<?php 
							 //   $done=false;
							 //   $query2="SELECT * from pitch_endorsement_request";
							 //   $result2=$db->query($query2);
							 //   $row2=$result2->result_array();
							 //   foreach($row2 as $rows2)
							 //   {
							 //       if($rows2["done"]==0 and $_SESSION["id"]==$rows2["coach_id"])
							 //       {
							 //       $done=false;
							 //       $name='';
							 //       $endorsement_id=$rows2['endorsement_id'];
							 //    //   echo $endorsement_id;
							 //       $user_id=$rows2['user_id'];
							 //       $query3="SELECT * from pitch_user where id=$user_id";
							 //       $result3=$db->query($query3);
							 //       $row3=$result3->result_array();
							        
							 //       foreach($row3 as $rows3)
							 //       {
							 //           $query4="SELECT endo.user_id,endorsment_user_id,pitch_endorsement_type.endorsement_name,pitch_endorsement_type.id FROM `pitch_endorsement` as endo LEFT join pitch_endorsement_type on endo.endorsement_id=pitch_endorsement_type.id where endo.user_id=$user_id and pitch_endorsement_type.id=$endorsement_id";
							 //           $result4=$db->query($query4);
							 //           $row4=$result4->result_array();
							 //        //   var_dump($row4);
							 //           foreach($row4 as $rows4)
							 //           {
							 //               $name=$rows4["endorsement_name"];
							 //               $coaches=explode(",",$rows4["endorsment_user_id"]);
							 //               $i=0;
							 //               while($i<sizeof($coachies))
							 //               {
							 //                   if($_SESSION['id']==$coaches[$i])
							 //                   {
							 //                       $done=true;                    
							                        
							 //                   }
							 //                   $i++;
							 //               }
							 //           }
							 //           if(!$done)
							 //           {
							 //            //   echo $name."here123";
							 //               if($name=="" or $name=='')
							 //               {
							 //                //   echo "here";
							 //                   $query6="SELECT endorsement_name from pitch_endorsement_type where id=$endorsement_id";
							 //                   $result6=$db->query($query6);
							 //                   $row6=$result6->result_array();
							 //                //   var_dump($row6);
							 //                   foreach($row6 as $rows6)
							 //                   {
							 //                    //   echo "here";
							 //                    //echo $rows6["endorsement_name"];
							 //                       $name=$rows6["endorsement_name"];
							 //                   }
							 //               }
    							        ?>
    							        
    						            <!--<div class="col-md-3 " style="height:auto;">-->
    						            <!--<div class="p_profile_img_inn img-responsive" style="background-image: url(<?= playerImageCheck( $rows3['photo']) ?>);">-->
    		                <!--            </div>-->
    						           
                      <!--                     <h5 class="text-center" style="height: 50px;clear: both;"><a href="<?= getLink('profile.php', 'profile_id='.$rows3['id']) ?>"><?= $rows3['first_name'] ?> <?= $rows3['last_name'] ?></a> Requested Endorsement on <?php echo $name; ?></h5> -->
                      <!--                      <form method="POST" style="margin-bottom:5px;">-->
                      <!--                          <input type="hidden" value='<?php echo $user_id; ?>' name="user_id">-->
                      <!--                          <input type="hidden" value='<?php echo $endorsement_id; ?>' name="endorsement_id">-->
                      <!--                          <center><input type="submit" value="Endorse?" class="btn-sm btn-primary" name="endorse"></center>-->
                      <!--                          <center><input type="submit" value="Delete?" class="btn-sm btn-primary" name="deleteEndorse"></center>-->
                      <!--                      </form>-->
                                       
                      <!--                  </div>-->
                                            
                                            
                                        <?php
							         //   }
							     //   }/**/
							 //   }
							 //   }
							    
							?>
						<!--	</div> -->
						<!--	</div> -->
						<!--</div>-->
						<div class="tab-pane" id="tab_default_4">
						    <!--<div class='container'>-->
						        <!--<div class='row special-row'>-->
						            <?php
						            
						                $coach_id=$_SESSION["id"];
						                $query15="SELECT * FROM pitch_score_validation where coach_id=$coach_id and status=0";
						                if (!$result15=$db->query($query15)) {
                                                echo("Error description: " . $db -> error);
                                        }
                                        $row15=$result15->result_array();
                                        // var_dump($row15);
                                        foreach($row15 as $rows15)
                                        {
                                            // var_dump($rows15);
                                            $user_id1=$rows15['player_id'];
                                            // echo "<script>alert(".$user_id.");</script>";
							                $query16="SELECT * from pitch_user where id=$user_id1";
							             //   echo $query16;
							                $result16=$db->query($query16);
							                $row16=$result16->result_array();
							             //   var_dump($row16);
							                foreach($row16 as $rows16)
							                {
                                            ?>
                                            <div  class='row special-row'>
                                             <!--<div class="col-md-4" style="height:auto;margin-top:5px;margin-bottom:5px;">-->
                                            <form method="POST">
                                                
                                                <input type="hidden" value='<?php echo $user_id1; ?>' name="user_id">
                                                <input type="hidden" value='<?php echo $coach_id; ?>' name="endorsement_id">
                                                
                                                <img src="<?= playerImageCheck( $rows16['photo']) ?>" style="width:100px;height:100px;margin-right:5px;">
                                                <a href="./profile.php?profile_id=<?php echo $user_id1; ?>"><?php echo $rows16["first_name"]." ".$rows16["last_name"] ?></a> Has requested Score validation
                                                
                                                <br>
                                                <input type="submit" value="Validate?" class="btn-sm btn-success" name="validate">
                                                <input type="submit" value="Delete?" class="btn-sm btn-success" name="deleteValidation">
                                            </form>
                                            </div>
                                            <!--</div>-->
                                            
                                            <?php
							                }
							                
							                    
							            }
							            
                                        // var_dump($row15);
                                        
						            ?>
						        <!--</div>-->
						    <!--</div>-->
						</div>
					<div class="tab-pane" id="tab_default_6">
					    
					    <?php
					    if($_SESSION["id"]==$profile_id)
					    {
					        $query4="SELECT DISTINCT dateTime as date1,table_type,endorsement_id as id1 FROM pitch_endorsement_request UNION ALL SELECT DISTINCT date_added as date1,table_type,id as id1 from pitch_blog_post UNION ALL SELECT DISTINCT date as date1,table_type,view_id as id1 from pitch_views ORDER BY date1 DESC LIMIT 200";
	        			$result4=$db->query($query4);
	        			$row = $result4->result_array();
	        			// var_dump($row);
	        			$endorsements=array();
	        			
	        			foreach($row as $rows)
	        			{
	        			    
	        			    if($rows["table_type"]=="views")
	        			    {
	        			            $result1 = $db->query('SELECT views.viewer, views.date, user.user_type, user.first_name, user.photo, user.last_name, user.currently_working_for FROM ' . $dbPrefix . 'views as views LEFT JOIN ' . $dbPrefix . 'user as user ON views.viewer = user.id WHERE views.viewed = ' . $_SESSION['id'] . ' AND views.viewer IS NOT NULL and date="'.$rows["date1"].'" ORDER BY views.date DESC LIMIT 1', array());
						            $user_views = $result1->result_array();
						            ?>
						            <div class="row">
						            <?php
						            foreach ($user_views as $view) { 
						            ?>
						            <div class="row" style="margin-bottom:10px;">
						            <div class="p_profile_img_inn img-responsive" style="background-image: url(<?= playerImageCheck( $view['photo']) ?>);width:50px;height:50px;">
		                            </div>
						            <div class="col-xs-9 col-sm-9">
						            <div class="row">
                                        <a href="<?= getLink('profile.php', 'profile_id='.$view['viewer']) ?>"><?= $view['first_name'] ?> <?= $view['last_name'] ?></a> viewed your profile<form method="POST" style="margin-bottom:5px;display:inline;">
                                                
                                                <input type="hidden" value='<?php echo $view['viewer']; ?>' name="viewer_id">
                                                <input type="hidden" value='<?php echo $_SESSION['id']; ?>' name="viewed">
                                                <input type="hidden" value='<?php echo $rows['date1']; ?>' name="date">
                                                <input type="submit" value="Delete?" class="btn-sm btn-success" name="deleteNotification" style="float:right;">
                                            </form><br> <p><?= time_elapsed_string($rows['date1']) ?></p>
                                        
                                    </div>
                                    </div>
                                        </div>
                                    <?php
						            }
						            
						            ?>
						            </div><?php
	        			    }
	        			    if($rows["table_type"]=="requests")
	        			    {
	        			        
	        			            $done=false;
	        			            // echo $rows["date1"];
	        			            $time=$rows["date1"];
	        			            $coach_id=$_SESSION["id"];
	        			            $endid=$rows["id1"];
							    $query2="SELECT * from pitch_endorsement_request where dateTime='$time' and done=0 and coach_id=$coach_id and endorsement_id=$endid LIMIT 1";
							 //   echo $query2."<br>";
							    $result2=$db->query($query2);
							    $row2=$result2->result_array();
							 //   var_dump($row2);
							    foreach($row2 as $rows2)
							    {
							     //   if($rows2["done"]==0 and $_SESSION["id"]==$rows2["coach_id"])
							     //   {
							        $done=false;
							        $name='';
							        $endorsement_id=$rows2['endorsement_id'];
							        $endorsements[]=$endorsement_id;
							        if(in_array($endorsement_id, $endorsements))
							        {
							     //   echo $endorsement_id;
							        $user_id=$rows2['user_id'];
							        $query3="SELECT * from pitch_user where id=$user_id";
							        $result3=$db->query($query3);
							        $row3=$result3->result_array();
							        
							        foreach($row3 as $rows3)
							        {
							            $query4="SELECT endo.user_id,endorsment_user_id,pitch_endorsement_type.endorsement_name,pitch_endorsement_type.id FROM `pitch_endorsement` as endo LEFT join pitch_endorsement_type on endo.endorsement_id=pitch_endorsement_type.id where endo.user_id=$user_id and pitch_endorsement_type.id=$endorsement_id";
							            $result4=$db->query($query4);
							            $row4=$result4->result_array();
							         //   var_dump($row4);
							            foreach($row4 as $rows4)
							            {
							                $name=$rows4["endorsement_name"];
							                $coaches=explode(",",$rows4["endorsment_user_id"]);
							                $i=0;
							                while($i<sizeof($coachies))
							                {
							                    if($_SESSION['id']==$coaches[$i])
							                    {
							                        $done=true;                    
							                        
							                    }
							                    $i++;
							                }
							            }
							            if(!$done)
							            {
							             //   echo $name."here123";
							                if($name=="" or $name=='')
							                {
							                 //   echo "here";
							                    $query6="SELECT endorsement_name from pitch_endorsement_type where id=$endorsement_id";
							                    $result6=$db->query($query6);
							                    $row6=$result6->result_array();
							                 //   var_dump($row6);
							                    foreach($row6 as $rows6)
							                    {
							                     //   echo "here";
							                     //echo $rows6["endorsement_name"];
							                        $name=$rows6["endorsement_name"];
							                    }
							                }
    							        ?>
    							        
    						            <!--<div class="col-md-3 " style="height:auto;">-->
    						            <div class="row">
    						            <div class="p_profile_img_inn img-responsive" style="background-image: url(<?= playerImageCheck( $rows3['photo']) ?>);width:50px;height:50px;display:inline;">
    		                            </div>
    						           <div class="col-xs-9 col-sm-9">
    						               <div class="row">
                                           <a href="<?= getLink('profile.php', 'profile_id='.$rows3['id']) ?>"><?= $rows3['first_name'] ?> <?= $rows3['last_name'] ?></a> Requested Endorsement on <?php echo $name; ?><form method="POST" style="margin-bottom:5px;display:inline;">
                                                <input type="hidden" value='<?php echo $user_id; ?>' name="user_id">
                                                <input type="hidden" value='<?php echo $endorsement_id; ?>' name="endorsement_id">
                                                <input type="submit" value="Delete?" class="btn-sm btn-success" name="deleteEndorse" style="float:right;margin-right: -13px;">
                                                <input type="submit" value="Endorse?" class="btn-sm btn-success" name="endorse" style="float:right;margin-right: 5px;">
                                                
                                            </form><br><p><?= time_elapsed_string($rows['date1']) ?></p>
                                            
                                       </div>
                                       </div>
                                       </div>
                                       
                                        <!--</div>-->
                                            
                                            
                                        <?php
							            }
							        }
							 //   }
							    }
							    }
	        			    }
	        			    if($rows["table_type"]=="blog")
	        			    {
	        			        $date=$rows["date1"];
	        			        $player_id=$_SESSION["id"];
	        			        $query2="SELECT * FROM pitch_blog_post where status=1 and date_added='$date' and deleted_blogs NOT LIKE '%$player_id%'";
                                if (!$result2=$db->query($query2)) {
                                    echo("Error description: " . $db -> error);
                                }
                                $row2=$result2->result_array();
                                foreach($row2 as $rows2)
                                {
                                    $author=$rows2["author"];
                                    $title=$rows2["title"];
                                    $blog_id=$rows2["id"];
                                    $linkoh="'./uploads/user/Social_Media_Socialmedia_network_share_socialnetwork_network-22-512 (1).png'";
                                    echo "<div class='row'>";
                                    ?>
                                     <div class='p_profile_img_inn img-responsive' style='background-image: url("./uploads/user/WhatsApp Image 2021-02-10 at 11.40.53 AM.jpeg");width:50px;height:50px;'>
		                            </div>
                                    <div class="col-xs-9 col-sm-9">
                                        <div class="row">
                                    A new blog has been published by <?php echo $author ?>, title <?php echo $title ?>, <a href='./blog_detail.php?blog_id=$blog_id'>Click here to read more</a>
                                    
                               
                              <form method="POST" style="
    margin-bottom: 5px;
    display: inline;
">
                                    
                                    <input type="hidden" name="blog_id" value="<?php echo $blog_id ?>">
                                <input type="hidden" name="player_id" value="<?php echo $_SESSION["id"] ?>">
                                <button type="submit" name="deleteBlog" value="delVal" class="btn-sm btn-success" style="float: right;margin-right:-12px">Delete?</button></form>
                                <br><?= time_elapsed_string($rows['date1']) ?>
                                    </div>
                                    </div></div>
                                    <?php
                                }
	        			    }
	        			    
	        			}
				// 		var_dump($user_views2);
				// 		var_dump($user_views3);
				// 		var_dump($user_views);
				// 		var_dump($user_views2);
				// 		var_dump($user_views3);
				// 		$combined_array=array();
				// 		$combined_array1=array();
				// 		$combined_array2=array();
				// 		$combined_array[]=$user_views;
				// 		$combined_array[]=$user_views2;
				// 		$combined_array[]=$user_views3;
				// 		var_dump($combined_array);
				        // $i=0;
                        // $cars["test"][0]=5;
				        // foreach($user_views as $rows)
				        // {
				            
				        //     // $combined_array["id"][$i] = $rows["viewer"];
				        //     $combined_array["view_id"][$i] = $rows["viewer"];
				        //     $combined_array['photo'][$i] = $rows["photo"];
				        //     $combined_array['first_name'][$i] = $rows["first_name"];
				        //     $combined_array['last_name'][$i] = $rows["last_name"];
				        //     $combined_array['viewed'][$i] = $rows["viewed"];
				        //     $combined_array['viewer'][$i] = $rows["viewer"];
				        //     $combined_array['dateTime'][$i] = $rows["date"];
				        //     $i++;
				        // }
				        // // $j=0;
				        // foreach($user_views2 as $rows2)
				        // {
				        //     // var_dump($rows2);
				        //     // $combined_array["id"][$i] = $rows["viewer"];
				        //     $combined_array["id"][$i] = $rows2["id"];
				        //     $combined_array['endorsement_id'][$i] = $rows2["endorsement_id"];
				        //     $combined_array['user_id'][$i] = $rows2["user_id"];
				        //     $combined_array['coach_id'][$i] = $rows2["coach_id"];
				        //     $combined_array['done'][$i] = $rows2["done"];
				        //     $combined_array['viewed'][$i] = $rows2["viewed"];
				        //     $combined_array['dateTime'][$i] = $rows2["dateTime"];
				        //     $i++;
				        // }
				        // // $i=0;
				        // foreach($user_views3 as $rows1)
				        // {
				            
				        //     // $combined_array["id"][$i] = $rows["viewer"];
				        //     $combined_array["id"][$i] = $rows1["id"];
				        //     $combined_array['title'][$i] = $rows1["title"];
				        //     // $combined_array['description'][$i] = $rows["description"];
				        //     $combined_array['author'][$i] = $rows1["author"];
				        //     $combined_array['status'][$i] = $rows1["status"];
				        //     // $combined_array['viewed'][$i] = $rows["viewed"];
				        //     $combined_array['dateTime'][$i] = $rows["dateTime"];
				        //     $i++;
				        // }
				        // function date_compare($element1, $element2) { 
            //                 $datetime1 = strtotime($element1['dateTime']); 
            //                 $datetime2 = strtotime($element2['dateTime']); 
            //                 return $datetime1 - $datetime2; 
            //             }  
                          
            //             // Sort the array  
            //             usort($combined_array, 'date_compare'); 
            //             var_dump($combined_array);
                        // var_dump($combined_array);
				        // print_r($combined_array);
				        // foreach($combined_array as $key => $value) {
            //               echo "$key is at ";
            //               foreach($value as $key1=> $value1)
            //               {
            //                   echo "$key1 is at $value1";
            //               }
            //               echo "<br>";
            //             }
                        // foreach($combined_array as $combined_arrays)
                        // {
                        //     foreach($combined_arrays as $key => $value)
                        //     {
                        //         // echo $value;
                        //         // echo "$key is at $value<br>";
                                
                        //     }
                        // }
                        // foreach($combined_array1 as $key => $value) {
                        //   echo "$key is at ";
                        //   var_dump($value);
                        //   echo "<br>";
                        // }
                        // foreach($combined_array2 as $key => $value) {
                        //   echo "$key is at ";
                        //   var_dump($value);
                        //   echo "<br>";
                        // }    
					    }
					   // $query2="SELECT * FROM pitch_blog_post where status=1";
					   // $query3="SELECT * from pitch_endorsement_request ORDER BY id DESC";
					   // $result1 = $db->query('SELECT views.viewer, views.date, user.user_type, user.first_name, user.photo, user.last_name, user.currently_working_for FROM ' . $dbPrefix . 'views as views LEFT JOIN ' . $dbPrefix . 'user as user ON views.viewer = user.id WHERE views.viewed = ' . $_SESSION['id'] . ' AND views.viewer IS NOT NULL ORDER BY views.date DESC', array());
				// 		$user_views = $result1->result_array();
				// 		$result2 = $db->query($query2);
				// 		$user_views3 = $result2->result_array();
				// 		$result3 = $db->query($query3);
				// 		$user_views2 = $result3->result_array();
	        			
					    ?>
					    </div>
					</div>
				</div>
			</div></div></div></div>
<div class="p_profile_dtl">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-3 player_info">
                <?php if($sendRequest) { ?>
                    <form action="<?php echo getLink('phpajax/send_validate_request.php', '', true); ?>" name="form-validate-request" id="form-validate-request" method="POST" data-type="single">
                        <p>&nbsp;</p>
                        <input type="hidden" name="coach[]" value="<?php echo $profile_id; ?>">
                        <!--<button type="submit" class="btn btn-success">Send A Score Validation Request</button>-->
                    </form>
                <?php } ?>
            </div>

            <div class="col-xs-12 col-sm-9 ply_abt">
            
                
              
   
			

			

		
			
			
	

            
        </div>
    </div>
</div>
</div>
<style>

@media only screen and (max-width: 552px) {
 ul.nav.nav-tabs {
    text-align: center;
    display: grid;
}
.tabbable-line > .nav-tabs > li.active {
    margin: 0 auto;
}
}
   
/* Default mode */
.tabbable-line > .nav-tabs {
  border: none;
  margin: 0px;
}
.tabbable-line > .nav-tabs > li {
  margin-right: 2px;
}
.tabbable-line > .nav-tabs > li > a {
  border: 0;
  margin-right: 0;
  color: #737373;
  
    font-size: 20px;
    font-weight: 500;
    line-height: 30px;
}
.tabbable-line > .nav-tabs > li > a > i {
  color: #a6a6a6;
}
.tabbable-line > .nav-tabs > li.open, .tabbable-line > .nav-tabs > li:hover {
  border-bottom: 4px solid   #3aaa35;
}
.tabbable-line > .nav-tabs > li.open > a, .tabbable-line > .nav-tabs > li:hover > a {
  border: 0;
  background: none !important;
  color: #333333;
}
.tabbable-line > .nav-tabs > li.open > a > i, .tabbable-line > .nav-tabs > li:hover > a > i {
  color: #a6a6a6;
}
.tabbable-line > .nav-tabs > li.open .dropdown-menu, .tabbable-line > .nav-tabs > li:hover .dropdown-menu {
  margin-top: 0px;
}
.tabbable-line > .nav-tabs > li.active {
  border-bottom: 4px solid #3aaa35;
  position: relative;
}
.tabbable-line > .nav-tabs > li.active > a {
  border: 0;
  color: #333333;
}
.tabbable-line > .nav-tabs > li.active > a > i {
  color: #404040;
}
.tabbable-line > .tab-content {
  margin-top: -3px;
  background-color: #fff;
  border: 0;
  border-top: 1px solid #eee;
  padding: 15px 0;
}
.portlet .tabbable-line > .tab-content {
  padding-bottom: 0;
}

/* Below tabs mode */

.tabbable-line.tabs-below > .nav-tabs > li {
  border-top: 4px solid transparent;
}
.tabbable-line.tabs-below > .nav-tabs > li > a {
  margin-top: 0;
}
.tabbable-line.tabs-below > .nav-tabs > li:hover {
  border-bottom: 0;
  border-top: 4px solid #fbcdcf;
}
.tabbable-line.tabs-below > .nav-tabs > li.active {
  margin-bottom: -2px;
  border-bottom: 0;
  border-top: 4px solid #f3565d;
}
.tabbable-line.tabs-below > .tab-content {
  margin-top: -10px;
  border-top: 0;
  border-bottom: 1px solid #eee;
  padding-bottom: 15px;
}
</style>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
<?php
if(isset($_POST["alertbuttonClicked"]))
            {
                
                ?>
                <script>$(".tabbable-line  a[href='#tab_default_6']").tab("show");
                document.getElementById("alertbuttonClicked").style.display="none"
                </script>
                <?php
            }
            if($user_views<=0)
            {
                ?>
                <script>document.getElementById("alertbuttonClicked").style.display="none";</script>
                <?php
            }
if(isset($_POST["endorse"]))
{
    ?>
                <script>$(".tabbable-line  a[href='#tab_default_6']").tab("show");
                
                </script>
                <?php
}
if(isset($_POST["deleteEndorse"]))
{
    ?>
                <script>$(".tabbable-line  a[href='#tab_default_6']").tab("show");
                
                </script>
                <?php
}
if(isset($_POST["deleteValidation"]))
{
    ?>
                <script>$(".tabbable-line  a[href='#tab_default_4']").tab("show");
                
                </script>
                <?php
}
if($profile_id==$_SESSION['id'])
{
    $result = $db->query('UPDATE ' . $dbPrefix . 'views SET new = 0 WHERE viewed = ' . $_SESSION['id'] . '', array());
    $result2 = $db->query('UPDATE ' . $dbPrefix . 'score_validation SET viewed = 1 WHERE coach_id = ' . $_SESSION['id'] . '', array());
    $result3 = $db->query('UPDATE ' . $dbPrefix . 'blog_post SET viewed_users = CONCAT(viewed_users,",","'.$profile_id.'") where viewed_users NOT LIKE "%'.$profile_id.'%"', array());
    $result1 = $db->query('UPDATE ' . $dbPrefix . 'endorsement_request SET viewed = 0 WHERE coach_id='.$_SESSION["id"], array());
}
?>