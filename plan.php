<?php include('config.php');

if(isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
} else {
    $user_id = 0;
}

//$result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND id = ?', array($user_id));

/*
if ($result->num_rows() == 0) {
    redirect(getLink('login.php'));
}
*/

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (!checkLogin()) {
        redirect(getLink('login.php'));
    }
    
    $user_id = $_SESSION['id'];
    $plan_id = isset($_POST['plan_id']) ? $_POST['plan_id'] : 0;
    $validation_session_plan_id = isset($_POST['validation_session_plan_id']) ? $_POST['validation_session_plan_id'] : 0;
    
    if ($validation_session_plan_id) {
        
        $plan = $db->query('SELECT * FROM ' . $dbPrefix . 'validation_session_plan WHERE status = 1 and id = ?', array($validation_session_plan_id));
        $planInfo = $plan->row_array();
        
        if ($plan->num_rows() == 0) {
            redirect(getLink());
        }
        
        $amenities = $db->query('SELECT * FROM ' . $dbPrefix . 'validation_session_plan_amenities as validation_session_plan_amenities INNER JOIN ' . $dbPrefix . 'amenities as amenities ON (amenities.id = validation_session_plan_amenities.amenity_id AND amenities.status = 1) WHERE validation_session_plan_amenities.validation_session_plan_id = ?', array($planInfo['id']));
        $amenities = $amenities->result_array();
        
    } else {
        
        $plan_type = isset($_POST['plan_type']) ? $_POST['plan_type'] : 'month';
        $plan = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan WHERE status = 1 and id = ?', array($plan_id));
        $planInfo = $plan->row_array();
        
        if ($plan->num_rows() == 0) {
            redirect(getLink());
        }
        
        $price = $planInfo[$plan_type . 'ly_price'];
        
        $purchase_date = date('Y-m-d H:i:s');
        
        if ($price != 0) {
            $previous_payment_date = $purchase_date;
            if ($plan_type == 'year') {
                $next_payment_date = date('Y-m-d H:i:s', strtotime('+1 years'));
            } else {
                $next_payment_date = date('Y-m-d H:i:s', strtotime('+1 months'));
            }
        } else {
            $plan_type = null;
            $previous_payment_date = null;
            $next_payment_date = null;
        }
        
        $planData = array(
                            'test_plan_id' => $planInfo['id'],
                            'type' => $plan_type,
                            'price' => $price,
                            'allowed_test' => $planInfo['allowed_test'],
                            /* 'training_plan_6_week' => $planInfo['training_plan_6_week'],
                            'training_plan_12_week' => $planInfo['training_plan_12_week'],
                            'potential_trial' => $planInfo['potential_trial'],
                            'sport_science_validation' => $planInfo['sport_science_validation'], */
                            'purchase_date' => $purchase_date,
                            'previous_payment_date' => $previous_payment_date,
                            'next_payment_date' => $next_payment_date,
                        );
        
        $where ='user_id =' . $user_id .'';
        $update = updateData('user_test_plan', $planData, $where);
        
        $amenities = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan_amenities as test_plan_amenities INNER JOIN ' . $dbPrefix . 'amenities as amenities ON (amenities.id = test_plan_amenities.amenity_id AND amenities.status = 1) WHERE test_plan_amenities.test_plan_id = ?', array($planInfo['id']));
        $amenities = $amenities->result_array();
        
        $date = date('Y-m-d H:i:s');
        $planAmenityData = array(
                            'availability' => 0,
                            'date_modified' => $date
                        );
        $where ='user_id =' . $user_id .' AND test_plan_id > 0';
        updateData('user_amenities', $planAmenityData, $where);
        
    }
    
    $date = date('Y-m-d H:i:s');
    foreach ($amenities as $amenity) {
        
        for ($i=0; $i<$amenity['quantity']; $i++) {
            $planAmenityData = array(
                            'user_id' => $user_id,
                            'amenity_id' => $amenity['id'],
                            'test_plan_id' => $plan_id,
                            'type' => $amenity['type'],
                            'date_added' => $date,
                            'date_modified' => $date
                        );
            insertData('user_amenities', $planAmenityData);
        }
    }
}

$allow_discount = true;
if (checkLogin()) {
    
    $user_id = $_SESSION['id'];
    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user WHERE id = ?', array($user_id));
    $userInfo = $result->row_array();
    
    $result = $db->query('SELECT * FROM ' . $dbPrefix . 'user_test_plan WHERE user_id = ?', array($user_id));
    $userPlanInfo = $result->row_array();
    
    if ($userPlanInfo['first_purchase'] == 1) {
        $allow_discount = false;
    }
}

$tests = $db->query('SELECT * FROM ' . $dbPrefix . 'test WHERE status = 1', array());
$tests = $tests->result_array();

$testList = array();
foreach ($tests as $test) {
    $testList[$test['id']] = $test['title'];
}

$plans = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan WHERE status = 1', array());
$plans = $plans->result_array();

$validation_session_plans = $db->query('SELECT * FROM ' . $dbPrefix . 'validation_session_plan WHERE status = 1', array());
$validation_session_plans = $validation_session_plans->result_array();

$document['script'][] = 'plan.js';

$page_title = 'Pricing and subscription';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('plan.php')));
include('common/header.php');
?>

<div class="p_plans">
	
	<h1>Pricing</h1>
	
	<ul id="plan-categories" class="plan_tab">
		<li class="active"><a href="#" data-category="test-plan">Test plans</a></li>
		<li><a href="#" data-category="validation-session-plan">Validation session plans</a></li>
	</ul>
	<br/>
    
	<ul id="plan-types" class="plan_rd">
		<li><label class="active"><input class="plan_radio plan-type" type="radio" name="plan_type" value="month" />Monthly</label></li>
		<li><label><input class="plan_radio plan-type" type="radio" name="plan_type" value="year" />Yearly</label></li>
	</ul>
	
	<div class="plan_wrap" >
    
        <div id="test-plan" >
        <?php 
        $plan_types = array('month', 'year');
        foreach ($plan_types as $plan_type) { ?>
                
            <div id="plan-<?php echo $plan_type; ?>" <?php if ($plan_type == 'year') { ?> style="display: none;" <?php } ?>>
                
                <?php foreach ($plans as $plan) {?>
                
                    <?php $price = $plan[$plan_type . 'ly_price']; ?>
                    <?php $discount = $plan[$plan_type . 'ly_discount']; ?>
                    
                    <?php if ($allow_discount && $discount > 0) {
                        $discounted_price = $price - ($price*$discount/100);
                    } else {
                        $discounted_price = 0;
                    } ?>
            
                    <div class="plan_wrap_inn plan_wrap_<?php echo strtolower($plan['title']); ?>">
                        <h2><?php echo $plan['title']; ?></h2>
                        <p><?php echo $plan['sub_title']; ?></p>
                        <hr class="plan_hr">
                        <div class="plan_descp">
                            <h3><?php echo $plan['test_title']; ?></h3>
                            <ul>
                                <?php 
                                $allowed_test = explode(',', $plan['allowed_test']);
                                foreach ($allowed_test as $test_id) { 
                                    if (isset($testList[$test_id])) { ?>
                                    <li><?php echo $testList[$test_id]; ?></li>
                                <?php } } ?>
                            </ul>
                            <div class="plan_descp_con">
                                
                                <?php 
                                $amenities = $db->query('SELECT * FROM ' . $dbPrefix . 'test_plan_amenities as test_plan_amenities INNER JOIN ' . $dbPrefix . 'amenities as amenities ON (amenities.id = test_plan_amenities.amenity_id AND amenities.status = 1) WHERE test_plan_amenities.test_plan_id = ?', array($plan['id']));
                                $amenities = $amenities->result_array();
                                
                                foreach($amenities as $amenity) { ?>
                                    <?php if ($amenity['per_year'] == 1) { ?>
                                        <p><?php echo ucfirst($amenity['quantity']) . ' ' . $amenity['title'] . '/year*'; ?></p>
                                    <?php } else { ?>
                                        <p><?php echo $amenity['title']; ?></p>
                                    <?php } ?>
                                <?php } ?>
                            
                            </div>
                            <hr>
                        </div>
                        <div class="plan_price">
                            <?php echo $discount_html = ''; ?>
                            <?php if ($price == 0) { ?>
                                <?php echo $price_html = 'FREE'; ?>
                            <?php } else  { ?>
                                <?php if ($discounted_price > 0) {
                                    echo $price_html = formatPrice($discounted_price) . '<span>/' . ucfirst($plan_type) . '</span>';
                                    $discount_html = '<span class=\'discount\'>' . formatPrice($price) . '<span>/' . ucfirst($plan_type) . '</span></span>';
                                    echo '<label>' . $discount_html . '</label>';
                                } else {
                                    echo $price_html = formatPrice($price) . '<span>/' . ucfirst($plan_type) . '</span>';
                                }?>
                                <?php if ($plan_type == 'year') { ?>
                                    <label>(Save <?php echo formatPrice((($plan['monthly_price'] * 12) - $price)/12); ?> per month)</label>
                                <?php } else { ?>
                                    <label>(<?php echo formatPrice($price * 12); ?> per year)</label>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        
                        <?php if (checkLogin()) { ?>
                            <?php if ($userInfo['user_type'] == 1) { ?>
                                <?php if ($userPlanInfo['test_plan_id'] == $plan['id'] && ($userPlanInfo['type'] == $plan_type || $price == 0)) { ?>
                                    <div class="plan_bn"><a href="javascript:" class="tick" ><i class="fa fa-check" aria-hidden="true"></i></a></div>
                                <?php } else { ?>
                                    <?php /* <form action="" method="post" >
                                        <input type="hidden" name="plan_id" value="<?php echo $plan['id']; ?>" >
                                        <input type="hidden" name="plan_type" value="<?php echo $plan_type; ?>" >
                                        <div class="plan_bn"><a href="javascript:" onclick="$(this).closest('form').submit()">Buy Now</a></div>
                                    </form> */ ?>
                                    <div class="plan_bn"><a href="javascript:" class="plan-purchase" data-plan_id="<?php echo $plan['id']; ?>" data-validation_session_plan_id="" data-plan_type="<?php echo $plan_type; ?>" data-title="<?php echo $plan['title']; ?>" data-price="<?php echo $price_html; ?>" data-actual-price="<?php echo $price; ?>" data-discount="<?php echo $discount_html; ?>" >Buy Now</a></div>
                                <?php } ?>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="plan_bn"><a href="<?php echo getLink('login.php'); ?>" >Buy Now</a></div>
                        <?php } ?>
                        
                        <hr class="plan_hr">
                        <p class="description"><?php echo $plan['description']; ?></p>
                    </div>
                    
                <?php } ?>
                
                <p>&nbsp;</p>
                <div class="plan_off">50% OFF ON ANNUAL SUBSCRIPTION FOR ONE YEAR ONLY</div>
                
            </div>
            
        <?php } ?>
        
        </div>
        
        <div id="validation-session-plan" style="display: none;">
                 
            <?php foreach ($validation_session_plans as $plan) {?>
        
                <div class="plan_wrap_inn">
                    <?php echo $discount_html = ''; ?>
                    <h2><?php echo $price_html = formatPrice($plan['price']); ?></h2>
                    <p>&nbsp;</p>
                    <hr class="plan_hr">
                    <div class="plan_descp">
                        <?php 
                        $amenities = $db->query('SELECT * FROM ' . $dbPrefix . 'validation_session_plan_amenities as validation_session_plan_amenities INNER JOIN ' . $dbPrefix . 'amenities as amenities ON (amenities.id = validation_session_plan_amenities.amenity_id AND amenities.status = 1) WHERE validation_session_plan_amenities.validation_session_plan_id = ?', array($plan['id']));
                        $amenities = $amenities->result_array();
                        
                        foreach($amenities as $amenity) { ?>
                            <h3><?php echo $amenity['title']; ?></h3>
                            <?php echo $amenity['description']; ?>
                        <?php } ?>
                        <hr>
                    </div>
                    <?php if (checkLogin()) { ?>
                        <?php if ($userInfo['user_type'] == 1) { ?>
                            <?php /* <form action="" method="post" >
                                <input type="hidden" name="validation_session_plan_id" value="<?php echo $plan['id']; ?>" >
                                <div class="plan_bn"><a href="javascript:" onclick="$(this).closest('form').submit()">Buy Now</a></div>
                            </form> */ ?>
                            <div class="plan_bn"><a href="javascript:" class="plan-purchase" data-plan_id="" data-validation_session_plan_id="<?php echo $plan['id']; ?>" data-plan_type="" data-title="Sport Science Validation" data-price="<?php echo $price_html; ?>" data-actual-price="<?php echo $plan['price']; ?>" data-discount="<?php echo $discount_html; ?>" >Buy Now</a></div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="plan_bn"><a href="<?php echo getLink('login.php'); ?>" >Buy Now</a></div>
                    <?php } ?>
                    
                </div>
                
            <?php } ?>
                
        </div>
    
    </div>
    
    <p class="notes">* Players can contact scouts, managers and coaches if all tests have been through full sports science validation</p>
	
</div>

<?php if(checkLogin()) { ?>    
    <div id="planModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content cstm_plan">
                <form action="<?php echo getLink('phpajax/purchase_plan.php', '', true); ?>" name="form-purchase-plan" id="form-purchase-plan" method="POST">
                    <input type="hidden" name="plan_id" value="" >
                    <input type="hidden" name="plan_type" value="" >
                    <input type="hidden" name="validation_session_plan_id" value="" >
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title">Purchase Plan</h2>
                    </div>
                    <div class="modal-body">
                        <div class="col-sm-12">
                            <h4>Plan : <span id="display-plan-title"></span></h4>
                            <p>Type : <span id="display-plan-type"></span></p>
                            <p>Price : <span id="display-plan-price"></span><span id="display-discount-price"></span></p>
                        </div>
                        
                        <div id="card-details" class="clearfix">
                            <div class="col-sm-12">
                                <h3>Enter Card Details</h3>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="card-number">Card Number:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" maxlength="16" id="card_number" placeholder="Enter Card Number" name="card_number" onkeypress="return isNumber(event)" value="">
                                </div>
                                <p>&nbsp;</p>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="expiry-date">Expiry Date:</label>
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <select class="form-control" id="expiry_month"  name="expiry_month">
                                                <option value="" >Month</option>
                                                <?php for ($i=1; $i<=12; $i++) { ?>
                                                    <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" ><?php echo date('M',  mktime(0, 0, 0, $i, 10)); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-xs-6">
                                            <select class="form-control" id="expiry_year"  name="expiry_year">
                                                <option value="" >Year</option>
                                                <?php for ($i=date('Y'),$j=date('y'); $i<=(date('Y')+25); $i++,$j++) { ?>
                                                    <option value="<?php echo $j; ?>" ><?php echo $i; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <p>&nbsp;</p>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="cvv">CVV:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="cvv" maxlength="4" placeholder="Enter cvv" name="cvv" onkeypress="return isNumber(event)" style="width: 100px;">
                                </div>
                                <p>&nbsp;</p>
                            </div>
                        </div>
                        <div class="ajax-loading" >
                            <h3>Loading...</h3>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" >Buy</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
<?php } ?>

<script>
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

jQuery(document).ready(function($) {
    
    $('#plan-types li label input:radio').click(function() {
        $('input:radio[name='+$(this).attr('name')+']').parent().removeClass('active');
        $(this).parent().addClass('active');
        var type = $(this).val();
        $('#plan-' + type).show().siblings().hide();
    });

    $('#plan-categories li a').click(function(e) {
        e.preventDefault();
        $(this).parent().addClass('active').siblings().removeClass('active');
        var category = $(this).data('category');
        $('#' + category).show().siblings().hide();
        if (category == 'test-plan') {
            $('#plan-types').show();
        } else {
            $('#plan-types').hide();
        }
    });
    
    $('.plan-purchase').click(function(e) {
        var plan_id = $(this).data('plan_id');
        var plan_type = $(this).data('plan_type');
        var validation_session_plan_id = $(this).data('validation_session_plan_id');
        var title = $(this).data('title');
        var price = $(this).data('price');
        var actual_price = $(this).data('actual-price');
        var discount_price = $(this).data('discount');
        
        var modal = $('#planModal');
        modal.find('.ajax-loading').hide();
        modal.find('.form-msg').remove();
        modal.find('.error-msg').remove();
        modal.find('input, select').val('');
        modal.find('input[name="plan_id"]').val(plan_id);
        modal.find('input[name="plan_type"]').val(plan_type);
        modal.find('input[name="validation_session_plan_id"]').val(validation_session_plan_id);
        modal.find('#display-plan-title').html(title);
        
        modal.find('#display-plan-price').html(price);
        modal.find('#display-discount-price').html(discount_price);
        if (plan_type == '' || actual_price == 0) {
            modal.find('#display-plan-type').html('').parent().hide();
        } else {
            modal.find('#display-plan-type').html(plan_type.charAt(0).toUpperCase() + plan_type.slice(1) + 'ly').parent().show();
        }
        if (actual_price == 0) {
            modal.find('#card-details').hide();
        } else {
            modal.find('#card-details').show();
        }
        $('#planModal').modal('show');
    });

	<?php if (isset($_GET['category'])) { ?>
        var category = '<?php echo $_GET['category']; ?>';
        $('#plan-categories').find('[data-category="validation-session-plan"]').trigger('click');
    <?php } ?>
});
</script>

<?php include('common/footer.php');?>