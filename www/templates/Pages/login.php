<?php
$title = false;

?>
<div class="container">

<div class="row">
  <div class="column"></div>
  <div class="column">
    <h1>Thorbell Login</h1>
    <p>Please enter your password for Thorbell.</p>

<form method="post">
    <fieldset>
    <input type="hidden" name="token" value="" />

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" value="" required>
    </fieldset>

    <fieldset>
    <input class="button-primary" type="submit" value="Login">
    </fieldset>
</form>

    </div>
    <div class="column"></div>
</div>