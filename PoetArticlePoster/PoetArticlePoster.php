<?php
/*
Plugin Name: Po.et Article Poster
Description: Post articles automatically on insertion and modification to po.et API
Version:     1.0.0
Author:      Mostafa Khater
Author URI:  mailto:mostafa@mostafakhater.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: poet_article_poster
Domain Path: /languages
*/

defined( 'ABSPATH' ) OR exit;

class PoetArticlePoster {

    /**
     * Holds plugin file location
     * @var string
     */
    private $plugin;

    /**
     * Initialization method
     * Used to make an object of the plugin class
     */
    public static function init() {
        $class = __CLASS__;
        new $class;
    }

    /**
     * PoetArticlePoster constructor.
     */
    public function __construct() {
        //Including Consumer class which is needed for API connection
        require_once 'Consumer.php';
        //Setting the plugin file location for later usage
        $this->plugin = plugin_basename(__FILE__);

        //Setting plugin actions

        register_activation_hook($this->plugin, array($this, 'activate'));
        register_deactivation_hook($this->plugin, array($this, 'deactivate'));
        register_uninstall_hook($this->plugin, array($this, 'uninstall'));
        add_filter('plugin_action_links_'.$this->plugin, array($this, 'add_settings_link'));
        add_action('poet_article_poster_set_default_values_on_activation', array($this, 'set_default_values'));
        add_action('admin_menu', array($this, 'add_options_page'));
        add_action('admin_init', array($this, 'register_setting'));
        add_action('save_post', array($this, 'post_article'));
    }


    /**
     * Activation method
     * Runs on plugin activation
     */
    public function activate()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        //Setting default values action to set settings default values on activation/reactivation
        do_action( 'poet_article_poster_set_default_values_on_activation' );
    }


    /**
     * Deactivation method
     * Runs on plugin deactivation
     */
    public function deactivate()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        //Unregister plugin settings on deactivation
        unregister_setting('poet_article_poster',
            'poet_article_poster_option',
            array($this, 'sanitize'));
    }

    /**
     * Uninstallation method
     * Runs on plugin deletion
     */
    public function uninstall()
    {
        if (!defined('WP_UNINSTALL_PLUGIN')) {
            return;
        }

        //Delete plugin option values from WordPress database
        delete_option('poet_article_poster_option');
    }

    /**
     * Setting plugin options default values
     */
    public function set_default_values(){
        $default = array(
            'api_url'     => 'https://app.po.et/api',
            'public_key'  => '',
            'private_key' => '',
            'active'      => 1
        );
        update_option('poet_article_poster_option', $default);
    }

    /**
     * Plugin settings page form
     */
    function create_options_page()
    {
        ?>  <div class="wrap">

        <form method="post" action="options.php"><?php
            settings_fields('poet_article_poster');
            do_settings_sections($this->plugin);
            submit_button(); ?>
        </form></div>
        <?php
    }

    /**
     * Registration of settings page in WordPress options menu
     */
    function add_options_page()
    {
        add_options_page(__('Po.et Article Poster Settings'),
            __('Po.et Article Poster Settings'),
            'manage_options',
            $this->plugin,
            array($this, 'create_options_page'));
    }

    /**
     * Adding Settings link in plugins page
     * The link redirects to plugin settings page
     * @param $links
     * @return mixed
     */
    function add_settings_link($links)
    {
        $url = menu_page_url(plugin_basename(__FILE__), false);
        $settings_link = '<a href="' . $url . '">' . __('Settings') . '</a>';
        array_push($links, $settings_link);
        return $links;
    }


    /**
     * Settings option fields registration
     */
    function register_setting()
    {
        register_setting('poet_article_poster',
            'poet_article_poster_option',
            array($this, 'sanitize'));

        add_settings_section(
            'poet_article_poster_setting_section_id', // ID
            __('Po.et Article Poster Settings'), // Title
            array($this, 'print_section_info'), // Callback
            $this->plugin // Page
        );

        add_settings_field(
            'api_url', // ID
            __('API URL'), // Title
            array($this, 'api_url_callback'), // Callback
            $this->plugin, // Page
            'poet_article_poster_setting_section_id' // Section
        );

        add_settings_field(
            'public_key', // ID
            __('Public Key/Profile ID'), // Title
            array($this, 'public_key_callback'), // Callback
            $this->plugin, // Page
            'poet_article_poster_setting_section_id' // Section
        );

        add_settings_field(
            'private_key', // ID
            __('Private Key'), // Title
            array($this, 'private_key_callback'), // Callback
            $this->plugin, // Page
            'poet_article_poster_setting_section_id' // Section
        );

        add_settings_field(
            'active', // ID
            __('Post articles to API automatically on insert or update?'), // Title
            array($this, 'active_callback'), // Callback
            $this->plugin, // Page
            'poet_article_poster_setting_section_id' // Section
        );
    }

    /**
     * Prints instruction string in top of settings page
     */
    function print_section_info()
    {
        print __('Enter API URL, Public Key/Profile ID, and Private Key (this will return to default value if the plugin deactivated and reactivated again):');
    }

    /**
     * Sanitizes option fields data
     * @param $input
     * @return array
     */
    function sanitize($input)
    {
        $new_input = array();

        if (isset($input['api_url']))
            $new_input['api_url'] = sanitize_text_field($input['api_url']);
        if (isset($input['public_key']))
            $new_input['public_key'] = sanitize_text_field($input['public_key']);
        if (isset($input['private_key']))
            $new_input['private_key'] = sanitize_text_field($input['private_key']);
        if (isset($input['active']))
            $new_input['active'] = sanitize_text_field($input['active']);

        return $new_input;
    }

    /**
     * Returns API URL field input
     */
    function api_url_callback()
    {
        printf(
            '<input type="text" id="api_url" name="poet_article_poster_option[api_url]" value="%s" required />',
            isset(get_option('poet_article_poster_option')['api_url']) ? esc_attr(get_option('poet_article_poster_option')['api_url']) : ''
        );
    }

    /**
     * Returns Public Key field input
     */
    function public_key_callback()
    {
        printf(
            '<input type="text" id="public_key" name="poet_article_poster_option[public_key]" value="%s" required />',
            isset(get_option('poet_article_poster_option')['public_key']) ? esc_attr(get_option('poet_article_poster_option')['public_key']) : ''
        );
    }

    /**
     * Returns Private Key field input
     */
    function private_key_callback()
    {
        printf(
            '<input type="text" id="private_key" name="poet_article_poster_option[private_key]" value="%s" required />',
            isset(get_option('poet_article_poster_option')['private_key']) ? esc_attr(get_option('poet_article_poster_option')['private_key']) : ''
        );
    }

    /**
     * Returns activation checkbox input
     */
    function active_callback()
    {
        $checked = isset(get_option('poet_article_poster_option')['active']) ? 1 : 0;
        echo '<input type="checkbox" id="active" name="poet_article_poster_option[active]" '.checked(1, $checked, false).' />';
    }

    /**
     * Called on WordPress post saving (insertion/modifications)
     * @param $post_id
     */
    function post_article($post_id)
    {
        $active = isset(get_option('poet_article_poster_option')['active']) ? 1 : 0;
        $post = get_post($post_id);

        //Checking if plugin is activated in its settings page and the post status is publish to make sure it is not just a draft
        if(!$active || $post->post_status != 'publish') {
            return;
        }

        //Getting API credentials set in plugin settings page
        $url = isset(get_option('poet_article_poster_option')['api_url']) ? get_option('poet_article_poster_option')['api_url'] : '';
        $public_key = isset(get_option('poet_article_poster_option')['public_key']) ? get_option('poet_article_poster_option')['public_key'] : '';
        $private_key = isset(get_option('poet_article_poster_option')['private_key']) ? get_option('poet_article_poster_option')['private_key'] : '';

        //Generating Consumer object with credentials sent to its constructor
        $consumer = new Consumer($url, $public_key, $private_key, $post);

        //Posting the article to the API
        try{
            $consumer->consume();
        } catch (Exception $exception) {

        }

    }
}

PoetArticlePoster::init();

?>
