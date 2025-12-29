<footer class="site-footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <div class="logo">
                <div class="logo-icon">🌶️</div>
                <span class="logo-text"><?php bloginfo('name'); ?></span>
            </div>
            <p><?php 
                $tagline = get_bloginfo('description');
                echo $tagline ? $tagline : 'CRAFTING BOLD FLAVORS SINCE 2024';
            ?></p>
        </div>
        
        <div class="footer-section">
            <h4>PRODUCTS</h4>
            <ul>
                <?php
                // Get WooCommerce products for footer menu
                if (class_exists('WooCommerce')) {
                    $products = wc_get_products(array('limit' => 3, 'status' => 'publish'));
                    foreach ($products as $product) {
                        echo '<li><a href="' . get_permalink($product->get_id()) . '">' . strtoupper($product->get_name()) . '</a></li>';
                    }
                }
                ?>
                <li><a href="<?php echo wc_get_page_permalink('shop'); ?>">ALL SAUCES</a></li>
            </ul>
        </div>
        
        <div class="footer-section">
            <h4>COMPANY</h4>
            <?php
            if (has_nav_menu('footer-menu')) {
                // Display WordPress footer menu if it exists
                wp_nav_menu(array(
                    'theme_location' => 'footer-menu',
                    'menu_class' => 'footer-menu-list',
                    'container' => false,
                    'depth' => 1,
                    'fallback_cb' => false,
                ));
            } else {
                // Fallback to default company links if no menu is set
                ?>
                <ul class="footer-menu-list">
                    <li><a href="<?php echo home_url(); ?>#about">ABOUT US</a></li>
                    <li><a href="<?php echo home_url(); ?>#contact">CONTACT</a></li>
                    <li><a href="<?php echo get_privacy_policy_url(); ?>">PRIVACY POLICY</a></li>
                    <?php if (class_exists('WooCommerce')) : ?>
                        <li><a href="<?php echo wc_get_page_permalink('terms'); ?>">TERMS & CONDITIONS</a></li>
                    <?php endif; ?>
                </ul>
                <?php
            }
            ?>
        </div>
        
        <!-- Social Links Section using new social_media repeater -->
        <div class="footer-section">
            <h4>FOLLOW US</h4>
            <div class="social-links">
                <?php
                // Get the new social_media repeater field
                $social_media = get_field('social_media', 'option');
                
                if ($social_media && is_array($social_media)) :
                    // Map platforms to FontAwesome icons
                    $icon_map = [
                        'facebook' => 'fab fa-facebook',
                        'flickr' => 'fab fa-flickr',
                        'instagram' => 'fab fa-instagram',
                        'linkedin' => 'fab fa-linkedin',
                        'pinterest' => 'fab fa-pinterest',
                        'skype' => 'fab fa-skype',
                        'spotify' => 'fab fa-spotify',
                        'tumblr' => 'fab fa-tumblr',
                        'twitter' => 'fab fa-twitter',
                        'vimeo' => 'fab fa-vimeo',
                        'youtube' => 'fab fa-youtube',
                        'tiktok' => 'fab fa-tiktok',
                        'x' => 'fab fa-twitter', // Use regular Twitter icon for X
                        'snapchat' => 'fab fa-snapchat',
                    ];
                    
                    foreach ($social_media as $social) :
                        $platform = $social['platform']; // Selected platform value
                        $url = $social['url']; // URL field
                        
                        // Get the appropriate icon
                        $icon_class = $icon_map[$platform] ?? 'fas fa-link';
                        
                        if ($url) : ?>
                            <a href="<?php echo esc_url($url); ?>" class="social-link" target="_blank" rel="noopener" title="<?php echo esc_attr(ucfirst($platform)); ?>">
                                <i class="<?php echo $icon_class; ?>"></i>
                            </a>
                        <?php endif;
                    endforeach;
                    
                else :
                    // Fallback to old social_links field if new field is empty
                    $social_links = get_field('social_links', 'option');
                    if ($social_links && is_array($social_links)) :
                        foreach ($social_links as $social) :
                            $icon_map = [
                                'facebook' => 'fab fa-facebook',
                                'instagram' => 'fab fa-instagram',
                                'twitter' => 'fab fa-twitter',
                                'linkedin' => 'fab fa-linkedin',
                                'youtube' => 'fab fa-youtube',
                                'tiktok' => 'fab fa-tiktok',
                                'flickr' => 'fab fa-flickr',
                            ];
                            $icon_class = $icon_map[strtolower($social['name'])] ?? 'fas fa-link';
                            ?>
                            <a href="<?php echo esc_url($social['link']); ?>" class="social-link" target="_blank" rel="noopener" title="<?php echo esc_attr($social['name']); ?>">
                                <i class="<?php echo $icon_class; ?>"></i>
                            </a>
                        <?php endforeach;
                    endif;
                endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Footer Bottom with Optional Secondary Menu -->
    <div class="footer-bottom">
        <div class="footer-bottom-content">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. ALL RIGHTS RESERVED.</p>
            
            <?php 
            $footer_message = get_field('footer_message', 'option');
            if ($footer_message) : ?>
                <p class="footer-custom-message"><?php echo esc_html($footer_message); ?></p>
            <?php endif; ?>
        </div>
        
        <?php
        // Optional secondary footer menu (for things like Privacy Policy, Terms, etc.)
        if (has_nav_menu('footer-secondary')) : ?>
            <div class="footer-secondary-menu">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer-secondary',
                    'menu_class' => 'footer-secondary-list',
                    'container' => false,
                    'depth' => 1,
                    'fallback_cb' => false,
                ));
                ?>
            </div>
        <?php endif; ?>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>