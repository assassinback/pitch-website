<?php

$result = $db->query('SELECT test_plan.*, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan LEFT JOIN  ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_test_plan.user_id = ?', array($profile_id));
$userPlanInfo = $result->row_array();

$allowed_test = explode(",", $userPlanInfo['allowed_test']);
//$tests = $db->query('SELECT user_score.*, test.* FROM ' . $dbPrefix . 'test as test LEFT JOIN  ' . $dbPrefix . 'user_test_score as user_score ON (user_score.test_id = test.id AND user_score.user_id = ?) WHERE test.status = 1 and user_score.available=1 ORDER BY test.sort_order ASC', array($profile_id));
$tests = $db->query('SELECT user_score.*, test.* FROM ' . $dbPrefix . 'test as test LEFT JOIN  ' . $dbPrefix . 'user_test_score as user_score ON (user_score.test_id = test.id AND user_score.user_id = ?) WHERE test.status = 1 ORDER BY test.sort_order ASC', array($profile_id));
$tests = $tests->result_array();

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND user_type = 2', array());
$coaches = $result->result_array();

if ((isset($_SESSION['id']) && $profile_id != $_SESSION['id']) && (isset($_SESSION['user_type']) && $_SESSION['user_type'] = 2)) {
    $allow_validate = false;
    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'score_validation WHERE status = 0 AND coach_id = ? AND player_id = ?', array($_SESSION['id'], $profile_id));
    if ($result->num_rows() > 0) {
        $allow_validate = true;
    }
} else {
    $allow_validate = false;
}

if ($userPlanInfo['test_plan_id'] == 1) {
    $test_message = 'Please subscribe to either Silver or Gold packages to access these tests';
} else if ($userPlanInfo['test_plan_id'] == 2) {
    $test_message = 'Please subscribe to Gold packages to access these tests';
} else {
    $test_message = '';
}
$testList = array();
foreach ($tests as $test) {
    $testList[$test['id']] = $test['title'];
}
$plans = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan WHERE status = 1', array());
$plans = $plans->result_array();

// print_r($tests);

?>
<?php
    $user_id=$_SESSION["id"];
    $query3="SELECT validation_request_date FROM pitch_user where id=$user_id";
    $result3=$db->query($query3);
    $row3=$result3->result_array();
    foreach($row3 as $rows3)
    {
        
        if(strtotime($rows3["validation_request_date"]) < strtotime('-30 days'))
        {
         // this is true
        //  echo strtotime('-30 days')."<br>";
        //  echo strtotime($rows3["validation_request_date"])."<br>";
            // echo date("Y-m-d");
         $dateforvalid = date("Y-m-d H:i:s");
        //  echo $dateforvalid; 
         $user_id=$_SESSION["id"];
         $query4="UPDATE pitch_user set validation_request_date='$dateforvalid', validation_request_count=1 where id=$user_id";
         if($userPlanInfo['test_plan_id']==3)
         {
             $query4="UPDATE pitch_user set validation_request_date='$dateforvalid', validation_request_count=5 where id=$user_id";
         }
         if($userPlanInfo['test_plan_id']==2)
         {
             $query4="UPDATE pitch_user set validation_request_date='$dateforvalid', validation_request_count=3 where id=$user_id";
         }
         
         if (!$result17=$db->query($query4)) {
            echo("Error description: " . $db -> error);
            }
        }
    }
?>
    <div id="ziaId">
    <?php if ($allow_edit and ($userPlanInfo['test_plan_id']==2 or $userPlanInfo['test_plan_id']==3  or $userPlanInfo['test_plan_id']==1)) { ?>
        <p>&nbsp;</p>
        <p>Your test results needed to be validated by either a Coach or by our Sport Science Validation System</p>
        <p><a href="<?php echo getLink('plan.php', 'category=validation-session-plan'); ?>" class="link-red">Click here</a> to buy a Sport Science Validation plan from us</p>
        <p><a href="javascript:" class="link-red" data-toggle="modal" data-target="#requestModal">Click here</a> to send request to a Coach to validate score</p>
        <p><b>(OR)</b></p>
        <p>Enter email address of Coach below to invite him/her if he/she is not listed on website</p>
        
        <form action="<?php echo getLink('phpajax/invite.php', '', true); ?>" name="form-invite" id="form-invite" method="POST">
            <div class="form-group" >
                <input type="email" class="form-control" name="invite" required />
            </div>
            <button type="submit" class="btn btn-success">Send Request</button>
        </form>
        
    <?php } ?>
    
    <?php if ($allow_validate) { ?>
        <form action="<?php echo getLink('phpajax/validate_score.php', '', true); ?>" name="form-validate-score" id="form-validate-score" method="POST">
            <p>&nbsp;</p>
            <input type="hidden" name="profile_id" value="<?php echo $profile_id; ?>" >
            <button type="submit" class="btn btn-success">Validate Score</button>
        </form>
    <?php } ?>
    </div>
    <?php
        if ($allow_edit) {
            $player_id=$_SESSION["id"];
            $query="SELECT * FROM pitch_user where id=$player_id";
            if (!$result=$db->query($query)) {
                echo("Error description: " . $db -> error);
            }
            $row=$result->result_array();
            foreach($row as $rows)
            {
                // $date=$rows["score_validated_date"];
                // echo "<script>alert('$date')</script>";
                if($rows["score_validated_date"]!=NULL)
                {
                    $coach_id=$rows["score_validated_by"];
                    if($coach_id!=NULL)
                    {
                        $query2="SELECT * from pitch_user where id=$coach_id";
                        if (!$result2=$db->query($query2)) {
                            echo("Error description: " . $db -> error);
                        }
                        $row2=$result2->result_array();
                        foreach($row2 as $rows2)
                        {
                            $photo=$rows2["photo"];
                            ?>
                            <script>
                                document.getElementById("ziaId").innerHTML="<span class='mycl'><img src='<?php echo playerImageCheck($photo) ?>' width='50' height='50' style='border-radius:50px;'>You Have Been Validated By <a href='./profile.php?profile_id=<?php echo $coach_id ?>'><?php echo $rows2["first_name"]." ".$rows2["last_name"];  ?></a></span>"+document.getElementById("ziaId").innerHTML;
                                // document.getElementById("ziaId").style.display="none";
                            </script>
                            <?php
                        }
                    }   
                    else{
                    ?>
                    <script>
                        document.getElementById("ziaId").innerHTML="You Have Been Validated By Admin"+document.getElementById("ziaId").innerHTML;
                        // document.getElementById("ziaId").style.display="none";
                    </script>
                    
                    <?php
                    }
                }
            }
            
        }
    ?>
	<div class="plan_wrap">
	<?php foreach ($plans as $plan) { ?>
                
					<?php $plan_type = 'month'; ?>
                    <?php $price = $plan[$plan_type . 'ly_price']; ?>
            
                    <div class="plan_wrap_inn plan_wrap_<?php echo strtolower($plan['title']); ?>">
                        <h2><?php echo $plan['title']; ?></h2>
                        <p><?php echo $plan['sub_title']; ?></p>
                        <hr class="plan_hr">
                        <div class="plan_descp">
                            <h3><?php echo $plan['test_title']; ?></h3>
                            <ul>
                                <?php 
								$plan_allowed_test = explode(',', $plan['allowed_test']);
                                foreach ($plan_allowed_test as $test_id) { 
                                    if (isset($testList[$test_id])) { ?>
                                    <li><?php echo $testList[$test_id]; ?></li>
                                <?php } } ?>
                            </ul>
                            <div class="plan_descp_con">
                                
                                <?php 
                                $amenities = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan_amenities as test_plan_amenities INNER JOIN ' . $dbPrefix . 'amenities as amenities ON (amenities.id = test_plan_amenities.amenity_id AND amenities.status = 1) WHERE test_plan_amenities.test_plan_id = ?', array($plan['id']));
                                $amenities = $amenities->result_array();
                                
                                foreach($amenities as $amenity) { ?>
                                    <?php if ($amenity['per_year'] == 1) { ?>
                                        <p><?php echo ucfirst($amenity['quantity']) . ' ' . $amenity['title'] . '/year*'; ?></p>
                                    <?php } else { ?>
                                        <p><?php echo $amenity['title']; ?></p>
                                    <?php } ?>
                                <?php } ?>
                            
                            </div>
                            <hr>
                        </div>
                        <div class="plan_price">
                            <?php if ($price == 0) { ?>
                                <?php echo $price_html = 'FREE'; ?>
                            <?php } else  { ?>
                                <?php echo $price_html = formatPrice($price) . '<span>/' . ucfirst($plan_type) . '</span>'; ?>
                                <?php if ($plan_type == 'year') { ?>
                                    <label>(Save <?php echo formatPrice((($plan['monthly_price'] * 12) - $price)/12); ?> per month)</label>
                                <?php } else { ?>
                                    <label>(<?php echo formatPrice($price * 12); ?> per year)</label>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        
                        <?php if (checkLogin()) { ?>
                            <?php if ($userInfo['user_type'] == 1) { ?>
                                <?php if ($userPlanInfo['test_plan_id'] == $plan['id'] && ($userPlanInfo['type'] == $plan_type || $price == 0)) { ?>
                                    <div class="plan_bn"><a href="javascript:" class="tick" ><i class="fa fa-check" aria-hidden="true"></i></a></div>
                                <?php } else { ?>
                                    <?php /* <form action="" method="post" >
                                        <input type="hidden" name="plan_id" value="<?php echo $plan['id']; ?>" >
                                        <input type="hidden" name="plan_type" value="<?php echo $plan_type; ?>" >
                                        <div class="plan_bn"><a href="javascript:" onclick="$(this).closest('form').submit()">Buy Now</a></div>
                                    </form> */ ?>
                                    <div class="plan_bn"><a href="<?php echo getLink('plan.php');?>" class="plan-purchase">Buy Now</a></div>
                                <?php } ?>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="plan_bn"><a href="<?php echo getLink('login.php'); ?>" >Buy Now</a></div>
                        <?php } ?>
                        
                        <hr class="plan_hr">
                        <p class="description"><?php echo $plan['description']; ?></p>
                    </div>
                    
                <?php } ?>
				</div>
</div>

<div class="col-xs-12 col-sm-9 ply_abt">

    <?php if (isset($success)) { ?>
        <div class="alert alert-success">
            <?php echo "<strong>Success!</strong> " . $success; ?>
        </div>
    <?php } ?>
    
    <div class="ply_ip">
        <div class="ply_ip_inn">
            <h3>My<span>Score</span></h3>
            <hr>
            <div class="p_rank">
                <div class="p_rank_inn">
                    <span class="rank_bl"></span>
                    <p>Player Ranking %</p>
                </div>
                <div class="p_rank_inn">
                    <span class="rank_rd"></span>
                    <p>Optimal Ranking %</p>
                </div>
                <div class="p_rank_inn">
                    <span class="rank_gr"></span>
                    <p>Elite Ranking %</p>
                </div>
            </div>
        </div>
        <div class="ply_ip_inn ply_ip_inn_rgt">
            <img class="p_graph" src="graph.php?user_id=<?php echo $profile_id; ?>" alt=""/>
        </div>
    </div>
    <div class="p_circle_dv">
        <ul>
            <?php foreach ($tests as $key => $test) { ?>
            <li <?php if (!in_array($test['id'], $allowed_test)) { ?> class="disable-test" <?php } ?>>
                <?php if ($allow_edit && $test['require_validation'] == 1 && $test['validated'] == 0 && (in_array($test['id'], $allowed_test))) { ?>
                    <p class='text-danger'>Validation Required</p>
                <?php } else { ?>
                    <p class='text-danger'>&nbsp;</p>
                <?php } ?>
                 
                <div class="p_cd_ldr">
                    <div class="progress-bar position" data-percent="<?php echo ($test['weightage']) ? $test['weightage'] : 0; ?>" data-color="#a456b1,#12b321"></div>
                    <div class="p_cd_img">
                        <img src="images/<?php echo $test['image']; ?>" alt=""/>
                    </div>
                    <?php if ($allow_edit && in_array($test['id'], $allowed_test)) { ?>
                        <a href="<?php echo getLink('test_score.php', 'test_id=' . $test['id']); ?>" ><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    <?php } ?>
                </div>
                <div class="p_cd_con">
                    <h3><?php echo $test['title']; ?></h3>
                    <?php if (in_array($test['id'], $allowed_test)) { ?>
                        <span class="p_per"><?php echo $test['weightage']; ?> %</span>
                        <label><?php echo preg_replace("/\([^)]+\)/", "", $test['label']); ?></label>
                    <?php } else { ?>
                        <span class="p_per">&nbsp;</span>
                        <label>&nbsp;</label>
                    <?php } ?>
                    <h3 class="<?php echo strtolower($test['test_type']); ?>"><?php echo $test['test_type']; ?></h3>
                </div>
                <p class="tooltip"><?php echo $test_message; ?></p>
            </li>
            <?php if ($key%4 == 3) { ?>
                <li class="clearfix visible-md visible-lg" ></li>
            <?php } ?>
            
            <?php if ($key%2 == 1) { ?>
                <li class="clearfix visible-sm" ></li>
            <?php } ?>
            <?php } ?>
        </ul>
    </div>
	
</div>
<?php

?>
<?php if ($allow_edit) { ?>
<div class="modal fade" id="requestModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo getLink('phpajax/send_validate_request.php', '', true); ?>" name="form-validate-request" id="form-validate-request" action="POST" data-type="multiple">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Select Coach</h4>
                </div>
                <div class="modal-body">
                    <p>Select coach to send validate score request</p>
                    <ul style="max-height: 300px; overflow-y: auto;">
                    <?php foreach($coaches as $coach) { ?>
                        <li class="clearfix">
                            <div class="col-xs-3 col-sm-2">
                                <div class="form-group">
                                    <input type="checkbox" class="form-control" name="coach[]" value="<?php echo $coach['id']; ?>"></textarea>
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-2" style="height: 80px;">
                                <a href="<?php echo getLink('profile.php', 'profile_id=' . $coach['id']); ?>" target="_blank"><?php echo getUserProfileImage($coach['photo'], 'coach', 'style="width:60px;height:60px;"'); ?></a>
                            </div>
                            <div class="col-xs-6 col-sm-8">
                                <a href="<?php echo getLink('profile.php', 'profile_id=' . $coach['id']); ?>" target="_blank"><h4><?php echo $coach['first_name'] . ' ' . $coach['last_name']; ?></h4></a>
                                <?php if(isset($coach['club_id'])) { ?>
                                <h4><?php echo getClub($coach['club_id']); ?></h4>
                                <?php } ?>
                            </div>
                        </li>
                    <?php } ?>
                    </ul>
                </div>
                <?php
                if($userPlanInfo['test_plan_id']==2 or $userPlanInfo['test_plan_id']==3)
                {
                ?>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="sendCoach">Send</button>
                </div>
                <?php
                }
                else if($userPlanInfo['test_plan_id']==1)
                {
                    ?>
                        <div class="modal-footer">
                            You Need to subscribe to request validation    
                        </div>
                    <?php
                    
                }
                ?>
            </form>
        </div>
    </div>
</div>
<?php } ?>

<script>
jQuery(document).ready(function($) {
    
    $(".progress-bar").loading();
    
    var owl = $("#owl-demo");
    
    owl.owlCarousel({
        items : 4,
        itemsDesktop : [1000,4],
        itemsDesktopSmall : [900,3],
        itemsTablet: [600,2],
        itemsMobile : [500,1],
        pagination:false
    });
    
    $(".next").click(function(){
        owl.trigger('owl.next');
    })
    
    $(".prev").click(function(){
        owl.trigger('owl.prev');
    })
});	
</script>

