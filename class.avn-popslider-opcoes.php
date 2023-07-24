<?php

if(!class_exists('AVN_PopSlider_Opcoes')){
    class AVN_PopSlider_Opcoes{

        public static $opcoes; //aqui vai ser guardado um array com valor de todas as opções do plugin

        public function __construct(){
            self::$opcoes = get_option('avn_popslider_opcoes');
            add_action('admin_init', array($this, 'admin_init'));
        }

        public function admin_init(){

            register_setting('avn_popslider_group', 'avn_popslider_opcoes', array($this, 'avn_popslider_validade'));

            add_settings_section(
                'avn_popslider_main_section',
                'Como é que funciona?',
                null,
                'avn_popslider_page1'
            );  

            add_settings_section(
                'avn_popslider_second_section',
                'Outras opções do plug-in',
                null,
                'avn_popslider_page2'
            );

            add_settings_field(
                'avn_popslider_shortcode',
                'Shortcode',
                array($this, 'avn_popslider_shortecode_callback'),
                'avn_popslider_page1',
                'avn_popslider_main_section',
            );    

            add_settings_field(
                'avn_popslider_title',
                'Slider Titulo',
                array($this, 'avn_popslider_title_callback'),
                'avn_popslider_page2',
                'avn_popslider_second_section',
                array(
                    'label_for' => 'avn_popslider_title'
                )
            );

            add_settings_field(
                'avn_popslider_bullets',
                'Marcadores',
                array($this, 'avn_popslider_bullets_callback'),
                'avn_popslider_page2',
                'avn_popslider_second_section',
                array(
                    'label_for' => 'avn_popslider_bullets'
                )
            );

            add_settings_field(
                'avn_popslider_style',
                'Slider Style',
                array($this, 'avn_popslider_style_callback'),
                'avn_popslider_page2',
                'avn_popslider_second_section',
                array(
                    'items' => array(
                        'style-1',
                        'style-2'
                    ),
                    'label_for' => 'avn_popslider_style'
                )                
            );

            
        }

        public function avn_popslider_shortecode_callback(){
            ?>
            <span>
                Use o shortcode [avn_popslider] para exibir o slider em qualquer page/post/widget
            </span>
            <?php
        }

        public function avn_popslider_title_callback($args){
            ?>
                <input 
                    type="text" 
                    name="avn_popslider_opcoes[avn_popslider_title]" 
                    id="avn_popslider_title" 
                    value="<?php echo isset(self::$opcoes['avn_popslider_title']) ? esc_attr(self::$opcoes['avn_popslider_title']) : ''; ?>"
                >
            <?php
        }

        public function avn_popslider_bullets_callback($args){
            ?>
                <input 
                    type="checkbox" 
                    name="avn_popslider_opcoes[avn_popslider_bullets]" 
                    id="avn_popslider_bullets" 
                    value="1"                   
                    <?php
                        if(isset(self::$opcoes['avn_popslider_bullets'])){
                            checked("1", self::$opcoes['avn_popslider_bullets'], true);
                        }
                    ?>
                >
                <label for="avn_popslider_bullets"> Deixe ou não selecionado os marcadores </label>
            <?php
        }

        public function avn_popslider_style_callback($args){
            ?>
                <select 
                    id="avn_popslider_style"
                    name="avn_popslider_opcoes[avn_popslider_style]">
                    <?php
                        foreach($args['items'] as $item):
                    ?>
                        <option value="<?php echo esc_attr($item); ?>"
                            <?php
                                isset(self::$opcoes['avn_popslider_style']) ? selected($item, self::$opcoes['avn_popslider_style'], true) : '';
                            ?>
                        >
                            <?php echo esc_html(ucfirst($item)); ?>
                        </option>
                    <?php 
                        endforeach; 
                    ?>
                </select>
            <?php
        }

        public function avn_popslider_validade($input){
            $new_input = array();

            foreach($input as $key => $value){
                switch($key){
                    case 'avn_popslider_title':
                        if(empty($value)){
                            add_settings_error('avn_popslider_opcoes', 'avn_popslider_message', 'O campo de titulo não pode ficar vazio', 'error');
                            $value = 'Insira aqui somente Texto';
                            $new_input[$key] = sanitize_text_field($value);
                        }                        
                    break;
                    default:
                        $new_input[$key] = sanitize_text_field($value);
                    break;
                }
            }

            return $new_input;
        }
    }
}