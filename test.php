<?php include('config.php');

if(isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
} else {
    $user_id = 0;
}

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($user_id));

if ($result->num_rows() == 0) {
    redirect(getLink('login.php'));
}

$userInfo = $result->row_array();

if ($userInfo['user_type'] != 1) {
    redirect(getLink('permission.php'));
}

/* $test_page = 'test_detail.php';
if (checkLogin()) {
    
    $user_id = $_SESSION['id'];

    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($user_id));
       
    if ($result->num_rows() > 0) {
        $userInfo = $result->row_array();
        if ($userInfo['user_type'] == 1) {
            $test_page = 'test_score.php';
        }
    }
} */

/* $tests = $db->query('SELECT * FROM ' . $dbPrefix . 'test WHERE status = 1 ORDER BY sort_order ASC', array());
$tests = $tests->result_array(); */

$test_page = 'test_score.php';

$result = $db->query('SELECT test_plan.*, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan LEFT JOIN  ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_test_plan.user_id = ?', array($user_id));
$userPlanInfo = $result->row_array();

$allowed_test = explode(",", $userPlanInfo['allowed_test']);

$tests = $db->query('SELECT user_score.*, test.* FROM ' . $dbPrefix . 'test as test LEFT JOIN  ' . $dbPrefix . 'user_test_score as user_score ON (user_score.test_id = test.id AND user_score.user_id = ?) WHERE test.status = 1 ORDER BY test.sort_order ASC', array($user_id));
$tests = $tests->result_array();

if ($userPlanInfo['test_plan_id'] == 1) {
    $test_message = 'Please subscribe to either Silver or Gold packages to access these tests';
} else if ($userPlanInfo['test_plan_id'] == 2) {
    $test_message = 'Please subscribe to Gold packages to access these tests';
} else {
    $test_message = '';
}

$page_title = 'Test';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('test.php')));
include('common/header.php');
?>

<div class="p_profile_bnr p_profile_bnr_hdr">
    <div class="container">
        <div class="row">
            <h1><?php echo $page_title; ?></h1>
        </div>
    </div>
</div>
<div class="p_view_test">
    <div class="container">
        <div class="row">
            <?php foreach ($tests as $key => $test) { ?>
                <?php if (in_array($test['id'], $allowed_test)) { ?> 
                    <div class="col-xs-6 col-sm-4 col-md-4">
                        <a href="<?php echo getLink($test_page, 'test_id=' . $test['id']); ?>" >
                            <div class="p_test_cell">
                                <img src="images/<?php echo $test['image']; ?>" alt=""/>
                                <h3><?php echo $test['title']; ?></h3>
                                <h3 class="<?php echo strtolower($test['test_type']); ?>"><?php echo $test['test_type']; ?></h3>
                            </div>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="col-xs-6 col-sm-4 col-md-4 disable-test">
                        <div class="p_test_cell">
                            <img src="images/<?php echo $test['image']; ?>" alt=""/>
                            <h3><?php echo $test['title']; ?></h3>
                            <h3 class="<?php echo strtolower($test['test_type']); ?>"><?php echo $test['test_type']; ?></h3>
                            <p class="tooltip"><?php echo $test_message; ?></p>
                        </div>
                    </div>

                <?php } ?>
                
                <?php if ($key%3 == 2) { ?>
                    <div class="clearfix visible-md visible-lg" ></div>
                <?php } ?>
                
                <?php if ($key%2 == 1) { ?>
                    <div class="clearfix visible-sm" ></div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>

<?php include('common/footer.php');?>