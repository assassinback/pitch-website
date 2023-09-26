<?php 
$module = 'trialsession';
checkPermission($module);

$pageTitle = 'Booked Trial Session';

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$query = 'SELECT * FROM ' . $dbPrefix . 'trial_session WHERE id = ?';
$result = $db->query($query, array($id));
if ($result->num_rows() == 0) {
    redirect(getAdminLink($module));
}
$trialSessionInfo = $result->row_array();
$pageTitle .= ' - ' . $trialSessionInfo['title'];

$query = 'SELECT * FROM ' . $dbPrefix . 'user_trial_session as user_trial_session INNER JOIN ' . $dbPrefix . 'user as user ON (user.id = user_trial_session.user_id AND user.status = 1) WHERE user_trial_session.trial_session_id = ? ORDER BY user_trial_session.id DESC';
$result = $db->query($query, array($id));
$total = $result->num_rows();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?php echo $pageTitle; ?></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo getAdminLink();?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#"><i class="fa fa-folder"></i><?php echo $pageTitle; ?></a></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $pageTitle; ?></h3>
                    </div>
                    
                    <?php if (isset($msgString)) { ?>
                        <div class="alert alert-<?php echo $msgType; ?>">
                            <?php echo $msgString; ?>
                        </div>
                    <?php } ?>

                    <div class="box-body">
                        <form method="post" name="<?php echo $module . '-form'; ?>">
                            <table id="table-list" class="table table-bordered table-striped">
                                <thead>
                                    <tr class="trheader">
                                        <th width="20%">No</th>
                                        <th width="40%">Name</th>
                                        <th width="40%">Booking Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result->result_array() as $key=>$row) { ?>
                                        <tr>
                                            <td><?php echo ($key + 1); ?></td>
                                            <td><a href="<?php echo getAdminLink('userinfo', 'id=' . $row['user_id']); ?>"><?php echo $row['first_name'] . " " . $row['last_name']; ?></a></td>
                                            <td><?php echo $row['booking_date']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="clear"></div>

<script type="text/javascript">
    var tableList = {container: 'table-list', total: '<?php echo $total; ?>', sort: false, search: true, stateSave: true};    
</script>
<script src="js/list.js"></script>