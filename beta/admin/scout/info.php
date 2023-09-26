<?php
$module = 'scout';
checkPermission($module);

$pageTitle = 'Scout';

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$data = array();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = $_REQUEST;
	$act = $_REQUEST['act'];
	
	$name = $_REQUEST['name'];
	$status = $_REQUEST['status'];
	
	if($act == "add")
	{	
		if($name != "")
		{
			/* $data = array(
					'name' => $name,
					'status' => $status,
					'date_added' => date('Y-m-d H:i:s'),
					'date_modified' => date('Y-m-d H:i:s')
				);
			insertData("user",$data);
			
			$_SESSION['msgType'] = 'success';
            $_SESSION['msgString'] = 'Record added successfully!';
			redirect(getAdminLink($module));
			exit; */
		}
	}

	// Modify Section :: Modify data into database
			
	if($act == "update")
	{
		if($name != "")
		{		
			/* $data = array(
					'name' => $name,
					'status' => $status,
					'date_modified' => date('Y-m-d H:i:s')
				);
                
			$where ="id ={$id}";
			updateData("user",$data,$where);
			
			$_SESSION['msgType'] = 'success';
            $_SESSION['msgString'] = 'Record updated successfully!';
			redirect(getAdminLink($module));
			exit; */
		}
	}	
}

$act = 'add';
if($id) {
	$act = 'update';
	$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE id ='.$id);
	$data = $result->row_array();
}

$fields = array('user_type', 'email', 'first_name', 'last_name', 'photo', 'country_id', 'county_id', 'date_of_birth', 'team_id', 'previous_teams', 'prefered_foot', '1st_player_position', '2nd_player_position', '3rd_player_position', 'height', 'weight', 'highest_education_level', 'previous_injury', 'nature_of_injury', 'years_playing_football', 'highest_level_played_at', 'club_played_at_highest_level', 'user_rating', 'overall_score', 'user_rank', 'score_validated_by', 'score_validated_date', 'status', 'phone', 'dbs_number', 'document_file', 'club_id', 'coaching_qualification', 'years_of_experience', 'currently_working_for', 'previously_worked_for');
foreach($fields as $field) {
	if(isset($_POST[$field])) {
		${$field} = $_POST[$field];
	} else if(isset($data[$field])) {
		${$field} = $data[$field];
	} else {
		${$field} = null;
	}
}

if ($user_type == 1) {
    
    $result = $db->query('SELECT test_plan.*, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan LEFT JOIN  ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_test_plan.user_id = ?', array($id));
    $userPlanInfo = $result->row_array();
    
    $amenities = $db->query('SELECT user_amenities.*, amenities.title, amenities.type FROM ' . $dbPrefix . 'user_amenities as user_amenities INNER JOIN ' . $dbPrefix . 'amenities as amenities ON (amenities.id = user_amenities.amenity_id AND amenities.status = 1) WHERE user_amenities.availability = 1 AND  user_amenities.user_id = ?', array($id));
    $amenities = $amenities->result_array();

    $tests = $db->query('SELECT user_score.*, test.* FROM ' . $dbPrefix . 'test as test LEFT JOIN  ' . $dbPrefix . 'user_test_score as user_score ON (user_score.test_id = test.id AND user_score.user_id = ?) WHERE test.status = 1 AND test.id IN (' . $userPlanInfo['allowed_test'] . ')', array($id));
    $tests = $tests->result_array();
    
    $result = $db->query('SELECT user_score.id FROM ' . $dbPrefix . 'test as test LEFT JOIN  ' . $dbPrefix . 'user_test_score as user_score ON (user_score.test_id = test.id AND user_score.user_id = ?) WHERE test.status = 1 AND user_score.validated = 0 AND user_score.require_validation = 1 AND test.id IN (' . $userPlanInfo['allowed_test'] . ')', array($id));
    $requireValidation = $result->num_rows();
    
    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user_amenities WHERE type="sport_science_validation" AND user_id = ? AND status = 0 AND availability = 1 ORDER BY ID ASC LIMIT 1', array($id));
    $validationPending = $result->num_rows();
    $validationInfo = $result->row_array();
}


if(isset($_REQUEST['delete']))
{
    $result = $db->query('DELETE FROM ' . $dbPrefix . 'user WHERE user_typr=3 AND id = ?', array($id));
    
    if ($result) {
        $_SESSION['msgType'] = 'danger';
        $_SESSION['msgString'] = 'Record deleted successfully!';
    }
    
    redirect(getAdminLink($module));
    exit;
}

?>

<div class="content-wrapper">
    <section class="content-header">
		<h1><?php echo $pageTitle; ?></h1>
        <ol class="breadcrumb">
			<li><a href="<?php echo getAdminLink();?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?php echo getAdminLink($module);?>"><i class="fa fa-folder"></i> <?php echo $pageTitle; ?></a></li>
        </ol>
	</section>
    <section class="content">
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
                        <h3 class="box-name"><?php echo $pageTitle . ' Info (' . $first_name . ' ' . $last_name . ')'; ?></h3>
                        <?php /*
						<h3 class="box-name"><?php echo $pageTitle . ' Info (' . $first_name . ' ' . $last_name . ' - ' . (($user_type == 2) ? 'Coach' : (($user_type == 3) ? 'Scout' : 'Player')) . ')'; ?></h3>
                        */ ?>
                        
                        <form name="" method="post" class="cut_btn">
                            <input type="submit" name="delete" class="btn btn-danger delete-selected-record" value="Delete" onclick="return confirm('Are you sure you want to delete?');" />
                        </form>
                        
					</div>
                    
                    <?php if (isset($msgString)) { ?>
                        <div class="alert alert-<?php echo $msgType; ?>">
                            <?php echo $msgString; ?>
                        </div>
                    <?php } ?>
                    
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" data-tab="info">Information</a></li>
                            <?php if ($user_type == 1) { ?>
                            <li><a href="#tab_2" data-toggle="tab" data-tab="score">Score</a></li>
                            <li><a href="#tab_3" data-toggle="tab" data-tab="plan">Plan</a></li>
                            <?php } ?>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                
                                <div class="box box-solid" style="">
                                    <div class="box-body no-padding">
                                        <table id="layout-skins-list" class="table table-striped bring-up nth-2-center">
                                            <tbody>
                                                <tr>
                                                    <td colspan="2"><strong>General Info</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>User Name</td>
                                                    <td>
                                                        <?php 
                                                        if ($user_type == 2) {
                                                            $type = 'coach';
                                                        } else if ($user_type == 3) {
                                                            $type = 'scout';
                                                        } else {
                                                            $type = 'player';
                                                        } ?>
                                                        <?php echo getUserProfileImage($photo, $type, 'style="width: 100px;"'); ?><br>
                                                        <strong><?php echo $first_name . ' ' . $last_name; ?></strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td><a href="mailto:<?php echo $email; ?>" ><?php echo $email; ?></a></td>
                                                </tr>
                                                <?php if ($user_type != 2) { ?>
                                                    <tr>
                                                        <td>Club</td>
                                                        <?php if ($user_type == 3) { ?>
                                                            <td><?php echo ($club_id) ? getClub($club_id) : ''; ?></td>
                                                        <?php } else { ?>
                                                            <td><?php echo ($team_id) ? getClub($team_id) : ''; ?></td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                                <?php if ($user_type == 1) { ?>
                                                    <tr>
                                                        <td>Rank</td>
                                                        <td><?php echo $user_rank; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Overall Score</td>
                                                        <td><?php echo $overall_score; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Rating</td>
                                                        <td><?php echo $user_rating; ?></td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="2"><strong>Personal Info</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Date of Birth</td>
                                                    <td><?php echo formatDate($date_of_birth); ?></td>
                                                </tr>
                                                <?php if ($user_type == 2) { ?>
                                                    <tr>
                                                        <td>Mobile No</td>
                                                        <td><?php echo $phone; ?></td>
                                                    </tr>
                                                <?php } ?>
                                                <?php if ($user_type == 1) { ?>
                                                    <tr>
                                                        <td>Height</td>
                                                        <td><?php echo $height; ?> cm</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Weight</td>
                                                        <td><?php echo $weight; ?> kg</td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td>Country</td>
                                                    <td><?php echo getCountry($country_id); ?></td>
                                                </tr>
                                                <tr>
                                                    <td>County</td>
                                                    <td><?php echo getCounty($county_id); ?></td>
                                                </tr>
                                                <?php if ($user_type != 1) { ?>
                                                    <tr>
                                                        <td>DBS Number</td>
                                                        <td><?php echo $dbs_number; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Document File</td>
                                                        <?php if ($document_file != "" && file_exists(USER_PATH . $document_file)) { ?>
                                                        <td><a href="<?php echo USER_URL . $document_file; ?>" target="_blank">View</a></td>
                                                        <?php } else { ?>
                                                        <td> - </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td colspan="2"><strong>Other Info</strong></td>
                                                </tr>
                                                <tr>
                                                    <td>Highest Education Level</td>
                                                    <td><?php echo $highest_education_level; ?></td>
                                                </tr>
                                                <?php if ($user_type == 1) { ?>
                                                    <tr>
                                                        <td>Any Previous Injuries</td>
                                                        <td><?php echo ($previous_injury == 1) ? 'Yes' : 'No'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>State Nature of Injury</td>
                                                        <td><?php echo $nature_of_injury; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>No. of Years playing Football</td>
                                                        <td><?php echo $years_playing_football; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Highest Level Played At</td>
                                                        <td><?php echo $highest_level_played_at; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Club Played At Highest Level</td>
                                                        <td><?php echo $club_played_at_highest_level; ?></td>
                                                    </tr>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td>Coaching Qualification</td>
                                                        <td><?php echo $coaching_qualification; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Years of Experience</td>
                                                        <td><?php echo $years_of_experience; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Currently working for</td>
                                                        <td><?php echo $currently_working_for; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Previously worked for</td>
                                                        <td><?php echo $previously_worked_for; ?></td>
                                                    </tr>
                                                <?php } ?>
                                                <?php if ($user_type == 1) { ?>
                                                    <tr>
                                                        <td colspan="2"><strong>Playing Position</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td>1st Playing Position</td>
                                                        <td><?php echo (isset($data['1st_player_position']) && $data['1st_player_position'] != "") ? getPosition($data['1st_player_position']) : ''; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>2nd Playing Position</td>
                                                        <td><?php echo (isset($data['2nd_player_position']) && $data['2nd_player_position'] != "") ? getPosition($data['2nd_player_position']) : ''; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>3rd Playing Position</td>
                                                        <td><?php echo (isset($data['3rd_player_position']) && $data['3rd_player_position'] != "") ? getPosition($data['3rd_player_position']) : ''; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Preferred Foot</td>
                                                        <td><?php echo $prefered_foot; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                
                            </div>
                            <!-- /.tab-pane -->
                            
                            <?php if ($user_type == 1) { ?>
                            <div class="tab-pane" id="tab_2">
                            
                                <img class="p_graph" src="<?php echo SITE_URL; ?>graph.php?user_id=<?php echo $id; ?>" alt=""/>
                                
                                <?php if ($requireValidation > 0 && $validationPending > 0) { ?>
                                <form action="<?php echo getAdminLink('user/update.php', '', true); ?>" method="post" name="form-amenities" class="form-amenities" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $id ?>"/>
                                    <input type="hidden" name="user_amenity_id" value="<?php echo $validationInfo['id']; ?>"/>
                                    <input type="hidden" name="type" value="validate_score"/>
                                    <button type="submit" name="update" class="btn btn-primary" data-label="Validate Score">Validate Score</button>
                                </form>
                                <?php } ?>
                                
                                <div class="box box-solid" style="">
                                    <div class="box-body no-padding">
                                        <table id="layout-skins-list" class="table table-striped bring-up nth-2-center">
                                            <tbody>
                                                <tr>
                                                    <td colspan="2"><strong>Score</strong></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Overall Score</strong></td>
                                                    <td><strong><?php echo $overall_score; ?></strong></td>
                                                </tr>
                                                <?php foreach ($tests as $test) { ?>
                                                <tr>
                                                    <td><?php echo $test['title']; ?></td>
                                                    <td><?php echo $test['weightage']; ?> (<?php echo preg_replace("/\([^)]+\)/", "", $test['label']); ?>)
                                                    <?php if ($test['require_validation'] == 1 && $test['validated'] == 0) { ?>
                                                        <b class='text-danger'>Validation Required</b>
                                                    <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            
                            <div class="tab-pane" id="tab_3">
                                <div class="box box-solid" style="">
                                    <div class="box-body no-padding">
                                        
                                        <table id="layout-skins-list" class="table table-striped bring-up nth-2-center">
                                            <tbody>
                                                <tr>
                                                    <td><strong><?php echo $userPlanInfo['title']; ?></strong>  :  <strong><?php echo formatPrice($userPlanInfo['price']); ?></strong> </td>
                                                </tr>
                                            </tbody>
                                        </table>
        
                                        <div class="row">
                                            
                                            <?php foreach ($amenities as $key => $amenity) { ?>
                                            
                                            <?php 
                                            $amenityData = unserialize($amenity['data']);
                                            $label = 'Receipt';
                                            if ($amenity['type'] == 'sport_science_validation'){
                                                $status = ($amenity['status'] == 1) ? 'Used' : 'Unused';
                                            } else if ($amenity['type'] == 'potential_trial'){
                                                $status = ($amenity['status'] == 1) ? 'Invited' : 'Send Invitaion';
                                            } else if ($amenity['type'] == 'trial_session'){
                                                $status = ($amenity['status'] == 1) ? 'Booked' : 'Unused';
                                            } else {
                                                $label = 'Plan';
                                                $status = ($amenity['status'] == 1) ? 'Sent' : 'Pending';
                                            }?>
                                            
                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading text-center"><b><?php echo $amenity['title']; ?></b></div>
                                                    <div class="panel-body">
                                                        <form action="<?php echo getAdminLink('user/update.php', '', true); ?>" method="post" name="form-amenities" class="form-amenities" enctype="multipart/form-data">
                                                            <input type="hidden" name="id" value="<?php echo $id ?>"/>
                                                            <input type="hidden" name="user_amenity_id" value="<?php echo $amenity['id']; ?>"/>
                                                            <input type="hidden" name="type" value="<?php echo $amenity['type']; ?>"/>
                                                            <div class="form-group">
                                                                <label>Status : </label>
                                                                <?php if ($amenity['type'] == 'potential_trial'){ ?>
                                                                    <?php if ($amenity['status'] == 0){ ?>
                                                                        <a href="javascript:" class="send-invitaion" data-toggle="modal" data-target="#sendInvitaionModal"><?php echo $status; ?></a>
                                                                    <?php } else { ?>
                                                                        <?php echo $status; ?>
                                                                    <?php } ?>
                                                                <?php } else if ($amenity['status'] == 1 && $amenity['type'] == 'trial_session'){ ?>
                                                                    <?php echo $status . ' - ' . formatDate($amenityData['trial_session_date']); ?>
                                                                <?php } else { ?>
                                                                    <?php echo $status;?> 
                                                                <?php } ?>
                                                            </div>
                                                            <div class="form-group">
                                                                <label><?php echo $label;?></label>
                                                                <input type="file" name="file" >
                                                                <?php 
                                                                if (isset($amenityData['file'])) { ?>
                                                                    <a href="<?php echo USER_URL . $amenityData['file']; ?>" target="_blank">View</a>
                                                                <?php } ?>
                                                            </div>
                                                            
                                                            <button type="submit" name="update" class="btn btn-primary" data-label="Send">Send</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <?php if ($amenity['type'] == 'potential_trial'){ ?>
                                            <div id="sendInvitaionModal" class="modal fade" role="dialog">
                                                <div class="modal-dialog">

                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <form action="<?php echo getAdminLink('user/send_invitaion.php', '', true); ?>" name="form-send-invitaion" id="form-send-invitaion" method="POST">
                                                            <input type="hidden" name="id" value="<?php echo $id ?>"/>
                                                            <input type="hidden" name="user_amenity_id" value="<?php echo $amenity['id']; ?>"/>
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                <h4 class="modal-title">Send Potential Trial Invitaion</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group" >
                                                                    <label for="message">Message:</label>
                                                                    <textarea class="form-control" name="message" required ></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-success" >Invite</button>
                                                            </div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                            <?php } ?>
                                            
                                            <?php if ($key%3 == 2) { ?>
                                                <div class="clearfix visible-md visible-lg" ></div>
                                            <?php } ?>
                                            
                                            <?php if ($key%2 == 1) { ?>
                                                <div class="clearfix visible-sm" ></div>
                                            <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <?php } ?>
                        </div>
                    <!-- /.tab-content -->
                    </div>

				</div>
			</div>
		</div>
	</section>
</div>

<script src="js/user.js"></script>

<script type="text/javascript">

$(document).ready(function(){
    <?php if(isset($_GET['tab'])) { ?>
        var tab = '<?php echo $_GET['tab']; ?>';
        $('.nav-tabs').find('[data-tab="' + tab + '"]').trigger('click');
    <?php } ?>
});
</script>
