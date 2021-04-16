<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc;

class GridHelper{

    public static function gridData($model) 
    {
        $query = $model->getGridQuery(request());

        $query = GridHelper::setOrders($query, $model);

        $query = GridHelper::applySearch($query, $model);

        $paginated_data = $query->paginate(request()->has('per_page') ? request()->per_page : 10);

        if(count($paginated_data) == 0) {
            request()->merge(['page' => 1]);
            $paginated_data = $query->paginate(request()->has('per_page') ? request()->per_page : 10);
        }

        $items = $paginated_data->getCollection();
        GridHelper::renderColumns($items, $model);
        $paginated_data->setCollection($items);

        return response()
            ->json([
                'paginated_data' => $paginated_data,
                'links' => $paginated_data->onEachSide(1)->links('pagination::bootstrap-4')->render(),
                'pagination_info' => __('Showing :from to :to of total :total records', [
                    'from' => $paginated_data->firstItem() == null ? 0 : $paginated_data->firstItem(),
                    'to' => $paginated_data->lastItem()== null ? 0 : $paginated_data->lastItem(),
                    'total' => $paginated_data->total()
                ])
            ])
            ->header('Vary', 'Accept');
    }

    public static function renderImage($value)
    {
        return '<img class="img-thumbnail" width="150px" src="'.
            route('admin.get-uploaded-image', ['path' => $value]).
            '" />';
    }

    public static function renderBoolean($value)
    {
        $bool = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        $color_class = $bool ? "text-success" : "text-danger";
        $font_class = $bool ? "fas fa-check" : "fas fa-times";
        $value = '<i class="'.$color_class.' '.$font_class.'"></i>';
        return $value;
    }

    private static function setOrders($query, $model)
    {
        if(request()->has('order') && request()->order != '') {
            $order_items = explode(',', request()->order);

            $grid_columns = collect($model->getGridColumns());

            foreach ($order_items as $order_item) {
                $order_item_parts = explode(':', $order_item);

                $column_id = $order_item_parts[0];
                $asc_or_desc = $order_item_parts[1];

                $column = $grid_columns->first(function($c) use ($column_id){
                    return $c['id'] == $column_id;
                });

                $query->orderBy($column['order_by'], $asc_or_desc);
            }
        }

        return $query;
    }

    private static function applySearch($query, $model)
    {
        if(request()->has('__search__') && request()->__search__ != '') {

            $search = request()->__search__;
            $grid_columns = $model->getGridColumns();

            $searchable_columns = [];
            foreach ($grid_columns as $grid_column) {
                if( isset($grid_column['search_by']))
                {
                    array_push($searchable_columns, $grid_column['search_by']);
                }
            }

            $query = $query->where(function($q) use ($search, $searchable_columns){

                foreach ($searchable_columns as $searchable_column) {
                    $q->orWhere($searchable_column, 'like', '%'.$search.'%');
                }
            });
        }

        return $query;
    }

    private static function renderColumns($items, $model)
    {
        foreach ($items as $index => $item) {
            $rendered_item = new \stdClass();
            $rendered_item->__id__ = $item->id;
            $rendered_item->__text__ = $item->text;
            foreach ($model->getGridColumns() as $grid_column_index => $grid_column) {

                $render_function = $grid_column['render_function'];
                $rendered_item->{$grid_column['id']} = $render_function($item);
            }

            $items[$index] = $rendered_item;
        }
    }
}
