<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class ImagesConfig extends BaseConfig
{
    public $ci_images_url = SITE_URL . 'images/';
    public $site_media_path = ROOTPATH.'public\media';
    public $site_upload_path = ROOTPATH.'public\media';
    public $commandPrefix = '!';
    public $image_sizes = [
        'svg'      => ['width' => 20, 'h_height' => 20], //not resized
        'gif'      => ['width' => 20, 'h_height' => 20], //not resized
        'original' => ['width' => 20, 'h_height' => 20], //not resized
        'customSize'   => ['width' => 20, 'h_height' => 20], //resized by custom size

        'sqr16' => array('width' => 16, 'h_height' => 16),
        'sqr30' => array('width' => 30, 'h_height' => 30),
        'sqr40' => array('width' => 40, 'h_height' => 40),
        'sqr60' => array('width' => 60, 'h_height' => 60),
        'sqr70' => array('width' => 70, 'h_height' => 70),
        'sqr80' => array('width' => 80, 'h_height' => 80),
        'sqr120'     => ['width' => 120, 'h_height' => 120],
        'sqr200'     => ['width' => 200, 'h_height' => 200 ],

        'rct50' => array('width' => 50, 'h_height' => 25),
        'rct60'      => ['width' => 60, 'h_height' => 30],
        'rct80' => array('width' => 80, 'h_height' => 40),
        'rct100' => array('width' => 100, 'h_height' => 50),
        'rct140' => array('width' => 140, 'h_height' => 70),
        'rct200' => array('width' => 200, 'h_height' => 100),
        'rct300'     => ['width' => 300, 'h_height' => 150],
        'pollAnswer' => array('width' => 90, 'h_height' => 30),

        'rect400'     => ['width' => 400, 'h_height' => 225],
        'rect850'     => ['width' => 850, 'h_height' => 478],
        'rect1100'     => ['width' => 1100, 'h_height' => 619],


        'social'     => ['width' => 1200, 'h_height' => 628],
    ];
}
