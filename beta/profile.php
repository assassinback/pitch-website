<?php include('config.php');

if (isset($_GET['profile_id'])) {
    $profile_id = $_GET['profile_id'];

    if(isset($_SESSION['id']) && $_SESSION['id'] == $_GET['profile_id']){
        
        // viewing own profile
    } else if(isset($_SESSION['id'])){
        $viewer = null;

        $result = $db->query('SELECT views.viewer, views.date, user.user_type, user.first_name, user.photo, user.last_name, user.currently_working_for FROM ' . $dbPrefix . 'views as views LEFT JOIN ' . $dbPrefix . 'user as user ON views.viewer = user.id WHERE views.viewed = ' . $profile_id . ' AND views.viewer = ' . $_SESSION['id'] . ' ORDER BY views.date DESC', array());

        $viewData = array(
            'viewed' => $profile_id,
            'viewer' => $_SESSION['id'],
            'date' => date('Y-m-d H:i:s')
        );

        if ($result->num_rows() != 0) {
            $data = $result->row_array();
            $date1 = new DateTime($data['date']);
            $date2 = new DateTime($viewData['date']);
            $diff = $date1->diff($date2);
            if($diff->i < 5 && $diff->h == 0 && $diff->days == 0){
                // echo '<script>alert("do nothing ' . $diff->i . ' ")</script>';
                //nothing
            } else {
                // echo '<script>alert("log view")</script>';
                $id = insertData('views', $viewData);
            }
        } else {
            // echo '<script>alert("log view")</script>';
            $id = insertData('views', $viewData);
        }
    } else {
        header( "Location: " . getLink('login.php?redirect=profile') . "");
    }

} else if(isset($_SESSION['id'])) {
    $profile_id = $_SESSION['id'];
} else {
    $profile_id = 0;
}

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($profile_id));
   
if ($result->num_rows() == 0) {
    redirect(getLink('login.php'));
}

if (isset($_GET['tab']) && isset($_SESSION['id'])) {
    $tab = $_GET['tab'];
} else {
    $tab = 'info';
}

$allow_edit = false;
if(isset($_SESSION['id']) && $profile_id == $_SESSION['id']) {
    $allow_edit = true;
} else if($tab == 'activity'){
    $tab = 'info';
}

$userInfo = $result->row_array();

if (isset($_COOKIE['compare'])) {
    $compare = explode('-', $_COOKIE['compare']);
} else {
    $compare = array();
}

$user_type = $userInfo['user_type'];

$document['script'][] = 'profile.js';
$document['script'][] = 'progressbar.js';

$page_title = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('login.php')));
include('common/header.php');
?>

<?php if($user_type == 3) { ?>

<?php include('common/profile/scout.php'); ?>

<?php } else if($user_type == 2) { ?>

<?php include('common/profile/coach.php'); ?>

<?php } else { ?>

    <?php include('common/profile/player.php'); ?>

<?php } ?>

<script>

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function checkCookie(element) {
  var todoList = getCookie("todoList");
  if (todoList && !isToday(new Date(todoList))) {

    
  } else {
    console.log('show todo');
    element.style.display = 'block';
    const date = new Date();
    setCookie("todoList", date, 10);
  }
}

const isToday = (date) => {
    const today = new Date()
    return date.getDate() === today.getDate() &&
        date.getMonth() === today.getMonth() &&
        date.getFullYear() === today.getFullYear();
};

var elements = document.getElementsByClassName('todoList-optional');
if(elements.length > 0){
    checkCookie(elements[0]);
}


</script>

<?php include('common/footer.php');?>
