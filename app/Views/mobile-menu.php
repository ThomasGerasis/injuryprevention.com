<div id="sidebar-mobile-backdrop" class="d-1300-none backdrop-toggle-menu cursor-pointer"></div>
<div id="sidebar-nav" class="d-xl-none">
    <?php $currentUrl = $_SERVER['REQUEST_URI']; ?>
    <div class="sidebar-content">
        <div class="w-100 d-none d-1300-flex justify-content-end">
            <div class="p-2 me-2 toggle-menu cursor-pointer">
                <img src="<?php echo base_url('assets/img/close_menu.svg'); ?>" alt="close menu button" width="16" height="16" class="close-sidebar-menu">
                <img src="<?php echo base_url('assets/img/open_menu.svg'); ?>" alt="open menu button" width="16" height="16" loading="lazy" class="open-sidebar-menu">
            </div>
        </div>
        <div class="w-100 d-flex d-1300-none justify-content-end py-2">
            <div class="mobile-col-6 text-center">
                <a href="<?php echo base_url(); ?>">
                    <img src="<?php echo base_url('assets/img/logo.svg'); ?>" loading="lazy" alt="" width="125" height="42" id="site-logo">
                </a>
            </div>
            <div class="mobile-col-3 text-end">
                <div class="d-inline-block p-2 me-2 toggle-menu cursor-pointer">
                    <img src="<?php echo base_url('assets/img/close_icon.svg'); ?>" loading="lazy" alt="close menu" width="14" height="14">
                </div>
            </div>
        </div>
        <div class="sidebar-menu-items">
            <?php
            $menuItems = $cacheHandler->getMenuItems();
            $openParent = false;
            $parentId = false;
            foreach($menuItems as $menuItem){
            $activeUrlClass = '';
            $url = '';
            if (empty($menuItem['relative_url'])){
                $url = $menuItem['external_url'] ?? '';
            }else{
                $relativeUrl = $menuItem['relative_url'] === 'homepage' ? '' : $menuItem['relative_url'];
                $url = base_url($relativeUrl);
                $activeUrlClass = $currentUrl === '/'.$relativeUrl ? 'active' : '';
            }
            if($menuItem['type'] === 'parent'){
            if($openParent){
                echo '</div>';
            }
            $parentId = $menuItem['id'];
            $openParent = true;
            ?>
            <div class="sidebar-menu-item ps-2 d-flex align-items-center has-submenu justify-content-center" data-submenu-container="submenu-<?php echo $parentId;?>">
                <?php if (!empty($menuItem['image_id'])){ ?>
                    <div class="sidebar-item-icon text-center">
                        <img height="30" width="30" alt="menu item icon" src="<?php echo $cacheHandler->imageUrl($menuItem['image_id'],'sqr30');?>" class="mx-auto">
                    </div>
                <?php }?>
                <div class="sidebar-menu-item-text font-fff flex-fill mx-2 hide-on-toggle"><?php echo $menuItem['title'];?></div>
                <div class="sidebar-menu-item-actions me-2 hide-on-toggle"><span class="open-submenu d-block text-center"><img height="20" width="12" src="<?php echo base_url('assets/img/arrow_down.svg'); ?>" class="mx-auto"></span></div>
            </div>
            <div class="sidebar-submenu hide-on-toggle-js" id="submenu-<?php echo $parentId;?>">
                <?php }elseif($menuItem['type'] == 'link'){
                    if($openParent){
                        echo '</div>';
                        $openParent = false; $parentId = false;
                    }?>
                    <a href="<?php echo $url;?>" class="sidebar-menu-item ps-2 d-flex align-items-center justify-content-center <?php echo $activeUrlClass;?>">
                        <?php if (!empty($menuItem['image_id'])){ ?>
                            <div class="sidebar-item-icon text-center">
                                <img height="30" width="30" alt="menu item icon" src="<?php echo $cacheHandler->imageUrl($menuItem['image_id'],'sqr30');?>" class="mx-auto">
                            </div>
                        <?php }?>
                        <div class="sidebar-menu-item-text font-fff flex-fill mx-2 hide-on-toggle"><?php echo $menuItem['title'];?></div>
                        <div class="sidebar-menu-item-actions me-2 hide-on-toggle"></div>
                    </a>
                <?php }else{ //child ?>
                    <a href="<?php echo $url;?>" class="sidebar-menu-item ps-2 d-flex align-items-center justify-content-center <?php echo $activeUrlClass;?>">
                        <div class="sidebar-menu-item-icon text-center"></div>
                        <div class="sidebar-menu-item-text font-fff flex-fill mx-2"><?php echo $menuItem['title'];?></div>
                        <div class="sidebar-menu-item-actions me-2"></div>
                    </a>
                <?php } ?>

                <?php }

                if($openParent){
                    echo '</div>';
                }?>
            </div>
        </div>
    </div>