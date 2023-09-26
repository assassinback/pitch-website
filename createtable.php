<?php
    include('config.php');
    $query="SELECT DISTINCT user_id from pitch_user_test_score";
    $result=$db->query($query);
    // var_dump($result);
    $row=$result->result_array();
    // var_dump($row);
    $user_id=[];
    foreach ($row as $rows)
    {
        $user_id[]=$rows["user_id"];    
    }
    // var_dump($user_id);
    $i=0;
    while($i<sizeof($user_id))
    {
        $query1="SELECT * from pitch_user_test_score where user_id=".$user_id[$i];
        $result1=$db->query($query1);
        $row1=$result1->result_array();
        foreach ($row1 as $rows1)
        {
            if($rows1["test_id"]==1)
            {
                $query2="UPDATE pitch_user_test_score_seperated set jump_score=".$rows1["weightage"]." where user_id=".$user_id[$i];
                if($result2=$db->query($query2))
                {
                    
                }
                else
                {
                	echo "Error: " . $db->error;
		        }
            }
            if($rows1["test_id"]==2)
            {
                $query2="UPDATE pitch_user_test_score_seperated set aerobic_score=".$rows1["weightage"]." where user_id=".$user_id[$i];
                if($result2=$db->query($query2))
                {
                    
                }
                else
                {
                	echo "Error: " . $db->error;
		        }
            }
            if($rows1["test_id"]==3)
            {
                $query2="UPDATE pitch_user_test_score_seperated set speed_score=".$rows1["weightage"]." where user_id=".$user_id[$i];
                if($result2=$db->query($query2))
                {
                    
                }
                else
                {
                	echo "Error: " . $db->error;
		        }
            }
            if($rows1["test_id"]==5)
            {
                $query2="UPDATE pitch_user_test_score_seperated set agility_score=".$rows1["weightage"]." where user_id=".$user_id[$i];
                if($result2=$db->query($query2))
                {
                    
                }
                else
                {
                	echo "Error: " . $db->error;
		        }
            }
            if($rows1["test_id"]==8)
            {
                $query2="UPDATE pitch_user_test_score_seperated set squat_score=".$rows1["weightage"]." where user_id=".$user_id[$i];
                if($result2=$db->query($query2))
                {
                    
                }
                else
                {
                	echo "Error: " . $db->error;
		        }
            }
            if($rows1["test_id"]==9)
            {
                $query2="UPDATE pitch_user_test_score_seperated set bench_score=".$rows1["weightage"]." where user_id=".$user_id[$i];
                if($result2=$db->query($query2))
                {
                    
                }
                else
                {
                	echo "Error: " . $db->error;
		        }
            }
        }
        // echo $query;
        $i++;
    }
?>