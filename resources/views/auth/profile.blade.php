@extends('speed-admin::layouts.layout')

@section('content')
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">

                @component('speed-admin::components.validation_errors')
                @endcomponent
                
                <form action="{{route('admin.update-profile')}}" method="post" enctype="multipart/form-data">
                    @csrf()

                    <div class="form-group">
                      <label for="name">{{__('Name')}}</label>
                      <input type="text" class="form-control" 
                        name="name" id="name" required
                        value="{{old('name') != null ? old('name') : \Auth::user()->name }}">
                    </div>

                    <div class="form-group">
                      <label for="picture">{{__('Picture')}}</label>

                      @if(\Auth::user()->picture != null && \Auth::user()->picture != '')
                      <br>
                      <img class="img-thumbnail" style="width: 200px;" src="{{route('admin.get-uploaded-image') . '?path=' . \Auth::user()->picture}}" alt="user image">
                      @endif

                      <input type="file" class="form-control-file" name="picture" id="picture" accept="image/*" >
                      <input type="checkbox" name="remove_picture"> Remove picture
                    </div>

                    <button type="submit" class="btn btn-primary">{{__('Update profile')}}</button>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection