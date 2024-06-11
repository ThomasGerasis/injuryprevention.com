import "../scss/main.scss";

function handleContentIntersection(entries) {
    entries.map((entry) => {
        if (entry.isIntersecting) {
            if (entry.target.classList.contains('lazyload-content')) {
                if (entry.isIntersecting) {
                    entry.target.classList.remove('lazyload-content');
                    let contentUrl = entry.target.getAttribute('data-url');
                    loadContent(contentUrl).then((data) => {
                        entry.target.innerHTML = data.contentHtml;
                        if (entry.target.querySelector('.swiper-container')) {
                            setUpSliders('#' + entry.target.getAttribute('id') + ' .swiper-container');
                        }
                    })
                        .catch((err) => {
                            console.log("error", err.message);
                        });
                }
            }
        }
    });
}

async function loadContent(contentUrl) {

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

    const response = await fetch(contentUrl, {
        method: "GET",
        headers: headers,
    });

    if (response.status !== 200) {
        throw new Error("cannot fetch data");
    }

    let responseData = await response.json();

    return responseData;
}

const observer = new IntersectionObserver(handleContentIntersection);
window.addEventListener("load", (event) => {
    document.querySelectorAll('.lazyload-content').forEach((i) => {
        if (i) {
            observer.observe(i);
        }
    });
});

document.addEventListener("DOMContentLoaded", function(event) {

    const scrollElement = document.getElementById('scroll-to-top');
    if(scrollElement){
        document.addEventListener("scroll", (event) => {
            if (document.documentElement.scrollTop > 500) {
                scrollElement.classList.add('show');
            } else {
                scrollElement.classList.remove('show');
            }
        });
        scrollElement.addEventListener('click', (event) => {
            window.scrollTo(0,0);
            return false;
        });
    }

    document.querySelectorAll('.toggle-menu').forEach(el => el.addEventListener('click', event => {
        document.body.classList.toggle('show-sidebar');
        if (document.body.classList.contains('show-sidebar')) {
            document.body.setAttribute('scroll', 'no');
        } else {
            document.body.removeAttribute('scroll');
        }
        // if not mobile
        [].forEach.call(document.querySelectorAll(".hide-on-toggle-js.show"), function(el) {
            el.classList.remove("show");
        });
        [].forEach.call(document.querySelectorAll(".has-submenu.submenu-open"), function(el) {
            el.classList.remove("submenu-open");
        });
    }));


    document.querySelectorAll('.backdrop-toggle-menu').forEach(el => el.addEventListener('click', event => {
        document.body.classList.toggle('show-sidebar');
        if (document.body.classList.contains('show-sidebar')) {
            document.body.setAttribute('scroll', 'no');
        } else {
            document.body.removeAttribute('scroll');
        }
    }));


    document.querySelectorAll('.toggle-tab').forEach(el => el.addEventListener('click', event => {
        const parentContainer = el.closest('.tab-container');
        const parentContainerId = el.closest('.tab-container').id;
        const parentTabsContainer = el.closest('.toggle-tabs');
        if (el.classList.contains('channel-toggle')) {
            //channel schedule
            let outerParent = parentContainer.parentElement.parentElement;
            // make all tabs and toggles inactive
            [].forEach.call(outerParent.querySelectorAll(".channel-toggle"), function(el) {
                el.classList.remove("active-tab");
            });
            [].forEach.call(outerParent.querySelectorAll(".channel-tab-content"), function(el) {
                el.classList.add("d-none");
            });
            //activate only the ones based on channel key
            let activeChannel = el.dataset.channel;
            let activeChannelTabs = document.querySelectorAll(".channel-toggle[data-channel=" + activeChannel + "]");
            activeChannelTabs.forEach((activeChannelTab) => {
                activeChannelTab.classList.add("active-tab");
                document.querySelector('#' + activeChannelTab.dataset.target + '').classList.remove('d-none');
            });
        } else {
            [].forEach.call(parentTabsContainer.querySelectorAll(".toggle-tab.active-tab"), function(el) {
                el.classList.remove("active-tab");
            });
            //activate current tab
            el.classList.add("active-tab");

            [].forEach.call(parentContainer.querySelectorAll('#' + parentContainerId + " > .tab-content"), function(el) {
                el.classList.add("d-none");
            });
            document.getElementById(el.getAttribute('data-target')).classList.remove("d-none");
        }
        return false;

    }));

    document.querySelectorAll('.toggle-filter').forEach(el => el.addEventListener('click', event => {
        const parentContainer = el.closest('.filter-container');
        const parentContainerId = el.closest('.filter-container').id;
        const parentTabsContainer = el.closest('.toggle-filters');
        [].forEach.call(parentTabsContainer.querySelectorAll(".toggle-filter.active-filter"), function(el) {
            el.classList.remove("active-filter");
        });
        el.classList.add("active-filter");
        [].forEach.call(parentContainer.querySelectorAll('#' + parentContainerId + " .filter-content"), function(el) {
            el.classList.add("d-none");
        });
        [].forEach.call(parentContainer.querySelectorAll('#' + parentContainerId + " ." + el.getAttribute('data-target')), function(el) {
            el.classList.remove("d-none");
        });
        return false;
    }));

    document.querySelectorAll('.toggle-content').forEach(el => el.addEventListener('click', event => {
        const parentContainer = el.closest('.toggle-content');
        parentContainer.classList.toggle('active');
        return false;
    }));



    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link')
    function colorLink() {
        if (linkColor) {
            linkColor.forEach(l => l.classList.remove('active'))
            this.classList.add('active')
        }
    }

    if (window.location.hash){
        let hashId = window.location.hash.substring(1);
        let hashContainer = document.getElementById('' + hashId + 'Container');
        if(hashContainer){
            window.scrollTo(0, hashContainer.offsetTop - document.getElementById('site-header').offsetHeight - 30);
        }
    }

});


