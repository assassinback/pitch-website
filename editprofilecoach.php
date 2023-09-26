<?php include('config.php');

$id = $_SESSION['id'];

if (isset($_GET['type'])) {
    $type = $_GET['type'];
} else {
    $type = '';
}

if (!$id) {
    redirect(getLink());
}

$error  =   '';
$success_msg ='';

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE id = ? AND status = 1', array($id));

$userInfo = $result->row_array();

$profile_image =   $userInfo['photo'];

$first_name =   $userInfo['first_name'];
$last_name =   $userInfo['last_name'];
$date_of_birth =   $userInfo['date_of_birth'];
if($date_of_birth != '' && $date_of_birth != "0000-00-00 00:00:00")
{
		$exploded_date = date('d/m/Y',strtotime($date_of_birth));
		$explodedate = explode("/",$exploded_date);
		$date_of_day = $explodedate[0];
		$date_of_month = $explodedate[1];
		$date_of_year = $explodedate[2];
} else {
    $date_of_day = $date_of_month = $date_of_year = null;
}
$country =   $userInfo['country_id'];
$county =   $userInfo['county_id'];
$hidden =   $userInfo['hidden'];

$highest_education_level =   $userInfo['highest_education_level'];
$coaching_qualification =   $userInfo['coaching_qualification'];
$years_of_experience =   $userInfo['years_of_experience'];
$currently_working_for =   $userInfo['currently_working_for'];
$previously_worked_for =   $userInfo['previously_worked_for'];
$dbs_number =   $userInfo['dbs_number'];
$club_played_at_highest_level =   $userInfo['club_played_at_highest_level'];

$about_me =   $userInfo['about_me'];

$coach_values_and_coaching_philosophy_video =   $userInfo['coach_values_and_coaching_philosophy_video'];
$coach_values_and_coaching_philosophy =   $userInfo['coach_values_and_coaching_philosophy'];

$recent_success_video =   $userInfo['recent_success_video'];
$recent_success =   $userInfo['recent_success'];

$testimonial_of_how_good_coach_i_am_video =   $userInfo['testimonial_of_how_good_coach_i_am_video'];
$testimonial_of_how_good_coach_i_am =   $userInfo['testimonial_of_how_good_coach_i_am'];

$date_modified =   date('Y-m-d H:i:s');

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_REQUEST['update'])) {

$first_name =   isset($_REQUEST["first_name"])?$_REQUEST["first_name"]:'';
$last_name =   isset($_REQUEST["last_name"])?$_REQUEST["last_name"]:'';
/* $date_of_birth  =   isset($_REQUEST["date_of_birth"])&&$_REQUEST["date_of_birth"]!=''?date('Y-m-d',strtotime(str_replace('/', '-', $_REQUEST["date_of_birth"]))):''; */
$date_of_day = isset($_REQUEST["day"])?$_REQUEST["day"]:'';
$date_of_month = isset($_REQUEST["month"])?$_REQUEST["month"]:'';
$date_of_year = isset($_REQUEST["year"])?$_REQUEST["year"]:'';
$country    =   isset($_REQUEST["country"])?$_REQUEST["country"]:'';
$county =   isset($_REQUEST["county"])?$_REQUEST["county"]:'';
$hidden =   isset($_REQUEST["hidden"])?$_REQUEST["hidden"]:0;

print($_REQUEST["hidden"]);

$highest_education_level =   isset($_REQUEST["highest_education_level"])?$_REQUEST["highest_education_level"]:'';
$coaching_qualification =   isset($_REQUEST["coaching_qualification"])?$_REQUEST["coaching_qualification"]:'';
$years_of_experience =   isset($_REQUEST["years_of_experience"])?$_REQUEST["years_of_experience"]:'';
$currently_working_for =   isset($_REQUEST["currently_working_for"])?$_REQUEST["currently_working_for"]:'';
$previously_worked_for =   isset($_REQUEST["previously_worked_for"])?$_REQUEST["previously_worked_for"]:'';
$dbs_number =   isset($_REQUEST["dbs_number"])?$_REQUEST["dbs_number"]:'';
$club_played_at_highest_level =   isset($_REQUEST["club_played_at_highest_level"])?$_REQUEST["club_played_at_highest_level"]:'';

$about_me =   isset($_REQUEST["about_me"])?$_REQUEST["about_me"]:'';

$coach_values_and_coaching_philosophy_video =   isset($_REQUEST["coach_values_and_coaching_philosophy_video"])?$_REQUEST["coach_values_and_coaching_philosophy_video"]:'';
$coach_values_and_coaching_philosophy =   isset($_REQUEST["coach_values_and_coaching_philosophy"])?$_REQUEST["coach_values_and_coaching_philosophy"]:'';

$recent_success_video =   isset($_REQUEST["recent_success_video"])?$_REQUEST["recent_success_video"]:'';
$recent_success =   isset($_REQUEST["recent_success"])?$_REQUEST["recent_success"]:'';

$testimonial_of_how_good_coach_i_am_video =   isset($_REQUEST["testimonial_of_how_good_coach_i_am_video"])?$_REQUEST["testimonial_of_how_good_coach_i_am_video"]:'';
$testimonial_of_how_good_coach_i_am =   isset($_REQUEST["testimonial_of_how_good_coach_i_am"])?$_REQUEST["testimonial_of_how_good_coach_i_am"]:'';

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

$date_modified =   date('Y-m-d H:i:s');
    
    $error = false;
       
    if(!$error) {
        
        if($type == 'image_info') {   
            $userData = array(
                        'photo' => $photo,
                        'date_modified' => $date_modified
                    );
            $userInfo['photo'] = $photo;
        }        
        else if($type == 'about_me') {   
            $userData = array(
                        'about_me' => $about_me,
                        'date_modified' => $date_modified
                    );
        }
        else if($type == 'coach_values_coaching_philosophy') {   
            $userData = array(
                        'coach_values_and_coaching_philosophy_video' => $coach_values_and_coaching_philosophy_video,
                        'coach_values_and_coaching_philosophy' => $coach_values_and_coaching_philosophy,
                        'date_modified' => $date_modified
                    );
        }
        else if($type == 'playing_position_second_coach') {   
            $userData = array(
                        'highest_education_level' => $highest_education_level,
                        'years_of_experience' => $years_of_experience,
                        'currently_working_for' => $currently_working_for,
                        'previously_worked_for' => $previously_worked_for,
                        'dbs_number' => $dbs_number,
                        'coaching_qualification' => $coaching_qualification
                    );
        }
        else if($type == 'recent_success') {   
            $userData = array(
                        'recent_success_video' => $recent_success_video,
                        'recent_success' => $recent_success,
                        'date_modified' => $date_modified
                    );
        }
        else if($type == 'testimonial_of_how_good_coach_i_am') {   
            $userData = array(
                        'testimonial_of_how_good_coach_i_am_video' => $testimonial_of_how_good_coach_i_am_video,
                        'testimonial_of_how_good_coach_i_am' => $testimonial_of_how_good_coach_i_am,
                        'date_modified' => $date_modified
                    );
        }    
        else {
			$date = $date_of_year."-".$date_of_month."-".$date_of_day;
            $userData = array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'country_id' => $country,
                'county_id' => $county,
                'date_of_birth' => $date,
                'date_modified' => $date_modified,
                'hidden' => $hidden
            );
        }            
        
        $where ='id =' . $id .'';
        $update = updateData('user', $userData, $where);
        //echo $db->last_query();
        //exit;
        
        if($update)
        {
            $success_msg = "Profile has been successfully updated.";
			redirect(getLink('profile.php'));
        }
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

$document['style'][] = 'jquery-ui.css';
$document['script'][] = 'jquery-ui.js';

$document['style'][] = 'validationEngine.jquery.css';
$document['script'][] = 'jquery.validationEngine.js';
$document['script'][] = 'jquery.validationEngine-en.js';

$document['style'][] = 'summernote/summernote.css';
$document['script'][] = 'summernote/summernote.js';

$page_title = 'Edit Profile';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => 'View Profile', 'link' => getLink('profile.php')), array('title' => $page_title, 'link' => getLink('editprofile.php')));

include('common/header.php');
?>    

<div class="stj_login_wrap stj_reg_wrap">
	<div class="container">
		<div class="row">
			<div class="reg_dv edit_profile">
				<h2>Edit Profile</h2>
				<div class="reg_cd">
					<h3>Update Coach details</h3>
                    
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
                    
					<div class="reg_ul">
                        <form role="form" class="validateForm" name="Admin" action="" method="post" enctype="multipart/form-data">
                            
                            <ul class="register-fields">
                                <?php if($type =='image_info') { ?>
                                <li class="text_cum">
                                    <label>Profile Picture </label><br>
                                    <div class="edit-profile-image" >
                                    <?php echo getUserProfileImage($userInfo['photo'], 'coach', 'width="100"'); ?>
                                    </div><br><br>
                                    <input type="file" name="profileimage" id="profileimage" class="form-control input3 mini" data-validation-engine="validate[funcCall[geThan[]]]" data-errormessage-value-missing="Only JPG, JPEG and PNG are allowed">
                                    <p class="text-info">Please upload in portrait only</p>
                                </li>
                                
                                <?php } else if($type =='about_me') { ?>                             
                                <li class="text_cum">
                                    <label>About Me</label>
                                    <textarea rows="7" cols="50" name="about_me" id="about_me" class="summernote" placeholder="Enter Content" ><?php echo $about_me; ?></textarea>
                                </li>
                                
                                <?php } else if($type =='testimonial_of_how_good_coach_i_am') { ?>
                                
                                <li class="text_cum">
                                    <label>Testimonial video</label>
                                    <input type="text" name="testimonial_of_how_good_coach_i_am_video" id="testimonial_of_how_good_coach_i_am_video" value="<?php echo $testimonial_of_how_good_coach_i_am_video; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Video URL" >
                                </li>                                
                                <li class="text_cum">
                                    <label>Testimonial Content</label>
                                    <textarea rows="7" cols="50" name="testimonial_of_how_good_coach_i_am" id="testimonial_of_how_good_coach_i_am" class="summernote" placeholder="Enter Content" ><?php echo $testimonial_of_how_good_coach_i_am; ?></textarea>
                                </li>
                                
                                <?php } else if($type =='recent_success') { ?>
                                
                                <li class="text_cum">
                                    <label>Recent Successes Video</label>
                                    <input type="text" name="recent_success_video" id="recent_success_video" value="<?php echo $recent_success_video; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Video URL" >
                                </li>                                
                                <li class="text_cum">
                                    <label>Recent Successes Content</label>
                                    <textarea rows="7" cols="50" name="recent_success" id="recent_success" class="summernote" placeholder="Enter Content" ><?php echo $recent_success; ?></textarea>
                                </li>
                                
                                <?php } else if($type =='coach_values_coaching_philosophy') { ?>
                                
                                <li class="text_cum">
                                    <label>Core values and coaching philosophy Video</label>
                                    <input type="text" name="coach_values_and_coaching_philosophy_video" id="coach_values_and_coaching_philosophy_video" value="<?php echo $coach_values_and_coaching_philosophy_video; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Video URL" >
                                </li>                                
                                <li class="text_cum">
                                    <label>Core values and coaching philosophy Content</label>
                                    <textarea rows="7" cols="50" name="coach_values_and_coaching_philosophy" id="coach_values_and_coaching_philosophy" class="summernote" placeholder="Enter Content" ><?php echo $coach_values_and_coaching_philosophy; ?></textarea>
                                </li>
                                
                                <?php } else if($type =='playing_position_second_coach') { ?>                             
                                <li>
                                    <label>Highest Education Level</label>
                                    <input type="text" name="highest_education_level" id="highest_education_level" value="<?php echo $highest_education_level; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Highest Education Level" >
                                </li>
                                <li>
                                    <label>Coaching Qualification</label>
                                    <input type="text" name="coaching_qualification" id="coaching_qualification" value="<?php echo $coaching_qualification; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Coaching Qualification" >
                                </li>
                                <li>
                                    <label>Years of Experience</label>
                                    <input type="text" name="years_of_experience" id="years_of_experience" value="<?php echo $years_of_experience; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Years of Experience" >
                                </li>
								</ul>
								<ul class="register-fields reg_f">
                                <li>
                                    <label>Currently working for</label>
                                    <input type="text" name="currently_working_for" id="currently_working_for" value="<?php echo $currently_working_for; ?>" class="form-control input3 mini txt_lg" placeholder="Enter currently working for" >
                                </li>
                                <li>
                                    <label>Previously worked for</label>
                                    <input type="text" name="previously_worked_for" id="previously_worked_for" value="<?php echo $previously_worked_for; ?>" class="form-control input3 mini txt_lg" placeholder="Enter previously worked for" >
                                </li>
                                <li class="coach_fields scout_fields">
                                    <label>DBS Number <em>*</em></label>
                                    <input type="text" name="dbs_number" id="dbs_number" value="<?php echo $dbs_number; ?>" class="form-control txt_lg" placeholder="Enter DBS Number" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter DBS Number"  >
                                </li>
                               
                                <?php } else { ?>
                                
                                <li>
                                    <label>First Name <em>*</em></label>
                                    <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>" class="form-control input3 mini txt_lg" placeholder="Enter First Name" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter your first name" >
                                </li>
                            
                                <li>
                                    <label>Last Name <em>*</em></label>
                                    <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>" class="form-control txt_lg" placeholder="Enter Last Name" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter your last name"  >
                                </li>
							
                                <?php
                                    $date = '';
                                    if($date_of_birth != '')
                                    {
                                        $date = date('d/m/Y',strtotime($date_of_birth));
                                    }
                                ?>
                                <li class="drp_datepicker">
                                    <label>Birth Date </label>
                                    <?php  /* <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" value="<?php echo $date; ?>" name="date_of_birth" class="form-control pull-right txt_lg" id="datepicker">
                                    </div> */ ?>
									<select name="day">
										<option value="">Day</option>
										<?php for ($day = 1; $day <= 31; $day++) { ?>
										<option <?php if($date_of_day == $day){ echo "selected"; } ?> value="<?php  echo strlen($day)==1 ? '0'.$day : $day; ?>"><?php echo strlen($day)==1 ? '0'.$day : $day; ?></option>
										<?php } ?>
									</select>
									<select name="month">
										<option value="">Month</option>
										<?php for ($month = 1; $month <= 12; $month++) { ?>
										<?php 
											//$month_name = date( 'F', mktime( 0, 0, 0, $month + 1, 0, 0, 0 ) );
                                            $dateObj   = DateTime::createFromFormat('!m', $month);
                                            $month_name = $dateObj->format('F');
										?>
										<option <?php if($date_of_month == $month){ echo "selected"; } ?> value="<?php echo strlen($month)==1 ? '0'.$month : $month; ?>"><?php echo $month_name; ?></option>
										<?php } ?>
									</select>
									<select name="year">
										<option value="">Year</option>
										<?php for ($year = date('Y'); $year > date('Y')-100; $year--) { ?>
										<option <?php if($date_of_year == $year){ echo "selected"; } ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
										<?php } ?>
									</select>
                                </li>
                                </ul>
								<ul class="register-fields reg_f">
                                <li>
                                    <label>Country <em>*</em></label>
                                    <select class="form-control" name="country" id="country" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select country." style="width: 320px;" onchange="getCounty(this.value)" >
                                        <option value="">Select Country</option>
                                        <?php
                                        foreach ($selectCountry->result_array() as $row) {
                                            $countryName = $row['name'];    ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $country) { echo "selected"; } ?>><?php echo $countryName; ?></option>
                                        <?php } ?>
                                    </select>
                                </li>
                                
                                <li>
                                    <label>County <em>*</em></label>
                                    <select class="form-control" name="county" id="county" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select county." style="width: 320px;" >
                                                
                                        <option value="">Select County</option>
                                        <?php
                                        foreach ($selectCounty as $row) {
                                            $countyName = $row['name'];    ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $county) { echo "selected"; } ?>><?php echo $countyName; ?></option>
                                        <?php } ?>
                                        
                                    </select>
                                </li>
                                <li>
                                    <label>Profile visibility<em>*</em></label>
                                    <select class="form-control" name="hidden" id="hidden" data-validation-engine="validate[required]" data-errormessage-value-missing="Account visibility." style="width: 320px;" >
                                                
                                        <!-- <option value="">Select visibility</option> -->
                                        <?php
                                        foreach (array("Visible", "Hidden") as $key => $row) { ?>
                                            <option value="<?php echo $key; ?>" <?php if($key == $hidden) { echo "selected"; } ?>><?php echo $row; ?></option>
                                        <?php } ?>
                                        
                                    </select>
                                </li>
                                <?php } ?>
                                
                                 
                            </ul>
							<ul class="text_center edit_btn_cusm">
								<li class="btn_li cusm_rightbtn">
									<a class="btn_lg" href="<?php echo getLink('profile.php'); ?>" onclick="return confirm('Are you sure?');" >Back</a>
                                    <button type="submit" name="update" class="btn_lg">Update</button>
                                </li> 
							</ul>
                        </form>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>

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
	
$(document).ready(function(){
    $(".validateForm").validationEngine({promptPosition : "inline", scroll: true});
    
    //$('.summernote').summernote();
    
});

function geThan(){
	
        var extFile  = document.getElementById("profileimage").value;
        var ext = extFile.split('.').pop();
        var filesAllowed = ["jpg", "jpeg", "png"];
        if( (filesAllowed.indexOf(ext)) == -1)
            return "Only JPG, JPEG , PNG files are allowed";
    }

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

<?php include('common/footer.php');?>