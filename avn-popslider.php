<?php
    /**
     * Plugin Name: AVN PopSlider
     * Plugin URI: #
     * Description: Plugin de Pop-Up's e Slides 
     * Version: 1.0
     * Requires at least: 5.6
     * Author: Stephany - Pixemotion
     * License: GPL v2 or later
     * License URI: https://www.gnu.org/licenses/gpl-2.0.html
     * Text Domain: avn-popslider
     * Domain Path: /languages
     */

     /*
        ** Itens de "segurança" **
        1- na index.php deixamos ele vazio ou colocamos algum texto para que os arquivos não sejam listados pela url
        2- check se alguma constante do wp esta definida 
    */
    if(! defined('ABSPATH')){
        exit;
    }

    if(! class_exists('AVN_PopSlider')){
        class AVN_PopSlider{
            function __construct(){
                $this->define_constants(); // referenciando o metodo das contantes 

                add_action('admin_menu', array($this, 'add_menu'));
                add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 999); //enfilera os arquivos
                add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts')); //metodo altera css do admin

                require_once(AVN_POPSLIDER_PATH . 'post-types/class.avn-popslider-cpt.php');  
                $AVN_PopSlider_Post_Type = new AVN_PopSlider_Post_Type(); 
                require_once(AVN_POPSLIDER_PATH . 'class.avn-popslider-opcoes.php');  
                $AVN_PopSlider_Opcoes = new AVN_PopSlider_Opcoes(); 
                require_once(AVN_POPSLIDER_PATH . 'shortcodes/class.avn-popslider-shortcode.php');  
                $AVN_PopSlider_Shortcode = new AVN_PopSlider_Shortcode();                 

            }
    
            public function define_constants(){
                define('AVN_POPSLIDER_PATH', plugin_dir_path(__FILE__)); 
                define('AVN_POPSLIDER_URL', plugin_dir_url(__FILE__)); 
                define('AVN_POPSLIDER_VERSION', '1.0.0'); 
            }

            public static function activate(){
                update_option('riwrite_rules', ''); // limpa os valores no campo riwrite_rules na tabela wp_options
            }

            public static function deactivate(){
                flush_rewrite_rules(); // apaga os valores no campo riwrite_rules na tabela wp_options
                unregister_post_type('avn-popslider');
            }

            public static function uninstall(){
                delete_option('avn_popslider_opcoes');
                
                $posts =  get_posts(
                    array(
                        'post_type' => 'avn-popslider',
                        'number_posts' => -1,
                        'post_status' => 'any'
                    )
                );

                foreach($posts as $post){
                    wp_delete_post($post->ID, true);
                }
            }  
            
            public function add_menu(){
                add_menu_page(
                    'AVN POPSlider Options',
                    'AVN POPSlider',
                    'manage_options',
                    'avn_popslide_admin',
                    array($this, 'avn_popslider_pagconfig'),
                    'dashicons-images-alt2',
                    10
                );

                add_submenu_page(
                    'avn_popslide_admin',
                    'Adicionar novo slide',
                    'Adicionar novo slide',
                    'manage_options',
                    'post-new.php?post_type=avn-popslider',
                    null,
                    null
                );

                add_submenu_page(
                    'avn_popslide_admin',
                    'Ver todos Slides',
                    'Ver todos Slides',
                    'manage_options',
                    'edit.php?post_type=avn-popslider',
                    null,
                    null
                );
            }   

            public function avn_popslider_pagconfig(){
                if(! current_user_can('manage_options')){
                    return;
                }

                if(isset($_GET['settings-updated'])){
                    add_settings_error('avn_popslider_opcoes', 'avn_popslider_message', 'Configurações Salvas', 'success');
                }

                settings_errors('avn_popslider_opcoes');

                require(AVN_POPSLIDER_PATH.'views/config-page.php');
            }

            public function register_scripts(){
                wp_register_script('avn-popslider-main-jq', AVN_POPSLIDER_URL . 'vendor/bootstrap/bootstrap.min.js', array('jquery'), AVN_POPSLIDER_VERSION, true);
                wp_register_style('avn-popslider-main-css', AVN_POPSLIDER_URL . 'vendor/bootstrap/bootstrap.css', array(), AVN_POPSLIDER_VERSION, 'all');
                wp_register_style('avn-popslider-style-css', AVN_POPSLIDER_URL . 'assets/css/style.css', array(), AVN_POPSLIDER_VERSION, 'all');
            }

            public function register_admin_scripts(){
                global $typenow;
                if($typenow == 'avn_popslider'){ //carrega o css somente quando estiver no plugin
                    wp_enqueue_style('avn-popslider-style-css', AVN_POPSLIDER_URL . 'assets/css/adminstyle.css');
                }
            }
        }
    }

    if(class_exists('AVN_PopSlider')){        
        // ganchos de chamada do metodo que ativa, desativa e desinstala o plugin        
        register_activation_hook(__FILE__, array('AVN_PopSlider', 'activate')); 
        register_deactivation_hook(__FILE__, array('AVN_PopSlider', 'deactivate'));
        register_uninstall_hook(__FILE__, array('AVN_PopSlider', 'uninstall')); 
        $avn_popslider = new AVN_PopSlider();
    }