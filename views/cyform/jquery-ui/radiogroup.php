<div class="input">
    <? if (isset($errors)) foreach ($errors as $error) : ?>
    <div class="ui-state-error error"><?= $error ?></div>
    <? endforeach; ?>
    <label for="<?= $name ?>"><?= $label ?>
<? if (isset($description)) : ?>
    <span class="descr"> <?= $description ?></span>
<? endif; ?>
    </label>
    <div class="radiogroup">
        <? foreach ($items as $val => $text) : ?>
        <input class="ui-widget-content" type="radio" value="<?= $val ?>" <?= \cyclone\HTML::attributes($attributes) ?>/><?= $text ?><br/>
        <? endforeach; ?>
    </div>
    <div class="clear"></div>
</div>