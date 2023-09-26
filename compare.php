<?php include('config.php');

if(isset($_SESSION['id'])) {
    $profile_id = $_SESSION['id'];
} else {
    $profile_id = 0;
}

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($profile_id));
if ($result->num_rows() == 0) {
    redirect(getLink('login.php'));
}

$userInfo = $result->row_array();
$user_type = $userInfo['user_type'];

if($user_type == 1){
	redirect(getLink('permission.php'));
}

if (isset($_COOKIE['compare'])) {
    $compare = str_replace('-', ',', $_COOKIE['compare']);
    $sql = 'SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND user_type = 1 AND id in (' . $compare . ')';
    $result = $db->query($sql, array());
    
    $players = array();
    if ($result->num_rows() > 0) {
        $players = $result->result_array();
    }
} else {
    $players = array();
}
$document['script'][] = 'progressbar.js';

$page_title = 'Compare';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('compare.php')));
include('common/header.php');
?>

<div class="p_profile_bnr p_profile_bnr_hdr">
    <div class="container">
        <div class="row">
            <h1><?php echo $page_title; ?></h1>
        </div>
    </div>
</div>
<div class="p_profile_dtl p_aero_test_dtl p_cms_dtl compare-page">
    <div class="container">
        <div class="row">
            <br>
            <div class="col-sm-12 col-md-offset-1 col-md-10 col-lg-offset-2 col-lg-8">
                <?php if (count($players) > 0) { ?>
                    <?php foreach ($players as $player) { ?>
                        <div class="col-xs-12 col-sm-6 compare-result" id="compare-<?php echo $player['id']; ?>">
                            <div class="clearfix text-center">
                                <a href="javascript:" data-player="<?php echo $player['id']; ?>" class="remove-compare"><button class="btn btn-danger">Remove</button></a>
                            </div>
                            <br>
                            <div class="profileimg"><a href="<?php echo getLink('profile.php', 'profile_id='.$player['id']); ?>"><?php echo getUserProfileImage($player['photo']); ?></a>
							</div>
                            <h3><?php echo $player['first_name']; ?> <?php echo $player['last_name']; ?></h3>
                            <?php if(isset($player['team_id'])) { ?>
                                <h4><?php echo getClub($player['team_id']); ?></h4>
                            <?php } ?>
                            <table class="table borderless">
                                <tbody>
                                    <tr class="group-title"><th colspan="2">General Information</th></tr>
                                    <tr><td>Rank : </td><td><strong> <?php echo $player['user_rank']; ?></strong></td></tr>
                                    <tr><td>Overall Score : </td><td><strong> <?php echo $player['overall_score']; ?></strong></td></tr>
                                    <tr><td>Rating : </td><td><strong> <?php echo $player['user_rating']; ?></strong></td></tr>
                                    
                                    <tr class="group-title"><th colspan="2">Personal Information</th></tr>
                                    <tr><td>Date of Birth : </td><td><strong> <?php echo formatDate($player['date_of_birth']); ?></strong></td></tr>
                                    <tr><td>Height : </td><td><strong> <?php echo $player['height']; ?> cm</strong></td></tr>
                                    <tr><td>Weight : </td><td><strong> <?php echo $player['weight']; ?> kg</strong></td></tr>
                                    <tr><td>Country : </td><td><strong> <?php echo getCountry($player['country_id']); ?></strong></td></tr>
                                    <tr><td>County : </td><td><strong> <?php echo getCountry($player['county_id']); ?></strong></td></tr>
                                    
                                    <tr class="group-title"><th colspan="2">Other Information</th></tr>
                                    <tr><td>Highest Education Level : </td><td><strong> <?php echo $player['highest_education_level']; ?></strong></td></tr>
                                    <tr><td>Any Previous Injuries : </td><td><strong> <?php echo ($player['previous_injury'] == 1) ? 'Yes' : 'No'; ?></strong></td></tr>
                                    <tr><td>State Nature of Injury : </td><td><strong> <?php echo $player['nature_of_injury']; ?></strong></td></tr>
                                    <tr><td>No. of Years playing Football : </td><td><strong> <?php echo $player['years_playing_football']; ?></strong></td></tr>
                                    <tr><td>Highest Level Played At : </td><td><strong> <?php echo $player['highest_level_played_at']; ?></strong></td></tr>
                                    <tr><td>Club Played At Highest Level : </td><td><strong> <?php echo $player['club_played_at_highest_level']; ?></strong></td></tr>
                                    
                                    <tr class="group-title"><th colspan="2">Playing Position</th></tr>
                                    <tr><td>1st Playing Position : </td><td><strong> <?php echo getPosition($player['1st_player_position']); ?></strong></td></tr>
                                    <tr><td>2nd Playing Position : </td><td><strong> <?php echo getPosition($player['2nd_player_position']); ?></strong></td></tr>
                                    <tr><td>3rd Playing Position : </td><td><strong> <?php echo getPosition($player['3rd_player_position']); ?></strong></td></tr>
                                    <tr><td>Preferred Foot : </td><td><strong> <?php echo $player['prefered_foot']; ?></strong></td></tr>
                                </tbody>
                            </table>
                            
                            <?php
                            $result = $db->query('SELECT test_plan.*, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan LEFT JOIN  ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_test_plan.user_id = ?', array($player['id']));
                            $userPlanInfo = $result->row_array();

                            $tests = $db->query('SELECT user_score.*, test.* FROM ' . $dbPrefix . 'test as test LEFT JOIN  ' . $dbPrefix . 'user_test_score as user_score ON (user_score.test_id = test.id AND user_score.user_id = ?) WHERE test.status = 1 AND test.id IN (' . $userPlanInfo['allowed_test'] . ')', array($player['id']));
                            $tests = $tests->result_array();
                            ?>
                            
                            <table class="table borderless">
                                <tbody>
                                    <tr><td colspan="2">
                                        <div class="p_circle_dv progress-bar-block">
                                            <div class="progress-bar position" data-percent="<?php echo ($player['overall_score']) ? $player['overall_score'] : 0; ?>" data-color="#a456b1,#12b321"></div>
                                        </div>
                                    </td></tr>
                                    <tr class="group-title"><th colspan="2">Stats</th></tr>
                                    
                                    <?php foreach ($tests as $test) { ?>
                                        <tr><td><?php echo $test['title']; ?></td><td><strong> <?php echo $test['weightage']; ?></strong></td></tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            
                            <br>
                            
                            <div class="clearfix text-center">
                                <a href="<?php echo getLink('profile.php', 'profile_id='.$player['id']); ?>"><button class="btn btn-success">View Profile</button></a>
                            </div>
                            <br>
                            <br>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="no-compare"><h1>No player added in the compare list</h1></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    
    $(".progress-bar").loading();
    
});
</script>

<?php include('common/footer.php');?>