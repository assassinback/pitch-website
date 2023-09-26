<?php
$module = 'admin';
checkPermission($module);

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$data = array();
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = $_REQUEST;
	$act = $_REQUEST['act'];
	
	$name = $_REQUEST['name'];
	$email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
	$status = $_REQUEST['status'];
    $permission = isset($_REQUEST['permission']) ? $_REQUEST['permission'] : array();
    
    $record = $db->query('SELECT * FROM ' . $dbPrefix . 'admin WHERE email = ?', array($email));
    $emailresult = $record->result_array();
    if (count($emailresult) > 0) {
       $error = "Email address is already registered. Please check your email.";
    }
	
	if($act == "add")
	{	
		if($email != "")
		{
            $password = $bcrypt->hash_password($password);
			$data = array(
					'name' => $name,
					'email' => $email,
					'password' => $password,
					'status' => $status,
					'date_added' => date('Y-m-d H:i:s'),
					'date_modified' => date('Y-m-d H:i:s')
				);
			$id = insertData("admin",$data);
            
            if (count($permission) > 0) {
                foreach ($permission as $module) {
                    $permissionData = array(
                        'admin_id' => $id,
                        'module' => $module
                    );
                    insertData("admin_permission",$permissionData);
                }
            }
			
			redirect(getAdminLink('admin'));
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
                    'email' => $email,
					'status' => $status,
					'date_modified' => date('Y-m-d H:i:s')
				);
            if ($password != "") {
                $data['password'] = $bcrypt->hash_password($password);
            }
			$where ="id ={$id}";
			updateData("admin",$data,$where);
            
            $db->query('DELETE FROM ' . $dbPrefix . 'admin_permission WHERE admin_id = ?', array($id));
            if (count($permission) > 0) {
                foreach ($permission as $module) {
                    $permissionData = array(
                        'admin_id' => $id,
                        'module' => $module
                    );
                    insertData("admin_permission",$permissionData);
                }
            }
			
			redirect(getAdminLink('admin'));
			exit;
		}
	}
	
}




$act = 'add';
if($id) {
	$act = 'update';
	$result = $db->query('SELECT * FROM ' . $dbPrefix . 'admin WHERE id ='.$id);
	$data = $result->row_array();
    
    $query = 'SELECT * FROM ' . $dbPrefix . 'admin_permission WHERE admin_id ='.$id;
    $results = $db->query($query);
    
    $permission = array();
    foreach ($results->result_array() as $result) {
        $permission[] = $result['module'];
    }
    
} else {
    $permission = array();
}


$fields = array('name', 'email', 'password', 'super_admin', 'status');
foreach($fields as $field) {
	if(isset($_POST[$field])) {
		${$field} = $else[$field];
	} else if(isset($data[$field])) {
		${$field} = $data[$field];
	} else {
		${$field} = null;
	}
}

//$modules = array ('admin', 'blog', 'blogcategory', 'club', 'cmspages', 'contributor', 'country', 'county', 'position', 'sociallink', 'test', 'user', 'trialsession', 'potentialtrial');
$modules = array ('admin', 'blog', 'club', 'cmspages', 'contributor', 'country', 'county', 'position', 'sociallink', 'test', 'user', 'trialsession');

?>


<div class="content-wrapper">
    <section class="content-header">
		<h1>Admin</h1>
        <ol class="breadcrumb">
			<li><a href="<?php echo getAdminLink();?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?php echo getAdminLink('admin');?>"><i class="fa fa-folder"></i> Admin</a></li>
        </ol>
	</section>
    <section class="content">
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-name">Admin</h3>
					</div>
                    <div class="error"></div>
                    <?php if($error){ ?>
                    <div class="alert alert-danger">
                        <?php echo "<strong>Error!</strong> " . $error; ?>
                    </div>
                    <?php } ?>
                    
					<form action='' method='post' class="validateForm" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?php echo $id ?>"/>
						<input type="hidden" name="act" value="<?php echo $act ?>"/>
                        <div class="box-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input type='text' name='name' class='form-control' value="<?php echo $name; ?>" placeholder="Name" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter admin name">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" id="email" value="<?php echo $email; ?>" class="form-control" placeholder="Enter email" data-validation-engine="validate[required,custom[email]]" data-errormessage-value-missing="The e-mail address you entered appears to be incorrect." maxlength="70" data-errormessage-custom-error="Example: test@gmail.com" >
                            </div>
                            
                            <?php if($act == 'update') { ?>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" maxlength="12">
                                <span class="help-block">Leave this field blank if you don't want to update</span>
                            </div>
                            <?php } else { ?>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" maxlength="12" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter password">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="rpassword" id="rpassword" class="form-control" placeholder="Confirm Password"  maxlength="12" data-validation-engine="validate[required,equals[password]]" data-errormessage-value-missing="Please enter confirm password" data-errormessage-custom-error="The two passwords you entered did not match each other. Please try again.">
                            </div> 
                            <?php } ?>
                            
                            <div class="form-group field-status1">
                                <label>Status</label>
                                <select style="width: 320px;" class="form-control" name="status">
                                    <option value="1">Active</option>
                                    <option value="0" <?php if($act == 'update' && $status == 0) { echo "selected";} ?>>Inactive</option>
                                </select>
                            </div>
                            
                            <?php if ($super_admin != 1) { ?>
                            <div class="form-group">
                                <label>Permission</label><br>
                                <?php foreach ($modules as $module) { ?>
                                <div class="col-xs-12 col-sm-6" >
                                    <label><input type="checkbox" name="permission[]" value="<?php echo $module; ?>" <?php if (in_array($module, $permission)) { ?> checked <?php } ?>> <?php echo ucfirst($module); ?></label>
                                </div>
                                <?php } ?>
                            </div>
                            <?php } ?>
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