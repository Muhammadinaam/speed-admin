# html

![](../.gitbook/assets/html-field.png)

```php
$this->addFormItem([
    'id' => 'order_totals',
    'parent_id' => null,
    'type' => 'html',
    'html' => <<<EOL
    <hr>
    <div class="row">
        <div class="col-md-6 offset-md-6">

            <table class="table totals">
                <tbody>
                    <tr>
                        <td>Excluding Tax</td>
                        <td><input class="form-control excluding-tax" readonly></td>
                    </tr>

                    <tr>
                        <td>Taxes</td>
                        <td><input class="form-control taxes" readonly></td>
                    </tr>

                    <tr>
                        <td>Total</td>
                        <td><input class="form-control total" readonly></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
    EOL
]);
```

