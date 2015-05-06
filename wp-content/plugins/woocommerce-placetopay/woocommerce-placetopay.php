<?php
/**
 * @wordpress-plugin
 * Plugin Name:       WooCommerce - Place to Pay
 * Plugin URI:        https://www.placetopay.com/component/placetopay-for-woocommerce/
 * Description:       Adds Place to Pay Payment Gateway to Woocommerce e-commerce plugin
 * Author:            PlacetoPay
 * Author URI:        https://www.placetopay.com/
 *
 * Version:           1.3.2
 * Requires at least:
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Domain Path:       /i18n/languages/
 *
 * @author Soporte <soporte@placetopay.com>
 * @copyright (c) 2013-2015 EGM Ingenieria sin fronteras S.A.S.
 * @version $Id: cron.php,v 1.3.2 2015/03/05 16:29:00 ingenieria Exp $
 */

// aborte si es accesado directamente
if (!defined('ABSPATH')) exit();

// agrega la rutina de inicialización de Place to Pay
add_action('plugins_loaded', 'init_placetopay_class');

// funcion para agregar js de acuerdo a la version
function wp_woocommerce_addjs( $js ) {
  global $woocommerce;
  
	if ( version_compare( $woocommerce->version, "2.1", ">=" ) ) {
		wc_enqueue_js( $js );
	} else {
		$woocommerce->add_inline_js( $js );
	}
}

function init_placetopay_class() {
	// verifica que la clase de WooCommerce de medios de pago esté cargada
	if (!class_exists('WC_Payment_Gateway')) return;

	// carga las traducciones de Place to Pay
	load_plugin_textdomain('woocommerce-placetopay', false,
		dirname(plugin_basename(__FILE__)) . '/languages/');

	function add_gateway_placetopay($methods) {
		$methods[] = 'WC_Gateway_PlacetoPay';
		return $methods;
	}

	function action_links_placetopay($links) {
		$customLinks = array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_gateway_placetopay'),
				__('Settings', 'woocommerce-placetopay')
			)
		);
		return array_merge($links, $customLinks);
	}

	// agrega PlacetoPay como un gateway para WooCommerce
	add_filter('woocommerce_payment_gateways', 'add_gateway_placetopay');

	// agrega los vínculos a mostrar al lado del plugin
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'action_links_placetopay');

	// carga la clase de
	class WC_Gateway_PlacetoPay extends WC_Payment_Gateway
	{
		const VERSION = '1.3.2';

		private $urlPlacetoPayImages = 'https://www.placetopay.com/images/providers/';
		private $urlPlacetoPayRedirect;
		private $urlPlacetoPayWS;

		public function __construct()
		{
			global $woocommerce;

			$this->version      = self::VERSION;
			$this->id           = 'placetopay';
			$this->method_title = __('Place to Pay', 'woocommerce-placetopay');
			$this->method_description = __('', 'woocommerce-placetopay');
			$this->icon         = plugins_url(
					'assets/images/placetopay.png',
					__FILE__
				);
			$this->has_fields   = true;

			// define los parámetros de configuración
			$this->init_form_fields();

			// carga la configuración
			$this->init_settings();

			// la URL base de retorno siempre es la misma
			$returnUrl = home_url('/');
			if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' ) {
				$returnUrl = str_replace( 'http:', 'https:', $returnUrl );
			}
			$this->settings['returnUrl'] = $returnUrl;

			// obtiene los valores de confirguración
			$this->enabled            = $this->settings['enabled'];
			$this->title              = $this->get_option('title');
			$this->description        = $this->get_full_payment_description();
			$this->testMode           = $this->get_option('testMode');
			$this->debug              = $this->get_option('debug');

			$this->merchantID         = $this->get_option('merchantID');
			$this->merchantName       = $this->get_option('merchantName');
			$this->merchantPhone      = $this->get_option('merchantPhone');
			$this->merchantEmail      = $this->get_option('merchantEmail');

			$this->paymentDescription = $this->get_option('paymentDescription');
			$this->customerSiteID     = $this->get_option('customerSiteID');
			$this->returnUrl          = $this->get_option('returnUrl');
			$this->programPathGnuPG   = $this->get_option('programPathGnuPG');
			$this->homeDirectoryGnuPG = $this->get_option('homeDirectoryGnuPG');
			$this->keyID              = $this->get_option('keyID');
			$this->passPhrase         = $this->get_option('passPhrase');
			$this->recipientKeyID     = $this->get_option('recipientKeyID');

			// define las URLs de Place to Pay basado en el modo
			if ($this->testMode == 'yes') {
				$this->urlPlacetoPayRedirect = 'https://test.placetopay.com/payment.php';
				$this->urlPlacetoPayWS = 'https://test.placetopay.com/soap/PlacetoPay/?wsdl';
			} else {
				$this->urlPlacetoPayRedirect = 'https://www.placetopay.com/payment.php';
				$this->urlPlacetoPayWS = 'https://www.placetopay.com/soap/PlacetoPay/?wsdl';
			}

			// determina si se ha habilitado la depuración
			if ($this->debug == 'yes')
				$this->log = $woocommerce->logger();

			// agrega el hook para que almacene las opciones de configuración
			if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=')) {
				add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
			} else {
				add_action('woocommerce_update_options_payment_gateways', array($this, 'process_admin_options'));
			}

			// agrega el hook de salida y reentrada de Place to Pay
			add_action('woocommerce_receipt_' . $this->id, array($this, 'process_redirect'));
			add_action('woocommerce_thankyou_' . $this->id, array($this, 'process_response'));
			add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'order_details'));

			// si esta en la consola administrativa instala o actualiza las estructuras
			if (is_admin()) {
				// si no está instalado entonces proceda
				if (!get_option('PLACETOPAY_Installed', false))
					$this->install();

				// versiones que requieren ser actualizadas
				$upgradeRequired = array('1.2.1');

				// versión actual instalada
				$currentVersion = get_option('PLACETOPAY_Version', '1.0.0');

				// verifica la versión instalada para determinar si actualiza
				foreach ($upgradeRequired as $version) {
					if (version_compare($currentVersion, $version, '<'))
						$this->upgrade($version);
				}

				// establece que está en la nueva versión
				update_option('PLACETOPAY_Version', $this->version);
			}

			// si está habilitado, pero mal configurado y no es la consola de administracion entonces lo inhabilita
			if ( !$this->is_valid_for_use() && !current_user_can( 'manage_woocommerce' ) )
				$this->enabled = 'no';

			// si está habilitado en el momento del checkout muestra el mensaje
			// de advertencia de las transacciones pendientes, incrementa la prioridad
			if ($this->enabled == 'yes') {
				add_action('woocommerce_before_checkout_form', array($this, 'checkout_message'), 5);
			}
		}

		/**
		 * Inicializa los campos del formulario de configuración
		 * @return void
		 */
		public function init_form_fields()
		{
			// acorde al sistema operativo reconocido por PHP trata de establecer la localización
			// del GnuPG para el valor por defecto
			$os = strtoupper(PHP_OS);
			if (substr($os, 0, 3) == 'WIN')
				$programPathGnuPG = 'C:\Program Files (x86)\GNU\GnuPG\gpg.exe';
			elseif (substr($os, 0, 6) == 'DARWIN')
				$programPathGnuPG = '/usr/local/bin/gpg';
			else
				$programPathGnuPG = '/usr/bin/gpg';

			// la ubicación del anillo de llaves, que en este caso está en la misma ubicación que el componente
			$homeDirectoryGnuPG = dirname(__FILE__) . DIRECTORY_SEPARATOR . '.gnupg';

			$logosInfo = $this->get_franchises();
			$logosOptions = array();
			foreach ($logosInfo as $key => $info) {
				$logosOptions[$key] = $info['label'];
			}
			$this->form_fields = array(
				'enabled' => array(
					'title' => __( 'Enable/Disable', 'woocommerce' ),
					'label' => __( 'Enable Place to Pay', 'woocommerce-placetopay' ),
					'type' => 'checkbox',
					'default' => 'no'
				),
				'title' => array(
					'title' => __( 'Title', 'woocommerce' ),
					'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
					'type' => 'text',
					'default' => __( 'Place to Pay - Credit cards and debits account', 'woocommerce-placetopay' ),
					'desc_tip' => true,
				),
				'description' => array(
					'title' => __( 'Description', 'woocommerce' ),
					'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ),
					'type' => 'textarea',
					'default' => __( 'Pay via Place to Pay; you can pay with credit cards or debit accounts (check and saving); another payment methods could be available.', 'woocommerce-placetopay' )
				),
				'paymentLogos' => array(
					'title' => __( 'Payment method logos', 'woocommerce-placetopay' ),
					'description' => __( 'Select the payment methods that you store have available to show in the Checkout page', 'woocommerce-placetopay' ),
					'type' => 'multiselect',
					'options' => $logosOptions,
					'default' => '',
				),
				'paymentDescription' => array(
					'title' => __( 'Payment Description', 'woocommerce-placetopay' ),
					'description' => __( 'For certification process, the text sended to the financial entities', 'woocommerce-placetopay' ),
					'type' => 'text',
					'default' => __( 'Place to Pay Payment - %s', 'woocommerce-placetopay'),
				),
				'merchantName' => array(
					'title' => __( 'Merchant Name', 'woocommerce-placetopay' ),
					'description' => __( 'Enter the company name like appears in the Commerce Chamber or your Brand', 'woocommerce-placetopay' ),
					'type' => 'text',
					'default' => '',
					'desc_tip' => true,
				),
				'merchantID' => array(
					'title' => __( 'Merchant ID', 'woocommerce-placetopay' ),
					'description' => __( 'Provide the registration number of your company like appears in the Tax Administration records', 'woocommerce-placetopay' ),
					'type' => 'text',
					'default' => '',
					'desc_tip' => true,
				),
				'merchantPhone' => array(
					'title' => __( 'Phone number', 'woocommerce-placetopay' ),
					'description' => __( 'Provide the phone number used for the inquiries or support in your shop', 'woocommerce-placetopay' ),
					'type' => 'text',
					'default' => '',
					'desc_tip'      => true,
				),
				'merchantEmail' => array(
					'title' => __( 'Email', 'woocommerce-placetopay' ),
					'description' => __( 'Provide contact email', 'woocommerce-placetopay' ),
					'type' => 'text',
					'default' => '',
					'desc_tip'      => true,
				),
				'customerSiteID' => array(
					'title' => __( 'Customer Site ID', 'woocommerce-placetopay' ),
					'description' => __( 'Identification issued by Place to Pay to the merchant', 'woocommerce-placetopay' ),
					'type' => 'text',
					'default' => '',
					'placeholder' => '{00000000-0000-0000-0000-000000000000}',
				),
				'returnUrl' => array(
					'title' => __( 'Callback Url', 'woocommerce-placetopay' ),
					'description' => __('This callback URL must be configured in Place to Pay.', 'woocommerce-placetopay'),
					'type' => 'text',
					'desc_tip' => true,
				),
				'programPathGnuPG' => array(
					'title' => __( 'GPG Executable Path', 'woocommerce-placetopay' ),
					'description' => __( 'Full path to the GnuPG executable file', 'woocommerce-placetopay' ),
					'type' => 'text',
					'default' => $programPathGnuPG,
				),
				'homeDirectoryGnuPG' => array(
					'title' => __( 'Keyring Home Directory', 'woocommerce-placetopay' ),
					'description' => __( 'Directory where the keyring is founded', 'woocommerce-placetopay' ),
					'type' => 'text',
					'default' => $homeDirectoryGnuPG,
				),
				'keyID' => array(
					'title' => __( 'Merchant Key', 'woocommerce-placetopay' ),
					'description' => __( 'GnuPG Key ID created by the merchant to connect with Place to Pay', 'woocommerce-placetopay' ),
					'type' => 'select',
					'options' => array(),
					'default' => '',
				),
				'passPhrase' => array(
					'title' => __( 'Merchant Passphrase', 'woocommerce-placetopay' ),
					'description' => __( 'Password used at the time of the creation of the key', 'woocommerce-placetopay' ),
					'type' => 'password',
					'default' => '',
				),
				'recipientKeyID' => array(
					'title' => __( 'Place to Pay Key ID', 'woocommerce-placetopay' ),
					'description' => __( 'GnuPG Place to Pay Key', 'woocommerce-placetopay' ),
					'type' => 'select',
					'options' => array(),
					'default' => '',
				),
				'debug' => array(
					'title' => __( 'Debug Log', 'woocommerce' ),
					'label' => __( 'Enable logging', 'woocommerce' ),
					'description' => sprintf( __( 'Log Place to Pay events, such as IPN requests, inside <code>woocommerce/logs/placetopay-%s.txt</code>', 'woocommerce-placetopay' ), sanitize_file_name( wp_hash( 'placetopay' ) ) ),
					'type' => 'checkbox',
					'default' => 'no',
				)
			);
		}

		/**
		 * Genera el HTML que muestra los parámetros de configuración
		 * @return string el HTML con
		 */
		public function generate_settings_html($form_fields = false)
		{
			if (!$form_fields)
				$form_fields = $this->form_fields;

			// use la clase de GnuPG para obtener las llaves
			if (!empty($this->programPathGnuPG)
				&& !empty($this->homeDirectoryGnuPG)) {

				if (!file_exists($this->programPathGnuPG)) {
					$secret = array(
						'' => __('The executable path of GnuPG (gpg) does not exist.', 'woocommerce-placetopay')
						);
					$public = $secret;
				} elseif (!is_executable($this->programPathGnuPG)) {
					$secret = array(
						'' => __('The gpg file can not be called, check execute permissions.', 'woocommerce-placetopay')
						);
					$public = $secret;
				} elseif (!is_dir($this->homeDirectoryGnuPG)) {
					$secret = array(
						'' => __('The route keyring does not exist.', 'woocommerce-placetopay')
						);
					$public = $secret;
				} else {
					require_once dirname(__FILE__) . '/classes/egmGnuPG.class.php';

					$gnuPG = new egmGnuPG($this->programPathGnuPG, $this->homeDirectoryGnuPG);
					$secret = $this->get_gnupg_keys($gnuPG->ListKeys('secret'), $gnuPG->error);
					$public = $this->get_gnupg_keys($gnuPG->ListKeys('public'), $gnuPG->error);
					unset($gnuPG);
				}

				// load the available Keys in the Keyring
				$form_fields['keyID']['options'] = $secret;
				$form_fields['recipientKeyID']['options'] = $public;
			}

			return parent::generate_settings_html($form_fields);
		}

		/**
		 * Determina si el medio de pago está disponible
		 * @return boolean
		 */
		public function is_available()
		{
			$enabled = parent::is_available();
			if ($enabled) {
				$enabled = !(
					empty($this->customerSiteID) ||
					empty($this->programPathGnuPG) ||
					empty($this->homeDirectoryGnuPG) ||
					empty($this->keyID) ||
					empty($this->passPhrase) ||
					empty($this->recipientKeyID) ||
					!$this->is_guid($this->customerSiteID) ||
					!file_exists($this->programPathGnuPG) ||
					!is_executable($this->programPathGnuPG) ||
					!is_dir($this->homeDirectoryGnuPG)
				);
			}
			return $enabled;
		}

		/**
		 * Valida que las configuraciones del medio de pago estén bien establecidas
		 * @return boolean
		 */
		private function is_valid_for_use()
		{
			$isValid = true;

			if ( empty($this->customerSiteID) || !$this->is_guid($this->customerSiteID) ) {
				add_action( 'admin_notices', array( $this, 'customersiteid_invalid_message' ) );
				$isValid = false;
			}
			if ( empty($this->programPathGnuPG) || !file_exists($this->programPathGnuPG) || !is_executable($this->programPathGnuPG) ) {
				add_action( 'admin_notices', array( $this, 'programpathgnupg_invalid_message' ) );
				$isValid = false;
			}
			if ( empty($this->homeDirectoryGnuPG) || !is_dir($this->homeDirectoryGnuPG) ) {
				add_action( 'admin_notices', array( $this, 'homedirectorygnupg_invalid_message' ) );
				$isValid = false;
			}
			if ( empty($this->keyID) ) {
				add_action( 'admin_notices', array( $this, 'keyid_invalid_message' ) );
				$isValid = false;
			}
			if ( empty($this->passPhrase) ) {
				add_action( 'admin_notices', array( $this, 'passphrase_invalid_message' ) );
				$isValid = false;
			}
			if ( empty($this->recipientKeyID) ) {
				add_action( 'admin_notices', array( $this, 'recipientkeyid_invalid_message' ) );
				$isValid = false;
			}

			return $isValid;
		}

		/**
		 * Muestra las opciones del panel de administración
		 * @return void
		 */
		public function admin_options()
		{
			?>
			<div id="wc_admin_placetopay">
				<h3><?php _e( 'Place to Pay', 'woocommerce-placetopay' ); ?></h3>
				<p><?php _e( 'Place to Pay works by sending the user to Place to Pay to enter their payment information in a secure way while keeping you in control of the design of your site.', 'woocommerce-placetopay' ); ?></p>
				<p><a href="https://www.placetopay.com/admin/" target="_blank" class="button button-primary"><?php _e( 'Place to Pay Admin console', 'woocommerce-placetopay' ); ?></a></p>
			</div>
			<table class="form-table">
				<?php $this->generate_settings_html(); ?>
			</table>
			<?php
		}

		public function process_payment($order_id)
		{
			global $wpdb, $woocommerce;

			// obtiene la información de la orden
			$order = new WC_Order($order_id);

			// valida que se pueda encriptar y firmar, para ello solo realiza una prueba

			// instancia el componente de Place to Pay
			require_once dirname(__FILE__) . '/classes/egmGnuPG.class.php';
			$gpg = new egmGnuPG($this->programPathGnuPG, $this->homeDirectoryGnuPG);
			$crypted = $gpg->Encrypt($this->keyID, $this->passPhrase, $this->recipientKeyID, 'Place to Pay Test');
			if ($crypted === false) {
				$errorMessage = __('Can not generate secure data to connect with Place to Pay.', 'woocommerce-placetopay');
				if ('yes' == $this->debug) {
					$errorMessage .= PHP_EOL . $gpg->error;
					$this->log->add('PlacetoPay', $errorMessage);
				}

				// informa del error
				if ( version_compare( $woocommerce->version, "2.3.5", ">=" ) ) {
					wc_add_notice( '<strong>' . $btn['label'] . '</strong> ' . __( 'Payment error:', 'woothemes' ). ' ' . nl2br($errorMessage), 'error' );
				} else {
					$woocommerce->add_error(__('Payment error:', 'woothemes') . ' ' . nl2br($errorMessage));
				}				
				return false;
			}

			// va a receipt_page
			return array(
				'result' 	=> 'success',
				'redirect'	=> $order-> get_checkout_payment_url(true)
			);
		}

		/**
		 * Genera el formulario con los datos a enviar a Place to Pay
		 * @param int $order_id
		 * @return void
		 */
		private function generate_placetopay_form($order_id)
		{
			global $woocommerce, $wpdb;

			try {
				// obtiene la información de la orden
				$order = new WC_Order($order_id);

				// recorre los productos de la orden para determinar la base de devolución
				// solo productos con el 10% o 16% de IVA
				$devolutionBase = 0;
				foreach ($order->get_items() as $item) {
					$taxRatePercentage = round($item['line_tax'] / $item['line_total'] * 100, 2);
					if (($taxRatePercentage == 10) || ($taxRatePercentage == 16))
						$devolutionBase += $item['line_total'];
				}

				require_once dirname(__FILE__) . '/classes/PlacetoPay.class.php';

				// establece las propiedades del componente
				$p2p = new PlacetoPay();
				$p2p->setGPGProgramPath($this->programPathGnuPG);
				$p2p->setGPGHomeDirectory($this->homeDirectoryGnuPG);
				$p2p->setCurrency(get_woocommerce_currency());
				$p2p->setLanguage(strtoupper(substr(get_bloginfo('language'), 0, 2)));
				$p2p->setPayerInfo(null, null,
					$order->billing_first_name . ' ' . $order->billing_last_name,
					$order->billing_email,
					$order->billing_address_1 . (empty($order->billing_address_2) ? '': PHP_EOL . $order->billing_address_2),
					$order->billing_city,
					$order->billing_state,
					$order->billing_country,
					$order->billing_phone,
					null);
				$p2p->setBuyerInfo(null, null,
					$order->shipping_first_name . ' ' . $order->shipping_last_name,
					null,
					$order->shipping_address_1 . (empty($order->shipping_address_2) ? '': PHP_EOL . $order->shipping_address_2),
					$order->shipping_city,
					$order->shipping_state,
					$order->shipping_country,
					$order->shipping_phone,
					null);
				$p2p->addAdditionalData('Component', 'woocommerce-placetopay ' . $this->version);
				$p2p->addAdditionalData('CustomerID', ($order->user_id == null) ? 'Guest' : $order->user_id);
				$p2p->setOverrideReturn($this->get_return_url($order));

				// por alguna razón las funciones de redirección de WordPress dañan los datos
				// a transferir, así que se opta por generar el formulario y realizar el auto
				// submit a Place to Pay
				$html = $p2p->getPaymentHiddenFields($this->keyID, $this->passPhrase, $this->recipientKeyID, $this->customerSiteID,
					$order->id, $order->get_total(), $order->get_total_tax(), $devolutionBase);

				// agrega información a la orden para notificar que salió a Place to Pay
				// e invalida el carro de compras
				$order->update_status('on-hold', __( 'Redirecting to Place to Pay', 'woocommerce-placetopay'));

				// agrega la orden a la lista de pendientes
				$wpdb->insert($wpdb->prefix . 'woocommerce_placetopay_pending', array(
						'order_id' => $order->id,
						'customer_id' => $order->user_id,
						'timestamp' => time(),
						'currency' => $p2p->getCurrency(),
						'amount' => $order->get_total()
					));

				// limpia el carro de compras para que no pueda ser modificado por el usuario
				$woocommerce->cart->empty_cart();

				// agrega el JS de auto-submit
				wp_woocommerce_addjs('
					jQuery("body").block({
							message: "' . esc_js(__(__('We are now redirecting you to Place to Pay to make payment, if you are not redirected please press the bottom.', 'woocommerce-placetopay'))) . '",
							baseZ: 99999,
							overlayCSS:
							{
								background: "#fff",
								opacity: 0.6
							},
							css: {
								padding:        "20px",
								zindex:         "9999999",
								textAlign:      "center",
								color:          "#555",
								border:         "3px solid #aaa",
								backgroundColor:"#fff",
								cursor:         "wait",
								lineHeight:		"24px",
							}
						});
					jQuery("#submit_placetopay_payment_form").click();
				');

				// genera el código JS que hace auto submit
				return '<form action="'.esc_url(PlacetoPay::PAYMENT_URL).'" method="post" name="placetopay_payment_form" id="placetopay_payment_form" target="_top">
					' . $html . '<input type="submit" class="button-alt" id="submit_placetopay_payment_form" value="'
					. __('Pay with Place to Pay', 'woocommerce-placetopay').'" /> <a class="button cancel" href="'
					. esc_url( $order->get_cancel_order_url() ).'">'.__('Cancel order &amp; restore cart', 'woocommerce').'</a></form>';
			} catch(Exception $e) {
				return false;
			}
		}

		/**
		 * Ocurre después de process_payment y lo usamos para mostrar el formulario con los datos que serán
		 * enviados a Place to Pay
		 * @param int $order_id
		 * @return void
		 */
		public function process_redirect($order_id)
		{
                 		// obtiene los últimos pedidos del cliente para revisar si tiene uno pendiente
		$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
		'numberposts' => 2,
		'meta_key' => '_customer_user',
		'meta_value' => get_current_user_id(),
		'post_type' => 'shop_order',
		'post_status' => 'publish',
		'shop_order_status' => 'on-hold'
		) ) );

		if ( $customer_orders ) {
			foreach ($customer_orders as $order_ids) {
				$order = new WC_Order();
				$order->populate($order_ids);
				if ((($order->status == 'pending') || ($order->status == 'on-hold')) && ($order->id != $order_id) ) {
					$authcode = get_post_meta($order->id, '_p2p_authorization', true);
					$message = sprintf( __( 'The order # %s is awaiting confirmation from your bank, Please wait a few minutes to check and see if your payment has been approved. For more information please contact our call center %s or via email %s and ask for the status of the transaction %s.', 'woocommerce-placetopay'),
					(string)$order->id, $this->merchantPhone, $this->merchantEmail, (($authcode == '') ? '': sprintf(__('with Authorization/tracking %s', 'woocommerce-placetopay'), $authcode)));
					echo "<table class='shop_table order_details'>
					<tbody>
					<tr>
					<th scope='row'>{$message}</th>
					</tr>
					</tbody>
					</table>";
					return;
				} else if($order->id != $order_id){
					echo '<p>'.__('Thank you for your order, please click the button below to pay with Place to Pay.', 'woocommerce-placetopay').'</p>';
				}
			}
		}
	
			//echo '<p>'.__('Thank you for your order, please click the button below to pay with Place to Pay.', 'woocommerce-placetopay').'</p>';
			if ($this->debug == 'yes') {
				$this->log->add('PlacetoPay', 'process_redirect - order #' . $order_id);
			}
			echo $this->generate_placetopay_form($order_id);
		}

		/**
		 * Lista de campos adicionales
		 * @param boolean $on_admin
		 * @return array
		 */
		private function extra_fields($on_admin = false)
		{
			return array(
				'_p2p_transaction_date' => __('Transaction date', 'woocommerce-placetopay'),
				'_p2p_status' => __('Status', 'woocommerce-placetopay'),
				'_p2p_reason' => __('Reason', 'woocommerce-placetopay'),
				'_p2p_franchise_name' => __('Franchise', 'woocommerce-placetopay'),
				'_p2p_bank_name' => __('Bank Name', 'woocommerce-placetopay'),
				'_p2p_authorization' => __('Authorization/tracking', 'woocommerce-placetopay'),
				'_p2p_receipt' => __('Receipt', 'woocommerce-placetopay'),
				'_p2p_platform_amount' => __('Platform amount', 'woocommerce-placetopay'),
				'_p2p_platform_factor' => __('Exchange rate', 'woocommerce-placetopay')
			);
		}

		/**
		 * Muestra los detalles de la orden en el panel administrativo
		 * @param WC_Order $order
		 * @return void
		 */
		public function order_details($order)
		{
			$order_id = $order->id;

			// presenta los detalles de la operación en PlacetoPay
			$fields = $this->extra_fields(true);

			// genera el HTML con la tabla de datos adicionales
			$html = '<h4>' . __('Transaction information', 'woocommerce-placetopay') . '</h4>'
				. '<div class="address">';
			foreach ($fields as $field => $label) {
				$value = get_post_meta($order_id, $field, true);
				if ($value !== '') {
					if (($field == '_p2p_platform_amount') || ($field == '_p2p_platform_factor')) {
						$value = wc_price($value) . ' ' . get_post_meta($order_id, '_p2p_platform_currency', true);
					}
					$html .= '<p><strong>' . $label . ':</strong>' . $value . '</p>';
				}
			}
			$html .= '</div>';

			echo $html;
		}

		/**
		 * Procesa la respuesta de Place to Pay
		 * @param int $order_id
		 * @return void
		 */
		public function process_response($order_id)
		{
			global $woocommerce;

			// obtiene la información de la orden
			$order = new WC_Order($order_id);

			// obtiene los datos retornados por Place to Pay
			$customerSiteID = (isset($_REQUEST['CustomerSiteID']) ? $_REQUEST['CustomerSiteID']: false);
			$paymentResponse = (isset($_REQUEST['PaymentResponse']) ? $_REQUEST['PaymentResponse']: false);

			// verifica que la petición sea válida para el sitio
			if (!empty($paymentResponse) && ($customerSiteID == $this->customerSiteID))  {
				// carga la libreria de Place to Pay
				require_once dirname(__FILE__) . '/classes/PlacetoPay.class.php';

				// instancia el objeto de Place to Pay, y procesa el bloque de respuesta
				$p2p = new PlacetoPay();
				$p2p->setGPGProgramPath($this->programPathGnuPG);
				$p2p->setGPGHomeDirectory($this->homeDirectoryGnuPG);
				$rc = $p2p->getPaymentResponse($this->keyID, $this->passPhrase, $paymentResponse);

				// si la respuesta es fallida por PGP, no realice ninguna opción con la transacción
				if (($rc == PlacetoPay::P2P_ERROR) && ($p2p->getErrorCode() == 'GPG')) {
					if ($this->debug == 'yes') {
						$this->log->add('PlacetoPay', __('Failure in the decryption of data.', 'woocommerce-placetopay') . PHP_EOL . $p2p->getErrorMessage());
					}
				} else {
					// asienta la operación
					$this->settlePaymentResponse($order, $rc, $p2p);

					// presenta los detalles de la operación en PlacetoPay
					$fields = $this->extra_fields(false);

					// genera el HTML con la tabla de datos adicionales
					$html = '<table class="shop_table order_details">'
						. '<thead><h2>' . __('Transaction information', 'woocommerce-placetopay') . '</h2></thead>'
						. '<tbody>'
						. '<tr><th scope="row">' . __('Merchant ID', 'woocommerce-placetopay')
							. '</th><td>' . $this->merchantID . '</td></tr>'
						. '<tr><th scope="row">' . __('Merchant Name', 'woocommerce-placetopay')
							. '</th><td>' . $this->merchantName . '</td></tr>'
						. '<tr><th scope="row">' . __('Reference', 'woocommerce-placetopay')
							. '</th><td>' . $order_id . '</td></tr>'
						. '<tr><th scope="row">' . __('Description', 'woocommerce-placetopay')
							. '</th><td>'.sprintf(__($this->paymentDescription, 'woocommerce-placetopay'), (string)$order_id) . '</td></tr>';
					foreach ($fields as $field => $label) {
						$value = get_post_meta($order_id, $field, true);
						if ($value !== '') {
							if (($field == '_p2p_platform_amount') || ($field == '_p2p_platform_factor')) {
								$value = wc_price($value) . ' ' . get_post_meta($order_id, '_p2p_platform_currency', true);
							}
							$html .= '<tr><th scope="row">' . $label . '</th><td class="'.$label.'">' . $value . '</td></tr>';
						}
					}
					$html .= '<tr><th scope="row">' . __('IP Address', 'woocommerce-placetopay')
						. '</th><td>' . $_SERVER['REMOTE_ADDR'] . '</td></tr>';
					$html .= '<tr><th scope="row" colspan="2">' . sprintf(
							__('If you have any questions contact us at phone %s or email us at %s.', 'woocommerce-placetopay'),
							$this->merchantPhone,
							$this->merchantEmail
						) . '</th></tr>';
					$html .= '</tbody></table>';

					echo $html;
				}
			} else
				wp_redirect($order->get_view_order_url());
		}

		/**
		 * Consulta si hay transacciones pendientes y genera un mensaje de advertencia al respecto
		 * @return void
		 */
		public function checkout_message()
		{
			// obtiene el usuario actual
			$user_ID = get_current_user_id();
			if ($user_ID) {

				// obtiene los últimos pedidos del cliente para revisar si tiene uno pendiente
				$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
				  'numberposts' => 5,
				  'meta_key'    => '_customer_user',
				  'meta_value'  => get_current_user_id(),
				  'post_type'   => 'shop_order',
				  'post_status' => 'publish',
				  'shop_order_status' => 'on-hold'
				) ) );

				// si obtuvo datos
				if ( $customer_orders ) {
					foreach ($customer_orders as $order_id) {
						$order = new WC_Order();
						$order->populate($order_id);
						if (($order->status == 'pending') || ($order->status == 'on-hold')) {
							$authcode = get_post_meta($order->id, '_p2p_authorization', true);
							$message = sprintf( __( 'The order # %s is awaiting confirmation from your bank, Please wait a few minutes to check and see if your payment has been approved. For more information please contact our call center %s or via email %s and ask for the status of the transaction %s.', 'woocommerce-placetopay'),
								(string)$order->id, $this->merchantPhone, $this->merchantEmail, (($authcode == '') ? '': sprintf(__('with Authorization/tracking %s', 'woocommerce-placetopay'), $authcode)));
							echo "<table class='shop_table order_details'>
								<tbody>
									<tr>
										<th scope='row'>{$message}</th>
									</tr>
								</tbody>
								</table>";
							return;
						}
					}
				}
			}
		}

		/**
		 * Dada una respuesta de Place to Pay, asienta la transacción
		 * @param WC_Order $order
		 * @param int $rc
		 * @param PlacetoPay $p2p
		 * @return void
		 */
		private function settlePaymentResponse($order, $rc, $p2p)
		{
			global $wpdb;

			// obtiene la orden basada en la referencia de pago
			$orderID = $order->id;

			// valida que la referencia en PlacetoPay concuerde con el ID de la orden
			// sino aborta el asiento
			if ($orderID != (int)$p2p->getReference()) return;

			// verifica que la orden no haya sido completada, para no perder el
			// tiempo haciendo un asiento
			if (in_array($order->status, array('completed', 'completed'))) {
				if ($this->debug == 'yes')
					$this->log->add( 'PlacetoPay', sprintf(
						__('Abort updating the Order # %s, was already completed!', 'woocommerce-placetopay'),
						(string)$orderID));
				return;
			}

			// almacena los datos de la operación
			if ($p2p->getTransactionDate() != '')
				update_post_meta($orderID, '_p2p_transaction_date', $p2p->getTransactionDate());
			update_post_meta($orderID, '_p2p_reason_code', $p2p->getErrorCodeB24());
			update_post_meta($orderID, '_p2p_reason_message', sanitize_text_field($p2p->getErrorMessage()));
			update_post_meta($orderID, '_p2p_reason', sanitize_text_field($p2p->getErrorCodeB24() . ' - ' . $p2p->getErrorMessage()));
			if ($p2p->getInternalReference() != '')
				update_post_meta($orderID, '_p2p_internal_reference', $p2p->getInternalReference());
			if ($p2p->getFranchise() != '')
				update_post_meta($orderID, '_p2p_franchise', $p2p->getFranchise());
			if ($p2p->getFranchiseName() != '')
				update_post_meta($orderID, '_p2p_franchise_name', sanitize_text_field($p2p->getFranchiseName()));
			if ($p2p->getBankName() != '')
				update_post_meta($orderID, '_p2p_bank_name', sanitize_text_field($p2p->getBankName()));
			if ($p2p->getAuthorization() != '')
				update_post_meta($orderID, '_p2p_authorization', $p2p->getAuthorization());
			if ($p2p->getReceipt() != '')
				update_post_meta($orderID, '_p2p_receipt', $p2p->getReceipt());
			if (($p2p->getPlatformCurrency() != '') && ($p2p->getCurrency() != $p2p->getPlatformCurrency())) {
				update_post_meta($orderID, '_p2p_platform_currency', $p2p->getPlatformCurrency());
				update_post_meta($orderID, '_p2p_platform_factor', $p2p->getPlatformConversionFactor());
				update_post_meta($orderID, '_p2p_platform_amount', $p2p->getPlatformTotalAmount());
			}

			// procede con el asiento dependiendo de la respuesta
			switch ($rc) {
				case PlacetoPay::P2P_ERROR:
				case PlacetoPay::P2P_DECLINED:
					update_post_meta($orderID, '_p2p_status', __((($rc == PlacetoPay::P2P_ERROR) ? 'Fallida': 'Rechazada'), 'woocommerce-placetopay'));
					$order->update_status('failed', $p2p->getErrorMessage());

					// elimina el registro de la lista de transacciones pendientes
					$wpdb->delete($wpdb->prefix . 'woocommerce_placetopay_pending', array('order_id' => $orderID));
					break;
				case PlacetoPay::P2P_APPROVED:
				case PlacetoPay::P2P_DUPLICATE:
					update_post_meta($orderID, '_p2p_status', __('Aprobada', 'woocommerce-placetopay'));
					$order->add_order_note($p2p->getErrorMessage());
					$order->payment_complete();

					// elimina el registro de la lista de transacciones pendientes
					$wpdb->delete($wpdb->prefix . 'woocommerce_placetopay_pending', array('order_id' => $orderID));
					break;
				case PlacetoPay::P2P_PENDING:
					if ($order->status !== 'pending') {
						update_post_meta($orderID, '_p2p_status', __('Pending', 'woocommerce-placetopay'));
						$order->update_status('pending', $p2p->getErrorMessage());

						// actualiza la fecha y hora de la operación pendiente
						$wpdb->update($wpdb->prefix . 'woocommerce_placetopay_pending', array('timestamp' => time()), array('order_id' => $orderID));
					}
					break;
			}
		}

		/**
		 * Realiza las configuraciones pertinentes a la instalación
		 * @return void
		 */
		private function install()
		{
			global $wpdb;

			// determina el conjunto de caracteres para la nueva tabla
			$charset_collate = '';
			if ( ! empty( $wpdb->charset ) )
				$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
			if ( ! empty( $wpdb->collate ) )
				$charset_collate .= " COLLATE {$wpdb->collate}";

			// instala la tabla de transacciones de PLACETOPAY
			$wpdb->query("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}woocommerce_placetopay_pending` (
                `order_id` int NOT NULL,
                `customer_id` int NULL,
                `timestamp` int NOT NULL,
                `currency` char(5) NOT NULL,
                `amount` decimal(10,2) NOT NULL,
                PRIMARY KEY (`order_id`),
                INDEX `customer_idIX` (`customer_id`),
                INDEX `timestampIX` (`timestamp`)
            ) {$charset_collate}");
		}

		/**
		 * Genera las modificaciones debido a la actualización del componente
		 * @return void
		 */
		private function upgrade($version)
		{
			global $wpdb;

			switch ($version) {
				case '1.2.1':
					// lee la tabla de transacciones que existia, para pasar los datos a los meta
					// de la orden
					$oldTransactions = $wpdb->get_results(
						'SELECT id_post AS order_id, id_user, date, currency, amount, status,'
							. ' reason, reason_description, franchise, franchise_name, bank,'
							. ' authcode, receipt'
						. " FROM `{$wpdb->prefix}woocommerce_payment_placetopay`"
					);
					if ($oldTransactions) {
						// el nombre de la nueva tabla donde ingresar los datos
						$newTable = $wpdb->prefix . 'woocommerce_placetopay_pending';

						foreach ($oldTransactions as $tran) {
							// agrega los metadatos a la orden
							update_post_meta($tran->order_id, '_p2p_transaction_date', $tran->date);
							update_post_meta($tran->order_id, '_p2p_reason_code', $tran->reason);
							update_post_meta($tran->order_id, '_p2p_reason_message', sanitize_text_field($tran->reason_description));
							update_post_meta($tran->order_id, '_p2p_reason', sanitize_text_field($tran->reason . ' - ' . $tran->reason_description));
							if (!empty($tran->franchise))
								update_post_meta($tran->order_id, '_p2p_franchise', $tran->franchise);
							if (!empty($tran->franchise_name))
								update_post_meta($tran->order_id, '_p2p_franchise_name', $tran->franchise_name);
							if (!empty($tran->bank))
								update_post_meta($tran->order_id, '_p2p_bank_name', $tran->bank);
							if (!empty($tran->authcode))
								update_post_meta($tran->order_id, '_p2p_authorization', $tran->authcode);
							if (!empty($tran->receipt))
								update_post_meta($tran->order_id, '_p2p_receipt', $tran->receipt);

							// si está pendiente lo agrega a la nueva tabla
							if ($tran->status == 3)
								$wpdb->insert($newTable, array(
										'order_id' => $tran->order_id,
										'customer_id' => $tran->id_user,
										'timestamp' => strtotime($tran->date),
										'currency' => $tran->currency,
										'amount' => $tran->amount
									));
						}
					}

					// elimina la tabla anterior
					$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}woocommerce_payment_placetopay");
					break;
			}
		}

		/**
		 * Consulta con Place to Pay las operaciones pendientes que tienen más del tiempo especificado
		 * @param int $minutes
		 * @return void
		 */
		public function resolvePending($minutes = 7)
		{
			global $wpdb;

			// por defecto son 7 minutos
			if (empty($minutes) || ($minutes < 0))
				$minutes = 7;

			// consulta por las pendientes que tienen mínumo los n minutos
			$pending = $wpdb->get_results(
				"SELECT order_id, currency, amount FROM {$wpdb->prefix}woocommerce_placetopay_pending"
				. ' WHERE timestamp < ' . (time() - $minutes * 60)
				);
			if ($pending) {
				// carga la libreria de Place to Pay
				require_once dirname(__FILE__) . '/classes/PlacetoPay.class.php';

				$p2p = new PlacetoPay();
				foreach ($pending as $tran) {
					// genera un log del consumo del servicio
					if ($this->debug == 'yes')
						$this->log->add('PlacetoPay', sprintf(
							__('Querying the webservice for the Order # %s', 'woocommerce-placetopay'), (string)$tran->order_id));

					// consulta la operación en Place to Pay
					$rc = $p2p->queryPayment($this->customerSiteID, $tran->order_id, $tran->currency, $tran->amount);
					if (($rc == PlacetoPay::P2P_ERROR) && ($p2p->getErrorCode() == 'HTTP')) {
						// sin cambios, falló el consumo del servicio
						if ($this->debug == 'yes')
							$this->log->add('PlacetoPay', sprintf(
								__('Fail querying the Order # %s with Place to Pay - %s', 'woocommerce-placetopay'),
								(string)$orderID, $p2p->getErrorMessage()));
					} else {
						// obtiene la información de la orden
						$order = new WC_Order($tran->order_id);
						$this->settlePaymentResponse($order, $rc, $p2p);
					}
				}
			}
		}

		/**
		 * Obtiene la descripción del medio de pago, incluyendo los logos
		 * @return string
		 */
		private function get_full_payment_description()
		{
			// obtiene la descripción base
			$description = $this->get_option('description');

			// determina los logos de los medios de pago
			$logos = $this->get_option('paymentLogos');
			$logosInfo = $this->get_franchises();
			if (!empty($logos)) {
				$description .= '<br/><p>';
				foreach($logos as $logo)
					$description .= '<img src="' . $this->urlPlacetoPayImages . $logosInfo[$logo]['image'] . '" alt="' . $logosInfo[$logo]['label'] . '" />';
				$description .= '</p>';
			}
			return $description;
		}

		/**
		 * Reduce la lista de llaves retornada por la clase de GnuPG
		 * @param array $keyList
		 * @param string $errorMessage
		 * @return array
		 */
		private function get_gnupg_keys($keyList, $errorMessage)
		{
			$options = array();
			if (is_array($keyList)) {
				$options[''] = __( '-- Please Select --', 'woocommerce-placetopay' );
				foreach($keyList as $v)
					$options[$v['KeyID']] = $v['UserID'];
			} else {
				$options[''] = $errorMessage;
			}

			return $options;
		}

		/**
		 * Verifica que el GUID tenga el formato esperado
		 * @param  string $guid
		 * @return bool
		 */
		private function is_guid($guid) {
			if (preg_match('/\{[0-9a-z]{8}(\-[0-9a-z]{4}){3}\-[0-9a-z]{12}\}/i', $guid))
				return true;
			else
				return false;
		}

		/**
		 * Obtiene la lista de franquicias
		 * @return array
		 */
		private function get_franchises()
		{
			return array(
				'CR_VS' => array('label' => 'Visa', 'image' => 'CR_VS.gif'),
				'RM_MC' => array('label' => 'MasterCard', 'image' => 'RM_MC.gif'),
				'CR_AM' => array('label' => 'American Express', 'image' => 'CR_AM.gif'),
				'CR_DN' => array('label' => 'Diners', 'image' => 'CR_DN.gif'),
				'_PSE_' => array('label' => 'PSE', 'image' => '_PSE_.png'),
				'V_VBV' => array('label' => 'Verified by VISA', 'image' => 'V_VBV.gif'),
				'TY_EX' => array('label' => 'Tarjeta Éxito', 'image' => 'tuya/TY_EX.png'),
				'TY_AK' => array('label' => 'Tarjeta Alkosto', 'image' => 'tuya/TY_AK.png'),
				'PINVL' => array('label' => 'Pinválida', 'image' => 'validda/PINVL.png'),
				'SFPAY' => array('label' => 'SafetyPay', 'image' => 'SFPAY.png'),
				'ACIWU' => array('label' => 'Western Union', 'image' => 'ACIWU.png'),
			);
		}

		/**
		 * Presenta un mensaje genérico de error
		 * @param string $message
		 * @return void
		 */
		private function admin_error_message($message)
		{
			echo '<div class="error"><p><strong>'
				. __( 'Place to Pay Disabled', 'woocommerce-placetopay' ) . '</strong>: '
				. sprintf( __( $message, 'woocommerce-placetopay' ),
					'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_gateway_placetopay') . '">'
					. __( 'Click here to configure!', 'woocommerce-placetopay' ) . '</a>' )
				. '</p></div>';
		}

		/**
		 * Obtiene el nombre de la tabla con el prefijo
		 * @param string $tableName
		 * @return string
		 */
		private function get_full_table_name($tableName) {
            global $wpdb;

            return $wpdb->prefix . $tableName;
        }

		/**
		 * Genere un mensaje de error cuando la moneda no es soportada.
		 * @return string Error Mensage.
		 */
		public function currency_not_supported_message() {
			$this->admin_error_message( 'The currency of your store is not supported by Place to Pay, please call Place to Pay support service. %s' );
		}

		/**
		 * Agrega un mensaje de error cuando el customerSiteID no está definido o no es un GUID.
		 * @return string Error Mensage.
		 */
		public function customersiteid_invalid_message() {
			$this->admin_error_message( 'The Customer Site ID is invalid, please check that is defined and has a GUID format. %s' );
		}

		/**
		 * Agrega un mensaje de error cuando el programPathGnuPG no está definido o es inváludo.
		 * @return string Error Mensage.
		 */
		public function programpathgnupg_invalid_message() {
			$this->admin_error_message( 'The GPG Executable Path is not defined, the path is invalid or doesn\'t exists. %s' );
		}

		/**
		 * Genera un mensaje cuando el homeDirectoryGnuPG no está definido o es inválido.
		 * @return string Error Mensage.
		 */
		public function homedirectorygnupg_invalid_message() {
			$this->admin_error_message( 'The Keyring Home Directory is not defined, the path is invalid or doesn\'t exists. %s' );
		}

		/**
		 * Mensaje de error cuando el keyID no está definido.
		 * @return string Error Mensage.
		 */
		public function keyid_invalid_message() {
			$this->admin_error_message( 'The Merchant Key is not defined. %s' );
		}

		/**
		 * Mensjae de error cuando el passPhrase no está definido.
		 * @return string Error Mensage.
		 */
		public function passphrase_invalid_message() {
			$this->admin_error_message( 'The Merchant Passphrase is not defined. %s' );
		}

		/**
		 * Agrega mensaje de error cuando el recipientKeyID no está definido.
		 * @return string Error Mensage.
		 */
		public function recipientkeyid_invalid_message() {
			$this->admin_error_message( 'The Place to Pay Key ID is not defined. %s' );
		}
	}
}