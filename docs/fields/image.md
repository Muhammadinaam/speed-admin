# image

![](../.gitbook/assets/image-field.png)

```php
$this->addFormItem([
    'id' => 'image',
    'parent_id' => 'left-col',
    'type' => 'image',
    'label' => __('Image'),
    'name' => 'image',
    'upload_path' => 'brands',
    'validation_rules' => ['image' => 'required|image|max:2048'],
]);
```

