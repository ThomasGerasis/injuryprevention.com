import jwt_decode from "jwt-decode";
import { showLoginModal, showModalWithoutData } from "../components/modal";
import { dropdownElement } from "../components/dropdown-toggle";

let token = localStorage.getItem('token') ?? '';
const siteUrl = window.location.origin;

if (token) {
    const decodedToken = jwt_decode(token);
    if (decodedToken && Date.now() >= decodedToken.exp * 1000) {
        localStorage.removeItem('token');
        token = '';
    }
}


function loadGoogleSSOJsScript() {
    const script = document.createElement('script');
    script.src = 'https://accounts.google.com/gsi/client';
    script.async = true;
    document.body.appendChild(script);
}

window.addEventListener("load", (event) => {
    userActions()
        .then((userData) => {
            if (userData.modalLogin){
                const ajaxModal = document.getElementById("ajaxLoginModal");
                ajaxModal.querySelector(".modal-body").innerHTML = userData.modalLogin;
            }
            loadAuthAjaxModal(userData);
            lockedArticles(userData);
            document.addEventListener('click', async (event) => {
                const element = event.target.closest('.dropdown-toggle');
                if(!element) return;
                dropdownElement(element);
            });
        })
        .catch((err) => {
            console.log("rejected", err.message);
        })
});

export async function userActions() {
    let displayLoginRegisterButtons = document.querySelector('.users-buttons');
    const response = await fetch(siteUrl + '/ajaxFunctions/authorizeUser', {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "Cache-Control": "no-cache",
            "Authorization": `Bearer ${token}`
        },
    });

    if (response.status !== 200) {
        throw new Error("cannot fetch data");
    }

    let responseData = await response.json();
    displayLoginRegisterButtons.innerHTML = responseData.user.userMenuButtons;

    if ('status' in responseData.user) {
        localStorage.removeItem('token');
        loadGoogleSSOJsScript();
        return { "modalLogin": responseData.user.modalLogin ?? '' }
    }

    return {
        "username": responseData.user.username,
        "email": responseData.user.email,
    }
}

function loadAuthAjaxModal(userData) {
    let modalButton = document.querySelector(".loadAuthAjaxModal");
    if (modalButton) {
        if ('modalLogin' in userData) {
            modalButton.onclick = function() {
                const currentUrl = window.location.href;
                localStorage.setItem('signUpGoBackUrl', currentUrl);
                localStorage.setItem('signUpGoBackExpiration', Date.now() + 30 * 60 * 1000);  //set 30 minutes expiration
                showLoginModal();
            }
        } else {
            modalButton.onclick = function() {
                if (modalButton.classList.contains('loaded')) {
                    showModalWithoutData();
                } else {
                    getLockedArticle(userData, modalButton);
                }
            }
        }
    }

}

function lockedArticles(userData) {
    if (!userData.username && document.querySelector('.locked')) {
        showModalWithoutData();
        //wait 3 secs and rredirect to home
        setTimeout(() => {
            window.location.href = '/';
        }, 1000);
    }
}


function getLockedArticle(userData, button) {
    const ajaxModal = document.getElementById("ajaxModal");
    const articleUrl = button.dataset.url;  
    ajaxModal.querySelector(".modal-body").innerHTML = '<div class="loader">\n' +
        '<div></div>\n' +
        '<div></div>\n' +
        '</div>';

    if ('username' in userData) {
        window.location.href = articleUrl;  
    }
    return true;
}
