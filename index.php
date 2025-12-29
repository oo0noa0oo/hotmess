<?php
/**
 * Index Template
 * 
 * Hot Sauce Co - Fallback template for WordPress
 * This template serves as a fallback when no specific template is found
 */

get_header(); ?>

<main class="index-content">
    <div class="index-container">
        <?php if (have_posts()) : ?>
            
            <div class="posts-header">
                <h1 class="posts-title">
                    <?php 
                    if (is_home() && !is_front_page()) {
                        echo get_the_title(get_option('page_for_posts'));
                    } elseif (is_category()) {
                        echo 'Category: ' . single_cat_title('', false);
                    } elseif (is_tag()) {
                        echo 'Tag: ' . single_tag_title('', false);
                    } elseif (is_search()) {
                        echo 'Search Results for: ' . get_search_query();
                    } elseif (is_archive()) {
                        the_archive_title();
                    } else {
                        echo 'Latest Posts';
                    }
                    ?>
                </h1>
                
                <?php if (is_search()) : ?>
                    <p class="search-description">
                        Found <?php echo $wp_query->found_posts; ?> result(s) for "<?php echo get_search_query(); ?>"
                    </p>
                <?php endif; ?>
            </div>

            <div class="posts-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="post-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <div class="post-meta">
                                <span class="post-date"><?php echo get_the_date(); ?></span>
                                <span class="post-category">
                                    <?php 
                                    $categories = get_the_category();
                                    if (!empty($categories)) {
                                        echo esc_html($categories[0]->name);
                                    }
                                    ?>
                                </span>
                            </div>
                            
                            <h2 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <div class="post-excerpt">
                                <?php 
                                if (has_excerpt()) {
                                    the_excerpt();
                                } else {
                                    echo wp_trim_words(get_the_content(), 30, '...');
                                }
                                ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline">READ MORE</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => '← Previous',
                    'next_text' => 'Next →',
                ));
                ?>
            </div>

        <?php else : ?>
            
            <div class="no-posts-found">
                <h1>Nothing Found</h1>
                
                <?php if (is_search()) : ?>
                    <p>Sorry, no results were found for "<?php echo get_search_query(); ?>". Try searching for something else.</p>
                    
                    <div class="search-form-wrapper">
                        <?php get_search_form(); ?>
                    </div>
                <?php else : ?>
                    <p>It looks like nothing was found at this location. Maybe try a search?</p>
                    
                    <div class="search-form-wrapper">
                        <?php get_search_form(); ?>
                    </div>
                    
                    <div class="home-link">
                        <a href="<?php echo home_url(); ?>" class="btn btn-primary">RETURN HOME</a>
                    </div>
                <?php endif; ?>
            </div>

        <?php endif; ?>
    </div>
</main>

<style>
/* Claudia commented out: NOTE - Much of this embedded CSS duplicates main.css definitions
   Consider moving all these styles to main.css and removing embedded <style> blocks from templates */

/* Index page specific styles */
.index-content {
    min-height: 60vh;
    padding: 4rem 0;
}

.index-container {
    max-width: var(--container-max-width);
    margin: 0 auto;
    padding: 0 1.5rem;
}

.posts-header {
    text-align: center;
    margin-bottom: 4rem;
    padding-bottom: 2rem;
    border-bottom: 4px solid var(--color-border);
}

.posts-title {
    font-family: var(--font-heading);
    font-size: clamp(2.5rem, 5vw, 4rem);
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.search-description {
    font-size: 1.125rem;
    color: var(--color-muted-foreground);
    margin: 0;
}

.posts-grid {
    display: grid;
    gap: 3rem;
    margin-bottom: 4rem;
}

@media (min-width: 768px) {
    .posts-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .posts-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

.post-card {
    background: var(--color-background);
    border: 4px solid var(--color-border);
    transition: box-shadow 0.3s ease;
}

.post-card:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.post-image {
    border-bottom: 4px solid var(--color-border);
}

.post-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    filter: grayscale(100%);
    transition: filter 0.3s ease;
}

.post-card:hover .post-image img {
    filter: grayscale(0%);
}

.post-content {
    padding: 2rem;
}

.post-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    font-family: var(--font-heading);
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--color-muted-foreground);
}

.post-title {
    font-family: var(--font-heading);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    line-height: 1.2;
}

.post-title a {
    color: var(--color-foreground);
    text-decoration: none;
    transition: color 0.3s ease;
}

.post-title a:hover {
    color: var(--color-accent);
}

.post-excerpt {
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
    color: var(--color-muted-foreground);
}

.pagination-wrapper {
    text-align: center;
    padding-top: 2rem;
    border-top: 2px solid var(--color-border);
}

.pagination .page-numbers {
    font-family: var(--font-heading);
    padding: 0.75rem 1.5rem;
    margin: 0 0.25rem;
    border: 2px solid var(--color-border);
    background: var(--color-background);
    color: var(--color-foreground);
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

.pagination .page-numbers:hover,
.pagination .page-numbers.current {
    background: var(--color-foreground);
    color: var(--color-background);
}

.no-posts-found {
    text-align: center;
    padding: 4rem 2rem;
    border: 4px solid var(--color-border);
    background: var(--color-muted);
}

.no-posts-found h1 {
    font-family: var(--font-heading);
    font-size: 2.5rem;
    margin-bottom: 1rem;
    text-transform: uppercase;
}

.no-posts-found p {
    font-size: 1.125rem;
    margin-bottom: 2rem;
    color: var(--color-muted-foreground);
    max-width: 32rem;
    margin-left: auto;
    margin-right: auto;
}

.search-form-wrapper {
    margin: 2rem 0;
}

.search-form-wrapper .search-form {
    display: flex;
    justify-content: center;
    gap: 1rem;
    max-width: 400px;
    margin: 0 auto;
}

.search-form-wrapper input[type="search"] {
    flex: 1;
    padding: 0.75rem;
    border: 2px solid var(--color-border);
    background: var(--color-background);
    font-family: var(--font-body);
}

.search-form-wrapper input[type="submit"] {
    font-family: var(--font-heading);
    padding: 0.75rem 1.5rem;
    border: 2px solid var(--color-border);
    background: var(--color-foreground);
    color: var(--color-background);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-form-wrapper input[type="submit"]:hover {
    background: var(--color-accent);
    border-color: var(--color-accent);
}

.home-link {
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .posts-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .post-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .search-form-wrapper .search-form {
        flex-direction: column;
    }
    
    .index-container {
        padding: 0 1rem;
    }
}
</style>

<?php get_footer(); ?>