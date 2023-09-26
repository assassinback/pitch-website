<?php
$module = 'country';
checkPermission($module);

$pageTitle = 'Country';

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$data = array();

$act = 'add';
if($id) {
	$act = 'update';
	$result = $db->query('SELECT * FROM ' . $dbPrefix . 'blog_post WHERE id ='.$id);
	$data = $result->row_array();
}


$fields = array('title', 'description', 'image', 'url_slug', 'author', 'status');
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
	$author = $_REQUEST['author'];
	$status = $_REQUEST['status'];
    
    $a=$_POST['c_chk'];
    
    if($act == "add")
	{	
		if($name != "")
		{
            $photo = "";
            if(!$error && isset($_FILES['blogimage']['name']) && !empty($_FILES['blogimage']['name']))
            {
                $source = $_FILES['blogimage']['tmp_name'];
                $file_name = 'Blog-' . str_pad(getAutoID($dbPrefix . 'blog_post'), 4, '0', STR_PAD_LEFT);
                $image_upload = uploadImage(array('source' => $source, 'destination' => BLOG_PATH, 'file_name' => $file_name));

                if(isset($image_upload['success'])) {
                    $photo = $image_upload['file'];
                } else if(isset($image_upload['error'])) {
                    $error = 'Img - ' . $image_upload['error'];
                }
            }
    
            if(!$error) {
                $data = array(
                        'title' => $name,
                        'description' => $description,
                        'image' => $photo,
                        'url_slug' => $url_slug,
                        'author' => $author,
                        'status' => $status,
                        'date_added' => date('Y-m-d H:i:s'),
                        'date_modified' => date('Y-m-d H:i:s')
                    );
                insertData("blog_post",$data);
                
                $a_id = $db->insert_id();
                foreach ($a as $value) 
				{
					$data1 = array('category_id'=>$value,'post_id'=>$a_id);
					insertData("blog_post_category",$data1);
				}
                redirect(getAdminLink('blog'));
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
            if(!$error && isset($_FILES['blogimage']['name']) && !empty($_FILES['blogimage']['name']))
            {
                $source = $_FILES['blogimage']['tmp_name'];
                $file_name = 'Blog-' . str_pad($id, 4, '0', STR_PAD_LEFT);
                $image_upload = uploadImage(array('source' => $source, 'destination' => BLOG_PATH, 'file_name' => $file_name));

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
					'title' => $name,
                    'description' => $description,
                    'image' => $photo,
					'url_slug' => $url_slug,
                    'author' => $author,
					'status' => $status,
					'date_modified' => date('Y-m-d H:i:s')
				);
			$where ="id ={$id}";
			updateData("blog_post",$data,$where);
            
            $db->query('DELETE FROM ' . $dbPrefix . 'blog_post_category WHERE post_id ='.$id);
            
            foreach ($a as $value) 
            {
                $data1 = array('category_id'=>$value,'post_id'=>$id);
                insertData("blog_post_category",$data1);
            }
			//echo $db->last_query();
            //exit;
			redirect(getAdminLink('blog'));
			exit;
		}
	}
	
}

/*

$act = 'add';
if($id) {
	$act = 'update';
	$result = $db->query('SELECT * FROM ' . $dbPrefix . 'blog_post WHERE id ='.$id);
	$data = $result->row_array();
}


$fields = array('title', 'description', 'image', 'url_slug', 'author', 'status');
foreach($fields as $field) {
	if(isset($_POST[$field])) {
		${$field} = $_POST[$field];
	} else if(isset($data[$field])) {
		${$field} = $data[$field];
	} else {
		${$field} = null;
	}
}
*/

$selectCategory = $db->query('SELECT category.id, category.title FROM ' . $dbPrefix . 'blog_category as category WHERE category.status=1 order by category.title', array());

$selected_category = $db->query('SELECT post_id, category_id FROM ' . $dbPrefix . 'blog_post_category WHERE post_id= ?', array($id));

$aa = $selected_category->result_array();
$zz = array();
foreach($aa as $a=>$k){
    $zz[] = $k['category_id'];
}
//exit;
?>


<div class="content-wrapper">
    <section class="content-header">
		<h1>Blog</h1>
        <ol class="breadcrumb">
			<li><a href="<?php echo getAdminLink();?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?php echo getAdminLink('blogcategory');?>"><i class="fa fa-folder"></i> Blog</a></li>
        </ol>
	</section>
    <section class="content">
		<div class="row">
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-name">Blog</h3>
					</div>
                    <div class="error"></div>
                    
					<form action='' method='post' class="validateForm" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?php echo $id ?>"/>
						<input type="hidden" name="act" value="<?php echo $act ?>"/>
                        <div class="box-body">
                        
                            <?php 
                                if($image!=''){
								$src = BLOG_URL . $image;  
						    ?>
								<div class="form-group">
									<!-- <label for="exampleInputEmail1">Image</label> -->
									<a href="<?php echo $src; ?>" class="enLarge" ><img src="<?php echo $src; ?>"  width='80' width='80'/></a><br/>
								</div>
							<?php } ?>
                            
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" name="blogimage" id="blogimage" class="form-control input3 mini" data-validation-engine="validate[funcCall[geThan[]]]" data-errormessage-value-missing="Only JPG, JPEG and PNG are allowed">
							</div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type='text' name='name' class='form-control' value="<?php echo $title; ?>" placeholder="Name" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter blog name">
                            </div>
                            <div class="form-group" style="display: none;">
                                <label>Category</label></td>
                                <select style="width: 320px;" class="form-control" id="category" name="c_chk[]" size="6" multiple data-validation-engine="validate[required]" data-errormessage-value-missing="Please select category name">
                                    <!-- <option value="">Select Category</option> -->
                                    <?php
                                        foreach ($selectCategory->result_array() as $row) {
                                        $catCheck = "";    
                                        if(in_array($row['id'],$zz))
											$catCheck = "selected";    
                                        $categoryName = $row['title'];    ?>
                                        <option value="<?php echo $row['id']; ?>" <?php echo $catCheck; ?>><?php echo $categoryName; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>URL Slug</label>
                                <input type='text' name='url_slug' class='form-control' value="<?php echo $url_slug; ?>" placeholder="URL Slug">
                            </div>
                            <div class="form-group">
                                <label>Description:</label><br>
                                <textarea name='description' class="form-control summernote" ><?php echo $description; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Author</label>
                                <input type='text' name='author' class='form-control' value="<?php echo $author; ?>" placeholder="Author name" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter author name">
                            </div>
                            <div class="form-group field-status1">
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
	
        var extFile  = document.getElementById("blogimage").value;
        var ext = extFile.split('.').pop();
        var filesAllowed = ["jpg", "jpeg", "png"];
        if( (filesAllowed.indexOf(ext)) == -1)
            return "Only JPG, JPEG , PNG files are allowed";
    }

$(document).ready(function(){
    $(".validateForm").validationEngine({promptPosition : "inline", scroll: true});   
});

</script>