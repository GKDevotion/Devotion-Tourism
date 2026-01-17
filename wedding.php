 <?php
  $pageTitle = "Devotion Tourism - Destination Wedding";
  include 'includes/head.php';
  ?>


 <style>
   .wedding-services h2 {
     font-size: 32px;
   }

   .service-card {
     text-align: center;
   }

   .service-card img {
     width: 100%;
     height: 260px;
     object-fit: cover;
     border-radius: 6px;
     transition: transform 0.4s ease;
   }

   .service-card h6 {
     margin-top: 12px;
     font-size: 16px;
     font-weight: 500;
   }

   /* Hover effect */
   .service-card:hover img {
     transform: scale(1.05);
   }

   /* Controls wrapper */
   .swiper-controls {
     display: flex;
     align-items: center;
     justify-content: center;
     gap: 24px;
     margin-top: 40px;
   }

   /* Progress bar container */
   .weddingSwiper .swiper-pagination {
     position: relative;
     width: 260px;
     height: 3px;
     background: #e6e6e6;
     border-radius: 10px;
     overflow: hidden;
     margin-bottom: 20px;
   }

   /* Progress fill */
   .weddingSwiper .swiper-pagination-progressbar-fill {
     background: #ab823e;
     height: 100%;
     border-radius: 10px;
   }

   /* Arrow buttons */
   .weddingSwiper .swiper-button-next,
   .weddingSwiper .swiper-button-prev {
     position: static;
     width: 38px;
     height: 38px;
     border-radius: 50%;
     border: 1px solid #ddd;
     background: #fff;
     color: #ab823e;
     display: flex;
     align-items: center;
     justify-content: center;
     transition: 0.3s ease;
   }

   .weddingSwiper .swiper-button-next:hover,
   .weddingSwiper .swiper-button-prev:hover {
     background: #ab823e;
     color: #fff;
   }

   /* Arrow icons */
   .weddingSwiper .swiper-button-next::after,
   .weddingSwiper .swiper-button-prev::after {
     font-size: 14px;
     font-weight: 600;
   }

   .destination-services {
     background: #fbfaf7;
   }

   .service-box {
     padding: 20px;
   }

   .service-box h5 {
     font-weight: 500;
     margin-bottom: 10px;
   }

   .service-box p {
     font-size: 14px;
     color: #555;
   }

   /* Icon circle */
   .wedding-icon {
     width: 70px;
     height: 70px;
     border-radius: 50%;
     border: 2px solid #ddd;
     margin: 0 auto;

     display: flex;
     align-items: center;
     justify-content: center;

     color: #ab823e;
     font-size: 28px;
   }

   /* Hover effect */
   .service-box:hover .wedding-icon {
     background: #ab823e;
     color: #fff;
     border-color: #ab823e;
     transition: 0.3s ease;
   }

   .faq-section {
     background: #f8f9fa;
   }

   .accordion-button {
     font-weight: 600;
   }

   .accordion-button:not(.collapsed) {
     background-color: #fff;
     color: #ab823e;
     box-shadow: none;
   }

   .accordion-item {
     border: 1px solid #e5e5e5;
     border-radius: 10px;
     margin-bottom: 10px;
     overflow: hidden;
   }
 </style>

 <body>

   <?php include 'navbar.php'; ?>

   <!-- banner section -->
   <section class="contact-section">
     <div class="container">
       <h2 class="display-4">Destination Wedding</h2>
       <p class="lead">
         Our Destination Wedding services turn your dream celebration into a
         flawless reality. From breathtaking beach ceremonies to royal palace
         weddings and scenic mountain vows, we handle every detail with
         elegance and precision. We assist with venue selection, travel
         arrangements, guest coordination, décor, and legal formalities—so you
         can focus on love and celebration. Wherever your heart leads, we make
         your wedding unforgettable.
       </p>
     </div>
   </section>

   <!-- wedding services -->
   <section class="wedding-services py-5">
     <div class="custom-container text-center">
       <h2 class="fw-light">Make your wedding a lifetime event by experts</h2>
       <p class="text-muted mb-4">We handle everything for you including</p>

       <!-- Swiper -->
       <div class="swiper weddingSwiper">
         <div class="swiper-wrapper">
           <!-- Slide 1 -->
           <div class="swiper-slide">
             <div class="service-card">
               <img
                 src="asset/image/wedding/wedding-planner.jfif"
                 alt="Wedding Planning" />
               <h6>Wedding Planning</h6>
             </div>
           </div>

           <!-- Slide 2 -->
           <div class="swiper-slide">
             <div class="service-card">
               <img
                 src="asset/image/wedding/venue-select.avif"
                 alt="Venue Selection" />
               <h6>Venue Selection</h6>
             </div>
           </div>

           <!-- Slide 3 -->
           <div class="swiper-slide">
             <div class="service-card">
               <img
                 src="asset/image/wedding/entertainment.webp"
                 alt="Entertainment" />
               <h6>Entertainment</h6>
             </div>
           </div>

           <!-- Slide 4 -->
           <div class="swiper-slide">
             <div class="service-card">
               <img
                 src="asset/image/wedding/destination.avif"
                 alt="Destination" />
               <h6>Destination</h6>
             </div>
           </div>

           <!-- Slide 5 -->
           <div class="swiper-slide">
             <div class="service-card">
               <img
                 src="asset/image/wedding/wedding-ceremony.avif"
                 alt="Destination" />
               <h6>Wedding Cremonies</h6>
             </div>
           </div>
         </div>
         <!-- Bottom Controls -->
         <div class="swiper-controls">
           <div class="swiper-button-prev"></div>

           <!-- Slider line -->
           <div class="swiper-pagination"></div>

           <div class="swiper-button-next"></div>
         </div>
       </div>
     </div>
   </section>

   <!-- Destination  services -->
   <section class="destination-services py-5">
     <div class="custom-container text-center">
       <!-- Heading -->
       <h2 class="fw-light mb-2">
         Hassle Free Destination wedding Planning Services for
         <span class="fw-semibold" style="color: #ab823e">YOU</span>
       </h2>

       <p class="text-muted mx-auto mb-5" style="max-width: 900px">
         From exotic wedding to royal wedding from beach wedding to any
         traditional wedding we give you plenty of choices, best celebration
         options, 24 hrs services and that in your budget.
       </p>

       <!-- Services Row -->
       <div class="row g-4 justify-content-center">
         <!-- Item 1 -->
         <div class="col-12 col-md-6 col-lg-3">
           <div class="service-box">
             <div class="wedding-icon">
               <i class="bi bi-globe2"></i>
             </div>
             <h5 class="mt-3">Choices</h5>
             <p>
               We have plenty of options for your dream celebrations, we have
               tied up with hundreds of properties to fit in your budget.
             </p>
           </div>
         </div>

         <!-- Item 2 -->
         <div class="col-12 col-md-6 col-lg-3">
           <div class="service-box">
             <div class="wedding-icon">
               <i class="bi bi-gem"></i>
             </div>
             <h5 class="mt-3">Celebrations</h5>
             <p>
               Our dedicated team will work with you to make each of your
               celebrations memorable.
             </p>
           </div>
         </div>

         <!-- Item 3 -->
         <div class="col-12 col-md-6 col-lg-3">
           <div class="service-box">
             <div class="wedding-icon">
               <i class="bi bi-headset"></i>
             </div>
             <h5 class="mt-3">Dedicated Services</h5>
             <p>
               Explore over 1,200 properties in 42 countries to find the
               perfect fit for your style and budget.
             </p>
           </div>
         </div>

         <!-- Item 4 -->
         <div class="col-12 col-md-6 col-lg-3">
           <div class="service-box">
             <div class="wedding-icon">
               <i class="bi bi-currency-dollar"></i>
             </div>
             <h5 class="mt-3">Savings</h5>
             <p>
               Our team would make sure that you get best of our services in
               your stipulated budget.
             </p>
           </div>
         </div>
       </div>
     </div>
   </section>

   <!-- faq -->
   <section class="faq-section py-5">
     <div class="custom-container">
       <!-- Section Heading -->
       <div class="text-center mb-4">
         <h2 class="fw-bold">Frequently Asked Questions</h2>
         <p class="text-muted">
           Everything you need to know about planning a sacred destination
           wedding.
         </p>
       </div>

       <!-- FAQ Accordion -->
       <div class="accordion accordion-flush" id="faqAccordion">
         <!-- Item 1 -->
         <div class="accordion-item">
           <h2 class="accordion-header">
             <button
               class="accordion-button"
               type="button"
               data-bs-toggle="collapse"
               data-bs-target="#faq1">
               1. What is a destination wedding in a spiritual location?
             </button>
           </h2>
           <div
             id="faq1"
             class="accordion-collapse collapse show"
             data-bs-parent="#faqAccordion">
             <div class="accordion-body">
               A destination wedding in a spiritual location is a sacred
               wedding ceremony conducted at holy destinations such as temples,
               ghats, heritage mandaps, or pilgrimage cities. It blends
               traditional rituals with the divine atmosphere of a revered
               religious place.
             </div>
           </div>
         </div>

         <!-- Item 2 -->
         <div class="accordion-item">
           <h2 class="accordion-header">
             <button
               class="accordion-button collapsed"
               type="button"
               data-bs-toggle="collapse"
               data-bs-target="#faq2">
               2. Which are the most popular destinations for spiritual
               weddings?
             </button>
           </h2>
           <div
             id="faq2"
             class="accordion-collapse collapse"
             data-bs-parent="#faqAccordion">
             <div class="accordion-body">
               Popular spiritual wedding destinations include Varanasi,
               Rishikesh, Haridwar, Ujjain, Tirupati, Madurai, Pushkar, Dwarka,
               Puri, and select temple towns across India known for their
               religious significance.
             </div>
           </div>
         </div>

         <!-- Item 3 -->
         <div class="accordion-item">
           <h2 class="accordion-header">
             <button
               class="accordion-button collapsed"
               type="button"
               data-bs-toggle="collapse"
               data-bs-target="#faq3">
               3. Do you arrange temple wedding rituals and priests?
             </button>
           </h2>
           <div
             id="faq3"
             class="accordion-collapse collapse"
             data-bs-parent="#faqAccordion">
             <div class="accordion-body">
               Yes, we arrange experienced temple priests (Pandits) who perform
               authentic Vedic rituals according to your tradition. All
               ceremonies are conducted as per religious customs and auspicious
               timings.
             </div>
           </div>
         </div>

         <!-- Item 4 -->
         <div class="accordion-item">
           <h2 class="accordion-header">
             <button
               class="accordion-button collapsed"
               type="button"
               data-bs-toggle="collapse"
               data-bs-target="#faq4">
               4. Is permission required for a temple destination wedding?
             </button>
           </h2>
           <div
             id="faq4"
             class="accordion-collapse collapse"
             data-bs-parent="#faqAccordion">
             <div class="accordion-body">
               Yes, most temples require prior permission. We assist with all
               temple approvals, documentation, and coordination to ensure a
               smooth and lawful wedding ceremony.
             </div>
           </div>
         </div>

         <!-- Item 5 -->
         <div class="accordion-item">
           <h2 class="accordion-header">
             <button
               class="accordion-button collapsed"
               type="button"
               data-bs-toggle="collapse"
               data-bs-target="#faq5">
               5. Can you customize rituals based on our tradition?
             </button>
           </h2>
           <div
             id="faq5"
             class="accordion-collapse collapse"
             data-bs-parent="#faqAccordion">
             <div class="accordion-body">
               Absolutely. Wedding rituals can be customized based on North
               Indian, South Indian, Gujarati, Maharashtrian, or other regional
               traditions while maintaining spiritual authenticity.
             </div>
           </div>
         </div>

         <!-- Item 6 -->
         <div class="accordion-item">
           <h2 class="accordion-header">
             <button
               class="accordion-button collapsed"
               type="button"
               data-bs-toggle="collapse"
               data-bs-target="#faq6">
               6. Do you provide complete wedding planning services?
             </button>
           </h2>
           <div
             id="faq6"
             class="accordion-collapse collapse"
             data-bs-parent="#faqAccordion">
             <div class="accordion-body">
               Yes, we offer end-to-end services including venue selection,
               priest arrangements, décor, accommodation, guest transfers,
               catering (satvik meals), photography, and post-wedding rituals.
             </div>
           </div>
         </div>

         <!-- Item 7 -->
         <div class="accordion-item">
           <h2 class="accordion-header">
             <button
               class="accordion-button collapsed"
               type="button"
               data-bs-toggle="collapse"
               data-bs-target="#faq7">
               7. Are destination weddings suitable for small and intimate
               gatherings?
             </button>
           </h2>
           <div
             id="faq7"
             class="accordion-collapse collapse"
             data-bs-parent="#faqAccordion">
             <div class="accordion-body">
               Yes, spiritual destination weddings are ideal for intimate and
               meaningful ceremonies with close family members, creating a
               peaceful and divine wedding experience.
             </div>
           </div>
         </div>

         <!-- Item 8 -->
         <div class="accordion-item">
           <h2 class="accordion-header">
             <button
               class="accordion-button collapsed"
               type="button"
               data-bs-toggle="collapse"
               data-bs-target="#faq8">
               8. Can you arrange accommodation for guests near temples?
             </button>
           </h2>
           <div
             id="faq8"
             class="accordion-collapse collapse"
             data-bs-parent="#faqAccordion">
             <div class="accordion-body">
               Yes, we arrange comfortable accommodations such as dharamshalas,
               spiritual resorts, hotels, and guest houses close to the wedding
               venue.
             </div>
           </div>
         </div>

         <!-- Item 9 -->
         <div class="accordion-item">
           <h2 class="accordion-header">
             <button
               class="accordion-button collapsed"
               type="button"
               data-bs-toggle="collapse"
               data-bs-target="#faq9">
               9. Do you provide vegetarian or satvik catering?
             </button>
           </h2>
           <div
             id="faq9"
             class="accordion-collapse collapse"
             data-bs-parent="#faqAccordion">
             <div class="accordion-body">
               Yes, we specialize in pure vegetarian and satvik food catering,
               prepared according to religious guidelines and temple norms.
             </div>
           </div>
         </div>

         <!-- Item 10 -->
         <div class="accordion-item">
           <h2 class="accordion-header">
             <button
               class="accordion-button collapsed"
               type="button"
               data-bs-toggle="collapse"
               data-bs-target="#faq10">
               10. Is photography allowed inside temples?
             </button>
           </h2>
           <div
             id="faq10"
             class="accordion-collapse collapse"
             data-bs-parent="#faqAccordion">
             <div class="accordion-body">
               Photography policies vary by temple. We guide you on permitted
               photography areas and arrange professional photographers who
               respect temple rules and spiritual sentiments.
             </div>
           </div>
         </div>
       </div>
     </div>
   </section>

   <?php include 'footer.php'; ?>

   <?php include 'includes/whatsapp.php'; ?>

   <!-- menu script -->
   <script src="asset/js/menu.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <script>
     new Swiper(".weddingSwiper", {
       slidesPerView: 4,
       spaceBetween: 20,
       loop: true,
       navigation: {
         nextEl: ".swiper-button-next",
         prevEl: ".swiper-button-prev",
       },

       pagination: {
         el: ".swiper-pagination",
         type: "progressbar",
       },
       breakpoints: {
         0: {
           slidesPerView: 1,
         },
         576: {
           slidesPerView: 2,
         },
         992: {
           slidesPerView: 4,
         },
       },
     });
   </script>
   
 </body>

 