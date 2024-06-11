<div class="page-header page-header-light">
	<div class="page-header-content header-elements-md-inline">
		<div class="page-title d-flex">
			<h4><?php echo $pageData['title'];?></h4>
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
		<div class="card-header header-elements-inline">
			<h5 class="card-title">Menu items  <button type="button" class="btn btn-icon rounded-round btn-light add-menu-item" data-target="link-container" data-template="link-part" data-counter="link-counter"><i class="icon-plus3"></i></button> </h5>
		</div>
		<div class="card-body">
			<p><em>Use relative url "homepage" for link to homepage.</em></p>
			<form method="post">
				<?php $links_counter = 0;?>
				<div id="link-container">
					<?php if(!empty($links)){
						$open_parent = false; $parent = false;
						foreach($links as $link){
							echo view('admin/menu/_menu_item', array(
								'link' => $link,
								'counter' => $links_counter,
								'order' => $link['order_num'],
							));
							$links_counter++;
						}
					} ?>
				</div>
				<input id="link-counter" class="d-none" type="text" value="<?php echo $links_counter;?>">
				<div class="form-group row">
					<div class="col-12">
						<button type="submit" class="btn btn-primary">Save menu <i class="icon-database-add ml-2"></i></button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/x-tmpl" id="link-part">
	<?php echo view('admin/menu/_menu_item', array(
		'link' => array(
			'id' => '',
			'type' => 'link',
			'order_num' => '',
			'title' => '',
			'image_id' => '',
			'relative_url' => '',
			'external_url' => '',
		),
		'counter' => '${counter}',
		'order' => '${order}',
	)); ?>
</script>

<script type="text/x-tmpl" id="new-image-template">
	<?php echo view('admin/widgets/_single_image_part', array(
		'image_id' => '${image_id}',
		'input_name' => '${input_name}',
		'filename' => '${filename}'
	)); ?>
</script>