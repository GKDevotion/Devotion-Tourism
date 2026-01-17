 <?php
  $pageTitle = "Devotion Tourism - MICE";
  include 'includes/head.php';
  ?>

 <style>
   .nav-pills .nav-link.active,
   .nav-pills .show>.nav-link {
     color: var(--bs-nav-pills-link-active-color);
     background-color: #ab823e;
     border-radius: 100px;
   }

   .mice-image {
     max-height: 320px;
   }

   @media (max-width: 768px) {
     .mice-image {
       max-height: 250px;
     }
   }
 </style>

 <body>

   <?php include 'navbar.php'; ?>

   <section class="py-5 mt-5 bg-white">
     <div class="custom-container">
       <!-- LEFT IMAGE -->
       <div class="col-lg-12 text-center mb-4 mb-lg-0">
         <img
           src="asset/image/mice/mice.webp"
           alt="MICE Tourism"
           class="img-fluid mice-image" />
       </div>

       <div class="row align-items-center">
         <!-- RIGHT CONTENT -->
         <div class="col-lg-12 pt-5">
           <h2 class="fw-bold mb-3">What is M.I.C.E. tourism?</h2>

           <p class="text-muted">
             <strong>M.I.C.E.</strong> stands for meetings, incentives,
             conference & events
           </p>

           <p class="text-muted">
             Corporate demands such mice tourism for their business units,
             associates & customers
           </p>

           <p class="text-muted">
             M.I.C.E. programs are held in large number and participants travel
             from different location across the globe to attend business
             meetings & conferences, such programs are also being held
             internally in companies to incentivise their team with RNR,
             Entertainment & Tourism.
           </p>

           <p class="text-muted">
             M.I.C.E. programs are planned well in advance with a proper travel
             itinerary, hospitality & Event Planning based the event
             requirement.
           </p>

           <p class="text-muted">
             M.I.C.E. planning is a compilation of travel, accommodation,
             hospitality, event planning, tourism planning & facilities with
             perfect time management and 24/7 support system.
           </p>

           <p class="text-muted">
             For planning a M.I.C.E. program either you hire a mice company or
             one should be an expert into mice services which includes travel,
             hospitality, events & tourism with destination proper resources so
             that the travel group can experience a memorable Meeting,
             Incentive, Conference & Event.
           </p>
         </div>
       </div>
     </div>
   </section>

   <section class="py-5">
     <div class="custom-container">
       <!-- MEETINGS -->
       <div class="row align-items-center mb-5">
         <!-- Image -->
         <div class="col-lg-6 mb-4 mb-lg-0">
           <img
             src="asset/image/mice/meeting.jpg"
             alt="Meetings"
             class="img-fluid rounded shadow" />
         </div>

         <!-- Content -->
         <div class="col-lg-6">
           <h3 class="fw-bold text-uppercase mb-3">Meetings</h3>
           <ul class="list-unstyled mice-list">
             <li>
               Meetings is formal gathering of people who are employees and
               professionals to conduct a business activity or for formal
               discussions sometimes combined with informal activities.
             </li>
             <li>
               Some forms of meetings are board Meetings, annual general
               meetings or AGM’s, Management Meetings, training or on boarding
               meetings, meetings with associates, partners, suppliers,
               dealers, etc. planning meets.
             </li>
             <li>
               It can also include product launches and team building meetings.
             </li>
           </ul>
         </div>
       </div>

       <!-- INCENTIVES -->
       <div class="row align-items-center mb-5">
         <!-- Content -->
         <div class="col-lg-6 order-2 order-lg-1">
           <h3 class="fw-bold text-uppercase mb-3">Incentives</h3>
           <ul class="list-unstyled mice-list">
             <li>
               Incentives is totally different segment of M.I.C.E. and type of
               meeting where the purpose as not business leisure.
             </li>
             <li>
               Incentive events are fun and leisure tours and trips planned for
               employees, staff or for associates and even sometimes for
               customers as a reward or recognition for their contribution or
               support.
             </li>
             <li>
               Incentive usually include a group travel with hotel stay, local
               travel, dinners, fun & recreational activities, and more.
             </li>
           </ul>
         </div>

         <!-- Image -->
         <div class="col-lg-6 order-1 order-lg-2 mb-4 mb-lg-0">
           <img
             src="asset/image/mice/mice.webp"
             alt="Incentives"
             class="img-fluid rounded shadow" />
         </div>
       </div>

       <!-- CONFERENCES -->
       <div class="row align-items-center mb-5">
         <!-- Image -->
         <div class="col-lg-6 mb-4 mb-lg-0">
           <img
             src="asset/image/mice/conference.png"
             alt="Meetings"
             class="img-fluid rounded shadow" />
         </div>

         <!-- Content -->
         <div class="col-lg-6">
           <h3 class="fw-bold text-uppercase mb-3">CONFERENCES</h3>
           <ul class="list-unstyled mice-list">
             <li>
               Conferences or often called as conventions are also a form of
               meetings but with wider and higher number of participation. This
               participation is usually not limited to one company or
               organisation.
             </li>
             <li>
               Also this type of meetings can last for few days and are often
               organised by professional or industry bodies and associations.
             </li>
             <li>These often may include small exhibitions.</li>
           </ul>
         </div>
       </div>

       <!-- EVENTS -->
       <div class="row align-items-center">
         <!-- Content -->
         <div class="col-lg-6 order-2 order-lg-1">
           <h3 class="fw-bold text-uppercase mb-3">EVENTS</h3>
           <ul class="list-unstyled mice-list">
             <li>
               Corporate events are an effective way for companies to engage
               with employees or customers.
             </li>
             <li>
               They can have various purposes, from announcing important
               changes to creating networking opportunities.
             </li>
           </ul>
         </div>

         <!-- Image -->
         <div class="col-lg-6 order-1 order-lg-2 mb-4 mb-lg-0">
           <img
             src="asset/image/mice/event.png"
             alt="Incentives"
             class="img-fluid rounded shadow" />
         </div>
       </div>
     </div>
   </section>

   <?php include 'footer.php'; ?>

   <?php include 'includes/whatsapp.php'; ?>

   <!-- menu script -->
   <script src="asset/js/menu.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
 </body>
 