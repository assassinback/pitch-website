<?php 
include_once('../../config.php');
$mohsin = $_POST['mohsin'];
//echo $mohsin;
//echo $mohsin;
$query10="SELECT * from pitch_user where id IN (".$mohsin.")";
$res = $db->query($query10);

                        if (!$result10=$db->query($query10)) {
                            echo("Error description: " . $db -> error);
                        }
                        $row10=$result10->result_array();
                        echo "<div class='container'>";
                           echo "<div class='row mx-auto' >";
                           echo "<table class='table table-hover table-striped'>";
                           echo "<th>User Type</th>";
                        //   echo "<th>Email</th>";
                           echo "<th>First Name</th>";
                           echo "<th>Last Name</th>";
                           echo "<th>Photo</th>";
                        foreach ($row10 as $rows10)
                        { 
                            echo "<tr>";
                            ?>
                                <td><?php 
                                if($rows10["user_type"]==1)
                                {
                                echo "Player";
                                }
                                if($rows10["user_type"]==2)
                                {
                                echo "Coach";
                                }
                                if($rows10["user_type"]==3)
                                {
                                echo "Scout";
                                }
                                ?></td>
                                <!--<td><?php //echo $rows10["email"]; ?></td>-->
                                <td><?php echo $rows10["first_name"]; ?></td>
                                <td><?php echo $rows10["last_name"]; ?></td>
                                <td><?php echo "<img class='img-responsive' src='".playerImageCheck($rows10['photo'])."' width='100' height='100'>"; ?></td>
                            <?php
                            
                            echo "</tr>";
                        //     echo "<div class='col-md-3'>";
                        //   echo "<img class='img-responsive' src='".playerImageCheck($rows10['photo'])."'>";
                        //   echo "</div>";
                        //   echo "<div class='col-md-3'>";
                        //     echo $rows10['first_name']." ". $rows10['last_name'];
                        //   echo "</div>";
                        
                        }
                        echo "</table>";
                        echo "</div>";
                        echo "</div>";
?>