<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><?php echo $pageData['title'];?></h4>
		</div>
		
		<div class="header-elements d-none">
			<a href="<?php echo base_url('faqCategories/sortOrder');?>" class="btn btn-labeled btn-labeled-right bg-success mr-2">Ordering<b><i class="icon-sort"></i></b></a>
			<a href="<?php echo base_url('faqCategories/edit');?>" class="btn btn-labeled btn-labeled-right bg-primary">New category<b><i class="icon-plus3"></i></b></a>
		</div>
		
	</div>

	<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
		<div class="d-flex">
			<div class="breadcrumb">
				<a href="<?php echo base_url('admin/dashboard');?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
				<a href="<?php echo base_url('faqCategories'); ?>" class="breadcrumb-item">FAQ categories</a>
				<span class="breadcrumb-item active"><?php echo $pageData['title'];?></span>
			</div>
		</div>

	</div>
</div>

<div class="content">
	<div class="card">
		<div class="card-body">
			<form method="post">
				<div class="items-sortable">
					<?php $order = 0;
					foreach($categories as $category){
						$order++;?>
						<div class="card mb-1">
							<h5 class="card-title mb-0 p-2"><i class="icon-sort"></i> <span class="order_num"><?php echo $order;?></span>. <?php echo $category['title'];?></h5>
							<input type="hidden" class="sort_order" name="category[<?php echo $category['id'];?>]" value="<?php echo $order;?>">
						</div>
					<?php } ?>
				</div>
				<hr/>
				<div class="text-center">
					<button type="submit" class="btn btn-primary">Save <i class="icon-database-add ml-2"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>