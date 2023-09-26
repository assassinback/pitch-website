
<?php if (isset($breadcrumb) && is_array($breadcrumb) && count($breadcrumb) > 0) { ?>

<hr class="p_hdr_br">

<div class="p_breadcrumb">
	<div class="container">
		<div class="row">
			
			<ul class="col-xs-12">
				<li><a href="<?php echo getLink(); ?>">Home</a></li>
                <?php foreach ($breadcrumb as $breadcrumb_index => $breadcrumb_data) { ?>
                    <?php if (($breadcrumb_index + 1) == count($breadcrumb)) { ?>
                        <li><span><?php echo $breadcrumb_data['title']; ?></span></li>
                    <?php } else { ?>
                        <li><a href="<?php echo $breadcrumb_data['link']; ?>"><?php echo $breadcrumb_data['title']; ?></a></li>
                    <?php } ?>
                <?php } ?>
			</ul>
			
		</div>
	</div>
</div>
<?php } ?>