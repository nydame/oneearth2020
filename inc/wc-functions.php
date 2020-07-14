<?php
// Get rid of the sidebar
function disable_woo_commerce_sidebar() {
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10); 
}
add_action('init', 'disable_woo_commerce_sidebar');

// Unhook WC content wrapper
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10); 
// Tell WC which content wrapper to use
function oesa_wrapper_start() {
  echo '<div class="wrap">';
}
function oesa_wrapper_end() {
  echo '</div>';
}
add_action('woocommerce_before_main_content', 'oesa_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'oesa_wrapper_end', 10);
// Declare support for WC
add_theme_support( 'woocommerce' );

// Add an H2 before the shop loop
function the_shop_intro() {
  ?>
  <h2>Invite More Beauty into Your World</h2>
  <?php
}
add_action('woocommerce_before_shop_loop', 'the_shop_intro');

// Add a latest sticky post after the shop loop
function the_freshest_sticky_post() {
	$args = array(
		'post__in' => get_option('sticky_posts'),
		'posts_per_page' => 1,
		'ignore_sticky_posts' => 1,
		'orderby' => 'modified'
	);
	$pq = new WP_Query($args);
	if ($pq->have_posts()) {
		while ($pq->have_posts()) {
      $pid = $pq->post->ID;
      $pq->the_post(); ?>
      <h2 class="feature-row-title"><?php the_title() ?></h2>
      <div class="feature-row">
        <figure>
          <a href="<?php the_permalink( $pid ) ?>"><?php echo wp_get_attachment_image( intval(get_post_thumbnail_id($pid)), 'medium'); ?></a>
          <figcaption><?php echo wp_get_attachment_caption( intval(get_post_thumbnail_id($pid)) ); ?></figcaption>
        </figure>
        <div class="feature-row-text"><?php 
        the_excerpt("Read more"); ?>
        </div>
      </div>
<?php		}
	}
}
add_action('woocommerce_after_shop_loop', 'the_freshest_sticky_post');

function the_coloring_books_post() {
  $coloring_books = get_post(1556);
  ?>
  <h2><?php echo $coloring_books->post_title; ?></h2>
  <p><?php echo apply_filters( 'the_content', $coloring_books->post_content ); ?></p>
  <?php 
}
add_action('woocommerce_after_shop_loop', 'the_coloring_books_post');

// Define is_woocommerce_related()
function is_woocommerce_related() {
    if (function_exists('is_woocommerce')) {
        return is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_wc_endpoint_url();
    } else {
        return false;
    }
}
add_action('init', 'is_woocommerce_related');
