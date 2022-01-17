<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    lupe
 * @subpackage lupe/admin/partials
 */
?>

<?php
if (!defined('ABSPATH')) {
    exit;
}
function lupe_wp_form_page_handler()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'lupe_forms';
    $formID = sanitize_key( $_GET['form_id'] );
    $form_from = get_option( 'admin_email' );
    $query = $wpdb->get_results(
                                "
                                SELECT *
                                FROM $table_name
                                WHERE id = $formID
                                "
                                );

    foreach ( $query as $result )
    {
        $form_name = $result->name;
        $form_subject = $result->form_mail_subject;
        $form_to = $result->form_mail_to;
        $form_from = $result->form_mail_from;
    }
?>
<div class="wrap">
    <div class="col-sm-12 lp_title">
        <h2><?php esc_html_e('Добавить новую форму'); ?>
        <a class="add-new-h2" href="../wp-admin/admin.php?page=lupe-form-builder-list"><?php esc_html_e('Вернуться к списку'); ?></a>
        </h2>
    </div>
            <div class="clearfix"></div>
            <div class="form_builder">
                <form id="lupe" novalidate>
                <div class="row">
                    <div class="col-md-9">
                        <div class="alert hide col-md-12">
                            <h2><?php esc_html_e('Сохранено!'); ?></h2>
                        </div>
                        <div class="col-md-12 lupe-form-name">
                            <input name="form_name" id="form_name" class="form-control lp-form-name" placeholder="Название формы" value="<?php echo $form_name; ?>" required />
                        </div>
                        <div class="col-md-3">
                            <div class="col-md-12 field-panel">
                                <!--<p><?php esc_html_e('Список полей'); ?></p>-->
                                <nav class="nav-sidebar">
                                    <ul id="add-field">
                                        <a id="add-text" data-type="text" href="#">
                                            <li><img src="<?echo plugin_dir_url(__FILE__).'ico/text.png'?>" /><?php esc_html_e('Текст'); ?></li>
                                        </a>
                                        <a id="add-email" data-type="email" href="#">
                                            <li><img src="<?echo plugin_dir_url(__FILE__).'ico/email.png'?>" /><?php esc_html_e('Email', 'lupe-form-builder'); ?></li>
                                        </a>
                                        <a id="add-tel" data-type="tel" href="#">
                                            <li><img src="<?echo plugin_dir_url(__FILE__).'ico/phone.png'?>" /><?php esc_html_e('Телефон'); ?></li>
                                        </a>
                                        <a id="add-date" data-type="date" href="#">
                                            <li><img src="<?echo plugin_dir_url(__FILE__).'ico/date.png'?>" /><?php esc_html_e('Дата'); ?></li>
                                        </a>
                                        <a id="add-time" data-type="time" href="#">
                                            <li><img src="<?echo plugin_dir_url(__FILE__).'ico/time.png'?>" /><?php esc_html_e('Время'); ?></li>
                                        </a>
                                        <a id="add-password" data-type="password" href="#">
                                            <li><img src="<?echo plugin_dir_url(__FILE__).'ico/pass.png'?>" /><?php esc_html_e('Пароль'); ?></li>
                                        </a>
                                        <a id="add-textarea" data-type="textarea" href="#">
                                            <li><img src="<?echo plugin_dir_url(__FILE__).'ico/textarea.png'?>" /><?php esc_html_e('Текстовая область'); ?></li>
                                        </a>
                                        <a id="add-select" data-type="select" href="#">
                                            <li><img src="<?echo plugin_dir_url(__FILE__).'ico/select.png'?>" /><?php esc_html_e('Список'); ?></li>
                                        </a>
                                        <a id="add-radio" data-type="radio" href="#">
                                            <li><img src="<?echo plugin_dir_url(__FILE__).'ico/radio.png'?>" /><?php esc_html_e('Радио кнопка'); ?></li>
                                        </a>
                                        <a id="add-checkbox" data-type="checkbox" href="#">
                                            <li><img src="<?echo plugin_dir_url(__FILE__).'ico/checkbox.png'?>" /><?php esc_html_e('Чекбокс'); ?></li>
                                        </a>
                                        <a id="add-agree" data-type="agree" href="#">
                                            <li><img src="<?echo plugin_dir_url(__FILE__).'ico/agree.png'?>" /><?php esc_html_e('Кнопка подтверждения'); ?></li>
                                        </a>
                                    </ul>
                                </nav>
                            </div>
                        </div>

                        <div class="col-md-9 bal_builder field-panel">
                            <h5><?php esc_html_e('Область создания формы'); ?></h5>
                            <div id="form-fields" class="col-md-12">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">

                        <div class="col-md-12 lp-left-mail-panel lp-left-panel">
                            <div class="form_builder_inside">
                                <h3 class="fancy_title"><?php esc_html_e('Настройка почты'); ?></h3>
                                <?// TODO: Реализовать отправку писем?>
                                <label><?php echo esc_html('Тема письма'); ?></label>
                                <input type="text" name="form_mail_subject" id="form_mail_subject" class="form-control lp-form-mail-subject" placeholder="<?php esc_html_e('Тема письма'); ?>" value="<?php echo $form_subject; ?>" required>

                                <label><?php echo esc_html( __( 'Email получателя письма' ) ); ?></label>
                                <input type="email" name="form_mail_to" id="form_mail_to" class="form-control lp-form-mail-to" placeholder="<?php esc_html_e('Email получателя письма'); ?>" value="<?php echo $form_to; ?>" required>

                                <label><?php echo esc_html( __( 'Email отправителя' ) ); ?></label>
                                <input name="form_mail_from" class="form-control lp-form-mail-from" placeholder="<?php esc_html_e('Email отправителя'); ?>" value="<?php echo $form_from; ?>">
                                <!--<label class="domain-id"><?php echo esc_html( __( "( Please Enter Domain Email Id )", 'lupe-form-builder' ) ); ?></label>-->
                            </div>
                        </div>

                        <div class="col-md-12 lp-left-panel">
                            <div class="form_builder_inside">
                                <button type="submit" id="lupesave_form" class="submit button button-primary button-block button-xl">Сохранить форму</button>
                            </div>
                        </div>
                    </div>
                </div>
         </form>
            </div>
        </div>
<?php
}
?>
