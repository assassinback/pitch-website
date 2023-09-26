<?php
require_once '../config.php';

if (checkAdminLogin()) {
    redirect(getAdminLink());
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $username = $_POST['admin_username'];
    $password = $_POST['admin_password'];
    
    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'admin WHERE status = 1 AND email = ?', array($username));
    
    if ($result->num_rows() > 0) {
        
        $adminInfo = $result->row_array();
        if ($bcrypt->check_password($password, $adminInfo['password'])) {
            
            $_SESSION['adminsessionid'] = trim($adminInfo['id']).";admin;".session_id();
            redirect(getAdminLink());
            
        } else {
        
            $error = 'Please enter correct username and password!';
        }
    } else {
        
        $error = 'Please enter correct username and password!';
    }
    
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo SITE_TITLE; ?> Admin | Log in</title>
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
                <h3 class="text-center">Login</h3>
                
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>
                
                <form action="" method="post" name="logFrm" onsubmit="">
                    <div class="form-group has-feedback">
                        <input type="email" tabindex="1" class="form-control" name="admin_username" autofocus placeholder="Email" required >
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" tabindex="2"  class="form-control" name="admin_password"  placeholder="Password" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                    </div>
                </form>
                <a href="forgot.php">I forgot my password</a><br>
            </div>
        </div>
    </body>
</html>