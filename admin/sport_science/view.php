<?php 
$module = 'sportscience';
checkPermission($module);

$pageTitle = 'Sport Science Validation';

$delId = (int)isset($_GET["delId"]) ? $_GET["delId"] : null;
if($delId) {
    $result = $db->query('DELETE FROM ' . $dbPrefix . 'sport_science_validation WHERE id = ?', array($delId));
    
    if ($result) {
        $_SESSION['msgType'] = 'danger';
        $_SESSION['msgString'] = 'Record deleted successfully!';
    }
    
    redirect(getAdminLink($module));
    exit;
}

if(isset($_REQUEST['del']))
{
    $delete = $_REQUEST['delete'];
    if (count($delete) > 0) {
        $delete = implode(',', $delete);
        $result = $db->query('DELETE FROM ' . $dbPrefix . 'sport_science_validation WHERE id IN ?', array($delId));
        
        if ($result) {
            $_SESSION['msgType'] = 'danger';
            $_SESSION['msgString'] = 'Selected record(s) deleted successfully!';
        }
    }
    redirect(getAdminLink($module));
    exit;
}

$query = 'SELECT sport_science.*, user.first_name, user.last_name, user.overall_score FROM ' . $dbPrefix . 'sport_science_validation as sport_science INNER JOIN ' . $dbPrefix . 'user as user ON (user.id = sport_science.player_id AND user.status = 1) ORDER BY sport_science.id DESC';
$result = $db->query($query);
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
                                <input type="hidden" name="del" id="del" value="" />
                                <thead>
                                    <tr class="trheader">
                                        <th width="5%">No</th>
                                        <th width="20%">Name</th>
                                        <th width="20%">Request Date</th>
                                        <th width="20%">Overall Score</th>
                                        <th width="20%">Status</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result->result_array() as $key=>$row) { ?>
                                        <tr>
                                            <td><?php echo ($key + 1); ?></td>
                                            <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                            <td><?php echo formatDate($row['request_date']); ?></td>
                                            <td><?php echo $row['overall_score']; ?></td>
                                            <td><?php echo ($row['status'] == 1) ? 'Validated' : 'Pending'; ?></td>
                                            <td>
                                                <?php if ($row['status'] == 0) { ?>
                                                <a href="<?php echo getAdminLink('userinfo', 'id=' . $row['player_id'] . '&tab=score'); ?>" target="_blank" class="btn btn-primary">Validate</a>
                                                <?php } ?>
                                            </td>
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