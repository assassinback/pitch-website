<?php 
if(isset($_SESSION['adminsessionid'])){
	$redirect="main.php";
	header("location:{$redirect}");
}?>