<?php
/**
 * Posts Widget Pro Class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Posts Widget Pro Class
 */
class Posts_Widget_Pro extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'posts_widget_pro',
            __('Posts Widget Pro', 'posts-widget-pro'),
            array(
                'description' => __('Display recent posts with advanced sorting and filtering options.', 'posts-widget-pro'),
                'classname' => 'posts-widget-pro-widget',
            )
        );
    }

    /**
     * Widget form in admin
     *
     * @param array $instance Widget instance
     * @return void
     */
    public function form($instance) {
        // Set default values
        $defaults = array(
            'title' => __('Recent Posts', 'posts-widget-pro'),
            'number_of_posts' => 5,
            'sort_by' => 'date',
            'sort_order' => 'desc',
            'categories' => array(),
            'tags' => array(),
            'show_date' => false,
            'show_author' => false,
            'show_categories' => false,
            'show_thumbnail' => true,
            'thumbnail_size' => 'thumbnail',
            'exclude_current' => true,
        );

        // Parse instance
        $instance = wp_parse_args((array) $instance, $defaults);

        // Get all categories
        $categories = get_categories(array(
            'hide_empty' => false,
        ));

        // Get all tags
        $tags = get_tags(array(
            'hide_empty' => false,
        ));
        ?>

        <!-- Title -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'posts-widget-pro'); ?>
            </label>
            <input 
                class="widefat" 
                id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                type="text" 
                value="<?php echo esc_attr($instance['title']); ?>" 
            />
        </p>

        <!-- Number of posts -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number_of_posts')); ?>">
                <?php esc_html_e('Number of posts to show:', 'posts-widget-pro'); ?>
            </label>
            <input 
                class="tiny-text" 
                id="<?php echo esc_attr($this->get_field_id('number_of_posts')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('number_of_posts')); ?>" 
                type="number" 
                step="1" 
                min="1" 
                value="<?php echo esc_attr($instance['number_of_posts']); ?>" 
                size="3" 
            />
        </p>

        <!-- Sort by -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('sort_by')); ?>">
                <?php esc_html_e('Sort by:', 'posts-widget-pro'); ?>
            </label>
            <select 
                class="widefat" 
                id="<?php echo esc_attr($this->get_field_id('sort_by')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('sort_by')); ?>"
            >
                <option value="date" <?php selected($instance['sort_by'], 'date'); ?>>
                    <?php esc_html_e('Date', 'posts-widget-pro'); ?>
                </option>
                <option value="title" <?php selected($instance['sort_by'], 'title'); ?>>
                    <?php esc_html_e('Alphabetically', 'posts-widget-pro'); ?>
                </option>
                <option value="comment_count" <?php selected($instance['sort_by'], 'comment_count'); ?>>
                    <?php esc_html_e('Comment Count', 'posts-widget-pro'); ?>
                </option>
                <option value="rand" <?php selected($instance['sort_by'], 'rand'); ?>>
                    <?php esc_html_e('Random', 'posts-widget-pro'); ?>
                </option>
            </select>
        </p>

        <!-- Sort order -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('sort_order')); ?>">
                <?php esc_html_e('Sort order:', 'posts-widget-pro'); ?>
            </label>
            <select 
                class="widefat" 
                id="<?php echo esc_attr($this->get_field_id('sort_order')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('sort_order')); ?>"
            >
                <option value="desc" <?php selected($instance['sort_order'], 'desc'); ?>>
                    <?php esc_html_e('Descending', 'posts-widget-pro'); ?>
                </option>
                <option value="asc" <?php selected($instance['sort_order'], 'asc'); ?>>
                    <?php esc_html_e('Ascending', 'posts-widget-pro'); ?>
                </option>
            </select>
        </p>

        <!-- Categories -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('categories')); ?>">
                <?php esc_html_e('Filter by categories:', 'posts-widget-pro'); ?>
            </label>
            <select 
                class="widefat" 
                id="<?php echo esc_attr($this->get_field_id('categories')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('categories')); ?>[]" 
                multiple="multiple"
                style="height: 100px;"
            >
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo esc_attr($category->term_id); ?>" <?php selected(in_array($category->term_id, (array) $instance['categories']), true); ?>>
                        <?php echo esc_html($category->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small><?php esc_html_e('Hold Ctrl/Cmd to select multiple categories', 'posts-widget-pro'); ?></small>
        </p>

        <!-- Tags -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('tags')); ?>">
                <?php esc_html_e('Filter by tags:', 'posts-widget-pro'); ?>
            </label>
            <select 
                class="widefat" 
                id="<?php echo esc_attr($this->get_field_id('tags')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('tags')); ?>[]" 
                multiple="multiple"
                style="height: 100px;"
            >
                <?php foreach ($tags as $tag) : ?>
                    <option value="<?php echo esc_attr($tag->term_id); ?>" <?php selected(in_array($tag->term_id, (array) $instance['tags']), true); ?>>
                        <?php echo esc_html($tag->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small><?php esc_html_e('Hold Ctrl/Cmd to select multiple tags', 'posts-widget-pro'); ?></small>
        </p>

        <!-- Display options -->
        <p>
            <input 
                class="checkbox" 
                type="checkbox" 
                id="<?php echo esc_attr($this->get_field_id('show_date')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" 
                <?php checked($instance['show_date']); ?> 
            />
            <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>">
                <?php esc_html_e('Show post date', 'posts-widget-pro'); ?>
            </label>
        </p>

        <p>
            <input 
                class="checkbox" 
                type="checkbox" 
                id="<?php echo esc_attr($this->get_field_id('show_author')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('show_author')); ?>" 
                <?php checked($instance['show_author']); ?> 
            />
            <label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>">
                <?php esc_html_e('Show post author', 'posts-widget-pro'); ?>
            </label>
        </p>

        <p>
            <input 
                class="checkbox" 
                type="checkbox" 
                id="<?php echo esc_attr($this->get_field_id('show_categories')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('show_categories')); ?>" 
                <?php checked($instance['show_categories']); ?> 
            />
            <label for="<?php echo esc_attr($this->get_field_id('show_categories')); ?>">
                <?php esc_html_e('Show post categories', 'posts-widget-pro'); ?>
            </label>
        </p>

        <p>
            <input 
                class="checkbox" 
                type="checkbox" 
                id="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('show_thumbnail')); ?>" 
                <?php checked($instance['show_thumbnail']); ?> 
            />
            <label for="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>">
                <?php esc_html_e('Show post thumbnail', 'posts-widget-pro'); ?>
            </label>
        </p>

        <!-- Thumbnail size -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('thumbnail_size')); ?>">
                <?php esc_html_e('Thumbnail size:', 'posts-widget-pro'); ?>
            </label>
            <select 
                class="widefat" 
                id="<?php echo esc_attr($this->get_field_id('thumbnail_size')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('thumbnail_size')); ?>"
            >
                <option value="thumbnail" <?php selected($instance['thumbnail_size'], 'thumbnail'); ?>>
                    <?php esc_html_e('Thumbnail', 'posts-widget-pro'); ?>
                </option>
                <option value="medium" <?php selected($instance['thumbnail_size'], 'medium'); ?>>
                    <?php esc_html_e('Medium', 'posts-widget-pro'); ?>
                </option>
                <option value="large" <?php selected($instance['thumbnail_size'], 'large'); ?>>
                    <?php esc_html_e('Large', 'posts-widget-pro'); ?>
                </option>
            </select>
        </p>

        <!-- Exclude current post -->
        <p>
            <input 
                class="checkbox" 
                type="checkbox" 
                id="<?php echo esc_attr($this->get_field_id('exclude_current')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('exclude_current')); ?>" 
                <?php checked($instance['exclude_current']); ?> 
            />
            <label for="<?php echo esc_attr($this->get_field_id('exclude_current')); ?>">
                <?php esc_html_e('Exclude current post', 'posts-widget-pro'); ?>
            </label>
        </p>

        <?php
    }

    /**
     * Process widget options on save
     *
     * @param array $new_instance New widget instance
     * @param array $old_instance Old widget instance
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number_of_posts'] = absint($new_instance['number_of_posts']);
        $instance['sort_by'] = sanitize_key($new_instance['sort_by']);
        $instance['sort_order'] = sanitize_key($new_instance['sort_order']);
        $instance['categories'] = isset($new_instance['categories']) ? array_map('absint', $new_instance['categories']) : array();
        $instance['tags'] = isset($new_instance['tags']) ? array_map('absint', $new_instance['tags']) : array();
        $instance['show_date'] = isset($new_instance['show_date']) ? (bool) $new_instance['show_date'] : false;
        $instance['show_author'] = isset($new_instance['show_author']) ? (bool) $new_instance['show_author'] : false;
        $instance['show_categories'] = isset($new_instance['show_categories']) ? (bool) $new_instance['show_categories'] : false;
        $instance['show_thumbnail'] = isset($new_instance['show_thumbnail']) ? (bool) $new_instance['show_thumbnail'] : false;
        $instance['thumbnail_size'] = sanitize_key($new_instance['thumbnail_size']);
        $instance['exclude_current'] = isset($new_instance['exclude_current']) ? (bool) $new_instance['exclude_current'] : false;

        return $instance;
    }

    /**
     * Display the widget
     *
     * @param array $args Widget arguments
     * @param array $instance Widget instance
     * @return void
     */
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
        $number_of_posts = absint($instance['number_of_posts']);
        $sort_by = sanitize_key($instance['sort_by']);
        $sort_order = sanitize_key($instance['sort_order']);
        $categories = isset($instance['categories']) ? (array) $instance['categories'] : array();
        $tags = isset($instance['tags']) ? (array) $instance['tags'] : array();
        $show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : false;
        $show_author = isset($instance['show_author']) ? (bool) $instance['show_author'] : false;
        $show_categories = isset($instance['show_categories']) ? (bool) $instance['show_categories'] : false;
        $show_thumbnail = isset($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : false;
        $thumbnail_size = sanitize_key($instance['thumbnail_size']);
        $exclude_current = isset($instance['exclude_current']) ? (bool) $instance['exclude_current'] : false;

        // Build query arguments
        $query_args = array(
            'posts_per_page' => $number_of_posts,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
        );

        // Set order parameters
        if ($sort_by === 'rand') {
            $query_args['orderby'] = 'rand';
        } else {
            $query_args['orderby'] = $sort_by;
            $query_args['order'] = $sort_order;
        }

        // Filter by categories
        if (!empty($categories)) {
            $query_args['category__in'] = $categories;
        }

        // Filter by tags
        if (!empty($tags)) {
            $query_args['tag__in'] = $tags;
        }

        // Exclude current post
        if ($exclude_current && is_singular()) {
            $query_args['post__not_in'] = array(get_the_ID());
        }

        // Get posts
        $posts_query = new WP_Query($query_args);

        // Display widget
        echo $args['before_widget'];

        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        if ($posts_query->have_posts()) {
            echo '<ul class="posts-widget-pro-list">';

            while ($posts_query->have_posts()) {
                $posts_query->the_post();

                echo '<li class="posts-widget-pro-item">';

                // Display thumbnail
                if ($show_thumbnail) {
                    echo '<div class="posts-widget-pro-thumbnail">';
                    echo '<a href="' . esc_url(get_permalink()) . '">';
                    if (has_post_thumbnail()) {
                        the_post_thumbnail($thumbnail_size, array(
                            'class' => 'posts-widget-pro-image',
                            'alt' => the_title_attribute(array('echo' => false)),
                        ));
                    } else {
                        // Fallback image if no thumbnail
                        echo '<img src="' . esc_url(plugins_url('assets/default-thumbnail.jpg', dirname(__FILE__))) . '" class="posts-widget-pro-image" alt="' . esc_attr__('Default thumbnail', 'posts-widget-pro') . '" />';
                    }
                    echo '</a>';
                    echo '</div>';
                }

                echo '<div class="posts-widget-pro-content">';
                echo '<h4 class="posts-widget-pro-title">';
                echo '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a>';
                echo '</h4>';

                // Only show meta if any meta option is enabled
                if ($show_date || $show_author || $show_categories) {
                    echo '<div class="posts-widget-pro-meta">';
                    
                    if ($show_date) {
                        echo '<span class="posts-widget-pro-date">' . esc_html(get_the_date()) . '</span>';
                    }
                    
                    if ($show_author) {
                        echo '<span class="posts-widget-pro-author">';
                        echo esc_html__('by', 'posts-widget-pro') . ' ';
                        echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">';
                        echo esc_html(get_the_author());
                        echo '</a>';
                        echo '</span>';
                    }
                    
                    if ($show_categories) {
                        $categories_list = get_the_category_list(', ');
                        if ($categories_list) {
                            echo '<span class="posts-widget-pro-categories">';
                            echo $categories_list;
                            echo '</span>';
                        }
                    }
                    
                    echo '</div>';
                }

                echo '</div>'; // .posts-widget-pro-content
                echo '</li>';
            }

            echo '</ul>';

            // Reset post data
            wp_reset_postdata();
        } else {
            echo '<p class="posts-widget-pro-no-posts">' . esc_html__('No posts found.', 'posts-widget-pro') . '</p>';
        }

        echo $args['after_widget'];
    }
}

