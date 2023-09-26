<?php include('config.php');

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'position WHERE status = 1 ORDER BY name ASC', array());

$positions = array();
if ($result->num_rows() > 0) {
    $rows = $result->result_array();  
    foreach ($rows as $row) {
        $positions[$row['id']] = $row['name'];
    }
}

$limit = 12;

$test_id = null;
$select = null;
$join = null;
$params = array();
$cond = array();

if (isset($_GET['test_id'])) {
    $test_id = $_GET['test_id'];
    $test = $db->query('SELECT * FROM ' . $dbPrefix . 'test WHERE status = 1 AND id = ?', array($test_id));
    $testInfo = $test->row_array();

    if ($result->num_rows() > 0) {
        $select = '(user_test_score.total_score) as test_score, (user_test_score.weightage) as weightage';
        $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id = ?)';
        $params[] = $test_id;
    } else {
        $test_id = null;
    }
}

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = utf8_decode($_GET['search']); 
    $search = trim($search);
	$pos = strrpos($search, " ");
	if($pos){
		$search_cond = array();
		$getval = explode(' ',$search);
		foreach($getval as $keyword){
			$search_cond[] = ' user.first_name like ? OR user.last_name like ? ';
			$params[] = '%' . $keyword . '%';
			$params[] = '%' . $keyword . '%';
		}
		$cond_str = implode(" OR " , $search_cond);
		$cond[] = ' (' . $cond_str . ') ';
	}else{
		$cond[] = ' (user.first_name like ? OR user.last_name like ?) ';
		$params[] = '%' . $search . '%';
		$params[] = '%' . $search . '%';
	}
} else {
    $search = '';
}

if (checkLogin()) {
    //$cond[] = ' (user.id != ?) ';
    //$params[] = $_SESSION['id'];
}

if (count($cond) > 0) {
    $cond = ' AND ' .implode(' AND ', $cond);
} else {
    $cond = '';
}

if (isset($_GET['sort_order'])) {
    $sort_order = $_GET['sort_order'];
    $sortOrder = explode('-', $sort_order);
    if ($sortOrder[0] == 'name') {
        $order = 'user.first_name ' . $sortOrder[1] . ', luser.ast_name ' . $sortOrder[1] . ', user.overall_score DESC';
    } else {
        if ($test_id) {
            $order = 'weightage ' . $sortOrder[1] . ', user.overall_score DESC, user.first_name ASC, user.last_name ASC';
        } else {
            $order = 'user.overall_score ' . $sortOrder[1] . ', user.first_name ASC, user.last_name ASC';
        }
    }
} else if ($test_id) {
    $sort_order = '';
    $order = 'weightage DESC, overall_score DESC, first_name ASC, last_name ASC';
} else {
    $sort_order = '';
    $order = 'overall_score DESC, first_name ASC, last_name ASC';
}

$sql = 'SELECT user.*';

if ($select) {
    $sql .= ', ' . $select . ' ' ; 
}

$sql .= 'FROM ' . $dbPrefix . 'user as user';

if ($join) {
    $sql .= ' ' . $join . ' ' ; 
}

$sql .= ' WHERE user.status = 1 AND user.user_type != 1 ';


if(isset($_SESSION['id'])){
    $plan = $db->query('SELECT test_plan.*, user_test_plan.* FROM ' . $dbPrefix . 'user_test_plan as user_test_plan INNER JOIN ' . $dbPrefix . 'test_plan as test_plan ON (test_plan.id = user_test_plan.test_plan_id) WHERE user_id = ?', array($_SESSION['id']));
    $planInfo = $plan->row_array();
    if($planInfo['test_plan_id'] > 1){ 
     
    } else {
        $sql .= ' AND user.hidden != 1 ';
    }
} else {
    $sql .= ' AND user.hidden != 1 ';
}

$sql .= $cond . ' ORDER BY ' . $order . ' LIMIT ' . $limit;


$result = $db->query($sql, $params);
//echo $db->last_query();
$players = array();
if ($result->num_rows() != 0) {
    $players = $result->result_array();
}
if (isset($_COOKIE['compare'])) {
    $compare = explode('-', $_COOKIE['compare']);
} else {
    $compare = array();
}

$document['style'][] = 'jquery-ui.css';
$document['script'][] = 'jquery-ui.js';
$document['script'][] = 'coach-scout.js';
    
$page_title = 'Coaches/Scouts';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('coaches_scouts.php')));
include('common/header.php');
?>

<div class="stj_players_wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-3 stj_filter">
                <form name="form-filter-players" id="form-filter-players" action="<?php echo getLink('phpajax/coaches_scouts.php', '', true); ?>" method="post" >
                <input type="hidden" name="filter_order" id="filter_order" value="<?php echo $sort_order; ?>">
                <input type="hidden" name="filter_test" id="filter_test" value="<?php echo $test_id; ?>">
                    <h2>Filter</h2>
                    <div class="stj_fltr_srch">
                        <input class="txt_hdr" placeholder="Search" type="text" name="search" id="search" value="<?php echo $search; ?>">
                        <input class="btn_hdr" value="go" type="submit">
                    </div>
                    <?php /*
                    <div class="stj_drag">
                        <h3>Score</h3>
                        <div class="stj_range">
                            <div id="slider-range"><input type="hidden" name="score_range" id="score_range" value="0-100"></div>
                            <div class="stj_rg_val"><span id="slider-value-0">0</span><span class="stval_right" id="slider-value-1">100</span></div>
                        </div>
                    </div> */ ?>
                    <?php /*
                    <?php if (count($positions) > 0) { ?>
                    <div class="stj_drag">
                        <h3>Position</h3>
                        <ul class="chk_ul">
                            <?php foreach ($positions as $position_id => $position_name) { ?>
                            <li>
                                <label class="lb_fltr"><input type="checkbox" name="position[]" value="<?php echo $position_id; ?>" class="lb_chk"/><?php echo $position_name; ?></label>
                            </li>
                            <?php } ?>
                        </ul>
                        <input type="submit" class="btn_fltr" value="Apply" />
                    </div>
                    <?php }?>
                    <?php */ ?>
                </form>
            </div>
            <div class="col-xs-12 col-md-9 stj_listing">
                <h2>Coaches/Scouts profiles <?php if ($test_id) { echo '<span> (' . $testInfo['title'] . ')</span>'; } ?></h2>
                <div class="stj_sort">
                    <a class="a_vcl hide" href="javascript:">View Compare List</a>
                    <div class="sort_sel">
                        <label>Sort By :</label>
                        <form name="form-sort-players" id="form-sort-players" action="" method="get" >
                            <?php 
                            
                                $sort_options = array(
                                                    //'score-desc' => 'Score - High to Low',
                                                    //'score-asc' => 'Score - Low to High',
                                                    'name-asc' => 'Name - Asc to Desc',
                                                    'name-desc' => 'Name - Desc to Asc'
                                                    );
                            
                            ?>
                            <select name="sort_order" id="sort_order" >
                                <?php foreach ($sort_options as $sort_option => $sort_label) { ?>
                                    <option value="<?php echo $sort_option; ?>" <?php if($sort_option == $sort_order) { ?> selected <?php } ?>><?php echo $sort_label; ?></option>
                                <?php } ?>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="stj_list_dv">
                    <ul id="players-list" class="coach-scout-list">
                        <?php foreach ($players as $player) { ?>
                        <li class="col-xs-12 col-sm-4">
                            <a href="javascript:" class="compare compare-icon <?php if (in_array($player['id'], $compare)) { ?> added <?php } ?>" data-player="<?php echo $player['id']; ?>"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>
                            <a href="<?php echo getLink('profile.php', 'profile_id='.$player['id']); ?>">
                                <div class="p_car_plyr" style="background-image: url(<?= playerImageCheck($player['photo']) ?>);">
                                    <?php 
                                        // echo getUserProfileImage($player['photo']); 
                                    ?>
                                    <div class="pcp_con">
                                        <div class="pcp_con_inn">
                                            <h3><?php echo $player['first_name']; ?><span><?php echo $player['last_name']; ?></span></h3>
                                            <?php if(trim($player['currently_working_for']) != NULL) { ?>
                                                <h4>Coach (<?php echo $player['currently_working_for']; ?>)</h4>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php /*
                                    <div class="pcp_value">
                                        <?php $score = ($test_id) ? $player['weightage'] : $player['overall_score']; ?>
                                        <?php echo ($score) ? $score : 0; ?>
                                    </div>
                                    */ ?>
                                </div>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <input type="button" class="load-more" value="Load More">
                <div class="stj_loader loader" style="display: none;">Loading...</div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($){
    
    $( "#slider-range" ).slider({
        range: true,
        min: 0,
        max: 100,
        values: [ 0, 100 ],
        slide: function( event, ui ) {
            
            var val1 = ui.values[ 0 ];
            var val2 = ui.values[ 1 ];
            $( "#score_range" ).val( val1 + "-" + val2 );
            
            var sliderWidth = $(this).width();
            var range1 = parseFloat($(this).find('.ui-slider-handle:first').css('left'));
            var range2 = parseFloat($(this).find('.ui-slider-handle:last').css('left'));
            
            if (val1 > 0) {
                $('#slider-value-0').html(val1).css('margin-left', (range1 - 3) + 'px');
            } else {
                $('#slider-value-0').html(val1).removeAttr('style');
            }
            
            if (val2 < 100) {
                $('#slider-value-1').html(val2).css('margin-right', (sliderWidth - (range2 + 5)) + 'px');
            } else {
                $('#slider-value-1').html(val2).removeAttr('style');
            }
            
        }
    });
        
    $('.lb_fltr input:checkbox').on('click', function(){
        if($(this).is(":checked")) {
            $(this).parent().addClass("active");
        } else {
            $(this).parent().removeClass("active");
        }
    });
});
</script>

<?php include('common/footer.php');?>