<?php

    $select_player = $db->query("SELECT * FROM " . $dbPrefix . "user where user_type=1 AND status=1");
    $player_count = $select_player->num_rows();
    
    $select_coach = $db->query("SELECT * FROM " . $dbPrefix . "user where user_type=2 AND status=1");
    $coach_count = $select_coach->num_rows();
    
    $select_scout = $db->query("SELECT * FROM " . $dbPrefix . "user where user_type=3 AND status=1");
    $scout_count = $select_scout->num_rows();
    
    $all_user = $db->query("SELECT * FROM " . $dbPrefix . "user where status=1 ORDER BY id DESC LIMIT 10");
    $user_count = $all_user->num_rows();
?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Dashboard
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
		</ol>
	</section>
    
    <section class="content">
        <div class="row">
            <div class="col-sm-4 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo $player_count; ?></h3>
                        <p>Player</p>
                    </div>
                    <div class="icon"><i class=""></i></div>
                    <a href="<?php echo getAdminLink('user');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            <div class="col-lg-4 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo $coach_count; ?></h3>
                        <p>Coach</p>
                    </div>
                    <div class="icon"><i class=""></i></div>
                    <a href="<?php echo getAdminLink('coach');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            
            <div class="col-lg-4 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo $scout_count; ?></h3>
                        <p>Scout</p>
                    </div>
                    <div class="icon"><i class=""></i></div>
                    <a href="<?php echo getAdminLink('scout');?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Latest Users</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>Name</th>
                                <th>User Type</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            <?php
                                foreach ($all_user->result_array() as $row) {
                                if($row['user_type'] == 2) 
                                { 
                                    $usertype ="Coach";
                                } 
                                else if($row['user_type'] == 3)
                                { 
                                    $usertype ="Scout";
                                }
                                else
                                {
                                    $usertype ="Player";
                                } 

                                if($row['status'] == 1)
                                {
                                    $status ='Active';
                                    $cls ='label label-success';
                                }
                                else
                                {
                                   $status ='Inactive'; 
                                   $cls ='label label-danger';
                                }
                                
                            ?>        
                            <tr>
                                <td><?php echo $row['first_name'] .' '. $row['last_name']; ?></td>
                                <td><?php echo $usertype; ?></td>
                                <td><?php echo formatDate($row['date_added']); ?></td>
                                <td><span class="label label-primary"><a href="<?php echo getAdminLink('userinfo', 'id=' . $row['id']) ?>" style="color:white;">View</a></span></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

