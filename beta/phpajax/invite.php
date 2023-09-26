<?php include('../config.php');

$response = array();
$redirect = null;
$error = null;
$msg = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $invite = $_POST['invite'];
    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND email = ?', array($invite));
    
    if ($result->num_rows() == 0) {
        
        $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($_SESSION['id']));
        
        if ($result->num_rows() > 0) {
            
            $userInfo = $result->row_array();
            
            $message = '<p>Hi ' . $invite. '</p>';
            $message .= '<p>You have been invited by ' . $userInfo['first_name'] . ' ' . $userInfo['last_name'] . ' to join ' . SITE_TITLE. '.</p>';
            $message .= '<p>please <a href="' . getLink(). '" title="' . SITE_TITLE. '">click here to join.</p>';
            
            $msgdata = array(
                'to' => array($invite),
                'subject' => "invite",
                'message' => $message
            );
            sendMsg($msgdata);
            
            $msg = 'Invitation send successfully.';
        } else {
            $redirect = getLink();
        }
        
    } else {
        $error = 'Email already exists on website.';
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