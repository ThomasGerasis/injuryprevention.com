import "../scss/footer.scss";

const toggleList = () => {
  const headingWrapperList = document.querySelectorAll(
    "[data-toggle_footer_list]"
  );

  headingWrapperList.forEach((wrapper) => {
    wrapper.addEventListener("click", (_) => {
      const ul = wrapper.nextElementSibling;
      ul.classList.toggle("d-none");
    });
  });
};

const main = () => {
  toggleList();
};

main();
