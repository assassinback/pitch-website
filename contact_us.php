<?php include('config.php');

$success_msg = '';

if(isset($_REQUEST['send_message']))
{
    $admin_email = CONTACT_EMAIL;
    $name = isset($_REQUEST["name"])?$_REQUEST["name"]:''; 
    $email = isset($_REQUEST["email"])?$_REQUEST["email"]:'';
    $subject = isset($_REQUEST["subject"])?$_REQUEST["subject"]:'';
    $message = isset($_REQUEST["message"])?$_REQUEST["message"]:'';

    $messageBlock = '<p>Dear Admin, </p>';
    $messageBlock .= '<p>Contact enquiry submitted on website.</p>';
    $messageBlock .= '<p><b>Name : </b>' . $name . '</p>';
    $messageBlock .= '<p><b>Email : </b>' . $email . '</p>';
    $messageBlock .= '<p><b>Subject : </b>' . $subject . '</p>';
    $messageBlock .= '<p><b>Message : </b>' . $message . '</p>';
    $msgdata = array(
        'to' => array($admin_email),
        'subject' => "Contact Us",
        'message' => $messageBlock
    );
    if(sendMsg($msgdata))
    {
        $success_msg = "Thank you! Your message has been sent successfully.";
    }
}    

$document['style'][] = 'validationEngine.jquery.css';

$document['script'][] = 'jquery.validationEngine.js';
$document['script'][] = 'jquery.validationEngine-en.js';

$page_title = 'Contact Us';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('contact_us.php')));
include('common/header.php');
?>

<div class="p_contact-us">
    <div id="map-view" class="map" style="min-height:400px;"></div>
    <div class="section-form">
        <div class="container">
            <h2><?php echo $page_title; ?></h2>
            <div class="left-detail">
                <ul>
                    <li class="addrs"><span><?php echo SITE_ADDRESS; ?></span></li>
                    <li class="tel"><a href="tel:<?php echo SITE_PHONE_NO; ?>"><?php echo SITE_PHONE_NO; ?></a></li>
                    <li class="mail"><a href="mailto:<?php echo SITE_EMAIL; ?>"><?php echo SITE_EMAIL; ?></a></li>
                </ul>
            </div>
            <div class="right-form">
                
                <?php if($success_msg){ ?>
                    <div class="alert alert-success">
                        <?php echo $success_msg; ?>
                    </div>
                <?php } ?>
                
                <form role="form" class="validateForm" name="contactForm" action="" method="post" enctype="multipart/form-data">
                    <ul>
                        <li>
                            <input type="text" name="name" id="name" value="" class="" placeholder="Your Name *" maxlength="50" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter your name" >
                        </li>
                        <li class="msg">
                            <textarea rows="2" cols="2" name="message" id="message" placeholder="Message *" maxlength="200" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter message" ></textarea>
                        </li>
                        <li>
                            <input type="text" name="email" id="email" value="" class="" placeholder="Email Address *" data-validation-engine="validate[required,custom[email]]" data-errormessage-value-missing="The e-mail address you entered appears to be incorrect." maxlength="70" data-errormessage-custom-error="Example: test@gmail.com" >
                        </li>
                        <li>
                            <input type="text" name="subject" id="subject" value="" class="" placeholder="Subject *" maxlength="250" data-validation-engine="validate[required]" data-errormessage-value-missing="Please enter subject" >
                        </li>
                        <li class="btn-li">
                            <input type="submit" name="send_message" class="btn-contact" value="Send Message"/>
                        </li>
                    </ul>
                </form>    
            </div>
        </div>
    </div>
</div>
<script src="http://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY; ?>"></script>
<script>
    var geocoder;
    var map;
    function initialize() {
        geocoder = new google.maps.Geocoder();
        var mapOptions = {
            zoom: 16,
        }
        map = new google.maps.Map(document.getElementById('map-view'), mapOptions);
    }
    
    function codeAddress(address) {
        var address = address;
        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    zoom: 12,
                    map: map,
                    position: results[0].geometry.location,
                    title: address
                });
                
            } else {
                console.log('Geocode was not successful for the following reason: ' + status);
            }
        });
    }
    
    google.maps.event.addDomListener(window, 'load', initialize);
    window.onload = function(){
        initialize();
        <?php if (SITE_ADDRESS != '') { ?>
        codeAddress('<?php echo SITE_ADDRESS; ?>');
        <?php } ?>
    }
    
    $(document).ready(function(){
        $(".validateForm").validationEngine({promptPosition : "inline", scroll: true});
    });
</script>

<?php include('common/footer.php');?>