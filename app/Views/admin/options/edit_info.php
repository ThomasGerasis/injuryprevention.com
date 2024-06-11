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
                <span class="breadcrumb-item">Settings panel</span>
                <span class="breadcrumb-item active"><?php echo $pageData['title']; ?></span>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <input id="edit_type" class="d-none" value="option">
    <input id="edit_type_id" class="d-none" value="<?php echo $option['name']; ?>">
    <input id="edit_back_url" class="d-none" value="<?php echo base_url('admin/dashboard'); ?>">

    <form method="post" class="myvalidation" id="tinymce_form">
        <?php $values = (empty($option['value']) ? array() : json_decode($option['value'], true)); ?>
        <label>Email</label>
        <div class="form-group">
            <input type="text" name="email" class="form-control int-input" placeholder="email" value="<?php echo @$values['email'];?>">
        </div>

        <label>Telephone Number</label>
        <div class="form-group">
            <input type="text" name="mobile" class="form-control int-input" placeholder="mobile" value="<?php echo @$values['mobile'];?>">
        </div>

        <label>Facebook Url</label>
        <div class="form-group">
            <input type="text" name="facebook_url" class="form-control" placeholder="Facebook Url" value="<?php echo @$values['facebook_url'];?>">
        </div>

        <label>Instagram Url</label>
        <div class="form-group">
            <input type="text" name="instagram_url" class="form-control" placeholder="Instagram Url" value="<?php echo @$values['instagram_url'];?>">
        </div>


        <label>Twitter / X Url</label>
        <div class="form-group">
            <input type="text" name="twitter_url" class="form-control" placeholder="Twitter Url" value="<?php echo @$values['twitter_url'];?>">
        </div>

        <div class="form-group row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Save <i class="icon-database-add ml-2"></i></button>
            </div>
        </div>
    </form>
</div>
<script type="text/x-tmpl" id="new-image-template">
	<?php echo view('admin/widgets/_single_image_part', array(
		'image_id' => '${image_id}',
		'input_name' => '${input_name}',
		'filename' => '${filename}'
	)); ?>
</script>