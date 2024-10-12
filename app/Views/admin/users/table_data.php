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
				<th class="do-sorting <?php echo ($sortingColumn == 'username' ? $sortingType : 'sorting'); ?>" data-sorting-col="username" data-sorting-type="<?php echo ($sortingColumn == 'username' ? $orderTypeOpposite : $orderType); ?>">Display name</th>
				<th>Email</th>
				<th>Ρόλοι</th>
				<th>Ενέργειες</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $user) { ?>
				<tr>
					<td data-title="Id"><?php echo $user['id']; ?></td>
					<td data-title="Display name"><a href="<?php echo base_url('admin/users/edit/' . $user['id']); ?>"><?php echo $user['username']; ?></a></td>
					<td data-title="Email"><?php echo $user['email']; ?></td>
					<td data-title="Ρόλος">
						<?php if (!empty($user['is_admin'])) {
							echo 'Διαχειριστής';
						} else {
							$grs = array();
							foreach ($user['groups'] as $gr) {
								$grs_info = $userGroups[$gr['user_group_id']]['name'];
								if (!empty($gr['relation_ids'])) {
									$rs = json_decode($gr['relation_ids'], true);
									foreach ($rs as $gkey => $gval) {
										if (!empty($rs[$gkey . '_names'])) $grs_info .= ' (' . $rs[$gkey . '_names'] . ')';
									}
								}
								$grs[] = $grs_info;
							}
							echo implode('<br/>', $grs);
						} ?>
					</td>
					<td data-title="Ενέργειες">
						<?php if(!empty($user['is_active'])){?>
							<a href="<?php echo base_url('admin/users/deactivate/'.$user['id']);?>" class="btn bg-warning btn-sm btn-confirm-action" data-confirmation="Do you want to deactivate the user <?php echo $user['username'];?>?" title="Deactivate"><i class="icon-file-download"></i></a>
						<?php }else{?>
							<a href="<?php echo base_url('admin/users/activate/'.$user['id']);?>" class="btn bg-success btn-sm btn-confirm-action" data-confirmation="Do you want to activate the user <?php echo $user['username'];?>?" title="Activate"><i class="icon-file-upload"></i></a>
						<?php }?>
						<a href="<?php echo base_url('admin/users/edit/' . $user['id']); ?>" class="btn bg-primary btn-sm" title="Επεξεργασία χρήστη"><i class="icon-pencil4"></i></a>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?php echo ajax_pagination($count, $page, base_url('admin/users/dir'), base_url('admin/users/getPaginatedList')); ?>