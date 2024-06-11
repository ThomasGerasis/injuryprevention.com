import "../../scss/components/pagination.scss";

document.body.addEventListener('click', (event) => {
    if (!event.target.classList.contains('pagination-link')) {
        return;
    }
    event.preventDefault();
    // αν είναι ήδη σε αυτή τη σελίδα, να μην τα ξανατραβάει
    if(!event.target.classList.contains('active')){
        fetchPaginationContent(event.target);
    }
});


function fetchPaginationContent(link) {
    let ajaxContainerID = link.closest('.paginated-content-container').id;

    let addUserId = link.closest('.paginated-content-container').classList.contains('add-user-id');
    let headers = {
        "Content-Type": "text/html",
        "X-Requested-With": "XMLHttpRequest"
    };
    if(addUserId){
        const token = localStorage.getItem('token') ?? '';
        if (token) {
            headers = {
                "Content-Type": "text/html",
                "X-Requested-With": "XMLHttpRequest",
                "Authorization": `Bearer ${token}`
            };
        }
    }

    //let ajaxContainerID = link.dataset.target;
    let ajaxContainer = document.getElementById('' + ajaxContainerID + '');
    let ajaxUrl = link.dataset.url;
    let loader = '<div class="position-absolute w-100 h-100 z3-index" style="top:0;left:0;background: #3c3c3c8c;">' +
        '<div class="position-absolute" style="top:50%;left:50%;transform:translate(-50%,-50%);color:#333;font-size:2rem">' +
        '<div class="loader">\n' +
        '            <div></div>\n' +
        '            <div></div>\n' +
        '        </div>' +
        '</div>' +
        '</div>';

    ajaxContainer.insertAdjacentHTML('beforeend', loader);

    fetch(ajaxUrl, {
        method: "GET",
        headers: headers,
    })
        .then(
            response => response.text()
        )
        .then(html => {
            ajaxContainer.innerHTML = html;
            /*ajaxContainer.scrollIntoView({
                behavior: 'smooth',
                block: "start"
            });*/
            window.scrollTo({top: (ajaxContainer.offsetTop - document.getElementById('site-header').offsetHeight - 30), behavior: 
                'smooth'});
        })
        .catch(function (error) {
            console.log(error);
        });
}




