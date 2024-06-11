<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><?php echo $pageData['title'];?></h4>
			<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
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
					<form class="form-inline" id="ci_datatable_form" data-ajax-url="<?php echo base_url('admin/siteUsers/getPaginatedList'); ?>">
						<div class="input-group mr-2">
							<span class="input-group-prepend">
								<button class="btn btn-light btn-icon" type="button" id="clear_term"><i class="icon-trash"></i></button>
							</span>
							<input type="text" class="form-control" name="term" value="<?php echo (isset($sessionData['term']) ? $sessionData['term'] : ''); ?>" placeholder="Search...">
							<span class="input-group-append">
								<button class="btn btn-light btn-icon" type="button" id="search_term"><i class="icon-search4"></i></button>
							</span>
						</div>
						<?php $filter_type = (isset($sessionData['filter_type']) ? $sessionData['filter_type'] : 'all'); ?>
						<select class="form-control submit-on-change mr-2" name="filter_type">
							<option value="all" <?php echo ($filter_type == 'all' ? 'selected="selected"' : ''); ?>>All types</option>
							<option value="isStreamer" <?php echo ($filter_type == 'isStreamer' ? 'selected="selected"' : ''); ?>>Streamers</option>
							<option value="isModerator" <?php echo ($filter_type == 'isModerator' ? 'selected="selected"' : ''); ?>>Moderators</option>
							<option value="isSimple" <?php echo ($filter_type == 'isSimple' ? 'selected="selected"' : ''); ?>>Simple users</option>
						</select>
						<?php $is_active = (isset($sessionData['is_active']) ? $sessionData['is_active'] : 'all'); ?>
						<select class="form-control submit-on-change mr-2" name="is_active">
							<option value="all" <?php echo ($is_active == 'all' ? 'selected="selected"' : ''); ?>>All statuses</option>
							<option value="1" <?php echo ($is_active == '1' ? 'selected="selected"' : ''); ?>>Active</option>
							<option value="0" <?php echo ($is_active == '0' ? 'selected="selected"' : ''); ?>>Inactive</option>
						</select>
						<?php $date_from = (isset($session_data['date_from'])?$session_data['date_from']:'');?>
						<div class="input-group mr-2">
							<span class="input-group-prepend" data-toggle="tooltip" title="Added past this date">
								<span class="input-group-text"><i class="icon-calendar5"></i></span>
							</span>
							<input type="text" name="date_from" id="datatable_date_selector" class="form-control" placeholder="" value="<?php echo $date_from;?>">
						</div>
						<button type="submit" class="btn btn-secondary" title="refresh results"><i class="icon-rotate-cw3 "></i></button>
						<a href="<?php echo base_url('admin/siteUsers/exportResults');?>" class="btn btn-primary ml-2" data-toggle="tooltip" title="export to excel"><i class="icon-file-excel "></i></a>
						<input type="hidden" name="sortingColumn" value="<?php echo (!empty($sessionData['sortingColumn']) ? $sessionData['sortingColumn'] : 'username'); ?>">
						<input type="hidden" name="sortingType" value="<?php echo (!empty($sessionData['sortingType']) ? $sessionData['sortingType'] : 'asc'); ?>">
						<input type="hidden" name="page" id="current_page" value="<?php echo $page; ?>">
						<input type="hidden" name="form_data_changed" id="form_data_changed" value="0">
					</form>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<p class="text-center mb-0">&nbsp;<span id="ci_datatable_loader" style="display: none;"><img src="<?php echo base_url('assets/img/sp-loading.gif'); ?>"></span></p>
					<div id="ci_datatable_container">
						<?php echo view(
							'admin/site_users/_table_data',
							['list' => $list, 'count' => $count, 'page' => $page, 'sessionData' => $sessionData]
						); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>