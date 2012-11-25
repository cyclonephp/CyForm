<fieldset class="cyform ui-widget">
    <? if ($model->title) : ?><legend class="ui-widget-header"><?= $model->title ?></legend> <? endif; ?>
    <form  <?= \cyclone\HTML::attributes($model->attributes)?>>
        <? foreach($fields as $field) echo $field; ?>
    </form>
</fieldset>
