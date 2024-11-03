<div class="dropdown d-flex justify-content-end">
    <button class="px-3 py-1 py-lg-2 bg-transparent border-radius-50 font-fff dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton">
        <img class="font-secondary px-1" src="<?php echo base_url('assets/img/user.svg'); ?>" width="25" height="20" loading="lazy" alt="user">
        <?= !$isMobile ? 'Hi ' . $user->username : '' ?>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <?php if ($isMobile) { ?>
            <li><a class="dropdown-item username" href="/my-account">Hi <?= $user->username ?? '' ?></a></li>
        <?php } ?>
        <li><a class="dropdown-item text-white" href="<?= base_url('/logout') ?>">Logout</a></li>
    </ul>
</div>
