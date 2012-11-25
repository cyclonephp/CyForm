<div class="input">
    <? if (isset($errors)) foreach ($errors as $error) : ?>
    <div class="ui-state-error error"><?= $error ?></div>
    <? endforeach; ?>
    <label for="<?= $name ?>"><?= $label ?>
<? if (isset($description)) : ?>
    <span class="descr"> <?= $description ?></span>
<? endif; ?>
    </label>
    <select class="ui-widget-content" name="<?= $name ?>">
	<? foreach ($items as $val => $txt) : ?>
	<option value="<?= $val ?>" <?= (isset($value) && $value == $val) ? 'selected' : '' ?>><?= $txt ?></option>
        <? endforeach; ?>
    </select>
    <div class="clear"></div>
</div>