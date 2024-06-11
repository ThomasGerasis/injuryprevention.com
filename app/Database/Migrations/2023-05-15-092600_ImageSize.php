<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ImageSize extends Migration
{
    public function up()
    {
        $this->db->query("ALTER TABLE `images` ADD `width` int(11) DEFAULT NULL AFTER `file_name`;");
        $this->db->query("ALTER TABLE `images` ADD `height` int(11) DEFAULT NULL AFTER `width`;");
        $this->db->query("ALTER TABLE `images` ADD `seo_alt` varchar(255) DEFAULT NULL AFTER `height`;");
        $this->db->query("ALTER TABLE `images` ADD `seo_description` varchar(255) DEFAULT NULL AFTER `seo_alt`;");

    }

    public function down()
    {
    }
}