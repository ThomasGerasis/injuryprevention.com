export function tabsHandler(tabButtons,tabContent) {
    tabButtons.forEach((element) => {
        element.addEventListener("click", () => {
            tabButtons.forEach((e) => {
                e.classList.remove("active");
            });
            let activeTab = element.dataset.tab;
            element.classList.add("active");
            Array.from(tabContent).forEach((content) => {
                const activeContent = content.dataset.status;
                if (activeContent === activeTab) {
                    content.classList.add("active")
                } else {
                    content.classList.remove("active")
                }
            });

        });
    });
}
