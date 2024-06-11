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
				<a href="<?php echo base_url('faqCategories'); ?>" class="breadcrumb-item">FAQ categories</a>
				<span class="breadcrumb-item active"><?php echo $pageData['title']; ?></span>
			</div>
		</div>
	</div>
</div>

<?php $validation = \Config\Services::validation(); ?>
<div class="content">
	<?php if (!empty($category['id'])) { ?>
		<input id="edit_type" class="d-none" value="faqCategory">
		<input id="edit_type_id" class="d-none" value="<?php echo $category['id']; ?>">
		<input id="edit_back_url" class="d-none" value="<?php echo base_url('faqCategories'); ?>">
	<?php } ?>
	<form method="post" class="myvalidation" id="tinymce_form">
		<div class="form-group row">
			<label class="col-form-label col-lg-1">Title*</label>
			<div class="col-lg-11">
				<input type="text" class="form-control" required name="title" value="<?php echo @$category['title'];?>">
			</div>
		</div>
		
		<div class="form-group row">
			<label class="col-form-label col-lg-1">Order</label>
			<div class="col-lg-11">
				<input type="text" class="form-control int-input" name="order_num" value="<?php echo @$category['order_num'];?>">
			</div>
		</div>

		<div class="form-group row">
			<div class="col-12 text-center">
				<button type="submit" class="btn btn-primary">Save <i class="icon-database-add ml-2"></i></button>
			</div>
		</div>
	</form>
</div>
