 <?php include('config.php');

$result = $db->query('SELECT * FROM ' . $dbPrefix . 'position WHERE status = 1 ORDER BY name ASC', array());
$resultHeight = $db->query('SELECT DISTINCT height FROM ' . $dbPrefix . 'user WHERE user_type = 1 ORDER BY height ASC ', array());
$resultWeight = $db->query('SELECT DISTINCT `weight` FROM ' . $dbPrefix . 'user WHERE user_type = 1 ORDER BY `weight` ASC ', array());
$resultAge = $db->query('SELECT DISTINCT YEAR(`date_of_birth`) as date_of_birth FROM ' . $dbPrefix . 'user WHERE user_type = 1 ORDER BY `date_of_birth` ASC ', array());
$resultFoot = $db->query('SELECT DISTINCT prefered_foot FROM ' . $dbPrefix . 'user WHERE user_type = 1 ORDER BY prefered_foot ASC ', array());
$resultSpeed = $db->query('SELECT DISTINCT user1.id,test.id,test.user_id,test.total_score from pitch_user as user1 INNER JOIN pitch_user_test_score as test on user1.id=test.user_id where test.test_id=3 ORDER BY total_score ASC ', array());
$resultAgility = $db->query('SELECT DISTINCT user1.id,test.id,test.user_id,test.total_score from pitch_user as user1 INNER JOIN pitch_user_test_score as test on user1.id=test.user_id where test.test_id=5 ORDER BY total_score ASC ', array());
$resultJump = $db->query('SELECT DISTINCT user1.id,test.id,test.user_id,test.total_score from pitch_user as user1 INNER JOIN pitch_user_test_score as test on user1.id=test.user_id where test.test_id=1 ORDER BY total_score ASC ', array());
$resultAerobic = $db->query('SELECT DISTINCT user1.id,test.id,test.user_id,test.total_score from pitch_user as user1 INNER JOIN pitch_user_test_score as test on user1.id=test.user_id where test.test_id=2 ORDER BY total_score ASC ', array());
$resultSquat = $db->query('SELECT DISTINCT user1.id,test.id,test.user_id,test.total_score from pitch_user as user1 INNER JOIN pitch_user_test_score as test on user1.id=test.user_id where test.test_id=8 ORDER BY total_score ASC ', array());
$resultBench = $db->query('SELECT DISTINCT user1.id,test.id,test.user_id,test.total_score from pitch_user as user1 INNER JOIN pitch_user_test_score as test on user1.id=test.user_id where test.test_id=9 ORDER BY total_score ASC ', array());

$height = array();
$positions = array();
$weight= array();
$age= array();
$foot=array();
$speed= array();
$agility=array();
$jump=array();
$aerobic=array();
$squat=array();
$bench=array();
if ($resultJump->num_rows() > 0) {
    $rows = $resultJump->result_array();  
    foreach ($rows as $row) {
        $jump[$row['total_score']] = $row['total_score'];
    }
}
if ($resultBench->num_rows() > 0) {
    $rows = $resultBench->result_array();  
    foreach ($rows as $row) {
        $bench[$row['total_score']] = $row['total_score'];
    }
}
if ($resultAerobic->num_rows() > 0) {
    $rows = $resultAerobic->result_array();  
    foreach ($rows as $row) {
        $aerobic[$row['total_score']] = $row['total_score'];
    }
}
if ($resultAgility->num_rows() > 0) {
    $rows = $resultAgility->result_array();  
    foreach ($rows as $row) {
        $agility[$row['total_score']] = $row['total_score'];
    }
}
if ($resultSpeed->num_rows() > 0) {
    $rows = $resultSpeed->result_array();  
    foreach ($rows as $row) {
        $speed[$row['total_score']] = $row['total_score'];
    }
} 
if ($resultFoot->num_rows() > 0) {
    $rows = $resultFoot->result_array();  
    foreach ($rows as $row) {
        $foot[$row['prefered_foot']] = $row['prefered_foot'];
    }
}
if ($resultAge->num_rows() > 0) {
    $rows = $resultAge->result_array();  
    foreach ($rows as $row) {
        $age[$row['date_of_birth']] = $row['date_of_birth'];
    }
}
if ($resultWeight->num_rows() > 0) {
    $rows = $resultWeight->result_array();  
    foreach ($rows as $row) {
        $weight[$row['weight']] = $row['weight'];
    }
}
if ($resultHeight->num_rows() > 0) {
    $rows = $resultHeight->result_array();  
    foreach ($rows as $row) {
        $height[$row['height']] = $row['height'];
    }
}
if ($resultSquat->num_rows() > 0) {
    $rows = $resultSquat->result_array();  
    foreach ($rows as $row) {
        $squat[$row['height']] = $row['height'];
    }
}
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

$sql .= ' WHERE user.status = 1 AND user.user_type = 1 ' . $cond . ' ORDER BY ' . $order . ' LIMIT ' . $limit;

$result = $db->query($sql, $params);
//echo $db->last_query();
$players = array();
if ($result->num_rows() > 0) {
    $players = $result->result_array();
}
if (isset($_COOKIE['compare'])) {
    $compare = explode('-', $_COOKIE['compare']);
} else {
    $compare = array();
}

$document['style'][] = 'jquery-ui.css';
$document['script'][] = 'jquery-ui.js';
$document['script'][] = 'player.js';
    
$page_title = 'Players';
$document['title'] = $page_title;
$breadcrumb = array(array('title' => $page_title, 'link' => getLink('players.php')));
include('common/header.php');
?>

<div class="stj_players_wrap">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-3 stj_filter">
                <form name="form-filter-players" id="form-filter-players" action="<?php echo getLink('phpajax/players.php', '', true); ?>" method="post" >
                <input type="hidden" name="filter_order" id="filter_order" value="<?php echo $sort_order; ?>">
                <input type="hidden" name="filter_test" id="filter_test" value="<?php echo $test_id; ?>">
                    <h2>Filter</h2>
                    <div class="stj_fltr_srch">
                        <input class="txt_hdr" placeholder="Search" type="text" name="search" id="search" value="<?php echo $search; ?>">
                        <input class="btn_hdr" value="go" type="submit">
                    </div>
                    <div class="stj_drag">
                        <h3>Score</h3>
                        <div class="stj_range">
                            <div id="slider-range"><input type="hidden" name="score_range" id="score_range" value="0-100"></div>
                            <div class="stj_rg_val"><span id="slider-value-0">0</span><span class="stval_right" id="slider-value-1">100</span></div>
                        </div>
                    </div>
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
                                            </div>
                    <?php }?>
                    <?php if (count($height) > 0) {$i=1;
                    $k=0;
                    $l=0;
                    $j=250;?>
                    <div class="stj_drag input-group">
                        <h3>Height (cm)</h3>
                       <select name="heightOption" class="form-control" id="height">
                            <option value="0">Please Select an option</option>
                            <?php //foreach ($height as $heights) { ?>
                            <?php while($i<=$j){ ?>
                            <option  
                            value="<?php 
                            
                                echo $i;
                            ?>">
                            <?php  
                                echo $i;
                            ?>
                            </option>
                            <?php 
                            $i++;
                            } ?>
                        </select>
                        
                    </div>
                    <?php }?>
                    <?php if (count($weight) > 0) {$i=1;
                    $k=0;
                    $l=0;
                    $j=200;?>
                    <div class="stj_drag input-group">
                        <h3>Weight (Kg)</h3>
                       <select name="weight" class="form-control" id="weight">
                           <option value="0">Please Select an option</option>
                            
                            <?php //foreach ($height as $heights) { ?>
                            <?php while($i<=$j){ ?>
                            <option  
                            value="<?php 
                            
                                echo $i;
                            ?>">
                            <?php  
                                echo $i;
                            ?>
                            </option>
                            <?php 
                            $i++;
                            } ?>
                        </select>
                        
                    </div>
                            <?php } ?>
                            <?php if (count($age) > 0) {$i=1;$j=80;
                    $k=0;
                    $l=0;  ?>
                    <div class="stj_drag input-group">
                        <h3>Age (Years)</h3>
                       <select name="age" class="form-control" id="age">
                           <option value="0">Please Select an option</option>
                           <?php //foreach ($height as $heights) { ?>
                            <?php while($i<=$j){ ?>
                            <option  
                            value="<?php 
                            
                                echo $i;
                            ?>">
                            <?php  
                                echo $i;
                            ?>
                            </option>
                            <?php 
                            $i++;
                            } ?>
                            
                            <?php } ?>
                            
                        </select>
                        <?php //} ?>
                        <?php if (count($foot) > 0) { ?>
                    <div class="stj_drag input-group">
                        <h3>Foot</h3>
                        
                       <select name="foot" class="form-control" id="foot">
                           <option  value="0">Please Select an option
                       </option>
                            <?php foreach ($foot as $foots) { ?>
                            <option  value="<?php echo $foots; ?>">
                            <?php echo $foots; ?>
                            </option>
                            <?php } ?>
                        </select>
                        
                                
                        </select>
                        
                    </div>
                            <?php } ?>
                            <div class="stj_drag input-group">
                        <h3>Validated</h3>
                       <select name="validated" class="form-control" id="valid">
                            <option  value="0">Please Select an option
                       </option>
                            <option  value="Validated">
                            Validated
                            </option>
                            <option  value="coachValidated">
                            Coach Validated
                            </option>
                            <option  value="notValidated">
                            Not Validated
                            </option>
                        </select>
                        
                                  
                      
                        <?php  ?>
                        <?php if (count($speed) > 0) { $i=0;$j=100;$k=0;?>
                    <div class="stj_drag input-group">
                        <h3>Speed (%)</h3>
                       <select name="speedster" class="form-control" id="speed">
                       <option  value="0">Please Select an option
                       </option>
                            <?php //foreach ($height as $heights) { ?>
                            <?php while($i<$j){ ?>
                            <option  
                            value="<?php 
                             
                                //echo $i;    
                                if($i==0)
                                {
                                    echo $i."-".($i+40);
                                }
                                else{
                                    echo $i."-".($i+20);
                                }
                            ?>">
                            <?php  
                                // echo $i;
                                // echo $i."-".($i+20)."%";
                                if($i==0)
                                {
                                    echo $i."-".($i+40);
                                }
                                else{
                                    echo $i."-".($i+20);
                                }
                                if($i==0)
                                {
                                    echo " (Below Average score)";
                                }
                                if($i==40)
                                {
                                    echo " (Average score)";
                                }
                                if($i==60)
                                {
                                    echo " (High-Performance score)";
                                }
                                if($i==80)
                                {
                                    echo "  (Elite score)";
                                }
                            ?>
                            </option>
                            <?php 
                            //$i++;
                            // $i+=20;
                            if($i==0)
                            {
                                $i=$i+40;
                            }
                            else{
                                $i=$i+20;
                            }
                            } ?>
                        </select>
                        
                                
                       
                        
                    </div>
                            <?php } ?>
                            <?php if (count($agility) > 0) { $i=0;
                    $k=0;
                    $l=0; ?>
                    <div class="stj_drag input-group">
                        <h3>Agility (%)</h3>
                       <select name="agility" class="form-control" id="agility">
                       <option  value="0">Please Select an option
                       </option>
                            <?php //foreach ($height as $heights) { 
                            $i=0;$j=100;?>
                            <?php while($i<$j){ ?>
                            <option  
                            value="<?php 
                            
                                // echo $i;
                                if($i==0)
                                {
                                    echo $i."-".($i+40);
                                }
                                else{
                                    echo $i."-".($i+20);
                                }
                            ?>">
                            <?php  
                                // echo $i;
                                // echo $i."-".($i+20)."%";
                                if($i==0)
                                {
                                    echo $i."-".($i+40);
                                }
                                else{
                                    echo $i."-".($i+20);
                                }
                                if($i==0)
                                {
                                    echo " (Below Average score)";
                                }
                                if($i==40)
                                {
                                    echo " (Average score)";
                                }
                                if($i==60)
                                {
                                    echo " (High-Performance score)";
                                }
                                if($i==80)
                                {
                                    echo "  (Elite score)";
                                }
                                
                            ?>
                            </option>
                            <?php 
                            // $i++;
                            // $i=$i+20;
                            if($i==0)
                            {
                                $i=$i+40;
                            }
                            else{
                                $i=$i+20;
                            }
                            } ?>
                        </select>
                        
                         
                        
                      
                    </div>
                            <?php } ?>
                        
                    </div>
                        <?php if (count($jump) > 0) {$i=0;
                    $k=0;
                    $l=0; ?>
                    <div class="stj_drag input-group">
                        <h3>Jump (%)</h3>
                       <select name="jump" class="form-control" id="jump">
                       <option  value="0">Please Select an option
                       </option>
                            <?php //foreach ($height as $heights) { 
                            $i=0;$j=100;?>
                            <?php while($i<$j){ ?>
                            <option  
                            value="<?php 
                            
                                // echo $i;
                                if($i==0)
                                {
                                    echo $i."-".($i+40);
                                }
                                else{
                                    echo $i."-".($i+20);
                                }
                            ?>">
                            <?php  
                                // echo $i;
                                // echo $i."-".($i+20)."%";
                                if($i==0)
                                {
                                    echo $i."-".($i+40);
                                }
                                else{
                                    echo $i."-".($i+20);
                                }
                                if($i==0)
                                {
                                    echo " (Below Average score)";
                                }
                                if($i==40)
                                {
                                    echo " (Average score)";
                                }
                                if($i==60)
                                {
                                    echo " (High-Performance score)";
                                }
                                if($i==80)
                                {
                                    echo "  (Elite score)";
                                }
                            ?>
                            </option>
                            <?php 
                            // $i++;
                            // $i=$i+20;
                            if($i==0)
                            {
                                $i=$i+40;
                            }
                            else{
                                $i=$i+20;
                            }
                            } ?>
                        </select>
                    </div>
                            <?php } ?>
                            <?php if (count($aerobic) > 0) { $i=0;
                    $k=0;
                    $l=0; ?>
                    <div class="stj_drag input-group">
                        <h3>Aerobic (%)</h3>
                       <select name="aerobic" class="form-control" id="aerobic">
                       <option  value="0">Please Select an option
                       </option>
                            <?php //foreach ($height as $heights) { 
                            $i=0;$j=100;?>
                            <?php while($i<$j){ ?>
                            <option  
                            value="<?php 
                            
                                // echo $i;
                                if($i==0)
                                {
                                    echo $i."-".($i+40);
                                }
                                else{
                                    echo $i."-".($i+20);
                                }
                            ?>">
                            <?php  
                                // echo $i;
                                // echo $i."-".($i+20)."%";
                                if($i==0)
                                {
                                    echo $i."-".($i+40);
                                }
                                else{
                                    echo $i."-".($i+20);
                                }
                                if($i==0)
                                {
                                    echo " (Below Average score)";
                                }
                                if($i==40)
                                {
                                    echo " (Average score)";
                                }
                                if($i==60)
                                {
                                    echo " (High-Performance score)";
                                }
                                if($i==80)
                                {
                                    echo "  (Elite score)";
                                }
                            ?>
                            </option>
                            <?php 
                            // $i++;
                            if($i==0)
                            {
                                $i=$i+40;
                            }
                            else{
                                $i=$i+20;
                            }
                            } ?>
                            </option>
                            <?php } ?>
                        </select>
                        
                        
                        
                      
                    </div>
                            <?php //} ?>
                            <?php if (count($squat) > 0) { ?>
                    <div class="stj_drag input-group">
                        <h3>Squat (%)</h3>
                       <select name="squat" class="form-control" id="squat">
                       <option  value="0">Please Select an option
                       </option>
                            <?php //foreach ($height as $heights) { 
                            $i=0;$j=100;?>
                            <?php while($i<$j){ ?>
                            <option  
                            value="<?php 
                            
                                // echo $i;
                                if($i==0)
                                {
                                    echo $i."-".($i+40);
                                }
                                else{
                                    echo $i."-".($i+20);
                                }
                            ?>">
                            <?php  
                                // echo $i;
                                // echo $i."-".($i+20)."%";
                                if($i==0)
                                {
                                    echo $i."-".($i+40);
                                }
                                else{
                                    echo $i."-".($i+20);
                                }
                                if($i==0)
                                {
                                    echo " (Below Average score)";
                                }
                                if($i==40)
                                {
                                    echo " (Average score)";
                                }
                                if($i==60)
                                {
                                    echo " (High-Performance score)";
                                }
                                if($i==80)
                                {
                                    echo "  (Elite score)";
                                }
                            ?>
                            </option>
                            <?php 
                            // $i++;
                            // $i=$i+20;
                            if($i==0)
                            {
                                $i=$i+40;
                            }
                            else{
                                $i=$i+20;
                            }
                            } ?>
                            </option>
                            <?php } ?>
                        </select>
                        
                          
                        
                      
                    </div>
                            <?php //} ?>
                            <?php if (count($bench) > 0) { ?>
                    <div class="stj_drag input-group">
                        <h3>Bench (%)</h3>
                       <select name="bench" class="form-control" id="bench">
                       <option  value="0">Please Select an option
                       </option>
                            <?php //foreach ($height as $heights) { 
                            $i=0;$j=100;?>
                            <?php while($i<$j){ ?>
                            <option  
                            value="<?php 
                            
                                // echo $i;
                                if($i==0)
                                {
                                    echo $i."-".($i+40);
                                }
                                else{
                                    echo $i."-".($i+20);
                                }
                            ?>">
                            <?php  
                                // echo $i;
                                // echo $i."-".($i+20)."%";
                                if($i==0)
                                {
                                    echo $i."-".($i+40);
                                }
                                else{
                                    echo $i."-".($i+20);
                                }
                                if($i==0)
                                {
                                    echo " (Below Average score)";
                                }
                                if($i==40)
                                {
                                    echo " (Average score)";
                                }
                                if($i==60)
                                {
                                    echo " (High-Performance score)";
                                }
                                if($i==80)
                                {
                                    echo "  (Elite score)";
                                }
                            ?>
                            </option>
                            <?php 
                            // $i++;
                            // $i=$i+20;
                            if($i==0)
                            {
                                $i=$i+40;
                            }
                            else{
                                $i=$i+20;
                            }
                            } ?>
                            </option>
                            <?php } ?>
                        </select>
                        
                        <input type="submit" class="btn_fltr m-2" value="Apply" />  <br>
                        <input type="button" class="btn_fltr m-2" value="Reset" id="reset"/>        
                      
                    </div>
                            <?php //} ?>
                    </div>
                          
                            
                </form>
                
                <script>
                    var reset=document.getElementById("reset");
                    reset.onclick=function()
                    {
                        document.getElementById("height").selectedIndex = 0;
                        document.getElementById("weight").selectedIndex = 0;
                        document.getElementById("age").selectedIndex = 0;
                        document.getElementById("foot").selectedIndex = 0;
                        document.getElementById("valid").selectedIndex = 0;
                        document.getElementById("speed").selectedIndex = 0;
                        document.getElementById("agility").selectedIndex = 0;
                        document.getElementById("jump").selectedIndex = 0;
                        document.getElementById("squat").selectedIndex = 0;
                        document.getElementById("bench").selectedIndex = 0;
                        document.getElementById("aerobic").selectedIndex = 0;
                        document.getElementById("score_range").value="0-100";
                        document.getElementsByClassName("ui-slider-handle")[0].style.left="0%";
                        document.getElementsByClassName("ui-slider-handle")[1].style.left="100%";
                        document.getElementById("slider-value-0").innerHTML="0";
                        document.getElementById("slider-value-1").innerHTML="100";
                        document.getElementById("slider-value-0").style.marginLeft="0px";
                        document.getElementById("slider-value-1").style.marginRight="0px";
                        
                        var checkboxes=document.getElementsByClassName("lb_chk");
                        for(var i=0;i<checkboxes.length;i++)
                        {
                            if(checkboxes[i].checked==true)
                            {
                                checkboxes[i].click();
                            }
                        }
                        //txt_hdr
                        var searchBox=document.getElementById("search").value="";;
                        // jQuery.ajax({url: "./js/player.js",success:function(data){searchPlayers(true);}});
                        //searchPlayers(true);
                        
                    }
                </script>
            </div>
            <div class="col-xs-12 col-md-9 stj_listing">
                <h2>Players profiles <?php if ($test_id) { echo '<span> (' . $testInfo['title'] . ')</span>'; } ?></h2>
                <div class="stj_sort">
                    <a class="a_vcl hide" href="javascript:">View Compare List</a>
                    <div class="sort_sel">
                        <label>Sort By :</label>
                        <form name="form-sort-players" id="form-sort-players" action="" method="get" >
                            <?php 
                            
                                $sort_options = array(
                                                    'score-desc' => 'Score - High to Low',
                                                    'score-asc' => 'Score - Low to High',
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
                    <ul id="players-list">
                        <?php foreach ($players as $player) { ?>
                        <li class="col-xs-12 col-sm-4">
                            <a href="javascript:" class="compare compare-icon <?php if (in_array($player['id'], $compare)) { ?> added <?php } ?>" data-player="<?php echo $player['id']; ?>"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>
                            <a href="<?php echo getLink('profile.php', 'profile_id='.$player['id']); ?>">
                            <div class="p_car_plyr <?php if($player['score_validated_date'] != null){?><?php if($player['score_validated_by'] != 0) {?>coach-<?php } ?>validated<?php } else {?>not-validated<?php } ?>" style="background-image: url(<?= playerImageCheck($player['photo']) ?>);">
                                    <?php 
                                        //echo getUserProfileImage($player['photo']); 
                                    ?>
                                    <div class="pcp_con_hvr">
                                        <div class="pcp_con_hvr_inner">
                                            <label><?php echo getPosition($player['1st_player_position']); ?></label>
                                            <span>1st Playing Position </span>
                                        </div>
                                        <div class="pcp_con_hvr_inner">
                                            <label><?php echo getPosition($player['2nd_player_position']); ?></label>
                                            <span>2nd Playing Position</span>
                                        </div>
                                        <div class="pcp_con_hvr_inner">
                                            <label><?php echo getPosition($player['3rd_player_position']); ?></label>
                                            <span>3rd Playing Position</span>
                                        </div>
                                    </div>
                                    <div class="pcp_con">
                                        <div class="pcp_con_inn">
                                            <h3><?php echo $player['first_name']; ?><span><?php echo $player['last_name']; ?></span></h3>
                                            <?php if(isset($player['team_id'])) { ?>
                                            <h4><?php echo getClub($player['team_id']); ?></h4>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="pcp_validation <?php if($player['score_validated_date'] != null){?><?php if($player['score_validated_by'] != 0) {?>coach-<?php } ?>validated<?php } ?>">
                                        <?php echo ($player['score_validated_date']) ? (($player['score_validated_by'] != 0) ? 'Coach Validated' : 'Validated') : 'Not Validated'; ?>
                                    </div>
                                    <div class="pcp_value">
                                        <?php $score = ($test_id) ? $player['weightage'] : $player['overall_score']; ?>
                                        <?php echo ($score) ? $score : 0; ?>
                                    </div>
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