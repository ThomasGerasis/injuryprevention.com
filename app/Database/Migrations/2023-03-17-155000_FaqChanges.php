<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FaqChanges extends Migration
{
    public function up()
    {
      $this->db->query("ALTER TABLE `pages` ADD `faq_content` text DEFAULT NULL AFTER `faq_heading`;");

      $this->db->query("CREATE TABLE `faq_categories` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `title` varchar(255) NOT NULL,
          `order_num` int(11) NOT NULL,
          PRIMARY KEY (`id`),
          KEY `title` (`title`),
          KEY `order_num` (`order_num`)
      ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

      $this->db->query("ALTER TABLE `page_faqs` ADD `faq_category_id` int(11) DEFAULT NULL AFTER `id`;");
      $this->db->query("ALTER TABLE `page_faqs` ADD INDEX(`faq_category_id`);");
		  $this->db->query("ALTER TABLE `page_faqs` ADD FOREIGN KEY (`faq_category_id`) REFERENCES `faq_categories`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;");
	      
    }

    public function down()
    {
        
    }
}
