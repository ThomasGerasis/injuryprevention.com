<?php
$sortingColumn = (!empty($sessionData['sortingColumn']) ? $sessionData['sortingColumn'] : 'title');
$orderType = (!empty($sessionData['sortingType']) ? $sessionData['sortingType'] : 'asc');
$sortingType = 'sorting_' . $orderType;
$orderTypeOpposite = ($orderType == 'asc' ? 'desc' : 'asc');
?>
<div class="table-responsive">
	<table class="table datatable-basic dataTable no-footer table-hover table-bordered table-striped ci_datatable">
		<thead class="table-dark">
			<tr>
				<th>id</th>
				<th class="do-sorting <?php echo ($sortingColumn == 'title' ? $sortingType : 'sorting'); ?>" data-sorting-col="title" data-sorting-type="<?php echo ($sortingColumn == 'title' ? $orderTypeOpposite : $orderType); ?>">Title</th>
				<th class="do-sorting <?php echo ($sortingColumn == 'modified_date' ? $sortingType : 'sorting'); ?>" data-sorting-col="modified_date" data-sorting-type="<?php echo ($sortingColumn == 'modified_date' ? $orderTypeOpposite : $orderType); ?>">Date modified</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $item) { ?>
				<tr>
					<td data-title="Id"><?php echo $item['id']; ?></td>
					<td data-title="Title"><a href="<?php echo base_url('multiUseContents/edit/' . $item['id']); ?>"><?php echo $item['title']; ?></a></td>
					<td data-title="Date modified"><?php echo date('d/m/Y H:i:s',strtotime($item['modified_date'])).' ('.$item['user_modified'].')'; ?></td>
					<td data-title="Actions">
						<a href="<?php echo base_url('multiUseContents/edit/' . $item['id']); ?>" class="btn bg-primary btn-sm" title="Edit content"><i class="icon-pencil4"></i></a>
						<a href="<?php echo base_url('multiUseContents/duplicate/'.$item['id']);?>" class="btn btn-default btn-sm" title="Duplicate"><i class="icon-copy3"></i></a>
						<a href="<?php echo base_url('multiUseContents/delete/'.$item['id']);?>" class="btn bg-danger btn-sm btn-confirm-action" data-confirmation="Θες να διαγράψεις το multi use content <?php echo $item['title'];?>;" title="Delete"><i class="icon-cross3"></i></a>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?php echo ajax_pagination($count, $page, base_url('multiUseContents/index'), base_url('multiUseContents/getPaginatedList')); ?>