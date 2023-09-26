<?php 
$module = 'contributor';
checkPermission($module);

$pageTitle = 'Contributor';

$delId = (int)isset($_GET["delId"]) ? $_GET["delId"] : null;
if($delId) {
    $result = $db->query('DELETE FROM ' . $dbPrefix . 'contributor WHERE id = ?', array($delId));
    
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
        $result = $db->query('DELETE FROM ' . $dbPrefix . 'contributor WHERE id IN ?', array($delId));
        
        if ($result) {
            $_SESSION['msgType'] = 'danger';
            $_SESSION['msgString'] = 'Selected record(s) deleted successfully!';
        }
    }
    redirect(getAdminLink($module));
    exit;
}

$query = 'SELECT * FROM ' . $dbPrefix . 'contributor ORDER BY id DESC';
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
                                        <th width="5%"><input type="checkbox" class="select-all" ></th>
                                        <th width="50%">Title</th>
                                        <th class="column-status" width="25%">Status</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result->result_array() as $row) { ?>
                                        <tr>
                                            <td><input type="checkbox" name="delete[]" value="<?php echo $row['id']; ?>" ></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td class="column-status"><?php echo ($row['status']) ? 'Active' : 'Inactive'; ?></td>
                                            <td>
                                                <a href="<?php echo getAdminLink('add' . $module, 'id=' . $row['id']); ?>" class="btn btn-primary">Modify</a>
                                                <a class="btn btn-danger delete-record" href="<?php echo getAdminLink($module, 'delId=' . $row['id']); ?>" title="are you sure want to delete this record?">Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div>
                                <a href="<?php echo getAdminLink('add' . $module);?>" ><button type="button" name="add" class="btn btn-primary">Add</button></a>
                                <button type="button" name="delete" class="btn btn-danger delete-selected-record">Delete</button>
                            </div>
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