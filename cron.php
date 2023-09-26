<?php 
include('config.php');
include('admin/inc/judopay/vendor/autoload.php');

// Send Payment Reminder
include('cron/payment_reminder.php');

// Check Payment
include('cron/payment.php');