var swiper = new Swiper(".myVisaSwiper", {
  loop: true,
  autoplay: {
    delay: 5000,
    disableOnInteraction: false,
  },
  slidesPerView: 3,
  spaceBetween: 24,

  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },

  breakpoints: {
    0: { slidesPerView: 1 },
    768: { slidesPerView: 2 },
    992: { slidesPerView: 3 },
  },
});

document.addEventListener("scroll", function () {
  const sticky = document.querySelector(".sticky-social");

  if (window.scrollY > 1000) {
    // 50px scroll
    sticky.classList.add("active");
  } else {
    sticky.classList.remove("active");
  }
});

const placeSwiper = new Swiper('.placeSwiper', {
    // Navigation arrows
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    
    // Core settings for mobile-first
    slidesPerView: 1,       // Shows 1 full image on small screens
    spaceBetween: 10,      // Small gap between slides
    centeredSlides: true,  // Keeps the active image in the middle
    loop: true,

    // Responsive breakpoints
    breakpoints: {
        // When window width is >= 768px (Tablets/Desktop)
        768: {
            slidesPerView: 3,
            spaceBetween: 20,
            centeredSlides: false,
        }
    }
});

 
let popupSwiper;

// Open popup on image click
document.querySelectorAll('.place-card').forEach(card => {
    card.addEventListener('click', () => {
        const index = card.getAttribute('data-index');
        document.getElementById('imagePopup').classList.add('active');

        popupSwiper = new Swiper(".popupSwiper", {
            initialSlide: index,
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            }
        });
    });
});

// Close popup
document.querySelector('.popup-close').addEventListener('click', () => {
    document.getElementById('imagePopup').classList.remove('active');
    popupSwiper.destroy(true, true);
});
 
