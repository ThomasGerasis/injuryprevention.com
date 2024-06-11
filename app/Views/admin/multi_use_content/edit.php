<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><?php echo $pageData['title']; ?></h4>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url('admin/dashboard'); ?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
				<a href="<?php echo base_url('multiUseContents'); ?>" class="breadcrumb-item">Multi use content</a>
				<span class="breadcrumb-item active"><?php echo $pageData['title']; ?></span>
			</div>
		</div>
	</div>
</div>

<?php $validation = \Config\Services::validation(); ?>
<div class="content">
	<?php if (!empty($page['id'])) { ?>
		<input id="edit_type" class="d-none" value="multiUseContent">
		<input id="edit_type_id" class="d-none" value="<?php echo $page['id']; ?>">
		<input id="edit_back_url" class="d-none" value="<?php echo base_url('multiUseContents'); ?>">
	<?php } ?>
	<form method="post" class="myvalidation" id="tinymce_form">
		<label>Title</label>
		<div class="form-group">
			<input type="text" name="title" class="form-control" placeholder="Title" required value="<?php echo htmlspecialchars(@$page['title']);?>">
		</div>
		<div class="my-3">
			<label>Content</label>
			<textarea class="tinymce_editor" name="content" class="form-control"><?php echo htmlentities(@$page['content']);?></textarea>
			<div class="d-none">
				<div id="qlinks"><?php //echo json_encode($links);?></div>
				<select id="shortcodes">
					<?php $shortcodes = getShortcodes();
					foreach($shortcodes as $scode=>$sopts){
						if(!empty($sopts['tabbed_content'])) continue;?>
						<option value="<?php echo $scode;?>" data-filters="<?php echo count($sopts['attrs']);?>"><?php echo $sopts['name'];?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		
		<div class="form-group row">
			<div class="col-12 text-center">
				<button type="submit" class="btn btn-primary">Save <i class="icon-database-add ml-2"></i></button>
			</div>
		</div>
	</form>
</div>

<script type="text/x-tmpl" id="new-image-template">
	<?php echo view('admin/widgets/_single_image_part', array(
		'image_id' => '${image_id}',
		'input_name' => '${input_name}',
		'filename' => '${filename}'
	)); ?>
</script>