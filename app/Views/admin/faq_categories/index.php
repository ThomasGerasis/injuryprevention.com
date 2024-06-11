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
				<span class="breadcrumb-item active"><?php echo $pageData['title'];?></span>
			</div>
		</div>

	</div>
</div>

<div class="content">
	<div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table datatable-basic dataTable no-footer table-hover table-bordered table-striped ci_datatable">
					<thead class="table-dark">
						<tr>
							<th>id</th>
							<th>Title</th>
							<th>Order</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($list as $item){?>
							<tr>
								<td data-title="id"><?php echo $item['id'];?></td>
								<td data-title="Title"><a href="<?php echo base_url('faqCategories/edit/'.$item['id']);?>"><?php echo $item['title'];?></a></td>
								<td data-title="Order"><?php echo $item['order_num'];?></td>
								<td data-title="Actions">
									<a href="<?php echo base_url('faqCategories/edit/' . $item['id']); ?>" class="btn bg-primary btn-sm" title="Edit FAQ category"><i class="icon-pencil4"></i></a>
									<a href="<?php echo base_url('faqCategories/delete/'.$item['id']);?>" class="btn bg-danger btn-sm btn-confirm-action" data-confirmation="Θες να διαγράψεις το FAQ category <?php echo $item['title'];?>;" title="Delete"><i class="icon-cross3"></i></a>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>