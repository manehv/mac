<?php
/**
 * WooCommerce - Place to Pay
 * Archivo que resuelve las transacciones pendientes.
 *
 * @author Soporte <soporte@placetopay.com>
 * @copyright (c) 2013-2014 EGM Ingenieria sin fronteras S.A.S.
 * @version $Id: cron.php,v 1.0.2 2014/09/12 14:52:00 ingenieria Exp $
 */

// incluye la configuración de Wordpress, WooCommerce y el plugin de Place to Pay
include_once(dirname(__FILE__) . '/../../../wp-config.php');
include_once(dirname(__FILE__) . '/../woocommerce/woocommerce.php');
include_once(dirname(__FILE__) . '/woocommerce-placetopay.php');

// instancia el medio de pago
$placetopay = new WC_Gateway_PlacetoPay();

// ejecuta la sonda
$placetopay->resolvePending(7);
