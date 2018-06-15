<?php

class Wpfancy_Fontawesome {
	
	private static $instance;
    const VERSION = '3.2.1';

    private static function has_instance() {
        return isset(self::$instance) && self::$instance != null;
    }

    public static function get_instance() {
        if (!self::has_instance())
            self::$instance = new Wpfancy_Fontawesome;
        return self::$instance;
    }

    public static function setup() {
        self::get_instance();
    }

    protected function __construct() {
        if (!self::has_instance()) {
            add_action('init', array(&$this, 'init'));
        }
    }

    public function init() {
        add_action('wp_enqueue_scripts', array(&$this, 'register_plugin_styles'));
        add_action('admin_enqueue_scripts', array(&$this, 'register_plugin_styles'));
        add_shortcode('icon', array($this, 'setup_shortcode'));
        add_filter('widget_text', 'do_shortcode');
		
        if ((current_user_can('edit_posts') || current_user_can('edit_pages')) &&
                get_user_option('rich_editing')) {
                	
			add_filter( 'mce_external_plugins', array($this, 'register_tinymce_plugin') );
		    // Add to line 1 form WP TinyMCE
		    add_filter( 'mce_buttons', array($this, 'add_tinymce_buttons') );
			
            add_filter('mce_css', array(&$this, 'add_tinymce_editor_sytle'));
        }
    }

    public function register_plugin_styles() {
        global $wp_styles;
		wp_enqueue_style('font-awesome-ie7', get_template_directory_uri().'/resources/css/font-awesome-ie7.min.css');
    }

    public function setup_shortcode($params) {
        
		if(strrpos($params['name'],'fa-') === FALSE) {
			$params['name'] = 'fa-'.$params['name'];
		}
        return '<i class="fa '. $params['name'] . '">&nbsp;</i>';
    }

    public function register_tinymce_plugin($plugin_array) {
        $plugin_array['font_awesome_glyphs'] = get_template_directory_uri().'/resources/js/font-awesome.js';
        return $plugin_array;
    }

    public function add_tinymce_buttons($buttons) {
        array_push($buttons, 'fontAwesomeGlyphSelect');
        return $buttons;
    }

    public function add_tinymce_editor_sytle($mce_css) {
        $mce_css .= ', ' . get_template_directory_uri().'/includes/fontawesome/editor_styles.css';
        return $mce_css;
    }
}

Wpfancy_Fontawesome::setup();
