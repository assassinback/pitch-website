<?php 
if(!in_array(3,$tes_mod)) { 
echo "<div class='grid_12'><div class='message error'><p>You don't have permission to access this page</p></div></div>";
die;
}
// delete record
	$delId = (int)isset($_POST["delId"])?$_POST["delId"]:0;	
	$delRqst = !empty($delId)?TRUE:FALSE;
	
	if($delRqst) {
		foreach ($_POST["del"] as $deletAdminID) {	
			if(is_numeric($deletAdminID) && $deletAdminID > 0 ){	
				$where = "id  = {$deletAdminID}";
				if($db->Delete("tblstates",$where)){
					$_SESSION['mt'] = "success";
					$_SESSION['me'] = "State deleted successfully.";
				}else{
					$_SESSION['mt'] = "error";
					$_SESSION['me'] = "Error while delete State. Please try again.";
					
				}
			}
			
		}
		header('Location:main.php?pg=viewstate&delete');
		exit;
		
	}
	
	if(isset($_REQUEST['id']) && $_REQUEST['id']!='')
	{
		$select_query = mysql_query("select * from tblstates where id=".$_REQUEST['id']."");
		$rows=mysql_num_rows($select_query);
		if($rows > 0)
		{
			$delete=mysql_query("delete from tblstates where id=".$_REQUEST['id']."");
			$res=mysql_fetch_array($select_query);
			$text=$res['name'];
			$_SESSION['mt'] = "success";
			$_SESSION['me'] = $text." state has been deleted successfully.";
			header('Location:main.php?pg=viewstate');
		    exit;
		}
		else
		{
			$_SESSION['mt'] = "error";
			$_SESSION['me'] = "Error while delete State. Please try again.";
			header('Location:main.php?pg=viewuser');
		    exit;		
		}
	}
$select_query = mysql_query("select * from tblstates where country_id='230' ORDER By `name` ASC");	
?>

<link rel="stylesheet" href="css/dataTables.bootstrap.css">

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
		<section class="content-header">
		  <h1>
			State 
			
		  </h1>
		  <ol class="breadcrumb">
			<li><a href="<?php echo ADMIN_URL; ?>main.php"><i class="fa fa-home"></i> Home</a></li>
			<li><a href=""><i class="fa fa-location-arrow"></i>State</a></li>
			
		  </ol>
		</section>

    <!-- Main content -->
		 <section class="content">
			  <div class="row">
				<div class="col-xs-12">
				  <div class="box">
					<div class="box-header">
					  <!--<h3 class="box-title">Customer </h3>-->
					  <?php echo getMsg();?>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
					 <form method="post" name="form">	
					  <table id="example1" class="table table-bordered table-striped">
					  <input type="hidden" name="delId" id = "delId" value="" />	
						<thead>
						<tr>
						  <th><input type="checkbox" name="delall" onClick="selectall();"></th>
						  
						  <th>State</th>
						  <th>Action</th>
						</tr>
						</thead>
						<tbody>
						<?php 
						$inc = 1;
						if(mysql_num_rows($select_query)){
							while($row = mysql_fetch_assoc($select_query)): 
						
							if(trim($row['status']) == '1')
								$isactive = "Active";
							else
								$isactive = "InActive";
							
							echo "<tr>
										<td><input type='checkbox' name='del[]' value='".$row["id"]."'></td>
										<td>".$row["name"]."</td>"; ?>
																				
										 <td><a class='btn btn-primary' href='main.php?pg=modstate&id=<?=$row["id"]?>'>Modify</a>
										<a class='btn btn-warning' onclick="return confirm('Are you sure want to delete this state?')" href='main.php?pg=viewstate&id=<?=$row["id"]?>' title="are you sure want to delete this state?">Delete</a></td>
									 </tr>
						<?php			 

						$inc++;
						endwhile; 
						}?>
						
						
						</tbody>
						
					  </table>
						<div >
							<button type="button" onclick="confirmDelete()"  name="submit_me"  class="btn btn-primary">Delete</button>
							<button type="button" onclick="location.href='main.php?pg=addstate'"  name="submit_me"  class="btn btn-primary">Add</button>
						</div>
					  </form>
					</div>
					<!-- /.box-body -->
				  </div>
				  <!-- /.box -->
				</div>
				<!-- /.col -->
			  </div>
      <!-- /.row -->
		</section>
    <!-- /.content -->
	</div>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>	
<script>
$(function () {
$('#example1').DataTable({
  "paging": true,
  "lengthChange": true,
  "searching": true,
  "ordering": true,
  "info": true,
  "autoWidth": false
});
});
</script>
<script type="text/javascript">
function confirmDelete(){
	var f=0;
	var len=document.form.length;
	for(i=1;i<len;i++){
		if(document.form.elements[i].checked==true){
			f=1;
			break;
		}
		else{	
			f=0;
		}
	}
	if(f==0){
		alert("Atleast select one record to be deleted..!");
		return false;
	}
	else{
		var temp=confirm("Do you really want to delete...!");
			if(temp==false)	{
				return false;
			}
			else{
				document.getElementById("delId").value="del";
				document.form.submit();
			}
	}
}

function selectall()
{
	
	if(document.form.delall.checked==true)
	{
		var chks = document.getElementsByName('del[]');
		
		for(i=0;i<chks.length;i++)
		{
			chks[i].checked=true;
		}
	}
	else if(document.form.delall.checked==false)
	{
		var chks = document.getElementsByName('del[]');
		
		for(i=0;i<chks.length;i++)
		{
			chks[i].checked=false;
		}
	}
}

</script>
<div class="clear"></div>

<script>
function changeContent(id,val){
	var data = {
	"id": id , "val" : val
	};
	data = $(this).serialize() + "&" + $.param(data);
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "phpajax/CustomerActiveInactive.php", 
		data: data,
		beforeSend: function() {
		  $("#loading-image_"+id).show();
	   },
		success: function(data) {
			if(data["msg"] == "success"){
				var rowID = data["id"];
				var status = data["status"];
				var statusText = data["statusText"];
				var link  = "<a href='javascript:void(0);' onclick='changeContent(" + id + ", " + status + ")'> " +  statusText + " </a>";
				$("#ajaxStatus_"+rowID).html(link);
				$("#loading-image_"+id).hide();
			}
				
		}
	});
}	
</script>