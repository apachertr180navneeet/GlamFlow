/**
 * Main
 */

'use strict';

let menu, animate;

(function () {
  // Initialize menu
  //-----------------

  let layoutMenuEl = document.querySelectorAll('#layout-menu');
  layoutMenuEl.forEach(function (element) {
    menu = new Menu(element, {
      orientation: 'vertical',
      closeChildren: false
    });
    if (menu && typeof menu.manageScroll === 'function') {
      menu.manageScroll();
    }
    window.Helpers.mainMenu = menu;
  });

  // Override scrollToActive on small screens so menu-inner always starts at top
  if (window.Helpers) {
    const origScrollToActive = window.Helpers._scrollToActive;
    window.Helpers._scrollToActive = function (animate) {
      if (this.isSmallScreen()) {
        let menuInner = document.querySelector('.menu-inner');
        if (menuInner) {
          menuInner.scrollTop = 0;
        }
        return;
      }
      if (typeof origScrollToActive === 'function') {
        origScrollToActive.call(this, animate);
      }
    };
  }

  // Initialize menu togglers and bind click on each
  let menuToggler = document.querySelectorAll('.layout-menu-toggle');
  menuToggler.forEach(item => {
    item.addEventListener('click', event => {
      event.preventDefault();
      let wasCollapsed = window.Helpers.isCollapsed();
      window.Helpers.toggleCollapsed();
      // When expanding sidebar drawer on mobile/tablet, ensure menu-inner starts at top
      if (wasCollapsed && window.Helpers.isSmallScreen()) {
        let menuInner = document.querySelector('.menu-inner');
        if (menuInner) {
          menuInner.scrollTop = 0;
        }
      }
    });
  });

  // Ensure independent scroll for sidebar without wheel/touch propagation to main page content
  let layoutMenuNode = document.querySelector('#layout-menu');
  if (layoutMenuNode) {
    let touchStartY = 0;

    layoutMenuNode.addEventListener('touchstart', function (e) {
      if (e.touches && e.touches.length === 1) {
        touchStartY = e.touches[0].clientY;
      }
    }, { passive: true });

    layoutMenuNode.addEventListener('touchmove', function (e) {
      let inner = this.querySelector('.menu-inner');
      if (!inner || !e.touches || e.touches.length !== 1) return;
      let touchCurrentY = e.touches[0].clientY;
      let deltaY = touchStartY - touchCurrentY;
      let scrollTop = inner.scrollTop;
      let scrollHeight = inner.scrollHeight;
      let height = inner.clientHeight;

      // Allow full scrolling down to last item without blocking touchmove
      if (deltaY < 0 && scrollTop <= 0) {
        if (e.cancelable) e.preventDefault();
      }
    }, { passive: true });

    layoutMenuNode.addEventListener('wheel', function (e) {
      let inner = this.querySelector('.menu-inner');
      if (!inner) return;
      let scrollTop = inner.scrollTop;
      let scrollHeight = inner.scrollHeight;
      let height = inner.clientHeight;
      let delta = e.deltaY;

      if (delta < 0 && scrollTop <= 0) {
        if (scrollTop === 0 && e.cancelable) e.preventDefault();
      }
    }, { passive: true });
  }

  // Display menu toggle (layout-menu-toggle) on hover with delay
  let delay = function (elem, callback) {
    let timeout = null;
    elem.onmouseenter = function () {
      // Set timeout to be a timer which will invoke callback after 300ms (not for small screen)
      if (!Helpers.isSmallScreen()) {
        timeout = setTimeout(callback, 300);
      } else {
        timeout = setTimeout(callback, 0);
      }
    };

    elem.onmouseleave = function () {
      // Clear any timers set to timeout
      if (!Helpers.isSmallScreen()) {
        let toggler = document.querySelector('#layout-menu .layout-menu-toggle');
        if (toggler) toggler.classList.remove('d-block');
      }
      clearTimeout(timeout);
    };
  };
  if (document.getElementById('layout-menu')) {
    delay(document.getElementById('layout-menu'), function () {
      // not for small screen
      if (!Helpers.isSmallScreen()) {
        let toggler = document.querySelector('#layout-menu .layout-menu-toggle');
        if (toggler) toggler.classList.add('d-block');
      }
    });
  }

  // Display in main menu when menu scrolls
  let menuInnerContainer = document.getElementsByClassName('menu-inner'),
    menuInnerShadow = document.getElementsByClassName('menu-inner-shadow')[0];
  if (menuInnerContainer.length > 0 && menuInnerShadow) {
    let handleMenuScroll = function () {
      if (menuInnerContainer[0].scrollTop > 10) {
        menuInnerShadow.style.display = 'block';
      } else {
        menuInnerShadow.style.display = 'none';
      }
    };
    menuInnerContainer[0].addEventListener('scroll', handleMenuScroll);
    menuInnerContainer[0].addEventListener('ps-scroll-y', handleMenuScroll);
  }

  // Init helpers & misc
  // --------------------

  // Init BS Tooltip
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Accordion active class
  const accordionActiveFunction = function (e) {
    if (e.type == 'show.bs.collapse' || e.type == 'show.bs.collapse') {
      e.target.closest('.accordion-item').classList.add('active');
    } else {
      e.target.closest('.accordion-item').classList.remove('active');
    }
  };

  const accordionTriggerList = [].slice.call(document.querySelectorAll('.accordion'));
  const accordionList = accordionTriggerList.map(function (accordionTriggerEl) {
    accordionTriggerEl.addEventListener('show.bs.collapse', accordionActiveFunction);
    accordionTriggerEl.addEventListener('hide.bs.collapse', accordionActiveFunction);
  });

  // Auto update layout based on screen size
  window.Helpers.setAutoUpdate(true);

  // Toggle Password Visibility
  window.Helpers.initPasswordToggle();

  // Speech To Text
  window.Helpers.initSpeechToText();

  // Auto close mobile/tablet menu drawer when clicking navigation links or clicking outside the menu
  document.addEventListener('click', function (e) {
    if (window.Helpers && window.Helpers.isSmallScreen() && !window.Helpers.isCollapsed()) {
      let isInsideMenu = e.target.closest('#layout-menu');
      let isMenuToggle = e.target.closest('.layout-menu-toggle');
      let link = e.target.closest('.menu-inner .menu-link');

      if (!isInsideMenu || link) {
        if (!isMenuToggle) {
          window.Helpers.setCollapsed(true, true);
        }
      }
    }
  });

  // Manage menu expanded/collapsed with templateCustomizer & local storage
  //------------------------------------------------------------------

  // If current layout is horizontal OR current window screen is small (overlay menu) than return from here
  if (window.Helpers.isSmallScreen()) {
    return;
  }

  // Ensure menu stays expanded on desktop by default
  window.Helpers.setCollapsed(false, false);
})();
