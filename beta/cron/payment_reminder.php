<?php
$logFile = 'payment-reminder';

$date = date('Y-m-d');
$now = date('Y-m-d H:i:s');
generateLog($logFile, 'Start : ' . $now);
$users = $db->query('SELECT *, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan INNER JOIN ' . $dbPrefix . 'user as USER ON user.id = user_test_plan.user_id WHERE datediff(next_payment_date, CURDATE()) = 3', array());

if ($users->num_rows() == 0) {
    generateLog($logFile, 'No user Found');
} else {
    
    $users = $users->result_array(); 
    foreach ($users as $user) {
        
        $user_id = $user['user_id'];
        $price = $user['price'];
        $plan_type = $user['type'];
        $plan_id = $user['test_plan_id'];
        
        generateLog($logFile, 'User : ' . $user_id);
        
        $plan = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan WHERE status = 1 and id = ?', array($plan_id));
        $planInfo = $plan->row_array();
        
        if ($price != 0) {
        } else {
            $plan_type = null;
        }
        
        
        $msg = 'Your plan has been renewed successfully.';
                    
        $emailSubject = 'Subscription Plan Payment Reminder';
        $emailMessage = '<p>Hi ' . $user['first_name'] . ' ' . $user['last_name'] . '</p>';
        $emailMessage .= '<p>This is a friendly reminder that your subscription plan will be renew in next 3 days. Please find the plan details below:</p>';
        $emailMessage .= '<p><b>Plan</b> : ' . $planInfo['title'] . ' - ' . $planInfo['sub_title'] . '</p>';
        if ($price != 0) {
            $emailMessage .= '<p><b>Type</b> : ' . $plan_type . '</p>';
        }
        $emailMessage .= '<p><b>Price</b> : ' . formatPrice($price) . '</p>';
        $emailMessage .= '<p><b>Payment Date</b> : ' . formatDate($user['next_payment_date']) . '</p>';
        
        $msgdata = array(
            'to' => array($user['email']),
            'subject' => $emailSubject,
            'message' => $emailMessage
        );
        sendMsg($msgdata);
        
        generateLog($logFile, 'success');
    }
}

generateLog($logFile, "End : " . date('Y-m-d H:i:s'));
generateLog($logFile, "\n");