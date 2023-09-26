<?php include('config.php');

if (isset($_GET['test_id'])) {
    $test_id = $_GET['test_id'];
} else {
    $test_id = 0;
}

$test = $db->query('SELECT * FROM ' . $dbPrefix . 'test WHERE status = 1 AND id = ?', array($test_id));

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
                                            
                                            <div class="psych_measure psychology-que">
                                                <?php for ($i=1; $i<=10; $i++) { ?>
                                                    <span class="ms_<?php echo $i?>" ><?php echo $i?></span>
                                                <?php } ?>
                                                <hr>
                                            </div>
                                            <br>
                                        </div>
                                    </div>
                                <?php } ?>
                                
                            </div>
                            
                        </div>
                    <?php } ?>
                    
            </div>
        </div>
    </div>
                
<?php } else { ?>

    <div class="p_profile_dtl p_aero_test_dtl">
        <div class="container">
            <div class="row">
            
                <div class="col-xs-12 ply_rv ply_tav">
                    
                    <div class="ply_rv_dv full_display">
                        <div class="ply_rv_dv_lft">
                            <?php echo $testInfo['video']; ?>
                        </div>
                        <div class="ply_rv_dv_rgt test-description">
                            <?php echo $testInfo['description']; ?>
                        </div>
                    </div>
                
                    <div class="ply_tav_rgt">
                        <div class="ply_tav_rgt_inn">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>
			
		
<?php include('common/footer.php');?>