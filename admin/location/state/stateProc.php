<?php 
if(!in_array(1,$tes_mod)) { 
echo "<div class='grid_12'><div class='message error'><p>You don't have permission to access this page</p></div></div>";
die;
}
// Include all file Here
error_reporting(0);
require_once "../config.php";
require_once "chkadminsess.php";
if(isset($_SERVER['HTTP_REFERER']) == ADMIN_URL."main1.php?pg=addadmin")
{
// Set Admin ID And Action
$a_id   = 	$_REQUEST['id'];
$act    = 	$_REQUEST["act"];

// Set All Admin data here
$country =	addslashes($_REQUEST["country"]); 
$state =	addslashes($_REQUEST["state"]); 
  		

// Add Section :: Add Data into database
if($act == "add"){	

	 if($state != "")
	 {			
		$data = array('name'=>$state,'country_id'=>$country);
		$db->Insert($data,"tblstates");
		//die;
		$_SESSION['mt'] = "success";
		$_SESSION['me'] = "'{$state}' added successfully.";
		header('Location:main.php?pg=viewstate');
		exit;
	}                 
	
	else{	
			$_SESSION['mt'] = "error";
			$_SESSION['me'] = "Enter Valid State Name.";
			header('Location:main.php?pg=viewstate');
			exit;
	}	
}
// Modify Section :: Modify data into database
if($act == "mod")
{		
	if($state != "" && $a_id > 0)
	{			
		$data = array('name'=>$state,'country_id'=>$country);
		$where ="id ={$a_id}";
					
		$db->Update($data,"tblstates",$where);
		 //$a_id = mysql_insert_id();
		
		$_SESSION['mt'] = "success";
		$_SESSION['me'] = "State Update Successfully.";
		header('Location:main.php?pg=viewstate');
		exit;
	}
	else
		{	
			$_SESSION['mt'] = "error";
			$_SESSION['me'] = "Admin Name/Password Invalid.";
			header('Location:main.php?pg=viewadmin');
			exit;
		}	
}	
	
// Delete Section :: Delete data from database
if($act == "del"){ 
$a_id = $_REQUEST["id"];
$adminid = ADMINID;
if(is_numeric($a_id) && $a_id > 0 AND $a_id != $adminid ){	
	$where = "adminid  = {$a_id} AND adminid  <> {$adminid} ";
	if($db->Delete("tbladmin",$where)){
		$_SESSION['mt'] = "success";
		$_SESSION['me'] = "Admin user deleted successfully.";
		header('Location:main.php?pg=viewadmin');
		exit;
	}else{
		$_SESSION['mt'] = "error";
		$_SESSION['me'] = "Error while delete admin user. Please try again.";
		header('Location:main.php?pg=viewadmin');
		exit;
	}
}
else{
	$_SESSION['mt'] = "error";
	$_SESSION['me'] = "Invalid ID/Name.";
	header('Location:main.php?pg=viewadmin');
	exit;	
}	
	
}
}
?>
<form name="frmAction" action="main.php?pg=viewadmin" method="post">
<input type="hidden" name="msg" value="<?php echo $msg; ?>">
<script language="javascript" type="text/javascript">
	document.frmAction.submit();
</script>
</form>