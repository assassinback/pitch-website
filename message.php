<?php include('config.php');

if (!checkLogin()) {
    redirect(getLink());
}

$receiver_id = $_SESSION['id'];

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($receiver_id));
   
if ($result->num_rows() == 0) {
    redirect(getLink());
}

$userInfo = $result->row_array();

$messages = $db->query('SELECT conversion.*, user.id as user_id, user.first_name, user.last_name, user.photo, (SELECT count(message.id) as unread FROM ' . $dbPrefix . 'message as message WHERE message.conversion_id = conversion.id AND message.receiver_id = ? AND message.is_read = 0) as unread, (SELECT message.text as text FROM ' . $dbPrefix . 'message as message WHERE message.conversion_id = conversion.id ORDER BY message.date_sent DESC LIMIT 1) as text FROM ' . $dbPrefix . 'conversion as conversion INNER JOIN ' . $dbPrefix . 'user as user ON (IF(conversion.sender_id != ?, conversion.sender_id, conversion.receiver_id) = user.id AND user.status = 1) WHERE (conversion.receiver_id = ? OR conversion.sender_id = ?) ORDER BY conversion.last_message DESC', array($receiver_id, $receiver_id, $receiver_id, $receiver_id));

$total = $messages->num_rows();
$messages = $messages->result_array();

$page_title = 'Messages';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('message.php')));
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
                
                <h2>Messages</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($total > 0) { ?>
                        <?php foreach ($messages as $message) { ?>
                        <tr>
                            <td><div class="msg-user-image"><a href="<?php echo getLink('profile.php', 'profile_id=' . $message['user_id']); ?>"><?php echo getUserProfileImage($message['photo'], 'player', 'style="width:60px;"'); ?></a></div><a href="<?php echo getLink('profile.php', 'profile_id=' . $message['user_id']); ?>"><?php echo $message['first_name'] . ' ' . $message['last_name']; ?></a></td>
                            <td><?php echo $message['text']; ?></td>
                            <td><?php echo formatDateTime($message['last_message']); ?></td>
                            <td><a href="<?php echo getLink('view_message.php', 'conversion_id=' . $message['id']); ?>" ><button name="back" class="btn btn-success">View <?php echo ($message['unread'] > 0) ? '<span class="text-danger unread">(' . $message['unread'] . ')</span>' : ''; ?></button></a></td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                            <tr><td colspan="4" align="center">No messages found.</td><tr>
                        <?php } ?>
                    </tbody>
                </table>
                  
            </div>
            
        </div>
    </div>
</div>

<?php include('common/footer.php');?>