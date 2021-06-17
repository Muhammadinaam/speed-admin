speedAdminBelongsTo = {
  initBelongsTo: function initBelongsTo(input) {
    $(input).select2({
      // theme: 'bootstrap4',
      allowClear: true,
      placeholder: '...',
      ajax: {
        url: input.dataset.url,
        dataType: 'json',
        data: function (params) {
          var query = {
            model: input.dataset.model,
            main_model: input.dataset.main_model,
            form_item_id: input.dataset.form_item_id,
            dataset: JSON.stringify(input.dataset)
          }
          return query
        }
      }
    });
  },

  belongsToAddNewButtonClicked: function belongsToAddNewButtonClicked(btn) {
    let modalId = 'modal_' + new Date().valueOf();

    let select = btn.closest('div').querySelector('select');
    let dataModelName = select.dataset['model'];

    speedAdminModel.loadUrlInModal({
      id: modalId,
      url: window.showAddNewFormRoute + '?dont_redirect_on_save=1&model=' + dataModelName,
      modalDialogClasses: 'modal-xl',
      modalTitle: window.addNew,
      closeModalCallback: () => {
      },
      urlLoadedCallback: (modalDiv) => {
        let form = modalDiv.querySelector('form');
        form.addEventListener('saved', function(e) {
          console.log(e.detail);
          $(select)
            .append(new Option(e.detail.text, e.detail.id, false, true))
            .trigger('change')

          $(select).trigger({
            type: 'select2:select',
            params: {
                data: e.detail
            }
          });

          let modalDivId = form.closest('.model-container').getAttribute('id')
          speedAdminModel.closeModal(modalDivId);
        })
      }
    })
  },
  
  belongsToSelectFromTableButtonClicked: function belongsToSelectFromTableButtonClicked(btn) {
    alert("this feature will be available soon")
  },
}