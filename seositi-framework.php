<?php

/**
 * Plugin Name: SeoSiti Framework
 * Plugin URI:
 * Description: Framework per l'utilizzo dei prodotti SeoSiti (v2.0 Refactored)
 * Version: 2.0.0
 * Author: SeoSiti Developing Team
 * Author URI: https://www.seositimarketing.it/
 * License: GPLv2 or later
 *
 * @package SeoSitiFramework
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Costante per il path assoluto del plugin
define('SSF_PATH', plugin_dir_path(__FILE__));
define('SSF_URL', plugin_dir_url(__FILE__));

// Inclusione librerie (path assoluti)
require_once SSF_PATH . 'assets/definizioni.php';
require_once SSF_PATH . 'assets/api_db.php';

// Includo le interfacce
require_once SSF_PATH . 'assets/interfaces/InterfaceDAO.php';
require_once SSF_PATH . 'assets/interfaces/InterfaceController.php';
require_once SSF_PATH . 'assets/interfaces/InterfaceView.php';

// Includo le classi
require_once SSF_PATH . 'assets/classes/MyObject.php';
require_once SSF_PATH . 'assets/classes/ObjectDAO.php';
require_once SSF_PATH . 'assets/classes/PrinterView.php';

// Includo altre funzionalità
require_once SSF_PATH . 'assets/functions.php';

/**
 * Registrazione e caricamento CSS/JS.
 *
 * Vengono caricati solo se il filtro `ssf_load_assets` ritorna true.
 * I plugin child possono attivare il caricamento dove necessario usando:
 * add_filter('ssf_load_assets', '__return_true');
 */
function ssf_register_public_assets()
{
    // Registrazione Stili
    wp_register_style('ssf_bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css', array(), '5.3.0');
    wp_register_style('ssf_bootstrap_docs', 'https://getbootstrap.com/docs/5.3/assets/css/docs.css', array(), '5.3.0');
    wp_register_style('ssf_datatable', 'https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css', array(), '1.13.2');

    wp_register_style('ssf_file_input', SSF_URL . 'css/fileinput.min.css', array(), '1.0');
    wp_register_style('ssf_multiple', SSF_URL . 'css/multiple-select.css', array(), '1.0');
    wp_register_style('ssf_chosen', SSF_URL . 'css/chosen.css', array(), '1.0');
    wp_register_style('ssf_datepicker', SSF_URL . 'css/bootstrap-datepicker.min.css', array(), '1.0');
    wp_register_style('ssf_styles', SSF_URL . 'css/styles.css', array(), '2.0');

    // Registrazione Script
    wp_register_script('ssf_ui_widget', SSF_URL . 'js/jquery-ui.min.js', array('jquery'), '1.0', true);
    wp_register_script('ssf_file_input', SSF_URL . 'js/fileinput.min.js', array('jquery'), '1.0', true);
    wp_register_script('ssf_livequery', SSF_URL . 'js/jquery.livequery.js', array('jquery'), '1.0', true);
    wp_register_script('ssf_scripts', SSF_URL . 'js/scripts.js', array('jquery'), '2.0', true);
    wp_register_script('ssf_multiple_select', SSF_URL . 'js/multiple-select.js', array('jquery'), '1.0', true);
    wp_register_script('ssf_chosen', SSF_URL . 'js/chosen.jquery.min.js', array('jquery'), '1.0', true);
    wp_register_script('ssf_bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.0', true);
    wp_register_script('ssf_datepicker', SSF_URL . 'js/bootstrap-datepicker.min.js', array('jquery'), '1.0', true);
    wp_register_script('ssf_datepicker_it', SSF_URL . 'js/bootstrap-datepicker.it.min.js', array('jquery', 'ssf_datepicker'), '1.0', true);
    wp_register_script('ssf_datatable', 'https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js', array('jquery'), '1.13.2', true);

    // Variabili AJAX
    wp_localize_script('ssf_scripts', 'ssf_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('ssf_ajax_nonce')
    ));

    /**
     * Guardia per le performance: se il filtro `ssf_load_assets` non è true,
     * fermo qui l'esecuzione e non includo gli script.
     */
    if (!apply_filters('ssf_load_assets', false)) {
        return;
    }

    // Enqueue
    wp_enqueue_style('ssf_bootstrap');
    wp_enqueue_style('ssf_datatable');
    wp_enqueue_style('ssf_file_input');
    wp_enqueue_style('ssf_multiple');
    wp_enqueue_style('ssf_chosen');
    wp_enqueue_style('ssf_datepicker');
    wp_enqueue_style('ssf_styles');

    wp_enqueue_script('ssf_multiple_select');
    wp_enqueue_script('ssf_chosen');
    wp_enqueue_script('ssf_bootstrap');
    wp_enqueue_script('ssf_datatable');
    wp_enqueue_script('ssf_datepicker');
    wp_enqueue_script('ssf_datepicker_it');
    wp_enqueue_script('ssf_scripts');
}
add_action('wp_enqueue_scripts', 'ssf_register_public_assets');
