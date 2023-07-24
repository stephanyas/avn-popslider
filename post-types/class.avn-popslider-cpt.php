<?php

if( !class_exists ('AVN_PopSlider_Post_Type') ){
    class AVN_PopSlider_Post_Type{
        function __construct(){
            add_action('init', array( $this, 'create_post_type')); //gancho que cria o post type 
            add_action('add_meta_boxes', array( $this, 'add_meta_boxes')); //gancho que cria a metabox
            add_action('save_post', array($this, 'save_post'), 10, 2); // salva na tabela o que é recebido da metabox
            add_filter('manage_avn-popslider_posts_columns', array($this, 'avn_popslider_colunaLink')); // colunas filtraveis
            add_action('manage_avn-popslider_posts_custom_column', array($this, 'avn_popslider_dadosColunaLink'), 10, 2); // adiciona os dados nas colunas filtraveis
            add_filter('manage_edit-avn-popslider_sortable_columns', array($this, 'avn_popslider_ordenaColuna')); // insere a ordenação nas colunas filtraveis
        }

        public function create_post_type(){
            register_post_type(
                'avn-popslider',
                array(
                    'labal' => 'Slider',
                    'description' => 'Sliders',
                    'labels' => array(
                        'name' => 'Sliders',
                        'singular_name' => 'Slider'
                    ),
                    'public' => true, 
                    'supports' => array( 'title', 'thumbnail' ),
                    'hierarchical' => false,
                    'show_ui' => true,
                    'show_in_menu' => false,
                    'menu_position' => 5,
                    'show_in_admin_bar' => false,
                    'show_in_nav_menu' => false,
                    'can_export' => true,
                    'has_archive' => false,
                    'exclude_from_search' => false,
                    'publicly_queryable' => true,
                    'show_in_rest' => false
                    /**
                     * uma forma alternativa de criar a metabox é registrar a meta junto com o post-type (seria + um argumento no array)
                     * register_meta_box_cb => array( $this, 'add_meta_boxes' ) -> recebe uma função/metodo callback que registra a metabox
                    */ 
                )
            );
        }

        public function add_meta_boxes(){
            add_meta_box(
                'avn_popslider_meta_box', 
                'Link Options', 
                array($this, 'add_inner_meta_boxes'), 
                'avn-popslider', 
                'normal', 
                'high'               
            );
        }

        public function add_inner_meta_boxes($post){ 
            require_once( AVN_POPSLIDER_PATH . 'views/avn-popslider_metabox.php' ); 
        }

        public function save_post($post_id){
           
            if( isset( $_POST['avn_popslider_nonce']) ){                
                if( wp_verify_nonce($_POST['avn_popslider_nonce'], 'avn_popslider_noncesecret') == false ){
                    return;
                }
            }

            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
                return;
            }

            if(isset($_POST['post_type']) && $_POST['post_type'] === 'avn_popslider'){
                if(! current_user_can('edit_page', $post_id)){
                    return;
                }elseif(! current_user_can('edit_post', $post_id)){
                    return;
                }
            }

            if( isset($_POST['action']) && $_POST['action'] == 'editpost' ){ 
                $old_link_url = get_post_meta($post_id, 'avn_popslider_link_url', true); 
                $new_link_url = $_POST['avn_popslider_link_url']; 
               
                if ( empty($new_link_url) ){
                    update_post_meta( $post_id, 'avn_popslider_link_url', '#' );
                }else{
                    update_post_meta( $post_id, 'avn_popslider_link_url', sanitize_text_field($new_link_url), $old_link_url );
                }
            }
        }

        public function avn_popslider_colunaLink($columns){
            $columns['avn_popslider_link_url'] = esc_html__('Link URL', 'avn-popslider');
            return $columns;
        }

        public function avn_popslider_dadosColunaLink($column, $post_id){
            switch($column){
                case 'avn_popslider_link_url':
                   echo esc_html(get_post_meta($post_id, 'avn_popslider_link_url', true));
                break;
            }
        }

        public function avn_popslider_ordenaColuna($column){
            $column['avn_popslider_link_url'] = 'avn_popslider_link_url';
            return $column;
        }
    }
}