<?php include('config.php');

if (!checkLogin()) {
    redirect(getLink('login.php'));
}

$coach_id = $_SESSION['id'];

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($coach_id));
   
if ($result->num_rows() == 0) {
    redirect(getLink('login.php'));
}

$userInfo = $result->row_array();

if ($userInfo['user_type'] != 2) {
    redirect(getLink('permission.php'));
}

$request = $db->query('SELECT score_validation.*, user.id as user_id, user.first_name, user.last_name, user.photo, user.overall_score FROM ' . $dbPrefix . 'score_validation as score_validation INNER JOIN ' . $dbPrefix . 'user as user ON (user.id = score_validation.player_id AND user.status = 1) WHERE score_validation.coach_id = ?', array($coach_id));

$total = $request->num_rows();
$requestList = $request->result_array();

$page_title = 'Score Validation Request';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('score_validation_request.php')));
include('common/header.php');
?>

<div class="p_profile_bnr">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-3 p_profile_img">
                <?php echo getUserProfileImage($userInfo['photo']); ?>
            </div>
            <div class="col-xs-12 col-sm-8 p_profile_con">
                <div class="p_profile_con_inn">
                    <h3><?php echo $userInfo['first_name']; ?><br/><span><?php echo $userInfo['last_name']; ?></span></h3>
                    
                    <?php if(isset($userInfo['club_name'])) { ?>
                        <h4><?php echo $userInfo['club_name']; ?></h4>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="p_profile_dtl">
    <div class="container">
        <div class="row">
            
            <div class="col-xs-12 col-sm-3">
                
            </div>
            
            <div class="col-xs-12 col-sm-9 col-sm-offset-3">
            
                <h2><?php echo $page_title; ?></h2>
                
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Request Date</th>
                            <th>Overall Score</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($total > 0) { ?>
                        <?php foreach ($requestList as $request) { ?>
                        <tr>
                            <td><div class="validation-user-image"><a href="<?php echo getLink('profile.php', 'profile_id=' . $request['user_id']); ?>"><?php echo getUserProfileImage($request['photo'], 'player', 'style="width:60px;"'); ?></a></div><a href="<?php echo getLink('profile.php', 'profile_id=' . $request['user_id']); ?>"><?php echo $request['first_name'] . ' ' . $request['last_name']; ?></a></td>
                            <td><?php echo formatDate($request['request_date']); ?></td>
                            <td><?php echo $request['overall_score']; ?></td>
                            <td><?php echo ($request['status'] == 1) ? 'Validated' : 'Pending'; ?></td>
                        <td><?php if ($request['status'] == 0) { ?><a href="<?php echo getLink('profile.php', 'tab=score&profile_id=' . $request['user_id']); ?>" ><button name="back" class="btn btn-success">Validate</button></a><?php } ?></td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                            <tr><td colspan="6" align="center">No request found.</td><tr>
                        <?php } ?>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>

<?php include('common/footer.php');?>