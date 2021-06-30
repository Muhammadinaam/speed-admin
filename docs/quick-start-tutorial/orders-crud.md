# Orders CRUD

This form is complicated. It has a repeater field for adding product lines. And it also has javascript to calculate Totals.

{% hint style="info" %}
All other things \(Adding controller, routes, etc are the same as explained in Brands CRUD tutorial\)
{% endhint %}

![](../.gitbook/assets/orders_form.gif)

## Add form fields

All other fields are simple. Please note how we added the repeater field \(id = order\_lines\). All the repeater fields \(product, quantity, price, taxes, total\) have their parent\_id set to the id of repeater i.e. **`order_lines`**

{% code title="File: App/Models/Order.php" %}
```php
...

public function __construct()
{
    ...
    
    $this->addFormFields()
    
    ...
}

public function addFormFields()
{
    $this->addFormItem([
        'id' => 'main-row',
        'type' => 'div',
        'class' => 'row'
    ]);
    $this->addFormItem([
        'id' => 'left-col',
        'parent_id' => 'main-row',
        'type' => 'div',
        'class' => 'col-md-6'
    ]);
    $this->addFormItem([
        'id' => 'right-col',
        'parent_id' => 'main-row',
        'type' => 'div',
        'class' => 'col-md-6'
    ]);

    $this->addFormItem([
        'id' => 'date',
        'parent_id' => 'left-col',
        'type' => 'datetime',
        'enable_time' => true,
        'validation_rules' => [
            'date' => 'required|date'
        ],
        'label' => __('Date'),
        'name' => 'date'
    ]);

    $this->addFormItem([
        'id' => 'customer_name',
        'parent_id' => 'left-col',
        'type' => 'text',
        'validation_rules' => [
            'customer_name' => 'required'
        ],
        'label' => __('Customer Name'),
        'name' => 'customer_name'
    ]);

    $this->addFormItem([
        'id' => 'shipping_address',
        'parent_id' => 'right-col',
        'type' => 'textarea',
        'validation_rules' => [
            'shipping_address' => 'required'
        ],
        'label' => __('Shipping Address'),
        'name' => 'shipping_address'
    ]);

    $this->addFormItem([
        'id' => 'order_lines',
        'parent_id' => null,
        'type' => 'repeater',
        'relation_name' => 'orderLines',
        'model' => '\App\Models\OrderLine',
        'label' => __('Order Lines'),
        'table_view' => true,
    ]);

    $this->addFormItem([
        'id' => 'product',
        'parent_id' => 'order_lines',
        'type' => 'belongsTo',
        'relation_name' => 'product',
        'model' => '\App\Models\Product',
        'where' => function($query){
            return $query->where('is_active', 1);
        },
        'validation_rules' => [
            'product' => 'required|array', 
            'product.*' => 'required'
        ],
        'label' => __('Product'),
        'name' => 'product',
        'show_add_new_button' => true,
    ]);

    $this->addFormItem([
        'id' => 'quantity',
        'parent_id' => 'order_lines',
        'type' => 'decimal',
        'validation_rules' => [
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric'
        ],
        'label' => __('Quantity'),
        'name' => 'quantity'
    ]);

    $this->addFormItem([
        'id' => 'price',
        'parent_id' => 'order_lines',
        'type' => 'decimal',
        'validation_rules' => [
            'price' => 'required|array',
            'price.*' => 'required|numeric'
        ],
        'readonly' => true,
        'label' => __('Price'),
        'name' => 'price'
    ]);

    $this->addFormItem([
        'id' => 'taxes',
        'parent_id' => 'order_lines',
        'type' => 'belongsToMany',
        'relation_name' => 'taxes',
        'model' => '\App\Models\Tax',
        'label' => __('Taxes'),
        'name' => 'taxes',
        'show_add_new_button' => true,
    ]);

    $this->addFormItem([
        'id' => 'instruction',
        'parent_id' => 'order_lines_instructions',
        'type' => 'text',
        'validation_rules' => [
            'instruction' => 'required|array'
        ],
        'label' => __('Instructions'),
        'name' => 'instruction'
    ]);

    $this->addFormItem([
        'id' => 'total',
        'parent_id' => 'order_lines',
        'type' => 'decimal',
        'label' => __('Total'),
        'readonly' => true,
        'display_only' => true,
        'name' => 'total'
    ]);
}

...
```
{% endcode %}

