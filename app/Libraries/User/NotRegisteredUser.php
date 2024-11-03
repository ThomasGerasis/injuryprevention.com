<?php

namespace App\Libraries\User;

class NotRegisteredUser
{
    public string $userMenuButtons;
    public string $modalLogin;
    public string $status = "notRegistered";

    public function __construct(string $userMenuButtons, string $modalLogin)
    {
        $this->userMenuButtons = $userMenuButtons;
        $this->modalLogin = $modalLogin;
    }
}
