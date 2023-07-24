    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php  
                $args = array(
                    'post_type' => 'avn-popslider',
                    'post_status' => 'publish',
                    'post__in'  => $id, 
                    'orderby' => $orderby
                );

                $my_query = new WP_Query($args);

                if($my_query->have_posts()):
                    while($my_query->have_posts()) : $my_query->the_post();
                        $link_url = get_post_meta(get_the_ID(), 'avn_popslider_link_url', true); 
            ?>
            
            <div class="carousel-item active">
                <a href="<?php echo esc_attr($link_url); ?>">  
                    <?php 
                        if(has_post_thumbnail()){
                            the_post_thumbnail('full', array('class' => 'd-block img-fluid')); 
                        }else{
                            echo "<img src='".AVN_POPSLIDER_URL."assets/images/default.jpg' class='img-fluid wp-post-image'/>";
                        }
                    ?>                            
                </a>         
            </div>
                    
            <?php 
                endwhile;            
                    wp_reset_postdata();
                endif; 
            ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
    </div>
