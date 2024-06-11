<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><?php echo $pageData['title'];?></h4>
        </div>
    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="d-flex">
            <div class="breadcrumb">
                <a href="<?php echo base_url('admin/dashboard');?>" class="breadcrumb-item">
                    <i class="icon-home2 mr-2"></i>
                    Dashboard
                </a>
                <span class="breadcrumb-item active"><?php echo $pageData['title'];?></span>
            </div>
        </div>
    </div>
</div>

<div class="content">
        <div class="header-elements-inline">
            <h5 class="">
                Menus
                <button type="button"
                        class="btn btn-icon rounded-round btn-light add-menu-item"
                        data-target="menus-container"
                        data-template="menu-part"
                        data-counter="menu-counter">
                    <i class="icon-plus3"></i>
                </button>
            </h5>
        </div>
    <form method="post">
        <div id="menus-container">
                <?php
                $menuCounter = 0;
                if (!empty($menus)) {
                    foreach ($menus as $menu) {
                        echo view('admin/menu/_footer_menu', array(
                            'menu' => array(
                                'id' => $menu['id'],
                                'order_num' => $menu['order_num'],
                                'title' => $menu['title'],
                            ),
                            'counter' => $menuCounter,
                            'order' => $menu['order_num'],
                            'links' => $menu['links']
                        ));
                        $menuCounter++;
                    }
                }
                ?>
        </div>
        <input id="menu-counter" class="d-none" type="text" value="<?php echo $menuCounter;?>">
    <div class="form-group row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Save Menus <i class="icon-database-add ml-2"></i></button>
        </div>
    </div>
</form>
</div>

<script type="text/x-tmpl" id="menu-part">
	<?php echo view('admin/menu/_footer_menu', array(
        'menu' => array(
            'id' => '',
            'order_num' => '',
            'title' => '',
        ),
        'links'=>array(),
        'counter' => '${counter}',
        'order' => '${order}',
    )); ?>
</script>

<script type="text/x-tmpl" id="link-part">
	<?php echo view('admin/menu/_footer_menu_item', array(
        'link' => array(
            'id' => '',
            'type' => 'link',
            'order_num' => '',
            'title' => '',
            'image_id' => '',
            'relative_url' => '',
            'external_url' => '',
        ),
        'origin_counter' => '${origin_counter}',
        'counter' => '${counter}',
        'order' => '${order}',
    )); ?>
</script>

<script type="text/x-tmpl" id="new-image-template">
	<?php echo view('admin/widgets/_single_image_part', array(
        'image_id' => '${image_id}',
        'input_name' => '${input_name}',
        'filename' => '${filename}'
    )); ?>
</script>