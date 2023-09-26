<?php
if(!in_array(1,$tes_mod)) { 
echo "<div class='grid_12'><div class='message error'><p>You don't have permission to access this page</p></div></div>";
die;
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>State</h1>
		  <ol class="breadcrumb">
			<li><a href="<?php echo ADMIN_URL; ?>main.php"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?php echo ADMIN_URL; ?>main.php?pg=viewstate"><i class="fa fa-location-arrow"></i>State</a></li>
			
		  </ol>
	</section>
	    <!-- Main content -->
		 <section class="content">
			  <div class="row">
					<div class="col-md-6">
					  <!-- general form elements -->
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Add State</h3>
							</div>
						<div class="error"></div>
						
						<form name="Admin" action="main.php?pg=stateproc" method="post" class="validateForm">
			<input type="hidden" value="add" name="act">
			 <div class="box-body">
					
										
					<div class="form-group">
						<label>Select Country</label><span style="color:#FF0000;">*</span>
						
							<select class="form-control" name="country"  size="" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select country name" >
							
							<?php 
								$select_query = mysql_query("SELECT * FROM tblcountries where id='230'");
                                while($row = mysql_fetch_assoc($select_query)) { ?>
							
									<option selected="selected" value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?></option>
								 <?php } ?>		
							</select>
						
					</div>
					
					<div class="form-group">
						<label>State</label><span style="color:#FF0000;">*</span>
						<input name="state" id="state" 
						class="form-control" maxlength="55" data-validation-engine="validate[required]"
						data-errormessage-value-missing="Please enter state name" >
					</div>
					
										
				</div>
				<!-- /.box-body -->
							
				<div class="box-footer">
					<button type="submit"  name="submit_me"  class="btn btn-primary">Submit</button>
				</div>
			</form>
			</div>
			</div> 
				<!-- /.col -->
			  </div>
      <!-- /.row -->
		</section>
    <!-- /.content -->
</div>
<div class="clear"></div>
