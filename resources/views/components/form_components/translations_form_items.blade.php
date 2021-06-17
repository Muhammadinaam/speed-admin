@if( config('speed-admin.additional_model_locales') != null
    && count(config('speed-admin.additional_model_locales')) > 0
    && isset($model->translatable) && count($model->translatable) > 0 )
    <hr>

    <div class="trans-container">
        <button class="btn btn-sm btn-info" type="button" data-toggle="collapse"
            data-target="#translations_form_items_{{ $uniqid }}">
            {{ __('Translations') }}
        </button>

        <div id="translations_form_items_{{ $uniqid }}" class="collapse pt-2">

            <ul class="nav nav-tabs">
                @foreach(config('speed-admin.additional_model_locales') as $locale)
                    <li class="nav-item">
                        <a class="nav-link {{$loop->index == 0 ? 'active' : ''}}" data-toggle="tab" href="#{{ $locale['locale'] }}">
                            {{ $locale['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                @foreach(config('speed-admin.additional_model_locales') as $locale)
                <div class="tab-pane p-2 {{$loop->index == 0 ? 'active' : ''}}" id="{{$locale['locale']}}">
                    <?php
                        $form_items = collect($model->getFormItemsFlat());
                        $translatable_form_items = $form_items->filter(function($form_item) use ($model){
                            return isset($form_item['name']) &&
                                in_array($form_item['name'], $model->translatable);
                        });

                        $translatable_form_items = $translatable_form_items->toArray();

                    ?>

                    @component('speed-admin::components.form_components.form_items', [
                        'model' => $model,
                        'form_items' => $translatable_form_items,
                        'obj' => isset($obj) ? $obj : null,
                        'locale' => $locale['locale']
                    ])
                    @endcomponent

                </div>
                @endforeach
            </div>

        </div>
    </div>

    <hr>
@endif
