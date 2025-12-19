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
                992: { slidesPerView: 3 }
            }
        });

        document.addEventListener("scroll", function () {
            const sticky = document.querySelector(".sticky-social");

            if (window.scrollY > 1000) { // 50px scroll
                sticky.classList.add("active");
            } else {
                sticky.classList.remove("active");
            }
        });
