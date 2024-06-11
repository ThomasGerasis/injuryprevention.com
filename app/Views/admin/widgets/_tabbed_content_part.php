<div class="card tabcard col-12">
	<div class="card-header header-elements-inline">
		<h5 class="card-title"><a class="btn btn-outline bg-primary text-primary-800 btn-icon btn-move-tab"><i class="icon-move"></i></a> Tab</h5>
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" data-action="remove"></a>
			</div>
		</div>
	</div>
	<div class="card-body">
		<input type="hidden" class="sort_order" value="<?php echo $order;?>">
		<?php foreach($shortcode_fields as $tid=>$tattrs){?>
			<div class="form-group row attr_row">
				<label class="col-form-label col-sm-2"><?php echo $tattrs['name'];?></label>
				<div class="col-sm-10 input-container">
					<?php if($tattrs['type'] == 'input'){?>
						<input name="tabs[<?php echo $counter;?>][<?php echo $tid;?>]" class="form-control <?php echo (!empty($tattrs['integer']) ? 'int-input' : '');?>" <?php echo (!empty($tattrs['required']) ? 'required="required"' : '');?> value="<?php echo (isset($values[$tid]) ? $values[$tid] : (isset($tattrs['def']) ? $tattrs['def'] : ''));?>">
					<?php }else if($tattrs['type'] == 'url_input'){ ?>
						<div class="input-group">
							<div class="input-group-append">
								<span class="input-group-text"><?php echo FRONT_SITE_URL;?></span>
							</div>
							<input name="tabs[<?php echo $counter;?>][<?php echo $tid;?>]" class="form-control <?php echo (!empty($tattrs['integer']) ? 'int-input' : '');?>" <?php echo (!empty($tattrs['required']) ? 'required="required"' : '');?> value="<?php echo (isset($values[$tid]) ? $values[$tid] : (isset($tattrs['def']) ? $tattrs['def'] : ''));?>">
						</div>
					<?php }else if($tattrs['type'] == 'tokeninput'){ ?>
						<?php if(!empty($tattrs['multiple'])){ ?>
							<?php $data_init_tags = array();
							if(isset($values[$tid]) && is_array($values[$tid]) && count($values[$tid])){
								foreach($values[$tid] as $artid){
									if(!isset($token_values[$tid][$artid])) continue;
									$tn = str_replace(array('\'','"'),'',$token_values[$tid][$artid]);
									$data_init_tags[] = '{"id": "'.$artid.'", "name": "'.htmlspecialchars($tn).'"}';
								}
							}?>
							<input type="text" name="tabs[<?php echo $counter;?>][<?php echo $tid;?>]" class="form-control input-multiple-tokeninput" data-ajax-function="<?php echo base_url().$tattrs['sourceUrl'];?>" data-init-token='<?php echo implode(',',$data_init_tags);?>' data-name="<?php echo $tid;?>">
						<?php }else{ ?>
							<?php $init_val = ''; $init_name = '';
							if(isset($values[$tid]) && !empty($values[$tid]) && isset($token_values[$tid][$values[$tid]])){
								$init_val = $values[$tid];
								$tn = str_replace(array('\'','"'),'',$token_values[$tid][$values[$tid]]);
								$init_name = htmlspecialchars($tn);
							}?>
							<input type="text" name="tabs[<?php echo $counter;?>][<?php echo $tid;?>]" class="form-control input-tokeninput" data-ajax-function="<?php echo base_url().(!empty($tattrs['portal_url'])?$tattrs['portal_url']:$tattrs['url']);?>" data-init-token-id="<?php echo $init_val;?>" data-init-token-name="<?php echo $init_name;?>">
						<?php } ?>
					<?php }else if($tattrs['type'] == 'select'){ ?>
						<?php $sel_id = (isset($values[$tid]) ? $values[$tid] : ($tattrs['multiple'] ? array() : 'novalue0'));?>
						<?php if(isset($tattrs['sourceType']) || isset($tattrs['sourceName'])){
							$select_array = $shortcode_values[$tid];
						}else{
							$select_array = $tattrs['values'];
						}?>
						<select class="form-control <?php echo ($tattrs['multiple'] ? 'select2cont' : 'selectv');?>" <?php echo ($tattrs['multiple'] ? 'multiple="multiple"' : '');?> <?php echo (!empty($tattrs['required']) ? 'required="required"' : '');?> name="tabs[<?php echo $counter;?>][<?php echo $tid;?>]<?php echo ($tattrs['multiple'] ? '[]' : '');?>" <?php echo ($tattrs['multiple'] ? 'data-name="'.$tid.'"' : '');?>>
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
					<?php } ?>
					<?php if(!empty($tattrs['desc'])){?>
						<span class="text-muted"><?php echo $tattrs['desc'];?></span>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>