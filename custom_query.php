<?php include('config.php');

?>
<?php
echo "here";
$query="SELECT COUNT(*) FROM pitch_user where user_type=1";
if (!$result1=$db->query($query)) {
    echo("Error description: " . $db -> error);
}
// $row=$result->result_array();
$rowcount=$result1->row_array();
$row_count= $rowcount['COUNT(*)']; 


    $query2="SELECT id FROM pitch_user where user_type=1";
    if (!$result2=$db->query($query2)) {
    echo("Error description: " . $db -> error);
    }   
    $row=$result2->result_array();
    var_dump($row);
    foreach($row as $rows)
    {
        for($i=1;$i<=20;$i++)
        {
            $user_id=$rows['id'];
            $query="INSERT INTO pitch_videos1(user_id,video_key,video_link) VALUES($user_id,'$i','')";
            $run=$db->query($query);
        }
    }
    



?>