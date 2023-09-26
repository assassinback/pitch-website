<?php
$module = 'contributor';
checkPermission($module);

$pageTitle = 'Contributor';

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$data = array();

$act = 'add';
if($id) {
	$act = 'update';
	$result = $db->query('SELECT * FROM ' . $dbPrefix . 'contributor WHERE id ='.$id);
	$data = $result->row_array();
}


$fields = array('name', 'description', 'image', 'url_slug', 'status');
foreach($fields as $field) {
	if(isset($_POST[$field])) {
		${$field} = $_POST[$field];
	} else if(isset($data[$field])) {
		${$field} = $data[$field];
	} else {
		${$field} = null;
	}
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = $_REQUEST;
	$act = $_REQUEST['act'];
	
    $error  =   "";
	$name = $_REQUEST['name'];
	$description = $_REQUEST['description'];
	$url_slug = $_REQUEST['url_slug'];
	$status = $_REQUEST['status'];
    
    $a=$_POST['c_chk'];
    
    if($act == "add")
	{	
		if($name != "")
		{
            $photo = "";
            if(!$error && isset($_FILES['contributorimage']['name']) && !empty($_FILES['contributorimage']['name']))
            {
                $source = $_FILES['contributorimage']['tmp_name'];
                $file_name = 'Contributor-' . str_pad(getAutoID($dbPrefix . 'contributor'), 4, '0', STR_PAD_LEFT);
                $image_upload = uploadImage(array('source' => $source, 'destination' => CONTRIBUTOR_PATH, 'file_name' => $file_name));

                if(isset($image_upload['success'])) {
                    $photo = $image_upload['file'];
                } else if(isset($image_upload['error'])) {
                    $error = 'Img - ' . $image_upload['error'];
                }
            }
    
            if(!$error) {
                $data = array(
                        'name' => $name,
                        'description' => $description,
                        'image' => $photo,
                        'url_slug' => $url_slug,
                        'status' => $status,
                        'date_added' => date('Y-m-d H:i:s'),
                        'date_modified' => date('Y-m-d H:i:s')
                    );
                insertData("contributor",$data);
                
                redirect(getAdminLink('contributor'));
                exit;
            }
		}
	}

	// Modify Section :: Modify data into database
			
	if($act == "update")
	{
		if($name != "")
		{	
            $photo = "";
            if(!$error && isset($_FILES['contributorimage']['name']) && !empty($_FILES['contributorimage']['name']))
            {
                $source = $_FILES['contributorimage']['tmp_name'];
                $file_name = 'Contributor-' . str_pad($id, 4, '0', STR_PAD_LEFT);
                $image_upload = uploadImage(array('source' => $source, 'destination' => CONTRIBUTOR_PATH, 'file_name' => $file_name));

                if(isset($image_upload['success'])) {
                    $photo = $image_upload['file'];
                } else if(isset($image_upload['error'])) {
                    $error = 'Img - ' . $image_upload['error'];
                }
            }
            else
            {
                $photo = $image;
            }
            
			$data = array(
					'name' => $name,
                    'description' => $description,
                    'image' => $photo,
					'url_slug' => $url_slug,
                    'status' => $status,
					'date_modified' => date('Y-m-d H:i:s')
				);
			$where ="id ={$id}";
			updateData("contributor",$data,$where);
            
            redirect(getAdminLink('contributor'));
			exit;
		}
	}
	
}

?>


<div class="content-wrapper">
    <section class="content-header">
		<h1>Contributor</h1>
        <ol class="breadcrumb">
			<li><a href="<?php echo getAdminLink();?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?php echo getAdminLink('contributor');?>"><i class="fa fa-folder"></i> Contributor</a></li>
        </ol>
	</section>
    <section class="content">
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-name">Contributor</h3>
					</div>
                    <div class="error"></div>
                    
					<form action='' method='post' class="validateForm" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?php echo $id ?>"/>
						<input type="hidden" name="act" value="<?php echo $act ?>"/>
                        <div class="box-body">
                        
                            <?php 
                                if($image!=''){
								$src = CONTRIBUTOR_URL . $image;  
						    ?>
								<div class="form-group">
									<!-- <label for="exampleInputEmail1">Image</label> -->
									<a href="<?php echo $src; ?>" class="enLarge" ><img src="<?php echo $src; ?>"  width='80' width='80'/></a><br/>
								</div>
							<?php } ?>
                            
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="contributorimage" id="contributorimage" class="form-control input3 mini" data-validation-engine="validate[funcCall[geThan[]]]" data-errormessage-value-missing="Only JPG, JPEG and PNG are allowed">
							</div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type='text' name='name' class='form-control' value="<?php echo $name; ?>" placeholder="Name" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter contributor name">
                            </div>
                            <div class="form-group">
                                <label>URL Slug</label>
                                <input type='text' name='url_slug' class='form-control' value="<?php echo $url_slug; ?>" placeholder="URL Slug">
                            </div>
                            <div class="form-group">
                                <label>Description:</label><br>
                                <textarea name='description' class="form-control summernote" ><?php echo $description; ?></textarea>
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

<link rel="stylesheet" type="text/css" href="js/summernote/dist/summernote.css">
<script type="text/javascript" src="js/summernote/dist/summernote.js"></script>

<script>
$(document).ready(function () {
    $('.summernote').summernote();
});

function geThan(){
	
        var extFile  = document.getElementById("contributorimage").value;
        var ext = extFile.split('.').pop();
        var filesAllowed = ["jpg", "jpeg", "png"];
        if( (filesAllowed.indexOf(ext)) == -1)
            return "Only JPG, JPEG , PNG files are allowed";
    }

$(document).ready(function(){
    $(".validateForm").validationEngine({promptPosition : "inline", scroll: true});   
});

</script>