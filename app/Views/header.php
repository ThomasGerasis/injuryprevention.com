<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes">
    <link rel="icon" type="image/x-icon" href="<?php echo base_url('admin/assets/img/favicon.ico') ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('/assets/img/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo base_url('/assets/img/android-chrome-192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('/assets/img/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('/assets/img/favicon-16x16.png') ?>">
    <link rel="preload" href="<?php echo base_url('assets/fonts/Alata_Regular.woff2');?>" as="font" type="font/woff2" crossorigin>
    <?php
    /**
     * load conditional header meta like google signin client id
     */
    $router = service('router');
    $controller = $router->controllerName();
    $method = $router->methodName();
    $params = $router->params();

    if (isset($headerMeta) && is_array($headerMeta)) {
        foreach ($headerMeta as $meta) {
            echo $meta;
        }
    }
    ?>
    <style>
        @font-face{font-family: 'Alata', sans-serif;font-style:normal;font-weight:300;font-display:swap;src:url(<?php echo base_url('assets/fonts/Alata_Regular.woff2');?>) format('woff2');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD}
        @font-face{font-family: 'Alata', sans-serif;font-style:normal;font-weight:400;font-display:swap;src:url(<?php echo base_url('assets/fonts/Alata_Regular.woff2');?>) format('woff2');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD}
        @font-face{font-family: 'Alata', sans-serif;font-style:normal;font-weight:500;font-display:swap;src:url(<?php echo base_url('assets/fonts/Alata_Regular.woff2');?>) format('woff2');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD}
        @font-face{font-family: 'Alata', sans-serif;font-style:normal;font-weight:700;font-display:swap;src:url(<?php echo base_url('assets/fonts/Alata_Regular.woff2');?>) format('woff2');unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD}
    </style>
    <link rel="stylesheet" href="/dist/css/bootstrap.css?v=2.51<?php echo ENVIRONMENT == 'development' ? time() : '';?>">
    <link rel="stylesheet" href="/dist/css/main.css?v=2.59<?php echo ENVIRONMENT == 'development' ? time() : '';?>">
    <link rel="stylesheet" href="/dist/css/customSwiper.css?v=2.76<?php echo ENVIRONMENT == 'development' ? time() : '';?>">

    <?php if (isset($loadCss) && is_array($loadCss)) : ?>
        <?php foreach ($loadCss as $cssFilename) : ?>
            <link rel="stylesheet" href="<?php echo "/dist/css/$cssFilename.css?v=2.57".(ENVIRONMENT == 'development' ? time() : '');?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (isset($loadAssetsCss) && is_array($loadAssetsCss)) : ?>
        <?php foreach ($loadAssetsCss as $cssFilename) : ?>
            <link rel="stylesheet" href="<?php echo "/assets/css/$cssFilename.css?v=2.57".(ENVIRONMENT == 'development' ? time() : '');?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="prefetch" href="/assets/img/arrow-down.svg" as="image">
    <link rel="preload" href="/assets/stats.json" as="fetch" type="application/json" crossorigin="anonymous">
    <link rel="preload" href="/assets/players.json" as="fetch" type="application/json" crossorigin="anonymous">
</head>

<body class="min-vh-100 body-pd primary-bg" id="body">

<?php
$bgClass = isset($isHomePage) ? '' : 'bg-primary';
$backgroundImage ='';
if (!isset($isHomePage) && isset($pageData['bg_image_id']) && !empty($pageData['bg_image_id'])) {
    $backgroundImage = $cacheHandler->imageUrl($pageData['bg_image_id'], 'rect1100');
} ?>

<div class="<?php echo !empty($backgroundImage) ? 'bg-page'  : $bgClass; ?>" id="page-container"
    <?php if (!empty($backgroundImage)) : ?> style="background-image: url('<?php echo $backgroundImage; ?>')" <?php endif; ?> >

    <?php if (!isset($isHomePage)){ ?>
        <?= view("mobile-menu", ["cacheHandler" => $cacheHandler]) ?>
        <?= view('menu', ['cacheHandler' => $cacheHandler]); ?>
    <?php } ?>