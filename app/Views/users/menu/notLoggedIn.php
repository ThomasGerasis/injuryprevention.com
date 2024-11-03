<div class="dropdown d-flex justify-content-end">
    <button class="px-3 py-1 py-lg-2 border-radius-50 font-fff dropdown-toggle d-flex align-items-center" id="socialLogins">
        Login
    </button>
    <div class="dropdown-menu text-white dropdownSocials" aria-labelledby="socialLogins">
        <div class="d-flex justify-content-center flex-wrap">
            <span class="sub_header text-center d-block w-100 font-size-13rem">
                Log in
            </span>
            <div id="g_id_onload"
                data-client_id="<?= GOOGLE_CLIENT_ID ?>"
                data-login_uri="<?= SITE_URL . 'login/googleauth'?>"
                data-auto_prompt="false">
            </div>
            <div class="g_id_signin"
                data-type="standard"
                data-size="large"
                data-theme="filled_blue"
                data-text="sign_in_with"
                data-shape="rectangular"
                data-logo_alignment="left">
            </div>

        </div>
    </div>
</div>
