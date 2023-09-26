<?php include('../config.php');

$response = array();
$redirect = null;
$error = null;
$msg = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
    $user_amenity_id = isset($_POST['user_amenity_id']) ? $_POST['user_amenity_id'] : 0;
    $trial_session_id = isset($_POST['trial_session_id']) ? $_POST['trial_session_id'] : 0;
    
    if($user_id != 0 && $user_amenity_id != 0 && $trial_session_id != 0) {
        
        $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND user_type = 1 AND id = ?', array($user_id));
        
        if ($result->num_rows() > 0) {
            $userInfo = $result->row_array();
            
            $result = $db->query('SELECT * FROM ' . $dbPrefix . 'trial_session WHERE date > Now() AND (space-booked) > 0 AND id = ?', array($trial_session_id));
            
            if ($result->num_rows() > 0) {
                $trialSessionInfo = $result->row_array();
                
                $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user_amenities WHERE availability = 1 AND id = ?', array($user_amenity_id));
            
                if ($result->num_rows() > 0) {
                    $userAmenityInfo = $result->row_array();
                    
                    $date = date('Y-m-d H:i:s');
                    $userTrialSessionData = array(
                                'user_id' => $user_id,
                                'trial_session_id' => $trialSessionInfo['id'],
                                'booking_date' => $date
                            );
                    insertData('user_trial_session', $userTrialSessionData);
                    
                    if ($userAmenityInfo['data'] != "") {
                        $data = unserialize($userAmenityInfo['data']);
                    } else {
                        $data = array();
                    }
                    
                    $data['trial_session_id'] = $trialSessionInfo['id'];
                    $data['trial_session_date'] = $trialSessionInfo['date'];
                    $data = serialize($data);
                    
                    $userAmenityData = array(
                                'status' => 1,
                                'data' => $data,
                                'date_modified' => $date
                            );
                    $where ='id =' . $user_amenity_id .'';
                    updateData('user_amenities', $userAmenityData, $where);
                    
                    $trialSessionData = array(
                                'booked' => $trialSessionInfo['booked'] + 1
                            );
                    $where ='id =' . $trialSessionInfo['id'] .'';
                    updateData('trial_session', $trialSessionData, $where);
                    
                    $msg = 'Trial session date booked successfully.';
                    
                } else {
                    $redirect = getLink('profile.php', 'tab=plan');
                }
                
            } else {
                $redirect = getLink('profile.php', 'tab=plan');
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