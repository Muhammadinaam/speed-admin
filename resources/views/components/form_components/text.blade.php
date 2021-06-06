<div class="form-group">
    <label>{{ $form_item['label'] }}</label>

    <?php
        $locale_and_value = \SpeedAdminHelpers::getLocaleSuffixAndValue([
            'obj' => isset($obj) ? $obj : null,
            'locale' => isset($locale) ? $locale : null,
            'form_item' => $form_item
        ]);
        $locale_suffix = $locale_and_value['locale_suffix'];
        $value = $locale_and_value['value'];
    ?>

    <input 
        type="text" 
        class="form-control" 
        name="{{ $form_item['name'] }}{{$locale_suffix}}" 
        data-id="{{ $form_item['id'] }}{{$locale_suffix}}"
        value="{{$value}}"
        {{isset($form_item['readonly']) && $form_item['readonly'] ? 'readonly' : '' }}>
</div>
