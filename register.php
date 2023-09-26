<?php
 
//  $secret = "6LejdPMUAAAAAFirYQsZWMSgHeQzodGGjYyjOkP7";
 $secret = "6LfAo_kUAAAAAHnY0R1ivhcv7BlWByXK-ZIQ6VlE";

?>

<?php include('config.php');

$error  =   "";
 
if(isset($_REQUEST["user_type"])){$user_type = $_REQUEST["user_type"];}elseif(isset($_SESSION['user_type'])){$user_type = $_SESSION['user_type'];} else {$user_type ='1';}

if(isset($_REQUEST["first_name"])){$first_name = $_REQUEST["first_name"];}elseif(isset($_SESSION['first_name'])){$first_name = $_SESSION['first_name'];} else {$first_name ='';}

if(isset($_REQUEST["last_name"])){$last_name = $_REQUEST["last_name"];}elseif(isset($_SESSION['last_name'])){$last_name = $_SESSION['last_name'];} else {$last_name ='';}

if(isset($_REQUEST["email"])){$email = $_REQUEST["email"];}elseif(isset($_SESSION['email'])){$email = $_SESSION['email'];} else {$email ='';}

if(isset($_REQUEST["team_id"])){$team_id = $_REQUEST["team_id"];}elseif(isset($_SESSION['team_id'])){$team_id = $_SESSION['team_id'];} else {$team_id ='';}

if(isset($_REQUEST["prefered_foot"])){$prefered_foot = $_REQUEST["prefered_foot"];}elseif(isset($_SESSION['prefered_foot'])){$prefered_foot = $_SESSION['prefered_foot'];} else {$prefered_foot ='';}

if(isset($_REQUEST["country"])){$country = $_REQUEST["country"];}elseif(isset($_SESSION['country'])){$country = $_SESSION['country'];} else {$country ='';}

if(isset($_REQUEST["county"])){$county = $_REQUEST["county"];}elseif(isset($_SESSION['county'])){$county = $_SESSION['county'];} else {$county ='';}

if(isset($_REQUEST["height"])){$height = $_REQUEST["height"];}elseif(isset($_SESSION['height'])){$height = $_SESSION['height'];} else {$height ='';}

if(isset($_REQUEST["weight"])){$weight = $_REQUEST["weight"];}elseif(isset($_SESSION['weight'])){$weight = $_SESSION['weight'];} else {$weight ='';}

if(isset($_REQUEST["guardian_email_addresses"])){$guardian_email_addresses = $_REQUEST["guardian_email_addresses"];}elseif(isset($_SESSION['guardian_email_addresses'])){$guardian_email_addresses = $_SESSION['guardian_email_addresses'];} else {$guardian_email_addresses ='';}
if((isset($_REQUEST["day2"]) && $_REQUEST["day2"]!='' && $_REQUEST["day"] =='') && (isset($_REQUEST["month2"]) && $_REQUEST["month2"]!='' && $_REQUEST["month"] == '') && (isset($_REQUEST["year2"]) && $_REQUEST["year2"]!='') && $_REQUEST["year"] == '') {
	$_REQUEST["day"] = $_REQUEST["day2"];
	$_REQUEST["month"] = $_REQUEST["month2"];
	$_REQUEST["year"] = $_REQUEST["year2"];
}
if((isset($_REQUEST["day"]) && $_REQUEST["day"]!='') && (isset($_REQUEST["month"]) && $_REQUEST["month"]!='') && (isset($_REQUEST["year"]) && $_REQUEST["year"]!=''))
{
   $date_of_day = $_REQUEST["day"];
   $date_of_month = $_REQUEST["month"];
   $date_of_year = $_REQUEST["year"];
    
}elseif((isset($_SESSION['day'])) && (isset($_SESSION['month'])) && (isset($_SESSION['year'])))
{
    $date_of_day = $_SESSION['day'];
    $date_of_month = $_SESSION['month'];
    $date_of_year = $_SESSION['year'];
} 
else 
{
    $_REQUEST['day'] = $date_of_day = null;
    $_REQUEST['month'] = $date_of_month = null;
    $_REQUEST['year'] = $date_of_year = null;
}


$password   =   isset($_REQUEST["password"])?$_REQUEST["password"]:'';
$password   =   $bcrypt->hash_password($password);

$dbs_number =   isset($_REQUEST["dbs_number"])?$_REQUEST["dbs_number"]:'';
$phone  =   isset($_REQUEST["phone"])?$_REQUEST["phone"]:'';
$fa_licensed  =   isset($_REQUEST["fa_licensed"])?$_REQUEST["fa_licensed"]:'';
$fanno  =   isset($_REQUEST["fanno"])?$_REQUEST["fanno"]:'';
$club_name  =   isset($_REQUEST["club_name"])?$_REQUEST["club_name"]:'';

$date_added =   date('Y-m-d H:i:s');

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_REQUEST['submit']) && isset($_POST['recaptcha_response'])) {

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    // $recaptcha_secret = '6LejdPMUAAAAABEOZ5t2Vpt3QgVko2znUIfuupPc';
    $recaptcha_secret = '6LfAo_kUAAAAAHgPUFpoaWfEwz3l6_js1TD7IXD-';
    $recaptcha_response = $_POST['recaptcha_response'];

    // Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    if (isset($recaptcha->score) && $recaptcha->score >= 0.5) {
        echo '<script>console.log("recapture succesess")</script>';
        $error = false;
        $record = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE email = ?', array($email));
        $emailresult = $record->result_array();
        if (count($emailresult) > 0) {
           $error = "Email address is already registered. Please check your email.";
        }
        
        $photo = "";
        if(!$error && isset($_FILES['profileimage']['name']) && !empty($_FILES['profileimage']['name']))
        {
            $source = $_FILES['profileimage']['tmp_name'];
            $file_name = 'Profile-' . str_pad(getAutoID($dbPrefix . 'user'), 4, '0', STR_PAD_LEFT);
            $image_upload = uploadImage(array('source' => $source, 'destination' => USER_PATH, 'file_name' => $file_name));
    
            if(isset($image_upload['success'])) {
                $photo = $image_upload['file'];
            } else if(isset($image_upload['error'])) {
                $error = 'Img - ' . $image_upload['error'];
            }
        }
        
        $docname = "";
        if(!$error && isset($_FILES['documentfile']['name']) && !empty($_FILES['documentfile']['name']))
        {
            $source = $_FILES['documentfile']['tmp_name'];
            $file_name = 'Document-' . str_pad(getAutoID($dbPrefix . 'user'), 4, '0', STR_PAD_LEFT);
            $expext= explode(".",$_FILES['documentfile']['name']);
            $type = $expext[1];
    
            $doc_upload = uploadDocument(array('source' => $source, 'destination' => USER_PATH, 'file_name' => $file_name, 'type'=>$type, 'original_name'=>$_FILES['documentfile']['name']));
    
            if(isset($doc_upload['success'])) {
                $docname = $doc_upload['file'];
            } else if(isset($doc_upload['error'])) {
                $error = 'Doc - ' . $doc_upload['error'];
            }
        }
       
        if(!$error) {
            
            if ($date_of_year != null && $date_of_month != null && $date_of_day != null) {
                $date = $date_of_year."-".$date_of_month."-".$date_of_day;
            } else {
                $date = null;
            }
            
            $userData = array(
                            'user_type' => $user_type,
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            'photo' => $photo,
                            'email' => $email,
                            'password' => $password,
                            'country_id' => $country,
                            'county_id' => $county,
                            'date_of_birth' => $date,
                            'date_added' => $date_added
                        );
            
            if ($user_type == 2) {
                
                $userData['phone'] = $phone;
                $userData['dbs_number'] = $dbs_number;
                $userData['coach_has_fa_licensed'] = $fa_licensed;
                $userData['coach_fan_number'] = $fanno;
                $userData['document_file'] = $docname;
                
            } else if ($user_type == 3) {
                
                $userData['club_id'] = $club_name;
                $userData['dbs_number'] = $dbs_number;
                $userData['document_file'] = $docname;
                
            } else {
                $userData['prefered_foot'] = $prefered_foot;
                $userData['height'] = $height;
                $userData['weight'] = $weight;
                $userData['guardian_email_addresses'] = $guardian_email_addresses;
                $userData['hidden'] = 1;
            }
                        
            $user_id = insertData('user', $userData);
            $query="SELECT * from pitch_user where id=".$userData["email"];
            $user_id=0;
            $result=$db->query($query);
            // var_dump($result);
            $row=$result->result_array();
            foreach ($row as $rows)
            {
                $user_id=$rows["id"];    
            }
            $query1="INSERT into pitch_user_test_score_seperated(user_id) VALUES($user_id)";
            $db->query($query1);
            for($i=1;$i<=20;$i++)
            {
                
                $query2="INSERT INTO pitch_videos1(user_id,video_key,video_link) VALUES($user_id,'$i','')";
                $db->query($query2);    
            }
            
            // $user_id = false;

            echo '<script>console.log("user added")</script>';

            if ($user_id){
                echo '<script>console.log("user id is there")</script>';
                $_SESSION['id'] = $user_id;
                $_SESSION['user_type'] = $user_type;
                
                if ($user_type == 1) {
                    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan WHERE status = 1 ORDER BY monthly_price ASC LIMIT 1', array());
                    
                    if ($result->num_rows() > 0) {
                    
                        $planInfo = $result->row_array();
                        $purchase_date = date('Y-m-d H:i:s');
                        $planData = array(
                                            'user_id' => $user_id,
                                            'test_plan_id' => $planInfo['id'],
                                            'price' => $planInfo['monthly_price'],
                                            'allowed_test' => $planInfo['allowed_test'],
                                            'purchase_date' => $purchase_date
                                        );
                        
                        insertData('user_test_plan', $planData);
                        echo '<script>console.log("plan added")</script>';
                    }
                }
                
                $message = '<p>Hi ' . $first_name . ' ' . $last_name. '</p>';
                $message .= '<p>You have been registered successfully.</p>';
                $msgdata = array(
                    'to' => array($email),
                    'subject' => "Account register",
                    'message' => $message
                );
                sendMsg($msgdata);

                echo '<script>console.log("go to profile")</script>';
                redirect(getLink('profile.php?registered=true'));
            }
        }   
    } else {
        echo '<script>console.log("recapture failed")</script>';
    }    
}

$selectTeam = $db->query('SELECT team.id, team.name FROM ' . $dbPrefix . 'club as team WHERE team.status=1 order by team.id', array());

$selectCountry = $db->query('SELECT country.id, country.name FROM ' . $dbPrefix . 'country as country WHERE country.status=1 order by country.name', array());

if ($country != '') {
	$selectCounty = $db->query('SELECT county.id, county.name FROM ' . $dbPrefix . 'county as county WHERE county.status=1 AND county.country_id = ? order by county.name', array($country));
	
	$selectCounty = $selectCounty->result_array();
} else {
	$selectCounty = array();
}

$document['style'][] = 'jquery-ui.css';
$document['script'][] = 'jquery-ui.js';

$document['style'][] = 'validationEngine.jquery.css';
$document['script'][] = 'jquery.validationEngine.js';
$document['script'][] = 'jquery.validationEngine-en.js';

$page_title = 'Register';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('register.php')));

include('common/header.php');
?>    

<div class="stj_login_wrap stj_reg_wrap">
	<div class="container">
		<div class="row">
			<div class="reg_dv register_form">
				<h2>Register</h2>
				<div class="reg_cd">
					<h3>Registration Details.</h3>  
									
					
                    <?php if($error){ ?>
                    <div class="alert alert-danger">
                        <?php echo "<strong>Error!</strong> " . $error; ?>
                    </div>
                    <?php } ?>

					<div class="reg_ul">
                        <form role="form" class="validateForm" name="Admin" action="" method="post" enctype="multipart/form-data">
                            <ul class="reg_select">
                                <li>
                                    <span class="iaa">I am a</span>
                                    <label class="rd_lb"><input name="user_type" value="1" class="rd_chk" type="radio" <?php if ($user_type == 1) { ?> checked <?php } ?>>Player</label>
                                    <label class="rd_lb"><input name="user_type" value="2" class="rd_chk" type="radio" <?php if ($user_type == 2) { ?> checked <?php } ?>>Coach</label>
                                    <label class="rd_lb"><input name="user_type" value="3" class="rd_chk" type="radio" <?php if ($user_type == 3) { ?> checked <?php } ?>>Scout</label>
                                </li>
                            </ul>
                            <ul class="register-fields">
                                <li>
                                    <label>Profile Picture </label>
                                    <input type="file" name="profileimage" id="profileimage" class="form-control input3 mini" data-validation-engine="validate[funcCall[geThan[]]]" data-errormessage-value-missing="Only JPG, JPEG and PNG are allowed">
                                    <p class="text-info">Please upload in portrait only</p>
                                </li>
								<li>
                                    <label>First Name <em>*</em></label>
                                    <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>" class="form-control input3 mini txt_lg" placeholder="Enter First Name" maxlength="50" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter your first name" >
                                </li>
								<li>
                                    <label>Last Name <em>*</em></label>
                                    <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>" class="form-control txt_lg" placeholder="Enter Last Name" data-validation-engine="validate[required]" maxlength="50" data-errormessage-value-missing="Please enter your last name"  >
                                </li>
								<li>
                                    <label>Email Address <em>*</em></label>
                                    <input type="email" name="email" id="email" value="<?php echo $email; ?>" class="form-control txt_lg" placeholder="Enter email" data-validation-engine="validate[required,custom[email]]" data-errormessage-value-missing="The e-mail address you entered appears to be incorrect." maxlength="70" data-errormessage-custom-error="Example: test@gmail.com" >
                                </li>
								 <li>
                                    <label>Password <em>*</em></label>
                                    <input type="password" name="password" id="password" class="form-control txt_lg" placeholder="Password" maxlength="12" data-validation-engine="validate[required,minSize[6]]" data-errormessage-value-missing="Please enter password">
                                </li>
								<li>
                                    <label>Confirm Password <em>*</em></label>
                                    <input type="password" name="rpassword" id="rpassword" class="form-control txt_lg" placeholder="Confirm Password"  maxlength="12" data-validation-engine="validate[required,minSize[6],equals[password]]" data-errormessage-value-missing="Please enter confirm password" data-errormessage-custom-error="The two passwords you entered did not match each other. Please try again.">
                                </li>
								<li class="player_fields drp_datepicker">
                                    <label>Birth Date</label>
                                    <?php /* <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" value="<?php echo $date_of_birth; ?>" name="date_of_birth" class="form-control pull-right txt_lg" id="datepicker" readonly>
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
                            
                                
                            
                                <li class="player_fields">
                                    <label>Prefered Foot <em>*</em></label>                        
                                    <select class="form-control" name="prefered_foot" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select Prefered Foot." style="width: 320px;" >
                                        <option value="">Select Prefered Foot</option>
                                        <option value="Left" <?php if($prefered_foot == "Left") { echo "selected"; } ?>>Left</option>
                                        <option value="Right" <?php if($prefered_foot == "Right") { echo "selected"; } ?>>Right</option>
                                        
                                    </select>
                                </li>
                                
                                <li class="coach_fields different">
                                    <label>Mobile No <em>*</em></label>
                                    <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" class="form-control txt_lg" placeholder="Enter Phone" maxlength="11" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter your mobile number" >
                                </li>
                            
                                <li class="scout_fields">
                                    <label>Club <em>*</em></label>
                                    <select class="form-control" name="club_name" id="club_name" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select club." style="width: 320px;" >
                                    <option value="">Select Club</option>
                                        <?php
                                        foreach ($selectTeam->result_array() as $row) {
                                            $teamName = $row['name'];    ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $club_name) { echo "selected"; } ?>><?php echo $teamName; ?></option>
                                        <?php } ?>
                                    </select>
                                </li>
                                
                                
                                
                                <li class="player_fields">
                                    <label>Height (cm) <em>*</em></label>
                                    <input type="text" name="height" id="height" value="<?php echo $height; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Height" maxlength="3" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter height." >
                                </li>
                                
                                <li class="coach_fields scout_fields">
                                    <label>DBS Number <em>*</em></label>
                                    <input type="text" name="dbs_number" id="dbs_number" value="<?php echo $dbs_number; ?>" class="form-control txt_lg" placeholder="Enter DBS Number" data-validation-engine="validate[required]" maxlength="50" data-errormessage-value-missing="Please enter DBS Number"  >
                                    <small>(required if scouting for players under 18years old)</small>
                                </li>
                                
                               <li class="coach_fields">
                                    <label>Are you a registered FA licensed coach?</label>
                                    <label class="rd_lb"><input name="fa_licensed" value="yes" class="rd_chk" type="radio" <?php if($fa_licensed == 'yes') { ?> checked <?php } ?>>Yes</label>
                                    <label class="rd_lb"><input name="fa_licensed" value="no" class="rd_chk" type="radio" <?php if($fa_licensed == 'no') { ?> checked <?php } ?>>No</label>
                                </li>
								
                                <li class="fan_li register-fields-odd" style="display:none">
                                    <input type="text" id="blank_text" name="fanno" id="fanno" value="<?php echo $fanno; ?>" class="form-control input3 mini txt_lg" placeholder="Enter FAN number">
                                </li>
                                                                
                                <li class="player_fields">
                                    <label>Weight (kg) <em>*</em></label>
                                    <input type="text" name="weight" id="weight" value="<?php echo $weight; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Weight" maxlength="3" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter weight." >
                                </li>
                                
                                <li class="coach_fields scout_fields">
                                    <label>Document File </label>
                                    <input type="file" name="documentfile" id="documentfile" class="form-control input3 mini" data-validation-engine="validate[funcCall[geThanDoc[]]]" data-errormessage-value-missing="Only PDF are allowed" >
                                </li>
                                
                                <li class="coach_fields scout_fields drp_datepicker">
                                    <label>Birth Date</label>
                                    <?php /* <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" value="<?php echo $date_of_birth; ?>" name="date_of_birth" class="form-control pull-right txt_lg" id="datepicker" readonly>
                                    </div> */ ?>
									<select name="day2">
										<option value="">Day</option>
										<?php for ($day = 1; $day <= 31; $day++) { ?>
										<option <?php if($date_of_day == $day){ echo "selected"; } ?> value="<?php echo strlen($day)==1 ? '0'.$day : $day; ?>"><?php echo strlen($day)==1 ? '0'.$day : $day; ?></option>
										<?php } ?>
									</select>
									<select name="month2">
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
									<select name="year2">
										<option value="">Year</option>
										<?php for ($year = date('Y'); $year > date('Y')-100; $year--) { ?>
										<option <?php if($date_of_year == $year){ echo "selected"; } ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
										<?php } ?>
									</select>
									
                                </li>

                                
                                
                                <li class="player_fields">
                                    <label>Parent/Guardian Email address </label>
                                    <input type="email" name="guardian_email_addresses" id="guardian_email_addresses" value="<?php echo $guardian_email_addresses; ?>" class="form-control txt_lg" placeholder="Enter email"  maxlength="70" />
                                </li>
                                
                                <?php /*
                                <li class="player_fields">
                                    <label>Current Team <em>*</em></label>
                                    <select class="form-control" name="team_id" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select team." style="width: 320px;" >
                                        <option value="">Select Current Team</option>
                                        <?php
                                        foreach ($selectTeam->result_array() as $row) {
                                            $teamName = $row['name'];    ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $team_id) { echo "selected"; } ?>><?php echo $teamName; ?></option>
                                        <?php } ?>
                                    </select>
                                </li>
                                */ ?>
                            
                                
                                
                                                            
                                <?php /*
                                <li>
                                    <label><input type="checkbox" value="1" name="status" <?php if($status) { echo "checked"; }?> data-validation-engine="validate[required]" data-errormessage-value-missing="Please indicate that you accept the Terms and Conditions." > Terms and Conditions </label>
                                </li>
                                */ ?>
    						<li>
							<!-- <div id="g-recaptcha" class="g-recaptcha" data-sitekey="6Lc0_rIUAAAAAN3tLAnEYjlD5pTpmAu0VOdyBzja"></div> -->
							<!-- <div id="g-recaptcha" class="g-recaptcha"></div> -->
                            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">
						</li>
						
                                <li class="btn_li">
                                    <button id="submitBtn" type="submit" name="submit" class="btn_lg">Submit</button>
                                </li>    
                            </ul>
                        </form>
						  <!--js-->
                          <!-- <script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit'></script>   -->
                          <script src="https://www.google.com/recaptcha/api.js?render=<?= $secret ?>"></script>

                    </div>

				</div>
			</div>
		</div>
	</div>
</div>

<script>
	/* var FromEndDate = new Date();
    $(function () {
		//Date picker
		$('#datepicker').datepicker({
			autoclose: true,
			dateFormat: 'dd-mm-yy',
			endDate: FromEndDate
		});
    }); */

    grecaptcha.ready(function() {
        grecaptcha.execute('<?= $secret ?>', {action: 'login'}).then(function(token) {
            var recaptchaResponse = document.getElementById('recaptchaResponse');
            recaptchaResponse.value = token;
        });
    });

   
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
    
    function formatFormDesign() {
        $('.validateForm .register-fields li').removeClass('register-fields-odd');
        $('.validateForm .register-fields li').removeClass('register-fields-even');
        
        var i = 1;
        $('.validateForm .register-fields li:visible').each(function() {
            if (i%2 == 0) {
                $(this).addClass( 'register-fields-even');
            } else {
                $(this).addClass( 'register-fields-odd');
            }
            i++;
        });
    }
    
    $('input[name="user_type"]').click(function(){
        var radio = $('input[name=user_type]:checked').val();
       	if(radio == "2"){
            $(".player_fields").hide();
            $(".scout_fields").hide();
            $(".coach_fields").show();
			var coa_radio = $('input[name=fa_licensed]:checked').val();
			 if(coa_radio == 'yes') {
					$(".fan_li").show();
			 }
        } else if(radio == "3"){
            $(".player_fields").hide();
            $(".coach_fields").hide();
            $(".scout_fields").show();
			$(".fan_li").hide();
        } else {
            $(".coach_fields").hide();
            $(".scout_fields").hide();
            $(".player_fields").show();
			$(".fan_li").hide();
        }
        
        formatFormDesign();

    });

	$(document).ready(function(){
		$(".validateForm").validationEngine({promptPosition : "inline", scroll: true});
        
        var user_type = $('input[name="user_type"][value="<?php echo $user_type; ?>"]');
        user_type.trigger('click');
        user_type.parent().addClass('active');
        //$(".validateForm li:visible").addClass('user-fields');
        
        $('.rd_lb input:radio').click(function() {
            $('.rd_lb input:radio[name='+$(this).attr('name')+']').parent().removeClass('active');
                $(this).parent().addClass('active');
        });	
        
        formatFormDesign();
		var select_coa_radio = $('input[name="fa_licensed"][value="<?php echo $fa_licensed; ?>"]');
        select_coa_radio.trigger('click');
		if('<?php echo $fa_licensed; ?>' == 'yes') {
				$(".fan_li").show();
			 }else {
				 $('#blank_text').val("");
				 $(".fan_li").hide();
			 }
		$('input[name="fa_licensed"]').click(function(){
			var coa_radio = $('input[name=fa_licensed]:checked').val();
			 if(coa_radio == 'yes') {
					$(".fan_li").show();
			 }else {
				 $('#blank_text').val("");
				 $(".fan_li").hide();
			 }
			});
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

<?php include('common/footer.php');?>