<?php

namespace App\Libraries\User;

class User
{
    public string $username;
    public string $email;
    public string $userMenuButtons;

    public function __construct(
        string $username,
        string $email,
        string $userMenuButtons
    ) {
        $this->username = $username;
        $this->email = $email;
        $this->userMenuButtons = $userMenuButtons;
    }
}
