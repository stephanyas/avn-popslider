<?php 
    //pega o valor e mostra no input com o eco no value
    
    /**
     * forma 1: 
     *      $meta = get_post_meta($post->ID);     
     * e no value da tabela no html
     *      value="<?php echo $meta['avn_popslider_link_url'][0]; ?>" 
    */

    //forma 2: 
    $link_url = get_post_meta($post->ID, 'avn_popslider_link_url', true);   
    // var_dump($link_url);
?>       

<table class="form-table avn-popslider-metabox">    
    <input type="hidden" name="avn_popslider_nonce" value="<?php echo wp_create_nonce('avn_popslider_noncesecret');?>">
    <tr>
        <th>
            <label for="avn_popslider_link_url"> Link URL </label>
        </th>
        <td>
            <input 
                type="url" 
                name="avn_popslider_link_url"
                id="avn_popslider_link_url" 
                class="regular-text link-url" 
                value=" <?php echo (isset($link_url)) ? esc_url($link_url) : ''; ?> " 
                require
            >
        </td>
    </tr>
</table>