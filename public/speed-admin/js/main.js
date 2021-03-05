function getParameterByNameFromUrl(name, url) {
  name = name.replace(/[\[\]]/g, '\\$&');
  var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
    results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

function handleAjaxError(jqXhr) {
  if (jqXhr.status === 422) {


    //process validation errors here.
    errors = jqXhr.responseJSON.errors; //this will get the errors response data.
    //show them somewhere in the markup
    //e.g
    errorsHtml = '<div class="alert alert-danger text-left"><ul>';

    $.each(errors, function (key, value) {
      errorsHtml += '<li>' + value + '</li>'; //showing only the first error.
    });
    errorsHtml += '</ul></di>';

    Swal.fire({
      title: '<strong>'+window.errorText+'</strong>',
      icon: 'error',
      html: errorsHtml,
    })
  } else if (jqXhr.status === 401) {
    window.location.href = window.adminLoginUrl + "?redirect_after_login=" + window.location.href;
  } else if (jqXhr.status === 403) {
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
  let formGroup = $(input).closest('.form-group');
  let img = formGroup.find('img');
  img.attr('src', '');
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      img.attr('src', e.target.result);
      formGroup.find('.btn-remove').removeClass('d-none');
      formGroup.find('input [type="hidden"]').val('0');
    };

    reader.readAsDataURL(input.files[0]);
  } else {
    img.attr('src', '');
  }
}

function removeSelectedImage(button) {
  let formGroup = $(button).closest('.form-group');
  let img = formGroup.find('img');
  img.attr('src', '');

  formGroup.find('.btn-remove').addClass('d-none');
  formGroup.find('input[type="file"]').val('');
  formGroup.find('input[type="hidden"]').val('1');
}

function submitForm(event) {
  event.preventDefault();
  let form = event.target;

  enableFromSubmitButton(form, false);

  let formData = new FormData(form);
  let ajaxOptions = {
    data: formData,
    type: $(form).attr('method'),
    url: $(form).attr('action'),
    processData: false,
    contentType: false
  }

  console.log(ajaxOptions)
  
  $.ajax(ajaxOptions)
    .done(data => {
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
    .fail(handleAjaxError)
    .always(() => {
      enableFromSubmitButton(form, true);
    })

  return false;
}

function enableFromSubmitButton(form, isEnabled) {
  let button = $(form).find('button[type="submit"]');

  $(button).attr('disabled', !isEnabled);

  let classesToAdd = isEnabled ? 'fas fa-save' : 'fas fa-spinner fa-spin';
  let classesToRemove = isEnabled ? 'fas fa-spinner fa-spin' : 'fas fa-save';

  $(button).find('i').removeClass(classesToRemove).addClass(classesToAdd);
}