<footer>
    <div class="main-content w-100 d-flex flex-wrap justify-content-between">
        
        <div class="logo_breadcrumbs d-flex flex-column flex-lg-row">
            <a href="<?php echo base_url(); ?>" class="d-inline-block mx-auto pb-lg-3">
                <img src="<?php echo base_url('assets/img/logo-plain.svg'); ?>" loading="lazy" alt="logo" width="150" height="100" id="site-logo">
            </a>
        </div>

        <div class="d-flex flex-column align-items-center mt-lg-2">
            <?php $menuItems = $cacheHandler->getMenuItems();?>
            <?php $openParent = false; $parentId = false;
            foreach($menuItems as $key => $menuItem){
                $url = (empty($menuItem['relative_url']) ? (empty($menuItem['external_url']) ? false : $menuItem['external_url']) : ($menuItem['relative_url'] == 'homepage' ? base_url() : base_url($menuItem['relative_url'])));
                if($menuItem['type'] == 'link'){ ?>
                    <a href="<?php echo $url;?>" class="menu-item ps-2 margin-bottom-20 text-decoration-none d-flex align-items-center justify-content-center">
                        <div class="sidebar-menu-item-text margin-left-5 font-fff flex-fill mx-2 hide-on-toggle">
                            <?php echo $menuItem['title'];?>
                        </div>
                    </a>
                <?php } ?>
            <?php } ?>
        </div>

        <?php $socials = $cacheHandler->getOption('info');?>
        <div class="footer_info d-flex flex-column justify-content-center">
            <span class="font-fff text-center text-lg-end">INJURY PREVENTION LAB</span>
            <span class="font-fff text-center text-lg-end"><?=$socials['email']?></span>
            <span class="font-fff text-center text-lg-end"><?=$socials['mobile']?></span>
            <div class="socials menu-item pt-2">
                <a target="_blank" rel="nofollow" href="<?php echo $socials['facebook_url'] ?? '#'; ?>" class="social-icon text-decoration-none d-inline-block facebook">
                    <img src="<?php echo base_url('assets/img/facebook.svg'); ?>" alt="nba" width="15" height="15">
                </a>

                <a target="_blank" rel="nofollow" href="<?php echo $socials['instagram_url'] ?? '#'; ?>" class="social-icon text-decoration-none d-inline-block instagram">
                    <img src="<?php echo base_url('assets/img/instagram.svg'); ?>" alt="nba" width="15" height="15">
                </a>

                <a target="_blank" rel="nofollow"  href="<?php echo $socials['twitter_url'] ?? '#'; ?>" class="social-icon text-decoration-none d-inline-block twitter">
                    <img src="<?php echo base_url('assets/img/twitter.svg'); ?>" alt="nba" width="15" height="15">
                </a>
            </div>
        </div>

        <span class="d-block w-100 text-center pt-5 font-fff-opacity-60">
            INJURY PREVENTION LAB <?=date('Y')?>. All Rights Reserved  / <a class="text-decoration-none font-fff" href="privacy-policy">Privacy Policy</a>
        </span>

    </div>

</footer>

</div>

<script src="/dist/js/main.js?v=4.37<?php echo ENVIRONMENT === 'development' ? time() : ''; ?>"></script>
<script src="/dist/js/users.js?v=3.97<?php echo ENVIRONMENT === 'development' ? time() : ''; ?>"></script>
<script src="/dist/js/customSwiper.js?v=3.61<?php echo ENVIRONMENT === 'development' ? time() : ''; ?>"></script>
<script src="/dist/js/footer.js?v=1.5<?php echo ENVIRONMENT === 'development' ? time() : ''; ?>"></script>
<script src="/dist/js/bootstrap.js?v=1.3<?php echo ENVIRONMENT === 'development' ? time() : ''; ?>"></script>
<!-- load scripts necessary for curtain pages -->

<?php if (isset($loadJs) && is_array($loadJs)) : ?>
    <?php foreach ($loadJs as $script) : ?>
        <script src="<?php echo base_url("dist/js/$script.js?v=2") . (ENVIRONMENT == 'development' ? time() : ''); ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<!-- load third party js like google sign in -->
<?php if (isset($load_third_party_js) && is_array($load_third_party_js)) : ?>
    <?php foreach ($load_third_party_js as $third_party_script) : ?>
        <script src="<?php echo $third_party_script; ?>" async defer></script>
    <?php endforeach; ?>
<?php endif; ?>


<?php if (ENVIRONMENT !== 'development') { ?>
<!--    <script async src="https://www.googletagmanager.com/gtag/js?id="></script>-->
<!--    <script>-->
<!--        window.dataLayer = window.dataLayer || [];-->
<!--        function gtag(){dataLayer.push(arguments);}-->
<!--        gtag('js', new Date());-->
<!--        gtag('config', '');-->
<!--    </script>-->
<?php } ?>

<div class="modal-popup fade ajaxModal" id="ajaxModal" tabindex="-1" aria-labelledby="ajaxModal" aria-hidden="true">
    <div class="modal-container">
        <img width="12" height="12"
             class="close-modal"
             src="<?php echo base_url('assets/img/close_icon.svg'); ?>"
             loading="lazy" alt="close icon">
            <div class="modal-body">
                <span class="font-size-12rem text-center text-white">You must be logged in to read this article</span>
            </div>
    </div>
</div>

<div class="modal-popup fade ajaxLoginModal"
     id="ajaxLoginModal"
     tabindex="-1" aria-labelledby="ajaxLoginModal"
     aria-hidden="true">
    <div class="modal-container">
        <img width="12" height="12"
             class="close-modal"
             src="<?php echo base_url('assets/img/close_icon.svg'); ?>"
             loading="lazy"
             alt="close icon">
        <div class="modal-body">

        </div>
    </div>
</div>


</body>
</html>
