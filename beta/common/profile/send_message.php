<?php if(isset($_SESSION['id']) && $profile_id != $_SESSION['id']) { ?>
    <?php /* <a class="a_sm" href="<?php echo getLink('send_message.php', 'receiver_id='.$profile_id); ?>">Send Message</a> */ ?>
    <a class="a_sm" href="javascript:" data-toggle="modal" data-target="#myModal">Send Message</a>
    
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="<?php echo getLink('phpajax/send_message.php', '', true); ?>" name="form-send-message" id="form-send-message" method="POST">
                    <input type="hidden" name="profile_id" value="<?php echo $userInfo['id']; ?>" >
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Send Message</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-6 col-sm-4 col-md-3" style="height: 80px;">
                                <?php 
                                    if ($userInfo['user_type'] == 2) {
                                        $user_type = 'coach';
                                    } else if ($userInfo['user_type'] == 2) {
                                        $user_type = 'scout';
                                    } else {
                                        $user_type = 'player';
                                    }
                                ?>
                                <?php echo getUserProfileImage($userInfo['photo'], $user_type, 'class="img-thumbnail img-responsive"'); ?>
                            </div>
                            
                            <div class="col-xs-6 col-sm-8 col-md-9">
                                <h3><?php echo $userInfo['first_name']; ?><br/><span><?php echo $userInfo['last_name']; ?></span></h3>
                            </div>
                        </div>
                        <div class="form-group" >
                            <label for="message">Message:</label>
                            <textarea class="form-control" name="message" required ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" >Send</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
<?php } ?>