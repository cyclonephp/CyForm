<div class="input">
    <? if (isset($errors)) foreach ($errors as $error) : ?>
    <div class="ui-state-error error"><?= $error ?></div>
    <? endforeach; ?>
    <label for="<?= $name ?>"><?= $label ?>
<? if (isset($description)) : ?>
    <span class="descr"> <?= $description ?></span>
<? endif; ?>
    </label>
    <div class="checkboxlist">
    <? foreach ($items as $val => $text) : ?>
        <input class="ui-widget-content" type="checkbox" <?= \cyclone\HTML::attributes($attributes) ?> value="<?= $val ?>"<?
            if (in_array($val, $values)) echo ' checked="checked"'?>/><?= $text ?><br/>
    <? endforeach; ?>
    </div>
    <div class="clear"></div>
</div>