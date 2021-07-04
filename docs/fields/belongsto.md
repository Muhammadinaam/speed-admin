# belongsTo

![](../.gitbook/assets/belongsto-field.png)

```php
$this->addFormItem([
    'id' => 'brand',
    'parent_id' => 'right-col',
    'type' => 'belongsTo',
    'relation_name' => 'brand',
    'model' => '\App\Models\Brand',
    'where' => function($query){
        return $query->where('is_active', 1);
    },
    'show_add_new_button' => true,
    'validation_rules' => ['brand' => 'required'],
    'label' => __('Brand'),
    'name' => 'brand'
]);
```

