<div class="form-group">
    <label>{{ $form_item['label'] }}</label>
    <input 
        type="password" 
        class="form-control" 
        name="{{ $form_item['name'] }}" 
        data-id="{{ $form_item['id'] }}"
        value="" 
        autocomplete="new-password">
    <span class="help">
    @if(isset($obj) && isset($form_item['update_help']))
        {{$form_item['update_help']}}
    @elseif(isset($form_item['help']))
        {{$form_item['help']}}
    @endif
    </span>
</div>

@if( isset($form_item['password_confirmation_options']) && $form_item['password_confirmation_options']['show'] )
<div class="form-group">
    <label>{{ $form_item['password_confirmation_options']['label'] }}</label>
    <input 
        type="password" 
        class="form-control" 
        name="{{ $form_item['name'] . '_confirmation' }}" 
        data-id="{{ $form_item['id'] }}"
        value="" 
        autocomplete="new-password">
</div>
@endif