<?php
if(!in_array(1,$tes_mod)) { 
echo "<div class='grid_12'><div class='message error'><p>You don't have permission to access this page</p></div></div>";
die;
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>City</h1>
		  <ol class="breadcrumb">
			<li><a href="<?php echo ADMIN_URL; ?>main.php"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?php echo ADMIN_URL; ?>main.php?pg=viewcity"><i class="fa fa-location-arrow"></i>City</a></li>
			
		  </ol>
	</section>
	    <!-- Main content -->
		 <section class="content">
			  <div class="row">
					<div class="col-md-6">
					  <!-- general form elements -->
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">Add City</h3>
							</div>
						<div class="error"></div>
						
						<form name="Admin" action="main.php?pg=cityproc" method="post" class="validateForm">
			<input type="hidden" value="add" name="act">
			 <div class="box-body">
					
										
					<div class="form-group">
						<label>State</label><span style="color:#FF0000;">*</span>
						
							<select class="form-control" name="state"  size="" data-validation-engine="validate[required]" data-errormessage-value-missing="Please select state name" >
							
							<?php 
								$select_query = mysql_query("SELECT * FROM tblstates where country_id='230' ORDER By 'name' DESC");
                                while($row = mysql_fetch_assoc($select_query)) { ?>
							
									<option selected="selected" value="<?php echo $row['id'] ?>"><?php echo $row['name']; ?></option>
								 <?php } ?>		
							</select>
						
					</div>
					
					<div class="form-group">
						<label>City</label><span style="color:#FF0000;">*</span>
						<input name="city" id="city" 
						class="form-control" maxlength="55" data-validation-engine="validate[required]"
						data-errormessage-value-missing="Please enter city name" >
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
