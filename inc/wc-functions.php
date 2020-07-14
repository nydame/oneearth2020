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

// Define is_woocommerce_related()
function is_woocommerce_related() {
    if (function_exists('is_woocommerce')) {
        return is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_wc_endpoint_url();
    } else {
        return false;
    }
}
add_action('init', 'is_woocommerce_related');
