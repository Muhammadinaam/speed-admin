# text

![](../.gitbook/assets/text-field.png)

```php
$this->addFormItem([
    'id' => 'name',
    
    // specify the parent_id if you want to add this field into
    // a parent div. It can also be null.
    'parent_id' => 'right-col',
    
    'type' => 'text',
    'validation_rules' => ['name' => 'required|unique:brands,name,{{$id}}'],
    'label' => __('Name'),
    'name' => 'name'
]);
```

