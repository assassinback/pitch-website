<?php include('config.php');

if (checkLogin()) {
    redirect(getLink());
}

$error = false;
$success = false;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE email = ? AND status = 1', array($email));
    
    if ($result->num_rows() > 0) {
        $userInfo = $result->row_array();
        
        $password = randomString(8);        
        $db->query('UPDATE ' . $dbPrefix . 'user SET PASSWORD = ? WHERE id = ? ', array($bcrypt->hash_password($password), $userInfo['id']));
        
        $message = '<p>Hi ' . $userInfo['first_name'] . ' ' . $userInfo['last_name'] . '</p>';
        $message .= '<p>Please use below password to login into your account.</p>';
        $message .= '<p><b>New password :</b> ' . $password . '</p>';

        $msgdata = array(
                    'to' => array($userInfo['email']),
                    'subject' => "New Password",
                    'message' => $message
                );
        sendMsg($msgdata);
        
        $success = true;
        /* $userInfo = $result->row_array();
        $_SESSION['id'] = $userInfo['id'];
        $_SESSION['user_type'] = $userInfo['user_type']; */
        
        //redirect(getLink());
    } else {
        $error = true;
    }
}

$document['style'][] = 'validationEngine.jquery.css';
$document['script'][] = 'jquery.validationEngine.js';
$document['script'][] = 'jquery.validationEngine-en.js';

$page_title = 'Forgot Password?';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => 'Login', 'link' => getLink('login.php')), array('title' => $page_title, 'link' => getLink('forgot_password.php')));
include('common/header.php');
?>

<div class="stj_login_wrap">
    <div class="container">
        <div class="row">
            
            <div class="login_dv">
                <div class="lg_dv_lft">
                    <h2>Forgot Password?</h2>
                    
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
                    
                    <form role="form" class="validateForm" name="loginForm" action="" method="post" enctype="multipart/form-data">
                    <ul>
                        <li>
                            <label>Email Address <em>*</em></label>
                            <input type="text" name="email" id="email" class="txt_lg" data-validation-engine="validate[required,custom[email]]" data-errormessage-value-missing="The e-mail address you entered appears to be incorrect." maxlength="70" data-errormessage-custom-error="Example: test@gmail.com"/>
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

<script>
    $(document).ready(function(){
        $(".validateForm").validationEngine({promptPosition : "inline", scroll: true});
    });
</script>

<?php include('common/footer.php');?>