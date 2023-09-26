<?php include('../../config.php');

$response = array();
$redirect = null;
$error = null;
$msg = null;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $adminsessionstr = trim($_SESSION['adminsessionid']);
    $parts = explode(";",$adminsessionstr);
    $admin_id = $parts[0];
    
    if ($admin_id > 0) {
            
        $id = isset($_POST['id']) ? $_POST['id'] : 0;
        $user_amenity_id = isset($_POST['user_amenity_id']) ? $_POST['user_amenity_id'] : 0;
        $message = isset($_POST['message']) ? $_POST['message'] : '';
        
        $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($id));
        
        if ($result->num_rows() > 0) {
            $userInfo = $result->row_array();
            
            $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user_amenities WHERE id = ?', array($user_amenity_id));
            
            if ($result->num_rows() > 0) {
            
                $amenityInfo = $result->row_array();
                if($amenityInfo['data'] != "") {
                    $amenityData = unserialize($amenityInfo['data']);
                } else {
                    $amenityData = array();
                }
                
                if ($amenityInfo['status'] == 0) {
                    
                    $amenityData['date_sent'] = date('Y-m-d H:i:s');
                    $planAmenityData = array(
                                'status' => 1,
                                'data' => serialize($amenityData),
                                );
                    updateData('user_amenities', $planAmenityData, 'id=' . $user_amenity_id);
                    
                    $messagedata = '<p>Hi ' . $userInfo['first_name'] . ' ' . $userInfo['last_name'] . '</p>';
                    $messagedata .= '<p>' . $message . '</p>';

                    $msgdata = array(
                                'to' => array($userInfo['email']),
                                'subject' => "Potential trial invitation",
                                'message' => $messagedata
                            );
                    sendMsg($msgdata);
                    
                    $_SESSION['msgType'] = 'success';
                    $_SESSION['msgString'] = 'Potential trial invitation send successfully.';
                    $redirect = getAdminLink('userinfo', 'id=' . $id . '&tab=plan');
                    
                } else {
                    
                    $error = 'Potential trial invitation already sent!';
                
                }
                
            } else {
                $redirect = getAdminLink('userinfo', 'id=' . $id . '&tab=plan');
            }
            
        } else {
            $redirect = getAdminLink('user');
        }
    } else {
        $redirect = getAdminLink();
    }
    
} else {
    
    $redirect = getAdminLink();
}

$response = array(
				'redirect' => $redirect,
				'error' => $error,
				'msg' => $msg,
			);
header("Content-type: application/json; charset=utf-8");
echo json_encode($response);