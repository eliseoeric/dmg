<?php

class Zoho_Intergrator {

	private static $instance;
	public $zoho_crm_url;

	private function __construct() {
		$this->init();
		$this->register_gravity_form_hooks();
		$this->zoho_crm_url = "https://crm.zoho.com/crm/private/xml/";
	}

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function auth( $email, $password ) {
		$res    = wp_remote_post( 'https://accounts.zoho.com/apiauthtoken/nb/create?SCOPE=ZohoCRM/crmapi&EMAIL_ID=' . $email . '&PASSWORD=' . $password );
		$result = parse_str( preg_replace( '/\s+/', '&', $res['body'] ), $output );

		return $output['AUTHTOKEN'];
	}

	public function lead_form_to_zoho( $entry, $form ) {
		$options = get_option( 'dmg_options_group' );
		$auth    = $options['dmg_zoho_admin_auth'];

		$xml = urlencode( "<Leads><row no='1'><FL val='First Name'>{$entry['1']}</FL><FL val='Last Name'>{$entry['3']}</FL><FL val='Company'>{$entry['11']}</FL><FL val='Title'>{$entry['12']}</FL><FL val='Email'>{$entry['13']}</FL><FL val='Phone'>{$entry['4']}</FL><FL val='Mobile'>{$entry['5']}</FL><FL val='Industry'>{$entry['7']}</FL><FL val='Street'>{$entry['10.1']}</FL><FL val='City'>{$entry['10.3']}</FL><FL val='State'>{$entry['10.4']}</FL><FL val='Country'>{$entry['10.6']}</FL><FL val='Zip Code'>{$entry['10.5']}</FL><FL val='Email Opt Out'>{$entry['9']}</FL><FL val='Lead Owner'>{$entry['15']}</FL><FL val='Lead Source'>{$entry['6']}</FL><FL val='Description'>{$entry['16']}</FL></row>
					</Leads>" );
		$url = "https://crm.zoho.com/crm/private/xml/Leads/insertRecords?newFormat=1&authtoken={$auth}&scope=crmapi&xmlData={$xml}&version=2";
		$res = wp_remote_post(
			$url,
			array(
				'method'      => 'POST',
				'httpversion' => '0.9',
				'headers'     => array(
					'Content-Type' => 'text/xml'
				),
				'body'        => simplexml_load_string( urldecode( $xml ) )
			)
		);

		if ( $res['response']['code'] == 200 ) {
			echo '<h3>Your new lead has been added successfully!</h3><p>You may access this lead and edit further details via the <a href="https://crm.zoho.com/crm/ShowTab.do?module=Leads"> Zoho Dashboard found here</a>.</p>';
		} else {
			$error = $res['response']['code'];
			echo "<h3>Something went wrong.</h3><p>Please re-submit the form. If this error occurs again, please contact <a href='mailto:eric@thinkgeneric.com&subject=DMG Zoho Error Code{$error}'>Think Generic</a> for assistance.</p>";
		}
	}


	public function register_gravity_form_hooks() {
		add_action( 'gform_post_submission_3', array( $this, 'lead_form_to_zoho' ), 10, 2 );
	}

	public function init() {
		$this->register_admin();
	}

	/*
	* Register the UMA Theme Admin actions
	*/
	function register_admin() {
		add_action( 'admin_menu', array( $this, 'zoho_intergrate_theme_menu' ) );

		add_action( 'admin_init', array( $this, 'zoho_intergrate_admin_init' ) );
	}

	/*
	* Add the menu pages within the Wordpress Dashboard
	*/
	function zoho_intergrate_theme_menu() {
		// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page( 'Zoho Intergration Settings', 'Zoho Intergration Settings', 'manage_options', 'dmg_global_settings', array(
			$this,
			'dmg_global_options'
		), 'dashicons-analytics' );

	}

	/*
	* Register the UMA Theme setting groups and fields
	*/
	function zoho_intergrate_admin_init() {
		// register_setting( $option_group, $option_name, $sanitize_callback );
		register_setting( 'dmg_options_group', 'dmg_options_group', array( $this, 'dmg_options_group_validate' ) );

		// add_settings_section( $id, $title, $callback, $page );
		add_settings_section( 'dmg_global_settings_section', 'Zoho Settings', array(
			$this,
			'dmg_global_settings_section_text'
		), 'dmg_global_settings' );

		// add_settings_field( $id, $title, $callback, $page, $section, $args );
		add_settings_field( 'dmg_zoho_admin_email', 'Zoho Admin Email', array(
			$this,
			'dmg_zoho_admin_email'
		), 'dmg_global_settings', 'dmg_global_settings_section' );
		add_settings_field( 'dmg_zoho_admin_password', 'Zoho Admin Password', array(
			$this,
			'dmg_zoho_admin_password'
		), 'dmg_global_settings', 'dmg_global_settings_section' );
		add_settings_field( 'dmg_zoho_admin_auth', 'Zoho Admin Auth Code', array(
			$this,
			'dmg_zoho_admin_auth'
		), 'dmg_global_settings', 'dmg_global_settings_section' );


	}

	/*
	* Call back to validate the uma global options 
	*/
	function dmg_options_group_validate( $input ) {

		$newinput['dmg_zoho_admin_email']    = esc_html( trim( $input['dmg_zoho_admin_email'] ) );
		$newinput['dmg_zoho_admin_password'] = esc_html( trim( $input['dmg_zoho_admin_password'] ) );
		$newinput['dmg_zoho_admin_auth']     = esc_html( trim( $input['dmg_zoho_admin_auth'] ) );

		if ( empty( $newinput['dmg_zoho_admin_auth'] ) && ! empty( $newinput['dmg_zoho_admin_password'] ) ) {
			$newinput['dmg_zoho_admin_auth'] = Zoho_Intergrator::auth( $newinput['dmg_zoho_admin_email'], $newinput['dmg_zoho_admin_password'] );
		}

		return $newinput;
	}

	/*
	* Call baack function to render the Global Settings options page
	*/
	function dmg_global_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficent permissions to access this page. Please contact your administrator for more details.' ) );
		} ?>
		<div class="wrap">
			<form action="options.php" method="post">
				<?php settings_fields( 'dmg_options_group' ); ?>
				<?php do_settings_sections( 'dmg_global_settings' ); ?>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary"
				                         value="Save Changes"/></p>
				<?php Zoho_Intergrator::do_zoho_confirmation(); ?>
			</form>
		</div>
	<?php
	}

	/*
	* Render Global settings page 
	*/
	function dmg_global_settings_section_text() {
		?>
		<h2>Zoho and Gravity Forms intergration</h2>
		<p>This plugin allows Gravity Forms to post submissions to Zoho CRM</p>
		<p>Please enter your Admin Credentials below. If you do not have an Auth Code, one will be provided for you.</p>
	<?php
	}

	/*
	* Render the
	*/
	function dmg_zoho_admin_email() {
		$options = get_option( 'dmg_options_group' );
		?>
		<input id="dmg_zoho_admin_email" name="dmg_options_group[dmg_zoho_admin_email]" size="30" type="text"
		       value="<?php echo $options['dmg_zoho_admin_email']; ?>"/>
	<?php
	}

	/*
	* Render the
	*/
	function dmg_zoho_admin_password() {
		$options = get_option( 'dmg_options_group' );
		?>
		<input id="dmg_zoho_admin_password" name="dmg_options_group[dmg_zoho_admin_password]" size="30" type="password"
		       value="<?php echo $options['dmg_zoho_admin_password']; ?>"/>
	<?php
	}

	/*
	* Render the
	*/
	function dmg_zoho_admin_auth() {
		$options = get_option( 'dmg_options_group' );
		?>
		<?php if ( empty( $options['dmg_zoho_admin_auth'] ) ): ?>
			<input id="dmg_zoho_admin_auth" name="dmg_options_group[dmg_zoho_admin_auth]" size="30" type="text"
			       value="<?php echo $options['dmg_zoho_admin_auth']; ?>"/>
		<?php else: ?>
			<input id="dmg_zoho_admin_auth" name="dmg_options_group[dmg_zoho_admin_auth]" size="30" type="text"
			       value="<?php echo $options['dmg_zoho_admin_auth']; ?>" disabled/>
		<?php endif;
	}

	public static function do_zoho_confirmation() {
		$options = get_option( 'dmg_options_group' );
		?>
		<h3>Zoho Confirmation:</h3>
		<p>Below is debug info from the Zoho API </p>
	<?php
	}
}