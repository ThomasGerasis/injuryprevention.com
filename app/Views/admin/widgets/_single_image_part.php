<div class="card">
	<div class="card-header header-elements-inline p-1">
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="remove"></a>
			</div>
		</div>
	</div>

	<div class="card-body p-1">
		<input type="hidden" name="<?php echo $input_name;?>" class="image_id-input" value="<?php echo $image_id;?>">
		<img class="img-fluid img-filename" loading="lazy" src="<?php echo $filename;?>" />
		<div class="progress d-none">
			<div class="progress-bar progress-bar-success"></div>
		</div>
	</div>
</div>