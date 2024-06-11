import {showLoginModal} from "./modal";

export default class ReplaceContent {
    className;
    constructor(className = "replace-content") {
        this.className = className;
        this.addEventListenersToElementList();
    }

    addEventListenersToElementList() {
        ["click", "change", "input"].forEach((event) => {
            body.addEventListener(event, async (event) => {
                const element = event.target.closest('.'+this.className);
                if(!element) return;
                if (element.dataset.trigger != event.type) return;

                const listener = element.dataset.trigger;
                const target = element.dataset.target;
                const closest = element.dataset.closest;
                const endpoint = element.dataset.get;

                if (!listener || !target || !endpoint) {
                    return;
                }

                event.preventDefault();

                let targetElement = undefined;
                if (closest) {
                    targetElement = element.closest(target);
                } else {
                    targetElement = document.querySelector(target);
                }

                const loader = this.getLoaderHtml();
                targetElement.insertAdjacentHTML('beforeend',loader);

                const response = await this.makeRequest(endpoint);

                if (response.hasOwnProperty("notLoggedIn")) {
                    const currentUrl = window.location.href;
                    localStorage.setItem('signUpGoBackUrl', currentUrl);
                    localStorage.setItem('signUpGoBackExpiration', Date.now() + 30 * 60 * 1000); //set 30 minutes expiration

                    let loaderElement = document.querySelector(".replace-loader")
                    targetElement.removeChild(loaderElement);
                    if(response.message) {
                        document.getElementById("popup-message").innerHTML = response.message;
                    }
                    showLoginModal();
                }else{
                    targetElement.innerHTML = response.html;
                }
            });
        });
    }

    async makeRequest(endpoint) {
        const headers = this.getHeaders();

        const response = await fetch(endpoint, {
            method: "GET",
            headers: headers,
        });

        if (response.status !== 200) {
            throw new Error("cannot fetch data");
        }

        let responseData = await response.json();

        return responseData;
    }

    getHeaders() {
        let headers = {
            'Accept': 'application/json',
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        };

        const token = localStorage.getItem('token') ?? '';
        if (token) {
            headers = {
                'Accept': 'application/json',
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "Authorization": `Bearer ${token}`
            };
        }
        return headers;
    }

    getLoaderHtml() {
        return `<div class="position-absolute replace-loader" style="top:0;left:0;width:100%;height:100%;background:#3c3c3c8c;z-index:2">
                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:#333;font-size:2rem">
                    <div class="loader"><div></div><div></div></div>
                </div>
            </div>`;
    }
}
