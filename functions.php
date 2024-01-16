<?php

// child theme code snippet
function twentytwentyfour_child_enqueue_styles() {
    $parent_style = 'twentytwentyfour'; // This is 'parent-style' for the twentytwentyfour theme.
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'twentytwentyfour_child_enqueue_styles' );
// child theme code snippet end.

//adding bootstrap
add_action('wp_enqueue_scripts' , 'understrap_add_style' ,11);
function understrap_add_style(){
    //               enqueue handle name->
    wp_enqueue_style('understrap-child', get_stylesheet_directory_uri(). '/assets/css/bootstrap.min.css'); //child theme mai new css file add ke hai.
}

// Create a new post type for 'Event'
function create_event_post_type() {
    $labels = array(
        'name'               => 'Events',
        'singular_name'      => 'Event',
        'menu_name'          => 'Events',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Event',
        'edit_item'          => 'Edit Event',
        'new_item'           => 'New Event',
        'view_item'          => 'View Event',
        'search_items'       => 'Search Events',
        'not_found'          => 'No events found',
        'not_found_in_trash' => 'No events found in trash',
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'hierarchical'        => false,
        'menu_position'       => 5,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt', 'custom-fields' ),
        'has_archive'         => true,
        'rewrite'             => array( 'slug' => 'events' ),
    );

    register_post_type( 'event', $args );
}

add_action( 'init', 'create_event_post_type' );

//Create Custom Taxonomy for Event Categories
function create_event_category_taxonomy() {
    $labels = array(
        'name'              => 'Event Categories',
        'singular_name'     => 'Event Category',
        'search_items'      => 'Search Event Categories',
        'all_items'         => 'All Event Categories',
        'parent_item'       => 'Parent Event Category',
        'parent_item_colon' => 'Parent Event Category:',
        'edit_item'         => 'Edit Event Category',
        'update_item'       => 'Update Event Category',
        'add_new_item'      => 'Add New Event Category',
        'new_item_name'     => 'New Event Category Name',
        'menu_name'         => 'Event Categories',
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'event-category' ),
    );

    register_taxonomy( 'event_category', 'event', $args );
}

add_action( 'init', 'create_event_category_taxonomy' );

//Create Shortcode to Display List of Events
function events_shortcode() {
   // Query to retrieve 'event' post type
$event_query = new WP_Query(array(
    'post_type' => 'event',
    'posts_per_page' => -1,
));

// Check if there are any events
if ($event_query->have_posts()) :
    ?>
    <div class="container mt-5">
        <div class="row">
             <h2 class="event-post-heading">Event post</h2>
            <?php
            // Loop through events
            while ($event_query->have_posts()) :
                $event_query->the_post();
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php
                        // Display event image
                        if (has_post_thumbnail()) :
                            ?>
                            <img src="<?php the_post_thumbnail_url('medium'); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php the_title(); ?></h5>
                         <p class="card-text"><?php echo get_post_meta(get_the_ID(), 'event_date_first', true); ?></p>
                            <?php
                            // Display event content
                            the_content();
                            ?>
          
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php
    // Reset Post Data
    wp_reset_postdata();
else :
    // If no events found
    echo 'No events found.';
endif;
}

add_shortcode( 'event_list', 'events_shortcode' );

/*add_filter('acf/settings/show_admin','show_admin_all');
function show_admin_all($show_admin){
return false;
}*/




?>
