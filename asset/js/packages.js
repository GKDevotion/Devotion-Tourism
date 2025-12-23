  //  const container = document.getElementById("package-container");
  //   const params = new URLSearchParams(window.location.search);
  //   const categoryId = params.get("category");

  //   fetch("asset/json/packages.json")
  //     .then(res => res.json())
  //     .then(data => {

  //       // Find the matching category
  //       const category = data.categories.find(cat => cat.id === categoryId);

  //       // If category not found
  //       if (!category) {
  //         container.innerHTML = `
  //       <div class="col-12">
         
  //         <div class="alert alert-warning text-center">
  //           Category not found.
            
  //         </div>
  //       </div>`;
  //         return;
  //       }

  //       let hasPackages = false;

  //       // Loop through children regions
  //       category.children.forEach(region => {
  //         if (region.packages && region.packages.length > 0) {
  //           region.packages.forEach(pkg => {
  //             hasPackages = true;

  //             // Use default values for missing fields
  //             const departureFrom = pkg.departureFrom || "Multiple cities";
  //             const startDate = pkg.travelDates?.start || "Flexible";
  //             const endDate = pkg.travelDates?.end || "Flexible";
  //             const priceNote = pkg.priceNote || "per person";

  //             container.innerHTML += `
  //          <div class="col-lg-4 col-md-6 mb-4">
      
  //           <div class="card tour-card h-100 border-0 shadow-sm">

  //             <!-- Image -->
  //             <a href="package-details.html?id=${pkg.id}">
  //             <div class="tour-img-wrapper">
  //               <img src="${pkg.bannerImage}" class="card-img-top" alt="${pkg.title}">
  //               <span class="tour-duration">${pkg.duration}</span>
  //             </div>
  //             </a>

  //             <!-- Body -->
  //             <div class="card-body d-flex flex-column">

  //               <h5 class="card-title fw-semibold mb-2">
  //                 ${pkg.title}
  //               </h5>

  //               <p class="text-muted small mb-2">
  //                 <i class="bi bi-geo-alt me-1"></i> ${region.name}
  //               </p>

  //               <ul class="list-unstyled small mb-3 tour-info">
  //                 <li>
  //                   <i class="bi bi-airplane me-1"></i>
  //                   <strong>Departure:</strong> ${departureFrom}
  //                 </li>
  //                 <li>
  //                   <i class="bi bi-calendar-event me-1"></i>
  //                   <strong>Dates:</strong> ${startDate} – ${endDate}
  //                 </li>
  //               </ul>

  //               <!-- Price -->
  //               <div class="mt-auto">
  //                 <div class="price-box mb-3">
  //                   <span class="price">
  //                     ${pkg.currency} ${pkg.startingPrice.toLocaleString()}
  //                   </span>
  //                   <span class="price-note">${priceNote}</span>
  //                 </div>

              
  //               </div>
              
  //             </div>
  //           </div>
  //         </div>

  //         `;
  //           });
  //         }
  //       });

  //       if (!hasPackages) {
  //         container.innerHTML = `
  //       <div class="col-12">
  //         <div class="alert alert-warning text-center">
  //           No packages available in this category.
  //         </div>
  //       </div>`;
  //       }
  //     })
  //     .catch(err => {
  //       console.error(err);
  //       container.innerHTML = `
  //     <div class="col-12">
  //       <div class="alert alert-danger text-center">
  //         Failed to load packages.
  //       </div>
  //     </div>`;
  //     });


  const container = document.getElementById("package-container");
  const categoryTitle = document.getElementById("category-name"); // ✅ NEW
const params = new URLSearchParams(window.location.search);
const categoryId = params.get("category");

fetch("asset/json/packages.json")
  .then(res => res.json())
  .then(data => {

    let regionsToShow = [];
    let hasPackages = false; // ✅ track package availability
    let categoryName = ""; // ✅ NEW

    // 🔹 Case 1: Parent category
    const parentCategory = data.categories.find(
      cat => cat.id === categoryId
    );

    if (parentCategory) {
      regionsToShow = parentCategory.children || [];
      categoryName = parentCategory.name; // ✅ SET TITLE
    } else {
      // 🔹 Case 2: Child category
      data.categories.forEach(cat => {
        const child = cat.children?.find(
          c => c.id === categoryId
        );
         if (child) {
          regionsToShow.push(child);
          categoryName = child.name; // ✅ SET TITLE
        }
      });
    }

    // ❌ Category not found
    if (!regionsToShow.length) {
       categoryTitle.textContent = "Tour"; // fallback title
      container.innerHTML = `
      
        <div class="col-12">
          <div class="alert alert-warning text-center">
            Category not found
          </div>
        </div>`;
      return;
    }
    // ✅ Update heading text
    categoryTitle.textContent = categoryName;
    container.innerHTML = ""; // clear before rendering

    // 🔹 Render packages
    regionsToShow.forEach(region => {
      if (region.packages && region.packages.length) {
        hasPackages = true;

        region.packages.forEach(pkg => {
          container.innerHTML += `
            <div class="col-lg-4 col-md-6 mb-4">
              <div class="card tour-card h-100 shadow-sm border-0">
                <a href="package-details.html?id=${pkg.id}">
                  <div class="tour-img-wrapper">
                    <img src="${pkg.bannerImage}" class="card-img-top" alt="${pkg.title}">
                    <span class="tour-duration">${pkg.duration}</span>
                  </div>
                </a>

                <div class="card-body">
                  <a href="package-details.html?id=${pkg.id}" class="text-decoration-none text-dark">
                  <h5>${pkg.title}</h5>
                  </a>
                  <p class="text-muted small">
                    <i class="bi bi-geo-alt"></i> ${region.name}
                  </p>
                  <strong>${pkg.currency} ${pkg.startingPrice.toLocaleString()}</strong>
                  <small class="text-muted"> per person</small>
                </div>
              </div>
            </div>`;
        });
      }
    });

    // ❌ No packages found
    if (!hasPackages) {
      container.innerHTML = `
        <div class="col-12">
          <div class="alert alert-warning text-center">
            No packages available in this category.
          </div>
        </div>`;
    }
  })
  .catch(err => {
    console.error(err);
    container.innerHTML = `
      <div class="col-12">
        <div class="alert alert-danger text-center">
          Failed to load packages.
        </div>
      </div>`;
  });
