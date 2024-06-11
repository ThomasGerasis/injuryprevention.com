<div class="d-flex no-gutters flex-wrap">
	<?php foreach($images as $image){?>
		<div class="mw200 <?php echo ($image['extension'] == 'svg' ? 'mh100' : '');?> m-1"><img class="img-fluid <?php echo ($image['extension'] == 'svg' ? 'mh100' : '');?> mw200" loading="lazy" data-image-id="<?php echo $image['id'];?>" src="<?php echo get_image_url($image['file_name'],'original');?>" data-title="<?php echo htmlspecialchars($image['title']);?>"  data-alt="<?php echo htmlspecialchars($image['seo_alt']);?>"/><?php echo $image['file_name'];?> - <?php echo $image['width'].'x'.$image['height']; ?></div>
	<?php } ?>
</div>