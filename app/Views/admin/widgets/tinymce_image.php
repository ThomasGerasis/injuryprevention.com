<form method="post" id="image_form">
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Title</label>
		<div class="col-sm-10">
			<input name="title" value="" class="form-control">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Alt</label>
		<div class="col-sm-10">
			<input name="alt" value="" class="form-control">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Float</label>
		<div class="col-sm-10">
			<select class="form-control image_render">
				<option value="NORMAL" selected="selected">normal</option>
				<option value="FLOAT_RIGHT">float δεξιά</option>
				<option value="FLOAT_RIGHT_MD">float δεξιά (>768px)</option>
				<option value="FLOAT_LEFT">float αριστερά</option>
				<option value="FLOAT_LEFT_MD">float αριστερά (>768px)</option>
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Εικόνα</label>
		<div class="col-sm-10">
			<div id="tinymce-image" class="original">

			</div>
			<input type="file" class="d-none single-image-upload" data-input-name="tinymce_image" data-target="#tinymce-image" data-template="shimg-template" data-url="<?php echo base_url();?>admin/fileUpload/do_upload_image/original">
			<button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left single-image-upload-btn"><b><i class="icon-image2"></i></b> Επιλογή εικόνας</button>
			<button type="button" class="btn bg-info btn-labeled btn-labeled-left open-image-bank"><b><i class="icon-image2"></i></b> Image bank</button>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Dimensions</label>
		<div class="col-sm-10">
			<div class="input-group">
				<input name="width" value="" class="form-control" data-ratio="" placeholder="width">
				<span class="input-group-append">
					<span class="input-group-text">x</span>
				</span>
				<input name="height" value="" class="form-control" data-ratio="" placeholder="height">
			</div>
		</div>
	</div>
	<div class="form-group row actions d-none">
		<label class="col-form-label col-lg-2"></label>
		<div class="col-lg-10">
			<button type="submit" class="btn btn-primary">Εισαγωγή</button>
		</div>
	</div>
	
</form>
<script type="text/x-tmpl" id="shimg-template">
	<div class="card">
		<div class="card-header header-elements-inline p-1">
			<div class="header-elements">
				<div class="list-icons">
					<a class="list-icons-item" data-action="remove"></a>
				</div>
			</div>
		</div>

		<div class="card-body p-1">
			<input type="hidden" name="${input_name}" class="image_id-input" value="${image_id}">
			<img class="img-fluid img-filename" src="${filename}" />
			<div class="progress d-none">
				<div class="progress-bar progress-bar-success"></div>
			</div>
		</div>
	</div>
</script>