@if( isset($form_item['show_select_from_table_button']) && $form_item['show_select_from_table_button'] )
<button type="button" class="btn mx-1 btn-secondary" onclick="speedAdminBelongsTo.belongsToSelectFromTableButtonClicked(this)">
    <i class="fas fa-list"></i>
</button>
@endif

@if( isset($form_item['show_add_new_button']) && $form_item['show_add_new_button'] )
<button type="button" class="btn mx-1 btn-secondary" onclick="speedAdminBelongsTo.belongsToAddNewButtonClicked(this)">
    <i class="fas fa-plus-circle"></i>
</button>
@endif