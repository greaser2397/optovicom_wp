// import external dependencies
import "slick-carousel/slick/slick.min"
import "select2"

// Import everything from autoload
import "./autoload/**/*"

const doc = document;

jQuery(document).ready(($) => {
  let isLegibleCarousel = (el) => {
      return el.length !== 0 && el.children().length > 1
    },
    bannerSlider = $('.banner-slider'),
    previewSlider = $('.preview-slider');

  if (isLegibleCarousel(bannerSlider)) {
    bannerSlider.slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      variableHeight: true,
      arrows: true,
      dots: false,
      asNavFor: '.preview-slider',
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            adaptiveHeight: true,
          },
        },
      ],
    });
  }

  if (isLegibleCarousel(previewSlider)) {
    previewSlider.slick({
      slidesToShow: bannerSlider.children().length < 4 ? bannerSlider.children().length - 1 : 3,
      slidesToScroll: 1,
      arrows: false,
      dots: false,
      asNavFor: '.banner-slider',
      focusOnSelect: true,
      responsive: [
        {
          breakpoint: 640,
          settings: {
            slidesToShow: bannerSlider.children().length < 3 ? bannerSlider.children().length - 1 : 2,
          },
        },
      ],
    });
  }

  $('.form-row-composite select').select2({
    width: null,
  });
});

let openMiniCartDropdown = () => {
  let cartDropdown = doc.querySelector('.woocommerce-mini-cart-wrapper');
  let accDropdown = doc.querySelector('.account-menu-dropdown-wrapper');

  doc.addEventListener('click', (e) => {
    let currTarget = e.target;

    if (currTarget.closest('.cart-contents')) {
      e.preventDefault();
      accDropdown = doc.querySelector('.account-menu-dropdown-wrapper');
      cartDropdown = doc.querySelector('.woocommerce-mini-cart-wrapper');
      cartDropdown.classList.add('expanded');

      if (accDropdown.classList.contains('expanded')) {
        accDropdown.classList.remove('expanded');
      }
    }

    if (currTarget.closest('.btn-close-cart')) {
      cartDropdown.classList.remove('expanded');
    }
  })
};

let openAccountDropdown = () => {
  let accDropdown = doc.querySelector('.account-menu-dropdown-wrapper');
  let cartDropdown = doc.querySelector('.woocommerce-mini-cart-wrapper');

  doc.addEventListener('click', (e) => {
    let currTarget = e.target;

    if (currTarget.closest('.header__account')) {
      e.preventDefault();
      accDropdown = doc.querySelector('.account-menu-dropdown-wrapper');
      cartDropdown = doc.querySelector('.woocommerce-mini-cart-wrapper');
      accDropdown.classList.add('expanded');

      if (cartDropdown.classList.contains('expanded')) {
        cartDropdown.classList.remove('expanded');
      }
    }

    if (currTarget.closest('.btn-close-account')) {
      accDropdown.classList.remove('expanded');
    }
  })
};

let changeStripeBtnText = () => {
  const stripeSubmit = doc.querySelector('.wc-stripe-checkout-button');

  if (doc.body.contains(stripeSubmit)) {
    stripeSubmit.innerHTML = 'Разместить заказ';
  }
};

let productSizeSelect2 = () => {
  const selectField = jQuery('#pa_size');

  if (selectField.length > 0) {
    selectField.select2();
  }
};

let mobileCats = () => {
  const chevronClass = 'expand-chevron';

  doc.addEventListener('click', (e) => {
    let currInput = e.target;

    if (currInput.classList.contains(chevronClass)) {
      currInput.parentNode.classList.toggle('sub-menu-open');
    }
  });
};

const preLoader = doc.getElementById('preloader');
doc.body.classList.add('static');

doc.addEventListener('DOMContentLoaded', () => {
  openMiniCartDropdown();
  openAccountDropdown();
  changeStripeBtnText();
  productSizeSelect2();
  mobileCats();

  setTimeout(() => {
    preLoader.classList.add('hidden');
    doc.body.classList.remove('static');
  }, 500);

  const togglePanel = doc.querySelector('.panel__toggle');
  togglePanel && togglePanel.addEventListener('click', () => {
    const headerPanel = doc.querySelector('.header-top-inner ');
    headerPanel && headerPanel.classList.toggle('expanded');
  });
});

window.onbeforeunload = () => {
  preLoader.classList.remove('hidden');
};
