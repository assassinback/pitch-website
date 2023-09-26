
    <div class="col-xs-12 col-sm-9 ply_rv">
        
        <div class="ply_rv_dv">
        
            <?php if($allow_edit) { ?>
               <p><i>Please upload a 30-60 second video stating to all the scouts, managers and coaches your core values and characteristics, who you have previously played for and why you think you should be given a chance or trial.</i><p>
            <?php } ?>
            
            <?php if ($videoId = getVideoId($userInfo['resume_video'])) { ?>      
                <div class="ply_rv_dv_lft">
                    <iframe src="https://www.youtube.com/embed/<?php echo $videoId; ?>" height="300" frameborder="0" width="560"></iframe>
                </div>
            <?php } ?>
            
            <div class="ply_rv_dv_rgt">
                <p><?php echo $userInfo['resume_video_content']; ?></p>
                <?php if($allow_edit) { ?>
                <p><a href="<?php echo getLink('editprofile.php', 'type=resume_video'); ?>" class="edit-link"><i class="fa fa-pencil" aria-hidden="true"></i></a></p>
                <?php } ?>
            </div>
            
        </div>
        
    </div>
            
