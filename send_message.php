<?php include('config.php');

if (!checkLogin()) {
    redirect(getLink());
}

if (isset($_GET['receiver_id'])) {
    $receiver_id = $_GET['receiver_id'];
} else {
    $receiver_id = 0;
}

$sender_id = $_SESSION['id'];

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($receiver_id));
   
if ($result->num_rows() == 0) {
    redirect(getLink());
}

$userInfo = $result->row_array();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $message = $_POST['message'];
    $date_sent = date('Y-m-d H:i:s');
    
    $conversion = $db->query('SELECT * FROM ' . $dbPrefix . 'conversion WHERE (sender_id = ? AND receiver_id = ?) OR (receiver_id = ? AND sender_id = ?)', array($sender_id, $receiver_id, $sender_id, $receiver_id));
    
    if ($conversion->num_rows() == 0) {
        
        $conversionData = array(
                                'sender_id' => $sender_id,
                                'receiver_id' => $receiver_id,
                                'start_date' => $date_sent,
                                'last_message' => $date_sent,
                                );
        insertData('conversion', $conversionData);
        $conversion_id = $db->insert_id();
        
    } else {
        $conversionInfo = $conversion->row_array();
        $conversion_id = $conversionInfo['id'];
        
        $conversionData = array(
                                'last_message' => $date_sent,
                                );
        updateData('conversion', $conversionData, 'id=' . $conversion_id);
    }
    
    $messageData = array(
                            'conversion_id' => $conversion_id,
                            'sender_id' => $sender_id,
                            'receiver_id' => $receiver_id,
                            'text' => $message,
                            'date_sent' => $date_sent,
                            );
    insertData('message', $messageData);
    
    $success = 'Message sent successfully.';
}

$page_title = 'Send Message';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $userInfo['first_name'] . ' ' . $userInfo['last_name'], 'link' => getLink('profile.php', 'profile_id=' . $receiver_id)), array('title' => $page_title, 'link' => getLink('message.php')));
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
                
                <h2>Send Message</h2>
                
                <?php if (isset($success)) { ?>
                    <div class="alert alert-success">
                        <?php echo "<strong>Success!</strong> " . $success; ?>
                    </div>
                <?php } ?>
                
                <form action="" method="POST" name="sendMessageForm" id="sendMessageForm">
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea class="form-control" rows="5" name="message" id="message"></textarea>
                    </div>
                    <button type="submit" class="btn btn-default btn-success">Submit</button>
                </form>
                
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(e){
        var form = $('#sendMessageForm');
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