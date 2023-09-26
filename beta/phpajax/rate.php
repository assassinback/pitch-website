<?php include('../config.php');

$response = array();
$redirect = null;
$error = null;
$msg = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $rater_user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
    $profile_id = isset($_POST['profile_id']) ? $_POST['profile_id'] : 0;
    $rating = isset($_POST['rating']) ? $_POST['rating'] : 1;
    
    if ($rater_user_id != 0 && $profile_id != 0) {
        
        $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($rater_user_id));
        
        if ($result->num_rows() > 0) {
            
            $raterInfo = $result->row_array();
            
            $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($profile_id));
            
            if ($result->num_rows() > 0) {
                
                $profileInfo = $result->row_array();
                
                $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user_rating WHERE rater_user_id = ? AND rated_user_id = ?', array($rater_user_id, $profile_id));
                    
                if ($result->num_rows() == 0) {
                    
                    $date_added = date('Y-m-d H:i:s');
                    $userData = array(
                        'rater_user_id' => $rater_user_id,
                        'rated_user_id' => $profile_id,
                        'rating' => $rating,
                        'date_added' => $date_added
                    );
                    insertData('user_rating', $userData);
                    
                    $rating_avg_result = $db->query('SELECT AVG(rating) AS averagerating FROM ' . $dbPrefix . 'user_rating WHERE rated_user_id = ?', array($profile_id));
                    $averageInfo = $rating_avg_result->row_array();
                    $average_rating = $averageInfo['averagerating'];
                    
                    $db->query('UPDATE ' . $dbPrefix . 'user SET user_rating = ? WHERE id = ? ', array($average_rating, $profile_id));
                    
                    $date_sent = date('Y-m-d H:i:s');
                    $notificationData = array(
                                    'user_id' => $profile_id,
                                    'sender_id' => $rater_user_id,
                                    'type' => 'rating',
                                    'date_sent' => $date_sent
                                );
                    insertData('notification', $notificationData);
                    
                    $msg = "Rating applied successfully.";
                    
                } else {
                    $error = "You have already ratd this player.";
                }
                
            } else {
                $redirect = getLink('players.php');
            }
            
        } else {
            $redirect = getLink('11');
        }
    
    } else {
        $redirect = getLink('131');
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