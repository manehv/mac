<?php
/**
 * Iniciador del programa para la administración de llaves de GnuPG
 *
 * @author    Enrique Garcia Molina <egarcia@egm.co>
 * @copyright Copyright (c) 2014, EGM Ingenieria sin fronteras S.A.S.
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since     Viernes, Septiembre 12, 2014
 * @version   $Id: index.php,v 1.0.17 2014-11-17 11:35:00-05 egarcia Exp $
 */

define('APP_PATH', realpath(dirname(__FILE__) . '/../'));

include_once APP_PATH . '/lib/egmGnuPGAdmin.class.php';

$gpg = new egmGnuPGAdmin();
$gpg->run(APP_PATH . '/config/config.ini');
