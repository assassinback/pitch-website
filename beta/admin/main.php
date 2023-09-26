<?php
// Page Title and Template
?>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php echo $title; ?></title>
	<!--<script src="js/bootstrap-datepicker.min.js"></script>  -->
	<script src="js/core.js" type="text/javascript"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
		<?php require_once("inc/top.inc.php");?>
		<?php require_once("inc/side.inc.php");?>	
		<?php if($mfile != ""){ include_once $mfile;}	?>
        <?php include_once("inc/footer.php");?>
    </div>
</body>
</html>