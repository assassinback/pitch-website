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
        $type = $_POST['type'];
        $validate_by = $_POST['validate_by'];
        
        $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($id));
        
        if ($result->num_rows() > 0) {
            
            $result = $db->query('SELECT test_plan.*, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan LEFT JOIN  ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_test_plan.user_id = ?', array($id));
            $userPlanInfo = $result->row_array();
            
            $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user_amenities WHERE id = ?', array($user_amenity_id));
            
            if ($result->num_rows() > 0) {
            
                $amenityInfo = $result->row_array();
                if($amenityInfo['data'] != "") {
                    $amenityData = unserialize($amenityInfo['data']);
                } else {
                    $amenityData = array();
                }
                
                if ($type == 'validate_score') {
                    
                    $userTestData = array(
                                'require_validation' => 0,
                                'validated' => 1
                                );
                    updateData('user_test_score', $userTestData, 'user_id=' . $id);
                    
                    $score_validated_date = date('Y-m-d H:i:s');
                    $userData = array(
                                            'score_validated_by' => 0,
                                            'score_validated_date' => $score_validated_date
                                            );
                    updateData('user', $userData, 'id=' . $id);
                    
                    $amenityData['validated_date'] = date('Y-m-d H:i:s');
                    $planAmenityData = array(
                                'status' => 1,
                                'data' => serialize($amenityData),
                                );
                    updateData('user_amenities', $planAmenityData, 'id=' . $user_amenity_id);
                    
                    $date_sent = date('Y-m-d H:i:s');
                    $notificationData = array(
                        'user_id' => $id,
                        'type' => 'score_validated',
                        'date_sent' => $date_sent
                    );
                    insertData('notification', $notificationData);
                    
                    $_SESSION['msgType'] = 'success';
                    $_SESSION['msgString'] = 'Score validated successfully.';
                    $redirect = getAdminLink('userinfo', 'id=' . $id . '&tab=score');
                    
                } else {
                    
                    $typeList = array(
                                    'training_plan_6_week' => array('name' => 'Training-Plan-6-Week', 'msg' => '6 Week Training plan sent successfully.'),
                                    'training_plan_12_week' => array('name' => 'Training-Plan-12-Week', 'msg' => '12 Week Training plan sent successfully.'),
                                    'sport_science_validation' => array('name' => 'Sport-Science-Validation', 'msg' => 'Sport Science Validation receipt sent successfully.'),
                                    'potential_trial' => array('name' => 'Potential Trial', 'msg' => 'Potential Trial receipt sent successfully.'),
                                    'trial_session' => array('name' => 'Trial-Session', 'msg' => 'Trial Session receipt sent successfully.'),
                                );
                    
                    if (isset($typeList[$type])) {
                        
                        $typeInfo = $typeList[$type];
                        
                        $file = $_FILES['file_0'];
                        if ($file['name'] == "") {
                            $error = "Please upload file";
                        } else {
                            
                            $source = $file['tmp_name'];
                            $file_name = $typeInfo['name'] . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
                            $file_upload = uploadDocument(array('source' => $source, 'destination' => USER_PATH, 'file_name' => $file_name, 'original_name' => $file['name']));
                            
                            if(isset($file_upload['success'])) {
                                $receipt = $file_upload['file'];
                                
                                if (isset($amenityData['file']) && file_exists(USER_PATH . $amenityData['file'])) {
                                    unlink(USER_PATH . $amenityData['file']);
                                }
                                
                                $amenityData['file'] = $receipt;
                                $amenityData['file_sent'] = date('Y-m-d H:i:s');
                                $planAmenityData = array(
                                            'data' => serialize($amenityData)
                                            );
                                            
                                if ($type == "training_plan_6_week" || $type == "training_plan_12_week") {
                                    $planAmenityData['status'] = 1;
                                }
                                updateData('user_amenities', $planAmenityData, 'id=' . $user_amenity_id);
                                
                                $_SESSION['msgType'] = 'success';
                                $_SESSION['msgString'] = $typeInfo['msg'];
                                $redirect = getAdminLink('userinfo', 'id=' . $id . '&tab=plan');
                                
                            } else if(isset($file_upload['error'])) {
                                $error = $file_upload['error'];
                            } else {
                                $error = 'Error occured. Please try again!';
                            }
                            
                        }
                    
                    } else {
                        $redirect = getAdminLink('user');
                    }
                
                }
                
            } else if($user_amenity_id == '' && $type == 'validate_score_by_admin') {
                
                $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user_amenities WHERE user_id = ? AND type = ? AND status = ? AND availability = ? ', array($id, 'sport_science_validation', 0, 1));
                
                if ($result->num_rows() > 0) {
                    $sportScienceValidationInfo = $result->row_array();
                } else {
                    $sportScienceValidationInfo = array();
                }
                
				if($validate_by == 'Green') {
				    $userTestData = array(
                                'require_validation' => 0,
                                'validated' => 1
                                );
                    updateData('user_test_score', $userTestData, 'user_id=' . $id);
                    
                    $score_validated_date = date('Y-m-d H:i:s');
                    $userData = array(
                                    'score_validated_by' => 0,
                                    'score_validated_date' => $score_validated_date
                                );
                    updateData('user', $userData, 'id=' . $id);
                    
                    if (count($sportScienceValidationInfo) > 0) {
                        $amenityData['validated_date'] = date('Y-m-d H:i:s');
                        $planAmenityData = array(
                                    'status' => 1,
                                    'data' => serialize($amenityData),
                                    );
                        updateData('user_amenities', $planAmenityData, 'id=' . $sportScienceValidationInfo['id']);
                    }
				} elseif($validate_by == 'Red'){
					$userTestData = array(
                                'require_validation' => 1,
                                'validated' => 0
                                );
                    updateData('user_test_score', $userTestData, 'user_id=' . $id);
                    
                    $score_validated_date = date('Y-m-d H:i:s');
                    $userData = array(
                                    'score_validated_by' => null,
                                    'score_validated_date' => $score_validated_date
                                );
                    updateData('user', $userData, 'id=' . $id);
				}  else {
					$userTestData = array(
                                'require_validation' => 0,
                                'validated' => 0
                                );
                    updateData('user_test_score', $userTestData, 'user_id=' . $id);
					$score_validated_date = date('Y-m-d H:i:s');
                    $userData = array(
                                    'score_validated_by' => 1,
                                    'score_validated_date' => $score_validated_date
                                );
                    updateData('user', $userData, 'id=' . $id);
				}
                
                $date_sent = date('Y-m-d H:i:s');
                $notificationData = array(
                    'user_id' => $id,
                    'type' => 'score_validated',
                    'date_sent' => $date_sent
                );
                insertData('notification', $notificationData);
                    
				$_SESSION['msgType'] = 'success';
                $_SESSION['msgString'] = 'Score validated successfully.';
                $redirect = getAdminLink('userinfo', 'id=' . $id . '&tab=score');
			} else {
                $redirect = getAdminLink('user');
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