<?php
 $module = 'endorsement_validate';
checkPermission($module);

$pageTitle = 'Endorsement Type Validation';
 if(isset($_POST["approve"]))
 {
     $id=$_POST["id"];
     $query2="UPDATE pitch_endorsement_type set validated=1 where id=$id";
     if($_POST["approval"]=="false")
     {
        $query2="UPDATE pitch_endorsement_type set validated=0 where id=$id";    
     }
     echo $query2;
	//   echo $query17;
	if (!$result2=$db->query($query2)) {
        echo("Error description: " . $db -> error);
        }
 }
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><?php echo $pageTitle; ?></h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo getAdminLink();?>"><i class="fa fa-home"></i> Home</a></li>
            <li><a href="#"><i class="fa fa-folder"></i><?php echo $pageTitle; ?></a></li>
        </ol>
    </section>
    <section>
        <?php
            $query="SELECT * FROM pitch_endorsement_type ORDER BY endorsement_name";
            $result=$db->query($query);
            $row=$result->result_array();
            ?>
            <table id="table-list" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="table-list_info" style="width: 1067px;">
                <th>Endorsement Name</th>
                <th>Validated</th>
                <th>Approve?</th>
            <?php
            foreach($row as $rows)
            {
                echo "<tr>";
                ?>
                    <td><?php echo $rows["endorsement_name"]; ?></td>
                    <td><?php 
                        if($rows["validated"]==0)
                        {
                            echo "Not Validated";
                        }
                    ?>
                    <?php 
                        if($rows["validated"]==1)
                        {
                            echo "Validated";
                        }
                    ?></td>
                    <td><form method="POST">
                        <input type="hidden" name="id" value="<?php echo $rows["id"]; ?>">
                        
                        <?php
                            if($rows["validated"]==0)
                            {
                        ?>
                        <input type="hidden" name="approval" value="true">
                        <input type="submit" name="approve" value="Approve"></form></td>
                        <?php
                            }
                        ?>
                        <?php
                            if($rows["validated"]==1)
                            {
                        ?>
                        <input type="hidden" name="approval" value="false">
                        <input type="submit" name="approve" value="Disapprove"></form></td>
                        <?php
                            }
                        ?>
                <?php
                echo "</tr>";
            }
            ?>
            </table>
            <?php
        ?>
        
    </section>
</div>
