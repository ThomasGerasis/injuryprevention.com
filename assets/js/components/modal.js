export function closeModal(){
    let modals = document.querySelectorAll(".modal-popup");
    modals.forEach((modal) => {
        modal.classList.remove('show');
        modal.style.display = 'none';
    });

    document.body.classList.remove('modal-open');
    if (document.querySelector('.modal-backdrop')){
        document.querySelector('.modal-backdrop').remove();
    }

    document.getElementById("popup-message").innerHTML = 'You must be a logged in user to do this.';
}

export function showModalWithData(data) {
    const ajaxModal = document.getElementById("ajaxModal");
    document.body.classList.add('modal-open');
    ajaxModal.querySelector(".modal-body").innerHTML = data;
    ajaxModal.classList.add('show');
    ajaxModal.style.display = "block";
    document.body.insertAdjacentHTML("beforeend", "<div class=\"modal-backdrop fade show\"></div>");
}

export function showModalWithoutData() {
    const ajaxModal = document.getElementById("ajaxModal");
    document.body.classList.add('modal-open');
    ajaxModal.classList.add('show');
    ajaxModal.style.display = "block";
    document.body.insertAdjacentHTML("beforeend", "<div class=\"modal-backdrop fade show\"></div>");
}


export function showLoginModal() {
    const ajaxModal = document.getElementById("ajaxLoginModal");
    document.body.classList.add('modal-open');
    ajaxModal.classList.add('show');
    ajaxModal.style.display = "block";
    document.body.insertAdjacentHTML("beforeend", "<div class=\"modal-backdrop fade show\"></div>");
}


