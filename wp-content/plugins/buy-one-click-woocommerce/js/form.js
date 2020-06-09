//(function (jQuery, undefinde) {

jQuery(document).ready(function () {



    jQuery(document).on('click', '.popup .close_order, .overlay', function (e) {
        e.preventDefault();
        jQuery('.popup, .overlay').css({'opacity': '0', 'visibility': 'hidden'});
        jQuery('#buyoneclick_form_order input:checkbox').removeAttr("checked");
        jQuery('#buyoneclick_form_order input[type=hidden].valTrFal').val('valTrFal_disabled');
    });

    jQuery(function () {
        jQuery('#buyoneclick_form_order input:checkbox').change(function () {
            if (jQuery(this).is(':checked')) {
                jQuery('#buyoneclick_form_order input[type=hidden].valTrFal').val('valTrFal_true');
            } else {

                jQuery('#buyoneclick_form_order input[type=hidden].valTrFal').val('valTrFal_disabled');
            }
        });
    });
    //Доп сообщение

    jQuery(document).on('click', '.popummessage .close_message, .overlay_message', function () {
        jQuery('.popummessage, .overlay_message').css({'opacity': '0', 'visibility': 'hidden'});

    });
});



//Обработка клика по кнопке
// Для формы - отправка в ajax class
function saveButton(text, url, objThis, callback) {

    jQuery.ajax({
        type: "POST",
        url: url,
        async: false,
        dataType: 'json',
        data: {
            action: 'buybuttonform',
            form: text
        },
    }).done(function (response) {

        callback(response);

        setTimeout(function () {
            jQuery("#buyoneclick_form_order .form-message-result").html('');
        }, 3000);

        var obj = response;
        if (!obj.success) {
            jQuery("#buyoneclick_form_order .form-message-result").html(obj.data.message)
            return false;
        }
        if (obj.data.num == "1") { //Действие по умолчанию
            jQuery("#buyoneclick_form_order .form-message-result").html(obj.data.result)
        }
        if (obj.data.num == "2") { // Закрытие формы через action мил сек
            jQuery("#buyoneclick_form_order .form-message-result").html(obj.data.result)
            jQuery('.popup, .overlay').fadeOut(obj.data.action);
        }
        if (obj.data.num == "3") { // Показать сообщение action
            jQuery('.popup, .overlay').hide();
            jQuery('.popummessage, .overlay_message').css('opacity', '1');
            jQuery('.popummessage, .overlay_message').css('visibility', 'visible');

        }
        if (obj.data.num == "4") { // Сделать редирект action
            jQuery("#buyoneclick_form_order .form-message-result").html(obj.data.result)
            self.location = obj.data.action;
        }



    }).fail(function (response) {
        jQuery("#buyoneclick_form_order .form-message-result").html('Ошибка сервера');

        callback(response);

        return false;
    });

}

function getAjaxUrl() {

    return buyone_ajax.ajaxurl;
}


jQuery(document).ready(function () {

    jQuery(document).on('click', '#buyoneclick_form_order .buyButtonOkForm', function (e) {
        e.preventDefault();
        var objButton = this;

        jQuery(objButton).prop("disabled", true);

        var parentForm = jQuery(this).parent('#buyoneclick_form_order');

        var allRequired;
        var errorSending = "no";
        var txtname = jQuery(parentForm).find("input[name=txtname]").val();
        var txtphone = jQuery(parentForm).find("input[name=txtphone]").val();
        var txtemail = jQuery(parentForm).find("input[name=txtemail]").val();
        var message = jQuery(".buymessage").val();
        var buy_nametovar = jQuery(parentForm).find("input[name=buy_nametovar]").val();
        var buy_pricetovar = jQuery(parentForm).find("input[name=buy_pricetovar]").val();
        var buy_idtovar = jQuery(parentForm).find("input[name=buy_idtovar]").val();
        var custom = jQuery(objButton).attr('data-custom');

        jQuery(".b1c-form").find(".buyvalide").each(function () {
            if (jQuery(this).attr("required") != undefined) { // если хотя бы одно поле обязательно
                allRequired = "no";
            }

        });

        jQuery(".b1c-form").find(".buyvalide").each(function () {  // проверяем заполенность полей

            if (jQuery(this).val().length < 1) {

                if (allRequired == "no" && jQuery(this).attr("required") != undefined) {

                    errorSending = 1;
                }
                if (allRequired == 1) {

                    errorSending = 1;
                }
            }
        });

        if (errorSending === "no") {
            var infozakaz = {
                txtname: txtname,
                txtphone: txtphone,
                txtemail: txtemail,
                message: message,
                nametovar: buy_nametovar,
                pricetovar: buy_pricetovar,
                idtovar: buy_idtovar,
                custom: custom,
                form: jQuery(this).parent('form').serializeArray(),
            };

            if (jQuery(parentForm).find("input[name=conset_personal_data]").is(":checked")) {
                infozakaz.conset_personal_data = 1;
            } else {
                infozakaz.conset_personal_data = 0;
            }

            var zixnAjaxUrl = getAjaxUrl();
            saveButton(infozakaz, zixnAjaxUrl, objButton, function (response) {

                jQuery(objButton).prop("disabled", false);
            });

        }



    });

});
//Форма рисователь
jQuery(document).ready(function () {
    jQuery(document).on('click', 'button.clickBuyButton', function (e) {
        e.preventDefault();
        var zixnAjaxUrl = getAjaxUrl();
        var butObj = 'body';

        var urlpost = window.location.href;
        var productid = jQuery(this).attr('data-productid');
        var action = 'getViewForm';

        if (typeof buyone_ajax.work_mode !== 'undefined' && buyone_ajax.work_mode == 1) {
            action = 'add_to_cart';
        }

        jQuery.ajax({
            type: "POST",
            url: zixnAjaxUrl,
            // async: false,
            data: {
                action: action,
                urlpost: urlpost,
                productid: productid
            },
            success: function (response) {

                if (action == 'add_to_cart') {
                    window.location.href = response;
                    return true;
                }

                jQuery('#formOrderOneClick').remove();
                jQuery(butObj).after(response);
                jQuery('.popup, .overlay').css('opacity', '1');
                jQuery('.popup, .overlay').css('visibility', 'visible');

                if (typeof buyone_ajax.tel_mask != 'undefined') {
                    jQuery('#buyoneclick_form_order [name="txtphone"]').mask(buyone_ajax.tel_mask);
                }


            }
        });
    });
    jQuery(document).on('click', 'button.clickBuyButtonCustom', function (e) {
        e.preventDefault();
        var zixnAjaxUrl = getAjaxUrl();
        var butObj = this;

        var urlpost = window.location.href;
        var productid = jQuery(butObj).attr('data-productid');
        var name = jQuery(butObj).attr('data-name');
        var count = jQuery(butObj).attr('data-count');
        var price = jQuery(butObj).attr('data-price');

        jQuery.ajax({
            type: "POST",
            url: zixnAjaxUrl,
            async: false,
            data: {
                action: 'getViewFormCustom',
                urlpost: urlpost,
                productid: productid,
                name: name,
                count: count,
                price: price,
            },
            success: function (response) {
                jQuery('#formOrderOneClick').remove();
                jQuery(butObj).after(response);
                jQuery('.popup, .overlay').css('opacity', '1');
                jQuery('.popup, .overlay').css('visibility', 'visible');


            }
        });
    });
});