<?php include('config.php');

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND user_type = 1', array());
   
if ($result->num_rows() == 0) {
    redirect(getLink());
}

$users = $result->result_array();

foreach ($users as $user) {
    
    $user_id = $user['id'];
    $userInfo = $user;
    
    echo $user_id . ' == <br>';
    
    $result = $db->query('SELECT test_plan.*, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan LEFT JOIN  ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_test_plan.user_id = ?', array($user_id));
    $userPlanInfo = $result->row_array();
    
    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user_test_question_score WHERE user_id = ?', array($user_id));
    $rows = $result->result_array();
    
    $test_questions = array();
    foreach ($rows as $row) {
        $test_questions[$row['test_id']][$row['question_id']] = $row['score'];
    }
    
    foreach ($test_questions as $test_id => $test_question) {
        
        $test = $db->query('SELECT * FROM ' . $dbPrefix . 'test WHERE status = 1 AND id IN (' . $userPlanInfo['allowed_test'] . ') AND id = ?', array($test_id));

        if ($result->num_rows() == 0) {
            redirect(getLink());
        }

        $testInfo = $test->row_array();

        $questionGroups = $db->query('SELECT * FROM ' . $dbPrefix . 'test_question_group WHERE test_id = ? ORDER BY sort_order ASC', array($testInfo['id']));

        $testquestionGroups = $questionGroups->result_array();

        $question = $db->query('SELECT * FROM ' . $dbPrefix . 'test_questions WHERE status = 1 AND test_id = ?', array($testInfo['id']));

        $testQuestions = $question->result_array();

        $questionList = array();
        foreach ($testQuestions as $testQuestion) {
            $questionList[$testQuestion['id']] = $testQuestion;
        }
        
        //$starttime = microtime(true);
        
        $question = $test_question;
        
        $db->query('DELETE FROM ' . $dbPrefix . 'user_test_question_score WHERE user_id = ? AND test_id = ?', array($user_id, $test_id));
        
        $total_score = 0;
        foreach ($question as $question_id => $score) {
            
            if ($questionList[$question_id]['question_parameter'] != null) {
                ${$questionList[$question_id]['question_parameter']} = $score;
            } else if (isset($questionList[$question_id]['reverse_value']) && $questionList[$question_id]['reverse_value'] == 1) {
                $reverse_value = (($questionList[$question_id]['min_value'] + $questionList[$question_id]['max_value']) - $score);
                if ($questionList[$question_id]['ignore_value'] == 0) {
                    $total_score += $reverse_value;
                }
            } else {
                if ($questionList[$question_id]['ignore_value'] == 0) {
                    $total_score += $score;
                }
            }
            
            $questionScoreData = array(
                        'user_id' => $user_id,
                        'test_id' => $test_id,
                        'question_id' => $question_id,
                        'score' => $score
                    );
            insertData('user_test_question_score', $questionScoreData);
        }
        
        if ($testInfo['id'] == 8 || $testInfo['id'] == 9) {
            $body_weight = $userInfo['weight'];
            $one_rep_max_result = $weight_lifted * (1 + (0.033 * $repetitions_lifted ));
            $total_score = ($one_rep_max_result / $body_weight) * 100;
        }
        
        if ($testInfo['id'] == 10) {
            $body_weight = $userInfo['weight'];
            $distance = 35;
            $velocity = array();
            $acceleration = array();
            $force = array();
            $power = array();
            
            $total_time = 0;
            for ($i=1; $i<=count($question); $i++) {
                $total_time += ${'sprint_' . $i};
                $velocity[$i] = $distance / ${'sprint_' . $i};
                $acceleration[$i] = $velocity[$i] / ${'sprint_' . $i};
                $force[$i] = $acceleration[$i] * $body_weight;
                $power[$i] = $velocity[$i] * $force[$i];
            }
            
            $max_power = max($power);
            $min_power = min($power);
            $average_power = array_sum($power) / count($power);
            $fatigue_index = ($max_power - $min_power) / $total_time;
            $total_score = $fatigue_index;
        }
        
        $weightage = 0;
        $label = null;
        $require_validation = 0;
        
        if ($total_score >= (float)$testInfo['min_score'] && $total_score <= (float)$testInfo['max_score']) {
            
            /* $sql = 'SELECT * FROM ' . $dbPrefix . 'test_score_weightage WHERE test_id = ? AND min_score <= ? AND max_score >= ?';
            $params = array($test_id, $total_score, $total_score); */
            
            $sql = 'SELECT * FROM ' . $dbPrefix . 'test_score_weightage WHERE test_id = ? AND min_score <= ? ORDER BY min_score DESC LIMIT 1';
            $params = array($test_id, $total_score);
            
        } else if ($total_score < (float)$testInfo['min_score']) {
            
            $sql = 'SELECT * FROM ' . $dbPrefix . 'test_score_weightage WHERE test_id = ? AND min_score > ? ORDER BY min_score ASC LIMIT 1';
            $params = array($test_id, $total_score);
            
        } else {
            
            $sql = 'SELECT * FROM ' . $dbPrefix . 'test_score_weightage WHERE test_id = ? AND max_score < ? ORDER BY max_score DESC LIMIT 1';
            $params = array($test_id, $total_score);
            
        }
        
        $score_weightage = $db->query($sql, $params);
        
        if ($score_weightage->num_rows() > 0) {
            $score_weightage = $score_weightage->row_array();
            $weightage = $score_weightage['weightage'];
            
            $weightage_status = $db->query('SELECT * FROM ' . $dbPrefix . 'test_score_weightage_status WHERE min_weightage <= ? AND max_weightage >= ?', array($weightage, $weightage));
            $weightage_status = $weightage_status->row_array();
            
            $label = $weightage_status['label'];
            $require_validation = $weightage_status['require_validation'];
        } else {
            $weightage = 0;
            $label = null;
            $require_validation = 0;
        }
        
        $userScoreData = array(
                        'user_id' => $user_id,
                        'test_id' => $test_id,
                        'total_score' => $total_score,
                        'weightage' => $weightage,
                        'label' => $label,
                        'require_validation' => $require_validation,
                        'validated' => 0
                    );
                    
        $user_test_score = $db->query('SELECT * FROM ' . $dbPrefix . 'user_test_score WHERE user_id = ? AND test_id = ?', array($user_id, $test_id));
        
        if ($user_test_score->num_rows() > 0) {
            $user_test_score = $user_test_score->row_array();
            if ($user_test_score['total_score'] != $userScoreData['total_score'] || $user_test_score['weightage'] != $userScoreData['weightage']) {
                updateData('user_test_score', $userScoreData, 'id=' . $user_test_score['id']);
            }
        } else {
            insertData('user_test_score', $userScoreData);
        }
        
        /** Update Ranking **/
        $total_test = $db->query('SELECT COUNT(test.id) as total FROM ' . $dbPrefix . 'test as test WHERE test.status = 1 AND test.id IN (' . $userPlanInfo['allowed_test'] . ')', array());
        $totalTest = $total_test->row_array();

        $user_total_score = $db->query('SELECT SUM(test_score.weightage) as score FROM ' . $dbPrefix . 'user_test_score as test_score INNER JOIN ' . $dbPrefix . 'test as test ON (test.id = test_score.test_id AND test.status = 1) WHERE test_score.user_id = ? AND test.id IN (' . $userPlanInfo['allowed_test'] . ')', array($user_id));
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
        
        /* $endtime = microtime(true);
        $duration = $endtime - $starttime;
        echo $duration; */
        
    }
    
}
