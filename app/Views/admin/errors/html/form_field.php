<?php if (isset($errors) && is_array($errors) && array_key_exists($keyName, $errors)) : ?>
    <span class="text-danger"><?= $errors[$keyName] ?></span>
<?php endif ?>
