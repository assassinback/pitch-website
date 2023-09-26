<?php 
$module = 'cmspages';
checkPermission($module);

$pageTitle = 'CMS Pages';

$query = 'SELECT * FROM ' . $dbPrefix . 'cms_pages ORDER BY title ASC';
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
                                        <th width="60%">Title</th>
                                        <th class="column-status" width="20%">Status</th>
                                        <th width="20%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result->result_array() as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['title']; ?></td>
                                            <td class="column-status"><?php echo ($row['status']) ? 'Active' : 'Inactive'; ?></td>
                                            <td>
                                                <a href="<?php echo getAdminLink('add' . $module, 'id=' . $row['id']); ?>" class="btn btn-primary">Modify</a>
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