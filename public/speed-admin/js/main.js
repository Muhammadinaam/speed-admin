speedAdmin = {

  // this function sets innerhtml and also executes scripts
  // https://stackoverflow.com/a/47614491/5013099
  setInnerHTML: function(elm, html) {
    elm.innerHTML = html;
    Array.from(elm.querySelectorAll("script")).forEach( oldScript => {
      const newScript = document.createElement("script");
      Array.from(oldScript.attributes)
        .forEach( attr => newScript.setAttribute(attr.name, attr.value) );
      newScript.appendChild(document.createTextNode(oldScript.innerHTML));
      oldScript.parentNode.replaceChild(newScript, oldScript);
    });
  },
  getParameterByNameFromUrl: function getParameterByNameFromUrl(name, url) {
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
      results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  },
  
  ready: function ready(callbackFunc) {
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
  },
  
  handleAjaxError: function handleAjaxError(error) {
    if (!error.response) {
      console.error(error);
      alert(window.errorText);
      return;
    }

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
  },
  
  removeRepeatedItem: function removeRepeatedItem(button) {
    let repeatedItem = button.closest('.repeated-item');
    let repeater = button.closest('.repeater');
    let deletedItemsInput = repeater.querySelector('.deleted_items_input');
    let deletedItemId = repeatedItem.querySelector(".id_input").value;
  
    if (deletedItemId != -1) {
      deletedItemsInput.value = deletedItemsInput.value + "," + deletedItemId;
    }
  
    repeatedItem.parentNode.removeChild(repeatedItem);
  },
  
  addRepeatedItem: function addRepeatedItem(button) {
    let repeatedItemsContainer = button.closest('.repeater').querySelector(':scope .repeated-items-container:first-of-type');
    let template = repeatedItemsContainer.querySelector('.template:first-of-type');
    let cloned = template.cloneNode(true);
    cloned.classList.remove('template');
    cloned.style.display = '';
  
    speedAdmin.setInputsNames(cloned);
  
    repeatedItemsContainer.appendChild(cloned);
  
    let form = button.closest('form')
    speedAdmin.initializeUninitializedItems(form);
  },
  
  setInputsNames: function setInputsNames(clonedGroup) {
    inputs = clonedGroup.querySelectorAll('[data-__repeater__name]');
    for (let i = 0; i < inputs.length; i++) {
      let input = inputs[i];
      let name = input.dataset.__repeater__name;
      input.setAttribute('name', name);
    }
  },
  
  initRepeater: function initRepeater(repeater) {
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
  },
  
  initializeUninitializedItems: function initializeUninitializedItems(parent) {
    items = parent.querySelectorAll('[data-initialized="false"]:not(.template *)');
  
    for (let i = 0; i < items.length; i++) {
      let item = items[i];
  
      let initializeFunctionName = item.dataset.initialize_function_name;

      let func = speedAdmin[initializeFunctionName];
      if (!func) {
        func = window[initializeFunctionName];
      }

      if (!func) {
        // https://stackoverflow.com/a/40634765/5013099
        const getValue = (object, keys) => keys.split('.').reduce((o, k) => (o || {})[k], object);

        func = getValue(window, initializeFunctionName);
      }

      func(item)
  
      item.dataset.initialized = true;
    }
  },
  
  showSelectedImage: function showSelectedImage(input) {
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
  },
  
  removeSelectedImage: function removeSelectedImage(button) {
    let formGroup = button.closest('.form-group');
    let img = formGroup.querySelector('img');
    img.setAttribute('src', '');
  
    formGroup.querySelector('.btn-remove').classList.add('d-none');
    formGroup.querySelector('input[type="file"]').value = '';
    formGroup.querySelector('input[type="hidden"]').value = '1';
  },
  
  setIndexOnFieldsInRepeaters: function setIndexOnFieldsInRepeaters(form) {
    let repeaters = form.querySelectorAll('.repeater');
    for (let i = 0; i < repeaters.length; i++) {
      let repeatedItems = repeaters[i].querySelectorAll('.repeated-item:not(.template)');

      for (let repeatedItemIndex = 0; repeatedItemIndex < repeatedItems.length; repeatedItemIndex++) {

        let inputs = repeatedItems[repeatedItemIndex].querySelectorAll('select,input');
        for (let j = 0; j < inputs.length; j++) {
          if (inputs[j].closest('.repeater') == repeaters[i]) {
            let nameWithBrackets = inputs[j].getAttribute('name');
            if (nameWithBrackets) {
              const regex = /\[.*?\]/;  // regex to replace first occurance of [] or [1] or [2], [...]
              let newName = nameWithBrackets.replace(regex, '[' + repeatedItemIndex + ']');
              
              inputs[j].setAttribute('name', newName)
            }
          }
        }
      }

    }
  },
  
  submitForm: function submitForm(event) {
    event.preventDefault();
    let form = event.target;
  
    speedAdmin.setIndexOnFieldsInRepeaters(form);
  
    speedAdmin.enableFromSubmitButton(form, false);
  
    let formData = new FormData(form);
    
    let model = form.dataset['model'];
    if(model) {
      formData.append('_model_', model);
    }

    // select fields don't send value if null,
    // this creates problem when select field is in repeater
    // as it misses the array index
    let fields = form.querySelectorAll('select')
    for(let i = 0; i < fields.length; i++) {
      if (!fields[i].value) {
        formData.append(fields[i].getAttribute('name'), '');
      }
    }

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
              detail: data.obj
            });
            form.dispatchEvent(event);
          }
        })
      })
      .catch(speedAdmin.handleAjaxError)
      .finally(() => {
        speedAdmin.enableFromSubmitButton(form, true);
      })
  
    return false;
  },
  
  enableFromSubmitButton: function enableFromSubmitButton(form, isEnabled) {
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
  },
  
  getGridTableFromUniqid: function getGridTableFromUniqid(uniqid) {
    return document.getElementById("table_" + uniqid)
  },
  
  getGridData: function getGridData(uniqid) {
    let table = speedAdmin.getGridTableFromUniqid(uniqid);
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
                  <input class="selectors" type="checkbox" data-id='` + items[i]['__id__'] + `' onclick="speedAdmin.gridCheckboxClicked('${uniqid}', this, '` + items[i]['__id__'] + `', '` + items[i]['__text__'] + `')" />
              </td>`;
          }
  
          if (radioButtonsVisible) {
            tableRows += `<td class="radiobuttons">
                  <input class="selectors" type="radio" data-id='` + items[i]['__id__'] + `' name="radio_"` + uniqid + ` onclick="speedAdmin.gridRadioClicked('${uniqid}', this, '` + items[i]['__id__'] + `', '` + items[i]['__text__'] + `')" />
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
            deleteButton = `<button type="button" onclick="speedAdmin.deleteGridItem('${uniqid}', '${items[i].__id__}')" class="btn btn-sm btn-danger" >
                  <i class="fas fa-trash"></i>
              </button>`
          }
  
          let otherActionButtons = document.getElementById(`other_actions_buttons_template_${uniqid}`).innerHTML;
  
          tableRows += '<td>' + otherActionButtons + editButton + deleteButton + '</td>';
  
          tableRows += '</tr>';
        }
  
        tableBody.innerHTML = tableRows;
  
        speedAdmin.updateGridSelection(uniqid)
  
        window.history.pushState("object or string", "Title", newIndexUrl);
      })
      .catch(error => {
        console.log(error);
        speedAdmin.handleAjaxError(error)
      })
      .finally(() => {
        document.getElementById("loader-" + uniqid).style.display = 'none';
      });
  },
  
  updateGridSelection: function updateGridSelection(uniqid) {
    
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
  },
  
  deleteGridItem: function deleteGridItem(uniqid, id) {
    let table = speedAdmin.getGridTableFromUniqid(uniqid);
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
                speedAdmin.handleAjaxError(error);
            })
            .then((response) => {
                data = response.data;
                Swal.fire(
                "",
                data.message,
                data.success ? 'success' : 'error'
                )
  
                speedAdmin.getGridData(uniqid)
            })
        }
    })
  },
  
  gridCheckboxClicked: function gridCheckboxClicked(uniqid, checkbox, id, text) {
    speedAdmin.addRemoveSelectedItems(uniqid, checkbox.checked, id, text);
    speedAdmin.updateGridSelection(uniqid)
  },
  
  gridRadioClicked: function gridRadioClicked(uniqid, radio, id, text) {
    speedAdmin.clearSelectedGridItems(uniqid)
    speedAdmin.addRemoveSelectedItems(uniqid, radio.checked, id, text);
    speedAdmin.updateGridSelection(uniqid)
  },
  
  addRemoveSelectedItems: function addRemoveSelectedItems(uniqid, isAdd, id, text) {
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
  },
  
  clearSelectedGridItems: function clearSelectedGridItems(uniqid) {
    let textInput = document.querySelector(`[name='selected_items_texts_${uniqid}']`);
    let idInput = document.querySelector(`[name='selected_items_ids_${uniqid}']`);
    textInput.value = '';
    idInput.value = '';
  },
  
  updateGridOrderButtonsUI: function updateGridOrderButtonsUI(uniqid) {
  
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
  },
  
  setGridOrder: function setGridOrder(uniqid, fieldName) {
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
    speedAdmin.updateGridOrderButtonsUI(uniqid);
  
    // get data
    speedAdmin.getGridData(uniqid)
  },
  
  toggleGridFilters: function toggleGridFilters(uniqid){
    document.querySelector(`#form-options-${uniqid} .filters`).classList.toggle('d-none');
  },
  
  gridOtherActionButtonClicked: function gridOtherActionButtonClicked(uniqid, button, actionId) {
    let table = speedAdmin.getGridTableFromUniqid(uniqid);
    let model = table.dataset.model;
    let id = button.closest('tr').dataset.id;
    speedAdmin.performGridAction(uniqid, model, actionId, [id]);
  },
  
  performGridAction: function performGridAction(uniqid, model, actionId, ids) {
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
              speedAdmin.getGridData(uniqid)
            }
          })
          .catch(speedAdmin.handleAjaxError)
      }
    })
  },
  
  gridActionSelectedBtnClicked: function gridActionSelectedBtnClicked(uniqid) {
    let ids = document.querySelector(`[name=selected_items_ids_${uniqid}]`).value.split(',');
    let { model, actionId } = speedAdmin.getGridModelAndSelectedActionId(uniqid);
    if(actionId == '') {
      alert('Please select action');
      return;
    }
    speedAdmin.performGridAction(uniqid, model, actionId, ids);
  },
  
  gridActionAllBtnClicked: function gridActionAllBtnClicked(uniqid) {
    let ids = '__all__';
    let { model, actionId } = speedAdmin.getGridModelAndSelectedActionId(uniqid);
    if(actionId == '') {
      alert('Please select action');
      return;
    }
    speedAdmin.performGridAction(uniqid, model, actionId, ids);
  },
  
  getGridModelAndSelectedActionId: function getGridModelAndSelectedActionId(uniqid) {
    let table = speedAdmin.getGridTableFromUniqid(uniqid);
    let model = table.dataset.model;
    let grid_action_select = document.getElementById(`grid_action_${uniqid}`);
    let actionId = grid_action_select.value;
    return { model, actionId };
  }
}
