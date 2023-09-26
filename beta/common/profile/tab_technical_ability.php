<?php

$rater_user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

$playerRating = $db->query('SELECT user.*, club.name as club_name FROM ' . $dbPrefix . 'user as user LEFT JOIN ' . $dbPrefix . 'club as club ON club.id = user.team_id WHERE user_type = 1 AND user.status = 1 ORDER BY user_rating DESC LIMIT 3');

$coach_scout_rating = $db->query('SELECT user.*, club.name as club_name FROM ' . $dbPrefix . 'user as user LEFT JOIN ' . $dbPrefix . 'club as club ON club.id = user.team_id WHERE user_type = 2 OR user_type = 3 AND user.status = 1 ORDER BY user_rating DESC LIMIT 3');

$technical_ability_video = $userInfo['technical_ability_video'];

$rating_result = $db->query('SELECT * FROM ' . $dbPrefix . 'user_rating WHERE rater_user_id = ? AND rated_user_id = ?', array($rater_user_id, $profile_id));

$userrate = false;
if ($rating_result->num_rows() > 0) {
    $rateInfo = $rating_result->row_array();
    $userrate = $rateInfo['rating'];
}
?>
    <div class="col-xs-12 col-sm-9 ply_rv ply_tav">
        
        <div class="ply_rv_dv">
        
            <?php if(isset($success_msg)){ ?>
                <div class="alert alert-success">
                    <?php echo $success_msg; ?>
                </div>
            <?php } ?>
            
            <?php if($allow_edit) { ?>
               <p><i>Please upload a 30-60 second video showing all the scouts, managers and coaches what you do best. For instance, if you are a striker and score goals, please show yourself scoring goals in games or training scenarios; or if you are brilliant at tackling please show yourself tackling. This way the scout, manager or coach can see your ability.</i><p>
            <?php } ?>
            
            <?php if ($userInfo['technical_ability_video'] != null) { ?>
                <div class="ply_rv_dv_lft">
                    <iframe src="<?php echo $userInfo['technical_ability_video'] ?>" allow="autoplay; encrypted-media" allowfullscreen="" height="315" frameborder="0" width="560"></iframe>
                </div>
            <?php } ?>
            
            <div class="ply_rv_dv_rgt">
                <?php echo $userInfo['technical_ability_video_content']; ?>
                
                <?php if($allow_edit) { ?>
                    <p><a href="<?php echo getLink('editprofile.php', 'type=technical_ability_video'); ?>" class="edit-link"><i class="fa fa-pencil" aria-hidden="true"></i></a></p>
                <?php } ?>
                
                <?php if(isset($_SESSION['id']) && $profile_id != $_SESSION['id']) { ?>
                    <div class="rtp_dv">
                        
                        <form role="form" class="validateForm" name="form-rate-user" id="form-rate-user" action="<?php echo getLink('phpajax/rate.php', '', true); ?>" method="post" enctype="multipart/form-data">
                            
                            <div class="plyr_star player-rating <?php if($userrate) { ?> rate-applied <?php } ?>">
                                <?php for($i=1; $i<=5; $i++) { ?>
                                <img src="images/star1.png" alt="" <?php if($i <= $userrate) { ?> class="rate-apply" <?php } ?>>
                                <?php } ?>
                                <input type="hidden" name="rating" value="<?php echo $userrate; ?>" >
                                <input type="hidden" name="profile_id" value="<?php echo $profile_id; ?>" >
                            </div>
                            <p>&nbsp;</p>
                            
                            <?php if(!$userrate) { ?>
                            <a class="a_rtp rate-player " href="javascript:">Rate the Player</a>
                            <?php } ?>

                        </form>
                    </div>
                <?php } ?>
                
            </div>
            
        </div>
        
        <div class="ply_tav_rgt">
            
            <div class="ply_tav_rgt_inn">
                <h2>Players<span>Overall Ratings</span></h2>
                <hr>
                <ul>
                    <?php foreach ($playerRating->result_array() as $row) { ?>
                        <li>
                            <div class="ply_tav_rgt_img">
                                <a href="<?php echo getLink('profile.php', 'profile_id=' . $row['id']); ?>">
                                    <?php echo getUserProfileImage($row['photo']); ?>
                                </a>
                            </div>
                            <div class="ply_tav_rgt_con">
                                <h3><a href="<?php echo getLink('profile.php', 'profile_id=' . $row['id']); ?>"><?php echo $row['first_name'] .' '. $row['last_name'];?></a></h3>
                                <p><?php echo $row['club_name']; ?></p>
                                <?php $user_rating = round($row['user_rating']); ?>    
                                <div class="plyr_star">
                                    <?php for ($i = 1; $i <= $user_rating; $i++) { ?>
                                     <img src="images/star1.png" alt=""/>
                                    <?php } ?>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            
            <div class="ply_tav_rgt_inn" style="display:none">
                <h2>Coach/Scout<span>Overall Ratings</span></h2>
                <hr>
                <ul>
                    <?php foreach ($coach_scout_rating->result_array() as $row) { ?>
                        <li>
                            <div class="ply_tav_rgt_img">
                                <?php echo getUserProfileImage($row['photo']); ?>
                            </div>
                            <div class="ply_tav_rgt_con">
                                <h3><a href="<?php echo getLink('profile.php', 'profile_id=' . $row['id']); ?>"><?php echo $row['first_name'] .' '. $row['last_name'];?></a></h3>
                                <p><?php echo $row['club_name']; ?></p>
                                <?php $user_rating = round($row['user_rating']); ?>    
                                <div class="plyr_star">
                                    <?php for ($i = 1; $i <= $user_rating; $i++) { ?>
                                     <img src="images/star1.png" alt=""/>
                                    <?php } ?>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            
        </div>
        
    </div>
 