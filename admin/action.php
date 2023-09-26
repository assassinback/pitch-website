<?php
error_reporting(0);
require_once("../config.php");
 $q=$_REQUEST['q'];
 $flag=0;
  		if(isset($_REQUEST['user_id']))
 			{
 				$id=$_REQUEST['user_id'];
				$table ='tbluser';
				$field='is_active';
 				$field_id='user_id';
				$flag=1;
				
  			}
			else
			{
				$id='';
			 	$table ='';
				$field='';
 				$field_id='';
			}
			
			
 
		if($q=='inactive')
		{
			$link=mysql_query("update $table set $field='active' where $field_id=$id"); 

			
			?>
			<a onclick="viewAction('active',<?php echo $id; ?>);">Active</a><?php
		}
	   if($q=='active')
		{
			$link=mysql_query("update $table set $field='inactive' where $field_id=$id"); 
			0
			
			?>
			<a onclick="viewAction('inactive',<?php echo $id; ?>);">Inactive</a> <?php 
		}

?>