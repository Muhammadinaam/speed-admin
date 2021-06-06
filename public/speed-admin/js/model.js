speedAdminModel = {
  loadUrlInModal: function loadUrlInModal(config) {
    let modalDiv = speedAdminModel.createModal(config)
    speedAdminModel.openModal(config.id)
    axios.get(config.url)
      .then(response => {
        
        let modelBody = modalDiv.querySelector('.modal-body');
        speedAdmin.setInnerHTML(modelBody, response.data)

        speedAdmin.initializeUninitializedItems(modalDiv);

        if (config.urlLoadedCallback) {
          config.urlLoadedCallback(modalDiv);
        }
        
      })
      .catch((error) => {
        speedAdmin.handleAjaxError(error);
        modalDiv.querySelector('.modal-body').innerHTML = window.ajaxError;
      })
  },

  createModal: function createModal(config) {
    // https://dev.to/ara225/how-to-use-bootstrap-modals-without-jquery-3475
    let modelContainerDiv = document.createElement("div");
    modelContainerDiv.setAttribute('id', config.id)
    modelContainerDiv.classList.add('model-container')
    modelContainerDiv.innerHTML = `
    <div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true"
    role="dialog">
        <div class="modal-dialog modal-dialog-scrollable ${config.modalDialogClasses}" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">${config.modalTitle}</h5>
                    <button type="button" class="close" aria-label="Close" onclick="speedAdminModel.closeModal('${config.id}')">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    ${window.loading}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="speedAdminModel.closeModal('${config.id}')">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show" style="display: none;"></div>
    `;

    speedAdminModel.closeModalCallbacks[config.id] = config.closeModalCallback;

    document.body.appendChild(modelContainerDiv);
    return modelContainerDiv;
  },

  openModal: function openModal(id) {

    // make all other models transparent
    let otherModals = document.querySelectorAll(".modal.show");
    for(let i = 0; i < otherModals.length; i++) {
      otherModals[i].classList.add('hidden-by-model');
      otherModals[i].style.opacity = '0.3';
     }

    let modalDiv = document.getElementById(id);
    modalDiv.querySelector(".modal-backdrop").style.display = "block"
    modalDiv.querySelector(".modal").style.display = "block"
    modalDiv.querySelector(".modal").classList.add("show");
    modalDiv.querySelector(".modal").classList.add("show");
  },

  closeModalCallbacks: {},

  closeModal: function closeModal(id) {

    document.querySelector('.modal-backdrop').style

    let modalDiv = document.getElementById(id);
    modalDiv.querySelector(".modal-backdrop").style.display = "none"
    modalDiv.querySelector(".modal").style.display = "none"
    modalDiv.querySelector(".modal").classList.remove("show");

    

    if(speedAdminModel.closeModalCallbacks[id]) {
      // https://stackoverflow.com/a/27746324/5013099
      Promise.resolve(speedAdminModel.closeModalCallbacks[id](modalDiv)).finally(() => {
        modalDiv.parentNode.removeChild(modalDiv);
      });
    } else {
      modalDiv.parentNode.removeChild(modalDiv);
    }

    // show models hidden by this model
    let otherModals = document.querySelectorAll(".modal.hidden-by-model");
    for(let i = 0; i < otherModals.length; i++) {
      otherModals[i].classList.remove('hidden-by-model');
      otherModals[i].style.opacity = '1';
    }
  },
}