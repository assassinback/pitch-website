<?php 
$module = 'country';
checkPermission($module);

$pageTitle = 'Country';

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
			insertData("country",$data);
			
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
			updateData("country",$data,$where);
			
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
	$result = $db->query('SELECT * FROM ' . $dbPrefix . 'country WHERE id ='.$id);
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


hi