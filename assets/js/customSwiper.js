import "../scss/customSwiper.scss";

import Swiper from 'swiper/bundle';

const breakpointsType1 = {
    320: {
        slidesPerView: 1,
        spaceBetween: 0
    },
    576: {
        slidesPerView: 1,
        spaceBetween: 0
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

// const breakpointsType5 = {
//     320: {
//         slidesPerView: 1,
//         spaceBetween: 0
//     },
//     576: {
//         slidesPerView: 2,
//         spaceBetween: 0
//     },
//     992: {
//         slidesPerView: 2,
//         spaceBetween: 0
//     },
//     1400: {
//         slidesPerView: 3,
//         spaceBetween: 0
//     }
// };


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
    "type3": breakpointsType3
};

const spaceForTypes = {"type1": 20, "type2": 20, "type3": 0};

export function setUpSliders(selector) {
    const articleSliders = document.querySelectorAll(selector);
    for (let i = 0; i < articleSliders.length; i++) {
        let parent = document.getElementById(articleSliders[i].id);
        const prefix = parent.getAttribute('data-prefix');
        const breakpointType = parent.getAttribute('data-breakpoint');
        parent.querySelector('.' + prefix + '-swiper').classList.add(prefix + '-swiper-' + i);
        // parent.querySelector('.swiper-button-next').classList.add(prefix+'-swiper-button-next-' + i);
        // parent.querySelector('.swiper-button-prev').classList.add(prefix+'-swiper-button-prev-' + i);

        let activeMatch = localStorage.getItem('activeMatch');
        let activeSlide = activeMatch ? Number(activeMatch) : 0;

        let swiper = new Swiper('.' + prefix + '-swiper-' + i, {
            loop: false,
            speed: 400,
            observer: true,
            observeParents: true,
            paginationClickable: true,
            centeredSlides: parent.classList.contains('timeline-swiper-container') || activeSlide !== 0,
            spaceBetween: breakpointsTypes[breakpointType],
            slidesPerView: 1,
            pagination: {
                el: ".swiper-pagination",
                dynamicBullets: true,
            },
            breakpoints: breakpointsTypes[breakpointType]
        });


        swiper.slideTo(activeSlide ?? 0); // Scroll to the third slide
    }
}

setUpSliders('.swiper-container');