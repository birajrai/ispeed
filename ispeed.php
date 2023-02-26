<?php
/**
 * Plugin Name: iSpeed
 * Plugin URI: https://birajrai.github.io/ispeed
 * Description: Add speed optimization to your website. Get free optimization
 * Version: 1.0
 * Requires at least: 5.2
 * Requires PHP: 5
 * Author: birajrai
 * Author URI: https://birajrai.github.io/
 * License: GPL v2l3
 */

$content = '
# Compress text, html, javascript, css, xml:

AddOutputFilterByType DEFLATE text/plain
AddOutputFilterByType DEFLATE text/html
AddOutputFilterByType DEFLATE text/xml
AddOutputFilterByType DEFLATE text/css
AddOutputFilterByType DEFLATE application/xml
AddOutputFilterByType DEFLATE application/xhtml+xml
AddOutputFilterByType DEFLATE application/rss+xml
AddOutputFilterByType DEFLATE application/javascript
AddOutputFilterByType DEFLATE application/x-javascript

# Enable browser caching

<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/jpg "access plus 1 year"
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/gif "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType text/css "access plus 1 month"
  ExpiresByType application/javascript "access plus 1 month"
</IfModule>


# Minifying CSS and JavaScript files
<IfModule mod_deflate.c>
  <IfModule mod_setenvif.c>
    BrowserMatch ^Mozilla/4 gzip-only-text/html
    BrowserMatch ^Mozilla/4\.[0678] no-gzip
    BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
  </IfModule>
  <IfModule mod_headers.c>
    Header append Vary User-Agent env=!dont-vary
  </IfModule>
  <IfModule mod_filter.c>
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE text/html text/plain text/xml
  </IfModule>
</IfModule>


# Minify HTML

<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/plain text/xml application/xml
</IfModule>
';

function edit_htaccess_file() {
    $htaccess_file = ABSPATH . '.htaccess';
    file_put_contents( $htaccess_file, $content, FILE_APPEND | LOCK_EX );
}

function deactivate_edit_htaccess_file() {
    $htaccess_file = ABSPATH . '.htaccess';
    $existing_content = file_get_contents( $htaccess_file );
    $new_content = str_replace( $content, '', $existing_content );
    file_put_contents( $htaccess_file, $new_content, LOCK_EX );
}


function add_page_caching() {
    if ( !is_user_logged_in() ) {
        $cache_time = 60 * 60 * 24; // 1 day in seconds
        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $cache_time ) . ' GMT' );
        header( 'Cache-Control: public, max-age=' . $cache_time );
    }
}
add_action( 'template_redirect', 'add_page_caching' );
