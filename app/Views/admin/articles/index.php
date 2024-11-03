<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><?php echo $pageData['title'];?></h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
		</div>

		<div class="header-elements d-none">
			<a href="<?php echo base_url('admin/articles/edit');?>" class="btn btn-labeled btn-labeled-right bg-primary">New article <b><i class="icon-plus3"></i></b></a>
		</div>
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url('admin/dashboard');?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
				<span class="breadcrumb-item active"><?php echo $pageData['title'];?></span>
			</div>
		</div>

	</div>
</div>

<div class="content">
	<div class="card">
	<div class="card-body">
			<div class="row">
				<div class="col-12">
					<form class="form-inline" id="ci_datatable_form" data-ajax-url="<?php echo base_url('admin/articles/getPaginatedList'); ?>">
						<div class="input-group mr-2">
							<span class="input-group-prepend">
								<button class="btn btn-light btn-icon" type="button" id="clear_term"><i class="icon-trash"></i></button>
							</span>
							<input type="text" class="form-control" name="term" value="<?php echo (isset($sessionData['term']) ? $sessionData['term'] : ''); ?>" placeholder="Search...">
							<span class="input-group-append">
								<button class="btn btn-light btn-icon" type="button" id="search_term"><i class="icon-search4"></i></button>
							</span>
						</div>
						<?php $article_category_id = (isset($sessionData['article_category_id']) ? $sessionData['article_category_id'] : 'all'); ?>
						<select class="form-control submit-on-change mr-2" name="article_category_id">
							<option value="all" <?php echo ($article_category_id == 'all' ? 'selected="selected"' : ''); ?>>--Article category--</option>
							<?php foreach($categories as $parentCategory){?>
								<option value="<?php echo $parentCategory['id'];?>" <?php echo ($article_category_id == $parentCategory['id'] ? 'selected="selected"' : ''); ?>><?php echo $parentCategory['title'];?></option>
							<?php } ?>
						</select>
						<?php $published = (isset($sessionData['published']) ? $sessionData['published'] : 'all'); ?>
						<select class="form-control submit-on-change mr-2" name="published">
							<option value="all" <?php echo ($published == 'all' ? 'selected="selected"' : ''); ?>>Status</option>
							<option value="1" <?php echo ($published == '1' ? 'selected="selected"' : ''); ?>>Published</option>
							<option value="0" <?php echo ($published == '0' ? 'selected="selected"' : ''); ?>>Not published</option>
						</select>
						<button type="submit" class="btn btn-secondary" title="refresh results"><i class="icon-rotate-cw3 "></i></button>
						<input type="hidden" name="sortingColumn" value="<?php echo (!empty($sessionData['sortingColumn']) ? $sessionData['sortingColumn'] : 'modified_date'); ?>">
						<input type="hidden" name="sortingType" value="<?php echo (!empty($sessionData['sortingType']) ? $sessionData['sortingType'] : 'desc'); ?>">
						<input type="hidden" name="page" id="current_page" value="<?php echo $page; ?>">
						<input type="hidden" name="form_data_changed" id="form_data_changed" value="0">
					</form>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<p class="text-center mb-0">&nbsp;<span id="ci_datatable_loader" style="display: none;"><img src="<?php echo base_url('admin/assets/img/sp-loading.gif'); ?>"></span></p>
					<div id="ci_datatable_container">
						<?php echo view(
							'admin/articles/_table_data',
							['list' => $list, 'count' => $count, 'page' => $page, 'sessionData' => $sessionData, 'categories' => $categories]
						); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>

<div class="modal fade" id="schedule_modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Προγραμματισμός δημοσίευσης</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="schedule_publish_form" data-ajax-url="<?php echo base_url('admin/analyseis/schedule/');?>">
					<div class="form-group">
						<label class="col-sm-2 control-label">Ημ/νία*</label>
						<div class="col-sm-10">
							<div class="input-group">
								<span class="input-group-prepend">
									<span class="input-group-text"><i class="icon-calendar5"></i></span>
								</span>
								<input type="text" name="schedule_date" class="form-control pickadate-format" placeholder="" value="<?php echo date('Y-m-d');?>">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">Ώρα*</label>
						<div class="col-sm-10">
							<div class="input-group">
								<span class="input-group-prepend">
									<span class="input-group-text"><i class="icon-watch2"></i></span>
								</span>
								<input type="text" id="mypickatime" name="schedule_time" class="form-control pickatime-format" value="">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<input type="submit" class="btn btn-primary" value="Προγραμματισμός">
						</div>
					</div>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->