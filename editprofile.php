<link rel="stylesheet" href= 
"https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
	integrity= 
"sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
	crossorigin="anonymous"> 

<script src= 
"https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"> 
</script> 
<script src= 
"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"> 
</script> 
<script src= 
"https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"> 
</script> 
<style>
    .reg_dv .reg_cd ul li
    {
        border:none !important;
    }
    
</style>
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
$team_id =   $userInfo['team_id'];
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
$height =   $userInfo['height'];
$weight =   $userInfo['weight'];
$country =   $userInfo['country_id'];
$county =   $userInfo['county_id'];

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
$team_id =   isset($_REQUEST["team_id"])?$_REQUEST["team_id"]:0;
//$date_of_birth  =   isset($_REQUEST["date_of_birth"])&&$_REQUEST["date_of_birth"]!=''?date('Y-m-d',strtotime(str_replace('/', '-', $_REQUEST["date_of_birth"]))):'';
$date_of_day = isset($_REQUEST["day"])?$_REQUEST["day"]:'';
$date_of_month = isset($_REQUEST["month"])?$_REQUEST["month"]:'';
$date_of_year = isset($_REQUEST["year"])?$_REQUEST["year"]:'';
$height =   isset($_REQUEST["height"])?$_REQUEST["height"]:'';
$weight =   isset($_REQUEST["weight"])?$_REQUEST["weight"]:'';
$country    =   isset($_REQUEST["country"])?$_REQUEST["country"]:'';
$county =   isset($_REQUEST["county"])?$_REQUEST["county"]:'';

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

$what_are_your_core_values =   isset($_REQUEST["what_are_your_core_values"])?$_REQUEST["what_are_your_core_values"]:'';

$what_sort_of_character_are_you =   isset($_REQUEST["what_sort_of_character_are_you"])?$_REQUEST["what_sort_of_character_are_you"]:'';

$resume_video =   isset($_REQUEST["resume_video"])?$_REQUEST["resume_video"]:'';
$resume_video_content =   isset($_REQUEST["resume_video_content"])?$_REQUEST["resume_video_content"]:'';

$technical_ability_video =   isset($_REQUEST["technical_ability_video"])?$_REQUEST["technical_ability_video"]:'';
$technical_ability_video_content =   isset($_REQUEST["technical_ability_video_content"])?$_REQUEST["technical_ability_video_content"]:'';

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
        else if($type == 'resume_video') {   
            $userData = array(
                        'resume_video' => $resume_video,
                        'resume_video_content' => $resume_video_content,
                        'date_modified' => $date_modified
                    );
        }
        else if($type == 'technical_ability_video') {   
            $userData = array(
                        'technical_ability_video' => $technical_ability_video,
                        'technical_ability_video_content' => $technical_ability_video_content,
                        'date_modified' => $date_modified
                    );
        }
        else if($type == 'playing_position_second') {   
            $userData = array(
                        '1st_player_position' => $first_player_position,
                        '2nd_player_position' => $second_player_position,
                        '3rd_player_position' => $third_player_position,
                        'prefered_foot' => $prefered_foot,
                        'date_modified' => $date_modified
                    );
        }
        else if($type == 'playing_position') {   
            $userData = array(
                        'highest_education_level' => $highest_education_level,
                        'previous_injury' => $previous_injury,
                        'nature_of_injury' => $nature_of_injury,
                        'years_playing_football' => $years_playing_football,
                        'highest_level_played_at' => $highest_level_played_at,
                        'club_played_at_highest_level' => $club_played_at_highest_level,
                        'date_modified' => $date_modified
                    );
        }
        else if($type == 'core_values') {   
            $userData = array(
                        'what_are_your_core_values' => $what_are_your_core_values,
                        'date_modified' => $date_modified
                    );
        }
        else if($type == 'what_sort_of_personality') {   
            $userData = array(
                        'what_sort_of_character_are_you' => $what_sort_of_character_are_you,
                        'date_modified' => $date_modified
                    );
        }    
        else {
			$date = $date_of_year."-".$date_of_month."-".$date_of_day;
            $userData = array(
                            'first_name' => $first_name,
                            'last_name' => $last_name,
                            //'team_id' => $team_id,
                            'height' => $height,
                            'weight' => $weight,
                            'country_id' => $country,
                            'county_id' => $county,
                            'date_of_birth' => $date,
                            'date_modified' => $date_modified
                        );
        }            
        
			/* print_r($userData);
			exit; */
        $where ='id =' . $id .'';
        $update = updateData('user', $userData, $where);
        //echo $db->last_query();
        //exit;
        if($update)
        {
            // if(isset($_POST["technical_ability_video"]))
            // {
                
            //     $videolink=$_POST["technical_ability_video"];
            //     $videolink= str_replace("watch?v=","embed/",$videolink);
            //     //https://vimeo.com/465476455
                
            //     $videolink= str_replace("watch?v=","embed/",$videolink);
            //     $user_id=$_SESSION['id'];
            //     if (strpos($videolink,"vimeo.com") !== false) {
            //         $videolink= str_replace("watch?v=","embed/",$videolink);
                   
            //     }
            //     $sql="UPDATE pitch_user set technical_ability_video='$videolink' where id=$user_id";
            //     ?>
                    
            //     <?php
            //     if ($db->query($sql)) 
            //     {
                	                      
            //     }
            //     else 
            //     {
            //         echo "Error: " . $db->error;
            //     }
            // }
            if(isset($_POST["1_video"]))
            {
                $user_id=$_SESSION['id'];
                // $query="SELECT COUNT(*) FROM pitch_videos where user_id=$user_id";
                // $result = $db->query($query);
                // $row=$result->row_array();
                // $count=1;
                // $arraylength=0;
                // while()
                for($i=1;$i<=20;$i++)
                {
                    if(isset($_POST[$i.'_video']) and $_POST[$i.'_video']!='')
                    {
                        // $checking=false;
                        $video_link=$_POST[$i.'_video'];
                        $video_title=$_POST[$i.'_title'];
                        $query2="UPDATE pitch_videos1 set video_link='$video_link',video_title='$video_title' where user_id=$user_id and video_key='$i'";
                        if (!$result2=$db->query($query2)) {
                            echo("Error description: " . $db -> error);
                        }
                        // $query2="SELECT * FROM pitch_videos1 where user_id=$user_id";
                        // if (!$result2=$db->query($query2)) {
                        //     echo("Error description: " . $db -> error);
                        // }
                        // $row2 = $result2->row_array();
                        // foreach($row2 as $rows2)
                        // {
                        //     echo "<script>alert('$key');</script>";
                        //     $key=$rows2['video_key'];
                        //     echo "<script>alert('$key');</script>";
                        //     // echo "<script>alert('".$rows2['video_key']." here $i');</script>";
                        //     if((int)$rows2['video_key']==(2))
                        //     {
                                
                        //         $query3="UPDATE pitch_videos1 set video_link='$video_link' where user_id=$user_id and video_key='$i'";
                        //         if (!$result3=$db->query($query3)) {
                        //             echo("Error description: " . $db -> error);
                        //         }
                         
                        //     }
                        // }
                        // if($checking==false)
                        // {
                        //     $videoLink=$_POST[$i.'_video'];
                        //     $query="INSERT INTO pitch_videos1(user_id,video_key,video_link) VALUES($user_id,'$i','$videoLink')";
                        //     $result = $db->query($query);
                        // }
                    }
                }
                redirect(getLink('profile.php?tab=technical_ability'));
            }
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

$selectTeam = $db->query('SELECT team.id, team.name FROM ' . $dbPrefix . 'club as team WHERE team.status=1 order by team.id', array());

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
<!--<script>alert("here");</script>-->
<?php
//1-video
if(isset($_POST['1_video']))
{
    // echo "abcd";
    ?>
    <script>//alert("here");</script>
    <?php
    $user_id=$_SESSION['id'];
    // $query="SELECT COUNT(*) FROM pitch_videos where user_id=$user_id";
    // $result = $db->query($query);
    // $row=$result->row_array();
    // $count=1;
    // $arraylength=0;
    // while()
    for($i=0;$i<100;$i++)
    {
        if(isset($_POST[$i.'_video']))
        {
            $videoLink=$_POST[$i.'-video'];
            $query="INSERT INTO pitch_videos(user_id,video_key,video_link) VALUES($user_id,'$i-video','$videoLink')";
            $result = $db->query($query);
        }
    }
    
}
?>
<script>
var rowIdx = 0; 
    function testfunc(){
                                            $('#tbody1').append(`<tr id="R${++rowIdx}"> 
                                        					<tr>
                                        
                                        <td class="row-index text-center"> 
                                        	<p>${rowIdx}</p> 
                                        		</td> 
                                        		<td><input type="text" placeholder="Enter Video Title" name="${rowIdx}_title"></td>
                                        		<td><input type="text" placeholder="Enter Video Link" name="${rowIdx}_video"></td>
                                        		
                                        <td><button class="btn btn-danger remove" type="button">Remove</button> </td>
                                        </tr>
                                        		</tr>`); 
                                        }
                                        function testfunc1(link1,title){
                                            $('#tbody1').append(`<tr id="R${++rowIdx}"> 
                                        					<tr>
                                        
                                        <td class="row-index text-center"> 
                                        	<p>${rowIdx}</p> 
                                        		</td>
                                        		<td><input type="text" placeholder="Enter Video Title" name="${rowIdx}_title" value='`+title+`'></td>
                                        		<td><input type="text" placeholder="Enter Video Link" name="${rowIdx}_video" value='`+link1+`'></td>
                                        		
                                        <td><button class="btn btn-danger remove" type="button">Remove</button> </td>
                                        </tr>
                                        		</tr>`); 
                                        }
</script>
<div class="stj_login_wrap stj_reg_wrap edit_p">
	<div class="container">
		<div class="row">
			<div class="reg_dv edit_profile">
				<h2>Edit Profile</h2>
				<div class="reg_cd">
					<h3>Update Player Details</h3>
                    
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
                                    <?php echo getUserProfileImage($userInfo['photo'], 'player', 'width="100"'); ?>
                                    </div><br><br>
                                    <input type="file" name="profileimage" id="profileimage" class="form-control input3 mini" data-validation-engine="validate[funcCall[geThan[]]]" data-errormessage-value-missing="Only JPG, JPEG and PNG are allowed">
                                    <p class="text-info">Please upload in portrait only</p>
                                </li>
                                <?php } else if($type =='resume_video') { ?>                             
                                <li>
                                    <label>Résumé Video </label>
                                    <input type="text" name="resume_video" id="resume_video" value="<?php echo $resume_video; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Video URL" >
                                    <p><i>Please upload a 30-60 second video stating to all the scouts, managers and coaches your core values and characteristics, who you have previously played for and why you think you should be given a chance or trial.</i><p>
                                </li>
                                <li style="display:none;">
                                    <label>Résumé Video Content</label>
                                    <textarea rows="7" cols="50" name="resume_video_content" id="resume_video_content" class="summernote" placeholder="Enter Content" ><?php echo $resume_video_content; ?></textarea>
                                </li>
                                
                                <?php } else if($type =='technical_ability_video') { ?>                  
                                <li>
                                    <label>Technical Ability Video </label>
                                    <i><p>Please Enter comma before each link and enter embed links only</p></i>
                                    <?php
                                        $user_id=$_SESSION['id'];
                                        $query="SELECT * FROM pitch_videos where user_id=$user_id";
                                        ?>
                                        <button class="btn btn-md btn-primary"id="addBtn1" type="button"> 
                                        		Add new Row 
                                        	</button> 
                                        	<table class="table table-bordered"> 
                            			<thead> 
                            			<tr> 
                            				<th>Video Number</th>
                            				<th>Video Title</th>
                            				<th>Video Link</th>
                            				<th>Remove</th>
                            				
                            				
                            			</tr> 
                            		
                            			</thead> 
                            			<tbody id="tbody1"> 
	                                        <?php
	                                            $user_id=$_SESSION['id'];
	                                            $query="SELECT * FROM pitch_videos1 where user_id=$user_id";
	                                            $result=$db->query($query);
	                                            $row = $result->result_array();
	                                            $count=sizeof($row);
	                                           // echo $count;
	                                            $newcount=1;
	                                            $check=false;
	                                            foreach($row as $rows)
	                                            {
	                                                if($newcount==1)
	                                                {
    	                                                if($rows['video_link']=='' and $check==false)
    	                                                {
    	                                                       ?>
    	                                                       <script>
    	                                                           testfunc();
    	                                                       </script>
    	                                                       <?php
    	                                                       $newcount++;
    	                                                }
	                                                }
	                                                if($rows['video_link']!='' and $rows['video_title']!='')
	                                                {
	                                                    $check=true;    
	                                                    ?>
	                                                       <script>
	                                                           testfunc1('<?php echo $rows['video_link'] ?>','<?php echo $rows["video_title"]; ?>');
	                                                       </script>
	                                                       <?php
	                                                }
	                                            }
	                                        ?>
                            			</tbody>
                            			</table>
                                        <script>
                                        
                                        $(document).ready(function () { 

                                        	// Denotes total number of rows 
                                        	
                                        	$('#addBtn1').on('click', function () { 

                                        	// Adding a row inside the tbody. 
                                        	$('#tbody1').append(`<tr id="R${++rowIdx}"> 
                                        					<tr>
                                        
                                        <td class="row-index text-center"> 
                                        	<p>${rowIdx}</p> 
                                        		</td> 
                                        		<td><input type="text" placeholder="Enter Video Link" name="${rowIdx}_video"></td>
                                        		<td><input type="text" placeholder="Enter Video Title" name="${rowIdx}_title"></td>
                                        <td><button class="btn btn-danger remove" type="button">Remove</button> </td>
                                        </tr>
                                        		</tr>`); 
                                        });
                                        $('#tbody1').on('click', '.remove', function () { 

	// Getting all the rows next to the row 
	// containing the clicked button 
	var child = $(this).closest('tr').nextAll(); 

	// Iterating across all the rows 
	// obtained to change the index 
	child.each(function () { 

	// Getting <tr> id. 
	var id = $(this).attr('id'); 

	// Getting the <p> inside the .row-index class. 
	var idx = $(this).children('.row-index').children('p'); 

	// Gets the row number from <tr> id. 
	var dig = parseInt(id.substring(1)); 

	// Modifying row index. 
	idx.html(`Row ${dig - 1}`); 

	// Modifying row id. 
	$(this).attr('id', `R${dig - 1}`); 
	}); 

	// Removing the current row. 
	$(this).closest('tr').remove(); 

	// Decreasing total number of rows by 1. 
	rowIdx--; 
});
                                        });
                                            
                                                                                </script>
                                        <?php
                                    ?>
                                    <!--<input type="text" name="technical_ability_video" id="technical_ability_video" value="<?php echo $technical_ability_video; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Video URL" >-->
                                    <i><p><ul><li>Please upload a video showing what you do best. For example, if you are a defender show yourself defending, if you are a midfield player show your passing and running ability, if you are a striker show your shooting and positioning. </li>
<li>Please upload a full-length video of match play if you have it. This will help scouts to get a good feel of how you play in a game. </li>
</ul></p></i>
                                <li>
                                    <label>Technical Ability Video Content</label>
                                    <textarea rows="7" cols="50" name="technical_ability_video_content" id="technical_ability_video_content" class="summernote" placeholder="Enter Content" ><?php echo $technical_ability_video_content; ?></textarea>
                                </li>
                                <?php } else if($type =='playing_position_second') { ?>
                                <li>
                                    <label>1st Playing Position</label>
                                    <select class="form-control" name="1st_player_position" id="1st_player_position" style="width: 320px;" >
                                                
                                        <option value="">Select Position</option>
                                        <?php
                                        foreach ($selectPosition->result_array() as $row) {    
                                            $positionName = $row['name']; ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $first_player_position) { echo "selected"; } ?>><?php echo $positionName; ?></option>
                                        <?php } ?>
                                        
                                    </select>
                                </li>
                                <li>
                                    <label>2nd Playing Position</label>
                                    <select class="form-control" name="2nd_player_position" id="2nd_player_position" style="width: 320px;" >
                                                
                                        <option value="">Select Position</option>
                                        <?php
                                        foreach ($selectPosition->result_array() as $row) {    
                                            $positionName = $row['name']; ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $second_player_position) { echo "selected"; } ?>><?php echo $positionName; ?></option>
                                        <?php } ?>
                                        
                                    </select>
                                </li>
							</ul>
							<ul class="register-fields reg_f">
                                <li>
                                    <label>3rd Playing Position</label>
                                    <select class="form-control" name="3rd_player_position" id="3rd_player_position" style="width: 320px;" >
                                                
                                        <option value="">Select Position</option>
                                        <?php
                                        foreach ($selectPosition->result_array() as $row) {    
                                            $positionName = $row['name']; ?>
                                            <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $third_player_position) { echo "selected"; } ?>><?php echo $positionName; ?></option>
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
							</ul>
                                <?php } else if($type =='playing_position') { ?>                             
                                <li>
                                    <label>Highest Education Level</label>
                                    <input type="text" name="highest_education_level" id="highest_education_level" value="<?php echo $highest_education_level; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Highest Education Level" >
                                </li>
                                <li>
                                    <label>Any Previous Injuries</label>                        
                                    <select class="form-control" name="previous_injury" id="previous_injury" style="width: 320px;" >
                                        
                                        <option value="0" <?php if($previous_injury == "0") { echo "selected"; } ?>>NO</option>
                                        <option value="1" <?php if($previous_injury == "1") { echo "selected"; } ?>>Yes</option>
                                    
                                    </select>
                                </li>
                                <li>
                                    <label>No. of Years playing Football</label>
                                    <input type="text" name="years_playing_football" id="years_playing_football" value="<?php echo $years_playing_football; ?>" class="form-control input3 mini txt_lg" placeholder="Enter No. of Years playing Football" >
                                </li>
							</ul>
							<ul class="register-fields reg_f">
                                <li>
                                    <label>State Nature of Injury</label>
                                    <input type="text" name="nature_of_injury" id="nature_of_injury" value="<?php echo $nature_of_injury; ?>" class="form-control input3 mini txt_lg" placeholder="Enter State Nature of Injury">
                                </li>
                                <li>
                                    <label>Highest Level Played At</label>
                                    <input type="text" name="highest_level_played_at" id="highest_level_played_at" value="<?php echo $highest_level_played_at; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Your Highest Level Played At" >
                                </li>
                                <li>
                                    <label>Club Played At Highest Level</label>
                                    <input type="text" name="club_played_at_highest_level" id="club_played_at_highest_level" value="<?php echo $club_played_at_highest_level; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Club Played At Highest Level" >
                                </li>
							</ul>
                                <?php } else if($type =='what_sort_of_personality') { ?>
                                    <li class="text_cum">
                                        <label>What sort of Personality or Character are you?</label>
                                        <textarea rows="7" cols="50" name="what_sort_of_character_are_you" id="what_sort_of_character_are_you" class="summernote" placeholder="Expand on your personality or character" ><?php echo $what_sort_of_character_are_you; ?></textarea>
                                    </li>
                                <?php } else if($type =='core_values') { ?>
                                    <li class="text_cum">
                                        <label>Core Values</label>
                                        <textarea rows="7" cols="50" name="what_are_your_core_values" id="what_are_your_core_values" class="summernote" placeholder="Enter Core Values"><?php echo $what_are_your_core_values; ?></textarea>
                                    </li>
                                <?php } else if($type =='playing_position') { ?>                             
                                <li>
                                    <label>Highest Education Level </label>
                                    <input type="text" name="highest_education_level" id="highest_education_level" value="<?php echo $highest_education_level; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Highest Education Level">
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
                                
                                <li class="player_fields">
                                    <label>Height (cm) <em>*</em></label>
                                    <input type="text" name="height" id="height" value="<?php echo $height; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Height" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter height." >
                                </li>
							</ul>
							<ul class="register-fields reg_f">
                                <li class="player_fields">
                                    <label>Weight (kg) <em>*</em></label>
                                    <input type="text" name="weight" id="weight" value="<?php echo $weight; ?>" class="form-control input3 mini txt_lg" placeholder="Enter Weight" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter weight." >
                                </li>
                                
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
                                <?php } ?>
                            </ul>
							<ul class="text_center edit_btn_cusm">
							<li class="btn_li cusm_rightbtn">
									<a class="btn_lg" href="<?php echo getLink('profile.php'); ?>" onclick="return confirm('Are you sure?');">Back</a>
									<input type="submit" name="update" value="Update" class="btn_lg">
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

function geThan(){
	
        var extFile  = document.getElementById("profileimage").value;
        var ext = extFile.split('.').pop();
        var filesAllowed = ["jpg", "jpeg", "png"];
        if( (filesAllowed.indexOf(ext)) == -1)
            return "Only JPG, JPEG , PNG files are allowed";
    }

/* $(function () {
    //Date picker
		$('#datepicker').datepicker({
			autoclose: true,
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+0"
		});
	}); */        
        
</script>
<?php
    
?>
<?php include('common/footer.php');?>