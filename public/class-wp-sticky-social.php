<?php
/**
 * WP Sticky Social
 *
 * @package   WP Sticky Social
 * @author    Vladislav Musilek
 * @license   GPL-2.0+
 * @copyright 2013 Vladislav Musilek
 *  
 */

class WP_Sticky_Social {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.2';

	/**
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'wp-sticky-social';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
  
  public $social_array = array(
        'facebook'    =>'Facebook',
        'twitter'     =>'Twitter',
        'pinterest'   =>'Pinterest',
        'github'      =>'Github',
        'linkedin'    =>'Linkedin',
        'dribble'     =>'Dribble',
        'stumble_upon'=>'Stumble Upon',
        'behance'     =>'Behance',
        'reddit'      =>'Reddit',
        'google_plus' =>'Google plus',
        'youtube'     =>'Youtube',
        'vimeo'       =>'Vimeo',
        'clickr'      =>'Flickr',
        'slideshare'  =>'Slideshare',
        'picassa'     =>'Picasa',
        'skype'       =>'Skype',
        'instagram'   =>'Instagram',
        'foursquare'  =>'Foursquare',
        'delicious'   =>'Delicious',
        'tumblr'      =>'Tumblr',
        'digg'        =>'Digg',
        'wordpress'   =>'Wordpress'
        );
   public $social_font = array(
        'facebook'    =>'facebook',
        'twitter'     =>'twitter',
        'pinterest'   =>'pinterest',
        'github'      =>'github',
        'linkedin'    =>'linkedin',
        'dribble'     =>'dribbble',
        'stumble_upon'=>'stumble-upon',
        'behance'     =>'behance',
        'reddit'      =>'reddit',
        'google_plus' =>'google-plus',
        'youtube'     =>'youtube',
        'vimeo'       =>'vimeo',
        'clickr'      =>'flickr',
        'slideshare'  =>'slideshare',
        'picassa'     =>'picassa',
        'skype'       =>'skype',
        'instagram'   =>'instagram',
        'foursquare'  =>'foursquare',
        'delicious'   =>'delicious',
        'tumblr'      =>'tumblr',
        'digg'        =>'digg',
        'wordpress'   =>'wordpress'
        );      

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		add_action( 'wp_footer', array( $this, 'display_wp_sticky_social' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		//Set plugin option
		$wp_sticky_social_option = array(
			'facebook_link'         => '#',
			'facebook_active'       => 0,
			'twitter_link'          => '#',
			'twitter_active'        => 0,
			'pinterest_link'        => '#',
			'pinterest_active'      => 0,
			'github_link'           => '#',
			'github_active'         => 0,
			'linkedin_link'         => '#',
			'linkedin_active'       => 0,
			'dribble_link'          => '#',
			'dribble_active'        => 0,
			'stumble_upon_link'     => '#',
			'stumble_upon_active'   => 0,
			'behance_link'          => '#',
			'behance_active'        => 0,
			'reddit_link'           => '#',
			'reddit_active'         => 0,
			'google_plus_link'      => '#',
			'google_plus_active'    => 0,
			'youtube_link'          => '#',
			'youtube_active'        => 0,
			'vimeo_link'            => '#',
			'vimeo_active'          => 0,
			'clickr_link'           => '#',
			'clickr_active'         => 0,
			'slideshare_link'       => '#',
			'slideshare_active'     => 0,
			'picassa_link'          => '#',
			'picassa_active'        => 0,
			'skype_link'            => '#',
			'skype_active'          => 0,
			'instagram_link'        => '#',
			'instagram_active'      => 0,
			'foursquare_link'       => '#',
			'foursquare_active'     => 0,
			'delicious_link'        => '#',
			'delicious_active'      => 0,
			'tumblr_link'           => '#',
			'tumblr_active'         => 0,
			'digg_link'             => '#',
			'digg_active'           => 0,
			'wordpress_link'        => '#',
			'wordpress_active'      => 0,
			'sticky_social_target' => 'on',
			'sticky_social_top_margin' => '40',
			'sticky_social_margin' => '120',
			'sticky_social_background_color' => '#333333',
			'sticky_social_text_color' => '#ffffff',
			'sticky_social_icon_color' => '#ffffff',
			'sticky_social_position' => 'left'
		);
    	update_option( 'wp-sticky-social', $wp_sticky_social_option );
 	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		//Delete plugin option
    	delete_option( 'wp-sticky-social' );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
    	wp_enqueue_style( 'social_foundicons', plugins_url( 'assets/foundation_icons_social/stylesheets/social_foundicons.css', __FILE__ ), array() );
    
  	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	  * @since    1.0.0
	 */
	public function display_wp_sticky_social() {
		$option = get_option('wp-sticky-social');
		?>
		<style>
		#sticky-social-wrap{
			<?php if($option['sticky_social_position']=='left'){ ?>
			left: 0px;
			<?php }else{ ?>
			right:0;
			<?php } ?>
				top:<?php  echo $option['sticky_social_top_margin']; ?>px;
			
		} 
		#sticky-social-list li{
			background-color:<?php  echo $option['sticky_social_background_color']; ?>;
			<?php if($option['sticky_social_position']=='left'){ ?>
			margin-left:-<?php  echo $option['sticky_social_margin']; ?>px;
			<?php }else{ ?>
			margin-left:<?php  echo $option['sticky_social_margin']; ?>px;
			<?php } ?>
			
		}
		#sticky-social-list li:hover{
			<?php if($option['sticky_social_position']=='left'){ ?>
			margin-left:0px;
			<?php }else{ ?>
			margin-left:0px;
			<?php } ?>
		}
		#sticky-social-list li a{float:left;
			color:<?php  echo $option['sticky_social_text_color']; ?>;
			<?php if($option['sticky_social_position']=='left'){ ?>
			text-align:right;
			<?php }else{ ?>
			text-align:left;
			<?php } ?>
		}
		#sticky-social-list li a span{display:inline-block;
			color:<?php  echo $option['sticky_social_icon_color']; ?>;
		}
		</style>
		
		<div id="sticky-social-wrap">
			<ul id="sticky-social-list">
			<?php foreach($this->social_array as $key => $item){ 
			if($option[$key.'_active']=='on'){ ?>
				<li>
				<a href="<?php echo $option[$key.'_link']; ?>" <?php if($option['sticky_social_target']=='on'){ echo 'target="_blank"'; } ?>>
					<?php if($option['sticky_social_position']=='right'){
					?>
					<span class="foundicon-<?php echo $this->social_font[$key]; ?>"></span>
					<?php } ?>
					<?php echo $item;
					if($option['sticky_social_position']=='left'){
					?>
					<span class="foundicon-<?php echo $this->social_font[$key]; ?>"></span>
					<?php } ?>
				</a>
				
				</li>
			<?php } 
				} ?>
			<ul>
	  	</div>
	<?php
	}

}//End class
