 fetch("asset/json/packages.json")
       .then((res) => res.json())
       .then((data) => {
         const wrapper = document.getElementById("corporatePackageWrapper");

         const corporateCategory = data.categories.find(
           (cat) => cat.id === "corporate-tours"
         );

         if (!corporateCategory) {
           wrapper.innerHTML = `<p class="text-muted">No corporate category found</p>`;
           return;
         }

         // ✅ Collect ALL packages from child categories
         let allPackages = [];

         corporateCategory.children.forEach((child) => {
           if (child.packages && child.packages.length) {
             allPackages.push(...child.packages);
           }
         });

         if (!allPackages.length) {
           wrapper.innerHTML = `<p class="text-muted">No corporate packages found</p>`;
           return;
         }

         // ✅ Render packages
         allPackages.forEach((pkg) => {
           wrapper.innerHTML += `
            <div class="swiper-slide">
        <div class="package-card mt-3 h-100 shadow-sm">
            
            <div>
            <!-- Image -->
            <a href="package-details.php?id=${pkg.id
                    }" class="position-relative d-block">
            <img
                src="${pkg.bannerImage}"
                class="w-100 package-img"
                alt="${pkg.title}"
            />

            <!-- Duration Badge -->
            <span class="badge duration-badge">
                ${pkg.duration}
            </span>
            </a>
            </div>

            <!-- Content -->
            <div class="p-3">
            <h6 class="fw-semibold mb-1">${pkg.title}</h6>
        
            <div class="fw-bold text-dark">
                AED ${pkg.startingPrice.toLocaleString()}
                <span class="fw-normal text-muted small">per person</span>
            </div>
            </div>

        </div>
        </div>
         `;
         });

         // ✅ Swiper init
         new Swiper(".corporateSwiper", {
           slidesPerView: 3,
           spaceBetween: 20,
           loop: true, // 🔁 required for smooth auto sliding

           autoplay: {
             delay: 3000, // ⏱️ 3 seconds
             disableOnInteraction: false, // keeps autoplay after arrow click
             pauseOnMouseEnter: true, // ⏸️ pause on hover
           },
           loop: true,
           navigation: {
             nextEl: ".swiper-button-next",
             prevEl: ".swiper-button-prev",
           },
           breakpoints: {
             0: {
               slidesPerView: 1
             },
             576: {
               slidesPerView: 2
             },
             992: {
               slidesPerView: 3
             },
           },
         });
       })
       .catch((err) => console.error("JSON Load Error:", err));