<div class="input">
    <? if (isset($errors)) foreach ($errors as $error) : ?>
    <div class="ui-state-error error"><?= $error ?></div>
    <? endforeach; ?>
        <label for="<?= $name ?>"><?= $label ?>
<? if (isset($description)) : ?>
    <span class="descr"> <?= $description ?></span>
<? endif; ?>
    </label>
    <textarea class="ui-widget-content" <?= \cyclone\HTML::attributes($attributes) ?>><?= $value ?></textarea>
    <div class="clear"></div>
</div>