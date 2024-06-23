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
				<a href="<?php echo base_url('admin/users'); ?>" class="breadcrumb-item">Users</a>
				<span class="breadcrumb-item active"><?php echo $pageData['title']; ?></span>
			</div>
		</div>
	</div>
</div>

<div class="content">
	<?php if (!empty($user['id'])) { ?>
		<input id="edit_type" class="d-none" value="user">
		<input id="edit_type_id" class="d-none" value="<?php echo $user['id']; ?>">
		<input id="edit_back_url" class="d-none" value="<?php echo base_url('users'); ?>">
	<?php } ?>
	<div class="card-body">
		<form method="post" class="myvalidation">
			<div class="form-group row">
				<label class="col-form-label col-lg-2">Username*</label>
				<div class="col-lg-10">
					<?php if (empty($user['id'])) { ?>
						<input type="text" class="form-control" required name="username" value="<?php echo @$user['username']; ?>">
					<?php } else { ?>
						<input type="text" class="form-control" required name="username" value="<?php echo $user['username']; ?>">
						<input type="hidden" name="id" value="<?php echo $user['id']; ?>">
					<?php } ?>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-lg-2">Email*</label>
				<div class="col-lg-10">
					<input type="email" class="form-control" required name="email" value="<?php echo @$user['email']; ?>">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-lg-2">Διαχειριστής</label>
				<div class="col-lg-10">
					<?php $isAdmin = (empty($user['is_admin']) ? 0 : $user['is_admin']); ?>
					<select class="form-control is_admin" name="is_admin">
						<option value="1" <?php echo ($isAdmin == '1' ? 'selected="selected"' : ''); ?>>Ναι</option>
						<option value="0" <?php echo (empty($isAdmin) ? 'selected="selected"' : ''); ?>>Όχι</option>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-lg-2">Active</label>
				<div class="col-lg-10">
					<?php $isActive = (empty($user['is_active']) ? 0 : $user['is_active']); ?>
					<select class="form-control" name="is_active">
						<option value="1" <?php echo ($isActive == '1' ? 'selected="selected"' : ''); ?>>Ναι</option>
						<option value="0" <?php echo (empty($isActive) ? 'selected="selected"' : ''); ?>>Όχι</option>
					</select>
				</div>
			</div>
			<div id="user_groups" <?php echo (empty($isAdmin) ? '' : 'style="display:none;"'); ?>>
				<div class="form-group row">
					<label class="col-form-label col-lg-2">Ρόλοι</label>
					<div class="col-lg-10">
						<?php foreach ($userGroups as $userGroup) {
							$uid = $userGroup['id'];?>
							<?php $group_rels = (empty($userGroup['relations']) ? array() : explode('|', trim($userGroup['relations'], '|'))); ?>
							<div class="form-group row m-0 user_group_row">
								<div class="col-lg-12">
									<div class="form-check">
										<label class="form-check-label">
											<input type="checkbox" class="form-check-input <?php echo empty($group_rels) ? '' : 'has-relations' ?>" <?php echo (empty($user['userGroups'][$uid]) ? '' : 'checked=""'); ?> name="userGroup[<?php echo $uid; ?>][on]" value="1">
											<?php echo $userGroup['name']; ?>
										</label>
									</div>
									<?php $user_relations = (empty($user['userGroups'][$uid]['relation_ids']) ? array() : json_decode($user['userGroups'][$uid]['relation_ids'], true)); ?>
									<?php foreach ($group_rels as $group_rel) {
										$all_name = 'all_' . $group_rel;
										$has_def = false;
										$all_checked = (empty($user_relations[$all_name]) ? 0 : $user_relations[$all_name]);
										$selected_ids = (empty($user_relations[$group_rel]) ? array() : $user_relations[$group_rel]);
										$list = false;
										switch ($group_rel) {
											case 'channels':
												$list = $channels;
												$iname = 'Channels';
												break;
											case 'streamers':
												$list = $streamers;
												$iname = 'Streamers';
												break;
											case 'casinos':
												$list = $casinos;
												$iname = 'Casinos';
												break;
											default:
												break;
										}
										if (empty($list)) continue; ?>
										<input type="hidden" value="<?php echo $group_rel; ?>" name="userGroup[<?php echo $uid; ?>][relation]">
										<input type="hidden" value="<?php echo (empty($user['userGroups'][$uid]['id']) ? 0 : $user['userGroups'][$uid]['id']); ?>" name="userGroup[<?php echo $uid; ?>][id]">
										<div class="form-group row relation-row" <?php echo (empty($user['userGroups'][$uid]) ? 'style="display:none"' : ''); ?>>
											<label class="col-form-label col-lg-2 text-right"><?php echo $iname; ?>:</label>
											<div class="col-10">
												<div class="form-check">
													<label class="form-check-label">
														<input type="checkbox" class="form-check-input" <?php echo ($all_checked ? 'checked="checked"' : ''); ?> value="1" name="userGroup[<?php echo $uid; ?>][<?php echo $all_name; ?>]">
														All
													</label>
												</div>
												<div class="form-group row">
													<label class="col-form-label col-lg-2 text-right">ή επιλογή:</label>
													<div class="col-lg-10">
														<select class="form-control select2cont" multiple="multiple" name="userGroup[<?php echo $uid; ?>][<?php echo $group_rel; ?>][]">
															<?php if ($has_def) { ?>
																<option value="default" <?php echo !$all_checked && in_array('default', $selected_ids) ? 'selected="selected"' : ''; ?>>Default</option>
															<?php } ?>
															<?php foreach ($list as $item) { ?>
																<option value="<?php echo $item['id']; ?>" <?php echo (!$all_checked && in_array($item['id'], $selected_ids) ? 'selected="selected"' : ''); ?>><?php echo $item['title']; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
											</div>
										</div>
									<?php } ?>
									<hr />
								</div>
							</div>
						<?php } ?>

					</div>
				</div>
			</div>
			<hr />
			<div class="form-group row">
				<label class="col-form-label col-lg-2"></label>
				<div class="col-lg-10">
					<button type="submit" class="btn btn-primary">Αποθήκευση <i class="icon-database-add ml-2"></i></button>
				</div>
			</div>
		</form>
	</div>
</div>
</div>