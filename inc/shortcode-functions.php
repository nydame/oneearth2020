<?php

// shortcode to display latest num posts in a single category
function oesa_show_latest($atts, $content = null) {
	$a = shortcode_atts( array(
		'href' => get_site_url(),
		'anchor_title'=> "",
		'intro' => "",
		'cat' => 1,
		'num' => 1,
		'pre_title_span' => ""
	), $atts );

	$headerP = "";
	$postHTML = "";
	extract($a);
	$href = wp_make_link_relative( esc_url($href) );
	$anchor_title = esc_attr( $anchor_title );
	$intro = esc_html( ent2ncr($intro) );
	$cat = ( intval($cat) === 0 )? 1 : intval($cat);
	$num = ( intval($num) === 0 )? 1 : intval($num);
	$pre_title_span = esc_html( $pre_title_span );

	if ( strlen($intro) > 0 ) {
		$headerP .= '<p><a href="' . $href . '" title="' . $anchor_title . '">' . $intro . '</a></p>';
		$postHTML .= $headerP;
	}

	$p_query = new WP_Query('cat=' . (string)$cat . '&posts_per_page=' . (string)$num);

	while ($p_query->have_posts()): $p_query->the_post();
		$postHTML .= '<div>';
		if (has_post_thumbnail()) {
			$postHTML .= get_the_post_thumbnail() . '<br />';
		}
		$postHTML .= '<span>' . $pre_title_span . '</span>' . get_the_title() . '</div>'; 
	endwhile;
	wp_reset_postdata();

	return $postHTML;
}
add_shortcode('show_latest', 'oesa_show_latest');

// shortcode to display random testimonial
function oesa_show_random_testimonial($atts, $content = null) {
	$postHTML = "";
	// Don't interfere with The Loop
	global $post;
	$tmp_post = $post;
	$postArr = get_posts( array('category' => 37) );
	shuffle($postArr);
	$rand_post = $postArr[0];
	if ($rand_post !== null) {
		setup_postdata($rand_post);
		$postHTML .= '<p>' . get_the_excerpt() . '<br />&mdash;<span>' . get_the_title($rand_post->ID) . '</span></p>';
	}
	$postHTML .= '<a href="/section/testimonials" title="Visit our Testimonials page">Read more testimonials &rarr;</a></p>';
	$post = $tmp_post;
	return $postHTML;
}
add_shortcode('show_random_testimonial', 'oesa_show_random_testimonial');

// shortcode to display bookmarks as a list of links
//user may change how they are ordered
function oesa_show_bookmarks($atts, $content = null) {
	$postHTML = "";
	$orderby_options = array('length', 'link_id', 'name', 'owner', 'rand', 'rating', 'url', 'visible');
	$a = shortcode_atts( array(
		'order' => 'name'
	), $atts );

	extract($a);
	$order = ( in_array($order, $orderby_options) )? $order : 'name';
	$linkArr = get_bookmarks( array('orderby' => $order) );
	foreach ($linkArr as $link) {
		$postHTML .= '<p><a class="' . $link->link_category . '" href="' . $link->link_url . '" title="' . $link->description . '">' . $link->link_name . '</a></p>';
	}
	return $postHTML;
}
add_shortcode('show_bookmarks', 'oesa_show_bookmarks');

