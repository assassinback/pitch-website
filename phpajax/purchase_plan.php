<?php include('../config.php');
include('../admin/inc/judopay/vendor/autoload.php');

$response = array();
$redirect = null;
$error = null;
$msg = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    generateLog('purchase_plan', 'Start : ' . date('Y-m-d H:i:s'));
    
    $user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
    $plan_id = isset($_POST['plan_id']) ? $_POST['plan_id'] : 0;
    $plan_type = isset($_POST['plan_type']) ? $_POST['plan_type'] : 'month';
    $validation_session_plan_id = isset($_POST['validation_session_plan_id']) ? $_POST['validation_session_plan_id'] : 0;
    $cancel = isset($_POST['cancel']) ? $_POST['cancel'] : 0;
    
    $user = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($user_id));
    $userInfo = $user->row_array();
    
    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user_test_plan WHERE user_id = ?', array($user_id));
    $userPlanInfo = $result->row_array();
    
    $first_purchase = 0;
    if ($userPlanInfo['first_purchase'] == 0) {
        $allow_discount = true;
    } else {
        $allow_discount = false;
    }
    
    if ($cancel) {
        $plan = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan WHERE status = 1 and (monthly_price = 0.00 OR yearly_price = 0.00)', array());
        $planData = $plan->row_array();
        $plan_id = $planData['id'];
    }
    
    if ($validation_session_plan_id) {
        
        $plan = $db->query('SELECT * FROM ' . $dbPrefix . 'validation_session_plan WHERE status = 1 and id = ?', array($validation_session_plan_id));
        $planInfo = $plan->row_array();
        
        $price = $planInfo['price'];
        $discount = 0;
        
    } else {
        
        $plan = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan WHERE status = 1 and id = ?', array($plan_id));
        $planInfo = $plan->row_array();
        
        $price = $planInfo[$plan_type . 'ly_price'];
        $discount = $planInfo[$plan_type . 'ly_discount'];
    }
    
    if ($user->num_rows() > 0 && $plan->num_rows() > 0) {
        
        $card_receipt_id = null;
        $payment_receipt_id = null;
        $consumer_token = null;
        $card_token = null;
        $reference = null;
        
        if ($price != 0.00) {
        
            $card_number = isset($_POST['card_number']) ? $_POST['card_number'] : '';
            $expiry_month = isset($_POST['expiry_month']) ? $_POST['expiry_month'] : date('y');
            $expiry_year = isset($_POST['expiry_year']) ? $_POST['expiry_year'] : date('m');
            $cvv = isset($_POST['cvv']) ? $_POST['cvv'] : '';
            
            if (!is_numeric($card_number) || strlen($card_number) < 13 || strlen($card_number) > 16) {
                $error['field']['card_number'] = 'Please enter correct card number!';
            }
            
            if (($expiry_year > date('y')) || ($expiry_year == date('y') && $expiry_month > date('m'))) {
            } else {
                $error['field']['expiry_year'] = 'Please select correct expiry year!';
            }
            
            if (!is_numeric($cvv) || strlen($cvv) < 3 || strlen($cvv) > 4) {
                $error['field']['cvv'] = 'Please enter correct cvv!';
            }
        }
        
        if (count($error) == 0) {
            
            $payable_price = $price;
            if ($price != 0.00) {
                
                if ($allow_discount && $discount > 0) {
                    $payable_price = $price - ($price*$discount/100);
                    $first_purchase = 1;
                } else {
                    $payable_price = $price;
                    $first_purchase = 0;
                }
                
                $expiry_month = str_pad($expiry_month, 2, '0', STR_PAD_LEFT);
                $judo_parameters = getJudoAPIToken();
                $judopay = new \Judopay($judo_parameters);
                
                $reference = JUDO_REFERENCE_PREFIX . '-' . $user_id . '-' . date('YmdHis');
                
                $registerCard = $judopay->getModel('RegisterCard');
                $registerCard->setAttributeValues(
                    array(
                        'judoId' => JUDO_ID,
                        'yourConsumerReference' => $reference,
                        'yourPaymentReference' => $reference,
                        'amount' => $payable_price,
                        'currency' => CURRENCY_CODE,
                        'cardNumber' => $card_number,
                        'expiryDate' => $expiry_month . '/' . $expiry_year,
                        'cv2' => $cvv
                    )
                );
                
                try {
                    $response = $registerCard->create();
                    if ($response['result'] === 'Success') {
                        $card_receipt_id = $response['receiptId'];
                        $consumer_token = $response['consumer']['consumerToken'];
                        $card_token = $response['cardDetails']['cardToken'];
                    } else {
                        $error['msg'] = 'There were some problems while processing your request';
                    }
                    generateLog('purchase_plan', 'Register Card :' . print_r($response, true));
                } catch (\Judopay\Exception\ValidationError $e) {
                    $error['msg'] =  $e->getSummary();
                } catch (\Judopay\Exception\ApiException $e) {
                    $error['msg'] =  $e->getSummary();
                } catch (\Exception $e) {
                    $error['msg'] =  $e->getMessage();
                }
                
                if (count($error) == 0) {
                    
                    $tokenPayment = $judopay->getModel('TokenPayment');
                    $tokenPayment->setAttributeValues(
                        array(
                            'judoId' => JUDO_ID,
                            'yourConsumerReference' => $reference,
                            'yourPaymentReference' => $reference,
                            'amount' => $payable_price,
                            'currency' => CURRENCY_CODE,
                            'consumerToken' => $consumer_token,
                            'cardToken' => $card_token
                        )
                    );
                    
                    try {
                        $response = $tokenPayment->create();
                        if ($response['result'] === 'Success') {
                            $payment_receipt_id = $response['receiptId'];
                        } else {
                            $error['msg'] = 'There were some problems while processing your payment';
                        }
                        generateLog('purchase_plan', 'Token Payment :' . print_r($response, true));
                    } catch (\Judopay\Exception\ValidationError $e) {
                        $error['msg'] =  $e->getSummary();
                    } catch (\Judopay\Exception\ApiException $e) {
                        $error['msg'] =  $e->getSummary();
                    } catch (\Exception $e) {
                        $error['msg'] =  $e->getMessage();
                    }
                }
            }
            
            if (count($error) == 0) {
                
                if ($validation_session_plan_id) {
                    
                    $plan_id = 0;
        
                    $plan = $db->query('SELECT * FROM ' . $dbPrefix . 'validation_session_plan WHERE status = 1 and id = ?', array($validation_session_plan_id));
                    $planInfo = $plan->row_array();
                    
                    if ($plan->num_rows() == 0) {
                        redirect(getLink());
                    }
                    
                    $transactionData = array(
                        'user_id' => $userInfo['id'],
                        'validation_session_plan_id' => $planInfo['id'],
                        'payment_reference' => $reference,
                        'receipt_id' => $payment_receipt_id,
                        'amount' => $price,
                        'transaction_date' => date('Y-m-d H:i:s')
                    );
    
                    insertData('user_transaction', $transactionData);
                    
                    $amenities = $db->query('SELECT * FROM ' . $dbPrefix . 'validation_session_plan_amenities as validation_session_plan_amenities INNER JOIN ' . $dbPrefix . 'amenities as amenities ON (amenities.id = validation_session_plan_amenities.amenity_id AND amenities.status = 1) WHERE validation_session_plan_amenities.validation_session_plan_id = ?', array($planInfo['id']));
                    $amenities = $amenities->result_array();
                    
                    $msg = 'Validation session plan purchased successfully.';
                    
                    $emailSubject = 'Validation Session Plan Purchase';
                    $emailMessage = '<p>Hi ' . $userInfo['first_name'] . ' ' . $userInfo['last_name'] . '</p>';
                    $emailMessage .= '<p>Validation session plan purchased successfully. Please find the plan details below:</p>';
                    $emailMessage .= '<p><b>Plan</b> : Validation Session Plan</p>';
                    $emailMessage .= '<p><b>Price</b> : ' . formatPrice($price) . '</p>';
                    $emailMessage .= '<p><b>Receipt Id</b> : ' . $payment_receipt_id . '</p>';
                    
                } else {
                    
                    $plan_type = isset($_POST['plan_type']) ? $_POST['plan_type'] : 'month';
                    $plan = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan WHERE status = 1 and id = ?', array($plan_id));
                    $planInfo = $plan->row_array();
                    
                    if ($plan->num_rows() == 0) {
                        redirect(getLink());
                    }
                    
                    $price = $planInfo[$plan_type . 'ly_price'];
                    
                    $purchase_date = date('Y-m-d H:i:s');
                    
                    if ($price != 0) {
                        $previous_payment_date = $purchase_date;
                        if ($plan_type == 'year') {
                            $next_payment_date = date('Y-m-d H:i:s', strtotime('+1 years'));
                        } else {
                            $next_payment_date = date('Y-m-d H:i:s', strtotime('+1 months'));
                        }
                    } else {
                        $plan_type = null;
                        $previous_payment_date = null;
                        $next_payment_date = null;
                    }
                    
                    $planData = array(
                                        'test_plan_id' => $planInfo['id'],
                                        'type' => $plan_type,
                                        'price' => $price,
                                        'allowed_test' => $planInfo['allowed_test'],
                                        'purchase_date' => $purchase_date,
                                        'previous_payment_date' => $previous_payment_date,
                                        'next_payment_date' => $next_payment_date,
                                        'consumer_reference' => $reference,
                                        'payment_reference' => $reference,
                                        'consumer_token' => $consumer_token,
                                        'card_token' => $card_token,
                                        'card_receipt_id' => $card_receipt_id,
                                        'payment_receipt_id' => $payment_receipt_id,
                                    );
                                    
                    if ($first_purchase == 1) {
                        $planData['first_purchase'] = $userPlanInfo['first_purchase'] + 1;
                    }
                    
                    $where ='user_id =' . $user_id .'';
                    $update = updateData('user_test_plan', $planData, $where);
                    
                    $transactionData = array(
                                        'user_id' => $userInfo['id'],
                                        'test_plan_id' => $planInfo['id'],
                                        'type' => $plan_type,
                                        'receipt_id' => $payment_receipt_id,
                                        'amount' => $payable_price,
                                        'transaction_date' => date('Y-m-d H:i:s')
                                    );
                    
                    insertData('user_transaction', $transactionData);
                    
                    $amenities = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan_amenities as test_plan_amenities INNER JOIN ' . $dbPrefix . 'amenities as amenities ON (amenities.id = test_plan_amenities.amenity_id AND amenities.status = 1) WHERE test_plan_amenities.test_plan_id = ?', array($planInfo['id']));
                    $amenities = $amenities->result_array();
                    
                    $date = date('Y-m-d H:i:s');
                    $planAmenityData = array(
                                        'availability' => 0,
                                        'date_modified' => $date
                                    );
                    $where ='user_id =' . $user_id .' AND test_plan_id > 0';
                    updateData('user_amenities', $planAmenityData, $where);
                    
                    if ($cancel) {
                        
                        $msg = 'Your plan cancelled successfully.';
                        
                    } else {
                        $msg = 'Your plan changed successfully.';
                    
                        $emailSubject = 'Subscription Plan Purchase';
                        $emailMessage = '<p>Hi ' . $userInfo['first_name'] . ' ' . $userInfo['last_name'] . '</p>';
                        $emailMessage .= '<p>Subscription plan purchased successfully. Please find the plan details below:</p>';
                        $emailMessage .= '<p><b>Plan</b> : ' . $planInfo['title'] . ' - ' . $planInfo['sub_title'] . '</p>';
                        if ($price != 0) {
                            $emailMessage .= '<p><b>Type</b> : ' . $plan_type . '</p>';
                        }
                        $emailMessage .= '<p><b>Price</b> : ' . formatPrice($payable_price) . '</p>';
                        if ($price != 0) {
                            $emailMessage .= '<p><b>Receipt ID</b> : ' . $payment_receipt_id . '</p>';
                        }
                    }
                }
                
                $date = date('Y-m-d H:i:s');
                foreach ($amenities as $amenity) {
                    
                    for ($i=0; $i<$amenity['quantity']; $i++) {
                        $planAmenityData = array(
                                        'user_id' => $user_id,
                                        'amenity_id' => $amenity['id'],
                                        'test_plan_id' => $plan_id,
                                        'type' => $amenity['type'],
                                        'date_added' => $date,
                                        'date_modified' => $date
                                    );
                        insertData('user_amenities', $planAmenityData);
                    }
                }
                
                if ($plan_id) {
                    
                    /** Update Ranking **/
                    updatePlayerRanking($user_id, $planInfo['allowed_test']);
                    /** Update Ranking **/
                }
                
                if (!$cancel) {
                    $msgdata = array(
                        'to' => array($userInfo['email']),
                        'subject' => $emailSubject,
                        'message' => $emailMessage
                    );
                    sendMsg($msgdata);
                }
            }
            
            /* $userInfo = $result->row_array();
            $request_date = date('Y-m-d H:i:s');
            
            foreach ($coach as $coach_id) {
                
                $result = $db->query('SELECT * FROM ' . $dbPrefix . 'score_validation WHERE status = 0 AND player_id = ? AND coach_id = ?', array($userInfo['id'], $coach_id));
                
                if ($result->num_rows() == 0) {
                    $validateRequestData = array(
                                    'player_id' => $userInfo['id'],
                                    'coach_id' => $coach_id,
                                    'request_date' => $request_date
                                    );
                    insertData('score_validation', $validateRequestData);
                    
                    $date_sent = date('Y-m-d H:i:s');
                    $notificationData = array(
                        'user_id' => $coach_id,
                        'sender_id' => $userInfo['id'],
                        'type' => 'validation_request',
                        'date_sent' => $date_sent
                    );
                    insertData('notification', $notificationData);
                } else {
                    if (count($coach) == 1) {
                        $error = 'Validate score request already sent to this Coach!';
                    }
                }
            }
            
            $msg = 'Validate score request sent successfully.'; */
            
        } else {
            
        }
        
    } else {
        $redirect = getLink('plan.php');
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