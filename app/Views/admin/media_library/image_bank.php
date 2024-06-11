<div class="d-flex justify-content-between">
	<form class="form-inline" id="image_Bank_form">
		<div class="input-group mr-2">
			<span class="input-group-prepend">	
				<button class="btn btn-light btn-icon" type="button" id="clear_term"><i class="icon-trash"></i></button>
			</span>
			<input type="text" class="form-control" id="term" value="" placeholder="Search...">
			<span class="input-group-append">
				<button class="btn btn-light btn-icon" type="submit"><i class="icon-search4"></i></button>
			</span>
		</div>
	</form>
	<div>
		<a href="#" id="prev-page" style="display:none" data-page="1">&laquo; προηγούμενη σελίδα</a>
		<a href="#" id="next-page" data-page="1">επόμενη σελίδα &raquo;</a>
	</div>
</div>
<div id="image_container">
	<?php echo view('admin/media_library/image_bank_content',
		['images' => $images]
	); ?>
</div>