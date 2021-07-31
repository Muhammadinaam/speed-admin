@extends('speed-admin::layouts.layout')

@section('content')

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
            {{__('System Applications')}}
            </h5>
        </div>
        <div class="card-body">
            @if(count($modules) == 0)
            <p class="text-center">
                {{__('No applications found')}}
            </p>
            @endif
            <div class="row">
                @foreach($modules as $module)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header p-2">
                            <h6 class="mb-0">
                            {{$module->getAlias()}}
                            </h6>
                        </div>
                        <div class="card-body p-2" style="min-height: 80px;">
                            {{$module->getDescription()}}
                        </div>
                        <div class="card-footer p-2">
                        {{$module->isEnabled() == '1' ? 'Enabled' : 'Disabled'}}
                            <form class="float-right" method="post" action="{{route('admin.system-applications.change-status', $module->getName())}}">
                                @csrf()
                                <button class="btn btn-sm {{$module->isEnabled() == '1' ? 'btn-danger' : 'btn-success'}}">
                                    {{$module->isEnabled() == '1' ? 'Disable' : 'Enable'}}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection