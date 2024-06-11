<div class="card menu-card">
    <div class="card-body">
    <div class="form-group row align-items-center">
        <div class="col-sm-1 align-self-center">
            <a class="btn btn-outline bg-primary text-primary-800 btn-icon btn-movetoc-part">
                <i class="icon-move"></i>
            </a>
        </div>
        <div class="col-sm-9">
            <input type="text"
                   name="menus[<?php echo $counter;?>][title]"
                   class="form-control mr-2"
                   required
                   placeholder="Τίτλος"
                   value="<?php echo $menu['title'] ?? '';?>"
                   style="min-width:300px;">
        </div>
        <div class="col-sm-2 text-center">
            <a class="list-icons-item" data-action="remove"></a>
        </div>
        <input type="hidden" class="sort_order"
               name="menus[<?php echo $counter;?>][order_num]"
               value="<?php echo $order;?>">
        <input type="hidden"
               name="menus[<?php echo $counter;?>][id]"
               value="<?php echo $menu['id'];?>">
    </div>

    <h5 class="mt-3">
        Menu items
        <button type="button" class="btn btn-icon rounded-round btn-light add-footer-menu-item"
                data-origin-counter="<?php echo $counter;?>"
                data-target="link-container-<?php echo $counter;?>"
                data-template="link-part"
                data-counter="link-counter-<?php echo $counter;?>">
            <i class="icon-plus3"></i>
        </button>
    </h5>
    
    <div id="link-container-<?php echo $counter;?>" class="menu-link-container">
        <?php
        $links_counter = 0;
        foreach ($links as $link) {
            echo view('admin/menu/_footer_menu_item', array(
                'link' => $link,
                'origin_counter' => $counter,
                'counter' => $links_counter,
                'order' => $link['order_num']
            ));
            $links_counter++;
        }
        ?>
        <input id="link-counter-<?php echo $counter;?>"
               class="d-none"
               type="text"
               value="<?php echo $links_counter;?>">
        </div>
    </div>
</div>
