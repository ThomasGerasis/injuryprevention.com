<div class="card link-card <?php echo (($link['type'] == 'child' || $link['type'] == 'child_parent') ? 'ml-3' : ($link['type'] == 'second_child' ? 'ml-5' : 'main_link'));?>">
	<div class="card-body">
        <div class=" p-1 form-inline">
			<a class="btn btn-outline bg-primary text-primary-800 btn-icon btn-movetoc-part"><i class="icon-move"></i></a>
			<label class="mr-2">Τύπος:</label>
			<select class="form-control required mr-2 link-selector" name="menus[<?php echo $origin_counter;?>][links][<?php echo $counter;?>][type]">
				<option value="link" data-class="main_link" <?php echo ($link['type'] == 'link' ? 'selected="selected"' : '');?>>Simple link</option>
				<option value="parent" data-class="main_link" <?php echo ($link['type'] == 'parent' ? 'selected="selected"' : '');?>>Parent link</option>
				<option value="child" data-class="ml-3" <?php echo ($link['type'] == 'child' ? 'selected="selected"' : '');?>>Child link</option>
			</select>
			<label class="mr-2">Icon: </label>
			<div class="form-group mr-2">
				<div class="single-img-container">
					<div id="image-<?php echo $origin_counter;?>-container-<?php echo $counter;?>" class="d-inline-block" style="width: 50px;">
						<?php if (!empty($link['image_id'])) {
							echo view('admin/widgets/_single_image_part', array(
								'input_name' => 'menus['.$origin_counter.'][links]['.$counter.'][image_id]',
								'image_id' => $link['image_id'],
								'filename' => get_image($link['image_id'], 'sqr30'),
								'watermark' => 0
							));
						} ?>
					</div>
					<button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left single-image-upload-btn"><b><i class="icon-image2"></i></b> <?php echo (empty($page['opener_image_id']) ? 'Choose' : 'Change'); ?> icon</button>
					<input type="file" class="d-none single-image-upload" data-input-name="menus[<?php echo $origin_counter;?>][links][<?php echo $counter;?>][image_id]" data-target="#image-<?php echo $origin_counter;?>-container-<?php echo $counter;?>" data-template="new-image-template" data-url="<?php echo base_url(); ?>admin/fileUpload/do_upload_image/sqr30">
					<button type="button" class="btn bg-info btn-labeled btn-labeled-left open-image-bank"><b><i class="icon-image2"></i></b> Image bank</button>
				</div>
			</div>
			<label class="mr-2">Τίτλος: </label>
			<input type="text" name="menus[<?php echo $origin_counter;?>][links][<?php echo $counter;?>][title]" class="form-control mr-2" required placeholder="Τίτλος" value="<?php echo $link['title'];?>" style="min-width:300px;">
			<a class="list-icons-item" data-action="remove"></a>
		</div>
		<div class=" p-1 form-inline">
			<label class="mr-2">Relative url: </label>
			<div class="input-group">
				<div class="input-group-append">
					<span class="input-group-text"><?php echo FRONT_SITE_URL;?></span>
				</div>
				<input type="text" name="menus[<?php echo $origin_counter;?>][links][<?php echo $counter;?>][relative_url]" class="form-control mr-2" placeholder="relative url" value="<?php echo $link['relative_url'];?>" style="min-width:300px;">
			</div>
			<label class="mr-2">or external url: </label>
			<input type="text" name="menus[<?php echo $origin_counter;?>][links][<?php echo $counter;?>][external_url]" class="form-control mr-2" placeholder="external url" value="<?php echo $link['external_url'];?>" style="min-width:300px;">
		</div>
		<input type="hidden" class="sort_order" name="menus[<?php echo $origin_counter;?>][links][<?php echo $counter;?>][order_num]" value="<?php echo $order;?>">
		<input type="hidden" name="menus[<?php echo $origin_counter;?>][links][<?php echo $counter;?>][id]" value="<?php echo $link['id'];?>">
	</div>
</div>