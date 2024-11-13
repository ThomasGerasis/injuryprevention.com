import "../scss/customSwiper.scss";

import Swiper from 'swiper/bundle';

const breakpointsType1 = {
    320: {
        slidesPerView: 3,
        spaceBetween: 0,
        spaceBetween: 0,
        centeredSlides: true,     // Centers the active slide
        loop: true,               // Enables looping
        slideToClickedSlide: true // Allows sliding to the clicked slide
    },
    576: {
        slidesPerView: 3,
        spaceBetween: 0,
        spaceBetween: 0,
        centeredSlides: true,     // Centers the active slide
        loop: true,               // Enables looping
        slideToClickedSlide: true // Allows sliding to the clicked slide
    },
    992: {
        slidesPerView: 4,
        spaceBetween: 0
    },
    1400: {
        slidesPerView: 5,
        spaceBetween: 0
    }
};

const breakpointsType5 = {
    320: {
        slidesPerView: 1,
        spaceBetween: 0
    },
    576: {
        slidesPerView: 1,
        spaceBetween: 0
    },
    992: {
        slidesPerView: 1,
        spaceBetween: 0
    },
    1400: {
        slidesPerView: 1,
        spaceBetween: 0
    }
};


const breakpointsType2 = {
    320: {
        slidesPerView: 2,
        spaceBetween: 20
    },
    576: {
        slidesPerView: 3,
        spaceBetween: 40
    },
    992: {
        slidesPerView: 4,
        spaceBetween: 25
    },
    1400: {
        slidesPerView: 5,
        spaceBetween: 25
    }
};
const breakpointsType3 = {
    320: {
        slidesPerView: 1,
        spaceBetween: 0
    }
};

const breakpointsTypes = {
    "type1": breakpointsType1,
    "type2": breakpointsType2,
    "type3": breakpointsType3,
    "type5": breakpointsType5
};

const spaceForTypes = {"type1": 20, "type2": 20, "type3": 0};

export function setUpSliders(selector) 
{
    const articleSliders = document.querySelectorAll(selector);
    for (let i = 0; i < articleSliders.length; i++) {
        let parent = document.getElementById(articleSliders[i].id);
        const prefix = parent.getAttribute('data-prefix');
        const breakpointType = parent.getAttribute('data-breakpoint');
        parent.querySelector('.' + prefix + '-swiper').classList.add(prefix + '-swiper-' + i);
        // parent.querySelector('.swiper-button-next').classList.add(prefix+'-swiper-button-next-' + i);
        // parent.querySelector('.swiper-button-prev').classList.add(prefix+'-swiper-button-prev-' + i);

        let activeMatch = localStorage.getItem('activeMatch');
        let activeMonth = localStorage.getItem('activeMonth');
        let activeYear = localStorage.getItem('activeYear');

        let activeSlide = activeMatch ? Number(activeMatch) : 0;

        let swiper = new Swiper('.' + prefix + '-swiper-' + i, {
            loop: false,
            speed: 400,
            observer: true,
            observeParents: true,
            paginationClickable: true,
            centeredSlides: parent.classList.contains('timeline-swiper-container') && activeSlide !== 0,
            spaceBetween: breakpointsTypes[breakpointType],
            slidesPerView: 1,
            pagination: {
                el: ".swiper-pagination",
                dynamicBullets: true,
            },
            breakpoints: breakpointsTypes[breakpointType]
        });

        swiper.slideTo(activeSlide ?? 0); // Scroll to the third slide

        if(parent.classList.contains('timeline-swiper-container')){
            dateControls(swiper);
        }

    }

}

setUpSliders('.swiper-container');


function dateControls(slider)
{
    let activeMonth = localStorage.getItem('activeMonth') ?? 10;
    let activeYear = localStorage.getItem('activeYear');    
    let monthControls = document.querySelectorAll('.month-controls');

    activeYear = activeYear ? Number(activeYear) : 2017;
    activeMonth = activeMonth ? Number(activeMonth) : 10;

    let slideToGo = 0;

    monthControls.forEach(element => {
        element.addEventListener('click', event => {
            if (element.classList.contains('next')) {
                activeMonth++;
                if (activeMonth > 12) {
                    activeYear++;
                    activeMonth = 1;
                }
            } else { // Previous button clicked
                activeMonth--;
                if (activeMonth < 1) {
                    activeYear--;
                    activeMonth = 12;
                }
            }

    
            // Check for slide existence and adjust if necessary
            let slideFound = document.querySelector(`.timeline-slide[data-month="${activeMonth}"][data-year="${activeYear}"]`);
            if (!slideFound) {
                // Find next available slide
                while (!slideFound) {
                    if (element.classList.contains('next')) {
                        activeMonth++;
                        if (activeMonth > 12) {
                            activeYear++;
                            if (activeYear > 2018) {
                                activeYear = 2017; // Loop back to the first year
                            }
                            activeMonth = 1;
                        }
                    } else {
                        activeMonth--;
                        if (activeMonth < 1) {
                            activeYear--;
                            if (activeYear < 2017) {
                                activeYear = 2018; // Loop back to the last year
                            }
                            activeMonth = 12;
                        }
                    }
                    slideFound = document.querySelector(`.timeline-slide[data-month="${activeMonth}"][data-year="${activeYear}"]`);
                }
            }
    
            // Get the slide number
            slideToGo = slideFound.dataset.slide;
    
            // Save updated active month and year in localStorage
            localStorage.setItem('activeMonth', activeMonth);
            localStorage.setItem('activeYear', activeYear);

            slider.slideTo(slideToGo);
        });
    });
}