console.log('we here');

moveFilterToRight();
moveFilterButtonDown();
removeFilterIfProductsNoexist();
moveitem();
addFilterShowButton();
function moveFilterToRight() {
    var products = document.querySelector('.products');
    if (!products) return false;
    var filter = document.querySelector('.golden-filters');
    if (!filter) return false;
    var main = document.querySelector('#main');
    if (!main) return false;
    var wrapForProducts = document.createElement('div');
    wrapForProducts.classList.add('golden-wrap-products');
    main.insertBefore(wrapForProducts,products);
    wrapForProducts.appendChild(filter);
    wrapForProducts.appendChild(products);
}
function moveFilterButtonDown() {
    var priceFilter = document.querySelector('.price_slider_amount');
    if (!priceFilter) return false;
    var buttonPriceFilter = priceFilter.querySelector('.button');
    if (!buttonPriceFilter) return false;
    priceFilter.appendChild(buttonPriceFilter);
}
function removeFilterIfProductsNoexist() {
    var noExist = document.querySelector('.woocommerce-no-results');
    var filter = document.querySelector('.golden-filters');
    if (!noExist || !filter) return false;
    filter.remove();
}
function moveitem() {
    var wrap = document.querySelector('.golden-wrap-products');
    if (!wrap) return false;
    $('.golden-wrap-products').bind("DOMSubtreeModified",function(){
        var ulForProduct = wrap.querySelector('.products');
        var allLi = document.querySelectorAll('.golden-wrap-products > .type-product');
        allLi.forEach(function (element) {
            ulForProduct.appendChild(element);
        });
    });
}
function addFilterShowButton() {
    var header = document.querySelector('.golden-wrap-products');
    var mainBlock = document.querySelector('#main');
    if (!mainBlock) return false;
    if (!header) return false;
    var filterBlock = document.querySelector('.golden-filters');
    var button = document.createElement('button');
    button.classList.add('golden-filter-button');
    // filterBlock.style.display = 'none';
    // button.classList.add('golden-filter-button__active');
    button.textContent = 'Фильтры';
    button.addEventListener('click', function (evt) {
        if (button.classList.contains('golden-filter-button__active')){
            // filterBlock.style.display = 'none';
            button.classList.remove('golden-filter-button__active');
            filterBlock.classList.remove('golden-filters__active');
        }
        else {
            filterBlock.classList.add('golden-filters__active');
            button.classList.add('golden-filter-button__active');
        }
    })

    mainBlock.insertBefore(button, header);
    console.log('fdsfsdfsd');



}