<fieldset class="cyform ui-widget">
    <? if ($model->title) : ?><legend class="ui-widget-header"><?= $model->title ?></legend> <? endif; ?>
    <? if ($show_form_tag) : ?>
    <form  <?= \cyclone\HTML::attributes($model->attributes)?>>
    <? endif; ?>
        <? foreach($fields as $field) echo $field; ?>
    <? if ($show_form_tag) : ?>
    </form>
    <? endif; ?>
</fieldset>
