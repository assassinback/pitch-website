<?php include('../config.php');

$response = array();
$redirect = null;
$error = null;
$msg = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
    $profile_id = isset($_POST['profile_id']) ? $_POST['profile_id'] : 0;
    $user_message = isset($_POST['message']) ? $_POST['message'] : null;
    
    if($user_id != 0 && $profile_id != 0 && $user_id != $profile_id) {
        
        $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($user_id));
        
        if ($result->num_rows() > 0) {
            $userInfo = $result->row_array();
            
            $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($profile_id));
            
            if ($result->num_rows() > 0) {
                $profileInfo = $result->row_array();
                
                $message = '<p>Hi ' . $profileInfo['first_name'] . ' ' . $profileInfo['last_name'] . '</p>';
                $message .= '<p>' . $userInfo['first_name'] . ' ' . $userInfo['last_name'] . ' has sent you a message.</p>';
                $message .= '<p>Please check below message:</p>';
                $message .= '<p><strong>' . $user_message . '</strong></p>';
                
                $msgdata = array(
                    'to' => array($profileInfo['email']),
                    'from_name' => $userInfo['first_name'] . ' ' . $userInfo['last_name'],
                    'from_email' => $userInfo['email'],
                    'subject' => $userInfo['first_name'] . ' ' . $userInfo['last_name'] . " has sent you a message",
                    'message' => $message
                );
                sendMsg($msgdata);
                
                $msg = 'Message sent successfully.';
                
            } else {
                $redirect = getLink();
            }
            
        } else {
            $redirect = getLink();
        }
        
    } else {
        $redirect = getLink();
    }
    
} else {
    
    $redirect = getLink();
}

$response = array(
				'redirect' => $redirect,
				'error' => $error,
				'msg' => $msg,
			);
header("Content-type: application/json; charset=utf-8");
echo json_encode($response);