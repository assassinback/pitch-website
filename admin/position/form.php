<?php
$module = 'position';
checkPermission($module);

$pageTitle = 'Position';

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
			$data = array(
					'name' => $name,
					'status' => $status,
					'date_added' => date('Y-m-d H:i:s'),
					'date_modified' => date('Y-m-d H:i:s')
				);
			insertData("position",$data);
			
			$_SESSION['msgType'] = 'success';
            $_SESSION['msgString'] = 'Record added successfully!';
			redirect(getAdminLink($module));
			exit;
		}
	}

	// Modify Section :: Modify data into database
			
	if($act == "update")
	{
		if($name != "")
		{		
			$data = array(
					'name' => $name,
					'status' => $status,
					'date_modified' => date('Y-m-d H:i:s')
				);
			$where ="id ={$id}";
			updateData("position",$data,$where);
            
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
	$result = $db->query('SELECT * FROM ' . $dbPrefix . 'position WHERE id ='.$id);
	$data = $result->row_array();
}

$fields = array('name', 'status');
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
                                <label>Name</label>
                                <input type='text' name='name' class='form-control' value="<?php echo $name; ?>" placeholder="Position name" maxlength="50" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter position name">
                            </div>
                            <div class="form-group field-status">
                                <label>Status</label>
                                <select style="width: 320px;" class="form-control" name="status">
                                    <option value="1">Active</option>
                                    <option value="0" <?php if($act == 'update' && $status == 0) { echo "selected";} ?>>Inactive</option>
                                </select>
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
    $(".validateForm").validationEngine({promptPosition : "inline", scroll: true});   
});
</script>
