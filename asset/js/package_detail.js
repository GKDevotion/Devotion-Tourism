  const container = document.getElementById("package-container");
      const pageTitle = document.getElementById("page-title");

      const params = new URLSearchParams(window.location.search);
      const packageId = params.get("id");

      if (!packageId) {
        pageTitle.innerText = "Package Not Found";
        container.innerHTML = "<p>Invalid package URL</p>";
        throw new Error("No package id");
      }

      fetch("asset/json/packages.json")
        .then((res) => res.json())
        .then((data) => {
          let foundPackage = null;

          // 🔍 Search package in all categories & children
          data.categories.forEach((category) => {
            category.children?.forEach((child) => {
              child.packages?.forEach((pkg) => {
                if (pkg.id === packageId) {
                  foundPackage = pkg;
                }
              });
            });
          });

          if (!foundPackage) {
            pageTitle.innerText = "Package Not Found";
            container.innerHTML = "<p>No package data available.</p>";
            return;
          }
          pageTitle.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div>
                <span class="fw-bold text-dark mb-2 d-block">
                    ${foundPackage.title}
                </span>
                <p class="text-dark mb-0" style="font-size: 24px;">
                    ${foundPackage.duration || ""}
                </p>
                </div>
                
                <div class="d-flex gap-2 mt-1">
                <button class="btn btn-sm" style="background-color: #ab823e; color: white;">
                    <i class="bi bi-heart"></i>
                </button>
                <button class="btn btn-sm" style="background-color: #ab823e; color: white;">
                    <i class="bi bi-share"></i>
                </button>
                <button class="btn btn-sm p-2" style="background-color: #ab823e; color: white;">
                    Book Now
                </button>
                </div>
            </div>
            `;

          container.innerHTML = `
            <div class="col-12">
                
            <div class="card border-0 shadow-sm">
            <img src="${
              foundPackage.bannerImage
            }" class="card-img-top rounded" alt="${foundPackage.title}" style="    max-height: 490px;
    object-fit: fill;">
            </div>

            <div class="card-body">

            ${
              foundPackage
                ? `
    <div class="border-bottom py-3 mb-4">
      <div class="row g-4">

        <!-- Tour Code -->
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="d-flex align-items-start gap-3">
            <i class="bi bi-clock fs-4 " style="color:#ab823e;"></i>
            <div>
              <small class="text-muted d-block">Tour code:</small>
              <strong>${foundPackage.tourid}</strong>
            </div>
          </div>
        </div>

        <!-- Length -->
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="d-flex align-items-start gap-3">
            <i class="bi bi-people fs-4" style="color:#ab823e;"></i>
            <div>
              <small class="text-muted d-block">Duration:</small>
              <strong>${foundPackage.duration}</strong>
            </div>
          </div>
        </div>

        <!-- Start From -->
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="d-flex align-items-start gap-3">
            <i class="bi bi-geo-alt fs-4" style="color:#ab823e;"></i>
            <div>
              <small class="text-muted d-block">Start From</small>
              <strong>AED ${foundPackage.startingPrice?.toLocaleString()} </strong>
            </div>
          </div>
        </div>

        <!-- Tour Type -->
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="d-flex align-items-start gap-3">
            <i class="bi bi-slash-circle fs-4" style="color:#ab823e;"></i>
            <div>
              <small class="text-muted d-block">Tour type</small>
              <strong>${foundPackage.tourType}</strong>
            </div>
          </div>
        </div>

      </div>
    </div>
    `
                : ""
            }

              ${
                foundPackage.highlights
                  ? `
                <h5 class="mt-4">Highlights</h5>
                <ul class="row list-unstyled">
                  ${foundPackage.highlights
                    .map(
                      (h) => `
                      <li class="col-lg-3 col-md-4 col-sm-6 d-flex align-items-start mb-3">
                        <img 
                          src="asset/image/star.gif"
                          alt="highlight" 
                          width="18" 
                          height="18" 
                          class="me-2 mt-1"
                        />
                        <span>${h}</span>
                      </li>
                    `
                    )
                    .join("")}
                </ul>
              `
                  : ""
              }

            ${
              foundPackage.description
                ? `
              <p class="mt-3">${foundPackage.description}</p>
            `
                : ""
            }

            ${
              foundPackage.itinerary && foundPackage.itinerary.length
                ? `
    <h5 class="mt-4">Itinerary</h5>

    <div class="accordion" id="itineraryAccordion">
      ${foundPackage.itinerary
        .map(
          (item, index) => `
          <div class="accordion-item mb-2 border rounded-3">
            <h2 class="accordion-header" id="heading${index}">
              <button 
                class="fs-5 accordion-button ${index !== 0 ? "collapsed" : ""}" 
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapse${index}"
                aria-expanded="${index === 0}"
                aria-controls="collapse${index}"
              >
                <strong class="me-2">Day ${item.day}</strong>${item.title}
              </button>
            </h2>

            <div 
              id="collapse${index}" 
              class="accordion-collapse collapse ${index === 0 ? "show" : ""}"
              aria-labelledby="heading${index}"
              data-bs-parent="#itineraryAccordion"
            >
              <div class="accordion-body">
                ${item.description}
              </div>
            </div>
          </div>
        `
        )
        .join("")}
    </div>
    `
                : ""
            }

          ${
            foundPackage.inclusions?.length || foundPackage.exclusions?.length
              ? `
          <h5 class="mt-4">Inclusions & Exclusions</h5>

          <div class="row mt-3">
            
            <!-- Inclusions -->
            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
              <div class="border rounded-4 p-3 h-100">
                <h6 class="text-success mb-3">✔ Inclusions</h6>
                <ul class="list-unstyled mb-0">
                  ${
                    foundPackage.inclusions
                      ? foundPackage.inclusions
                          .map(
                            (item) => `
                            <li class="d-flex align-items-start mb-2">
                              <img src="asset/image/star.gif" width="16" class="me-2 mt-1">
                              <span>${item}</span>
                            </li>
                          `
                          )
                          .join("")
                      : "<li>No inclusions available</li>"
                  }
          </ul>
        </div>
      </div>

      <!-- Exclusions -->
      <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
        <div class="border rounded-4 p-3 h-100">
          <h6 class="text-danger mb-3">✖ Exclusions</h6>
          <ul class="list-unstyled mb-0">
            ${
              foundPackage.exclusions
                ? foundPackage.exclusions
                    .map(
                      (item) => `
                      <li class="d-flex align-items-start mb-2">
                        <img src="asset/image/star.gif" width="16" class="me-2 mt-1">
                        <span>${item}</span>
                      </li>
                    `
                    )
                    .join("")
                : "<li>No exclusions available</li>"
            }
          </ul>
        </div>
      </div>

    </div>
    `
              : ""
          }

          ${
            foundPackage.terms
              ? `
    <h5 class="mt-4">Terms & Conditions</h5>
    <div class="terms-wrapper">
      ${foundPackage.terms
        .map(
          (term) => `
          <div class="term-card">
            <div class="term-title">${term.title}</div>
            <div class="term-body">
              <ul>
                ${term.points.map((p) => `<li>${p}</li>`).join("")}
              </ul>
            </div>
          </div>
        `
        )
        .join("")}
    </div>
  `
              : ""
          }


            </div>
        </div>
        `;
        })
        .catch((err) => {
          console.error(err);
          container.innerHTML = "<p>Error loading package</p>";
        });