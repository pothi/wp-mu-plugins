<?php
/*
 * Plugin Name: Snapmunk Optimizer
 * Plugin URI:  https://github.com/pothi/
 * Version:     0.1
 * Description: Automatically optimize CSS and JS (among other things)
 * Author:      Pothi Kalimuthu
 * Author URI:  http://pothi.info
 * License:     GPL
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// directly from https://wordpress.org/plugins/disable-emojis/

/**
 * Disable the emoji's
 */
function tiny_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );    
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );  
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'tiny_disable_emojis_tinymce' );
}
// add_action( 'init', 'tiny_disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 * 
 * @param    array  $plugins  
 * @return   array             Difference betwen the two arrays
 */
function tiny_disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}

/* loads loadCSS and script.js */
function tiny_hook_header_javascript () {
    $code = <<<'EOD'
<script type="text/javascript">
    document.documentElement.className = 'js';

    // https://github.com/filamentgroup/loadCSS
    function loadCSS( href, before, media, callback ){
        "use strict";
        var ss = window.document.createElement( "link" );
        var ref = before || window.document.getElementsByTagName( "script" )[ 0 ];
        var sheets = window.document.styleSheets;
        ss.rel = "stylesheet";
        ss.href = href;
        ss.media = "only x";
        if( callback ) {
            ss.onload = callback;
        }
        ref.parentNode.insertBefore( ss, ref );
        ss.onloadcssdefined = function( cb ){
            var defined;
            for( var i = 0; i < sheets.length; i++ ){
                if( sheets[ i ].href && sheets[ i ].href.indexOf( href ) > -1 ){
                    defined = true;
                }
            }
            if( defined ){
                cb();
            } else {
                setTimeout(function() {
                    ss.onloadcssdefined( cb );
                });
            }
        };
        ss.onloadcssdefined(function() {
            ss.media = media || "all";
        });
        return ss;
    }

    // script.js
    (function (name, definition) {
      if (typeof module != 'undefined' && module.exports) module.exports = definition()
      else if (typeof define == 'function' && define.amd) define(definition)
      else this[name] = definition()
    })('$script', function () {
      var doc = document
        , head = doc.getElementsByTagName('head')[0]
        , s = 'string'
        , f = false
        , push = 'push'
        , readyState = 'readyState'
        , onreadystatechange = 'onreadystatechange'
        , list = {}
        , ids = {}
        , delay = {}
        , scripts = {}
        , scriptpath
        , urlArgs

      function every(ar, fn) {
        for (var i = 0, j = ar.length; i < j; ++i) if (!fn(ar[i])) return f
        return 1
      }
      function each(ar, fn) {
        every(ar, function (el) {
          return !fn(el)
        })
      }

      function $script(paths, idOrDone, optDone) {
        paths = paths[push] ? paths : [paths]
        var idOrDoneIsDone = idOrDone && idOrDone.call
          , done = idOrDoneIsDone ? idOrDone : optDone
          , id = idOrDoneIsDone ? paths.join('') : idOrDone
          , queue = paths.length
        function loopFn(item) {
          return item.call ? item() : list[item]
        }
        function callback() {
          if (!--queue) {
        list[id] = 1
        done && done()
        for (var dset in delay) {
          every(dset.split('|'), loopFn) && !each(delay[dset], loopFn) && (delay[dset] = [])
        }
          }
        }
        setTimeout(function () {
          each(paths, function loading(path, force) {
        if (path === null) return callback()
        path = !force && path.indexOf('.js') === -1 && !/^https?:\/\//.test(path) && scriptpath ? scriptpath + path + '.js' : path
        if (scripts[path]) {
          if (id) ids[id] = 1
          return (scripts[path] == 2) ? callback() : setTimeout(function () { loading(path, true) }, 0)
        }

        scripts[path] = 1
        if (id) ids[id] = 1
        create(path, callback)
          })
        }, 0)
        return $script
      }

      function create(path, fn) {
        var el = doc.createElement('script'), loaded
        el.onload = el.onerror = el[onreadystatechange] = function () {
          if ((el[readyState] && !(/^c|loade/.test(el[readyState]))) || loaded) return;
          el.onload = el[onreadystatechange] = null
          loaded = 1
          scripts[path] = 2
          fn()
        }
        el.async = 1
        el.src = urlArgs ? path + (path.indexOf('?') === -1 ? '?' : '&') + urlArgs : path;
        head.insertBefore(el, head.lastChild)
      }

      $script.get = create

      $script.order = function (scripts, id, done) {
        (function callback(s) {
          s = scripts.shift()
          !scripts.length ? $script(s, id, done) : $script(s, callback)
        }())
      }

      $script.path = function (p) {
        scriptpath = p
      }
      $script.urlArgs = function (str) {
        urlArgs = str;
      }
      $script.ready = function (deps, ready, req) {
        deps = deps[push] ? deps : [deps]
        var missing = [];
        !each(deps, function (dep) {
          list[dep] || missing[push](dep);
        }) && every(deps, function (dep) {return list[dep]}) ?
          ready() : !function (key) {
          delay[key] = delay[key] || []
          delay[key][push](ready)
          req && req(missing)
        }(deps.join('|'))
        return $script
      }

      $script.done = function (idOrDone) {
        $script([null], idOrDone)
      }

      return $script
    });
</script>

<script async>
    // loadCSS( 'fontURL' );
    // loadCSS( '/wp-content/themes/theme_name/style.css' );
</script>
<noscript>
<!-- <link rel='stylesheet' id='divi-fonts-css'  href='fontURL' type='text/css' media='all' /> -->
<!-- <link rel='stylesheet' id='divi-fonts-css'  href='themestyleURL' type='text/css' media='all' /> -->
</noscript>
EOD;

    echo $code;
}
add_action( 'wp_head', 'tiny_hook_header_javascript', 999 );

/* loads loadCSS and script.js */
function tiny_hook_critical_css () {
    $code = <<<'EOD'
<style>
/* Critical CSS - uncompressed CSS can be found at critical.css in the plugin's folder */
EOD;

    echo $code;
}
add_action( 'wp_head', 'tiny_hook_critical_css', 0 );

/* load javascript at the footer */
function tiny_hook_footer_javascript () {
    $code = <<<'EOD'
<script type="text/javascript">

/* <![CDATA[ */
var somevar = 'somevalue';
/* ]]> */

// let's load jquery from Microsoft CDN
$script( '//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.2.min.js', 'jquery' );

$script.ready('jquery', function() {
    $script( '//ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js' );
    try{jQuery.noConflict();}catch(e){};
    $script( '/wp-content/themes/theme_name/js/somescript.js' );
});
</script>
EOD;

    echo $code;
}
add_action( 'wp_footer', 'tiny_hook_footer_javascript', 999 );

// de_queue script that can be loaded alternatively
add_action( 'wp_enqueue_scripts', function() {
    // Uncomment the following, if granvillephotobooth.com start using comments
    // if( is_home() || is_front_page() )
        // wp_dequeue_script( 'comment-reply' );

    // use this with care!!!
    // if( !is_admin() )
        // wp_deregister_script( 'jquery' );

    // sample: let's defer theme scripts inc lightbox script
    wp_dequeue_script( 'js_handle' );
}, 20);

// de_register the styles that load asynchronously
add_action( 'wp_enqueue_scripts', function() {
        // theme styles
        wp_deregister_style( 'style_handle' );
}, 20 );

// optimizi home page image/s
// add_filter('the_content', 'tiny_jetpack_images_optimization');
function tiny_jetpack_images_optimization() {
    if( wp_is_mobile() ) {
        // let's do something - resize images
    }
    // return $content;
}

// Add vary: user-agent header
add_filter('wp_headers', function ($headers) {
    $headers['Vary'] = 'User-Agent';
    return $headers;
});
