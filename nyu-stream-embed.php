<?php
/*
Plugin Name: NYU Stream Embed (Accessibility Ready)
Description: Enables embedding an NYU Stream video/playlist from its URL or vid via the [nyustream] shortcode. Accepts attributes url(complete url), vid(video id), playlist(playlist id), w(width), h(height), title(title) and fullscreen(allow fullscreen). You can use values fullscreen = "no" or "false" to disable fullscreen.
Plugin URI: https://github.com/KonainM/nyu-stream-embeds.git
Author: Harshit Sanghvi, Konain Mukadam, Neel Shah
Version: 1.1
*/


define( 'NSE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

class NYU_Stream_Embed {

    /** @var string $text_domain The text domain of the plugin */
    var $text_domain = 'nse_trans';
    /** @var string $plugin_dir The plugin directory path */
    var $plugin_dir;
    /** @var string $plugin_url The plugin directory URL */
    var $plugin_url;
    /** @var string $domain The plugin domain */
    var $domain;
    /** @var string $options_name The plugin options string */
    var $options_name = 'nyu_stream_options';
    /** @var array $settings The plugin site options */
    var $settings;
    /** @var array $settings The plugin network options */
    var $network_settings;
    /** @var array $settings The plugin network or site options depending on localization in admin page */
    var $current_settings;

    /**
     * Constructor.
     */
    function __construct() {

        $this->init_vars();
        $this->init();
    }

    /**
     * Initiate plugin.
     *
     * @return void
     */
    function init() {
        add_action( 'init', array( &$this, 'load_plugin_textdomain' ), 0 );
        add_action( 'admin_init', array( &$this, 'nse_save_settings' ) );
        //add_action( 'admin_menu', array( &$this, 'nse_admin_menu' ) );
        add_action( 'network_admin_menu', array( &$this, 'nse_network_admin_menu' ) );

        //add CSS
        //add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );

        /* Add shortcode [nyu_stream] to any page to display NYU Stream Video over it */
        add_shortcode( 'nyustream', array( $this, 'nyu_stream_embed_video' ) );

    }

    /**
     * Initiate variables.
     *
     * @return void
     */
    function init_vars() {
        global $wpdb;

        if ( isset( $wpdb->site) )
            $this->domain = $wpdb->get_var( "SELECT domain FROM {$wpdb->site}" );

        $this->settings = $this->get_options();
        $this->network_settings = $this->get_options(null, 'network');
        $this->current_settings = is_network_admin() ? $this->network_settings : $this->settings;

        /* Set plugin directory path */
        $this->plugin_dir = NSE_PLUGIN_DIR;
        /* Set plugin directory URL */
        $this->plugin_url = plugin_dir_url(__FILE__);
    }

    /**
     * Add CSS
     * @todo remove, not yet used.
     * @return void
     */
    function admin_enqueue_scripts($hook) {
        // Including CSS file
    }

    /**
     * Loads the language file from the "languages" directory.
     *
     * @return void
     */
    function load_plugin_textdomain() {
        load_plugin_textdomain( $this->text_domain, null, dirname( plugin_basename( __FILE__ ) ) . '/includes/languages/' );
    }

    /**
     * Add Google Analytics options page.
     *
     * @return void
     */
    function nse_admin_menu() {
        if ( !is_admin() ) {
            return;
        } else {
            add_submenu_page( 'options-general.php', 'NYU Stream', 'NYU Stream', 'manage_options', 'nyu-stream-settings', array( &$this, 'output_site_settings_page' ) );
        }
    }

    /**
     * Add network admin menu
     *
     * @access public
     * @return void
     */
    function nse_network_admin_menu() {
        add_submenu_page( 'settings.php', 'NYU Stream', 'NYU Stream', 'manage_network', 'nyu-stream-settings', array( &$this, 'output_network_settings_page' ) );
    }


    /**
     * Update Google Analytics settings into DB.
     *
     * @return void
     */
    function nse_save_settings() {
        if ( isset( $_POST['submit'] ) ) {

            if ( wp_verify_nonce( $_POST['_wpnonce'], 'submit_nse_settings_network' ) ) {
            //save network settings
                $this->save_options( array('nse_settings' => $_POST), 'network' );

                wp_redirect( add_query_arg( array( 'page' => 'nyu-stream-settings', 'dmsg' => urlencode( __( 'Changes were saved!', $this->text_domain ) ) ), 'settings.php' ) );
                exit;
            }
            elseif ( wp_verify_nonce( $_POST['_wpnonce'], 'submit_nse_settings' ) ) {
            //save settings

                $this->save_options( array('nse_settings' => $_POST) );

                wp_redirect( add_query_arg( array( 'page' => 'nyu-stream-settings', 'dmsg' => urlencode( __( 'Changes were saved!', $this->text_domain ) ) ), 'options-general.php' ) );
                exit;
            }
        }
    }


    /**
     * Network settings page
     *
     * @access public
     * @return void
     */
    function output_network_settings_page() {
        /* Get Network settings */
        $this->output_site_settings_page( 'network' );
    }

    /**
     * Admin options page output
     *
     * @return void
     */
    function output_site_settings_page( $network = '' ) {
        require_once( $this->plugin_dir . "includes/page-settings.php" );
    }

    /**
     * Save plugin options.
     *
     * @param  array $params The $_POST array
     * @return void
     */
    function save_options( $params, $network = ''  ) {
        /* Remove unwanted parameters */
        unset( $params['_wpnonce'], $params['_wp_http_referer'], $params['submit'] );
        /* Update options by merging the old ones */

        if ( '' == $network )
            $options = get_option( $this->options_name );
        else
            $options = get_site_option( $this->options_name );

        if(!is_array($options))
            $options = array();

        $options = array_merge( $options, $params );

        if ( '' == $network )
            update_option( $this->options_name, $options );
        else
            update_site_option( $this->options_name, $options );
    }

    /**
     * Get plugin options.
     *
     * @param  string|NULL $key The key for that plugin option.
     * @return array $options Plugin options or empty array if no options are found
     */
    function get_options( $key = null, $network = '' ) {

        if ( '' == $network )
            $options = get_option( $this->options_name );
        else
            $options = get_site_option( $this->options_name );

        /* Check if specific plugin option is requested and return it */
        if ( isset( $key ) && array_key_exists( $key, $options ) )
            return $options[$key];
        else
            return $options;
    }


    /**
     * Encrypt text (SMTP password)
     *
     * @todo remove, not used
     **/
    private function _encrypt( $text ) {
        if  ( function_exists( 'mcrypt_encrypt' ) ) {
            return base64_encode( @mcrypt_encrypt( MCRYPT_RIJNDAEL_256, DB_PASSWORD, $text, MCRYPT_MODE_ECB ) );
        } else {
            return $text;
        }
    }

    /**
     * Decrypt password (SMTP password)
     *
     * @todo remove, not used
     **/
    private function _decrypt( $text ) {
        if ( function_exists( 'mcrypt_decrypt' ) ) {
            return trim( @mcrypt_decrypt( MCRYPT_RIJNDAEL_256, DB_PASSWORD, base64_decode( $text ), MCRYPT_MODE_ECB ) );
        } else {
            return $text;
        }
    }


    /**
     * NYU Stream Embed Video function
     *
     **/
    public function nyu_stream_embed_video( $atts ) {
        $is_playlist = 0;
        /* Assign default value for width and height for displaying stream playlist */
            $playlist['w'] = 740;
            $playlist['h'] = 330;
        $atts = shortcode_atts (
            array (
                0 => 0,
                'url' => 0,         // video url
                'vid' => 0,         // video id
                'playlist' => 0,    // playlist id
                'w' => 400,         // width
                'h' => 285,         // height
                'fullscreen' => 1,  // allow fullscreen
                'title' => array(), // allow manual title attribute
                ),
            $atts,
            'nyustream'
            );
        /* Validate all attributes */
        if( $atts['vid'] ) {
            $is_playlist = 0;
            /* If we are given vid directly, we are good to go */
        }
        /* Get vid from the url, it's the last element in the url */
        elseif( $atts['url'] ) {
            $atts['vid'] = explode( '/', $atts['url'] );
            $atts['vid'] = end( $atts['vid'] );
        }
        elseif( $atts['playlist'] ) {
            $is_playlist = 1;
            $playlist['w'] = $atts['w'];
            $playlist['h'] = $atts['h'];
            $atts['playlist'] = explode( '=', $atts['playlist'] );
            $atts['playlist'] = end( $atts['playlist'] );
        }
        /* if url or vid attribute is not mentioned we strip it value given to identify the video id from it */
        elseif( $atts[0] ) {
            $atts['vid'] = explode( '/', $atts[0] );
            $atts['vid'] = end( $atts['vid'] );
        }
        else {
            $error = 1;
        }
        /* Check if fullscreen is not to be allowed */
        if( $atts['fullscreen'] == 'no' || $atts['fullscreen'] == 'false' ) {
            $atts['fullscreen'] = '';
        }
        else {
            $atts['fullscreen'] = 'allowfullscreen webkitallowfullscreenmozAllowFullScreen';
        }
        /* Debug Code*/
        /*        echo '<br>Atts = ';
                print_r($atts);
                echo '<br>';
        */

        if (!empty($this->network_settings['nse_settings']['nse_settings_video_id'])) {
            $iframe_vid_id = $this->network_settings['nse_settings']['nse_settings_video_id'];
        }
        else {
            $iframe_vid_id = "kaltura_player";
        }

        if (!empty($this->network_settings['nse_settings']['nse_settings_video_src'])) {
            $iframe_vid_src = $this->network_settings['nse_settings']['nse_settings_video_src'];
        }
        else {
            $iframe_vid_src = "https://cdnapisec.kaltura.com/p/1674401/sp/167440100/embedIframeJs/uiconf_id/23435151/partner_id/1674401?iframeembed=true&amp;playerId=kaltura_player&amp;flashvars[mediaProtocol]=rtmp&amp;flashvars[streamerType]=rtmp&amp;flashvars[streamerUrl]=rtmp://www.kaltura.com:1935&amp;flashvars[rtmpFlavors]=1&amp;&amp;wid=1_f8okstds&amp;entry_id=";
        }

        if (!empty($this->network_settings['nse_settings']['nse_settings_playlist_src'])) {
            $iframe_play_src = $this->network_settings['nse_settings']['nse_settings_playlist_src'];
        }
        else {
            $iframe_play_src = "https://cdnapisec.kaltura.com/p/1674401/sp/167440100/embedIframeJs/uiconf_id/23437711/partner_id/1674401/widget_id/1_zh8d6z1g?iframeembed=true&playerId=kaltura_player_1390404249&flashvars[playlistAPI.autoContinue]=true&flashvars[playlistAPI.autoInsert]=true&flashvars[ks]=";
        }

        if(!empty($atts['title'])) //Konain's title function
        {
          $km_title = $atts['title'];
        }
        else
        {
          $km_title = "nyu_stream_video '. $iframe_vid_id .'";
        }

        $html .= '
            <div class = "nyu_stream" id = "nyu_stream" title = "'. $km_title .'">
        ';


        /* Embed NYU Stream Video if the vid or playlist is found from the user input shortcode */
        if ( $error != 1 ) {
            if( $is_playlist != 1 ) {
                $html .= '
                <iframe id="'. $iframe_vid_id .'" src="'. $iframe_vid_src . $atts['vid']. '" width="'.$atts['w'].'" height="'.
                $atts['h'].'" '.$atts['fullscreen'].' frameborder="0" title = "'. $km_title .'"></iframe>';
            }
            else {
                $html .=  '
                <iframe src="' . $iframe_play_src . $atts['playlist'] . '" width="'.$playlist['w'].'" height="'.$playlist['h'].'"'.
                ' allowfullscreen webkitallowfullscreen mozAllowFullScreen frameborder="0" aria-label="nyu stream playlist"></iframe>
                ';
            }
        }
        else {
            $html .= 'The NYU Stream video cannot be found';
        }

        $html .= '</div>';

        return $html;
    }



}

global $nyu_strem_embed;
$nyu_stream_embed = new NYU_Stream_Embed();
