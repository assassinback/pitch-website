<?php
//checkPermission('category', true);

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$data = array();

$act = 'add';
if($id) {
	$act = 'update';
	$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE id ='.$id);
	$data = $result->row_array();
}


$fields = array('email', 'first_name', 'last_name', 'photo', 'country_id', 'county_id', 'date_of_birth', 'team_id', 'previous_teams', 'prefered_foot', '1st_player_position', '2nd_player_position', '3rd_player_position', 'height', 'weight', 'highest_education_level', 'previous_injury', 'nature_of_injury', 'years_playing_football', 'highest_level_played_at', 'club_played_at_highest_level', 'user_rating', 'overall_score', 'user_rank', 'score_validated_by', 'score_validated_date', 'status');
foreach($fields as $field) {
	if(isset($_POST[$field])) {
		${$field} = $_POST[$field];
	} else if(isset($data[$field])) {
		${$field} = $data[$field];
	} else {
		${$field} = null;
	}
}

?>


<div class="content-wrapper">
    <section class="content-header">
		<h1>User</h1>
        <ol class="breadcrumb">
			<li><a href="<?php echo getAdminLink();?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?php echo getAdminLink('user');?>"><i class="fa fa-folder"></i> User</a></li>
        </ol>
	</section>
    <section class="content">
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-name">User</h3>
					</div>
                     
                    <ul>
                        <li>
                            <label>User Type</label>
                            <span></span>
                        </li>
                        <li>
                            <label>First Name</label>
                            <span></span>
                        </li>
                        <li>
                            <label>Last Name</label>
                            <span></span>
                        </li>
                    </ul>
                    
                    <div class="box-footer">
                        <a href="javascript:history.go(-1);" class="btn btn-primary">Back</a>
                    </div>
                </div>
			</div>
		</div>
	</section>
</div>

<script>

$(document).ready(function(){
    $(".validateForm").validationEngine({promptPosition : "inline", scroll: true});   
});
</script>