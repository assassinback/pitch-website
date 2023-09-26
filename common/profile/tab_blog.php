<style>
    .col-md-3.specialCase p {
    width: auto!important;
}
</style>
<?php
						        $query17="SELECT * FROM pitch_blog_post where status=1";
						        $result17=$db->query($query17);
							    $row17=$result17->result_array();
							    ?>
							    
							    
							    <?php
							    $i=1;
							    foreach($row17 as $rows17)
							    {
							        if($i%3==0)
							        {
							            ?><div class="row">     <?php
							        }
							        ?>
							        
							        <div class="col-md-3 specialCase">
							        <?php
							        
							        $blog_id=$rows17["id"];
							        echo "<h3>".$rows17["title"]."</h3>";
							        if(strlen($rows17["description"])>600)
							        {
							            echo "".substr($rows17["description"],0,600 );
							        }
							        else{
							            echo $rows17["description"];
							        }
							        
							        echo "...<a href='./blog_detail.php?blog_id=$blog_id'>"."Read More"."</a><br></div>";
							        if($i%3==0)
							        {
							            ?></div>        <?php
							        }
							        $i++;
							    }
							 //   var_dump($row17);
						    ?>
						    
						    
						    <!---->