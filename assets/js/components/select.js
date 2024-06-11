export function fireSelects() {
    let selectContainer = document.querySelectorAll(".select-container");
    selectContainer.forEach((element) => {
        let select = element.querySelector(".select");
        let input = element.querySelector(".select-input");
        let options = element.querySelectorAll(".option");

        select.onclick = () => {
            element.classList.toggle("active");
        };

        options.forEach((option) => {
            if (option.classList.contains("selected")) {
                input.value = option.innerText;
                element.classList.remove("active");
            }

            option.addEventListener("click", () => {

                input.value = option.innerText;
                input.dataset.itemValue = option.dataset.value;
                element.classList.remove("active");

                options.forEach((e) => {
                    e.classList.remove("selected");
                });
                option.classList.add("selected");
            });
        });
    });
}
