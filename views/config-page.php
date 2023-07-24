<div class="wrap">
    <h1> <?php echo esc_html(get_admin_page_title()); ?> </h1>

    <?php
        $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'main_options'; //define qual tab ativa
    ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=avn_popslide_admin&tab=main_options" class="nav-tab <?php echo $active_tab == 'main_options' ? 'nav-tab-active' : ''; ?>">Principais</a>
        <a href="?page=avn_popslide_admin&tab=additional_options" class="nav-tab <?php echo $active_tab == 'additional_options' ? 'nav-tab-active' : ''; ?>">Adicionais</a>
    </h2>

    <form action="options.php" method="post">
        <?php 
            if($active_tab == 'main_options'){
                settings_fields('avn_popslider_group');
                do_settings_sections('avn_popslider_page1');
            }else{
                settings_fields('avn_popslider_group');
                do_settings_sections('avn_popslider_page2');
            }
            submit_button('Salvar');
        ?>
    </form>
</div>