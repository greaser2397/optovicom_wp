<?php
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Некоторый функционал плагина
 * 
 */
class BuyFunction {

    /**
     * Собирает тело сообщения SMS
     * @param string $options Текст смс сообщения
     * @param array $data Массив данных для замены
     * 
     */
    static public function composeSms($options, $data) {
        //Тэги замены
        $template = array(
            '%FIO%' => $data['fio'],
            '%FON%' => $data['fon'],
            '%EMAIL%' => $data['txtemail'],
            '%DOPINFO%' => $data['dopinfo'],
            '%TPRICE%' => $data['price'],
            '%TNAME%' => $data['nametov']
        );
        $return_text = strtr($options, $template);
        return $return_text;
    }

    /**
     * Дополнительное сообщение
     */
    public static function viewBuyMessage() {
        if (!empty(BuyCore::$buyoptions['success_action'])) {
            ?>
            <div class = "overlay_message" title = "<?php _e('Notification', 'coderun-oneclickwoo'); ?>"></div>
            <div class = "popummessage">
                <div class="close_message">x</div>
                <?php echo BuyCore::$buyoptions['success_action_message']; ?>
            </div>
            <?php
        }
    }

    /**
     * Форма для быстрого заказа
     */
    static function viewBuyForm($cartinfo) {
        ob_start();
        $idtovar = $cartinfo['article']; //Номер товара или страницы WP
        $nametovar = $cartinfo['name']; // Название товара или title страницы
        $pricetovar = $cartinfo['amount']; // Цена
        if (!empty($cartinfo['custom'])) {
            $custom = 1;
        } else {
            $custom = 0;
        }
        if (!empty($cartinfo['imageurl'])) {
            $imagetovar = '<img src="' . $cartinfo['imageurl'] . '" width="80" height="80">'; // Изображение
        } else {
            $imagetovar = '';
        }
        ?>
        <div id="formOrderOneClick">
            <?php
            if (BuyCore::$variation) {
                BuyVariationClass::viewJs();
            }
            ?>
            <div class="overlay" title="окно"></div>
            <div class="popup">
                <div class="close_order <?php echo (self::is_template_style() ? 'button' : '') ?>">x</div>
                <form class="b1c-form" method="post" action="#" id="buyoneclick_form_order">
                    <h2><?php echo BuyCore::$buyoptions['namebutton']; ?></h2>
                    <?php if (!empty(BuyCore::$buyoptions['infotovar_chek'])) { ?>
                        <table>
                            <tr valign="top">
                                <th scope="row"><?php _e('Name', 'coderun-oneclickwoo'); ?></th>
                                <td>
                                    <span class="description"><?php _e('Price', 'coderun-oneclickwoo'); ?></span>
                                </td>
                                <?php if (!empty($cartinfo['imageurl'])) { ?>
                                    <td>
                                        <span class="description"><?php _e('Picture', 'coderun-oneclickwoo'); ?></span>
                                    </td>
                                <?php } ?>
                            </tr>
                            <tr valign="top">
                                <th scope="row"><?php echo $nametovar; ?></th>
                                <td>
                                    <span class="description"><?php echo $pricetovar; ?></span>
                                </td>
                                <?php if (!empty($imagetovar)) { ?>
                                    <td>
                                        <span class="description"><?php echo $imagetovar; ?></span>
                                    </td>
                                <?php } ?>
                            </tr>
                        </table>
                    <?php } ?>

                    <?php if (!empty(BuyCore::$buyoptions['fio_chek'])) { ?>
                        <input class="buyvalide <?php echo (self::is_template_style() ? 'input-text' : '') ?>" type="text" <?php
                        if (!empty(BuyCore::$buyoptions['fio_verifi'])) {
                            // echo 'required';
                        }
                        ?> placeholder="<?php echo BuyCore::$buyoptions['fio_descript']; ?>" name="txtname">	
                           <?php } ?>
                           <?php if (!empty(BuyCore::$buyoptions['fon_chek'])) { ?>
                        <input class="buyvalide <?php echo (self::is_template_style() ? 'input-text' : '') ?> " type="tel" <?php
                        if (!empty(BuyCore::$buyoptions['fon_verifi'])) {
                            //echo 'required';
                        }
                        ?> placeholder="<?php echo BuyCore::$buyoptions['fon_descript']; ?>" name="txtphone">
                        <p class="phoneFormat"><?php
                            if (!empty(BuyCore::$buyoptions['fon_format'])) {
                                echo __('Format', 'coderun-oneclickwoo') . ' ' . BuyCore::$buyoptions['fon_format'];
                            }
                            ?></p>
                    <?php } ?>
                    <?php if (!empty(BuyCore::$buyoptions['email_chek'])) { ?>
                        <input class="buyvalide <?php echo (self::is_template_style() ? 'input-text' : '') ?> " type="email" <?php
                        if (!empty(BuyCore::$buyoptions['email_verifi'])) {
                            //echo 'required';
                        }
                        ?> placeholder="<?php echo BuyCore::$buyoptions['email_descript']; ?>" name="txtemail">
                           <?php } ?>
                           <?php if (!empty(BuyCore::$buyoptions['dopik_chek'])) { ?>
                        <textarea class="buymessage buyvalide" <?php
                        if (!empty(BuyCore::$buyoptions['dopik_verifi'])) {
                            //echo 'required';
                        }
                        ?> name="message" placeholder="<?php echo BuyCore::$buyoptions['dopik_descript']; ?>" rows="2" value=""></textarea>
                              <?php } ?>

                    <?php if (!empty(BuyCore::$buyoptions['conset_personal_data_enabled'])) { ?>
                        <p>
                            <input type="checkbox" name="conset_personal_data">
                            <?php echo BuyCore::$buyoptions['conset_personal_data_text']; ?>
                        </p>
                    <?php } ?>

                    <input type="hidden" name="buy_nametovar" value="<?php echo $nametovar; ?>" />
                    <input type="hidden" name="buy_pricetovar" value="<?php echo $pricetovar; ?>" />
                    <input type="hidden" name="buy_idtovar" value="<?php echo $idtovar; ?>" /> 

                    <?php wp_nonce_field(); ?>
                    <p class="form-message-result"></p>
                    <input type="submit" data-custom="<?php echo $custom; ?>" class="button alt buyButtonOkForm" value="<?php echo BuyCore::$buyoptions['butform_descript']; ?>" name="btnsend">
                </form>
            </div>
            <?php self::viewBuyMessage(); ?>
        </div>
        <?php
        $form = ob_get_contents();
        ob_end_clean();

        return $form;
    }

    /**
     * HTML форма кнопки "Заказать в один клик"
     */
    static function viewBuyButton($short_code = false) {
        $page = '';
        if (!empty(BuyCore::$buyoptions['positionbutton'])) {
            global $product, $woocommerce_loop;

            $name = self::get_button_name();

            ob_start();
            ?>

            <button class="clickBuyButton button21 button alt" data-productid="<?= $product->get_id(); ?>"><?php echo $name; ?></button>

            <?php
            $page = ob_get_contents();
            ob_end_clean();
        }

        if ($short_code) {
            return $page;
        } else {
            echo $page;
        }
    }

    /**
     * HTML форма кнопки "Заказать в один клик" для произвольного способа заказа
     */
    static function viewBuyButtonCustrom($arParams) {
        $page = '';
        if (!empty(BuyCore::$buyoptions['namebutton']) and ! empty(BuyCore::$buyoptions['positionbutton'])) {
            ob_start();
            ?>

            <button class="clickBuyButtonCustom button21 button alt" href="#" data-productid="<?php echo $arParams['id']; ?>" data-name="<?php echo $arParams['name']; ?>" data-count="<?php echo $arParams['count']; ?>" data-price="<?php echo $arParams['price']; ?>"><?php echo BuyCore::$buyoptions['namebutton']; ?></button>

            <?php
            $page = ob_get_contents();
            ob_end_clean();
        }

        return $page;
    }

    protected static function is_template_style() {
        if (isset(BuyCore::$buyoptions['form_style_color']) && BuyCore::$buyoptions['form_style_color'] == '6') {
            return true;
        }
        return false;
    }

    /**
     * Собираем информацию о товаре, для формы
     * Этот вариант кнопки расположен в карточке товара или в категории и подразумевает заказ 1й еденицы
     * товара (покупка в один клик, минуя корзину)
     * @return array 'article' - код товара, 'name'-наименование,'imageurl'-url картинки,'amount'-цена,
     * 'quantity' -количество
     */
    static function BuyInfoCart($productid, $urlpost) {
        $urlpost = ''; //устарела
        //global $post; // Что бы получать данные о посте Wordpress
        //$postid = url_to_postid($urlpost);
        // if (empty($postid)) {
        $postid = $productid;
        //}
        //$product_id = $post->ID; //ID продукта (ID поста Wordpress)
        $product_id = $postid; //ID продукта (ID поста Wordpress)
        $product = new WC_Product($product_id); // Класс Woo для работы с товаром
        if (method_exists($product, 'get_image_id')) {


            $article = $product_id; //Код товара по классификации Wordpress (ID продукта)
            $name = $product->get_post_data()->post_title; //Название товара
            $imageurl = wp_get_attachment_image_src($product->get_image_id()); //Урл картинки товара
            $amount = $product->get_price(); //Цена товара
            $quantity = '1'; //Количество товаров - не использую
            //Данные о товаре
            $datacart = array(
                'article' => $article,
                'name' => $name,
                'imageurl' => $imageurl[0],
                'amount' => $amount,
                'quantity' => $quantity
            );

            return $datacart;
        } else {
            return FALSE;
        }
    }

    /**
     * Отправка Email через функцию PHP mail
     */
    static function BuyEmailNotification($to, $subject, $message) {

        $namemag = $message['namemag'];
        $date = $message['time'];
        $urltovar = $message['url'];
        $price = $message['price'];
        $nametovar = $message['nametov'];
        $dopinfo = $message['dopinfo'];
        $fon = $message['fon'];
        $fio = $message['fio'];
        $email = $message['email'];
        $dop_pole = $message['dop_pole'];
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8 \r\n";
        $headers .= "From: " . $namemag . " <" . BuyCore::$buynotification['emailfrom'] . ">\r\n";
//Функция Wordpress иногда ломается, можно использовать просто mail
        wp_mail($to, $subject, self::htmlEmailTemplate($namemag, $date, $urltovar, $price, $nametovar, $dopinfo, $fon, $fio, $dop_pole, $email), $headers);
    }

    protected static function get_button_name() {
        global $product;

        $default_name = __('Buy on click', 'coderun-oneclickwoo');

        if (!isset(BuyCore::$buyoptions['namebutton'])) {
            return $default_name;
        }

        $name = null;

        $default_name = BuyCore::$buyoptions['namebutton'];

        if (isset(BuyCore::$buyoptions['woo_stock_status_button_text'])) {
            $name = BuyCore::$buyoptions['woo_stock_status_button_text'];
        }

        if (!is_object($product) || empty($product->get_id()) || empty(BuyCore::$buyoptions['woo_stock_status_enable'])) {
            return $default_name;
        }

        $stock_status = get_post_meta($product->get_id(), '_stock_status', true);
        //outofstock - нет в наличие
        //instock - в наличие
        //onbackorder - в не выполненом заказе
        if ($stock_status === 'outofstock') {
            return $name;
        }
        return $default_name;
    }

    /**
     * Шаблон emial сообщения
     */
    static function htmlEmailTemplate($namemag, $date, $urltovar, $price, $nametovar, $dopinfo, $fon, $fio, $dop_pole, $email) {
        $message = ' 
<table style="height: 255px; border-color: #1b0dd9;" border="2" width="579">
<tbody>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;" colspan="2">' . $namemag . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;"> ' . __('Date', 'coderun-oneclickwoo') . ': </td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $date . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . __('Link to the product', 'coderun-oneclickwoo') . ': </td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $urltovar . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;"> ' . __('Price', 'coderun-oneclickwoo') . ': </td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $price . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . __('Name', 'coderun-oneclickwoo') . '</td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $nametovar . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . __('Email', 'coderun-oneclickwoo') . '</td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $email . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . __('Phone number', 'coderun-oneclickwoo') . '</td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $fon . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . __('Customer', 'coderun-oneclickwoo') . '</td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;">' . $fio . '</td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;"> ' . __('Additionally', 'coderun-oneclickwoo') . ' </td>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;"> ' . $dop_pole . ' </td>
</tr>
<tr>
<td style="border-color: #132cba; text-align: center; vertical-align: middle;" colspan="2">' . $dopinfo . '</td>
</tr>
</tbody>
</table>
&nbsp;
        ';
        return $message;
    }

}
