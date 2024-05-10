<?php

/*
  Plugin Name: SeoSiti Framework
  Plugin URI:
  Description: Framework per l'utilizzo dei prodotti SeoSiti
  Version: 1.1.1
  Author: SeoSiti Developing Team
  Author URI: https://www.seositimarketing.it/
  License: GPLv2 or later
 */


/*** SEO SITI FRAMEWORK DEVE ESSERE CARICATO PRIMA DEGLI ALTRI PLUGIN DI SEOSITI ***/
add_action( 'activated_plugin', 'my_plugin_load_first' );
function my_plugin_load_first(){
    $path = str_replace( WP_PLUGIN_DIR . '/', '', __FILE__ );
    if($plugins = get_option( 'active_plugins' )){
        if ($key = array_search($path, $plugins)) {
            array_splice($plugins, $key, 1);
            array_unshift($plugins, $path);
            update_option('active_plugins', $plugins);
        }
    }
}

loadSeoSitiFramework();
function loadSeoSitiFramework(){
    //inclusione librerie
    require_once 'assets/definizioni.php';
    require_once 'assets/api_db.php';

    //includo le interfacce
    require_once 'assets/interfaces/InterfaceDAO.php';
    require_once 'assets/interfaces/InterfaceController.php';
    require_once 'assets/interfaces/InterfaceView.php';

    //includo le classi
    require_once 'assets/classes/classes.php';

    //includo altre funzionalità
    require_once 'assets/functions.php';

    define('PATH_SEOSITI', plugin_dir_path(__FILE__));


    //INSERIMENTO CSS E JS
    function register_public_seositiframework_css(){
        //wp_register_style('seositiframework_bootstrap-style', plugins_url('css/bootstrap.min.css', __FILE__) ); //file interno
        wp_register_style('seositiframework_bootstrap-style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css' ); //CDN
        wp_register_style('seositiframework_bootstrap-docs', 'https://getbootstrap.com/docs/5.3/assets/css/docs.css' ); //CDN
        wp_register_style('seositiframework_bootstrap-datatable', 'https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css' ); //CDN

        wp_register_style('seositiframework_file-input', plugins_url('css/fileinput.min.css', __FILE__) );
        wp_register_style('seositiframework_multiple', plugins_url('css/multiple-select.css', __FILE__) );
        wp_register_style('seositiframework_chosen', plugins_url('css/chosen.css', __FILE__) );
        wp_register_style('seositiframework_datepicker', plugins_url('css/bootstrap-datepicker.min.css', __FILE__) );
        wp_register_style('seositiframework_styles', plugins_url('css/styles.css', __FILE__));


        wp_enqueue_style('seositiframework_bootstrap-style');
        wp_enqueue_style('seositiframework_bootstrap-docs');
        wp_enqueue_style('seositiframework_bootstrap-datatable');

        wp_enqueue_style('seositiframework_file-input');
        wp_enqueue_style('seositiframework_multiple');
        wp_enqueue_style('seositiframework_chosen');
        wp_enqueue_style('seositiframework_datepicker');
        wp_enqueue_style('seositiframework_styles');

    }
    add_action('wp_enqueue_scripts', 'register_public_seositiframework_css');

    function register_public_seositiframework_js(){
        //wp_register_script('autocomplete-js', plugins_url('seositi-framework/js/jquery.autocomplete-min.js'), array('jquery'), '1.0', false);   
        //wp_register_script('jquery', plugins_url('seositi-framework/js/jquery-2.0.3.min.js'), array('jquery'), '1.0', false);
        wp_register_script('ui-widget-js', plugins_url('seositi-framework/js/jquery-ui.min.js'), array('jquery'), '1.0', false);       
        wp_register_script('file-input', plugins_url('seositi-framework/js/fileinput.min.js'), array('jquery'), '1.0', false); 
        wp_register_script('livequery', plugins_url('seositi-framework/js/jquery.livequery.js'), array('jquery'), '1.0', false);       
        wp_register_script('scripts', plugins_url('seositi-framework/js/scripts.js'), array('jquery'), '1.0', false);   
        wp_register_script('multiple-select', plugins_url('seositi-framework/js/multiple-select.js'), array('jquery'), '1.0', false);
        wp_register_script('chosen', plugins_url('seositi-framework/js/chosen.jquery.min.js'), array('jquery'), '1.0', false); 
        //wp_register_script('bootstrap-min', plugins_url('seositi-framework/js/bootstrap.min.js'), array('jquery'), '1.0', false); //file interno
        wp_register_script('bootstrap-min', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js', array('jquery'), '1.0', false); //CDN
        wp_register_script('datepicker', plugins_url('seositi-framework/js/bootstrap-datepicker.min.js'), array('jquery'), '1.0', false); 
        wp_register_script('datepicker-it', plugins_url('seositi-framework/js/bootstrap-datepicker.it.min.js'), array('jquery'), '1.0', false);     

        wp_register_script('bootstrap-datatable', 'https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js', array('jquery'), '1.0', false); //CDN

        //wp_enqueue_script('autocomplete-js');  
        //wp_enqueue_script('jquery'); 
        //wp_enqueue_script('ui-widget-js'); 
        //wp_enqueue_script('file-input'); 
        //wp_enqueue_script('livequery');


        wp_enqueue_script('multiple-select'); 
        //wp_enqueue_script('chosen');
        wp_enqueue_script('bootstrap-min');
        wp_enqueue_script('bootstrap-datatable');
        //wp_enqueue_script('datepicker');
        //wp_enqueue_script('datepicker-it');    

        wp_enqueue_script('scripts'); 

        //chiamate ajax
        /*
        wp_localize_script('script', 'myscript', array(
                'ajax_url'  => admin_url('admin-ajax.php'),
                'nonce'     => wp_create_nonce('zecchini-etichette')
        ));
        */

    }
    add_action( 'wp_enqueue_scripts', 'register_public_seositiframework_js' );
    
    function addFA() {
        echo '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous" />';
    }
    add_action('admin_enqueue_scripts', 'addFA');    
}


