export function dropdownElement(el) {
    let subMenu = el.nextElementSibling;
    while (subMenu) {
        if (subMenu.classList.contains('dropdown-menu')) {
            break;
        }
        subMenu = subMenu.nextElementSibling;
    }
    if (!subMenu) return;

    let open = false;
    if (subMenu.style.display !== 'block') {
        subMenu.style.display = 'block';
        open = true;
    } else {
        subMenu.style.display = 'none';
        open = false;
    }
    el.setAttribute("aria-expanded", open);
    
    document.addEventListener("click", (e) => {
        if(open == false) {
            return;
        }
        if ( e.target.id === 'g_id_signin') {
            return;
        }
        subMenu.style.display = 'none';
        open = false;
    });
}


export function toggleParent(el) {
    el.stopPropagation();

    let btn = this;
    let subMenu = btn.parentElement;
    while (subMenu && subMenu.nodeType !== 1) {
        subMenu = subMenu.parentElement
    }
    if (!subMenu) return;

    let open = false;
    if (subMenu.style.display !== 'block') {
        subMenu.style.display = 'block';
        subMenu.classList.add("open");
        open = true;
    } else {
        subMenu.style.display = 'none';
        subMenu.classList.remove("open");
        open = false;
    }

    btn.setAttribute("aria-expanded", open);

    btn.addEventListener("focusout", (e) => {
        if (subMenu.contains(e.relatedTarget)) return;
        subMenu.style.display = 'none';
        subMenu.classList.remove("open");
        open = false;
    });
}
