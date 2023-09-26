<?php include('config.php');

if (!checkLogin()) {
    redirect(getLink('login.php'));
}

$id = $_SESSION['id'];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password == "") {
        $error = 'Please enter your password!';
    } else if (strlen($password) < 6) {
        $error = 'Password must be atleast 6 character!';
    } else if ($password != $confirm_password) {
        $error = 'Password and confirm password must be same!';
    } else {
        
        $userData = array(
                            'password' => $bcrypt->hash_password($password),
                            'date_modified' => date('Y-m-d H:i:s')
                        );
        
        $where ='id =' . $id;
        $update = updateData('user', $userData, $where);
        $success = 'Password changed successfully.';
    }
}

$page_title = 'Change Password';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => 'Profile', 'link' => getLink('profile.php')), array('title' => $page_title, 'link' => getLink('change_password.php')));
include('common/header.php');
?>

<div class="stj_login_wrap">
    <div class="container">
        <div class="row">
            
            <div class="login_dv">
                <div class="lg_dv_lft">
                    <h2><?php echo $page_title; ?></h2>
                    
                    <?php if (isset($error)) { ?>
                    <div class="alert alert-danger">
                        <strong>Error!</strong> <?php echo $error; ?>
                    </div>
                    <?php } ?>
                    
                    <?php if (isset($success)) { ?>
                    <div class="alert alert-success">
                        <strong>Success!</strong> <?php echo $success; ?>
                    </div>
                    <?php } ?>
                    
                    <form role="form" class="validateForm" name="loginForm" action="" method="post" enctype="multipart/form-data">
                    <ul>
                        <li>
                            <label>Password </label>
                            <input type="password" name="password" id="password" class="txt_lg" />
                            <small id="passwordHelp" class="form-text text-muted">Please enter minimum 6 character</small>
                        </li>
                        <li>
                            <label>Confirm Password </label>
                            <input type="password" name="confirm_password" id="confirm_password" class="txt_lg" />
                        </li>
                        <li>
                            <input type="submit" value="Submit" class="btn_lg"/>
                        </li>
                    </ul>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include('common/footer.php');?>