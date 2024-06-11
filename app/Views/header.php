<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" context="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes">
    <link rel="icon" href="<?php echo base_url('admin/assets/img/image.png') ?>?v=2">
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
    <link rel="stylesheet" href="/dist/css/bootstrap.css?v=2<?php echo ENVIRONMENT == 'development' ? time() : '';?>">
    <link rel="stylesheet" href="/dist/css/main.css?v=2.5<?php echo ENVIRONMENT == 'development' ? time() : '';?>">
    <link rel="stylesheet" href="/dist/css/customSwiper.css?v=2.5<?php echo ENVIRONMENT == 'development' ? time() : '';?>">

    <?php if (isset($loadCss) && is_array($loadCss)) : ?>
        <?php foreach ($loadCss as $cssFilename) : ?>
            <link rel="stylesheet" href="<?php echo "/dist/css/$cssFilename.css?v=2.5".(ENVIRONMENT == 'development' ? time() : '');?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (isset($loadAssetsCss) && is_array($loadAssetsCss)) : ?>
        <?php foreach ($loadAssetsCss as $cssFilename) : ?>
            <link rel="stylesheet" href="<?php echo "/assets/css/$cssFilename.css?v=2.5".(ENVIRONMENT == 'development' ? time() : '');?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="prefetch" href="/assets/img/timeline-grey-bg.svg">
    <link rel="prefetch" href="/assets/img/ball.svg">
    <link rel="prefetch" href="/assets/img/ball.svg">
</head>

<body class="min-vh-100 body-pd primary-bg" id="body">

<div class="" id="main-content">
    <?= view("mobile-menu", ["cacheHandler" => $cacheHandler]) ?>
    <?php echo view('menu', ['cacheHandler'=>$cacheHandler]); ?>