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
				<a href="<?php echo base_url('admin/pages'); ?>" class="breadcrumb-item">Pages</a>
				<span class="breadcrumb-item active"><?php echo $pageData['title']; ?></span>
			</div>
		</div>
		<div class="header-elements">
			<div class="breadcrumb justify-content-center">
				<?php if(!empty($page['id'])){?>
					<a href="<?php echo FRONT_SITE_URL.'preview/previewPage/'.$page['id'].'/'.md5($page['id'].'_FXPREVIEW_'.$page['permalink']);?>" id="previewChangesBtn" target="_new" class="breadcrumb-elements-item"><i class="icon-file-eye"></i> preview changes</a>
					<?php if($page['published'] == 1){?>
						<a href="<?php echo FRONT_SITE_URL.$page['permalink'];?>" class="breadcrumb-elements-item" target="_new" title="Προβολή"><i class="icon-eye"></i> view</a>
					<?php }else{ ?>
						<a href="<?php echo FRONT_SITE_URL.'preview/previewPage/'.$page['id'].'/'.md5($page['id'].'_XPREVIEW_'.$page['permalink']);?>" target="_new" class="breadcrumb-elements-item" title="Προεπισκόπηση"><i class="icon-file-eye"></i> preview</a>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php $validation = \Config\Services::validation(); ?>
<div class="content">
	<?php if (!empty($page['id'])) { ?>
		<input id="edit_type" class="d-none" value="page">
		<input id="edit_type_id" class="d-none" value="<?php echo $page['id']; ?>">
		<input id="edit_back_url" class="d-none" value="<?php echo base_url('admin/pages'); ?>">
	<?php } ?>
	<form method="post" class="myvalidation" id="tinymce_form">
		<div class="form-group row">
			<div class="col-md-6">
				<label>Title*</label>
				<input type="text" name="title" class="form-control <?php echo (empty($page['published']) ? 'fill-permalink check-permalink' : '');?>" data-type-id="<?php echo (empty($page['id'])?0:$page['id']);?>" data-permalink-name="permalink" required placeholder="Title" value="<?php echo htmlspecialchars(@$page['title']);?>">
			</div>
			<div class="col-md-6">
				<label>Permalink <span class="text-danger">*</span></label>
				<input type="text" name="permalink" class="form-control check-permalink-input" <?php echo (empty($page['published']) ? 'required' : 'readonly');?> placeholder="Permalink" value="<?php echo @$page['permalink'];?>" data-original-value="<?php echo @$page['permalink'];?>">
			</div>
		</div>
		<label>SEO Title</label>
		<div class="form-group">
			<input type="text" name="seo_title" class="form-control" placeholder="SEO Title" value="<?php echo htmlspecialchars(@$page['seo_title']);?>">
		</div>
		<label>SEO description</label>
		<div class="form-group">
			<textarea rows="3" cols="3" name="seo_description" class="form-control"><?php echo htmlspecialchars(@$page['seo_description']);?></textarea>
		</div>

		<div class="row">
			<div class="col-md-8 col-lg-9">
				<div class="mb-3">
					<label>Content</label>
					<textarea class="tinymce_editor" name="content" class="form-control"><?php echo htmlentities(@$page['content']);?></textarea>
					<div class="d-none">
						<div id="qlinks"><?php //echo json_encode($links);?></div>
						<select id="shortcodes">
							<?php $shortcodes = getShortcodes();
							foreach($shortcodes as $scode=>$sopts){?>
								<option value="<?php echo $scode;?>" data-filters="<?php echo count($sopts['attrs']);?>"><?php echo $sopts['name'];?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-lg-3">
				<label>Background image (1920x1080)</label>
				<div class="form-group">
					<div class="single-img-container">
						<div id="logo_filename-container" class="d-inline-block">
							<?php if (!empty($page['bg_image_id'])) {
								echo view('admin/widgets/_single_image_part', array(
									'input_name' => 'bg_image_id',
									'image_id' => $page['bg_image_id'],
									'filename' => get_image($page['bg_image_id'], 'rect400'),
									'watermark' => 0
								));
							} ?>
						</div>
						<button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left single-image-upload-btn"><b><i class="icon-image2"></i></b> <?php echo (empty($page['bg_image_id']) ? 'Choose' : 'Change'); ?> logo</button>
						<input type="file" class="d-none single-image-upload" data-input-name="bg_image_id" data-target="#logo_filename-container" data-template="new-image-template" data-url="<?php echo base_url(); ?>admin/fileUpload/do_upload_image/rect400">
						<button type="button" class="btn bg-info btn-labeled btn-labeled-left open-image-bank"><b><i class="icon-image2"></i></b> Image bank</button>
					</div>
				</div>

				<label>Social title</label>
				<div class="form-group">
					<input type="text" class="form-control" name="social_title" value="<?php echo @$page['social_title']; ?>">
				</div>

				<label>Social image</label>
				<div class="form-group">
					<div class="single-img-container">
						<div id="social_image-container" class="d-inline-block">
							<?php if (!empty($page['social_image_id'])) {
								echo view('admin/widgets/_single_image_part', array(
									'input_name' => 'social_image_id',
									'image_id' => $page['social_image_id'],
									'filename' => get_image($page['social_image_id'], 'social'),
									'watermark' => 0
								));
							} ?>
						</div>
						<button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left single-image-upload-btn"><b><i class="icon-image2"></i></b> <?php echo (empty($page['social_image_id']) ? 'Choose' : 'Change'); ?> image</button>
						<input type="file" class="d-none single-image-upload" data-input-name="social_image_id" data-target="#social_image-container" data-template="new-image-template" data-url="<?php echo base_url(); ?>admin/fileUpload/do_upload_image/social">
						<button type="button" class="btn bg-info btn-labeled btn-labeled-left open-image-bank"><b><i class="icon-image2"></i></b> Image bank</button>
					</div>
				</div>
			</div>
		</div>
		
		<div class="card">
			<div class="card-header header-elements-inline">
				<h5 class="card-title">FAQ  <button type="button" class="btn btn-icon rounded-round btn-light add-template-btn" data-target="faq-container" data-template="faq-part" data-counter="faq-counter"><i class="icon-plus3"></i></button> </h5>
				<div class="header-elements">
					<div class="list-icons">
						<a class="list-icons-item" data-action="collapse"></a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<label>FAQ title</label>
				<div class="form-group">
					<div class="input-group">
						<input type="text" class="form-control" name="faq_title" value="<?php echo @$page['faq_title']; ?>">
						<div class="input-group-append">
							<select name="faq_heading" class="form-control">
								<option value="" <?php echo (empty(@$page['faq_heading']) ? 'selected="selected"' : '');?>> -- </option>
								<option value="H2" <?php echo (@$page['faq_heading'] == 'H2' ? 'selected="selected"' : '');?>>h2</option>
								<option value="H3" <?php echo (@$page['faq_heading'] == 'H3' ? 'selected="selected"' : '');?>>h3</option>
								<option value="H4" <?php echo (@$page['faq_heading'] == 'H4' ? 'selected="selected"' : '');?>>h4</option>
							</select>
						</div>
					</div>
				</div>

				<label>FAQ subtitle</label>
				<div class="form-group">
					<input type="text" class="form-control" name="faq_subtitle" value="<?php echo @$page['faq_subtitle']; ?>">
				</div>

				<label>FAQ text</label>
				<div class="form-group">
					<textarea class="form-control inline_editor" id="faq_content" name="faq_content"><?php echo @$page['faq_content'];?></textarea>
				</div>

				<?php $faq_counter = 0;?>
				<div id="faq-container">
					<?php if(!empty($page_faqs)){
						foreach($page_faqs as $faq){
							echo view('admin/widgets/_faq_part', array(
								'faq' => $faq,
								'counter' => $faq_counter,
								'order' => $faq['order_num'],
								'hasCategories' => true
							));
							$faq_counter++;
						}
					} ?>
				</div>
				<input id="faq-counter" class="d-none" type="text" value="<?php echo $faq_counter;?>">
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
<script type="text/x-tmpl" id="faq-part">
	<?php echo view('admin/widgets/_faq_part', array(
		'faq' => array(
			'id' => '',
			'question' => '',
			'answer' => '',
			'order_num' => ''
		),
		'counter' => '${counter}',
		'order' => '${order}',
		'hasCategories' => 0
	)); ?>
</script>