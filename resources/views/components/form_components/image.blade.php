<div class="form-group">
    @php
    $is_image_set = isset($obj) && $obj->{$form_item['name']} != null
    @endphp

    
    <input type="hidden" name="{{$form_item['name'] . '_deleted'}}" value="">
    
    <span style="position: relative; display: inline-block">
        <img 
            src="{{$is_image_set ? route('admin.get-uploaded-image', ['path' => $obj->{$form_item['name']}]) : '' }}" 
            width="200px" 
            class="img-thumbnail">

        <button
            style="position: absolute;
                    top: 3px;
                    right: 11px;
                    opacity: 0.70;" 
            onclick="speedAdmin.removeSelectedImage(this)" 
            type="button" 
            class="btn btn-sm btn-danger btn-remove mt-1 {{$is_image_set ? '' : 'd-none'}}">
            
            <i class="fas fa-trash"></i>
        </button>
    </span>
    <br>
    <label>{{ $form_item['label'] }}</label>
    <br>
    <input type="file" class="" name="{{ $form_item['name'] }}" data-id="{{$form_item['id']}}" onchange="speedAdmin.showSelectedImage(this);">
</div>
