<?php
$title = 'Thorbell Settings';

?>

<form method="post">
    <input type="hidden" name="token" value="" />
    <fieldset>
        <label for="name">Thorbell Name:</label>
        <input type="text" name="name" id="name" value="<?= $name->value ?>" class="<?= ($name->hasErrors ? 'error' : '') ?>"required>
        <?php if ($name->hasErrors) { echo '<div class="error">Invalid Thorbell Name.</div>'; } ?>
    </fieldset>

    <fieldset>
    <input class="button-primary" type="submit" value="Save">
    </fieldset>
</form>
