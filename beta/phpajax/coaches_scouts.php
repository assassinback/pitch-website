<?php include('../config.php');

$response = array();
$message = null;
$list = null;
$finish = false;
$redirect = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $page_id = (int)$_POST['page_id'];
    $limit = 12;
    
    $test_id = null;
    $select = null;
    $join = null;
    $params = array();
    $cond = array();
    
    if (isset($_POST['filter_test'])) {
        $test_id = $_POST['filter_test'];
        $test = $db->query('SELECT * FROM ' . $dbPrefix . 'test WHERE status = 1 AND id = ?', array($test_id));

        if ($result->num_rows() > 0) {
            $select = '(user_test_score.total_score) as test_score, (user_test_score.weightage) as weightage';
            $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id = ?)';
            $params[] = $test_id;
        } else {
            $test_id = null;
        }
    }

    if (isset($_POST['search']) && $_POST['search'] != "") {
        /* $cond[] = ' (user.first_name like ? OR user.last_name like ?) ';
        $params[] = '%' . $_POST['search'] . '%';
        $params[] = '%' . $_POST['search'] . '%'; */
		
		$search = utf8_decode($_POST['search']); 
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
    }
    
    if (isset($_POST['score_range']) && $_POST['score_range'] != "" && $_POST['score_range'] != "0-100") {
        $score_range = explode('-', $_POST['score_range']);
        if ($test_id) {
            $cond[] = ' (weightage >= ? AND weightage <= ?) ';
        } else {
            $cond[] = ' (user.overall_score >= ? AND user.overall_score <= ?) ';
        }
        $params[] = $score_range[0];
        $params[] = $score_range[1];
    }
    
    if (isset($_POST['position'])) {
        $position = implode(',', $_POST['position']);
        $cond[] = ' (user.1st_player_position IN (' . $position . ') OR user.2nd_player_position IN (' . $position . ') OR user.3rd_player_position IN (' . $position . ')) ';
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
    
    if (isset($_POST['filter_order']) && $_POST['filter_order'] != "") {
        $sort_order = $_POST['filter_order'];
        $sortOrder = explode('-', $sort_order);
        if ($sortOrder[0] == 'name') {
            $order = 'user.first_name ' . $sortOrder[1] . ', user.last_name ' . $sortOrder[1] . ', user.overall_score DESC';
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
        $order = 'user.overall_score DESC, user.first_name ASC, user.last_name ASC';
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

    $sql .= $cond . ' ORDER BY ' . $order;
    
    $start = ($page_id - 1) * $limit;
    $sql .= ' LIMIT ' . $start . ','. $limit;
    
    $result = $db->query($sql, $params);
    //echo $db->last_query();

    $players = array();
    if ($result->num_rows() > 0) {
        $players = $result->result_array();
    } else {
        $finish = true;
    }
    
    if (isset($_COOKIE['compare'])) {
        $compare = explode('-', $_COOKIE['compare']);
    } else {
        $compare = array();
    }
    
    foreach ($players as $player) {
        
        $profile_link = getLink('profile.php', 'profile_id='.$player['id']);
        $photo = getUserProfileImage($player['photo']);
        
        if(isset($player['currently_working_for'])) {
            $club = '<h4>Coach (' . $player['currently_working_for'] . ')</h4>';
        } else {
            $club = null;
        }
        
        if (in_array($player['id'], $compare)) {
            $added = 'added';
        } else {
            $added = '';
        }
        
        $score = (($test_id) ? $player['weightage'] : $player['overall_score']);
        
        $list .= '<li class="col-xs-12 col-sm-4">
                    <a href="javascript:" class="compare compare-icon ' . $added . '" data-player="' . $player['id'] . '"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>
                    <a href="' . $profile_link . '">
                    <div class="p_car_plyr ' . (($player['score_validated_date'] != null) ? '' : '') . '" style="background-image: url(' . playerImageCheck($player['photo']) .');">
                            <div class="pcp_con">
                                <div class="pcp_con_inn">
                                    <h3>' . $player['first_name'] . '<span>' . $player['last_name'] . '</span></h3>
                                    ' . $club . '
                                </div>
                            </div>
                        </div>
                    </a>
                </li>';
    }
}

$response = array(
				'list' => $list,
				'finish' => $finish,
				'message' => $message,
				'redirect' => $redirect,
			);
header("Content-type: application/json; charset=utf-8");
echo json_encode($response);