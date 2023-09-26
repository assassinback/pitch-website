<?php include('../config.php');
$response = array();
$message = null;
$list = null;
$finish = false;
$redirect = null;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $page_id = $_POST["page_id"];
    $limit = 12;
    // var_dump($_POST);
    $test_id = null;
    $select = null;
    $join = null;
    $params = array();
    $cond = array();
    $testid=array();
    if (isset($_POST['filter_test']) ) {
        $test_id = $_POST['filter_test'];
        $test = $db->query('SELECT * FROM ' . $dbPrefix . 'test WHERE status = 1 AND id = ?', array($test_id));

        if ($result->num_rows() > 0) {
            // $test_id=3;
            $select = '(user_test_score.total_score) as test_score, (user_test_score.weightage) as weightage';
            $test_id = $_POST['filter_test'];
            $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id = ?) LEFT JOIN pitch_user_test_score_seperated as new_score on new_score.user_id=user.id';
            $vals=[];
            if(isset($_POST["speedster"]))
            {
                // $test_id[]=3;
                // $test_id[]=(int)3;
                
                $speed =$_POST['speedster'];
                if($speed!="" and $speed!="0")
                {
                    // $vals[]=3;
                    array_push($vals,3);
                    // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id IN (?))';
                    // $test_id=3;
                    $speed=explode('-',$speed);
                    // $cond[]=' ( user_test_score.total_score >=( ' .$speed. ' ) ) ';
                    $cond[]=' ( new_score.speed_score BETWEEN '.$speed[0].' AND '.$speed[1].' ) ';//AND user_test_score.test_id IN (3)
                    // var_dump($cond);
                    //AND user_test_score.test_id=3
                    // error_log("There is something wrong!", 0);
                    // echo "<script>alert('something');</script>";
                    // $cond[]=' ((SELECT pitch_user.id from pitch_user INNER JOIN pitch_user_test_score on pitch_user.id=pitch_user_test_score.user_id where pitch_user_test_score.test_id=3) IN ('.$speed.') ) ';
                    
                    // $cond[] = ' (user.id,pitch_user_test_score.id,pitch_user_test_score.user_id,pitch_user_test_score.total_score INNER JOIN pitch_user_test_score on user.id=pitch_user_test_score.user_id) ';
                    // $join=' ';
                    // $join='';
                }
                // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id = ?)';
            }
            if(isset($_POST["agility"]) )//and isset($_POST["speedster"])
            {
                $agility =$_POST['agility'];
                if($agility!="" and $agility!="0")
                {
                    // $vals[]=5;
                    array_push($vals,5);
                    // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id IN (?))';
                    // $test_id=5;
                    $agility=explode('-',$agility);
                    
                    $cond[]=' ( new_score.agility_score BETWEEN '.$agility[0].' AND '.$agility[1].'  ) ';//AND user_test_score.test_id = 5
                    //AND user_test_score.test_id IN(5,3)
                    // $cond[]=' ( user_test_score.test_id IN (5)) ';
                    // $cond[]=' ( user_test_score.total_score >=( ' .$speed. ' ) ) ';
                    // $cond[] = ' (user_test_score.total_score ='. $agility . ') ';
                    // $cond[]=' ( user_test_score.total_score >= '.$agility.') ';
                    // error_log("There is something wrong!", 0);
                    // echo "<script>alert('something');</script>";
                    // $cond[]=' ((SELECT pitch_user.id from pitch_user INNER JOIN pitch_user_test_score on pitch_user.id=pitch_user_test_score.user_id where pitch_user_test_score.test_id=3) IN ('.$speed.') ) ';
                    
                    // $cond[] = ' (user.id,pitch_user_test_score.id,pitch_user_test_score.user_id,pitch_user_test_score.total_score INNER JOIN pitch_user_test_score on user.id=pitch_user_test_score.user_id) ';
                    // $join=' ';
                    // $join='';
                }
                // $test_id[]=5;
                // $test_id[]=(int)5;
                // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id IN (?))';
                // $params[]=5;
                // $_POST["speedster"]=$_POST["agility"];
                // array_push($test_id, 5);
                // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id = ?)';
            }
            if(isset($_POST["jump"]))
            {
                
                $jump =$_POST['jump'];
                // echo $jump;
                // $jump=explode('-',$jump);
                // var_dump($jump);
                // $jump=3;
                if($jump!="" and $jump!="0")
                {
                    // $vals[]=1;
                    array_push($vals,1);
                    // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id IN (?))';
                    // $test_id=1;
                    // echo $jump; 
                    // $cond[]=' ( user_test_score.total_score >=( ' .$speed. ' ) ) ';
                    $jump=explode('-',$jump);
                    // var_dump($jump);
                    $cond[]=' ( new_score.jump_score BETWEEN '.$jump[0].' AND '.$jump[1].') ';//AND user_test_score.test_id =1
                    // AND user_test_score.test_id =1
                    
                    // $cond[]=' ( user_test_score.total_score >=( ' .$speed. ' ) ) ';
                    // $cond[] = ' (user_test_score.total_score = ' . $jump . ' ) ';
                    // $cond[]=' ( user_test_score.total_score >= '.$jump.') ';
                    // error_log("There is something wrong!", 0);
                    // echo "<script>alert('something');</script>";
                    // $cond[]=' ((SELECT pitch_user.id from pitch_user INNER JOIN pitch_user_test_score on pitch_user.id=pitch_user_test_score.user_id where pitch_user_test_score.test_id=3) IN ('.$speed.') ) ';
                    
                    // $cond[] = ' (user.id,pitch_user_test_score.id,pitch_user_test_score.user_id,pitch_user_test_score.total_score INNER JOIN pitch_user_test_score on user.id=pitch_user_test_score.user_id) ';
                    // $join=' ';
                    // $join='';
                }
                // $test_id[]=5;
                // $test_id[]=(int)5;
                // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id IN (?))';
                // $params[]=5;
                // $_POST["speedster"]=$_POST["agility"];
                // array_push($test_id, 5);
                // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id = ?)';
            }
            if(isset($_POST["aerobic"]))
            {
                
                $aerobic =$_POST['aerobic'];
                // $jump=3;
                if( $aerobic!="" and $aerobic!="0")
                {
                    // $vals[]=2;
                    array_push($vals,2);
                    // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id IN (?))';
                    // $test_id=2;
                    $aerobic=explode('-',$aerobic);
                    // $cond[]=' ( user_test_score.total_score >=( ' .$speed. ' ) ) ';
                    // $cond[] = ' (user_test_score.total_score = ' . $aerobic . ' ) ';
                    $cond[]=' ( new_score.aerobic_score BETWEEN '.$aerobic[0].' AND '.$aerobic[1].' ) ';//AND user_test_score.test_id IN (2)
                    // $cond[]=' ( user_test_score.total_score >=( ' .$speed. ' ) ) ';
                    // $cond[]=' ( user_test_score.total_score >= '.$aerobic.') ';
                    // error_log("There is something wrong!", 0);
                    // echo "<script>alert('something');</script>";
                    // $cond[]=' ((SELECT pitch_user.id from pitch_user INNER JOIN pitch_user_test_score on pitch_user.id=pitch_user_test_score.user_id where pitch_user_test_score.test_id=3) IN ('.$speed.') ) ';
                    
                    // $cond[] = ' (user.id,pitch_user_test_score.id,pitch_user_test_score.user_id,pitch_user_test_score.total_score INNER JOIN pitch_user_test_score on user.id=pitch_user_test_score.user_id) ';
                    // $join=' ';
                    // $join='';
                }
                // $test_id[]=5;
                // $test_id[]=(int)5;
                // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id IN (?))';
                // $params[]=5;
                // $_POST["speedster"]=$_POST["agility"];
                // array_push($test_id, 5);
                // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id = ?)';
            }
            if(isset($_POST["squat"]))
            {
                
                $squat =$_POST['squat'];
                // $jump=3;
                if( $squat!="" and $squat!="0")
                {
                    // $vals[]=8;
                    array_push($vals,8);
                    // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id IN (?))';
                    // $test_id=8;
                    $squat=explode('-',$squat);
                    // $cond[]=' ( user_test_score.total_score >=( ' .$speed. ' ) ) ';
                    // $cond[]=' ( user_test_score.total_score = '.$squat.') ';
                    $cond[]=' ( new_score.squat_score BETWEEN '.$squat[0].' AND '.$squat[1].' ) ';//AND user_test_score.test_id IN (8)
                    // error_log("There is something wrong!", 0);
                    // echo "<script>alert('something');</script>";
                    // $cond[]=' ((SELECT pitch_user.id from pitch_user INNER JOIN pitch_user_test_score on pitch_user.id=pitch_user_test_score.user_id where pitch_user_test_score.test_id=3) IN ('.$speed.') ) ';
                    
                    // $cond[] = ' (user.id,pitch_user_test_score.id,pitch_user_test_score.user_id,pitch_user_test_score.total_score INNER JOIN pitch_user_test_score on user.id=pitch_user_test_score.user_id) ';
                    // $join=' ';
                    // $join='';
                }
                // $test_id[]=5;
                // $test_id[]=(int)5;
                // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id IN (?))';
                // $params[]=5;
                // $_POST["speedster"]=$_POST["agility"];
                // array_push($test_id, 5);
                // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id = ?)';
            }
            if(isset($_POST['bench']))
            {
                
                $bench =$_POST['bench'];
                // $jump=3;
                if( $bench!="" and $bench!="0")
                {
                    // $vals[]=9;
                    array_push($vals,9);
                    // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id IN (?))';
                    // $test_id=9;
                    $bench=explode('-',$bench); 
                    // $cond[]=' ( user_test_score.total_score >=( ' .$speed. ' ) ) ';
                    // $cond[]=' ( user_test_score.total_score = '.$bench.') ';
                    $cond[]=' ( new_score.bench_score BETWEEN '.$bench[0].' AND '.$bench[1].' ) ';//AND user_test_score.test_id IN (9)
                    // error_log("There is something wrong!", 0);
                    // echo "<script>alert('something');</script>";
                    // $cond[]=' ((SELECT pitch_user.id from pitch_user INNER JOIN pitch_user_test_score on pitch_user.id=pitch_user_test_score.user_id where pitch_user_test_score.test_id=3) IN ('.$speed.') ) ';
                    
                    // $cond[] = ' (user.id,pitch_user_test_score.id,pitch_user_test_score.user_id,pitch_user_test_score.total_score INNER JOIN pitch_user_test_score on user.id=pitch_user_test_score.user_id) ';
                    // $join=' ';
                    // $join='';
                }
                // $test_id[]=5;
                // $test_id[]=(int)5;
                // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id IN (?))';
                // $params[]=5;
                // $_POST["speedster"]=$_POST["agility"];
                // array_push($test_id, 5);
                // $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id = ?)';
            }
            else{
                
            }
            // $test_id="";
            if(sizeof($vals)>0)
            $test_id=(string)$vals[0];
            $m=1;
            while($m<sizeof($vals))
            {
                $test_id.=','.(string)$vals[$m];
                $m++;
                
            }
            // $cond[]=' ( user_test_score.test_id IN ('.implode(",",$vals).' ) ';
            $join = 'LEFT JOIN ' . $dbPrefix . 'user_test_score as user_test_score ON (user_test_score.user_id = user.id AND user_test_score.test_id IN (?)) LEFT JOIN pitch_user_test_score_seperated as new_score on new_score.user_id=user.id';
            $params[] = $test_id;
            // echo $join;
            // if(isset($_POST["speedster"]) or isset($_POST["agility"]))
            // {
                
            //     // $test_id=3;
            //     // $test_id = $_POST['filter_test'];
            //     $test_id=implode(',',$test_id);
            //     // $test_id=[3,5];
            //     $loop=0;
                
                ?>
                   
                <?php
                
                // while($loop<=len($test_id))
                // {
                    // $join=$join.',?';
                    // $params[] = $test_id[$loop];
                    // $loop=$loop+1;
                // }
                // $join.='))';
            // }
            // else{
            //     $test_id=null;
            
            //     $params[] = $test_id;
            // }
        } else {
            $test_id = null;
        }
    }

    if (isset($_POST['search']) && $_POST['search'] != "") {
        $cond[] = ' (user.first_name like ? OR user.last_name like ?) ';
        $params[] = '%' . $_POST['search'] . '%';
        $params[] = '%' . $_POST['search'] . '%';
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
    
    if (isset($_POST['heightOption'])) {
        $heights =$_POST['heightOption'];
        if($heights!=0)
        {
            // $heights=explode("-",$heights);
            $cond[] = ' (user.height IN ( '.$heights.' ) ) ';
        }
        // echo "<script>alert('something');</script>";
    }
    if (isset($_POST['weight'])) {

        $weights =$_POST['weight'];
        if($weights!=0)
        {
            // $weights=explode("-",$weights);
            $cond[] = ' (user.weight IN ( '.$weights.' ) ) ';
        //  $cond[] = ' (user.weight IN (' . $weights . ') ) ';
        }
        // echo "<script>alert('something');</script>";
        // echo "<script>alert('something');</script>";
    }
    if (isset($_POST['foot'])) {
        
        $foot =$_POST['foot'];
        // echo "<script>alert('$foot');</script>";
        if($foot!="0" and $foot!="")
        {
            // echo "<script>alert('$foot');</script>";
        $cond[] = ' (user.prefered_foot IN ("' . $foot . '") ) ';
        }
        
    }
    if (isset($_POST['age'])) {

        $ages =$_POST['age'];
        if($ages!=0 and $ages!="")
        {
            // $ages=explode("-",$ages);
            $ages=date("Y")-(int)$ages;
            $cond[] = ' (YEAR(user.date_of_birth) IN ( ' . $ages . ' ) ) ';
        // $cond[] = ' (YEAR(user.date_of_birth) IN (' . $ages . ') ) ';
        }
        
        // echo "<script>alert('something');</script>";
    }
    if (isset($_POST['speedster'])) {

        // $speed =$_POST['speedster'];
        // if($speed!=0 and $speed!="")
        // {
        //     // $cond[]=' ( user_test_score.total_score >=( ' .$speed. ' ) ) ';
        //     $cond[]=' ( user_test_score.total_score = '.$speed.') ';
        //     // error_log("There is something wrong!", 0);
        //     // echo "<script>alert('something');</script>";
        //     // $cond[]=' ((SELECT pitch_user.id from pitch_user INNER JOIN pitch_user_test_score on pitch_user.id=pitch_user_test_score.user_id where pitch_user_test_score.test_id=3) IN ('.$speed.') ) ';
            
        //     // $cond[] = ' (user.id,pitch_user_test_score.id,pitch_user_test_score.user_id,pitch_user_test_score.total_score INNER JOIN pitch_user_test_score on user.id=pitch_user_test_score.user_id) ';
        //     // $join=' ';
        //     // $join='';
        // }
        // echo "<script>alert('something');</script>";
    }
    if (isset($_POST['agility'])) {

        $agility =$_POST['agility'];
        if($agility!=0 and $agility!="")
        {
            // $cond[]=' ( user_test_score.total_score = '.$agility.') ';
            // $cond[]=' (SELECT pitch_user_test_score.user_id from pitch_user as user1 INNER JOIN pitch_user_test_score on user1.id=pitch_user_test_score.user_id where pitch_user_test_score.test_id=5 AND pitch_user_test_score.total_score = CAST('.$agility.' as DECIMAL) LIMIT 1) IN '.$agility.') ';
            // echo "<script>alert('something');</script>";
            // $cond[]=' ((SELECT pitch_user.id from pitch_user INNER JOIN pitch_user_test_score on pitch_user.id=pitch_user_test_score.user_id where pitch_user_test_score.test_id=3) IN ('.$speed.') ) ';
            
            // $cond[] = ' (user.id,pitch_user_test_score.id,pitch_user_test_score.user_id,pitch_user_test_score.total_score INNER JOIN pitch_user_test_score on user.id=pitch_user_test_score.user_id) ';
            // $join=' ';
            // $join='';
        }
        // echo "<script>alert('something');</script>";
    }
    if (isset($_POST['validated'])) {
        // echo "<script>alert('something');</script>";
        $validated =$_POST['validated'];
        // (($player['score_validated_date']) ? (($player['score_validated_by'] != 0) ? 'Coach Validated' : 'Validated') : 'Not Validated')
        // if($validated=="Validated")
        // {
        //     echo "<script>alert('something');</script>";
        //         // $cond[] = ' user.score_validated_by IN (' . 0 . ') ) '; 
            
        //     // $cond[] = ' user.score_validated_date IN (' . NULL . ') ) ';
            
        // }
        // echo "here";
        
        if($validated=="Validated")
        {
            $x=0;
            $cond[] = ' (user.score_validated_by IN ('.$x.') AND user.score_validated_date is not NULL)'; 
        }
        if($validated=="coachValidated")
        {
            $x=1;
            $cond[] = ' (user.score_validated_by IN ('.$x.') AND user.score_validated_date is not NULL)'; 
        }
        if($validated=="notValidated")
        {
            $x=1;
            $cond[] = ' (user.score_validated_by IS NULL AND user.score_validated_date is NULL)'; 
        }
        // if($validated=="coachValidated")
        // {
        //     $cond[] = ' user.score_validated_by IN (' . 1 . ') ) ';
        // }
        // else
        // {
        //     $cond[] = ' user.score_validated_date IN (' . NULL . ') ) ';
        // }
        // echo "<script>alert('something');</script>";
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

    $sql .= 'WHERE user.status = 1 AND hidden = 0 AND user.user_type = 1 ' . $cond . ' ORDER BY ' . $order;
    
    $start = ($page_id - 1) * $limit;
    $sql .= ' LIMIT ' . $start . ','. $limit;
    ?>
    
    <?php
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
    // echo $sql;
    foreach ($players as $player) {
        
        
        $profile_link = getLink('profile.php', 'profile_id='.$player['id']);
        if(isset($_POST["speedster"]) or isset($_POST["agility"]) or isset($_POST["jump"]) or isset($_POST["aerobic"]) or isset($_POST["squat"]) or isset($_POST['bench']))
        {
            $profile_link = getLink('profile.php', 'tab=score&'.'profile_id='.$player['id']);
        }
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
        
        $score = (($test_id) ? $player['weightage'] : $player['overall_score']);
        
        $list .= '<li class="col-xs-12 col-sm-4">
                    <a href="javascript:" class="compare compare-icon ' . $added . '" data-player="' . $player['id'] . '"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>
                    <a href="' . $profile_link . '">

                        <div class="p_car_plyr ' . (($player['score_validated_date'] != null) ? (($player['score_validated_by'] != 0) ? 'coach-validated' : 'validated') : 'not-validated') . '" style="background-image: url(' . playerImageCheck($player['photo']) .');">
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
                            <div class="pcp_value">' . (($score) ? $score : 0) . '</div>
                            <div class="pcp_validation ' . (($player['score_validated_date'] != null) ? (($player['score_validated_by'] != 0) ? 'coach-validated' : 'validated') : '') . '">
                                '. (($player['score_validated_date']) ? (($player['score_validated_by'] != 0) ? 'Coach Validated' : 'Validated') : 'Not Validated') . '
                            </div>
                        </div>
                    </a>
                </li>
                ';
                
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