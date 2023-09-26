<?php
$module = 'setting';
checkPermission($module);

$pageTitle = 'Settings';

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$data = array();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
    foreach ($_POST as $key => $value) {
        if ($key == 'submit_me')
            continue;
        
        $data = array(
                'config_value' => $value
            );
        $where ="config_key = '" . $key . "' ";
        updateData("config",$data,$where);
    }
    
    $_SESSION['msgType'] = 'success';
    $_SESSION['msgString'] = 'Settings saved successfully!';
    redirect(getAdminLink($module));
    exit;
}


$results = $db->query('SELECT * FROM ' . $dbPrefix . 'config');
$results = $results->result_array();

foreach($results as $result) {
	${$result['config_key']} = $result['config_value'];
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
                
                <?php if (isset($msgString)) { ?>
                    <div class="alert alert-<?php echo $msgType; ?>">
                        <?php echo $msgString; ?>
                    </div>
                <?php } ?>
                
                <div class="nav-tabs-custom">
                    <form action='' method='post' class="validateForm" enctype="multipart/form-data">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab">General</a></li>
                            <li><a href="#tab_2" data-toggle="tab">SMTP</a></li>
                            <li style="display: none;"><a href="#tab_3" data-toggle="tab">Advance</a></li>
                            <li class="pull-right"><a class="text-muted"><i class="fa fa-gear"></i></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="col-xs-12" >
                                    <div class="form-group">
                                        <label>Site Title</label>
                                        <input type='site_title' name='site_title' class='form-control' value="<?php echo $site_title; ?>" placeholder="Name" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter site name">
                                    </div>
                                    <div class="form-group">
                                        <label>Admin Title</label>
                                        <input type='admin_title' name='admin_title' class='form-control' value="<?php echo $admin_title; ?>" placeholder="Name" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter admin name">
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type='site_email' name='site_email' class='form-control' value="<?php echo $site_email; ?>" placeholder="Email Address" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter email address">
                                    </div>
                                    <div class="form-group">
                                        <label>Phone No</label>
                                        <input type='site_phone_no' name='site_phone_no' class='form-control' value="<?php echo $site_phone_no; ?>" placeholder="Phone No" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter phone no">
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type='site_address' name='site_address' class='form-control' value="<?php echo $site_address; ?>" placeholder="Address" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter address">
                                    </div>
                                    <div class="form-group">
                                        <label>Contact Email</label>
                                        <input type='contact_email' name='contact_email' class='form-control' value="<?php echo $contact_email; ?>" placeholder="Contact Email" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter contact email">
                                    </div>
                                    <div class="form-group">
                                        <label>Copyright Text</label>
                                        <input type='copyright_text' name='copyright_text' class='form-control' value="<?php echo $copyright_text; ?>" placeholder="Copyright Text" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter copyright text">
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_2">
                                <div class="col-xs-12" >
                                    <div class="form-group">
                                        <label>Host</label>
                                        <input type='smtp_host' name='smtp_host' class='form-control' value="<?php echo $smtp_host; ?>" placeholder="Host" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter host name">
                                    </div>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type='smtp_username' name='smtp_username' class='form-control' value="<?php echo $smtp_username; ?>" placeholder="Username" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter username">
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type='smtp_password' name='smtp_password' class='form-control' value="<?php echo $smtp_password; ?>" placeholder="Password" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter password">
                                    </div>
                                    <div class="form-group">
                                        <label>From Name</label>
                                        <input type='smtp_from_name' name='smtp_from_name' class='form-control' value="<?php echo $smtp_from_name; ?>" placeholder="From Name" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter from name">
                                    </div>
                                    <div class="form-group">
                                        <label>From Email</label>
                                        <input type='smtp_from_email' name='smtp_from_email' class='form-control' value="<?php echo $smtp_from_email; ?>" placeholder="From Email" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter from email">
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_3">
                                <div class="col-xs-12" >
                                    <div class="form-group">
                                        <label>Mail Type</label>
                                        <input type='mail_type' name='mail_type' class='form-control' value="<?php echo $mail_type; ?>" placeholder="Mail Type" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select mail type">
                                    </div>
                                    <div class="form-group">
                                        <label>Blog Date Format</label>
                                        <input type='blog_date_format' name='blog_date_format' class='form-control' value="<?php echo $blog_date_format; ?>" placeholder="Blog Date Format" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter blog date format">
                                    </div>
                                    <div class="form-group">
                                        <label>Default Date Format</label>
                                        <input type='default_date_format' name='default_date_format' class='form-control' value="<?php echo $default_date_format; ?>" placeholder="Default Date Format" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter default date format">
                                    </div>
                                    <div class="form-group">
                                        <label>Default Date Time Format</label>
                                        <input type='default_date_time_format' name='default_date_time_format' class='form-control' value="<?php echo $default_date_time_format; ?>" placeholder="Default Date Time Format" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter default date time format">
                                    </div>
                                    <div class="form-group">
                                        <label>Currency Code</label>
                                        <input type='currency_code' name='currency_code' class='form-control' value="<?php echo $currency_code; ?>" placeholder="Currency Code" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter currency code">
                                    </div>
                                    <div class="form-group">
                                        <label>Currency Symbol</label>
                                        <input type='currency_symbol' name='currency_symbol' class='form-control' value="<?php echo $currency_symbol; ?>" placeholder="Currency Symbol" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter currency symbol">
                                    </div>
                                    <div class="form-group">
                                        <label>Currency Position</label>
                                        <input type='currency_position' name='currency_position' class='form-control' value="<?php echo $currency_position; ?>" placeholder="Currency Position" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter currency position">
                                    </div>
                                    <div class="form-group">
                                        <label>Google Api Key</label>
                                        <input type='google_api_key' name='google_api_key' class='form-control' value="<?php echo $google_api_key; ?>" placeholder="Google Api Key" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter google api key">
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                        <div class="box-footer">
                            <button type="submit" name="submit_me"  class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
			</div>
		</div>
	</section>
</div>

<script>

$(document).ready(function(){
    $(".validateForm").validationEngine({promptPosition : "inline", scroll: true});   
});
</script>