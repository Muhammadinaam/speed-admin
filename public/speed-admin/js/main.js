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
    document.attachEvent('onreadystatechange', function() {
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
    for(let i = 0; i < errorMessages.length; i++) {
      errorsHtml += '<li>' + errorMessages[i][0] + '</li>'; //showing only the first error.
    }

    errorsHtml += '</ul></di>';

    Swal.fire({
      title: '<strong>'+window.errorText+'</strong>',
      icon: 'error',
      html: errorsHtml,
    })
  } else if (error.response.status === 401) {
    window.location.href = window.adminLoginUrl + "?redirect_after_login=" + window.location.href;
  } else if (error.response.status === 403) {
    Swal.fire({
      title: '<strong>'+window.errorText+'</strong>',
      icon: 'error',
      // text: jqXhr.responseJSON.message,
      text: window.noPermissionMessage
    })
  } else {
    Swal.fire({
      title: '<strong>'+window.errorText+'</strong>',
      icon: 'error',
      // text: jqXhr.responseJSON.message,
      text: window.ajaxError
    })
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

function submitForm(event) {
  event.preventDefault();
  let form = event.target;

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
        const event = new CustomEvent('saved', { obj: data.obj });
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

  if(isEnabled) {
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