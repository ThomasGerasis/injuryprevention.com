<?php /*<pre><?php
//print_r($values);
//print_r($shortcode_attrs);
//print_r($shortcode_values);
?></pre>
$shortcode_attrs['tabbed_content']
tabbed_content_title
*/
$shortcode_fields = $shortcode_attrs['attrs'];?>
<form method="post" id="shortcode_form">
	<?php if(!empty($shortcode_attrs['tabbed_content'])){?>
		<h5><?php echo $shortcode_attrs['tabbed_content_title'];?> <button type="button" class="btn btn-icon rounded-round btn-light add-tab-btn scrollto" data-target="alltabs" data-template="new-tab-block" data-counter="tab-counter"><i class="icon-plus3"></i></button></h5>
		<?php $shortcode_fields = $shortcode_attrs['attrs']['tabs']['attrs'];
	} ?>
	<?php $has_img = false;?>
	<input type="hidden" id="current_shortcode" value="<?php echo $shortcode;?>">
	
	<?php if(!empty($shortcode_attrs['tabbed_content'])){?>
		<?php $selected_tabs = (isset($values['tabs']) ? $values['tabs'] : array());
		$tab_counter = 0;?>
		<div id="alltabs" class="row container-tabs">
			<?php if(!empty($selected_tabs)){
				$order = 0;
				foreach($selected_tabs as $selected_tab_id=>$selected_tab){
					echo view('admin/widgets/_tabbed_content_part', array(
						'values' => $selected_tab,
						'counter' => $tab_counter,
						'order' => $tab_counter,
						'shortcode_fields' => $shortcode_fields
					));
					$tab_counter++;
				}
			} ?>
		</div>
		<input id="tab-counter" class="d-none" type="text" value="<?php echo $tab_counter;?>">
		<script type="text/x-tmpl" id="new-tab-block">
			<?php echo view('admin/widgets/_tabbed_content_part', array(
				'values' => array(),
				'counter' => '${counter}',
				'order' => '${order}',
				'shortcode_fields' => $shortcode_fields
			)); ?>
		</script>
	<?php }else{ ?>
		<?php foreach($shortcode_fields as $tid=>$tattrs){?>
			<div class="form-group row attr_row">
				<label class="col-form-label col-sm-2"><?php echo $tattrs['name'];?></label>
				<div class="col-sm-10 input-container">
					<?php if($tattrs['type'] == 'multiple_draggable'){
						$selected_items = (isset($values[$tid]) ? $values[$tid] : array());?>
						<div class="multiple_draggable_container">
							<div class="input-group mb-2">
								<select class="form-control new_multiple_draggable_item">
									<option value=""> -- Choose -- </option>
									<?php foreach ($shortcode_values[$tid] as $item_id=>$item) {?>
										<option value="<?php echo $item_id; ?>" <?php echo (isset($selected_items[$item_id]) ? 'style="display:none;"' : ''); ?>><?php echo $item['title']; ?></option>
									<?php } ?>
								</select>
								<span class="input-group-append">
									<button class="btn btn-light add_new_multiple_draggable_item required_draggable_items" data-input-name="<?php echo $tid;?>" type="button">Add</button>
								</span>
							</div>
							<div class="table-responsive">
								<table class="table table-hover table-bordered table-striped multiple_draggable_items">
									<tbody>
										<?php if(!empty($selected_items)){
											$order = 0;
											foreach($selected_items as $selected_item_id=>$selected_item){
												if(!isset($shortcode_values[$tid][$selected_item_id])) continue;
												$order++;?>
												<tr>
													<td><a class="btn bg-primary btn-sm btn-icon btn-move-item"><i class="icon-move"></i></a></td>
													<td><?php echo $shortcode_values[$tid][$selected_item_id]['title'];?><input type="hidden" name="<?php echo $tid;?>[<?php echo $selected_item_id;?>][item_id]" class="form-control" value="<?php echo $selected_item_id;?>"><input type="hidden" name="<?php echo $tid;?>[<?php echo $selected_item_id;?>][order_num]" class="form-control sort_order" value="<?php echo $order;?>"></td>
													<td><button class="btn bg-danger btn-sm btn-icon btn-remove-item" title="Remove item" data-item-id="<?php echo $selected_item_id;?>"><i class="icon-cross3"></i></button></td>
												</tr>
											<?php }
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					<?php }else if($tattrs['type'] == 'randomId'){?>
						<input type="text" name="<?php echo $tid;?>" class="d-none" value="<?php echo (isset($values[$tid]) ? $values[$tid] : getToken(18));?>">
					<?php }else if($tattrs['type'] == 'input'){?>
						<input type="text" name="<?php echo $tid;?>" class="form-control add_attr <?php echo (!empty($tattrs['integer']) ? 'int-input' : '');?>" <?php echo (!empty($tattrs['required']) ? 'required="required"' : '');?> value="<?php echo (isset($values[$tid]) ? $values[$tid] : (isset($tattrs['def']) ? $tattrs['def'] : ''));?>">
					<?php }else if($tattrs['type'] == 'url_input'){ ?>
						<div class="input-group">
							<div class="input-group-append">
								<span class="input-group-text"><?php echo FRONT_SITE_URL;?></span>
							</div>
							<input name="<?php echo $tid;?>" class="form-control add_attr <?php echo (!empty($tattrs['integer']) ? 'int-input' : '');?>" <?php echo (!empty($tattrs['required']) ? 'required="required"' : '');?> value="<?php echo (isset($values[$tid]) ? $values[$tid] : (isset($tattrs['def']) ? $tattrs['def'] : ''));?>">
						</div>
					<?php }else if($tattrs['type'] == 'anchor_input'){ ?>
						<div class="input-group">
							<div class="input-group-append">
								<span class="input-group-text">#</span>
							</div>
							<input name="<?php echo $tid;?>" class="form-control add_attr <?php echo (!empty($tattrs['integer']) ? 'int-input' : '');?>" <?php echo (!empty($tattrs['required']) ? 'required="required"' : '');?> value="<?php echo (isset($values[$tid]) ? $values[$tid] : (isset($tattrs['def']) ? $tattrs['def'] : ''));?>">
						</div>
					<?php }else if($tattrs['type'] == 'select_tokeninput'){ ?>
						<?php $init_val = ''; $init_name = '';
						if(isset($values[$tid]) && !empty($values[$tid]) && isset($token_values[$tid][$values[$tid]])){
							$init_val = $values[$tid];
							$tn = str_replace(array('\'','"'),'',$token_values[$tid][$values[$tid]]);
							$init_name = htmlspecialchars($tn);
						}?>
						<input type="text" name="<?php echo $tid;?>" class="form-control input-tokeninput add_token update_type" data-ajax-function="<?php echo base_url().(!empty($tattrs['portal_url'])?$tattrs['portal_url']:$tattrs['url']);?>" data-init-token-id="<?php echo $init_val;?>" data-init-token-name="<?php echo $init_name;?>">
						<input type="hidden" name="<?php echo $tid;?>_type" class="input_type" value="<?php echo (isset($values[$tid.'_type']) ? $values[$tid.'_type'] : '');?>">
					<?php }else if($tattrs['type'] == 'tokeninput'){ ?>
						<?php if(!empty($tattrs['multiple'])){ ?>
							<?php $data_init_tags = array();
							if(isset($values[$tid]) && count($values[$tid])){
								foreach($values[$tid] as $artid){
									if(!isset($token_values[$tid][$artid])) continue;
									$tn = str_replace(array('\'','"'),'',$token_values[$tid][$artid]);
									$data_init_tags[] = '{"id": "'.$artid.'", "name": "'.htmlspecialchars($tn).'"}';
								}
							}?>
							<input type="text" name="<?php echo $tid;?>" class="form-control input-multiple-tokeninput add_multiple_token" data-ajax-function="<?php echo base_url().$tattrs['sourceUrl'];?>" data-init-token='<?php echo implode(',',$data_init_tags);?>' data-name="<?php echo $tid;?>">
						<?php }else{ ?>
							<?php $init_val = ''; $init_name = '';
							if(isset($values[$tid]) && !empty($values[$tid]) && isset($token_values[$tid][$values[$tid]])){
								$init_val = $values[$tid];
								$tn = str_replace(array('\'','"'),'',$token_values[$tid][$values[$tid]]);
								$init_name = htmlspecialchars($tn);
							}?>
							<input type="text" name="<?php echo $tid;?>" class="form-control input-tokeninput add_token" data-ajax-function="<?php echo base_url().$tattrs['sourceUrl'];?>" data-init-token-id="<?php echo $init_val;?>" data-init-token-name="<?php echo $init_name;?>">
						<?php } ?>
					<?php }else if($tattrs['type'] == 'date_selector'){ ?>
						<div class="input-group mr-2">
							<span class="input-group-prepend">
								<span class="input-group-text"><i class="icon-calendar5"></i></span>
							</span>
							<input type="text" name="<?php echo $tid;?>" class="form-control pickadate-format add_attr" <?php echo (!empty($tattrs['required']) ? 'required="required"' : '');?> value="<?php echo (isset($values[$tid]) ? $values[$tid] : '');?>">
						</div>
					<?php }else if($tattrs['type'] == 'select'){ ?>
						<?php $sel_id = (isset($values[$tid]) ? $values[$tid] : ($tattrs['multiple'] ? array() : 'novalue0'));?>
						<?php if(isset($tattrs['sourceType']) || isset($tattrs['sourceName'])){
							$select_array = $shortcode_values[$tid];
						}else{
							$select_array = $tattrs['values'];
						}?>
						<select class="form-control <?php echo ($tattrs['multiple'] ? 'select2cont add_multiple_attr' : 'add_attr selectv');?>" <?php echo ($tattrs['multiple'] ? 'multiple="multiple"' : '');?> <?php echo (!empty($tattrs['required']) ? 'required="required"' : '');?> name="<?php echo $tid;?><?php echo ($tattrs['multiple'] ? '[]' : '');?>" <?php echo ($tattrs['multiple'] ? 'data-name="'.$tid.'"' : '');?>>
							<?php if(!$tattrs['multiple']){?>
								<option value="" <?php echo (empty($sel_id) || $sel_id == 'novalue0' ? 'selected="selected"' : '');?>> -- </option>
							<?php } ?>
							<?php foreach($select_array as $cid=>$cval){?>
								<?php if($tattrs['multiple']){?>
									<option value="<?php echo $cid;?>" <?php echo (in_array($cid,$sel_id) ? 'selected="selected"' : '');?>><?php echo (is_array($cval)?$cval['title']:$cval);?></option>
								<?php }else{ ?>
									<option value="<?php echo $cid;?>" <?php echo ($sel_id == $cid ? 'selected="selected"' : '');?>><?php echo (is_array($cval)?$cval['title']:$cval);?></option>
								<?php } ?>
							<?php } ?>
						</select>
					<?php }else if($tattrs['type'] == 'file'){ 
						$has_img = true;
						$image_id = (isset($values[$tid]) ? $values[$tid] : '');?>
						<div id="<?php echo $tid;?>-image" class="card_<?php echo (empty($tattrs['iclass'])?'rct200':$tattrs['iclass']);?>">
							<?php if(!empty($image_id)){
								echo view('admin/widgets/_single_image_part', array(
									'input_name' => $tid,
									'image_id' => $image_id,
									'filename' => get_image($image_id,(empty($tattrs['iclass'])?'rct200':$tattrs['iclass'])),
									'watermark' => false,
									'not_lazy' => true
								));
							}?>
						</div>
						<input type="file" class="d-none single-image-upload" data-input-name="<?php echo $tid;?>" data-target="#<?php echo $tid;?>-image" data-template="shimg-template" data-url="<?php echo base_url();?>/admin/fileUpload/do_upload_image/<?php echo empty($tattrs['iclass']) ? 'rct200' : $tattrs['iclass'];?>">
						<button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left single-image-upload-btn"><b><i class="icon-image2"></i></b> Επιλογή εικόνας</button> <button type="button" class="btn bg-info btn-labeled btn-labeled-left open-image-bank"><b><i class="icon-image2"></i></b> Image bank</button>
					<?php } ?>
					<?php if(!empty($tattrs['desc'])){?>
						<span class="text-muted"><?php echo $tattrs['desc'];?></span>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	<?php } ?>
	<?php //only on banners
	if(!empty($shortcode_attrs['multigeo']) && !empty($shortcode_attrs['multigeo_attrs']) && !empty($countries)){
		$active_countries_array = array();
		foreach($countries as $country){
			$active_countries_array[$country['id']] = $country['title'];
		}?>
		<?php foreach($active_countries_array as $countryid=>$countryname){?>
			<hr/><h4><?php echo $countryname;?></h4>
			<?php foreach($shortcode_fields as $tid=>$tattrs){
				if(!in_array($tid,$shortcode_attrs['multigeo_attrs'])) continue;?>
				<div class="form-group row attr_row">
					<label class="col-form-label col-sm-2"><?php echo str_replace('*','',$tattrs['name']);?> - <?php echo $countryname;?></label>
					<div class="col-sm-10 input-container">
						<?php if($tattrs['type'] == 'multiple_draggable'){
							$selected_items = (isset($values['countries_'.$countryid.'_'.$tid]) ? $values['countries_'.$countryid.'_'.$tid] : array());?>
							<div class="multiple_draggable_container">
								<div class="input-group mb-2">
									<select class="form-control new_multiple_draggable_item">
										<option value=""> -- Choose -- </option>
										<?php foreach ($shortcode_values[$tid] as $item_id=>$item) {?>
											<option value="<?php echo $item_id; ?>" <?php echo (isset($selected_items[$item_id]) ? 'style="display:none;"' : ''); ?>><?php echo $item['title']; ?></option>
										<?php } ?>
									</select>
									<span class="input-group-append">
										<button class="btn btn-light add_new_multiple_draggable_item" data-input-name="<?php echo 'countries_'.$countryid.'_'.$tid;?>" type="button">Add</button>
									</span>
								</div>
								<div class="table-responsive">
									<table class="table table-hover table-bordered table-striped multiple_draggable_items">
										<tbody>
											<?php if(!empty($selected_items)){
												$order = 0;
												foreach($selected_items as $selected_item_id=>$selected_item){
													if(!isset($shortcode_values[$tid][$selected_item_id])) continue;
													$order++;?>
													<tr>
														<td><a class="btn bg-primary btn-sm btn-icon btn-move-item"><i class="icon-move"></i></a></td>
														<td><?php echo $shortcode_values[$tid][$selected_item_id]['title'];?><input type="hidden" name="<?php echo 'countries_'.$countryid.'_'.$tid;?>[<?php echo $selected_item_id;?>][item_id]" class="form-control" value="<?php echo $selected_item_id;?>"><input type="hidden" name="<?php echo 'countries_'.$countryid.'_'.$tid;?>[<?php echo $selected_item_id;?>][order_num]" class="form-control sort_order" value="<?php echo $order;?>"></td>
														<td><button class="btn bg-danger btn-sm btn-icon btn-remove-item" title="Remove item" data-item-id="<?php echo $selected_item_id;?>"><i class="icon-cross3"></i></button></td>
													</tr>
												<?php }
											} ?>
										</tbody>
									</table>
								</div>
							</div>
						<?php }else if($tattrs['type'] == 'select'){?>
							<?php $sel_id = (isset($values['countries_'.$countryid.'_'.$tid]) ? $values['countries_'.$countryid.'_'.$tid] : 'novalue0');?>
							<?php if(isset($tattrs['sourceType']) || isset($tattrs['sourceName'])){
								$select_array = $shortcode_values[$tid];
							}else{
								$select_array = $tattrs['values'];
							}?>
							<select class="form-control add_attr selectv" name="countries_<?php echo $countryid;?>_<?php echo $tid;?>">
								<option value="" <?php echo (empty($sel_id) || $sel_id == 'novalue0' ? 'selected="selected"' : '');?>> -- </option>
								<?php foreach($select_array as $cid=>$cval){?>
									<option value="<?php echo $cid;?>" <?php echo ($sel_id == $cid ? 'selected="selected"' : '');?>><?php echo (is_array($cval)?$cval['title']:$cval);?></option>
								<?php } ?>
							</select>
							<?php if(!empty($tattrs['desc'])){?>
								<span class="text-muted"><?php echo $tattrs['desc'];?></span>
							<?php } ?>
						<?php }else if($tattrs['type'] == 'tokeninput'){ ?>
							<?php if(!empty($tattrs['multiple'])){ ?>
								<?php $data_init_tags = array();
								if(isset($values['countries_'.$countryid.'_'.$tid]) && count($values['countries_'.$countryid.'_'.$tid])){
									foreach($values['countries_'.$countryid.'_'.$tid] as $artid){
										if(!isset($token_values['countries_'.$countryid.'_'.$tid][$artid])) continue;
										$tn = str_replace(array('\'','"'),'',$token_values['countries_'.$countryid.'_'.$tid][$artid]);
										$data_init_tags[] = '{"id": "'.$artid.'", "name": "'.htmlspecialchars($tn).'"}';
									}
								}?>
								<input type="text" name="countries_<?php echo $countryid;?>_<?php echo $tid;?>" class="form-control input-multiple-tokeninput add_multiple_token" data-ajax-function="<?php echo base_url().(!empty($tattrs['portal_url'])?$tattrs['portal_url']:$tattrs['url']);?>" data-init-token='<?php echo implode(',',$data_init_tags);?>' data-name="countries_<?php echo $countryid;?>_<?php echo $tid;?>">
							<?php }else{ ?>
								<?php $init_val = ''; $init_name = '';
								if(isset($values['countries_'.$countryid.'_'.$tid]) && !empty($values['countries_'.$countryid.'_'.$tid]) && isset($token_values['countries_'.$countryid.'_'.$tid][$values['countries_'.$countryid.'_'.$tid]])){
									$init_val = $values['countries_'.$countryid.'_'.$tid];
									$tn = str_replace(array('\'','"'),'',$token_values['countries_'.$countryid.'_'.$tid][$values['countries_'.$countryid.'_'.$tid]]);
									$init_name = htmlspecialchars($tn);
								}?>
								<input type="text" name="countries_<?php echo $countryid;?>_<?php echo $tid;?>" class="form-control input-tokeninput add_token" data-ajax-function="<?php echo base_url().(!empty($tattrs['portal_url'])?$tattrs['portal_url']:$tattrs['url']);?>" data-init-token-id="<?php echo $init_val;?>" data-init-token-name="<?php echo $init_name;?>">
							<?php } ?>
						<?php }else if($tattrs['type'] == 'input'){?>
							<input name="countries_<?php echo $countryid;?>_<?php echo $tid;?>" class="form-control add_attr <?php echo (!empty($tattrs['integer']) ? 'int-input' : '');?>" value="<?php echo (isset($values['countries_'.$countryid.'_'.$tid]) ? $values['countries_'.$countryid.'_'.$tid] : '');?>">
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
	<?php } ?>
	<div class="form-group row">
		<label class="col-form-label col-lg-2"></label>
		<div class="col-lg-10">
			<button type="submit" class="btn btn-primary">Ορισμός</button>
			<?php if(empty($hide_close)){?>
				<button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Κλείσιμο</button>
			<?php } ?>
		</div>
	</div>
</form>
<?php if($has_img){?>
	<script type="text/x-tmpl" id="shimg-template">
		<?php echo view('admin/widgets/_single_image_part', array(
			'image_id' => '${image_id}',
			'input_name' => '${input_name}',
			'filename' => '${filename}'
		)); ?>
	</script>
<?php } ?>
<script type="text/x-tmpl" id="new_item_row">
	<tr>
		<td><a class="btn bg-primary btn-sm btn-icon btn-move-item"><i class="icon-move"></i></a></td>
		<td>${item_name}<input type="hidden" name="${input_name}[${item_id}][item_id]" class="form-control" value="${item_id}"><input type="hidden" name="${input_name}[${item_id}][order_num]" class="form-control sort_order" value="${order_num}"></td>
		<td><button class="btn bg-danger btn-sm btn-icon btn-remove-item" title="Remove item" data-item-id="${item_id}"><i class="icon-cross3"></i></button></td>
	</tr>
</script>
<script type="text/x-tmpl" id="new_tab_casinos">
	<tr>
		<td><a class="btn bg-primary btn-sm btn-icon btn-move-item"><i class="icon-move"></i></a></td>
		<td>${item_name}<input type="hidden" name="${input_name}[${item_id}][item_id]" class="form-control" value="${item_id}"><input type="hidden" name="${input_name}[${item_id}][order_num]" class="form-control sort_order" value="${order_num}"></td>
		<td><button class="btn bg-danger btn-sm btn-icon btn-remove-item" title="Remove item" data-item-id="${item_id}"><i class="icon-cross3"></i></button></td>
	</tr>
</script>


