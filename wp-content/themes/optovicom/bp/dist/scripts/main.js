/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/yeya-prod/wp-content/themes/optovicom/dist/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

module.exports = jQuery;

/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(2);
module.exports = __webpack_require__(3);


/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* WEBPACK VAR INJECTION */(function(jQuery) {/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery__ = __webpack_require__(0);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_jquery___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_jquery__);
// import external dependencies


// Import everything from autoload


var doc = document;
var wdw = window;

jQuery(document).ready(function ($) {
    // const globalVars = {
    //     homeUrl: $('.site-url').attr('data-src'),
    //     rootDirUrl: $('.tpl-url').attr('data-src'),
    //     ajaxUrl: $('.ajax-url').attr('data-src'),
    // };

    var bannerSlider = $('.banner-slider');
    var previewSlider = $('.preview-slider');

    if (bannerSlider.length !== 0 && previewSlider.length !== 0) {

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
                } ],
        });

        previewSlider.slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
            asNavFor: '.banner-slider',
            focusOnSelect: true,
            responsive: [
                {
                    breakpoint: 640,
                    settings: {
                        slidesToShow: 2,
                    },
                } ],

        });
    }

    $('.form-row-composite select').select2({
        width: null,
    });
});

var openMiniCartDropdown = function () {
    var cartDropdown = doc.querySelector('.woocommerce-mini-cart-wrapper');
    var accDropdown = doc.querySelector('.account-menu-dropdown-wrapper');

    doc.addEventListener('click', function (e) {
        var currTarget = e.target;

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

var openAccountDropdown = function () {
    var accDropdown = doc.querySelector('.account-menu-dropdown-wrapper');
    var cartDropdown = doc.querySelector('.woocommerce-mini-cart-wrapper');

    doc.addEventListener('click', function (e) {
        var currTarget = e.target;

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

var changeStripeBtnText = function () {
    var stripeSubmit = doc.querySelector('.wc-stripe-checkout-button');

    if (doc.body.contains(stripeSubmit)) {
        stripeSubmit.innerHTML = 'Разместить заказ';
    }
};

var productSizeSelect2 = function () {
    var selectField = jQuery('#pa_size');

    if (selectField.length > 0) {
        selectField.select2();
    }
};

var mobileCats = function () {
    var chevronClass = 'expand-chevron';

    doc.addEventListener('click', function (e) {
        var currInput = e.target;

        if (currInput.classList.contains(chevronClass)) {
            currInput.parentNode.classList.toggle('sub-menu-open');
        }
    });
};

var preLoader = doc.getElementById('preloader');
doc.body.classList.add('static');

doc.addEventListener('DOMContentLoaded', function () {
    openMiniCartDropdown();
    openAccountDropdown();
    changeStripeBtnText();
    productSizeSelect2();
    mobileCats();

    setTimeout(function () {
        preLoader.classList.add('hidden');
        doc.body.classList.remove('static');
    }, 500);

    var togglePanel = doc.querySelector('.panel__toggle');
    togglePanel && togglePanel.addEventListener('click', function () {
        var headerPanel = doc.querySelector('.header-top-inner ');
        headerPanel && headerPanel.classList.toggle('expanded');
    });
});

window.onbeforeunload = function () {
    preLoader.classList.remove('hidden');
};

wdw.addEventListener('resize', function () {

});



/* WEBPACK VAR INJECTION */}.call(__webpack_exports__, __webpack_require__(0)))

/***/ }),
/* 3 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);
//# sourceMappingURL=main.js.map