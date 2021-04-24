function getParameterByNameFromUrl(name, url) {
  name = name.replace(/[\[\]]/g, '\\$&');
  var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
    results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function ready(callbackFunc) {
  if (document.readyState !== 'loading') {
    // Document is already ready, call the callback directly
    callbackFunc();
  } else if (document.addEventListener) {
    // All modern browsers to register DOMContentLoaded
    document.addEventListener('DOMContentLoaded', callbackFunc);
  } else {
    // Old IE browsers
    document.attachEvent('onreadystatechange', function () {
      if (document.readyState === 'complete') {
        callbackFunc();
      }
    });
  }
}

function handleAjaxError(error) {
  if (error.response.status === 422) {


    //process validation errors here.
    errors = error.response.data.errors; //this will get the errors response data.
    //show them somewhere in the markup
    //e.g
    errorsHtml = '<div class="alert alert-danger text-left"><ul>';

    errorMessages = Object.values(errors);
    for (let i = 0; i < errorMessages.length; i++) {
      errorsHtml += '<li>' + errorMessages[i][0] + '</li>'; //showing only the first error.
    }

    errorsHtml += '</ul></di>';

    Swal.fire({
      title: '<strong>' + window.errorText + '</strong>',
      icon: 'error',
      html: errorsHtml,
    })
  } else if (error.response.status === 401) {
    window.location.href = window.adminLoginUrl + "?redirect_after_login=" + window.location.href;
  } else if (error.response.status === 403) {
    Swal.fire({
      title: '<strong>' + window.errorText + '</strong>',
      icon: 'error',
      // text: jqXhr.responseJSON.message,
      text: window.noPermissionMessage
    })
  } else {
    Swal.fire({
      title: '<strong>' + window.errorText + '</strong>',
      icon: 'error',
      // text: jqXhr.responseJSON.message,
      text: window.ajaxError
    })
  }
}

function removeRepeatedItem(button) {
  let repeatedItem = button.closest('.repeated-item');
  let repeater = button.closest('.repeater');
  let deletedItemsInput = repeater.querySelector('.deleted_items_input');
  let deletedItemId = repeatedItem.querySelector(".id_input").value;

  if (deletedItemId != -1) {
    deletedItemsInput.value = deletedItemsInput.value + "," + deletedItemId;
  }

  repeatedItem.parentNode.removeChild(repeatedItem);
}

function addRepeatedItem(button) {
  let repeatedItemsContainer = button.closest('.repeater').querySelector(':scope .repeated-items-container:first-of-type');
  let template = repeatedItemsContainer.querySelector('.template:first-of-type');
  let cloned = template.cloneNode(true);
  cloned.classList.remove('template');
  cloned.style.display = '';

  setInputsNames(cloned);

  repeatedItemsContainer.appendChild(cloned);

  let form = button.closest('form')
  initializeUninitializedItems(form);
}

function setInputsNames(clonedGroup) {
  inputs = clonedGroup.querySelectorAll('[data-__repeater__name]');
  for (let i = 0; i < inputs.length; i++) {
    let input = inputs[i];
    let name = input.dataset.__repeater__name;
    input.setAttribute('name', name);
  }
}

function initRepeater(repeater) {
  inputs = repeater.querySelectorAll('[name]');
  for (let i = 0; i < inputs.length; i++) {
    let input = inputs[i];

    if (input.classList.contains('deleted_items_input')) {
      continue;
    }

    // only set names of inputs which are children of this
    // repeater and not the children of a nested repeater 
    if (input.closest('.repeater') == repeater) {

      if (input.closest('.template') != null) // inside template
      {
        let name = input.getAttribute('name');
        input.setAttribute('name', '');
        input.dataset.__repeater__name = name + '[]';
      } else {
        let name = input.getAttribute('name');

        input.setAttribute('name', name + '[]');
      }

    }
  }
}

function initBelongsTo(input) {
  $(input).select2({
    theme: 'bootstrap4',
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
}

function initializeUninitializedItems(form) {
  items = form.querySelectorAll('[data-initialized="false"]:not(.template *)');
  console.log(items);

  for (let i = 0; i < items.length; i++) {
    let item = items[i];

    let initializeFunctionName = item.dataset.initialize_function_name;

    window[initializeFunctionName](item)

    item.dataset.initialized = true;
  }
}

function showSelectedImage(input) {
  let formGroup = input.closest('.form-group');
  let img = formGroup.querySelector('img');
  img.getAttribute('src', '');
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      img.setAttribute('src', e.target.result);
      formGroup.querySelector('.btn-remove').classList.remove('d-none');
      formGroup.querySelector('input[type="hidden"]').value = 0;
    };

    reader.readAsDataURL(input.files[0]);
  } else {
    img.setAttribute('src', '');
  }
}

function removeSelectedImage(button) {
  let formGroup = button.closest('.form-group');
  let img = formGroup.querySelector('img');
  img.setAttribute('src', '');

  formGroup.querySelector('.btn-remove').classList.add('d-none');
  formGroup.querySelector('input[type="file"]').value = '';
  formGroup.querySelector('input[type="hidden"]').value = '1';
}

function setIndexOnMultipleSelectsInRepeaters(form) {
  let repeaters = form.querySelectorAll('.repeater');
  for (let i = 0; i < repeaters.length; i++) {
    let selects = repeaters[i].querySelectorAll('select[multiple]');
    let index = 0;
    for (let j = 0; j < selects.length; j++) {
      if (selects[j].closest('.repeater') == repeaters[i]) {
        let nameWithoutBrackets = selects[j].getAttribute('name');
        if (nameWithoutBrackets) {
          let bracketStart = nameWithoutBrackets.indexOf("[");
          nameWithoutBrackets = nameWithoutBrackets.substring(0, bracketStart);

          let newName = nameWithoutBrackets + '[' + index + '][]';
          selects[j].setAttribute('name', newName)
          index++;
        }
      }
    }
  }
}

function submitForm(event) {
  event.preventDefault();
  let form = event.target;

  setIndexOnMultipleSelectsInRepeaters(form);

  enableFromSubmitButton(form, false);

  let formData = new FormData(form);
  let ajaxOptions = {
    data: formData,
    method: form.getAttribute('method'),
    url: form.getAttribute('action'),
    processData: false,
    contentType: false
  }

  axios(ajaxOptions)
    .then(resp => {
      let data = resp.data;

      Swal.fire({
        title: '<strong>' + (data.success ? window.successText : window.errorText) + '</strong>',
        icon: data.success ? 'success' : 'error',
        text: data.message,
      }).then(() => {
        if (data.success) {
          const event = new CustomEvent('saved', {
            obj: data.obj
          });
          form.dispatchEvent(event);
        }
      })
    })
    .catch(handleAjaxError)
    .finally(() => {
      enableFromSubmitButton(form, true);
    })

  return false;
}

function enableFromSubmitButton(form, isEnabled) {
  let button = form.querySelector('button[type="submit"]');

  if (isEnabled) {
    button.removeAttribute("disabled");
  } else {
    button.setAttribute("disabled", "disabled");
  }

  let classesToAdd = isEnabled ? 'fas fa-save' : 'fas fa-spinner fa-spin';
  let classesToRemove = isEnabled ? 'fas fa-spinner fa-spin' : 'fas fa-save';

  let i = button.querySelector('i');
  i.classList.remove(...classesToRemove.split(' '))
  i.classList.add(...classesToAdd.split(' '));
}

function getGridTableFromUniqid(uniqid) {
  return document.getElementById("table_" + uniqid)
}

function getGridData(uniqid) {
  let table = getGridTableFromUniqid(uniqid);
  document.getElementById("loader-" + uniqid).style.display = 'block';
  document.getElementById("table-body-" + uniqid).innerHTML = '';
  document.getElementById("table-pagination-info-" + uniqid).innerHTML = '';

  let getDataUrl = table.dataset.get_data_url;
  let indexUrl = new URL(table.dataset.index_url)
  let url = new URL(getDataUrl);

  var form = document.querySelector('#form-options-' + uniqid);
  var data = new FormData(form);

  url.search = new URLSearchParams(data)
  let newIndexUrl = new URL(table.dataset.index_url);
  newIndexUrl.search = new URLSearchParams(data);

  axios.get(url)
    .then(response => {
      let data = response.data;

      let items = data.paginated_data.data;

      let tableBody = document.getElementById("table-body-" + uniqid);
      let tablePagination = document.getElementById("table-pagination-" + uniqid);

      tablePagination.innerHTML = '';
      tablePagination.innerHTML = data.links;
      let pagination = document.querySelector("#table-pagination-" + uniqid + " .pagination");
      if (pagination) {
        pagination.classList.add('pagination-sm');
      }

      document.getElementById("table-pagination-info-" + uniqid).innerHTML = data.pagination_info;

      let checkBoxesVisible = table.querySelector('th.checkboxes') != null;
      let radioButtonsVisible = table.querySelector('th.radiobuttons') != null;

      let tableRows = '';
      for (let i = 0; i < items.length; i++) {
        tableRows += '<tr data-id="'+items[i]['__id__']+'">';

        if (checkBoxesVisible) {
          tableRows += `<td class="checkboxes">
                <input class="selectors" type="checkbox" data-id='` + items[i]['__id__'] + `' onclick="gridCheckboxClicked('${uniqid}', this, '` + items[i]['__id__'] + `', '` + items[i]['__text__'] + `')" />
            </td>`;
        }

        if (radioButtonsVisible) {
          tableRows += `<td class="radiobuttons">
                <input class="selectors" type="radio" data-id='` + items[i]['__id__'] + `' name="radio_"` + uniqid + ` onclick="gridRadioClicked('${uniqid}', this, '` + items[i]['__id__'] + `', '` + items[i]['__text__'] + `')" />
            </td>`;
        }

        let keys = Object.keys(items[i]);

        for (let j = 0; j < keys.length; j++) {
          let key = keys[j];
          if (key != '__id__' && key != '__text__') {
            tableRows += '<td>' + items[i][key] + '</td>';
          }
        }

        // action column
        let editButton = '';
        if (table.dataset.has_edit_permission == 1) {
          editButton = `<a href="` + indexUrl + `/` + items[i].__id__ + `/edit" class="btn btn-sm btn-info" >
                <i class="fas fa-edit"></i>
            </a>`
        }

        let deleteButton = '';
        if (table.dataset.has_delete_permission == 1) {
          deleteButton = `<button type="button" onclick="deleteGridItem('${uniqid}', '${items[i].__id__}')" class="btn btn-sm btn-danger" >
                <i class="fas fa-trash"></i>
            </button>`
        }

        let otherActionButtons = document.getElementById(`other_actions_buttons_template_${uniqid}`).innerHTML;

        tableRows += '<td>' + otherActionButtons + editButton + deleteButton + '</td>';

        tableRows += '</tr>';
      }

      tableBody.innerHTML = tableRows;

      updateGridSelection(uniqid)

      window.history.pushState("object or string", "Title", newIndexUrl);
    })
    .catch(error => {
      console.log(error);
      handleAjaxError(error)
    })
    .finally(() => {
      document.getElementById("loader-" + uniqid).style.display = 'none';
    });
}

function updateGridSelection(uniqid) {
  
  allCheckboxes = document.querySelectorAll(`#table_${uniqid} .selectors`);
  allRadioButtons = document.querySelectorAll(`#table_${uniqid} .selectors`);

  let idInput = document.querySelector(`[name='selected_items_ids_${uniqid}']`);
  let idsArray = idInput.value.split(',');

  for(let i = 0; i < allCheckboxes.length; i++) {
      allCheckboxes[i].checked = false;
      allRadioButtons[i].checked = false;
  }

  let selectorsCheckedCount = 0;
  for(let i = 0; i < idsArray.length; i++) {
      let query = `#table_${uniqid} [data-id="${idsArray[i]}"].selectors`;
      let selectors = document.querySelectorAll(query);
      for(let j = 0; j < selectors.length; j++) {
          selectors[j].checked = true;
          selectorsCheckedCount++;
      }
  }

  document.querySelector(`[name='selected_items_texts_${uniqid}']`).style.display = 
      selectorsCheckedCount > 0 ? 'block' : 'none';
}

function deleteGridItem(uniqid, id) {
  let table = getGridTableFromUniqid(uniqid);
  let indexUrl = new URL(table.dataset.index_url)

  Swal.fire({
      title: window.areYouSure,
      text: window.youCantUndoIt,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: window.yesIamSure
  }).then((result) => {
      if (result.isConfirmed) {

          axios.post(indexUrl +'/' + id, {
              '_method': 'DELETE', 
              '_token': window.csrfToken
          })
          .catch(error => {
              handleAjaxError(error);
          })
          .then((response) => {
              data = response.data;
              Swal.fire(
              "",
              data.message,
              data.success ? 'success' : 'error'
              )

              getGridData(uniqid)
          })
      }
  })
}

function gridCheckboxClicked(uniqid, checkbox, id, text) {
  addRemoveSelectedItems(uniqid, checkbox.checked, id, text);
  updateGridSelection(uniqid)
}

function gridRadioClicked(uniqid, radio, id, text) {
  clearSelectedGridItems(uniqid)
  addRemoveSelectedItems(uniqid, radio.checked, id, text);
  updateGridSelection(uniqid)
}

function addRemoveSelectedItems(uniqid, isAdd, id, text) {
  let SEPERATOR = ',';
  let textInput = document.querySelector(`[name='selected_items_texts_${uniqid}']`);
  let idInput = document.querySelector(`[name='selected_items_ids_${uniqid}']`);

  let idsArray = idInput.value ? idInput.value.split(SEPERATOR) : [];
  let textsArray = idsArray.length == 0 ? [] : textInput.value.split(SEPERATOR);

  if (isAdd) {
      if (!idsArray.includes(id)) {
          idsArray.push(id)
          textsArray.push(text)
      }
  } else {
      // remove
      idsArray = idsArray.filter(x => x != id)
      textsArray = textsArray.filter(x => x != text)
  }

  idInput.value = idsArray.join(SEPERATOR);
  textInput.value = textsArray.join(SEPERATOR)
}

function clearSelectedGridItems(uniqid) {
  let textInput = document.querySelector(`[name='selected_items_texts_${uniqid}']`);
  let idInput = document.querySelector(`[name='selected_items_ids_${uniqid}']`);
  textInput.value = '';
  idInput.value = '';
}

function updateGridOrderButtonsUI(uniqid) {

  // reset all buttons
  buttons = document.querySelectorAll(`#table_${uniqid} .btn-order`)
  for(let i = 0; i < buttons.length; i++) {
      const button = buttons[i];
      button.classList.remove('btn-info');
      button.classList.add('btn-secondary');
      button.querySelector('span').innerHTML = '';
      button.querySelector('i').className = 'fas fa-arrows-alt-v';
  }

  let order = document.querySelector(`#form-options-${uniqid} [name="order"]`).value;
  let orderItems = order == '' ? [] :order.split(',');
  for(let i = 0; i < orderItems.length; i++) {
      let orderItem = orderItems[i];
      let orderItemParts = orderItem.split(':');
      let orderItemField = orderItemParts[0];
      let orderItemAscOrDesc = orderItemParts[1];

      let button = document.querySelector(`#order_button_${orderItemField}_${uniqid}`);
      button.classList.remove('btn-secondary');
      button.classList.add('btn-info');
      button.querySelector('span').innerHTML = i+1;
      button.querySelector('i').className = orderItemAscOrDesc == 'asc' ? 'fas fa-arrow-up' : 'fas fa-arrow-down';
  }
}

function setGridOrder(uniqid, fieldName) {
  // update order field
  let order = document.querySelector(`#form-options-${uniqid} [name="order"]`).value;
  let orderItems = order == '' ? [] :order.split(',');
  let newOrderItems = [];

  let alreadyExists = false;
  for(let i = 0; i < orderItems.length; i++) {
      let orderItemParts = orderItems[i].split(':');
      let orderItemField = orderItemParts[0];
      let orderItemAscOrDesc = orderItemParts[1];

      if(orderItemField == fieldName) {
          alreadyExists = true;

          if(orderItemAscOrDesc == 'asc')
          {
              orderItemAscOrDesc = 'desc';
              newOrderItems.push(orderItemField + ":" + orderItemAscOrDesc);
          }
      } else {
          newOrderItems.push(orderItemField + ":" + orderItemAscOrDesc);
      }
  }

  if(alreadyExists == false) {
      newOrderItems.push(fieldName+":asc")
  }
  document.querySelector(`#form-options-${uniqid} [name="order"]`).value = newOrderItems.join(',');

  // update order buttons look
  updateGridOrderButtonsUI(uniqid);

  // get data
  getGridData(uniqid)
}

function toggleGridFilters(uniqid){
  document.querySelector(`#form-options-${uniqid} .filters`).classList.toggle('d-none');
}

function gridOtherActionButtonClicked(uniqid, button, actionId) {
  let table = getGridTableFromUniqid(uniqid);
  let model = table.dataset.model;
  let id = button.closest('tr').dataset.id;
  performGridAction(uniqid, model, actionId, [id]);
}

function performGridAction(uniqid, model, actionId, ids) {
  let baseUrl = window.adminBaseUrl;
  
  let ajaxOptions = {
    data: {model, ids, action_id: actionId},
    method: 'post',
    url: baseUrl + '/perform-grid-action',
  }

  Swal.fire({
    title: window.areYouSure,
    // text: window.youCantUndoIt,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: window.yesIamSure
  }).then((result) => {
    if (result.isConfirmed) {
      axios(ajaxOptions)
        .then(resp => {
          if(resp.data.success) {
            getGridData(uniqid)
          }
        })
        .catch(handleAjaxError)
    }
  })
}

function gridActionSelectedBtnClicked(uniqid) {
  let ids = document.querySelector(`[name=selected_items_ids_${uniqid}]`).value.split(',');
  let { model, actionId } = getGridModelAndSelectedActionId(uniqid);
  if(actionId == '') {
    alert('Please select action');
    return;
  }
  performGridAction(uniqid, model, actionId, ids);
}

function gridActionAllBtnClicked(uniqid) {
  let ids = '__all__';
  let { model, actionId } = getGridModelAndSelectedActionId(uniqid);
  if(actionId == '') {
    alert('Please select action');
    return;
  }
  performGridAction(uniqid, model, actionId, ids);
}

function getGridModelAndSelectedActionId(uniqid) {
  let table = getGridTableFromUniqid(uniqid);
  let model = table.dataset.model;
  let grid_action_select = document.getElementById(`grid_action_${uniqid}`);
  let actionId = grid_action_select.value;
  return { model, actionId };
}

function belongsToAddNewButtonClicked(uniqid) {
  alert(uniqid)
}

function belongsToSelectFromTableButtonClicked(uniqid) {
  alert(uniqid)
}

function loadUrlInModal(config) {

}