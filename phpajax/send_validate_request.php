<?php include('../config.php');

$response = array();
$redirect = null;
$error = null;
$msg = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $coach = isset($_POST['coach']) ? $_POST['coach'] : array();
    
    if (count($coach) > 0) {
        $part_coach = implode (',',$coach);
		
        $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND user_type = 1 AND id = ?', array($_SESSION['id']));
		
        
		if ($result->num_rows() > 0) {
            
            $userInfo = $result->row_array();
			
            $request_date = date('Y-m-d H:i:s');
            
            foreach ($coach as $coach_id) {
                
                $result = $db->query('SELECT * FROM ' . $dbPrefix . 'score_validation WHERE status = 0 AND player_id = ? AND coach_id = ?', array($userInfo['id'], $coach_id));
				
				$coachInfo = $result->row_array();
                
                if ($result->num_rows() == 0) {
                    $validateRequestData = array(
                                    'player_id' => $userInfo['id'],
                                    'coach_id' => $coach_id,
                                    'request_date' => $request_date
                                    );
                    insertData('score_validation', $validateRequestData);
					
					
                    
            
                    $user_id=$_SESSION["id"];
                    $i=0;
                    
                    $query="SELECT validation_request_date,validation_request_count from pitch_user where id=$user_id";
                    if (!$result=$db->query($query)) {
                        echo("Error description: " . $db -> error);
                    }
                    $row=$result->result_array();
                    foreach($row as $rows)
                    {
                        if($rows["validation_request_count"]<=0)
                        {
                            $i++;
                            // $msg="You have reached your monthly validation limit";   
                        }
                    }
                    if($i>0)
                    {
                    $date_sent = date('Y-m-d H:i:s');
                    $notificationData = array(
                        'user_id' => $coach_id,
                        'sender_id' => $userInfo['id'],
                        'type' => 'validation_request',
                        'date_sent' => $date_sent
                    );
                    }
                    insertData('notification', $notificationData);
                } else {
                    if (count($coach) == 1) {
                        $error = 'Validate score request already sent to this Coach!';
                    }
                }
            }
            
            $coach_users = $db->query('SELECT email FROM ' . $dbPrefix . 'user WHERE status = 1 AND user_type = 2 AND id in ('.($part_coach).')');
            if(!empty($coach_users)) {
				$coach_emails = array();
                $coach_datas = $coach_users->result_array();
                foreach ($coach_datas as $coach_data) {
					
					$coach_emails[] = $coach_data['email'];

                }
				$message = '<p>' . ucfirst($userInfo['first_name']) . ' ' . $userInfo['last_name'] . ' has requested to validate the score.</p>';
				$msgdata = array(
					'to' => $coach_emails,
					'subject' => "Score Validate",
					'message' => $message
				);
				sendMsg($msgdata);
            }
            
            $msg = 'Validation Score Request Sent Successfully.';
            
            $user_id=$_SESSION["id"];
            
            $query="SELECT validation_request_date,validation_request_count from pitch_user where id=$user_id";
            if (!$result=$db->query($query)) {
                echo("Error description: " . $db -> error);
            }
            $row=$result->result_array();
            foreach($row as $rows)
            {
                if($rows["validation_request_count"]<=0)
                {
                    $msg="You have reached your monthly validation limit";   
                }
            }
            $query2="UPDATE pitch_user set validation_request_count=validation_request_count-1 where id=$user_id";
            if (!$result2=$db->query($query2)) {
                echo("Error description: " . $db -> error);
            }
            
        } else {
            $redirect = getLink();
        }
        
    } else {
        $error = 'Please select coach to send request!';
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