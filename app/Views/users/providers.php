<div class="login-providers d-flex flex-column align-items-center justify-content-center mx-auto p-4" style="max-width: 900px;">
    <span class="font-weight-700 d-block mb-4 text-center font-xl-size-11rem font-fff font-size-1rem" id="popup-message">
        <?php echo $message ?? 'You must be a logged in user to do this.';?>
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