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

// present the coloring books after the shop loop
function the_coloring_books_post() {
  $args = array('p' => 1556);
  $coloring_books = new WP_Query($args);
  if ($coloring_books->have_posts()):
    while ($coloring_books->have_posts() && $coloring_books->post->ID === 1556):
      $coloring_books->the_post();
  ?>
  <hr />
  <div class="store-insert coloring-books">
    <h2><?php the_title(); ?></h2>
    <?php the_content(); ?>
  </div>
  <script>
  // Get rid of hash in more link's URL
  $moreLink = document.querySelector('.coloring-books .more-link');
  $moreLink.setAttribute('href', $moreLink.href.split('#')[0]);
  </script>
    <?php endwhile; 
  endif;
}
add_action('woocommerce_after_shop_loop', 'the_coloring_books_post');


// Add the latest sticky post after the shop loop
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
      <h2>Did we mention that we do gorgeous custom artwork?</h2>
      <h3 class="feature-row-title"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h3>
      <div class="feature-row store-insert sticky-post">
        <figure>
          <a href="<?php the_permalink( $pid ) ?>"><?php echo wp_get_attachment_image( intval(get_post_thumbnail_id($pid)), 'medium'); ?></a>
          <figcaption><?php echo wp_get_attachment_caption( intval(get_post_thumbnail_id($pid)) ); ?></figcaption>
        </figure>
        <div class="feature-row-text"><?php 
        the_excerpt("Read more"); ?>
        </div>
      </div>
      <p class="entry-content feature-row-after">Are you ready to manifest your vision of the divine? Use our <a href="/contact">contact form</a> to order or propose a beautiful work of art for your home or work space.</p>
<?php		}
	}
}
add_action('woocommerce_after_shop_loop', 'the_freshest_sticky_post');

// Add text to all short descriptions
function oesa_short_description_append($content) {
  if (! is_shop()) {
    
    return $content . "<a href=\"#tab-description\">See longer desription below.</a>";
  } else {
    return $content;
  }
}
add_filter('woocommerce_short_description', 'oesa_short_description_append');

// Define is_woocommerce_related()

function is_woocommerce_related() {
    if (function_exists('is_woocommerce')) {
        return is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_wc_endpoint_url();
    } else {
        return false;
    }
}
add_action('init', 'is_woocommerce_related');
