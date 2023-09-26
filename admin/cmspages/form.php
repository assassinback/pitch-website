<?php
$module = 'cmspages';
checkPermission($module);

$pageTitle = 'CMS Pages';

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$data = array();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$data = $_REQUEST;
	$act = $_REQUEST['act'];
	
	$title = $_REQUEST['title'];
	$description = $_REQUEST['description'];
	$video = $_REQUEST['video'];
	$status = $_REQUEST['status'];
	$image = $_REQUEST['image'];
	
	// Modify Section :: Modify data into database
			
	if($act == "update")
	{
		if($title != "")
		{	

            $photo = "";
            if(isset($_FILES['bannerimage']['name']) && !empty($_FILES['bannerimage']['name']))
            {
                $source = $_FILES['bannerimage']['tmp_name'];
                $file_name = 'Banner-' . str_pad($id, 4, '0', STR_PAD_LEFT);
                $image_upload = uploadImage(array('source' => $source, 'destination' => CMS_PATH, 'file_name' => $file_name));

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
            
            if(!isset($error)) {
                $data = array(
                        'title' => $title,
                        'description' => $description,
                        'image' => $photo,
                        'video' => $video,
                        'status' => $status,
                        'date_modified' => date('Y-m-d H:i:s')
                    );
                $where ="id ={$id}";
                updateData("cms_pages",$data,$where);
                
                $_SESSION['msgType'] = 'success';
                $_SESSION['msgString'] = 'Record updated successfully!';
                redirect(getAdminLink($module));
                exit;
            }
		}
	}	
}

$act = 'add';
if($id) {
	$act = 'update';
	$result = $db->query('SELECT * FROM ' . $dbPrefix . 'cms_pages WHERE id ='.$id);
	$data = $result->row_array();
}

$fields = array('title', 'description', 'image', 'video', 'url_slug', 'status');
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

<div class="content-wrapper">
    <section class="content-header">
		<h1><?php echo $pageTitle; ?></h1>
        <ol class="breadcrumb">
			<li><a href="<?php echo getAdminLink();?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?php echo getAdminLink($module);?>"><i class="fa fa-folder"></i> <?php echo $pageTitle; ?></a></li>
        </ol>
	</section>
    <section class="content">
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-name"><?php echo ucfirst($act) . ' ' . $pageTitle; ?></h3>
					</div>
                    <?php if(isset($error)) { ?>
                        <div class="error"><?php echo $error; ?></div>
                    <?php } ?>
                    
					<form action='' method='post' class="validateForm" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?php echo $id ?>"/>
						<input type="hidden" name="act" value="<?php echo $act ?>"/>
                        <div class="box-body">
                        
                            <?php 
                                if($image!=''){
								$src = CMS_URL . $image;  
						    ?>
								<div class="form-group">
									<!-- <label for="exampleInputEmail1">Image</label> -->
									<a href="<?php echo $src; ?>" class="enLarge" ><img src="<?php echo $src; ?>"  width='80' width='80'/></a><br/>
								</div>
							<?php } ?>
                        
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="bannerimage" id="bannerimage" class="form-control input3 mini" data-validation-engine="validate[funcCall[geThan[]]]" data-errormessage-value-missing="Only JPG, JPEG and PNG are allowed">
                                <input type="hidden" name="image" value="<?php echo $image; ?>" >
							</div>
                        
                            <div class="form-group">
                                <label>Title</label>
                                <input type='text' name='title' class='form-control' value="<?php echo $title; ?>" placeholder="Title">
                            </div>
                            <div class="form-group">
                                <label>Description:</label><br>
                                <textarea name='description' class="form-control summernote" ><?php echo $description; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Video</label>
                                <input type='text' name='video' class='form-control' value="<?php echo $video; ?>" placeholder="Video">
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
	
        var extFile  = document.getElementById("bannerimage").value;
        var ext = extFile.split('.').pop();
        var filesAllowed = ["jpg", "jpeg", "png"];
        if( (filesAllowed.indexOf(ext)) == -1)
            return "Only JPG, JPEG , PNG files are allowed";
    }

    $(document).ready(function(){
        $(".validateForm").validationEngine({promptPosition : "inline", scroll: true});   
    });  
      
</script>