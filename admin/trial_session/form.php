<?php
$module = 'trialsession';
checkPermission($module);

$pageTitle = 'Trial Session';

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$data = array();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = $_REQUEST;
	$act = $_REQUEST['act'];
	
	$title = $_REQUEST['title'];
	$date = date('Y-m-d',strtotime(str_replace('/', '-', $_REQUEST['date'])));
	$min_age = $_REQUEST['min_age'];
	$max_age = $_REQUEST['max_age'];
	$space = $_REQUEST['space'];
	
	if($act == "add")
	{	
		if($date != "")
		{
			$data = array(
					'title' => $title,
					'date' => $date,
					'min_age' => $min_age,
					'max_age' => $max_age,
					'space' => $space,
					'date_added' => date('Y-m-d H:i:s'),
					'date_modified' => date('Y-m-d H:i:s')
				);
			insertData("trial_session",$data);
			
			$_SESSION['msgType'] = 'success';
            $_SESSION['msgString'] = 'Record added successfully!';
			redirect(getAdminLink($module));
			exit;
		}
	}

	// Modify Section :: Modify data into database
			
	if($act == "update")
	{
		if($date != "")
		{		
			$data = array(
					'title' => $title,
					'date' => $date,
					'min_age' => $min_age,
					'max_age' => $max_age,
					'space' => $space,
					'date_modified' => date('Y-m-d H:i:s')
				);
                
			$where ="id ={$id}";
			updateData("trial_session",$data,$where);
			
			$_SESSION['msgType'] = 'success';
            $_SESSION['msgString'] = 'Record updated successfully!';
			redirect(getAdminLink($module));
			exit;
		}
	}	
}

$act = 'add';
if($id) {
	$act = 'update';
	$result = $db->query('SELECT * FROM ' . $dbPrefix . 'trial_session WHERE id ='.$id);
	$data = $result->row_array();
}

$fields = array('title', 'date', 'min_age', 'max_age', 'space');
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
						<h3 class="box-name"><?php echo ucfirst($act) . ' ' . $pageTitle; ?></h3>
					</div>
                    <div class="error"></div>
                    
					<form action='' method='post' class="validateForm" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?php echo $id ?>"/>
						<input type="hidden" name="act" value="<?php echo $act ?>"/>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input type='text' name='title' class='form-control' value="<?php echo $title; ?>" placeholder="Title" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter title">
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <input type='text' name='date' class='form-control datepicker' value="<?php echo isset($date)? formatAdminDate($date):''; ?>" placeholder="Date" data-date-start-date="+1d" data-date-end-date="+365d" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select date">
                            </div>
                            <div class="form-group">
                                <label>Minimum Age</label>
                                <input type='text' name='min_age' class='form-control' value="<?php echo $min_age; ?>" placeholder="Minimum Age" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter minimum age">
                            </div>
                            <div class="form-group">
                                <label>Maximum Age</label>
                                <input type='text' name='max_age' class='form-control' value="<?php echo $max_age; ?>" placeholder="Maximum Age" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter maximum age">
                            </div>
                            <div class="form-group">
                                <label>Space</label>
                                <input type='text' name='space' class='form-control' value="<?php echo $space; ?>" placeholder="Spaces" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter space">
                            </div>
                        </div>
                        
                        <div class="box-footer">
                            <button type="submit"  name="submit_me"  class="btn btn-primary">Submit</button>
                        </div>
					</form>
				</div>
			</div>
		</div>
	</section>
</div>

<script>

$(document).ready(function(){
    var todayDate = new Date().getDate();
    $('.datepicker').datepicker({
        dateFormat:'dd/mm/yy'
    });
    
    $(".validateForm").validationEngine({promptPosition : "inline", scroll: true});   
});
</script>