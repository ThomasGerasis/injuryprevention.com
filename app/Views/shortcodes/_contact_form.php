<form class="contact-form mt-5 mb-5" action="<?=base_url('sendEmail')?>" method="post" accept-charset="utf-8">
    <div class="input-group">
        <input type="text" name="name" class="form-control me-2" id="name" placeholder="John">
        <input type="text" name="surname" class="form-control" id="surname" placeholder="Doe">
    </div>
    <input type="email" name="email" class="form-control mt-2" id="email" placeholder="johndoe@example.com">
    <textarea name="message" class="form-control mt-2" placeholder="Message" id="message" rows="5"></textarea>
    <button type="submit" class="main-button font-fff black-gradient">
        <span class="button-slanted-content"> SEND</span>
    </button>
</form>