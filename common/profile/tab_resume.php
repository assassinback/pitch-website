<style>
 .Endorsement-check-box {
    position: unset !important;
    opacity: 1 !important;
}.form-control.mdb-select.colorful-select.dropdown-primary.md-form {
    display: none;
}
</style>
    <div class="col-xs-12 col-sm-9 ply_rv">
        
        <div class="ply_rv_dv">
             
             <?php
             
             $endCount=[];
             //if (!isset($_SESSION['id']) || (isset($_SESSION['id']) && $profile_id != $_SESSION['id'])) {
                //  echo $_SESSION['id'];
                // echo $user_type; 
                if($_SESSION["user_type"]==2 or $_SESSION["user_type"]==3 or $_SESSION["user_type"]==1)
                {
                    
                    
                    
                    ?>
                    
                        <form method="POST">
                            
                    <!--<form method="POST">-->
                    <!--    <button name="endorse">Endorse this player</button>-->
                        
                    <?php
                    $query4="SELECT * FROM `pitch_endorsement_type` WHERE validated=1 ORDER BY endorsement_name";
                    if (!$result4=$db->query($query4)) {
                            echo("Error description: " . $db -> error);
                    }    
                    $looping=$result4->num_rows();
                    // echo $looping;
                    $loopingfinal=(int)$looping/3;
                    // echo $loopingfinal;
                    $row4=$result4->result_array();
                    echo "<br>";
                    ?>
                    <div class="row">
                    <?php
                    foreach ($row4 as $rows4)
                    {
                        $endCount[]=$rows4["id"];
                        // echo $rows4["endorsement_name"].", ";
                        ?>
                        <div class="col-md-4">
                            <!--<div class="checkbox">-->
                                <label style="font-weight:400;">
                            <input class="Endorsement-check-box" type="checkbox" value="<?php 
                            echo $rows4["id"];
                            ?>" name='<?php
                            echo $rows4["id"];
                            ?>'><?php 
                            echo "&nbsp;".$rows4["endorsement_name"]."<br>";
                            ?>
                            </label>
                            <!--</div>-->
                            </div>
                        <?php
                    }
                    ?>
                    </div>
                    <br>
                    <label>Enter name of field:</label>
                        <input type="text" name="customField" placeholder="Custom Endorse" class="form-control">
                        <br>
                        <?php if($profile_id!=$_SESSION['id']){ ?>
                        <center><button name="endorse" class="btn btn-primary" style="">Endorse this player</button></center><br>
                        <?php }?>
                        <?php if($profile_id==$_SESSION['id']){ ?>
                        
                        <!-- Compiled and minified CSS -->
                        <div class="mdb-select">
                        <select class="form-control colorful-select dropdown-primary md-form" name="coaches[]" multiple style="display:none" placeholder="Select a Coach">
                            <!--<option value="0" selected>Select a Coach</option>-->
                            
                            <?php
                                $query17="SELECT * FROM pitch_user where user_type IN (2,3)";
                                if (!$result17=$db->query($query17)) {
                                echo("Error description: " . $db -> error);
                                }    
                                $row17=$result17->result_array();
                                foreach ($row17 as $rows17)
                                {
                                     ?>
                                        <option value="<?php echo $rows17["id"] ?>"><?php echo $rows17["first_name"]." ".$rows17["last_name"] ?></option>
                                     <?php
                                }
                            ?>
                        </select>
                        </div>
                        <br>
                        <center><button name="request" class="btn btn-primary" style="">Request</button></center><br>
                        <?php }?>
                    <?php
                    ?>
                    </form>
                    
                    <!--<div class="col-md-6">abcd</div>-->
                    
                    <?php
                    if((!isset($_POST["coaches"]) or $_POST["coaches"]==0 or $_POST["coaches"]=="0")  and isset($_POST["request"]))
                    {
                        echo "<script>alert('Please Select a Coach');</script>";
                    }
                    else if(isset($_POST["request"]))
                    {
                        
                        // var_dump($_POST["coaches"]);
                        $playerid=$_SESSION["id"];
                        
                        $query16="SELECT * from pitch_endorsement_type";
                        $query5="SELECT * from pitch_endorsement_type";
                        $coach_id=$_POST["coaches"];
                        // echo "<script>alert($coach_id);</script>";
                        $endloop=0;
                        
                        
                        if (!$result5=$db->query($query5)) {
                                echo("Error description1: " . $db -> error);
                        }    
                        $row5=$result5->result_array();
                          
                        // var_dump($endCount);
                        while($endloop<sizeof($endCount))
                        {
                            if(isset($_POST[$endCount[$endloop]]))
                            {
                                $endorsement_id=$endCount[$endloop];
                                $inserts=0;
                                while($inserts<sizeof($coach_id))
                                {
                                if($coach_id[$inserts]!=0)
                                {
                                    $date = date("Y-m-d H:i:s");
                                    // echo "<script>alert('".$date."');</script>";
                                $query14="INSERT into pitch_endorsement_request(endorsement_id,user_id,coach_id,viewed,dateTime) values($endorsement_id,$playerid,$coach_id[$inserts],1,'$date')";
                                
                                if (!$result14=$db->query($query14)) {
                                    echo("Error description2: " . $db -> error);
                                }
                                }
                                $inserts++;
                                }
                                // $db->query($query14);
                                
                            }
                            $endloop++;
                        }
                        // $row14=$result14->result_array();
                                     ?>
                                                         <div class="container">
                                                          
                                                           <div class="alert alert-success">
                                                             <strong>Success!</strong> Request made to coaches
                                                           </div>
                                                         </div>
                                              <?php
                                                  if(isset($_POST["customField"]) and $_POST["customField"]!="")
                                        {
                                            $customfield=$_POST["customField"];
                                            $query15="INSERT INTO pitch_endorsement_type(endorsement_name) values('$customfield')";;
                                            if ($db->query($query15)) 
                    				        {
                    		             
                    				        }
                    				        else
                    				        {
                    				            echo "Error: " . $db->error;
                    				        }
                                            
                                        }
                        // foreach ($row5 as $rows5)
                        // {
                        //     // var_dump($rows5);
                            
                        //     $postname1=$rows5["id"];
                        //     $postname=$rows5["endorsement_name"];
                        //     $coachid=$_SESSION["id"];
                    //         if(isset($_POST[$postname1]))
                    //         {
                    //             $query14="INSERT into pitch_endorsement_request(endorsement_id,user_id) values($postname1,$playerid)";
                    //             if ($db->query($query14) === TRUE) 
        				        // {
        				            // if(isset($_POST["customField"]) and $_POST["customField"]!="")
                        //             {
                        //                 $query15="INSERT INTO pitch_endorsement_type(endorsement_name) values('$customfield')";;
                        //                 if ($db->query($query15)) 
                				    //     {
                		             
                				    //     }
                				    //     else
                				    //     {
                				    //         echo "Error: " . $db->error;
                				    //     }
                                        
                        //             }
        				        // }
        				        // else
        				        // {
        				        //     echo "Error: " . $db->error;
        				        // }
                    //         }
                        // }
                        
                    }
                    if(isset($_POST["endorse"]))
                    {
                        
                        $points=0;
                        // $user_type1=$_SESSION["user_type"];
                        // echo "<script>alert('$user_type1')</script>";
                        $user_id=$_SESSION["id"];
                        $query25="SELECT user_type from pitch_user where id=$user_id";
                        if (!$result25=$db->query($query25)) {
                                echo("Error description: " . $db -> error);
                        }
                        $row25=$result25->result_array();
                        foreach($row25 as $rows25)
                        {
                            $user_type1=$rows25["user_type"];
                        }
                        // echo "<script>alert('$user_type1')</script>";
                        if($user_type1==1)
                        {
                            // var_dump($_SESSION);
                            $points=1;
                        }
                        if($user_type1==2)
                        {
                            $points=20;
                        }
                        if($user_type1==3)
                        {
                            $points=20;
                        }
                        $query5="SELECT * from pitch_endorsement_type";
                        if (!$result5=$db->query($query5)) {
                                echo("Error description: " . $db -> error);
                        }    
                        $row5=$result5->result_array();
                        foreach ($row5 as $rows5)
                        {
                            // var_dump($rows5);
                            
                            $postname1=$rows5["id"];
                            $postname=$rows5["endorsement_name"];
                            $coachid=$_SESSION["id"];
                            if(isset($_POST[$postname1]))
                            {
                                $endorseid=$rows5["id"];
                                // echo $endorseid; 
                                $query7="SELECT * from pitch_endorsement where user_id=$profile_id and endorsement_id=$endorseid";
                                
                                if (!$result7=$db->query($query7)) {
                                    echo("Error description: " . $db -> error);
                                }    
                                if ($result7->num_rows() == 0) {
                                    $date = date("Y-m-d H:i:s");
				                    $query6="INSERT INTO pitch_endorsement (user_id,endorsement_id,endorsement_count,endorsment_user_id,endorsement_points,viewed,dateTime) VALUES($profile_id,$endorseid,1,$coachid,$points,1,'$date')" ;
				                    if ($db->query($query6) === TRUE) 
				                    {
                                        // echo "New record created successfully";
                                        ?>
                                                <div class="container">
                                                  
                                                  <div class="alert alert-success">
                                                    <strong>Success!</strong> Player endorsed successfully in <?php echo $postname; ?>
                                                  </div>
                                                </div>
                                        <?php
                                        $query12="UPDATE pitch_user set endorsement_count = endorsement_count+1 where id=$profile_id";
                                                if ($db->query($query12)) 
        				                        {
        				                        
        				                        }
        				                        else 
                                                {
                                                    echo "Error: " . $db->error;
                                                }
                                    }    
                                    else 
                                    {
                                        echo "Error: " . $db->error;
                                    }
				                }
				                else
				                {
				                    // $query8="SELECT * from pitch_endorsement_type where user_id=$profile_id and ";
				                    $row7=$result7->result_array();
				                    // var_dump($row4);
                                    foreach ($row7 as $rows7)
                                    {
                                        // var_dump($rows7);
                                        
                                        $endcount=$rows7["endorsement_count"];
                                        $endcountuser=$rows7["endorsment_user_id"];
                                        $enduserarray=explode(",",$rows7["endorsment_user_id"]);
                                        $k=0;
                                        $check=true;
                                        while($k<sizeof($enduserarray))
                                        {
                                            if($enduserarray[$k]==$_SESSION["id"])
                                            {
                                                $check=false;
                                            }
                                            $k++;
                                        }
                                        if($check)
                                        {
                                            $date = date("Y-m-d H:i:s");
                                            $query6="UPDATE pitch_endorsement set endorsement_count=".$endcount."+1, endorsment_user_id='$endcountuser,$coachid',endorsement_points=endorsement_points+$points, viewed=1, dateTime='$date' where user_id=$profile_id AND endorsement_id=$endorseid";
                                            
                                            // echo $query6;
        				                    if ($db->query($query6)) 
        				                    {
                                                // echo "New record created successfully";
                                                ?>
                                                <div class="container">
                                                  
                                                  <div class="alert alert-success">
                                                    <strong>Success!</strong> Player endorsed successfully in <?php echo $postname; ?>
                                                  </div>
                                                </div>
                                                <?php
                                                $query12="UPDATE pitch_user set endorsement_count = endorsement_count+1 where id=$profile_id";
                                                if ($db->query($query12)) 
        				                        {
        				                        
        				                        }
        				                        else 
                                                {
                                                    echo "Error: " . $db->error;
                                                }
                                            }    
                                            else 
                                            {
                                                echo "Error: " . $db->error;
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                            <div class="container">
                                                <div class="alert alert-danger">
                                                    <strong>Error:</strong> This Player is already endorsed by you in <?php echo $postname; ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
				                    // echo "here";
				                    
				                }
                                
                            }
                        }
                        if(isset($_POST["customField"]) and $_POST["customField"]!="")
                        {
                            $customfield=$_POST["customField"];
                            $query13="INSERT INTO pitch_endorsement_type(endorsement_name) values('$customfield')";
                            if ($db->query($query13)) 
        				    {
        				        $query15="SELECT id from pitch_endorsement_type where endorsement_name='$customfield'";
        				        if ($result15=$db->query($query15)) 
        				        {
        				            // echo "here";
        				            $row15=$result15->result_array();
        				            // var_dump($row15);
        				            // echo "here";
        				            foreach($row15 as $rows15)
        				            {
        				                // echo "here";
        				                $endtypeid=$rows15["id"];
        				                // echo $endtypeid;
        				                $sessionid=$_SESSION['id'];
        				                $date = date("Y-m-d H:i:s");
        				                $query14="INSERT into pitch_endorsement(endorsement_id,user_id,endorsement_count,endorsment_user_id,endorsement_points,viewed,dateTime) values($endtypeid,$profile_id,1,'$sessionid',$points,1,'$date')";
        				                // echo "here";
        				                if ($db->query($query14)) 
        				                {
        				                    ?>
                                            <div class="container">
                                                  <div class="alert alert-success">
                                                    <strong>Success!</strong> Player endorsed successfully in <?php echo $customfield; ?>
                                                  </div>
                                                </div>
                                            <?php
        				                }
        				                else 
                                        {
                                            echo "Error: " . $db->error;
                                        }
        				            }
        				            
        	                    }
        				        else 
                                {
                                    echo "Error: here:" . $db->error;
                                }
        				        
                            }
        				    else 
                            {
                                echo "Error: Endorsement Already exists";
                            }
                        }
                    }
                }//checkbox
                // echo $_SESSION["user_type"];
                //  echo $profile_id;
                $query="SELECT * from pitch_user where id=".$profile_id;
                
                // echo $query;
                // $result=$db->query($query); 
    //             if ($db -> connect_errno) {
    //                 echo "Failed to connect to MySQL: " . $db -> connect_error;
    //                 exit();
    //             }
    //             if ($result->num_rows() > 0) {
				//     // echo "here";
				// }
				if (!$result=$db->query($query)) {
                    echo("Error description: " . $db -> error);
                }
                // var_dump($result);
				$row = $result->row_array(); 
				// echo $row["id"];
				// echo $row["endorsment_user_id"];
				// echo $row["endorsement_count"];
				// $coachids=explode(',',$row["endorsment_user_id"]);
				// var_dump($coachids);
				$i=0;
				echo '<div style="float:right">';
				echo "<p style='font-size:18px;font-weight:600;font-size: 18px;font-weight: 400;border: 1px solid #3aaa35;padding: 10px;box-shadow: 0 0 5px BLACK;display:inline-block';'>Total Endorsements: ".$row["endorsement_count"]."</p>";
					$query16="SELECT SUM(endorsement_points) FROM `pitch_endorsement` WHERE user_id=$profile_id";
				// echo $query16;
				if (!$result16=$db->query($query16)) {
                    echo("Error description1: " . $db -> error);
                }
                $row16 = $result16->row_array(); 
                
                    echo "<p style='font-size:18px;font-weight:600;font-size: 18px;font-weight: 400;border: 1px solid #3aaa35;padding: 10px;box-shadow: 0 0 5px BLACK;display: inline-block;margin: 10px;'>Total Endorsement points: ".$row16["SUM(endorsement_points)"]."</p>";
                echo '</div>';
				echo "<table class='table table-hover table-striped'>";
				echo "<tr>";
				// echo "<th>S.No</th>";
				echo "<th>Score</th>";
				echo "<th>Type of Endorsement</th>";
				echo "<th>Endorsement Points</th>";
				// echo "<th>Coaches</th>";
			//	echo "<th>Image</th>";
				echo "</tr>";
				$query2="SELECT * from pitch_endorsement where user_id=".$profile_id." ORDER BY endorsement_points DESC";
				// echo $query2;
				
                if (!$result1=$db->query($query2)) {
                    echo "Error description: " . $db -> error;
                }
                // $result=$db->query($query2);
                // var_dump($result1);
                // echo "<br>";
                // echo $result->current_row;
                // // $row = $result->row_array(); 
                // $row = mysql_fetch_row($result);
                // echo $result->current_row;no
                // $row = $result->row_array(); 
                // echo $row["endorsement_id"];
                // echo $_SESSION["id"];
                // $row = $result->fetch_array();
                // var_dump($row);
                // print_r($row);
                // echo $result1->num_rows();
                if ($result1->num_rows() > 0) {
                // output data of each row
                    // echo "here";
                    // foreach($x as $result1){
                   	$row = $result1->result_array();
                   	// $row=$result1->fetch_assoc();
                   	$i=1;
                    foreach($row as $rows){
                        // while($row->fetch_assoc())
                        // {
                        //     echo "<td>".$rows['endorsement_count']."</td>";
                        // }
                        // var_dump($rows);
                        
                        // echo $rows["endorsement_count"]." ";
                        // echo "here."<br>";
                        echo "<tr>"; 
                        // echo "<td>$i</td>";
                        echo "<td ><button data-target='#exampleModal' data-toggle='modal' class='btn btn-primary' style='border-radius: 0px;' onClick='exampleModal(this.value)'  value=".$rows['endorsment_user_id'].">".$rows['endorsement_count']."</button></td>";
                        $query1="SELECT * from pitch_endorsement_type where id=".$rows['endorsement_id'];
                        if (!$result2=$db->query($query1)) {
                            echo("Error description: " . $db -> error);
                        }
                        $row1=$result2->result_array();
                        foreach($row1 as $rows1)
                        {
                            echo "<td>".$rows1["endorsement_name"]."</td>";
                        }
                        echo "<td>".$rows["endorsement_points"]."</td>";
                        
                        // $query3="SELECT * from pitch_user where id IN (".$rows['endorsment_user_id'].") LIMIT 10";
                        // // echo $query3;
                        // if (!$result3=$db->query($query3)) {
                        //     echo("Error description: " . $db -> error);
                        // }
                        // $row3=$result3->result_array();
                        // $names="";
                        // echo "<td>";
                        // foreach ($row3 as $rows3)
                        // {
                        //     $names=$names.$rows3["first_name"].",";
                        // }
                        // // $names=substr($names, 0, -1);
                        // $names = rtrim($names, ", ");
                        // echo $names;
                        //echo "</td>";
                        // $query10="SELECT * from pitch_user where id IN (".$rows['endorsment_user_id'].") LIMIT 10";
                        // if (!$result10=$db->query($query10)) {
                        //     echo("Error description: " . $db -> error);
                        // }
                        // $row10=$result10->result_array();
                        // echo "<td>";
                        // foreach ($row10 as $rows10)
                        // {
                        //     echo "<img width='50' height='50' src='".playerImageCheck($rows10['photo'])."'>";
                        // }
                        // echo "</td>";
                        //echo $query3;
                        // echo $query1;
                        // while ($row = mysqli_fetch_assoc($result1))
                        // {
                        //     echo "<td>".$row["endorsement_name"]."</td>";
                        // }   
                        // $query1="SELECT * from pitch_endorsement_type where id IN (".$row['endorsment_user_id'].")";
                        // echo $query1;
                        
                        // // echo "<td>".$row['endorsement_name']."</td>";
                        // echo "<input type='hidden' class='mohsin' value=".$rows['endorsment_user_id'].">";
                        
                        echo "</tr>";
                        $i++;
                   }
                }
                echo "</table>";
                // echo "here";
                // while($row = $result->fetch_assoc()) 
                // {
                //     echo "here";
                //     // code
                //     echo "<tr>"; 
                //     echo "<td>".$row['endorsement_count']."</td>";
                //     $query1="SELECT * from pitch_endorsement_type where id=".$row['endorsement_id'];
                //     if (!$result1=$db->query($query1)) {
                //         echo("Error description: " . $db -> error);
                //     }
                //     // echo $query1;
                //     while ($row = mysqli_fetch_assoc($result1))
                //     {
                //         echo "<td>".$row["endorsement_name"]."</td>";
                //     }   
                //     $query1="SELECT * from pitch_endorsement_type where id IN (".$row['endorsment_user_id'].")";
                //     echo $query1;
                    
                //     // echo "<td>".$row['endorsement_name']."</td>";
                //     // echo "<td>".$row['endorsement_id_names']."</td>";
                //     echo "</tr>";
                // }
                // echo "</table>";
                // while()                                
				// echo $_SESSION['id'];
				// while($i<sizeof($coachids))
				// {
				    // $query="SELECT * from pitch_user where id=".$coachids[$i];
				    // if (!$result=$db->query($query)) {
        //                 echo("Error description: " . $db -> error);
        //             }
        //             $row = $result->row_array();
                    
				    // // echo $coachids[$i];
				    // $i++;
				// }
				// echo $_SESSION['id'];
				// while($row = mysqli_fetch_assoc($result)) {
    //                 echo "here";
    //             }
                // var_dump($result);
                
             //}
             ?>
            
    <?php
        
    ?>
        </div>
        
    </div>
<!--<div class="bootstrap-iso">-->
   <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
  <div class="modal-content " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Endorsement Received</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary modal-close" data-dismiss="modal" data-target="#exampleModal">Close</button>
        
      </div>
    </div>
  </div>
  </div>
  </div>
  <script>
  
  
  
  
      function exampleModal(mohsin){
          
             //console.log(e);
          $.ajax({
          url:'./common/profile/tab_resumemodal.php',
              
			data:{mohsin:mohsin},
			type:'post',
			success: function(result){
			 //     var elems = document.querySelectorAll('.modal');
    // var instances = M.Modal.init(elems, options);
    // var elems = document.querySelectorAll('.modal');
    // var instances = M.Modal.init(elems, options);
    // instance.open();
		$('#exampleModal').modal();
        $('#exampleModal .modal-body').html(result);
				//console.log(result);
			},
			error:function(result){
				console.log(result);
			}
          });
          //$('#exampleModal').modal();
          //$('#exampleModal .modal-body').html("naam");
          //console.log('Naam hai ballu');
      }
  </script>
  <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">-->

<!-- Compiled and minified JavaScript -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>-->
                        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
                        <!--<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>-->
                        <!--<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>-->
                        <script>
                        $(document).ready(function() {
$('.mdb-select').dropdown()
});
                    // $(".chosen-select").chosen({})
</script>
<?php
// echo "<h1>here</h1>".$_SESSION["id"];
if($profile_id==$_SESSION["id"])
{
    $result1 = $db->query('UPDATE ' . $dbPrefix . 'endorsement SET viewed = 0 WHERE user_id='.$_SESSION["id"], array());
}
?>