// fetch("asset/json/menu.json")
//   .then(res => res.json())
//   .then(data => {
//     const menuContainer = document.getElementById("main-menu");

//     const renderMenu = (menu, isSub = false) => {
//       return menu.map(item => {

//         // SIMPLE LINK
//         if (!item.children) {
//           return `
//             <li class="nav-item ${isSub ? '' : ''}">
//               <a href="${item.url}" class="nav-link fw-bold">${item.title}</a>
//             </li>
//           `;
//         }

//         // DROPDOWN / SUBMENU
//         return `
//           <li class="nav-item dropdown ${isSub ? 'dropdown-submenu dropend' : ''}">
//             <a class="${isSub ? 'dropdown-item dropdown-toggle' : 'nav-link dropdown-toggle fw-bold'}"
//                href="#"
//                data-bs-toggle="dropdown">
//                ${item.title}
//             </a>
//             <ul class="dropdown-menu shadow border-0">
//               ${renderMenu(item.children, true).join("")}
//             </ul>
//           </li>
//         `;
//       });
//     };

//     menuContainer.innerHTML = renderMenu(data.menus).join("");
//   })
//   .catch(err => {
//     console.error("Menu load failed", err);
//   });

fetch("asset/json/menu.json")
  .then((res) => res.json())
  .then((data) => {
    const menuContainer = document.getElementById("main-menu");

    const renderMenu = (menu, isSub = false) => {
      return menu.map((item) => {
        if (!item.children) {
          return `
            <li class="nav-item ${isSub ? "" : ""}">
              <a href="${item.url}" class="${
            isSub ? "dropdown-item" : "nav-link fw-bold"
          }">${item.title}</a>
            </li>
          `;
        }

        return `
      <li class="nav-item dropdown ${isSub ? "dropdown-submenu dropend" : ""}">
  <a href="#"
     class="${
       isSub
         ? "dropdown-item submenu-toggle d-flex justify-content-between align-items-center"
         : "nav-link dropdown-toggle fw-bold"
     }"
     ${isSub ? "" : 'data-bs-toggle="dropdown"'}>
     
    <span>${item.title}</span>

    ${isSub ? '<span class="submenu-icon">▸</span>' : ""}
  </a>

  <ul class="dropdown-menu shadow border-0">
    ${renderMenu(item.children, true).join("")}
  </ul>
</li>

        `;
      });
    };

    menuContainer.innerHTML = renderMenu(data.menus).join("");

    // Initialize top-level Bootstrap dropdowns
    const dropdownElementList = [].slice.call(
      document.querySelectorAll(".dropdown-toggle")
    );
    dropdownElementList.map(function (dropdownToggleEl) {
      return new bootstrap.Dropdown(dropdownToggleEl);
    });

    // Custom JS for submenus
    const submenuLinks = document.querySelectorAll(".dropdown-submenu > a");

    submenuLinks.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        const currentMenu = this.nextElementSibling;

        // CLOSE all other open submenus
        document
          .querySelectorAll(".dropdown-submenu .dropdown-menu.show")
          .forEach((menu) => {
            if (menu !== currentMenu) {
              menu.classList.remove("show");
            }
          });

        // TOGGLE current submenu
        if (currentMenu) {
          currentMenu.classList.toggle("show");
        }
      });
    });

    // Close submenu when clicking outside
    document.addEventListener("click", function (e) {
      submenus.forEach((el) => {
        const submenu = el.nextElementSibling;
        if (
          submenu &&
          submenu.classList.contains("show") &&
          !el.contains(e.target)
        ) {
          submenu.classList.remove("show");
        }
      });
    });
  })
  .catch((err) => console.error("Menu load failed", err));
