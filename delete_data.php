<?php include('config.php');
  
/* $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user', array());

foreach ($result->result_array() as $userInfo) {
    if ($userInfo['id'] != 62) {
        $key = '-' . str_pad($userInfo['id'], 4, '0', STR_PAD_LEFT);
        $files = scandir(USER_PATH);
        foreach ($files as $file) {
            if (strpos($file, $key) !== false) {
                unlink(USER_PATH . $file);
            }
        }
        
        $db->query('DELETE FROM ' . $dbPrefix . 'user WHERE id = ?', array($userInfo['id']));
    }
} 
 */