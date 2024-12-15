<div class="main-menu">
    <div class="text-left d-block d-lg-none mobile-col-4 toggle-menu cursor-pointer ps-2">
        <img src="<?php echo base_url('assets/img/mobile_menu_bars.svg'); ?>" alt="" width="20" height="14">
    </div>

    <div class="menu-item logo">
        <a href="<?php echo base_url(); ?>" class="d-inline-block mx-auto">
            <img src="<?php echo base_url('assets/img/logo.svg'); ?>" alt="nba" width="56" height="56" id="site-logo">
        </a>
    </div>

    <?php 
    
    $menuItems = $cacheHandler->getMenuItems();
    $socials = $cacheHandler->getOption('info');
    ?>
    <?php $openParent = false; $parentId = false;
    foreach($menuItems as $menuItem){
        $url = (empty($menuItem['relative_url']) ? (empty($menuItem['external_url']) ? false : $menuItem['external_url']) : ($menuItem['relative_url'] == 'homepage' ? base_url() : base_url($menuItem['relative_url'])));
         if($menuItem['type'] == 'link'){ ?>
            <a href="<?php echo $url;?>" class="menu-item ps-2 d-flex align-items-center d-lg-block d-none justify-content-center">
                <?php if (!empty($menuItem['image_id'])){ ?>
                    <div class="menu-item-icon text-center">
                        <img height="30" width="30" src="<?php echo $cacheHandler->imageUrl($menuItem['image_id'],'sqr30');?>" class="mx-auto">
                    </div>
                <?php }?>
                <div class="sidebar-menu-item-text font-fff flex-fill mx-2 hide-on-toggle"><?php echo $menuItem['title'];?></div>
                <div class="sidebar-menu-item-actions me-2 hide-on-toggle"></div>
            </a>
        <?php } ?>
    <?php } ?>

    <div class="socials menu-item">
        <a target="_blank" rel="nofollow" href="<?php echo $socials['facebook_url'] ?? '#'; ?>" class="social-icon d-inline-block facebook">
            <img src="<?php echo base_url('assets/img/facebook.svg'); ?>" alt="nba" width="15" height="15">
        </a>

        <a target="_blank" rel="nofollow" href="<?php echo $socials['instagram_url'] ?? '#'; ?>" class="social-icon d-inline-block instagram">
            <img src="<?php echo base_url('assets/img/instagram.svg'); ?>" alt="nba" width="15" height="15">
        </a>

        <a target="_blank" rel="nofollow"  href="<?php echo $socials['twitter_url'] ?? '#'; ?>" class="social-icon d-inline-block twitter">
            <img src="<?php echo base_url('assets/img/twitter.svg'); ?>" alt="nba" width="15" height="15">
        </a>

       

    </div>

    <div class="text-1300-center text-end users-buttons" style="order:4;">

    </div>

</div>
