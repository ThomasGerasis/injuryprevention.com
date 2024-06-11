<?php
$sortingColumn = (!empty($sessionData['sortingColumn']) ? $sessionData['sortingColumn'] : 'modified_date');
$orderType = (!empty($sessionData['sortingType']) ? $sessionData['sortingType'] : 'desc');
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
					<td data-title="Title"><a href="<?php echo base_url('admin/pages/edit/' . $item['id']); ?>"><?php echo $item['title']; ?></a></td>
					<td data-title="Date modified"><?php echo date('d/m/Y H:i:s',strtotime($item['modified_date'])).' ('.$item['user_modified'].')'; ?></td>
					<td data-title="Actions">
						<?php if(!empty($item['published'])){?>
							<a href="<?php echo FRONT_SITE_URL.$item['permalink'];?>" class="btn btn-default" target="_new" title="View page"><i class="icon-eye"></i></a>
							<a href="<?php echo base_url('admin/pages/unpublish/'.$item['id']);?>" class="btn bg-warning btn-sm btn-confirm-action" data-confirmation="Do you want to unpublish the page <?php echo $item['title'];?>?" title="Unpublish"><i class="icon-file-download"></i></a>
						<?php }else{?>
							<a href="<?php echo FRONT_SITE_URL.'preview/previewPage/'.$item['id'].'/'.md5($item['id'].'_NBAPREVIEW_'.$item['permalink']);?>" target="_new" class="btn btn-default" title="Preview page"><i class="icon-file-eye"></i></a>
							<a href="<?php echo base_url('admin/pages/publish/'.$item['id']);?>" class="btn bg-success btn-sm btn-confirm-action" data-confirmation="Do you want to publish the page <?php echo $item['title'];?>?" title="Publish"><i class="icon-file-upload"></i></a>
						<?php }?>
						<a href="<?php echo base_url('admin/pages/edit/' . $item['id']); ?>" class="btn bg-primary btn-sm" title="Edit page"><i class="icon-pencil4"></i></a>
						<?php if(empty($item['published'])){?>
							<a href="<?php echo base_url('admin/pages/delete/'.$item['id']);?>" class="btn btn-danger btn-sm btn-confirm-action" data-confirmation="Do you want to PERMANENTLY delete this page;" title="Permanent delete"><i class="icon-cross3"></i></a>
						<?php }?>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?php echo ajax_pagination($count, $page, base_url('admin/pages/index'), base_url('pages/getPaginatedList')); ?>