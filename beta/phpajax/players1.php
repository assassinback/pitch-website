<?php include('../config.php');

$response = array();
$message = null;
$list = null;
$finish = false;
$redirect = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $page_id = (int)$_POST['page_id'];
    $limit = 12;
    
    $params = array();
    $cond = array();
    if (isset($_POST['search']) && $_POST['search'] != "") {
        $cond[] = ' (first_name like ? OR last_name like ?) ';
        $params[] = '%' . $_POST['search'] . '%';
        $params[] = '%' . $_POST['search'] . '%';
    }
    
    if (isset($_POST['score_range']) && $_POST['score_range'] != "" && $_POST['score_range'] != "0-100") {
        $score_range = explode('-', $_POST['score_range']);
        $cond[] = ' (overall_score >= ? AND overall_score <= ?) ';
        $params[] = $score_range[0];
        $params[] = $score_range[1];
    }
    
    if (isset($_POST['position'])) {
        $position = implode(',', $_POST['position']);
        $cond[] = ' (1st_player_position IN (' . $position . ') OR 2nd_player_position IN (' . $position . ') OR 3rd_player_position IN (' . $position . ')) ';
    }
    
    if (count($cond) > 0) {
        $cond = ' AND ' .implode(' AND ', $cond);
    } else {
        $cond = '';
    }
    
    
    $sql = 'SELECT * FROM ' . $dbPrefix . 'user WHERE status = 1 AND user_type = 1 ' . $cond;
    
    if (isset($_POST['filter_order']) && $_POST['filter_order'] != "") {
        $sort_order = $_POST['filter_order'];
        $sortOrder = explode('-', $sort_order);
        if ($sortOrder[0] == 'name') {
            $order = 'first_name ' . $sortOrder[1] . ', last_name ' . $sortOrder[1] . ', overall_score DESC';
        } else {
            $order = 'overall_score ' . $sortOrder[1] . ', first_name ASC, last_name ASC';
        }
    } else {
        $sort_order = '';
        $order = 'overall_score DESC, first_name ASC, last_name ASC';
    }
    
    $sql .= ' ORDER BY ' . $order;
    
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
        
        if(isset($player['team_id'])) {
            $club = '<h4>' . getClub($player['team_id']) . '</h4>';
        } else {
            $club = null;
        }
        
        if (in_array($player['id'], $compare)) {
            $added = 'added';
        } else {
            $added = '';
        }
        
        $list .= '<li class="col-xs-12 col-sm-4">
                    <a href="javascript:" class="compare compare-icon ' . $added . '" data-player="' . $player['id'] . '"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>
                    <a href="' . $profile_link . '">
                        <div class="p_car_plyr">
                            ' . $photo . '
                            <div class="pcp_con_hvr">
                                <div class="pcp_con_hvr_inner">
                                    <label>' . getPosition($player['1st_player_position']) . '</label>
                                    <span>1st Playing Position </span>
                                </div>
                                <div class="pcp_con_hvr_inner">
                                    <label>' . getPosition($player['2nd_player_position']) . '</label>
                                    <span>2nd Playing Position</span>
                                </div>
                                <div class="pcp_con_hvr_inner">
                                    <label>' . getPosition($player['3rd_player_position']) . '</label>
                                    <span>3rd Playing Position</span>
                                </div>
                            </div>
                            <div class="pcp_con">
                                <div class="pcp_con_inn">
                                    <h3>' . $player['first_name'] . '<span>' . $player['last_name'] . '</span></h3>
                                    ' . $club . '
                                </div>
                            </div>
                            <div class="pcp_value">' . $player['overall_score'] . '</div>
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