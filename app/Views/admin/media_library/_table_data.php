<?php
$sortingColumn = (!empty($sessionData['sortingColumn']) ? $sessionData['sortingColumn'] : 'id');
$orderType = (!empty($sessionData['sortingType']) ? $sessionData['sortingType'] : 'desc');
$sortingType = 'sorting_' . $orderType;
$orderTypeOpposite = ($orderType == 'asc' ? 'desc' : 'asc');
?>
<div class="table-responsive">
	<table class="table datatable-basic dataTable no-footer table-hover table-bordered table-striped ci_datatable">
		<thead class="table-dark">
			<tr>
				<th class="do-sorting <?php echo ($sortingColumn == 'id' ? $sortingType : 'sorting'); ?>" data-sorting-col="id" data-sorting-type="<?php echo ($sortingColumn == 'id' ? $orderTypeOpposite : $orderType); ?>">id</th>
				<th>Image</th>
				<th>Info</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $item) { ?>
				<tr>
					<td data-title="Id"><?php echo $item['id']; ?></td>
					<td data-title="Image" style="max-width:300px;"><img class="img-fluid" src="<?php echo get_image_url($item['file_name'], 'original'); ?>"><br/><?php echo $item['width'].'x'.$item['height']; ?></td>
					<td data-title="Info">
						<form class="ajax-form" method="post" action="<?php echo base_url('admin/mediaLibrary/update/' . $item['id']); ?>">
							<div class="form-group row">
								<label class="col-form-label col-lg-2">Τίτλος*</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" required name="title" value="<?php echo $item['title'];?>">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label col-lg-2">Alt</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" name="seo_alt" value="<?php echo $item['seo_alt'];?>">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label col-lg-2">Description</label>
								<div class="col-lg-10">
									<textarea class="form-control" name="seo_description"><?php echo $item['seo_description'];?></textarea>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label col-lg-2"></label>
								<div class="col-lg-10">
									<button type="submit" class="btn btn-primary">Save <i class="icon-database-add ml-2"></i></button>
									<span class="ci_save_loader" style="display: none;"><img src="<?php echo base_url('admin/assets/img/sp-loading.gif'); ?>"></span>
								</div>
							</div>
						</form>
					</td>
					<td data-title="Actions">
						<a href="<?php echo base_url('admin/mediaLibrary/delete/'.$item['id']);?>" class="btn bg-danger btn-sm btn-confirm-action" data-confirmation="Do you really want to permanently delete the image?" title="Delete image"><i class="icon-cross3"></i></a>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?php echo ajax_pagination($count, $page, base_url('admin/mediaLibrary/index'), base_url('admin/mediaLibrary/getPaginatedList')); ?>