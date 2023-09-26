<?php include('../config.php');

$response = array();
$redirect = null;
$error = null;
$msg = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
    
    if($user_id != 0) {
        
        $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($user_id));
        
        if ($result->num_rows() > 0) {
            $userInfo = $result->row_array();
            
            $key = '-' . str_pad($userInfo['id'], 4, '0', STR_PAD_LEFT);
            $files = scandir(USER_PATH);
            foreach ($files as $file) {
                if (strpos($file, $key) !== false) {
                    unlink(USER_PATH . $file);
                }
            }
            
            $db->query('DELETE FROM ' . $dbPrefix . 'user WHERE id = ?', array($user_id));
            
            $redirect = getLink();
            
            session_destroy();
            session_start();
            $_SESSION['flash'] = 'Account Deleted Successfully.';
            
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