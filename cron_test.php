<?php 
include('config.php');

$logFile = 'cron-test';
$now = date('Y-m-d H:i:s');
generateLog($logFile, 'Start : ' . $now);

$date = date('Y-m-d');
$now = date('Y-m-d H:i:s');
generateLog($logFile, 'Start : ' . $now);
$users = $db->query('SELECT *, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan INNER JOIN ' . $dbPrefix . 'user as user ON user.id = user_test_plan.user_id WHERE datediff(next_payment_date, CURDATE()) <= 3', array());
print_r($users->result_array());
$users = $db->query('SELECT *, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan INNER JOIN ' . $dbPrefix . 'user as user ON user.id = user_test_plan.user_id WHERE user_test_plan.next_payment_date < ?', array($date));
print_r($users->result_array());
echo $db->last_query();
generateLog($logFile, 'Start : ' . $now);
