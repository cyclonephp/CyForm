<fieldset class="cyform">
    <? if ($model->title) : ?><legend><?= $model->title ?></legend> <? endif; ?>
    <form  <?= \cyclone\HTML::attributes($model->attributes)?>>
        <? foreach($fields as $field) echo $field; ?>
    </form>
</fieldset>
