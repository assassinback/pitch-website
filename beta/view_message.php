<?php include('config.php');

if (!checkLogin()) {
    redirect(getLink());
}

if (isset($_GET['conversion_id'])) {
    $conversion_id = $_GET['conversion_id'];
} else {
    $conversion_id = 0;
}

$sender_id = $_SESSION['id'];

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($sender_id));
   
if ($result->num_rows() == 0) {
    redirect(getLink());
}

$userInfo = $result->row_array();

$conversion = $db->query('SELECT * FROM ' . $dbPrefix . 'conversion WHERE id = ?', array($conversion_id));

if ($conversion->num_rows() == 0) {
    redirect(getLink('message.php'));
}

$conversionInfo = $conversion->row_array();

if ($conversionInfo['sender_id'] == $sender_id) {
    $receiver_id = $conversionInfo['receiver_id'];
} else {
    $receiver_id = $conversionInfo['sender_id'];
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $message = $_POST['message'];
    $date_sent = date('Y-m-d H:i:s');
    
    $conversionData = array(
                            'last_message' => $date_sent,
                            );
    updateData('conversion', $conversionData, 'id=' . $conversion_id);
    
    
    $messageData = array(
                            'conversion_id' => $conversion_id,
                            'sender_id' => $sender_id,
                            'receiver_id' => $receiver_id,
                            'text' => $message,
                            'date_sent' => $date_sent,
                            );
    insertData('message', $messageData);
    
    $success = 'Reply sent successfully.';
}

$messages = $db->query('SELECT message.*, user.id as user_id, user.first_name, user.last_name, user.photo FROM ' . $dbPrefix . 'message as message INNER JOIN ' . $dbPrefix . 'user as user ON (message.sender_id = user.id AND user.status = 1) WHERE message.conversion_id = ? ORDER BY message.date_sent DESC', array($conversion_id));

$total = $messages->num_rows();
$messages = $messages->result_array();

if ($total == 0) {
    redirect(getLink('message.php'));
}

$messageData = array(
                    'is_read' => 1,
                    );
updateData('message', $messageData, 'conversion_id=' . $conversion_id . ' AND receiver_id=' . $sender_id);

$page_title = 'View Message';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => 'Message', 'link' => getLink('message.php')), array('title' => $page_title, 'link' => getLink('message.php')));
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
            
            <div class="col-xs-12 col-sm-9">
            
                <h2>Reply</h2>
                
                <?php if (isset($success)) { ?>
                    <div class="alert alert-success">
                        <?php echo "<strong>Success!</strong> " . $success; ?>
                    </div>
                <?php } ?>
                
                <form action="" method="POST" name="form-send-message" id="form-send-message">
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea class="form-control" rows="5" name="message" id="message"></textarea>
                    </div>
                    <button type="submit" class="btn btn-default btn-success">Reply</button>
                </form>
                
                <h2>Messages</h2>
                <?php foreach ($messages as $message) { ?>
                    <div class="bs-callout <?php if ($message['receiver_id'] == $receiver_id) {?> bs-callout-info <?php } else { ?> bs-callout-warning <?php } ?>" id="callout-labels-inline-block">
                        <div class="row">
                            <div class="col-xs-3 col-sm-2 msg-user-image">
                                <a href="<?php echo getLink('profile.php', 'profile_id=' . $message['user_id']); ?>"><?php echo getUserProfileImage($message['photo'], 'player', 'style="width:60px;"'); ?></a>
                            </div>
                            <div class="col-xs-9 col-sm-10">
                                <a href="<?php echo getLink('profile.php', 'profile_id=' . $message['user_id']); ?>"><h4><?php echo $message['first_name'] . ' ' . $message['last_name']; ?></h4></a>
                                <p><?php echo $message['text']; ?></p>
                                <small>Sent on <?php echo formatDateTime($message['date_sent']); ?></small>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                  
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(e){
        var form = $('#form-send-message');
        form.find('[type="submit"]').on('click', function(e){
            e.preventDefault();
            form.find('.form-error').remove();
            var message = form.find('#message').val();
            if (message == "") {
                form.find('#message').after('<p class="form-error text-danger">Please enter message!</p>');
            } else {
                form.submit();
            }
        });
    });
</script>

<?php include('common/footer.php');?>