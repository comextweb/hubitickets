  // Apply 'minimenu' early (before DOMContentLoaded)
  if (localStorage.getItem('minimenu-enabled') === 'true') {
    document.body.classList.add('no-transition');
    document.body.classList.add('minimenu');
  }

  // Inject style to disable transitions on load
  const style = document.createElement('style');
  style.textContent = `
    body.no-transition .dash-sidebar,
    body.no-transition .main-content {
      transition: none !important;
    }
  `;
  document.head.appendChild(style);

  document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('sidebar-toggle-btn');
    const body = document.body;
    const sidebarSubmenu = document.querySelector('.sidebar-submenu');
    let activeDashItem = null;

    // Remove no-transition after load
    if (body.classList.contains('no-transition')) {
      setTimeout(() => {
        body.classList.remove('no-transition');
      }, 50);
    }

    // Toggle minimenu on button click
    toggleBtn.addEventListener('click', function () {
      const enabled = body.classList.contains('minimenu');
      body.classList.toggle('minimenu', !enabled);

      if (!enabled) {
        localStorage.setItem('minimenu-enabled', 'true');
      } else {
        localStorage.removeItem('minimenu-enabled');
      }
    });

    // Position submenu beside item
    function positionSidebarSubmenu(target) {
      const rect = target.getBoundingClientRect();
      const submenuHeight = sidebarSubmenu.offsetHeight;
      const viewportHeight = window.innerHeight;
      const isRTL = document.documentElement.getAttribute('dir') === 'rtl';
      let top = rect.top + rect.height;
      const left = isRTL
        ? window.innerWidth - rect.left + 10
        : rect.left + rect.width + 10;

      if (top + submenuHeight > viewportHeight) {
        const availableAbove = rect.top;
        if (availableAbove > submenuHeight) {
          top = rect.top - submenuHeight;
        } else {
          top = Math.min(viewportHeight - submenuHeight - 10, rect.top + rect.height);
          sidebarSubmenu.style.maxHeight = `${viewportHeight - top - 20}px`;
          sidebarSubmenu.style.overflowY = 'auto';
        }
      } else {
        sidebarSubmenu.style.maxHeight = '';
        sidebarSubmenu.style.overflowY = '';
      }

      sidebarSubmenu.style.position = 'fixed';
      sidebarSubmenu.style.top = `${top}px`;

      if (isRTL) {
        sidebarSubmenu.style.right = `${left}px`;
        sidebarSubmenu.style.left = 'auto';
      } else {
        sidebarSubmenu.style.left = `${left}px`;
        sidebarSubmenu.style.right = 'auto';
      }

      sidebarSubmenu.style.zIndex = '9999';
    }

    // Attach submenu listeners
    function attachClickListeners() {
      document.querySelectorAll('.navbar-content .dash-item.dash-hasmenu').forEach(item => {
        item.addEventListener('click', (e) => {
          if (!body.classList.contains('minimenu')) return;

          const submenu = item.querySelector(':scope > .dash-submenu');
          if (!submenu) return;

          const isAnchor = e.target.closest('a');
          if (isAnchor) e.preventDefault();
          if (sidebarSubmenu.contains(e.target)) return;

          const isSameItem = activeDashItem === item;

          if (isSameItem && submenu.style.display === 'block') {
            submenu.style.display = 'none';
            sidebarSubmenu.style.display = 'none';
            item.classList.remove('dash-active');
            activeDashItem = null;
            return;
          }

          if (activeDashItem && activeDashItem !== item) {
            const oldSubmenu = sidebarSubmenu.querySelector('.dash-submenu');
            if (oldSubmenu) {
              oldSubmenu.style.display = 'none';
              activeDashItem.appendChild(oldSubmenu);
            }
            activeDashItem.classList.remove('dash-active');
          }

          item.classList.add('dash-active');

          sidebarSubmenu.innerHTML = '';
          sidebarSubmenu.appendChild(submenu);
          submenu.style.display = 'block';
          sidebarSubmenu.style.display = 'block';

          requestAnimationFrame(() => {
            positionSidebarSubmenu(item);
          });

          activeDashItem = item;
        });
      });
    }

    // Handle class changes on body
    const observer = new MutationObserver(() => {
      if (!body.classList.contains('minimenu') && activeDashItem) {
        const submenu = sidebarSubmenu.querySelector('.dash-submenu');
        if (submenu && !activeDashItem.contains(submenu)) {
          activeDashItem.appendChild(submenu);
          submenu.style.display = 'none';
          sidebarSubmenu.style.display = 'none';
          activeDashItem.classList.remove('dash-active');
          activeDashItem = null;
        }
      }
    });

    observer.observe(body, { attributes: true, attributeFilter: ['class'] });
    attachClickListeners();

    // Sidebar close logic
    document.addEventListener("click", function (e) {
      const closeBtn = e.target.closest(".sidebar-close-btn");
      const sidebar = document.querySelector(".dash-sidebar");

      if (closeBtn && sidebar) {
        const overlay = sidebar.querySelector(".dash-menu-overlay");
        if (overlay) {
          overlay.remove();
        }
        sidebar.classList.remove("dash-over-menu-active");
        sidebar.classList.remove("mob-sidebar-active");
        body.classList.remove("no-scroll");
        body.classList.remove("mob-sidebar-active");
      }
    });
  });
