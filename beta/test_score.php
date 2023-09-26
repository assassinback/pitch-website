<?php include('config.php');

if (isset($_GET['test_id'])) {
    $test_id = $_GET['test_id'];
} else {
    $test_id = 0;
}

if (checkLogin()) {
    
    $user_id = $_SESSION['id'];

    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND user_type = 1 AND id = ?', array($user_id));
       
    if ($result->num_rows() == 0) {
        redirect(getLink());
    }

    $userInfo = $result->row_array();

    $result = $db->query('SELECT test_plan.*, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan LEFT JOIN  ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_test_plan.user_id = ?', array($user_id));
    $userPlanInfo = $result->row_array();

    $test = $db->query('SELECT * FROM ' . $dbPrefix . 'test WHERE status = 1 AND id IN (' . $userPlanInfo['allowed_test'] . ') AND id = ?', array($test_id));

} else {
    
    $test = $db->query('SELECT * FROM ' . $dbPrefix . 'test WHERE status = 1 AND id = ?', array($test_id));
}


if ($result->num_rows() == 0) {
    redirect(getLink());
}

$testInfo = $test->row_array();
if ($test->num_rows() == 0) {
    redirect(getLink("test.php"));
}

$questionGroups = $db->query('SELECT * FROM ' . $dbPrefix . 'test_question_group WHERE test_id = ? ORDER BY sort_order ASC', array($testInfo['id']));

$testquestionGroups = $questionGroups->result_array();

$question = $db->query('SELECT * FROM ' . $dbPrefix . 'test_questions WHERE status = 1 AND test_id = ?', array($testInfo['id']));

$testQuestions = $question->result_array();

$questionList = array();
foreach ($testQuestions as $testQuestion) {
    $questionList[$testQuestion['id']] = $testQuestion;
}

if (checkLogin()) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        //$starttime = microtime(true);
        
        $question = $_POST['question'];
        
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
        
        redirect(getLink('profile.php', 'tab=score'));
        
        /* $endtime = microtime(true);
        $duration = $endtime - $starttime;
        echo $duration; */
    } else {
        
        $question = array();
        $user_question_score = $db->query('SELECT * FROM ' . $dbPrefix . 'user_test_question_score WHERE user_id = ? AND test_id = ?', array($user_id, $test_id));
        if ($user_question_score->num_rows() > 0) {
            foreach ($user_question_score->result_array() as $question_score) {
                $question[$question_score['question_id']] = $question_score['score'];
            }
        }
    }
}

if ($testInfo['layout'] == 'one-column') {
    $document['style'][] = 'jquery-ui.css';
    $document['script'][] = 'jquery-ui.js';
}

$document['script'][] = 'test.js';

$page_title = $testInfo['title'];
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('test_score.php', 'test_id=' . $testInfo['id'])));
include('common/header.php');
?>

<div class="p_profile_bnr p_profile_bnr_hdr">
	<div class="container">
		<div class="row">
			<h1><?php echo $page_title; ?></h1>
		</div>
	</div>
</div>


			
<?php if ($testInfo['layout'] == 'one-column') { ?>
    
    
    <div class="p_profile_dtl p_psych_test">
        <div class="container">
            <div class="row">
                
                <?php if (checkLogin()) { ?>
                <form action="" method="POST" name="testScoreForm" id="testScoreForm" >
                <?php } ?>
                
                    <?php foreach ($testquestionGroups as $testquestionGroup) { ?>
                    
                        <div class="col-xs-12 ply_abt">
                    
                            <div class="ply_ip">
                                <div class="ply_ip_inn">
                                    <h3><?php echo $testquestionGroup['title']; ?></h3>
                                    <hr>
                                    <div class="psych_test_con">
                                        <div class="psych_test_con_lft">
                                            <p><?php echo $testquestionGroup['description']; ?></p>
                                        </div>
                                        <?php if ($testquestionGroup['summary']) { ?>
                                        <?php $summary = unserialize($testquestionGroup['summary']); ?>
                                        <div class="psych_test_con_rgt">
                                            <div class="psych_measure">
                                                <?php foreach ($summary as $summaryInfo) { ?>
                                                    <span class="ms_<?php echo $summaryInfo['key']; ?> <?php echo ($summaryInfo['value']) ? 'active' : 'disable'; ?>"><?php echo $summaryInfo['value']; ?></span>
                                                <?php } ?>
                                                <hr>
                                                <?php foreach ($summary as $summaryInfo) { ?>
                                                    <label><?php echo $summaryInfo['label']; ?></label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    
                        <div class="col-xs-12 p_rate_test">
                            
                            <div class="rate_test_wrap psychology-test">
                        
                                <?php foreach ($testQuestions as $key => $testQuestion) { ?>
                                    <?php if ($testQuestion['test_question_group_id'] != $testquestionGroup['id']) {
                                        continue;
                                    } ?>
                                    <div class="rate_test_inn" id="question-<?php echo $testQuestion['id']; ?>">
                                        <h2><?php echo $testQuestion['title']; ?></h2>
                                        
                                        <div class="col-xs-12">
                                            
                                            
                                            <?php /* <div class="slider" data-min="<?php echo $testQuestion['min_value']; ?>" data-max="<?php echo $testQuestion['max_value']; ?>" data-default="<?php echo (isset($question[$testQuestion['id']])) ? $question[$testQuestion['id']] : $testQuestion['min_value']; ?>" >
                                                <div id="custom-handle" class="custom-handle ui-slider-handle"></div>
                                                <input type="hidden" name="question[<?php echo $testQuestion['id']; ?>]" value="">
                                            </div> */ ?>
                                            
                                            <?php if (checkLogin()) { ?>
                                            <div class="psych_measure psychology-que">
                                                <?php for ($i=1; $i<=10; $i++) { ?>
                                                    <span class="ms_<?php echo $i?> <?php echo ((isset($question[$testQuestion['id']])) && ($i == $question[$testQuestion['id']])) ? 'active' : ''; ?>" data-value="<?php echo $i; ?>" ><?php echo $i?></span>
                                                <?php } ?>
                                                <hr>
                                                <input type="hidden" name="question[<?php echo $testQuestion['id']; ?>]" value="<?php echo (isset($question[$testQuestion['id']])) ? $question[$testQuestion['id']] : ''; ?>" data-question="<?php echo $testQuestion['id']; ?>" >
                                            </div>
                                            <?php } else { ?>
                                            <div class="psych_measure psychology-que">
                                                <?php for ($i=1; $i<=10; $i++) { ?>
                                                    <span class="ms_<?php echo $i?>" ><?php echo $i?></span>
                                                <?php } ?>
                                                <hr>
                                            </div>
                                            <?php } ?>
                                            
                                            <br>
                                        </div>
                                    </div>
                                <?php } ?>
                                
                            </div>
                            
                        </div>
                    <?php } ?>
                    
                    <?php if (checkLogin()) { ?>
                    <div class="col-xs-12 text-center" >
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
                <?php } ?>
                
            </div>
        </div>
    </div>
                
<?php } else { ?>

    <div class="p_profile_dtl p_aero_test_dtl">
        <div class="container">
            <div class="row">
            
                <div class="col-xs-12 ply_rv ply_tav">
                    
                    <div class="ply_rv_dv">
                        <div class="ply_rv_dv_lft">
                            <?php echo $testInfo['video']; ?>
                        </div>
                        <div class="ply_rv_dv_rgt test-description">
                            <?php echo $testInfo['description']; ?>
                        </div>
                    </div>
                
                    <div class="ply_tav_rgt">
                        <div class="ply_tav_rgt_inn">
                            <?php if (checkLogin()) { ?>
                            <form action="" method="POST" name="testScoreForm" id="testScoreForm" >
                            
                                <?php foreach ($testQuestions as $testQuestion) { ?>
                                    <h2><?php echo $testQuestion['title']; ?></span></h2>
                                    <hr>
                                    <div class="aero_form">
                                        <input type="text" name="question[<?php echo $testQuestion['id']; ?>]" class="txt_aero" id="question-<?php echo $testQuestion['id']; ?>" value="<?php echo (isset($question[$testQuestion['id']])) ? $question[$testQuestion['id']] : ''; ?>" data-type="<?php echo $testQuestion['data_type']; ?>">
                                        <label><?php echo $testQuestion['unit']; ?></label>
                                    </div>
                                <?php } ?>
                                
                                <div class="aero_form">
                                    <button type="submit" class="btn_aero">Submit</button>
                                </div>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>
			
		
<?php include('common/footer.php');?>