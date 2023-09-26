<?php 
require_once '../config.php';

if (checkAdminLogin()) {
    redirect(getAdminLink());
}

$error = false;
$success = false;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['admin_username'];
    
    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'admin WHERE status = 1 AND email = ?', array($username));
    
    if ($result->num_rows() > 0) {
        $userInfo = $result->row_array();
        
        $password = randomString(8);        
        $db->query('UPDATE ' . $dbPrefix . 'admin SET password = ? WHERE id = ? ', array($bcrypt->hash_password($password), $userInfo['id']));
        
        $message = '<p>Hi ' . $userInfo['name'] . '</p>';
        $message .= '<p>Please use below password to login into your account.</p>';
        $message .= '<p>New password : ' . $password . '</p>';

        $msgdata = array(
                    'to' => array($userInfo['email']),
                    'subject' => "New Password",
                    'message' => $message
                );
        if(sendMsg($msgdata)){
			$success = true;
		} else {
			$error = true;
		}
        
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo SITE_TITLE; ?> Admin | Forgot Password?</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="css/theme.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="css/blue.css">
        
        <script src="js/jQuery/jquery-2.2.3.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="<?php echo ADMIN_URL;?>"><?php echo SITE_TITLE; ?> Admin</a>
            </div>
            <div class="login-box-body">
                <h3 class="text-center">Forgot Password?</h3>
                
                <?php if ($error) { ?>
                    <div class="alert alert-danger">
                        <strong>Error!</strong> Please enter correct email address!
                    </div>
                <?php } ?>
                
                <?php if ($success) { ?>
                    <div class="alert alert-success">
                        <strong>Success!</strong> New password has been sent. Please check your email.
                    </div>
                <?php } ?>
                
                <form action="" method="post" name="logFrm" onsubmit="">
                    <div class="form-group has-feedback">
                        <input type="email" tabindex="1" class="form-control" name="admin_username" autofocus placeholder="Email" required >
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
                        </div>
                    </div>
                    <span><a href="index.php">Back to login</a></span>
                </form>
            </div>
        </div>
    </body>
</html>

