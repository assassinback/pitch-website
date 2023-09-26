<?php include('config.php');
session_destroy();
redirect(getLink());
exit;
?>