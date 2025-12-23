  // RENDER UI
        const container = document.getElementById("visa-container");
        const alphabetContainer = document.querySelector(".alphabet-filter");

        fetch("asset/json/GlobalVisa.json")
            .then(res => res.json())
            .then(json => {
                json.response.forEach(country => {
                    // determine first letter; fallback to '#'
                    const firstChar = (country.countryName || "").trim().charAt(0).toUpperCase();
                    const letter = /[A-Z]/.test(firstChar) ? firstChar : "#";

                    country.visaInfo.forEach(visa => {

                        const flagsHtml = (visa.flags || []).map((f, index) => `
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-${visa._id}-${index}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#collapse-${visa._id}-${index}" aria-expanded="false" 
                            aria-controls="collapse-${visa._id}-${index}">
                        ${f.serviceType}
                    </button>
                    </h2>
                    <div id="collapse-${visa._id}-${index}" class="accordion-collapse collapse" 
                        aria-labelledby="heading-${visa._id}-${index}" data-bs-parent="#accordion-${visa._id}">
                    <div class="accordion-body">
                        ${f.serviceDetails}
                    </div>
                    </div>
                </div>
                `).join("");

                        const accordionWrapper = `
                <div class="accordion" id="accordion-${visa._id}">
                ${flagsHtml}
                </div>
                `;


                        const additionalHtml = (visa.addtionalService || []).map((a, idx) => `
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-add-${visa._id}-${idx}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-add-${visa._id}-${idx}" aria-expanded="false" aria-controls="collapse-add-${visa._id}-${idx}">
                        ${a.serviceName}
                    </button>
                    </h2>
                    <div id="collapse-add-${visa._id}-${idx}" class="accordion-collapse collapse" aria-labelledby="heading-add-${visa._id}-${idx}" data-bs-parent="#additionalAccordion-${visa._id}">
                    <div class="accordion-body">
                        ${a.serviceDescription}
                    </div>
                    </div>
                </div>
                `).join('');


                        const showReadMore = (visa.description || "").length > 10;
                        const encodedDesc = encodeURIComponent(visa.description || "");
                        const encodedDocu = encodeURIComponent(visa.reqDocuments || "");
                        const encodedNote = encodeURIComponent(visa.reqNotes || "");

                        container.innerHTML += `
              
                <div class="col-lg-6 col-md-6 mb-4 global-visa-card" data-letter="${letter}">
                <div class="card mb-4 shadow-sm h-100">
                  <div class="card-body">
                    <h5 class="card-title">${country.countryName}</h5>
                    <h6 class="text-secondary">${visa.title}</h6>

                    <p class="mt-2"><strong>Processing Days:</strong> ${country.processDaysStart}-${country.processDaysEnd} Days</p>

                    <!-- clamped description -->
                    <p class="small clamp-text description-text">
                    ${visa.description || ''}
                    </p>

                    ${showReadMore ? `
                    <button class="btn btn-link text-decoration-none border-0 shadow-none p-0 read-more"
                    data-title="${encodeURIComponent(country.countryName + ' - ' + visa.title)}"
                            data-desc="${encodedDesc}" data-docu="${encodedDocu}" data-notes="${encodedNote}">
                    Read More
                    </button>` : ''}

                    <h6 class="mt-3">Visa Details</h6>
                    <ul class="list-group mb-3">
                      ${accordionWrapper}
                    </ul>

                    <h6 class="mt-3">Additional Services</h6>
                    <div class="accordion" id="additionalAccordion-${visa._id}">
                    ${additionalHtml}
                    </div>

                    <div class="d-flex justify-content-between p-2 align-items-center">
                      <h5 class="visa-price mb-0">AED ${visa.price}</h5>
                    </div>
                  </div>
                </div>
         
              </div>`;
                    });
                });
                // after rendering, ensure default filter state (show ALL)
                applyAlphabetFilter("A"); // Default filter = A

            })
            .catch(err => {
                console.error("Failed to load JSON:", err);
                container.innerHTML = `<div class="col-12"><div class="alert alert-danger">Failed to load data.</div></div>`;
            });

        // ---------- Alphabet filter logic ----------
        function applyAlphabetFilter(letter) {
            // normalize
            const normalized = (letter || "ALL").toString().toUpperCase();
            // toggle button active class
            document.querySelectorAll(".alphabet-filter button").forEach(btn => {
                btn.classList.toggle("active", btn.dataset.letter === normalized);
            });

            const cards = document.querySelectorAll(".global-visa-card");
            cards.forEach(card => {
                const cardLetter = (card.dataset.letter || "").toUpperCase();
                if (normalized === "ALL") {
                    card.style.display = ""; // restore default (Bootstrap col will show)
                } else {
                    card.style.display = (cardLetter === normalized) ? "" : "none";
                }
            });
        }

        // attach click handlers to alphabet buttons (use event delegation)
        alphabetContainer.addEventListener("click", function (e) {
            const btn = e.target.closest("button[data-letter]");
            if (!btn) return;
            const letter = btn.dataset.letter;
            applyAlphabetFilter(letter);
            // optional: scroll to cards area when a letter is clicked
            // document.getElementById('visa-container').scrollIntoView({ behavior: 'smooth' });
        });

        // keyboard accessibility: allow Enter on focused button
        alphabetContainer.addEventListener("keydown", function (e) {
            if ((e.key === "Enter" || e.key === " ") && document.activeElement.dataset?.letter) {
                e.preventDefault();
                applyAlphabetFilter(document.activeElement.dataset.letter);
            }
        });

        // Use Bootstrap 5's Modal API (not jQuery .modal)
        $(document).on("click", ".read-more", function () {
            // decode safely
            const title = decodeURIComponent($(this).data("title") || "");
            const desc = decodeURIComponent($(this).data("desc") || "");
            const docu = decodeURIComponent($(this).data("docu") || "");
            const notes = decodeURIComponent($(this).data("notes") || "");
            // fill modal
            document.getElementById("descModalTitle").textContent = title;
            // allow HTML if you want formatting: use innerHTML carefully
            document.getElementById("descModalBody").innerHTML = desc.replace(/\n/g, "<br>");
            document.getElementById("docuModalBody").innerHTML = docu.replace(/\n/g, "<br>");
            document.getElementById("notesModalBody").innerHTML = notes.replace(/\n/g, "<br>");
            // show modal using Bootstrap 5
            const modalEl = document.getElementById('descModal');
            const bsModal = new bootstrap.Modal(modalEl);
            bsModal.show();
        });

        $(document).ready(function () {
            $('#descModal').on('show.bs.modal', function () {
                $('nav.navbar, footer').fadeOut(200);
            });

            $('#descModal').on('hide.bs.modal', function () {
                $('nav.navbar, footer').fadeIn(200);
            });
        });
