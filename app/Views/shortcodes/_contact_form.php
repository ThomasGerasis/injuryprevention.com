<form class="contact-form mt-5 mb-5 text-center" action="<?=base_url('sendEmail')?>" method="post" accept-charset="utf-8" id="contact-form">
    <div class="input-group">
        <input type="text" name="name" class="form-control me-2" id="name" placeholder="John">
        <input type="text" name="surname" class="form-control" id="surname" placeholder="Doe">
    </div>
    <input type="email" name="email" class="form-control mt-2" id="email" placeholder="johndoe@example.com">
    <textarea name="message" class="form-control mt-2" placeholder="Message" id="message" rows="5"></textarea>
    <button type="submit" class="main-button font-fff black-gradient g-recaptcha" data-sitekey="<?=CAPTCHA_KEY?>" 
    data-callback='onSubmit' data-action='submit'>
        <span class="button-slanted-content"> SEND</span>
    </button>
</form>
<script src="https://www.google.com/recaptcha/api.js?render=<?=CAPTCHA_KEY?>"></script>

<script>
    function onSubmit(token) {
        document.getElementById('g-recaptcha-response').value = token;
        document.getElementById('contact-form').submit();
    }
    grecaptcha.ready(function () {
        grecaptcha.execute("<?=CAPTCHA_KEY?>", { action: 'submit' }).then(function (token) {
            document.getElementById('g-recaptcha-response').value = token;
        });
    });
</script>