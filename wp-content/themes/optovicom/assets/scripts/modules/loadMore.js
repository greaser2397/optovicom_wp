function isAnyPartOfElementInViewport(el) {

    const rect = el.getBoundingClientRect();
    // DOMRect { x: 8, y: 8, width: 100, height: 100, top: 8, right: 108, bottom: 108, left: 8 }
    const windowHeight = (window.innerHeight || document.documentElement.clientHeight);
    const windowWidth = (window.innerWidth || document.documentElement.clientWidth);
    const vertInView = (rect.top <= windowHeight) && ((rect.top + rect.height) >= 0);
    const horInView = (rect.left <= windowWidth) && ((rect.left + rect.width) >= 0);

    return (vertInView && horInView);
}

let $flag = false;

function loadMoreProducts(el, urls) {
    let button = el,
        data = {
            'action': 'optovicom_load_more_ajax_callback',
            'query': globalVars.posts,
            'page': globalVars.current_page,
        };

    $.ajax({
        url: urls.ajaxUrl,
        data: data,
        type: 'POST',
        beforeSend: function () {
            button.text('Загрузка...');
        },
        success: function (data) {
            if (data) {
                button.prev().append(data);
                button.text('Загрузить еще');
                globalVars.current_page++;
                $flag = false;

                if (parseInt(globalVars.current_page) === parseInt(globalVars.max_page))
                    button.remove();
            } else {
                button.remove();
            }
        },
    });
}

jQuery(document).ready(($) => {
    const urls = {
        homeUrl: $('.site-url').attr('data-src'),
        rootDirUrl: $('.tpl-url').attr('data-src'),
        ajaxUrl: $('.ajax-url').attr('data-src'),
    };

    const loadBtn = document.getElementById('load-more');
    const $loadBtn = jQuery('#load-more');

    $loadBtn.length > 0 && $loadBtn.click(function () {
        loadMoreProducts($(this), urls);
    });

    loadBtn && jQuery(window).on('scroll', function () {
        if (isAnyPartOfElementInViewport(loadBtn) && $.active < 1) {
            loadMoreProducts($loadBtn, urls);
        }
    });
});
