# div

You can add a 'div' to work as a bootstrap row or columns. In the following example code, we added a bootstrap row with two columns inside it. Please note that we specified the parent\_id of two columns equal to the id of row div.

```php
// bootstrap row
$this->addFormItem([
    'id' => 'main-row',
    'type' => 'div',
    'class' => 'row'
]);

// bootstrap column
// this column will be shown on left side and
// it will show image field
$this->addFormItem([
    'id' => 'left-col',
    'parent_id' => 'main-row',
    'type' => 'div',
    'class' => 'col-md-4'
]);

// bootstrap column
// this column will be shown on right side and
// it will show fields other than image
$this->addFormItem([
    'id' => 'right-col',
    'parent_id' => 'main-row',
    'type' => 'div',
    'class' => 'col-md-8'
]);
```

