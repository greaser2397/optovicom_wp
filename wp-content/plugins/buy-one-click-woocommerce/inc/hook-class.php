<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * События
 */
class BuyHookPlugin {

    public static function load() {
        
    }

    /**
     * Вызывается после создания нового заказа
     * @param array $arResult результат функции с заказом
     * @param array $arLog лог(журнал плагина)
     */
    public static function buyClickNewrder($arResult, $arLog) {
        do_action('buy_click_new_order', $arResult, $arLog);
    }

}
