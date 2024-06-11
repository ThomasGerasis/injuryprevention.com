<footer>
    <div class="main-content w-100 d-flex flex-wrap">
        <div class="logo_breadcrumbs col-12 col-lg-6 d-flex flex-column flex-lg-row">
            <a href="<?php echo base_url(); ?>" class="d-inline-block mx-auto pb-lg-3">
                <img src="<?php echo base_url('assets/img/footer-logo.svg'); ?>" style="filter: invert(1)" loading="lazy" alt="logo" width="150" height="100" id="site-logo">
            </a>
            <div class="d-flex w-100 flex-column align-items-center mt-lg-2">
                <?php $menuItems = $cacheHandler->getMenuItems();?>
                <?php $openParent = false; $parentId = false;
                foreach($menuItems as $key => $menuItem){
                    $url = (empty($menuItem['relative_url']) ? (empty($menuItem['external_url']) ? false : $menuItem['external_url']) : ($menuItem['relative_url'] == 'homepage' ? base_url() : base_url($menuItem['relative_url'])));
                    if($menuItem['type'] == 'link'){ ?>
                        <a href="<?php echo $url;?>" class="menu-item ps-2 margin-bottom-20 d-flex align-items-center justify-content-center">
                            <div class="sidebar-menu-item-text margin-left-5 font-fff flex-fill mx-2 hide-on-toggle">
                                <?php echo $menuItem['title'];?>
                            </div>
                        </a>
                    <?php } ?>
                <?php } ?>
            </div>

        </div>

        <div class="footer_info col-12 col-lg-6 d-flex flex-column justify-content-center justify-content-lg-end">
            <span class="font-fff text-center text-lg-end">INJURY PREVENTION LAB</span>
            <span class="font-fff text-center text-lg-end">+30698408037</span>
            <span class="font-fff text-center text-lg-end">somalabagency@gmail.com</span>
            <div class="socials menu-item pt-2">
                <a target="_blank" rel="nofollow" href="<?php echo base_url(); ?>" class="social-icon d-inline-block facebook">
                    <img src="<?php echo base_url('assets/img/facebook.svg'); ?>" loading="lazy" alt="facebook" width="15" height="15">
                </a>
                <a target="_blank" rel="nofollow" href="<?php echo base_url(); ?>" class="social-icon d-inline-block instagram">
                    <img src="<?php echo base_url('assets/img/instagram.svg'); ?>" loading="lazy" alt="instagram" width="15" height="15">
                </a>
                <a target="_blank" rel="nofollow"  href="<?php echo base_url(); ?>" class="social-icon d-inline-block twitter">
                    <img src="<?php echo base_url('assets/img/twitter.svg'); ?>" loading="lazy" alt="twitter" width="15" height="15">
                </a>
            </div>
        </div>

        <span class="d-block w-100 text-center pt-5 font-fff-opacity-60">
            INJURY PREVENTION LAB <?=date('Y')?>. All Rights Reserved
        </span>

    </div>

</footer>

</div>

<script src="/dist/js/main.js?v=3.9<?php echo ENVIRONMENT === 'development' ? time() : ''; ?>"></script>
<script src="/dist/js/customSwiper.js?v=3.2<?php echo ENVIRONMENT === 'development' ? time() : ''; ?>"></script>
<script src="/dist/js/footer.js?v=1.1<?php echo ENVIRONMENT === 'development' ? time() : ''; ?>"></script>
<script src="/dist/js/bootstrap.js?v=1.1<?php echo ENVIRONMENT === 'development' ? time() : ''; ?>"></script>
<!-- load scripts necessary for curtain pages -->
<?php //print_r($loadJs);?>

<?php if (isset($loadJs) && is_array($loadJs)) : ?>
    <?php foreach ($loadJs as $script) : ?>
        <script src="<?php echo base_url("dist/js/$script.js?v=9.7") . (ENVIRONMENT == 'development' ? time() : ''); ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<!-- load third party js like google sign in -->
<?php if (isset($load_third_party_js) && is_array($load_third_party_js)) : ?>
    <?php foreach ($load_third_party_js as $third_party_script) : ?>
        <script src="<?php echo $third_party_script; ?>" async defer></script>
    <?php endforeach; ?>
<?php endif; ?>


<?php if (ENVIRONMENT !== 'development') { ?>
<!--    <script async src="https://www.googletagmanager.com/gtag/js?id=G-X57GBYP4FX"></script>-->
<!--    <script>-->
<!--        window.dataLayer = window.dataLayer || [];-->
<!--        function gtag(){dataLayer.push(arguments);}-->
<!--        gtag('js', new Date());-->
<!--        gtag('config', 'G-X57GBYP4FX');-->
<!--    </script>-->
<?php } ?>



</body>
</html>
