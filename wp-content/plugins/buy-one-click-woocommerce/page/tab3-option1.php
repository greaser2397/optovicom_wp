<?php
if (!defined('ABSPATH')) {
    exit;
}
use Coderun\BuyOneClick\BuyCore;
use Coderun\BuyOneClick\Help;
?>
<h3><?php _e('Orders via plugin', 'coderun-oneclickwoo'); ?> <?php echo BuyCore::NAME_PLUGIN; ?></h3>
<p><?php _e('All orders sent via the button', 'coderun-oneclickwoo'); ?> "<?php
    if (isset(BuyCore::$buyoptions['namebutton'])) {
        echo BuyCore::$buyoptions['namebutton'];
    }
    ?>"</p>
<?php
?>
<input type="button" class="btn btn-default btn-sm removeallorder" value="<?php _e('Delete history', 'coderun-oneclickwoo'); ?>"/>
<?php
$url_tab = add_query_arg(array('page' => BuyCore::URL_SUB_MENU, 'tab' => 'orders'), 'admin.php');

?>
<table class="table table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <th>№ </th>
        <th><?php _e('Date and time of addition', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Item Number', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Full name', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Phone', 'coderun-oneclickwoo'); ?></th>
        <th>Email</th>
        <th><?php _e('Product Name', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Price', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Message', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Product', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('SMS', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Status', 'coderun-oneclickwoo'); ?></th>
        <th><?php _e('Remove', 'coderun-oneclickwoo'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach (BuyCore::$buyzakaz as $id => $zakaz) { ?>
        <tr class="success order<?php echo $id; ?>">
            <th>
                <?php
                echo '<br>'.__('Plugin Order №', 'coderun-oneclickwoo').": {$id}";
                ?>
                <?php
                if(!empty($zakaz['woo_order_id'])) {
                    echo '<br>'.__('Woo Order №', 'coderun-oneclickwoo').": <a href='/wp-admin/post.php?post={$zakaz['woo_order_id']}&action=edit'>{$zakaz['woo_order_id']}</a>";
                }
                ?>
            </th>
            <th><?php echo $zakaz['time']; ?></th>
            <th><?php echo $zakaz['idtovar']; ?></th>
            <th><?php echo $zakaz['txtname']; ?></th>
            <th><?php echo $zakaz['txtphone']; ?></th>
            <th><?php echo $zakaz['txtemail']; ?></th>
            <th><?php echo $zakaz['nametovar']; ?></th>
            <th><?php echo $zakaz['pricetovar']; ?></th>
            <th><?php echo $zakaz['message']; ?></th>
            <th><?php echo $zakaz['linktovar']; ?></th>
            <th><?php
                if (!empty($zakaz['smslog']) && is_array($zakaz['smslog'])) {
                    echo 'id:' . $zakaz['smslog'][0] . '</br>' . __('Count.sms', 'coderun-oneclickwoo') . ':' . $zakaz['smslog'][1] . '</br>' . __('Cost of', 'coderun-oneclickwoo') . ':' . $zakaz['smslog'][2] . '</br>' . __('Balance', 'coderun-oneclickwoo') . ':' . $zakaz['smslog'][3];
                }
                ?></th>
            <th><a orderstat="<?php
                if ($zakaz['status'] == 2) {
                    echo '2';
                } else {
                    echo '1';
                }
                ?>" class="updatestatus" id="<?php echo $id; ?>" href="<?php echo $url_tab . '#id=' . $id; ?>">
                    <?php
                    if ($zakaz['status'] == 1) {
                        echo '<span class="glyphicon glyphicon-ban-circle">' . __('NOT', 'coderun-oneclickwoo') . '</span>';
                    } else {
                        echo '<span class="glyphicon glyphicon-ok-circle">' . __('OK', 'coderun-oneclickwoo') . '</span>';
                    }
                    ?>



                </a>
            </th>

            <th>
                <a class="removeorder" id="<?php echo $id ?>" href="<?php echo $url_tab; ?>#id=<?php echo $id; ?>">
                    <span class="glyphicon glyphicon-remove-circle"><?php _e('OnlyPlugin', 'coderun-oneclickwoo'); ?></span>
                </a>
                <?php if(!empty($zakaz['woo_order_id']) && Help::getInstance()->isset_woo_order($zakaz['woo_order_id'])) { ?>
                    <br><br>
                    <a class="removeorder_woo" data-plugin_id="<?php echo $id; ?>" data-woo_id="<?php echo $zakaz['woo_order_id'] ?>" href="<?php echo $url_tab; ?>#id=<?php echo $zakaz['woo_order_id']; ?>">
                        <span class="glyphicon glyphicon-remove-circle"><?php _e('OnlyWoo', 'coderun-oneclickwoo'); ?></span>
                    </a>
                <?php } ?>
            </th>
        </tr>
    <?php } ?>
    </tbody>



</table>