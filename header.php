<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Preload critical fonts -->
    <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/big_noodle_titling-webfont.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/big_noodle_titling-webfont.woff2" as="font" type="font/woff2" crossorigin>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="header">
    <div class="header-content">
        <div class="site-branding">
            <?php 
            // Get header logo fields from Global Options
            $logo_image = get_field('header_logo_image', 'option');
            $logo_text = get_field('header_logo_text', 'option');
            
            // Use custom logo text if set, otherwise use site name
            $display_logo_text = $logo_text ?: get_bloginfo('name');
            ?>
            
            <a href="<?php echo home_url(); ?>" class="logo">
                <?php if ($logo_image) : ?>
                    <img src="<?php echo $logo_image['url']; ?>" alt="<?php echo $logo_image['alt'] ?: $display_logo_text; ?>" class="logo-image">
                <?php else : ?>
                    <div class="logo-icon">🌶️</div>
                    <span class="logo-text"><?php echo $display_logo_text; ?></span>
                <?php endif; ?>
            </a>
        </div>
        
        <nav class="main-nav">
            <ul>
                <?php 
                // Custom navigation items from Global Options
                $nav_items = get_field('header_nav_items', 'option');
                if ($nav_items && is_array($nav_items)) :
                    foreach ($nav_items as $item) :
                        $target = $item['new_tab'] ? 'target="_blank" rel="noopener"' : '';
                        ?>
                        <li>
                            <a href="<?php echo esc_url($item['url']); ?>" <?php echo $target; ?>>
                                <?php echo esc_html(strtoupper($item['text'])); ?>
                            </a>
                        </li>
                    <?php endforeach;
                endif;
                
                // Show default navigation items if enabled
                $show_default_nav = get_field('show_default_nav', 'option');
                if ($show_default_nav) : ?>
                    <li><a href="<?php echo home_url(); ?>#products">PRODUCTS</a></li>
                    <li><a href="<?php echo home_url(); ?>#about">ABOUT</a></li>
                    <li><a href="<?php echo home_url(); ?>#contact">CONTACT</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        
        <div class="header-actions">
            <?php 
            // Additional header buttons from Global Options
            $header_buttons = get_field('header_buttons', 'option');
            if ($header_buttons && is_array($header_buttons)) :
                foreach ($header_buttons as $button) :
                    $btn_class = 'btn btn-' . ($button['style'] ?: 'primary');
                    ?>
                    <a href="<?php echo esc_url($button['url']); ?>" class="<?php echo $btn_class; ?> header-btn">
                        <?php echo esc_html($button['text']); ?>
                    </a>
                <?php endforeach;
            endif;
            
            // Show cart button if enabled and WooCommerce is active
            $show_cart = get_field('show_cart_button', 'option');
            if ($show_cart && class_exists('WooCommerce')) : ?>
                <button class="cart-btn" id="cartBtn">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count <?php echo WC()->cart->get_cart_contents_count() > 0 ? 'visible' : 'hidden'; ?>" id="cartCount">
                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                    </span>
                </button>
            <?php endif; ?>
            
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <span class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </button>
        </div>
    </div>
    
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-content">
            <ul>
                <?php 
                // Custom navigation items for mobile menu
                if ($nav_items && is_array($nav_items)) :
                    foreach ($nav_items as $item) :
                        $target = $item['new_tab'] ? 'target="_blank" rel="noopener"' : '';
                        ?>
                        <li>
                            <a href="<?php echo esc_url($item['url']); ?>" <?php echo $target; ?>>
                                <?php echo esc_html(strtoupper($item['text'])); ?>
                            </a>
                        </li>
                    <?php endforeach;
                endif;
                
                // Show default navigation items in mobile menu if enabled
                if ($show_default_nav) : ?>
                    <li><a href="<?php echo home_url(); ?>#products">PRODUCTS</a></li>
                    <li><a href="<?php echo home_url(); ?>#about">ABOUT</a></li>
                    <li><a href="<?php echo home_url(); ?>#contact">CONTACT</a></li>
                <?php endif; ?>
            </ul>
            
            <?php 
            // Additional buttons in mobile menu
            if ($header_buttons && is_array($header_buttons)) : ?>
                <div class="mobile-header-buttons">
                    <?php foreach ($header_buttons as $button) :
                        $btn_class = 'btn btn-' . ($button['style'] ?: 'primary');
                        ?>
                        <a href="<?php echo esc_url($button['url']); ?>" class="<?php echo $btn_class; ?> mobile-header-btn">
                            <?php echo esc_html($button['text']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>