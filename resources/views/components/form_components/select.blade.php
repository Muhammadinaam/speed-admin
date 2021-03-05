<div class="form-group">
    <label>{{$options['label']}}</label>
    <select class="form-control form-control-sm" name="{{$options['name']}}">
        @foreach ($options['options'] as $option)
        <option value="{{$option['value']}}">{{$option['text']}}</option>
        @endforeach
    </select>
</div>
