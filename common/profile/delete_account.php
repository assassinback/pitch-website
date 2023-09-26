<?php if($allow_edit) { ?>
    <div class="clearfix" >
        <br>
        <a href="javascript:" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete Account</a>
    </div>
    
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="<?php echo getLink('phpajax/delete_account.php', '', true); ?>" name="form-delete-account" id="form-delete-account" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title">Delete Account</h2>
                    </div>
                    <div class="modal-body">
                        <h3>Are you sure want to delete your account?</h3>
                        <br>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger" >Delete</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
<?php } ?>