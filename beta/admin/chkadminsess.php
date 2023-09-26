<?php 
error_reporting(3);
if(session_id() == ""){
	session_start();
}
	
	function doLogin(){
		global $urlpath;
		header( "location:index.php?dologin" );
		exit;
	}
	if(isset($_SESSION['adminsessionid']))
		$adminsessionstr = trim($_SESSION['adminsessionid']);
	 else
		$adminsessionstr= "";

	if($adminsessionstr == "") {
		doLogin();
		die;
	}
	else{
		$parts = explode(";",$adminsessionstr);
		if(count($parts) < 3){
			doLogin();
			die;
		}
		else{
			if($parts[2]!=session_id()){
				doLogin();
				die;
			}
		}
	}
?>
