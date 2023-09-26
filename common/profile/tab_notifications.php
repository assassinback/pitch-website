<style>
   .special-Div {
    display: inline-flex;
    width: 100%;
}
p.speacial-Paragraph {
    margin: 30px;
}
.speacial-delete-Button {
       transform: translate(16px, -26px);
}
.row{
        margin-bottom: 5px;
}
.img-responsive{
    width:50px !important;
    height:50px !important;
}
.p_profile_img_inn.img-responsive {
    border-radius: 50%;
}
</style>
<?php

//$result = $db->query('SELECT * FROM ' . $dbPrefix . 'views as views LEFT JOIN ' . $dbPrefix . 'users as users ON views.viewer = user.id WHERE views.viewed = ' . $_SESSION['id'] . ' ORDER BY views.date ASC', array());
$endorsements=array();

$user_views = $result->result_array();

$plan = $db->query('SELECT test_plan.*, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan INNER JOIN ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_id = ?', array($profile_id));
$planInfo = $plan->row_array();

// views seen
$result = $db->query('UPDATE ' . $dbPrefix . 'views SET new = 0 WHERE viewed = ' . $_SESSION['id'] . '', array());
if(isset($_POST["deleteEndorse"]))
{
    $endorsement_id=$_POST["endorsement_id"];
    $player_id=$_SESSION["id"];
    $coach_id=$_POST["coach_id"];
    $query="UPDATE pitch_endorsement set deleted=1 where endorsement_id=$endorsement_id and user_id=$player_id and endorsment_user_id LIKE '%$coach_id%'";
    $deleteEndorse=$db->query($query);
}
if(isset($_POST["deleteNotification"]))
{
    $player_id=$_SESSION["id"];
    $coach_id=$_POST["coach_id"];
    $deleteDate=$_POST["deleteDate"];
    $query="DELETE FROM pitch_views where viewer=$coach_id and viewed=$player_id and date='$deleteDate'";
    $deleteEndorse=$db->query($query);
}
if(isset($_POST["deleteValidation"]))
{
    $player_id=$_SESSION["id"];
    $coach_id=$_POST["coach_id"];
    $deleteDate=$_POST["deleteDate"];
    // echo $player_id."<br>".$coach_id."<br>".$deleteDate; 
    $query="DELETE FROM pitch_views where viewer=$coach_id and viewed=$player_id and date='$deleteDate'";
    if (!$result2=$db->query($query)) {
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
?>

<?php
// print_r($user_views);
?>

<div class="col-xs-12 col-sm-9 ply_abt">
    <?php
    $player_id=$_SESSION["id"];
        $query5="SELECT DISTINCT dateTime as date1,table_type,endorsement_id as id1 FROM pitch_endorsement UNION ALL SELECT DISTINCT  date_added as date1,table_type,id as id1 from pitch_blog_post UNION ALL SELECT DISTINCT  date as date1,table_type,view_id as id1 from pitch_views ORDER BY date1 DESC";
        $result5=$db->query($query5);
	        			$row = $result5->result_array();
	        			foreach($row as $rows)
	        			{
	        			    if($rows['table_type']=='blog')
	        			    {
	        			        $date=$rows['date1'];
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
                ?>
                <!--zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz-->
                <div class="row activity">
                     <div class="p_profile_img_inn img-responsive" style="background-image: url('./uploads/user/WhatsApp Image 2021-02-10 at 11.40.53 AM.jpeg'); background-size:100% 100%;">
		    </div>
                    
                <?php 
                echo "
                <div class='col-xs-9 col-sm-9 zicls'><div class='row'><div class='zicls'>
               <span class='myClass'>A new blog has been published by $author, title $title, <br><a href='./blog_detail.php?blog_id=$blog_id'>Click here to read more</a></span></div>"; 
                ?>
                
                               <form method="POST" style="display: inline;"><input type="hidden" name="blog_id" value="<?php echo $blog_id ?>">
                                <input type="hidden" name="player_id" value="<?php echo $_SESSION["id"] ?>">
                                
                                <button type="submit" name="deleteBlog" value="delVal" class="btn-sm btn-success del-btn">Delete?</button></form>
                                <p><?= time_elapsed_string($rows['date1']) ?></p>
                </div></div></div><?php
                                
            }
	        			    }
	        			    if($rows["table_type"]=='validation')
	        			    {
	        			        
	        			        $view_id=$rows["id1"];
	        			        $query="SELECT * FROM pitch_views where view_id=$view_id";
	        			        $result=$db->query($query);
	        			        $view=$result->result_array();
	        			        foreach($view as $views)
	        			        {
	        			            $coach_id=$views["viewer"];
	        			            // echo "<script>alert(1);</script>";
	        			            $query2="SELECT * FROM pitch_user where id=$coach_id";
	        			            $result2=$db->query($query2);
	        			            $row2=$result2->result_array();
	        			            foreach($row2 as $rows2)
	        			            {
	        			                // var_dump($rows2);
	        			                ?>
	        			                <div class="row activity">
	        			                    <div class="p_profile_img_inn img-responsive" style="background-image: url('./uploads/user/V.png');background-size:100% 100%;">
		    </div>
	        			                
	        			                <?php
	        			                $coach_name=$rows2["first_name"]." ".$rows2["last_name"];
	        			                echo "<div class='col-xs-9 col-sm-9 zicls'><div class='row'><p class='speacial-Paragraph'>You have Been Validated By $coach_name";
	        			                
	        			                ?>
	        			                <br><?= time_elapsed_string($rows['date1']) ?>
	        			                </p>
	        			                <div class="speacial-delete-Button">
                                <form method="POST"><input type="hidden" name="coach_id" value="<?php echo $views['viewer'] ?>">
                                <input type="hidden" name="player_id" value="<?php echo $_SESSION["id"] ?>">
                                <input type="hidden" name="deleteDate" value="<?php echo $rows["date1"] ?>">
                                <button type="submit" name="deleteValidation" value="delVal" class="btn-sm btn-success del-btn">Delete?</button></form></div>
            
	        			                </div></div>
	        			                </div>
	        			                <?php
	        			            }
	        			            
	        			        }
	        			        
	        			        
	        			    }
	        			    if($rows['table_type']=='views')
	        			    {
	        			        $date=$rows["date1"];
	        			        $result = $db->query('SELECT views.viewer, views.date, user.user_type, user.first_name, user.photo, user.last_name, user.currently_working_for FROM pitch_views as views LEFT JOIN pitch_user as user ON views.viewer = user.id WHERE views.viewed = '.$_SESSION["id"].' AND views.viewer IS NOT NULL and date="'.$date.'" ORDER BY views.date DESC', array());
	        			        $user_views = $result->result_array();
	        			        foreach ($user_views as $key => $view) { 
        if ($key > 9 || $view['viewer'] == null) {  
            continue;
        }else if($key !== 0 && $view['viewer'] === $user_views[($key -1)]['viewer']){

            $date1 = new DateTime($view['date']);
            $date2 = new DateTime($user_views[($key -1)]['date']);
            $diff = $date1->diff($date2);

            if($diff->i < 5){
                continue;
            }
        }
    ?>
    

    <div class="row activity">
            <div class="p_profile_img_inn img-responsive" style="background-image: url(<?= playerImageCheck($planInfo['test_plan_id'] > 1 ? $view['photo']: null) ?>);">
		    </div>
            <div class="col-xs-9 col-sm-9 zicls">
                <?php if($planInfo['test_plan_id'] > 1){ ?>
                <div class="row">
                    <div class="zicls">
                    <a href="<?= getLink('profile.php', 'profile_id='.$view['viewer']) ?>"><?= $view['first_name'] ?> <?= $view['last_name'] ?></a> viewed your profile
                     </div>
               
                <?php } else { ?>
                <div class="row">
                    <p class="speacial-Paragraph">Someone viewed your profile, upgrade your plan <a href="<?= getLink('profile.php', 'tab=plan'); ?>">here</a> to find out who<br>
                    <?php echo time_elapsed_string($view['date']); ?></p>
                </div>
                <?php } ?>
                <?php if(($planInfo['test_plan_id'] > 1 && ($view['user_type'] == 2 || $view['user_type'] == 3 || $view['user_type'] == 1)) && $view['currently_working_for'] != null && $planInfo['test_plan_id'] > 0){ ?>
                <!--<div class="row">-->
                    <!--<p class="speacial-Paragraph">Currently works for -->
                    
                    <!--</p><br>-->
                    
                <!--</div>-->
                <?php } ?>
                <!--<div class="row">-->
                    <!--<p class="speacial-Paragraph"></p>-->
                <!--</div>-->
               
                                <form method="POST" style=" display: inline;"><input type="hidden" name="coach_id" value="<?php echo $view['viewer'] ?>">
                                <input type="hidden" name="player_id" value="<?php echo $_SESSION["id"] ?>">
                                <input type="hidden" name="deleteDate" value="<?php echo $rows["date1"] ?>">
                                <button type="submit" name="deleteNotification" value="delVal" class="btn-sm btn-success del-btn">Delete?</button></form>
                                <br>
                                <p><?php echo time_elapsed_string($view['date']); ?></p>
            </div> </div>
    </div>
    
    <?php } ?>
    <div class="hidden-notifications">
    <?php foreach ($user_views as $key => $view) { 
        if ($key < 9 || $view['viewer'] == null) { 
            continue;
        } else if($key !== 0 && $view['viewer'] === $user_views[($key -1)]['viewer']){

            $date1 = new DateTime($view['date']);
            $date2 = new DateTime($user_views[($key -1)]['date']);
            $diff = $date1->diff($date2);

            if($diff->i < 5 && $diff->days == 0 && $diff->h == 0){
                continue;
            }
        }
    ?>

    <div class="row activity more">
    <div class="p_profile_img_inn img-responsive" style="background-image: url(<?= playerImageCheck($planInfo['test_plan_id'] > 1 ? $view['photo']: null) ?>);">
		    </div>
            <div class="col-xs-9 col-sm-9 zicls">
            <?php if($planInfo['test_plan_id'] > 1){ ?>
                <div class="row">
                    <div class="zicls">
                    <a href="<?= getLink('profile.php', 'profile_id='.$view['viewer']) ?>"><?= $view['first_name'] ?> <?= $view['last_name'] ?></a> viewed your profile
               </div>
                <?php } else { ?>
                <div class="row">
                    <p class="speacial-Paragraph">Someone viewed your profile, upgrade your plan <a href="<?= getLink('profile.php', 'tab=plan'); ?>">here</a> to find out who<br>
                    <?php echo time_elapsed_string($view['date']); ?></p>
                </div>
                
                <?php } if(($planInfo['test_plan_id'] > 1 && ($view['user_type'] == 2 || $view['user_type'] == 3 || $view['user_type'] == 1)) && $view['currently_working_for'] != null && $planInfo['test_plan_id'] > 0){ ?>
                <!--<div class="row">-->
            <!--<p class="speacial-Paragraph">Currently works for </p>-->
                
                <!--</div>-->
                <?php } ?>
                <div class="row">
                    <!--<p class="speacial-Paragraph"></p>-->
                </div>
              
                                <form method="POST"><input type="hidden" name="coach_id" value="<?php echo $view['viewer'] ?>">
                                <input type="hidden" name="player_id" value="<?php echo $_SESSION["id"] ?>">
                                <input type="hidden" name="deleteDate" value="<?php echo $rows["date1"] ?>">
                                
                                <button type="submit" name="deleteNotification" value="delVal" class="btn-sm btn-success del-btn">Delete?</button></form>
                                <br><p><?php echo time_elapsed_string($view['date']); ?></p>
                             
            </div> </div>
    </div>
    
    <?php } ?>
    </div>
    <?php if(count($user_views) > 9){ ?>
    <input type="button" class="load-more" value="Load More">
    <div class="stj_loader loader" style="display: none;">Loading...</div>
    <?php } ?>

	        <?php			    }
	        			    if($rows['table_type']=='endorse')
	        			    {
	        			        	
	        			         $date=$rows['date1'];
	        			        $query="SELECT * FROM pitch_endorsement where user_id=".$_SESSION['id']." and dateTime='$date' and deleted=0";
                    if (!$result=$db->query($query)) {
                            echo("Error description: " . $db -> error);
                    }
                    $row5=$result->result_array();
                    
                    foreach ($row5 as $rows5)
                    {
                        
                       $endorsement_id=$rows5['endorsement_id'];
					     if(!in_array($endorsement_id, $endorsements))
							        {
							             
					    $endorsements[]=$endorsement_id;
                        $image="";
                        $tests=explode(',',$rows5["endorsment_user_id"]);
                        $coach_id=$tests[sizeof($tests)-1];
                        $query2="SELECT * from pitch_user where id=$coach_id";
                        if (!$result2=$db->query($query2)) {
                            echo("Error description: " . $db -> error);
                        } 
                        $row2=$result2->result_array();
                        foreach ($row2 as $rows2)
                        {
                            $name=$rows2["first_name"]." ".$rows2["last_name"];
                            $endorsement_id=$rows5["endorsement_id"];
                            // echo $endorsement_id;
                            $query3="SELECT * FROM pitch_endorsement_type where id=$endorsement_id";
                            if (!$result3=$db->query($query3)) {
                            echo("Error description: " . $db -> error);
                            } 
                            $row3=$result3->result_array();
                            foreach($row3 as $rows3)
                            {
                                $endorsement_name=$rows3["endorsement_name"];
                                ?>
                                <div class="row activity">
                                    <div class="p_profile_img_inn img-responsive" style="background-image: url('./uploads/user/E.png');background-size:100% 100%;">
		    </div>
                                <?php
                                // echo "<img class='img-responsive' src='".playerImageCheck($rows2['photo'])."' style='display:inline;width:7em;height:7em;'>
                                echo "
                                <div class='col-xs-9 col-sm-9 zicls'><div class='row'><div class='zicls'>
                             You have been Endorsed By <a href='./profile.php?profile_id=$coach_id'>$name</a> On $endorsement_name </div>";         
                                
                                ?>
                                <style>
                                    
                                    button.btn-sm.btn-success.del-btn {
                                                                        margin-top: -15px;
                                                                       }
                                    
                                    @media only screen and (max-width: 768px) {
  .zicls {
    width: 75%;
  }
 button.btn-sm.btn-success.del-btn {
    margin-top: -36px;
}
}



                                </style>
                               
                                
                             
                                <!--zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz-->
                              
                                <form method="POST" style="display: inline; margin-top: -15px;" class="btn-fr"><input type="hidden" name="coach_id" value="<?php echo $coach_id ?>">
                                <input type="hidden" name="player_id" value="<?php echo $_SESSION["id"] ?>">
                                <input type="hidden" name="endorsement_id" value="<?php echo $endorsement_id ?>">
                                <button type="submit" style="" name="deleteEndorse" value="delVal" class="btn-sm btn-success del-btn">Delete?</button></form>
                                <?= time_elapsed_string($rows['date1']) ?>
                                </div></div></div><?php
                            }
                        }   
                    }
                    }
	        			    }
	        			}
            //viewed_users
            
        ?>
    </div>
   
<script>
$('.load-more').on('click', function(e) {
    var hidden = $('.hidden-notifications > .activity:not(.show)');
    console.log(hidden.length);
    for (let index = 0; index < 10; index++) {
        if((hidden.length) > index){
            $(hidden[index]).addClass('show');
        }
    }
    var remain = $('.hidden-notifications > .activity:not(.show)');
    if(remain.length == 0){
        $('.load-more').css('display', 'none');
    }
});
</script>
<style>
    button.btn-sm.btn-success {
    float: right;
}
</style>
<?php

if($profile_id==$_SESSION['id'])
    {
        // $result = $db->query('UPDATE ' . $dbPrefix . 'views SET new = 0 WHERE viewed = ' . $_SESSION['id'] . '', array());
        // $result2 = $db->query('UPDATE ' . $dbPrefix . 'score_validation SET viewed = 1 WHERE coach_id = ' . $_SESSION['id'] . '', array());
        // $result3 = $db->query('UPDATE ' . $dbPrefix . 'blog_post SET viewed_users = CONCAT(viewed_users,",","'.$profile_id.'")', array());
        $result1 = $db->query('UPDATE ' . $dbPrefix . 'endorsement SET viewed = 0 WHERE user_id='.$_SESSION["id"], array());
        // $result1 = $db->query('UPDATE ' . $dbPrefix . 'endorsement_request SET viewed = 0 WHERE coach_id='.$_SESSION["id"], array());
    }
?>