<?php include('config.php');

if (checkLogin()) {
    redirect(getLink());
}

$error = false;
if($_SERVER['REQUEST_METHOD'] == 'POST') {

    echo '<script>console.log("checking login")</script>';

    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE email = ? AND status = 1', array($email));
    
    if ($result->num_rows() > 0) {
        // echo '<script>console.log("login user found")</script>';
        $userInfo = $result->row_array();
        if ($bcrypt->check_password($password, $userInfo['password'])) {
            $_SESSION['id'] = $userInfo['id'];
            $_SESSION['user_type'] = $userInfo['user_type'];
            echo '<script>console.log("user set")</script>';
            redirect(getLink());
        } else {
            $error = true;
        }
    } else {
        $error = true;
    }
}

$document['style'][] = 'validationEngine.jquery.css';
$document['script'][] = 'jquery.validationEngine.js';
$document['script'][] = 'jquery.validationEngine-en.js';

$page_title = 'Login';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('login.php')));
include('common/header.php');
?>

<div class="stj_login_wrap">
    <div class="container">
        <div class="row">
            
            <div class="login_dv">
                <div class="lg_dv_lft">
                    <h2>Login</h2>

                    <?php if (isset($_GET['redirect'])) { ?>
                    <div class="alert alert-warning">
                        <strong>Warning!</strong> You need to login or register a new account to view <?= $_GET['redirect'] ?>
                    </div>
                    <?php } ?>
                    
                    <?php if ($error) { ?>
                    <div class="alert alert-danger">
                        <strong>Error!</strong> Please enter correct email address and password!
                    </div>
                    <?php } ?>
                    
                    <form role="form" class="validateForm" name="loginForm" action="" method="post" enctype="multipart/form-data">
                    <ul>
                        <li>
                            <label>Email Address <em>*</em></label>
                            <input type="text" name="email" id="email" class="txt_lg" data-validation-engine="validate[required,custom[email]]" data-errormessage-value-missing="The e-mail address you entered appears to be incorrect." maxlength="70" data-errormessage-custom-error="Example: test@gmail.com"/>
                        </li>
                        <li>
                            <label>Password <em>*</em></label>
                            <input type="password" name="password" id="password" class="txt_lg" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter password" />
                        </li>
                        <li>
                            <a class="a_fp" href="<?php echo getLink('forgot_password.php'); ?>">Forgot Password?</a>
                            <input type="submit" value="Login" class="btn_lg"/>
                        </li>
                        
                        <li>
                        </li>
                        
                    </ul>
                    </form>
                </div>
                <!-- <div class="col">
                    Or
                </div> -->
                <div class="reg-section ">
                    <a class="btn_lg"  href="<?php echo getLink('register.php'); ?>">Register Here</a>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(".validateForm").validationEngine({promptPosition : "inline", scroll: true});
    });
</script>

<?php include('common/footer.php');?>