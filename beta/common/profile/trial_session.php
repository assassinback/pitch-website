<?php
$trial_sessions = $db->query('SELECT * FROM ' . $dbPrefix . 'trial_session WHERE date > Now() AND (space-booked) > 0 ORDER BY date ASC', array());
$trial_sessions = $trial_sessions->result_array();
?>
 
    <div id="trialSessionModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <form action="<?php echo getLink('phpajax/trial_session.php', '', true); ?>" name="form-book-trial-session" id="form-book-trial-session" method="POST">
                    <input type="hidden" name="user_amenity_id" value="<?php echo $amenity['id']; ?>" >
                    <input type="hidden" name="trial_session_id" value="" >
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Trial Session Dates</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <tbody>
                                <?php foreach ($trial_sessions as $trial_session) { ?>
                                    <tr>
                                        <td><?php echo formatDate($trial_session['date']); ?></td>
                                        <td><?php echo $trial_session['title']; ?></td>
                                        <td><?php echo 'Age ' . $trial_session['min_age'] . ' - ' . $trial_session['max_age']; ?></td>
                                        <td><?php echo ($trial_session['space'] - $trial_session['booked']) . ' Spaces Left'; ?></td>
                                        <td><a href="javascript:" id="<?php echo $trial_session['id']; ?>" class="book-session"><i class="fa fa-check text-info" aria-hidden="true" ></i></a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" >Confirm</button>
                    </div>
                </form>
            </div>

        </div>
    </div>