<?php

//$module = 'admin';
//checkPermission($module);

$adminsessionstr = trim($_SESSION['adminsessionid']);
$parts = explode(";",$adminsessionstr);
$id = $parts[0];
    
$data = array();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
    $data = $_REQUEST;
	$act = $_REQUEST['act'];
	$name = $_REQUEST['name'];
	$password = $_REQUEST['password'];
    
    $data = array(
                    'name' => $name,
                    'date_modified' => date('Y-m-d H:i:s')
                );
    
    if($password != '')
    {
        $data['password'] = $bcrypt->hash_password($password);
	}
    
    $where ="id ={$id}";
    updateData("admin",$data,$where);
    
    $_SESSION['msgType'] = 'success';
    $_SESSION['msgString'] = 'Profile updated successfully.';
    
    redirect(getAdminLink('profile'));
    exit;
}

if($id) {
    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'admin WHERE id ='.$id);
	$data = $result->row_array();
}    

$fields = array('name', 'password');
foreach($fields as $field) {
	if(isset($_POST[$field])) {
		${$field} = $else[$field];
	} else if(isset($data[$field])) {
		${$field} = $data[$field];
	} else {
		${$field} = null;
	}
}

?>


<div class="content-wrapper">
    <section class="content-header">
		<h1>Profile</h1>
        <ol class="breadcrumb">
			<li><a href="<?php echo getAdminLink();?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?php echo getAdminLink('admin');?>"><i class="fa fa-folder"></i> Profile</a></li>
        </ol>
	</section>
    <section class="content">
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-name">Profile</h3>
					</div>
                    
                    <?php if (isset($msgString)) { ?>
                        <div class="alert alert-<?php echo $msgType; ?>">
                            <?php echo $msgString; ?>
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
                                <label>Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" maxlength="12">
                                <span class="help-block">Leave this field blank if you don't want to update</span>
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