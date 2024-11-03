<?php
$sortingColumn = (!empty($sessionData['sortingColumn']) ? $sessionData['sortingColumn'] : 'username');
$orderType = (!empty($sessionData['sortingType']) ? $sessionData['sortingType'] : 'asc');
$sortingType = 'sorting_' . $orderType;
$orderTypeOpposite = ($orderType == 'asc' ? 'desc' : 'asc');
?>
<div class="table-responsive">
	<table class="table datatable-basic dataTable no-footer table-hover table-bordered table-striped ci_datatable">
		<thead class="table-dark">
			<tr>
				<th>id</th>
				<th>Email</th>
				<th class="do-sorting <?php echo ($sortingColumn == 'date_added' ? $sortingType : 'sorting'); ?>" data-sorting-col="date_added" data-sorting-type="<?php echo ($sortingColumn == 'date_added' ? $orderTypeOpposite : $orderType); ?>">Date added</th>
				<th class="do-sorting <?php echo ($sortingColumn == 'is_active' ? $sortingType : 'sorting'); ?>" data-sorting-col="is_active" data-sorting-type="<?php echo ($sortingColumn == 'is_active' ? $orderTypeOpposite : $orderType); ?>">Active</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $item) { ?>
				<tr>
					<td data-title="Id"><?php echo $item['id']; ?></td>
					<td data-title="Email"><?php echo $item['email']; ?></td>
					<td data-title="Date added"><?php echo date('d/m/Y H:i:s',strtotime($item['date_added'])); ?></td>
					<td data-title="Active">
						<?php if(!empty($item['is_active'])){?>
							YES <?php /*<i class="icon-user-check text-success" data-toggle="tooltip" title="Active"></i>*/?>
							<a href="<?php echo base_url('admin/siteUsers/deactivate/'.$item['id']);?>" class="btn bg-danger btn-sm btn-confirm-action" data-confirmation="Do you want to deactivate the user <?php echo $item['firstname'];?>?" title="Deactivate"><i class="icon-user-block"></i></a>
						<?php }else{?>
							NO <?php /*<i class="icon-user-block text-danger" data-toggle="tooltip" title="Inactive"></i>*/?>
							<a href="<?php echo base_url('admin/siteUsers/activate/'.$item['id']);?>" class="btn bg-success btn-sm btn-confirm-action" data-confirmation="Do you want to activate the user <?php echo $item['firstname'];?>?" title="Activate"><i class="icon-user-check"></i></a>
						<?php }?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<?php echo ajax_pagination($count, $page, base_url('admin/site_users/index'), base_url('admin/site_users/getPaginatedList')); ?>