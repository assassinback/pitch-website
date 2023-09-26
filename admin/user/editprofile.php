<?php include('config.php');

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$userType = isset($_REQUEST['userType']) ? $_REQUEST['userType'] : 1;

//echo $id; exit;


$error  =   '';
$success_msg ='';

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE id = ? AND status = 1', array($id));

$userInfo = $result->row_array();

$profile_image =   $userInfo['photo'];

$user_type =   $userInfo['user_type'];
$first_name =   $userInfo['first_name'];
$last_name =   $userInfo['last_name'];
$club_id =   $userInfo['club_id'];
$date_of_birth =   $userInfo['date_of_birth'];
$height =   $userInfo['height'];
$weight =   $userInfo['weight'];
$country =   $userInfo['country_id'];
$county =   $userInfo['county_id'];
$phone =   $userInfo['phone'];
$dbs_number =   $userInfo['dbs_number'];
$dbs_verified =   $userInfo['dbs_verified'];
$coaching_qualification =   $userInfo['coaching_qualification'];
$years_of_experience =   $userInfo['years_of_experience'];
$currently_working_for =   $userInfo['currently_working_for'];
$previously_worked_for =   $userInfo['previously_worked_for'];
$doc_name =   $userInfo['document_file'];

$highest_education_level =   $userInfo['highest_education_level'];
$previous_injury =   $userInfo['previous_injury'];
$nature_of_injury =   $userInfo['nature_of_injury'];
$years_playing_football =   $userInfo['years_playing_football'];
$highest_level_played_at =   $userInfo['highest_level_played_at'];
$club_played_at_highest_level =   $userInfo['club_played_at_highest_level'];

$first_player_position =   $userInfo['1st_player_position'];
$second_player_position =   $userInfo['2nd_player_position'];
$third_player_position =   $userInfo['3rd_player_position'];
$prefered_foot =   $userInfo['prefered_foot'];

$what_are_your_core_values =   $userInfo['what_are_your_core_values'];

$what_sort_of_character_are_you =   $userInfo['what_sort_of_character_are_you'];

$resume_video =   $userInfo['resume_video'];
$resume_video_content =   $userInfo['resume_video_content'];

$technical_ability_video =   $userInfo['technical_ability_video'];
$technical_ability_video_content =   $userInfo['technical_ability_video_content'];

$date_modified =   date('Y-m-d H:i:s');

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_REQUEST['update'])) {

$first_name =   isset($_REQUEST["first_name"])?$_REQUEST["first_name"]:'';
$last_name =   isset($_REQUEST["last_name"])?$_REQUEST["last_name"]:'';
$club_id =   isset($_REQUEST["club_id"])?$_REQUEST["club_id"]:0;
$date_of_birth  =   isset($_REQUEST["date_of_birth"])&&$_REQUEST["date_of_birth"]!=''?date('Y-m-d',strtotime(str_replace('/', '-', $_REQUEST["date_of_birth"]))):'';
$height =   isset($_REQUEST["height"])?$_REQUEST["height"]:'';
$weight =   isset($_REQUEST["weight"])?$_REQUEST["weight"]:'';
$country    =   isset($_REQUEST["country"])?$_REQUEST["country"]:'';
$county =   isset($_REQUEST["county"])?$_REQUEST["county"]:'';

$phone  =   isset($_REQUEST["phone"])?$_REQUEST["phone"]:'';
$dbs_number =   isset($_REQUEST["dbs_number"])?$_REQUEST["dbs_number"]:'';
$dbs_verified =   isset($_REQUEST["dbs_verified"])?$_REQUEST["dbs_verified"]:0;
$coaching_qualification =   isset($_REQUEST["coaching_qualification"])?$_REQUEST["coaching_qualification"]:'';
$years_of_experience =   isset($_REQUEST["years_of_experience"])?$_REQUEST["years_of_experience"]:'';
$currently_working_for =   isset($_REQUEST["currently_working_for"])?$_REQUEST["currently_working_for"]:'';
$previously_worked_for =   isset($_REQUEST["previously_worked_for"])?$_REQUEST["previously_worked_for"]:'';

$highest_education_level =   isset($_REQUEST["highest_education_level"])?$_REQUEST["highest_education_level"]:'';
$previous_injury =   isset($_REQUEST["previous_injury"])?$_REQUEST["previous_injury"]:'';
$nature_of_injury =   isset($_REQUEST["nature_of_injury"])?$_REQUEST["nature_of_injury"]:'';
$years_playing_football =   isset($_REQUEST["years_playing_football"])?$_REQUEST["years_playing_football"]:'';
$highest_level_played_at =   isset($_REQUEST["highest_level_played_at"])?$_REQUEST["highest_level_played_at"]:'';
$club_played_at_highest_level =   isset($_REQUEST["club_played_at_highest_level"])?$_REQUEST["club_played_at_highest_level"]:'';

$first_player_position =   isset($_REQUEST["1st_player_position"])?$_REQUEST["1st_player_position"]:'';
$second_player_position =   isset($_REQUEST["2nd_player_position"])?$_REQUEST["2nd_player_position"]:'';
$third_player_position =   isset($_REQUEST["3rd_player_position"])?$_REQUEST["3rd_player_position"]:'';
$prefered_foot =   isset($_REQUEST["prefered_foot"])?$_REQUEST["prefered_foot"]:'';


/**** Image Update ****/
$photo = "";
    if(!$error && isset($_FILES['profileimage']['name']) && !empty($_FILES['profileimage']['name']))
	{
        $source = $_FILES['profileimage']['tmp_name'];
		$file_name = 'Profile-' . str_pad($id, 4, '0', STR_PAD_LEFT);
		$image_upload = uploadImage(array('source' => $source, 'destination' => USER_PATH, 'file_name' => $file_name));

		if(isset($image_upload['success'])) {
			$photo = $image_upload['file'];
            if (file_exists(USER_PATH . $userInfo['photo'])) {
                unlink(USER_PATH . $userInfo['photo']);
            }
		} else if(isset($image_upload['error'])) {
			$error = 'Img - ' . $image_upload['error'];
		}
	}
    else
    {
        $photo = $profile_image;
    }
/**** Image Update Ends ****/ 

/**** Document Update ****/
$docname = "";
if(!$error && isset($_FILES['documentfile']['name']) && !empty($_FILES['documentfile']['name']))
{
    $source = $_FILES['documentfile']['tmp_name'];
    //$file_name = 'Document-' . str_pad(getAutoID($dbPrefix . 'user'), 4, '0', STR_PAD_LEFT);
    $file_name = 'Document-' . str_pad($id, 4, '0', STR_PAD_LEFT);
    $expext= explode(".",$_FILES['documentfile']['name']);
    $type = $expext[1];

    $doc_upload = uploadDocument(array('source' => $source, 'destination' => USER_PATH, 'file_name' => $file_name, 'type'=>$type, 'original_name'=>$_FILES['documentfile']['name']));

    if(isset($doc_upload['success'])) {
        $docname = $doc_upload['file'];
    } else if(isset($doc_upload['error'])) {
        $error = 'Doc - ' . $doc_upload['error'];
    }
} 
else
{
    $docname = $doc_name;
}    
/**** Document Update Ends ****/  

$date_modified =   date('Y-m-d H:i:s');
    
    $error = false;
       
    if(!$error) {
        
                
                if($user_type ==1){
                    $userData = array(
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            //'team_id' => $team_id,
                            'photo' => $photo,
                            'height' => $height,
                            'weight' => $weight,
                            'country_id' => $country,
                            'county_id' => $county,
                            'date_of_birth' => $date_of_birth,
                            '1st_player_position' => $first_player_position,
                            '2nd_player_position' => $second_player_position,
                            '3rd_player_position' => $third_player_position,
                            'prefered_foot' => $prefered_foot,
                            'highest_education_level' => $highest_education_level,
                            'previous_injury' => $previous_injury,
                            'nature_of_injury' => $nature_of_injury,
                            'years_playing_football' => $years_playing_football,
                            'highest_level_played_at' => $highest_level_played_at,
                            'club_played_at_highest_level' => $club_played_at_highest_level,
                            'date_modified' => $date_modified
                        );
                    $userInfo['photo'] = $photo;
                }  
                

                if($user_type ==2){
                    $userData = array(
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            //'team_id' => $team_id,
                            'photo' => $photo,
                            'country_id' => $country,
                            'county_id' => $county,
                            'phone' => $phone,
                            'dbs_number' => $dbs_number,
                            'dbs_verified' => $dbs_verified,
                            'date_of_birth' => $date_of_birth,
                            'highest_education_level' => $highest_education_level,
                            'coaching_qualification' => $coaching_qualification,
                            'years_of_experience' => $years_of_experience,
                            'currently_working_for' => $currently_working_for,
                            'previously_worked_for' => $previously_worked_for,
                            'document_file' => $docname,
                            'date_modified' => $date_modified
                        );
                    $userInfo['photo'] = $photo;
                }
                
                if($user_type ==3){
                    $userData = array(
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'club_id' => $club_id,
                            'photo' => $photo,
                            'country_id' => $country,
                            'county_id' => $county,
                            'phone' => $phone,
                            'dbs_number' => $dbs_number,
                            'dbs_verified' => $dbs_verified,
                            'date_of_birth' => $date_of_birth,
                            'highest_education_level' => $highest_education_level,
                            'coaching_qualification' => $coaching_qualification,
                            'years_of_experience' => $years_of_experience,
                            'currently_working_for' => $currently_working_for,
                            'previously_worked_for' => $previously_worked_for,
                            'document_file' => $docname,
                            'date_modified' => $date_modified
                        );
                    $userInfo['photo'] = $photo;
                }
                    
                
        }            
        
        $where ='id =' . $id .'';
        $update = updateData('user', $userData, $where);
        //echo $db->last_query();
        //exit;
        
        if($update)
        {
            $success_msg = "Profile has been successfully updated.";
        }
}

$selectCountry = $db->query('SELECT country.id, country.name FROM ' . $dbPrefix . 'country as country WHERE country.status=1 order by country.name', array());

if ($country != '') {
	$selectCounty = $db->query('SELECT county.id, county.name FROM ' . $dbPrefix . 'county as county WHERE county.status=1 AND county.country_id = ? order by county.name', array($country));
	$selectCounty = $selectCounty->result_array();
} else {
	$selectCounty = array();
}

$selectPosition = $db->query('SELECT * FROM ' . $dbPrefix . 'position WHERE status = 1 ORDER BY name ASC', array());

$selectTeam = $db->query('SELECT team.id, team.name FROM ' . $dbPrefix . 'club as team WHERE team.status=1 order by team.id', array());

if ($userType == 2) {
    $page_label = "Coach";
    $page_link = "coach";
} else if ($userType == 3) {
    $page_label = "Scout";
    $page_link = "scout";
} else {
    $page_label = "User";
    $page_link = "user";
}

?>    

<div class="content-wrapper">
    <section class="content-header">
		<h1><?php echo $page_label; ?></h1>
        <ol class="breadcrumb">
			<li><a href="<?php echo getAdminLink();?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?php echo getAdminLink($page_link);?>"><i class="fa fa-folder"></i> <?php echo $page_label; ?></a></li>
        </ol>
	</section>
    <section class="content">
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-name"><?php echo $page_label; ?></h3>
					</div>
                    
                    <?php if($success_msg){ ?>
                        <div class="alert alert-success">
                            <?php echo $success_msg; ?>
                        </div>
                    <?php } ?>
                    
                    <?php if($error){ ?>
                    <div class="alert alert-danger">
                        <?php echo "<strong>Error!</strong> " . $error; ?>
                    </div>
                    <?php } ?>
                    
                    <div class="error"></div>
                    
					<form action='' method='post' class="validateForm" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?php echo $id ?>"/>
						<input type="hidden" name="act" value="<?php echo $act ?>"/>
                        <div class="box-body">
                        
                            <div class="form-group">
                                <label>Profile Picture </label><br>
                                    <?php echo getUserProfileImage($userInfo['photo'], 'player', 'width="100"'); ?><br><br>
                                    <input type="file" name="profileimage" id="profileimage" class="form-control input3 mini" data-validation-engine="validate[funcCall[geThan[]]]" data-errormessage-value-missing="Only JPG, JPEG and PNG are allowed">
							</div>
                            
                            <div class="form-group">
                                <label>First Name</label>
                                <input type='text' name="first_name" id="first_name" class='form-control' value="<?php echo $first_name; ?>" placeholder="Enter First Name" maxlength="50" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter your first name">
                            </div>
                            
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>" class="form-control txt_lg" placeholder="Enter Last Name" data-validation-engine="validate[required]" maxlength="50" data-errormessage-value-missing="Please enter your last name"  >
                            </div>
                                                                                   
                            <?php /*
                            <div class="form-group">
                                <label>Club</label>
                                <select class="form-control" name="team_id" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select team." style="width: 320px;" >
                                    <option value="">Select Current Team</option>
                                    <?php
                                    foreach ($selectTeam->result_array() as $row) {
                                        $teamName = $row['name'];    ?>
                                        <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $team_id) { echo "selected"; } ?>><?php echo $teamName; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            */ ?>
                            
                            <?php
                                $date = '';
                                if($date_of_birth != '')
                                {
                                    $date = date('d/m/Y',strtotime($date_of_birth));
                                }
                            ?>
                            <div class="form-group">
                                <label>Birth Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" value="<?php echo $date; ?>" name="date_of_birth" class="form-control pull-right txt_lg" id="datepicker">
                                </div>
                            </div>
                            
                            <?php if($user_type == 2) { ?>
                            <div class="form-group">
                                <label>Mobile No</label>
                                <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" class="form-control txt_lg" placeholder="Enter Phone" maxlength="11" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter your mobile number" >
                            </div>
                            <?php } ?>
                            
                            <?php if($user_type == 1) { ?>
                            <div class="form-group">
                                <label>Height (cm)</label>
                                <input type="text" name="height" id="height" value="<?php echo $height; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Height" maxlength="3" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter height." >
                            </div>
                            
                            <div class="form-group">
                                <label>Weight (kg)</label>
                                <input type="text" name="weight" id="weight" value="<?php echo $weight; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Weight" maxlength="3" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter weight." >
                            </div>
                            
                            <?php } ?>
                            
                            <div class="form-group">
                                <label>Country</label>
                                <select class="form-control" name="country" id="country" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select country." style="width: 320px;" onchange="getCounty(this.value)" >
                                        <option value="">Select Country</option>
                                        <?php
                                        foreach ($selectCountry->result_array() as $row) {
                                            $countryName = $row['name'];    ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $country) { echo "selected"; } ?>><?php echo $countryName; ?></option>
                                        <?php } ?>
                                    </select>
                            </div>
                            
                            <div class="form-group">
                                <label>County</label>
                                <select class="form-control" name="county" id="county" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select county." style="width: 320px;" >
                                                
                                        <option value="">Select County</option>
                                        <?php
                                        foreach ($selectCounty as $row) {
                                            $countyName = $row['name'];    ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $county) { echo "selected"; } ?>><?php echo $countyName; ?></option>
                                        <?php } ?>
                                        
                                    </select>
                            </div>
                            
                            <?php if($user_type == 3) { ?>
                            <div class="form-group">
                                <label>Club</label>
                                <select class="form-control" name="club_id" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select team." style="width: 320px;" >
                                    <option value="">Select Current Team</option>
                                    <?php
                                    foreach ($selectTeam->result_array() as $row) {
                                        $teamName = $row['name'];    ?>
                                        <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $club_id) { echo "selected"; } ?>><?php echo $teamName; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php } ?>
                            
                            <?php if($user_type == 2 || $user_type == 3) { ?>
                            <div class="form-group">
                                <label>DBS Number</label>
                                <input type="text" name="dbs_number" id="dbs_number" value="<?php echo $dbs_number; ?>" class="form-control txt_lg" placeholder="Enter DBS Number" data-validation-engine="validate[required]" maxlength="50" data-errormessage-value-missing="Please enter DBS Number"  >
                            </div>
                            
                            <div class="form-group">
                                <label>DBS Verify</label><br>
                                <input type="checkbox" name="dbs_verified" id="dbs_verified" value="1" <?php if ($dbs_verified == 1) { echo 'checked'; } ?> >
                            </div>
                            <?php } ?>
                            
                            <div class="form-group">
                                <label>Highest Education Level</label>
                                <input type="text" name="highest_education_level" id="highest_education_level" value="<?php echo $highest_education_level; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Highest Education Level" maxlength="50">
                            </div>
                            <?php if($user_type == 2 || $user_type == 3) { ?>
                            <div class="form-group">
                                <label>Coaching Qualification</label>
                                <input type="text" name="coaching_qualification" id="coaching_qualification" value="<?php echo $coaching_qualification; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Coaching Qualification" maxlength="50">
                            </div>
                            <div class="form-group">
                                <label>Years of Experience</label>
                                <input type="text" name="years_of_experience" id="years_of_experience" value="<?php echo $years_of_experience; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Years of Experience" maxlength="50">
                            </div>
                            <div class="form-group">
                                <label>Currently working for</label>
                                <input type="text" name="currently_working_for" id="currently_working_for" value="<?php echo $currently_working_for; ?>" class="form-control input3 mini txt_lg" placeholder="Enter currently working for" maxlength="50">
                            </div>
                            <div class="form-group">
                                <label>Previously worked for</label>
                                <input type="text" name="previously_worked_for" id="previously_worked_for" value="<?php echo $previously_worked_for; ?>" class="form-control input3 mini txt_lg" placeholder="Enter previously worked for" maxlength="50">
                            </div>
                            
                            <div class="form-group">
                                <label>Document File </label>
                                <?php /* if ($doc_name != "" && file_exists(USER_PATH . $document_file)) { ?>
                                <a href="<?php echo USER_URL . $doc_name; ?>" target="_blank">View</a>
                                <?php } */ ?>
                                <input type="file" name="documentfile" id="documentfile" class="form-control input3 mini" data-validation-engine="validate[funcCall[geThanDoc[]]]" data-errormessage-value-missing="Only PDF are allowed" >
                            </div>
                            
                            <?php } ?>
                            
                            <?php if($user_type == 1) { ?>
                            <div class="form-group">
                                <label>Any Previous Injuries</label>
                                <select class="form-control" name="previous_injury" id="previous_injury" style="width: 320px;" >
                                        
                                        <option value="0" <?php if($previous_injury == "0") { echo "selected"; } ?>>NO</option>
                                        <option value="1" <?php if($previous_injury == "1") { echo "selected"; } ?>>Yes</option>
                                    
                                    </select>
                            </div>
                            
                            <div class="form-group">
                                <label>State Nature of Injury</label>
                                <input type="text" name="nature_of_injury" id="nature_of_injury" value="<?php echo $nature_of_injury; ?>" class="form-control input3 mini txt_lg" placeholder="Enter State Nature of Injury" maxlength="50">
                            </div>
                            
                            <div class="form-group">
                                <label>No. of Years playing Football</label>
                                <input type="text" name="years_playing_football" id="years_playing_football" value="<?php echo $years_playing_football; ?>" class="form-control input3 mini txt_lg" placeholder="Enter No. of Years playing Football" maxlength="50">
                            </div>
                            
                            <div class="form-group">
                                <label>Highest Level Played At</label>
                                <input type="text" name="highest_level_played_at" id="highest_level_played_at" value="<?php echo $highest_level_played_at; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Your Highest Level Played At" maxlength="50">
                            </div>
                            
                            <div class="form-group">
                                <label>Club Played At Highest Level</label>
                                <input type="text" name="club_played_at_highest_level" id="club_played_at_highest_level" value="<?php echo $club_played_at_highest_level; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Club Played At Highest Level" maxlength="200">
                            </div>
                            
                            <div class="form-group">
                                <label>1st Playing Position</label>
                                <select class="form-control" name="1st_player_position" id="1st_player_position" style="width: 320px;" >
                                            
                                    <option value="">Select Position</option>
                                    <?php
                                    foreach ($selectPosition->result_array() as $row) {    
                                        $positionName = $row['name']; ?>
                                        <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $first_player_position) { echo "selected"; } ?>><?php echo $positionName; ?></option>
                                    <?php } ?>
                                    
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>2nd Playing Position</label>
                                <select class="form-control" name="2nd_player_position" id="2nd_player_position" style="width: 320px;" >
                                            
                                    <option value="">Select Position</option>
                                    <?php
                                    foreach ($selectPosition->result_array() as $row) {    
                                        $positionName = $row['name']; ?>
                                        <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $second_player_position) { echo "selected"; } ?>><?php echo $positionName; ?></option>
                                    <?php } ?>
                                    
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>3rd Playing Position</label>
                                <select class="form-control" name="3rd_player_position" id="3rd_player_position" style="width: 320px;" >
                                            
                                    <option value="">Select Position</option>
                                    <?php
                                    foreach ($selectPosition->result_array() as $row) {    
                                        $positionName = $row['name']; ?>
                                        <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $third_player_position) { echo "selected"; } ?>><?php echo $positionName; ?></option>
                                    <?php } ?>
                                    
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Prefered Foot</label>
                                <select class="form-control" name="prefered_foot" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select Prefered Foot." style="width: 320px;" >
                                        <option value="">Select Prefered Foot</option>
                                        <option value="Left" <?php if($prefered_foot == "Left") { echo "selected"; } ?>>Left</option>
                                        <option value="Right" <?php if($prefered_foot == "Right") { echo "selected"; } ?>>Right</option>
                                        
                                    </select>
                            </div>
                            <?php } ?>
                        </div>
                        
                        <div class="box-footer">
                            <button type="submit"  name="update"  class="btn btn-primary">Update</button>
                            <!--<a href="main.php?pg=user" class="btn btn-primary">Back</a> -->
                        </div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<link rel="stylesheet" type="text/css" href="js/summernote/dist/summernote.css">
<script type="text/javascript" src="js/summernote/dist/summernote.js"></script>

<script>

function getCounty(val) {
    $.ajax({
        type: "POST",
        url: "<?php echo SITE_URL ?>phpajax/get_county.php",
        data:'countryid='+val,
        success: function(data){
            $("#county").html(data);	
        }
    });
}

$(document).ready(function () {
    $('.summernote').summernote();
});

function geThan(){
	
        var extFile  = document.getElementById("profileimage").value;
        var ext = extFile.split('.').pop();
        var filesAllowed = ["jpg", "jpeg", "png"];
        if( (filesAllowed.indexOf(ext)) == -1)
            return "Only JPG, JPEG , PNG files are allowed";
    }
    
function geThanDoc(){
	
        var extFile  = document.getElementById("documentfile").value;
        var ext = extFile.split('.').pop();
        var filesAllowed = ["pdf"];
        if( (filesAllowed.indexOf(ext)) == -1)
            return "Only PDF files are allowed";
    }    

$(document).ready(function(){
    $(".validateForm").validationEngine({promptPosition : "inline", scroll: true});   
    
    var selvalue = $('#previous_injury :selected').val();
    if(selvalue == 1) {
        $('#nature_of_injury').prop( "disabled", false );
        $('#nature_of_injury').removeClass('nature_injury_cls');
    } else {       
        $('#nature_of_injury').prop( "disabled", true );
        $('#nature_of_injury').addClass('nature_injury_cls');
    }
});

$('#previous_injury').change(function() {
    if( $(this).val() == 1) {
        $('#nature_of_injury').prop( "disabled", false );
        $('#nature_of_injury').removeClass('nature_injury_cls');
    } else {       
        $('#nature_of_injury').prop( "disabled", true );
        $('#nature_of_injury').addClass('nature_injury_cls');
    }
});

$(function () {
    //Date picker
		$('#datepicker').datepicker({
			autoclose: true,
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+0"
		});
	});  

</script>