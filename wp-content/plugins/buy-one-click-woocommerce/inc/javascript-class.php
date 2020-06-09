<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Класс для работы с JavaScript функциями отправляемыми через скрипты
 */
class BuyJavaScript {

    /**
     * Конструктор класса
     */
    public function __construct() {
        $this->addaction();
    }

    /**
     * Адды
     */
    public function addaction() {
        add_action('wp_ajax_buybuttonform', array($this, 'ajaxBuyButtonForm')); //buybuttonform Посылается из js файла
        add_action('wp_ajax_nopriv_buybuttonform', array($this, 'ajaxBuyButtonForm')); //buybuttonform Посылается из js файла
        add_action('wp_ajax_removeorder', array($this, 'ajaxRemoveOrderId'));
        add_action('wp_ajax_nopriv_removeorder', array($this, 'ajaxRemoveOrderId'));
        add_action('wp_ajax_updatestatus', array($this, 'ajaxStatusOrderId'));
        add_action('wp_ajax_nopriv_updatestatus', array($this, 'ajaxStatusOrderId'));
        add_action('wp_ajax_removeorderall', array($this, 'ajaxRemoveOrderAll'));
        add_action('wp_ajax_nopriv_removeorderall', array($this, 'ajaxRemoveOrderAll'));
        add_action('wp_ajax_getViewForm', array($this, 'ajaxgetViewForm')); //Запрос формы
        add_action('wp_ajax_nopriv_getViewForm', array($this, 'ajaxgetViewForm')); //Запрос формы
        add_action('wp_ajax_getViewFormCustom', array($this, 'ajaxgetViewFormCustom')); //Запрос Кастомной формы
        add_action('wp_ajax_nopriv_getViewFormCustom', array($this, 'ajaxgetViewFormCustom')); //Запрос Кастомной формы
        add_action('wp_ajax_add_to_cart', array($this, 'add_to_cart')); //Добавление в корзину и отправка в оформление
        add_action('wp_ajax_nopriv_add_to_cart', array($this, 'add_to_cart')); //Добавление в корзину и отправка в оформление
        //
    }

    /**
     * Проверка обязательных полей
     */
    protected static function checkRequireField($form) {

        $params = get_option('buyoptions', array());

        if (empty($params)) {
            return true;
        }



        if (!empty($params['email_verifi']) && empty($form['txtemail'])) {
            throw new Exception(__('Email field is required', 'coderun-oneclickwoo'), 200);
        }

        if (!empty($params['fio_verifi']) && empty($form['txtname'])) {
            throw new Exception(__('Name field is required', 'coderun-oneclickwoo'), 200);
        }

        if (!empty($params['fon_verifi']) && empty($form['txtphone'])) {
            throw new Exception(__('Telephone field is required', 'coderun-oneclickwoo'), 200);
        }

        if (!empty($params['dopik_verifi']) && empty($form['message'])) {
            throw new Exception(__('Message field is required', 'coderun-oneclickwoo'), 200);
        }
        
         if (!empty($params['conset_personal_data_enabled']) && empty(boolval($form['conset_personal_data']))) {
            throw new Exception(__('You need to give consent', 'coderun-oneclickwoo'), 200);
        }
    }

    /**
     * Выбросит исключение, если форма отправляетс чаще чем N секунд
     * @param int $product_id ид товара
     * @throws Exception
     */
    protected static function checkLimitSendForm($product_id) {
        //Лимит отправки формы
        $limit_time = intval(BuyCore::$buyoptions['time_limit_send_form']);

        $limit_message = __('You have already sent an order!', 'coderun-oneclickwoo');

        if (empty($limit_time)) {
            $limit_time = 10;
        }
        if (!empty(BuyCore::$buyoptions['time_limit_message'])) {
            $limit_message = BuyCore::$buyoptions['time_limit_message'];
        }

        $key = 'ORDER_LAST_DATE_' . $product_id;

        if (empty($_SESSION['BUY_ONE_CLICK_WOOCOMMERCE'][$key])) {//Установка
            $_SESSION['BUY_ONE_CLICK_WOOCOMMERCE'][$key] = time();
        } else {
            if (($_SESSION['BUY_ONE_CLICK_WOOCOMMERCE'][$key] + $limit_time) > time()) {
                throw new Exception($limit_message, 200);
            } else {
                $_SESSION['BUY_ONE_CLICK_WOOCOMMERCE'][$key] = time();
            }
        }
        //
    }

    /**
     * Функция выполняется после нажатия на кнопку в форме заказа
     */
    public static function ajaxBuyButtonForm() {



        $arResult = array();
        $textjson = $_POST['form']; //Весь запрос
        // echo '<pre>';print_r($textjson);echo '</pre>';die();
        $arForm = array(); //данные формы
        if (isset($textjson['form']) && !empty($textjson['form'])) {
            $arForm = $textjson['form']; //Данные полей формы
        }
        if (BuyCore::$variation) {
            if ($variation = BuyVariationClass::getVariableProductInfo($arForm)) {
                $strVariation = '<br>' . $variation;
            }
        }

        if (empty($textjson)) {
            wp_send_json_error(array('message' =>  __('request error', 'coderun-oneclickwoo')));
        }

        try {
            self::checkRequireField($textjson);
        } catch (Exception $ex) {
            wp_send_json_error(array('message' => $ex->getMessage()));
        }


        if (!empty($textjson['txtname'])) {
            $txtname = wp_specialchars_decode(esc_html($textjson['txtname']), ENT_QUOTES);
        } else {
            $txtname = '';
        }
        if (!empty($textjson['txtphone'])) {
            $txtphone = $textjson['txtphone'];
        } else {
            $txtphone = '';
        }
        if (isset($textjson['txtemail']) and ! empty($textjson['txtemail'])) {
            $txtemail = sanitize_email($textjson['txtemail']);
        } else {
            $txtemail = '';
        }
        $nametovar = $textjson['nametovar'];
        if (BuyCore::$variation) {
            $nametovar .= $strVariation;
        }
        $pricetovar = $textjson['pricetovar'];
        $idtovar = intval($textjson['idtovar']);
        if ($idtovar < 1) {
            $arResult['message'] = __('An error has occurred! Order not formed', 'coderun-oneclickwoo');
            wp_send_json_error($arResult);
        }
        try {
            self::checkLimitSendForm($idtovar); //Проверка на частоту нажатий
        } catch (Exception $ex) {
            wp_send_json_error(array('message' => $ex->getMessage()));
        }


        if (isset($textjson['message']) and ! empty($textjson['message'])) {
            $dopinfo = wp_specialchars_decode(esc_html($textjson['message']), ENT_QUOTES);
        } else {
            $dopinfo = '';
        }
        $linktovar = '<a href="' . get_the_permalink($idtovar) . '" target="_blank"><span class="glyphicon glyphicon-share"></span></a>';

        $infotovar_old = get_option('buyzakaz');

        $time = current_time('mysql');
        $status = '1'; //По умолчанию статус - новый
        $smslog = ''; //Лог смс
        //В таблицу Woo
        if (isset(BuyCore::$buyoptions['add_tableorder_woo']) and $textjson['custom'] == 0) {
            $blockNull = ''; //Заглушка
            $order = wc_create_order(); //создаём новый заказ
//Записываем в массив данные о доставке заказа и данные клиента
            $address = array(
                'first_name' => (!empty($txtname) ? $txtname : ''),
                'last_name' => (!empty($txtname) ? $txtname : ''),
                'company' => (!empty($txtname) ? $txtname : ''),
                'email' => (!empty($txtemail) ? $txtemail : ''),
                'phone' => (!empty($txtphone) ? $txtphone : ''),
                'address_1' => (!empty($dopinfo) ? $dopinfo : ''),
                'address_2' => (!empty($blockNull) ? $blockNull : ''),
                'city' => (!empty($blockNull) ? $blockNull : ''),
                'state' => (!empty($blockNull) ? $blockNull : ''),
                'postcode' => (!empty($blockNull) ? $blockNull : ''),
                'country' => (!empty($blockNull) ? $blockNull : ''),
            );
            $order->add_product(wc_get_product($idtovar), 1); //Добавляем в заказ товары: 99-ID товара, 1-количество
            $order->set_address($address, 'billing'); //Добавляем данные о доставке
            $order->set_address($address, 'shipping'); // и оплате
            $order->update_status('processing'); //Статус заказа изменяем
            $order->calculate_totals(); //подбиваем сумму и видим что наш заказ появился в админке
        }
        //---таблица Woo
        if (isset(BuyCore::$buynotification['namemag'])) {
            $namemag = BuyCore::$buynotification['namemag'];
        } else {
            $namemag = '';
        }
        if (isset(BuyCore::$buynotification['dopiczakaz'])) {
            $dopiczakaz = BuyCore::$buynotification['dopiczakaz'];
        } else {
            $dopiczakaz = '';
        }
        if (isset(BuyCore::$buyoptions['success_action'])) { // опции "действия после нажатия кнопки в форме"
            if (!empty(BuyCore::$buyoptions['success_action_close'])) {
                $success_time = BuyCore::$buyoptions['success_action_close']; // 2 Закрытие формы через мсек
            }
            if (!empty(BuyCore::$buyoptions['success_action_message'])) {
                $success_message = BuyCore::$buyoptions['success_action_message']; // 3 Сообщение после нажатия кнопки в форме
            }
            if (!empty(BuyCore::$buyoptions['success_action_redirect'])) {
                $success_redirect = BuyCore::$buyoptions['success_action_redirect']; // 4  Редирект на страницу после нажатия на кнопку в форме
            }
            switch (BuyCore::$buyoptions['success_action']) {
                case 1: $success_action = 'no'; //Ни чего не делать, пользователь сам закроет форму
                    $num = 1;
                    break;
                case 2: $success_action = $success_time;
                    $num = 2;
                    break;
                case 3: $success_action = $success_message;
                    $num = 3;
                    break;
                case 4: $success_action = $success_redirect;
                    $num = 4;
                    break;
                default: $success_action = 'no';
                    $num = 2; //Ни чего не делать, пользователь сам закроет форму
            }
        } //конец IF действий после нажатия кнопки в форме
        $message = array(
            'time' => $time,
            'url' => '<a href="' . get_the_permalink($idtovar) . '" target="_blank">'. __('Look', 'coderun-oneclickwoo').'</a>',
            'price' => $pricetovar,
            'nametov' => $nametovar,
            'namemag' => $namemag,
            'dopinfo' => $dopiczakaz,
            'fon' => $txtphone,
            'fio' => $txtname,
            'form' => $arForm,
            'dop_pole' => $dopinfo,
            'email' => $txtemail,
        );
        if (!empty($txtemail) and ! empty(BuyCore::$buynotification['infozakaz_chek'])) {
            BuyFunction::BuyEmailNotification($txtemail, BuyCore::$buynotification['namemag'], $message);
        }
        if (!empty(BuyCore::$buynotification['emailbbc'])) {
            BuyFunction::BuyEmailNotification(BuyCore::$buynotification['emailbbc'], BuyCore::$buynotification['namemag'], $message);
        }
        //Отправка СМС клиенту
        if (!empty(BuyCore::$buysmscoptions['enable_smsc'])) {
            $smsmessage = array(
                'fon' => $txtphone,
                'fio' => $txtname,
                'txtemail' => $txtemail,
                'dopinfo' => $dopiczakaz,
                'price' => $pricetovar,
                'nametov' => $nametovar
            );
            $sms = new BuySMSC();
            $smslog = $sms->send_sms(trim($smsmessage['fon']), BuyFunction::composeSms(BuyCore::$buysmscoptions['smshablon'], $smsmessage));
            ///Переписать функцию sms? помнить про static
        }
        //Отправка СМС продавцу
        if (!empty(BuyCore::$buysmscoptions['enable_smsc_saller'])) {
            $smsmessage = array(
                'fon' => $txtphone,
                'fio' => $txtname,
                'txtemail' => $txtemail,
                'dopinfo' => $dopiczakaz,
                'price' => $pricetovar,
                'nametov' => $nametovar
            );
            $sms2 = new BuySMSC();
            $smslog = $sms2->send_sms(trim(BuyCore::$buysmscoptions['phone_saller']), BuyFunction::composeSms(BuyCore::$buysmscoptions['smshablon_saller'], $smsmessage));
        }
        //Журналирование

        $infotovar_temp = array('time' => $time, 'idtovar' => $idtovar, 'txtname' => $txtname, 'txtphone' => $txtphone,
            'txtemail' => $txtemail, 'nametovar' => $nametovar, 'pricetovar' => $pricetovar, 'message' => $dopinfo, 'status' => $status, 'linktovar' => $linktovar, 'smslog' => $smslog
        );

        $infotovar_new = $infotovar_old;
        array_push($infotovar_new, $infotovar_temp);
        update_option('buyzakaz', $infotovar_new);

        //Конец журналирования
        ob_end_clean();
        $arResult['message'] =__('The order has been sent','coderun-oneclickwoo');
        $arResult['result'] = BuyCore::$buyoptions['success'];
        $arResult['num'] = $num;
        $arResult['action'] = $success_action;

        BuyHookPlugin::buyClickNewrder($arResult, $infotovar_temp);

        echo wp_send_json_success($arResult);
    }

    /**
     * Функция удаляет элемент заказа из таблицы
     * Данные отправляются из файла admin_order.js
     */
    public function ajaxRemoveOrderId() {
        $id = $_POST['text'];
        $infotovar_old = get_option('buyzakaz');
        unset($infotovar_old[$id]);
        $infotovar_new = $infotovar_old;
        update_option('buyzakaz', $infotovar_new);
        die();
    }

    /**
     * Функция удаляет всю таблицу заказов
     * Данные отправляются из файла admin_order.js
     */
    public function ajaxRemoveOrderAll() {
        $nonce = $_POST['nonce']; // Массив URL и NONCE
        ob_end_clean();
        if (wp_verify_nonce($nonce['nonce'], 'superKey')) {
            update_option('buyzakaz', array());
            wp_die('ok');
        } else {
            wp_die(__('Are you a hacker?','coderun-oneclickwoo'));
        }
    }

    /**
     * Функция Изменения статуса заказа
     * Данные отправляются из файла admin_order.js
     */
    public function ajaxStatusOrderId() {
        $text = $_POST['text'];
        $id = $text['id'];
        $infotovar_old = get_option('buyzakaz');
        $infotovar_old[$id]['status'] = $text['status'];
        $infotovar_new = $infotovar_old;
        update_option('buyzakaz', $infotovar_new);

        die();
    }

    public static function add_to_cart() {
        $productid = intval($_POST['productid']);

        if (!function_exists('WC')) {
            echo get_home_url();
            die();
        }


        WC()->cart->add_to_cart($productid);

        $url = get_permalink(get_option('woocommerce_checkout_page_id'));

        echo $url;

        die();
    }

    /**
     * Возвращает форму для быстрого заказа
     */
    public static function ajaxgetViewForm() {
        $url = $_POST['urlpost'];
        $productid = $_POST['productid'];
        $cartinfo = BuyFunction::BuyInfoCart($productid, $url);
        $cartinfo['custom'] = 0;
        // ob_end_clean();
        echo BuyFunction::viewBuyForm($cartinfo);
        die();
    }

    public static function ajaxgetViewFormCustom() {
        $url = $_POST['urlpost'];
        $productid = $_POST['productid'];
        $name = $_POST['name'];
        $count = $_POST['count'];
        $price = $_POST['price'];
        $arProduct = array(
            'article' => $productid,
            'name' => $name,
            'imageurl' => '',
            'amount' => $price,
            'quantity' => $count,
            'custom' => 1,
        );
        //ob_end_clean();
        echo BuyFunction::viewBuyForm($arProduct);
        die();
    }

}
