<?php include('../config.php');

$response = array();
$redirect = null;
$error = null;
$msg = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $coach_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
    $profile_id = isset($_POST['profile_id']) ? $_POST['profile_id'] : 0;
    
    if ($coach_id != 0 && $profile_id != 0) {
        
        $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND user_type = 2 AND id = ?', array($coach_id));
        
        if ($result->num_rows() > 0) {
            
            $coachInfo = $result->row_array();
            
            $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND user_type = 1 AND id = ?', array($profile_id));
            
            if ($result->num_rows() > 0) {
                
                $profileInfo = $result->row_array();
                
                $result = $db->query('SELECT * FROM ' . $dbPrefix . 'score_validation WHERE status = 0 AND player_id = ? AND coach_id = ?', array($profileInfo['id'], $coach_id));
                    
                if ($result->num_rows() > 0) {
                    
                    $userTestData = array(
                                'require_validation' => 0,
                                'validated' => 1
                                );
                    updateData('user_test_score', $userTestData, 'user_id=' . $profile_id);
                    
                    $score_validated_date = date('Y-m-d H:i:s');
                    $userData = array(
                                            'score_validated_by' => $coach_id,
                                            'score_validated_date' => $score_validated_date
                                            );
                    updateData('user', $userData, 'id=' . $profile_id);
                    
                    $validate_date = date('Y-m-d H:i:s');
                    $validateData = array(
                                            'status' => 1,
                                            'validate_date' => $validate_date,
                                            );
                    updateData('score_validation', $validateData, 'coach_id=' . $coach_id . ' AND player_id=' . $profile_id);
                    
                    $date_sent = date('Y-m-d H:i:s');
                    $notificationData = array(
                        'user_id' => $profile_id,
                        'sender_id' => $coach_id,
                        'type' => 'score_validated',
                        'date_sent' => $date_sent
                    );
                    insertData('notification', $notificationData);
                    
                    $msg = 'Score validated successfully.';
                    
                } else {
                    $redirect = getLink('profile.php', 'tab=score&profile_id=' . $profile_id);
                }
                
            } else {
                $redirect = getLink('score_validation_request.php');
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