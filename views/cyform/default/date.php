<div class="input">
    <? if (isset($errors)) foreach ($errors as $error) : ?>
    <div class="error"><?= $error ?></div>
    <? endforeach; ?>
    <label><?= $label ?>
<? if (isset($description)) : ?>
    <span class="descr"> <?= $description ?></span>
<? endif; ?>
    </label>
    <div class="select-cnt">
    <? foreach ($segments as $segment) : ?>
        <select name="<?= $segment['name'] ?>">
            <? foreach ($segment['items'] as $val => $txt) : ?>
            <option value="<?= $val ?>" <?= (isset($segment['value']) && $segment['value'] == $val) ? 'selected' : ''?>><?= $txt ?></option>
            <? endforeach; ?>
        </select>
    <? endforeach; ?>
    </div>
    <div class="clear"></div>
</div>