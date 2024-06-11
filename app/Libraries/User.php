<?php

namespace App\Libraries;

class User
{
   private array $info;

   public function __construct(array $info)
   {
      $this->info = $info;
   }

   /**
    * Determine if a user has the right to access something
    * based on user groups
    */
   public function can(string $right): bool
   {
      if (!empty($this->info['is_admin'])) return true;
      if (empty($this->info['userGroups'])) return false;

      switch ($right) {
         case 'chatActions':
            if (isset($this->info['userGroups'][1])) return true;
         default:
            return false;
      }
      return false;
   }

   public function getInfo(): ?array
   {
      if (!isset($this->info)) {
         return null;
      }

      return $this->info;
   }

   public function isAdmin(): bool
   {
      return (int)$this->info['is_admin'] === 1 ? true : false;
   }
}
