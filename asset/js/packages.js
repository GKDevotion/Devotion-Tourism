   const container = document.getElementById("package-container");
    const params = new URLSearchParams(window.location.search);
    const categoryId = params.get("category");

    fetch("asset/json/packages.json")
      .then(res => res.json())
      .then(data => {

        // Find the matching category
        const category = data.categories.find(cat => cat.id === categoryId);

        // If category not found
        if (!category) {
          container.innerHTML = `
        <div class="col-12">
         
          <div class="alert alert-warning text-center">
            Category not found.
            
          </div>
        </div>`;
          return;
        }

        let hasPackages = false;

        // Loop through children regions
        category.children.forEach(region => {
          if (region.packages && region.packages.length > 0) {
            region.packages.forEach(pkg => {
              hasPackages = true;

              // Use default values for missing fields
              const departureFrom = pkg.departureFrom || "Multiple cities";
              const startDate = pkg.travelDates?.start || "Flexible";
              const endDate = pkg.travelDates?.end || "Flexible";
              const priceNote = pkg.priceNote || "per person";

              container.innerHTML += `
           <div class="col-lg-4 col-md-6 mb-4">
      
            <div class="card tour-card h-100 border-0 shadow-sm">

              <!-- Image -->
              <a href="package-details.html?id=${pkg.id}">
              <div class="tour-img-wrapper">
                <img src="${pkg.bannerImage}" class="card-img-top" alt="${pkg.title}">
                <span class="tour-duration">${pkg.duration}</span>
              </div>
              </a>

              <!-- Body -->
              <div class="card-body d-flex flex-column">

                <h5 class="card-title fw-semibold mb-2">
                  ${pkg.title}
                </h5>

                <p class="text-muted small mb-2">
                  <i class="bi bi-geo-alt me-1"></i> ${region.name}
                </p>

                <ul class="list-unstyled small mb-3 tour-info">
                  <li>
                    <i class="bi bi-airplane me-1"></i>
                    <strong>Departure:</strong> ${departureFrom}
                  </li>
                  <li>
                    <i class="bi bi-calendar-event me-1"></i>
                    <strong>Dates:</strong> ${startDate} – ${endDate}
                  </li>
                </ul>

                <!-- Price -->
                <div class="mt-auto">
                  <div class="price-box mb-3">
                    <span class="price">
                      ${pkg.currency} ${pkg.startingPrice.toLocaleString()}
                    </span>
                    <span class="price-note">${priceNote}</span>
                  </div>

              
                </div>
              
              </div>
            </div>
          </div>

          `;
            });
          }
        });

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