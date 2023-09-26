<?php 
/* if(!in_array(10,$tes_mod)) { 
    echo "<div class='grid_12'><div class='message error'><p>You don't have permission to access this page</p></div></div>";
    die;
} */

$module = 'user';

$delId = (int)isset($_GET["delId"]) ? $_GET["delId"] : null;
if($delId) {
    $result = $db->query('DELETE FROM ' . $dbPrefix . 'user WHERE id = ?', array($delId));
    
    if ($result) {
        $_SESSION['msgType'] = 'danger';
        $_SESSION['msgString'] = 'Record deleted successfully!';
    }
    
    redirect(getAdminLink($module));
    exit;
}

if(isset($_REQUEST['del']))
{
    $delete = $_REQUEST['delete'];
    if (count($delete) > 0) {
        $delete = implode(',', $delete);
        //$sql = 'DELETE FROM '.$dbPrefix.'user WHERE id IN ('.$delete.')';
        //echo $sql; exit;
        //$result = $db->query('DELETE FROM ' . $dbPrefix . 'user WHERE id IN ?', array($delete));
        $result = $db->query('DELETE FROM '.$dbPrefix.'user WHERE id IN ('.$delete.')');
        if ($result) {
            $_SESSION['msgType'] = 'danger';
            $_SESSION['msgString'] = 'Selected record(s) deleted successfully!';
        }
    }
    redirect(getAdminLink($module));
    exit;
}

?>


<script type="text/javascript">
    $(document).ready(function() {
        $('#table-list').DataTable( {
            "bProcessing": true,
            "bServerSide": true,
            "filter": true,
            "stateSave": true,
            "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
                    var tottext = 'entries';
                    if(iTotal > 1){ tottext = 'entries'; }else{ tottext = 'entry'; }
                    if(iTotal > 0){iStart = iStart;}else{iStart = 0;}
                    return 'Showing '+iStart+' to '+iEnd+' of '+iTotal+' '+tottext;
                },
            //"pageLength": pageLength,
            "sAjaxSource": "<?php echo getAdminLink('user/script.php', '', true);?>",
            
            aoColumnDefs: [ 
                    {  bSortable: false, bSearchable: false, aTargets: [ -1 , 0 ] },							
                ],
             "aoColumns": [ 
             {"sClass": "center"},
             {"sClass": "center"},
             {"sClass": "center"},
             {"sClass": "center"},
             {"sClass": "center"},
             ],
        } );
    } );
</script>
<script type="text/javascript">

/*
function confirmDelete(){
	var f=0;
	var len=document.userForm.length;
	for(i=1;i<len;i++){
		if(document.userForm.elements[i].checked==true){
			f=1;
			break;
		}
		else{	
			f=0;
		}
	}
	if(f==0){
		alert("Please select at least one record to delete");
		return false;
	}
	else{
		var temp=confirm("Do you really want to delete the selected records?");
			if(temp==false)	{
				return false;
			}
			else{
				document.getElementById("delId").value="del";
				document.userForm.submit();
			}
	}
}
function selectall()
{		
	if(document.userForm.delall.checked==true)
	{			
		var chks = document.getElementsByName('del[]');
		for(i=0;i<chks.length;i++)
		{
			chks[i].checked=true;			
		}
	}
	else if(document.userForm.delall.checked==false)
	{
		var chks = document.getElementsByName('del[]');
		for(i=0;i<chks.length;i++)
		{
			chks[i].checked=false;
		}
	}
}
*/

function viewAction(str,id)
{

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtAction-"+id).innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","<?php echo ADMIN_URL ;?>cmsaction.php?q="+str+"&page_id="+id,true);
xmlhttp.send();

}

</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>User</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo ADMIN_URL; ?>main.php"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="#"><i class="fa fa-folder"></i>User</a></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
				<div class="box-header">
					<h3 class="box-title">User </h3>
				</div>
                
                <?php if (isset($msgString)) { ?>
                    <div class="alert alert-<?php echo $msgType; ?>">
                        <?php echo $msgString; ?>
                    </div>
                <?php } ?>
                  
				<div class="box-body"> 
                <form method="post" name="userForm">
                    <table id="table-list" class="table table-bordered table-striped">
                        <input type="hidden" name="del" id = "del" value="" />
                        <thead>
                            <tr class="trheader">
                                <th width="5%"><input type="checkbox" class="select-all" ></th>
                                <th width="25%">Name</th>
                                <th width="25%">User Type</th>
                                <th width="15%">Status</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                        </tbody> 
                    </table>
                    
                    <div> 
                    
                        <button type="button" name="delete" class="btn btn-danger delete-selected-record">Delete</button>
                        
                        <?php /*
                        <button type="button" onclick="confirmDelete()"  name="submit_me"  class="btn btn-primary">Delete</button>  
                        */ ?>    
                        <?php /*
                        <a href="<?php echo getAdminLink('addcountry');?>" ><button type="button"  name="submit_me"  class="btn btn-primary">Add</button></a> */ ?>
                    </div> 
                </form>
				</div>
				</div>
			</div>
		</div>
	</section>
</div>

<div class="clear"></div>
<script src="js/list.js"></script>