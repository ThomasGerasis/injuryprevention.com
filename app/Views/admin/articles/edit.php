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
				<a href="<?php echo base_url('admin/articles'); ?>" class="breadcrumb-item">Articles</a>
				<span class="breadcrumb-item active"><?php echo $pageData['title']; ?></span>
			</div>
		</div>
		<div class="header-elements">
			<div class="breadcrumb justify-content-center">
				<?php if(!empty($page['id'])){?>
					<a href="<?php echo FRONT_SITE_URL.'preview/previewArticle/'.$page['id'].'/'.md5($page['id'].'_SOMALAB_'.$page['permalink']);?>" id="previewChangesBtn" target="_new" class="breadcrumb-elements-item"><i class="icon-file-eye"></i> preview changes</a>
					<?php if($page['published'] == 1){?>
						<a href="<?php echo FRONT_SITE_URL.$page['permalink'];?>" class="breadcrumb-elements-item" target="_new" title="Προβολή"><i class="icon-eye"></i> view</a>
					<?php }else{ ?>
						<a href="<?php echo FRONT_SITE_URL.'preview/previewArticle/'.$page['id'].'/'.md5($page['id'].'_SOMALAB_'.$page['permalink']);?>" target="_new" class="breadcrumb-elements-item" title="Προεπισκόπηση"><i class="icon-file-eye"></i> preview</a>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php $validation = \Config\Services::validation(); ?>
<div class="content">
	<?php if (!empty($page['id'])) { ?>
		<input id="edit_type" class="d-none" value="article">
		<input id="edit_type_id" class="d-none" value="<?php echo $page['id']; ?>">
		<input id="edit_back_url" class="d-none" value="<?php echo base_url('admin/articles'); ?>">
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

		<label>Subtitle</label>
		<div class="form-group row">
			<div class="col-12">
				<textarea id="short_title" name="short_title" class="form-control inline_editor"><?php echo @$page['short_title'];?></textarea>
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
				
				<label>Article category*</label>
				<div class="form-group">
					<?php $article_category_id = (isset($page['article_category_id']) ? $page['article_category_id'] : ''); ?>
					<select class="form-control" required name="article_category_id">
						<option value="" <?php echo (empty($article_category_id) ? 'selected="selected"' : ''); ?>>-- No parent category --</option>
						<?php foreach($categories as $parentCategory){?>
							<option value="<?php echo $parentCategory['id'];?>" <?php echo ($article_category_id == $parentCategory['id'] ? 'selected="selected"' : ''); ?>><?php echo $parentCategory['title'];?></option>
						<?php } ?>
					</select>
				</div>
					
				<label>Opener image (1100x619)</label>
				<div class="form-group">
					<div class="single-img-container">
						<div id="logo_filename-container" class="d-inline-block">
							<?php if (!empty($page['opener_image_id'])) {
								echo view('admin/widgets/_single_image_part', array(
									'input_name' => 'opener_image_id',
									'image_id' => $page['opener_image_id'],
									'filename' => get_image($page['opener_image_id'], 'rect400'),
									'watermark' => 0
								));
							} ?>
						</div>
						<button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left single-image-upload-btn"><b><i class="icon-image2"></i></b> <?php echo (empty($page['opener_image_id']) ? 'Choose' : 'Change'); ?> image</button>
						<input type="file" class="d-none single-image-upload" data-input-name="opener_image_id" data-target="#logo_filename-container" data-template="new-image-template" data-url="<?php echo base_url(); ?>admin/fileUpload/do_upload_image/rect400">
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

				<?php if(!empty($page['id'])){?>
					<label>Status</label>
					<div class="form-group row">
						<div class="col-12">
							<?php if(!empty($page['published'])){?>
								<p>Published on <?php echo date('d/m/Y H:i:s',strtotime($page['date_published']));?></p>
								<a href="<?php echo base_url('admin/articles/unpublish/'.$page['id']);?>" class="btn btn-warning confirm-action" data-confirmation="Do you want to unpublish the article?" title="Unpublish"><i class="icon-file-download"></i></a>
							<?php }else if(!empty($page['date_scheduled'])){ ?>
								<p>Scheduled<br/> for <?php echo date('d/m/Y H:i:s',strtotime($page['date_scheduled']));?></p>
								<a href="<?php echo base_url('admin/articles/publish/'.$page['id']);?>" class="btn btn-success confirm-action" data-confirmation="Do you want to publish the article?" title="Immediate publish"><i class="icon-file-upload"></i></a>
								<a href="<?php echo base_url('admin/articles/unschedule/'.$page['id']);?>" class="btn btn-warning confirm-action" data-confirmation="Do you want to unschedule the article?" title="Unschedule"><i class="icon-alarm-cancel"></i></a>
							<?php }else{ ?>
								<p>Not published</p>
								<p>
								<a href="<?php echo base_url('admin/articles/publish/'.$page['id']);?>" class="btn btn-success confirm-action" data-confirmation="Do you want to publish the article?" title="Immediate publish"><i class="icon-file-upload"></i></a>
								<a href="<?php echo base_url('admin/articles/delete/'.$page['id']);?>" class="btn btn-danger confirm-action" data-confirmation="Do you want to PERMANENTLY delete this article?" title="Permanent delete"><i class="icon-cross3"></i></a>
								</p>
								<p>Schedule</p>
								<div class="input-group">
									<span class="input-group-prepend">
										<span class="input-group-text"><i class="icon-calendar5"></i></span>
									</span>
									<input type="text" name="schedule_date" class="form-control pickadate-format" placeholder="" value="<?php echo date('Y-m-d');?>">
								</div>
								<div class="input-group">
									<span class="input-group-prepend">
										<span class="input-group-text"><i class="icon-watch2"></i></span>
									</span>
									<input type="text" id="mypickatime" name="schedule_time" class="form-control pickatime-format" value="">
								</div>
							<?php } ?>
						</div>
					</div>
					<hr/>
				<?php } ?>
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