<?php include('../config.php');
include('../admin/inc/judopay/vendor/autoload.php');

$response = array();
$redirect = null;
$error = null;
$msg = null;

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
$user_type = $_SESSION['user_type'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$photo = $_SESSION['photo'];
$email = $_SESSION['email'];
$password = $_SESSION['password'];
//$team_id = $_SESSION['team_id'];
$prefered_foot = $_SESSION['prefered_foot'];
$country = $_SESSION['country'];
$county = $_SESSION['county'];
$height = $_SESSION['height'];
$weight = $_SESSION['weight'];
$guardian_email_addresses = $_SESSION['guardian_email_addresses'];
$date_of_birth = $_SESSION['date_of_birth'];
$date_added = $_SESSION['date_added'];

    
    generateLog('purchase_plan', 'Start : ' . date('Y-m-d H:i:s'));
    
    $plan_id = isset($_POST['plan_id']) ? $_POST['plan_id'] : 0;
    $plan_type = isset($_POST['plan_type']) ? $_POST['plan_type'] : 'month';
        
    $plan = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan WHERE status = 1 and id = ?', array($plan_id));
    $planInfo = $plan->row_array();
    
    $allow_discount = true;
    $first_purchase = 0;
    $price = $planInfo[$plan_type . 'ly_price'];
    $discount = $planInfo[$plan_type . 'ly_discount'];
        
    if ($plan->num_rows() > 0) {
        
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
                
                $reference = JUDO_REFERENCE_PREFIX . '-' . date('YmdHis');
                
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
                
                    /* Registration on successfull payment starts here. */
                
                    $userData = array(
                        'user_type' => $user_type,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'photo' => $photo,
                        'email' => $email,
                        'password' => $password,
                        'country_id' => $country,
                        'county_id' => $county,
                        'date_of_birth' => $date_of_birth,
                        'prefered_foot' => $prefered_foot,
                        'height' => $height,
                        'weight' => $weight,
                        'guardian_email_addresses' => $guardian_email_addresses,
                        'date_added' => $date_added
                    );
        
                    $user_id = insertData('user', $userData);
                    
                    /* Registration on successfull payment done ends here. */
                    
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
					
                    /* User test plan database entry starts here. */
                    if ($user_id){
                        if ($user_type == 1) {
                            $planData = array(
                                'user_id' => $user_id,
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
                                $planData['first_purchase'] = 1;
                            }
                                
                            insertData('user_test_plan', $planData);
                        }
                    }
                    
                    /* User test plan database entry ends here. */
                    
                    
                    /* User transaction database entry starts here. */
                    $transactionData = array(
                                        'user_id' => $user_id,
                                        'test_plan_id' => $planInfo['id'],
                                        'type' => $plan_type,
                                        'payment_reference' => $reference,
                                        'receipt_id' => $payment_receipt_id,
                                        'amount' => $payable_price,
                                        'transaction_date' => date('Y-m-d H:i:s')
                                    );
                    
                    insertData('user_transaction', $transactionData);
                    
                    /* User transaction database entry ends here. */
                    
                    $amenities = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan_amenities as test_plan_amenities INNER JOIN ' . $dbPrefix . 'amenities as amenities ON (amenities.id = test_plan_amenities.amenity_id AND amenities.status = 1) WHERE test_plan_amenities.test_plan_id = ?', array($planInfo['id']));
                    $amenities = $amenities->result_array();
                    
                    //$msg = 'Subscription plan purchased successfully.';
                    
                    
                    /* Email for registration starts here */
                    $message = '<p>Hi ' . $first_name . ' ' . $last_name. '</p>';
                    $message .= '<p>You have been registered successfully.</p>';
                    $msg_data = array(
                        'to' => array($email),
                        'subject' => "Account register",
                        'message' => $message
                    );
                    sendMsg($msg_data);
                    
                    /* Email for registration ends here */
                    
                    
                    $emailSubject = 'Subscription Plan Purchase';
                    $emailMessage = '<p>Hi ' . $first_name . ' ' . $last_name . '</p>';
                    $emailMessage .= '<p>Subscription plan purchased successfully. Please find the plan details below:</p>';
                    $emailMessage .= '<p><b>Plan</b> : ' . $planInfo['title'] . ' - ' . $planInfo['sub_title'] . '</p>';
					if ($price != 0) {
						$emailMessage .= '<p><b>Type</b> : ' . $plan_type . '</p>';
					}
                    $emailMessage .= '<p><b>Price</b> : ' . formatPrice($payable_price) . '</p>';
					if ($price != 0) {
						$emailMessage .= '<p><b>Receipt ID</b> : ' . $payment_receipt_id . '</p>';
					}
                    
                
                /* User amenities database entry starts here. */
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
                
                /* User amenities database entry ends here. */
                
                
                if ($plan_id) {
                    
                    /** Update Ranking **/
                    $db->query('DELETE FROM ' . $dbPrefix . 'user_test_score WHERE user_id = ? AND test_id NOT IN (' . $planInfo['allowed_test'] . ')', array($user_id));
                    
                    $total_test = $db->query('SELECT COUNT(test.id) as total FROM ' . $dbPrefix . 'test as test WHERE test.status = 1 AND test.id IN (' . $planInfo['allowed_test'] . ')', array());
                    $totalTest = $total_test->row_array();

                    $user_total_score = $db->query('SELECT SUM(test_score.weightage) as score FROM ' . $dbPrefix . 'user_test_score as test_score INNER JOIN ' . $dbPrefix . 'test as test ON (test.id = test_score.test_id AND test.status = 1) WHERE test_score.user_id = ? AND test.id IN (' . $planInfo['allowed_test'] . ')', array($user_id));
                    $userTotalScore = $user_total_score->row_array();

                    $overall_score = number_format((float)($userTotalScore['score']/$totalTest['total']), 2, '.', '');

                    $userData = array('overall_score' => $overall_score);
                    updateData('user', $userData, 'id=' . $user_id);
                    
                    $users = $db->query('SELECT id, overall_score FROM ' . $dbPrefix . 'user WHERE status = 1 AND overall_score > 0 ORDER BY overall_score DESC', array($user_id));
                    $users = $users->result_array();
                    
                    $userData = array('user_rank' => null);
                    updateData('user', $userData);
                    
                    $previous_user_ranking = 0;
                    $previous_user_rank = 0;
                    foreach ($users as $key => $user) {
                        
                        if ($user['overall_score'] == $previous_user_ranking) {
                            $user_rank = $previous_user_rank;
                        } else {
                            $user_rank = ($key + 1);
                        }
                        $previous_user_rank = $user_rank;
                        $previous_user_ranking = $user['overall_score'];
                        
                        $userData = array('user_rank' => $user_rank);
                        updateData('user', $userData, 'id=' . $user['id']);
                    }
                    
                    /** Update Ranking **/
                }
                
                $msgdata = array(
                    'to' => array($email),
                    'subject' => $emailSubject,
                    'message' => $emailMessage
                );
                sendMsg($msgdata);
                
                generateLog('payment', 'success');
                generateLog('payment', "\n");
                
                
                unset($_SESSION['user_type']);
                unset($_SESSION['first_name']);
                unset($_SESSION['last_name']);
                unset($_SESSION['photo']);
                unset($_SESSION['email']);
                unset($_SESSION['password']);
                //unset($_SESSION['team_id']);
                unset($_SESSION['prefered_foot']);
                unset($_SESSION['country']);
                unset($_SESSION['county']);
                unset($_SESSION['height']);
                unset($_SESSION['weight']);
                unset($_SESSION['guardian_email_addresses']);
                unset($_SESSION['date_of_birth']);
                unset($_SESSION['date_added']);
                
                $redirect = getLink('thank_you.php');                
                
            } else {
                generateLog('payment', 'Error : ' . print_r($error, true));
                generateLog('payment', "\n");
            }
            
                        
        } else {
            
        }
        
    } else {
        $redirect = getLink('plan_new.php');
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