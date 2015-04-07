<?php
/**
 * Clase que encapsula toda la funcionalidad de la aplicación para la administración de llaves
 *
 * @author    Enrique Garcia Molina <egarcia@egm.co>
 * @copyright Copyright (c) 2014, EGM Ingenieria sin fronteras S.A.S.
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since     Viernes, Septiembre 12, 2014
 * @version   $Id: egmGnuPGAdmin.class.php,v 1.0.24 2014-09-15 03:06:00-05 egarcia Exp $
 */

require_once dirname(__FILE__) . '/egmGnuPGAdmin.class.php';

class egmGnuPGAdmin
{
	private $programPath;
	private $homeDirectory;
	private $jsInline;
	private $jsFiles = array(
		'//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js',
		'//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/js/bootstrap.min.js',
		'//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.1/js/bootstrapValidator.min.js',
		'//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.1/js/language/es_ES.js',
		'//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.2.0/bootbox.min.js'
	);
	private $translations = array();

	/**
	 * Ejecuta la mini aplicación
	 * @param string $configFile
	 */
	public function run($configFile)
	{
		error_reporting(E_ERROR | E_PARSE | E_NOTICE);

		// obtiene la opción a ejecutar
		$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
		if ($action == 'delete') {
			// procesa el archivo de configuración
			$this->readConfig($configFile);

			// elimina la llave
			$result = $this->deleteKey();
			header('Content-Type: application/json');
			print json_encode($result);
			exit();
		}

		$this->header();
		try {
			// procesa el archivo de configuración
			$this->readConfig($configFile);

			switch ($action) {
				case 'genkey':
					$this->genKey();
					break;
				case 'import':
					$this->importKey();
					break;
				case 'list':
					$this->listKeys();
					break;
				case 'sign':
					$this->signKey();
					break;
				case 'view':
					$keyID = filter_input(INPUT_GET, 'keyID', FILTER_SANITIZE_STRING);
					if (!$keyID)
						throw new Exception("No se proporcionó un identificador de llave.", 1);
					$this->viewKey($keyID);
					break;
				default:
					$this->home();
					break;
			}
		} catch (Exception $e) {
			print '<h2>Atención</h2>'
				. '<p class="bg-warning">' . $e->getMessage() . '</p>';
		}
		$this->footer();
	}

	/**
	 * Genera el encabezado del layout
	 */
	private function header()
	{
		print '<!DOCTYPE html>'
			. '<html lang="es">'
			. '<header>'
				. '<meta charset="utf-8">'
				. '<meta http-equiv="X-UA-Compatible" content="IE=edge">'
				. '<meta name="viewport" content="width=device-width, initial-scale=1">'
				. '<title>Place to Pay - Administrador de llaves GnuPG</title>'
				. '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/css/bootstrap.min.css">'
				. '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/css/bootstrap-theme.min.css">'
				. '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.1/css/bootstrapValidator.min.css"/>'
			. '</header>'
			. '<body role="document">'
			. '<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">'
				. '<div class="container">'
					. '<div class="navbar-header">'
						. '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">'
							. '<span class="icon-bar"></span>'
							. '<span class="icon-bar"></span>'
							. '<span class="icon-bar"></span>'
						. '</button>'
						. '<a class="navbar-brand" href="#">Place to Pay - GnuPG</a>'
					. '</div>'
					. '<div class="navbar-collapse collapse">'
						. '<ul class="nav navbar-nav">'
							. '<li class="active"><a href="' . $this->getUrl() . '">Inicio</a></li>'
							. '<li class="dropdown">'
								. '<a href="#" class="dropdown-toggle" data-toggle="dropdown">Acciones <span class="caret"></span></a>'
								. '<ul class="dropdown-menu" role="menu">'
									. '<li><a href="' . $this->getUrl('list') . '">Listar llaves</a></li>'
									. '<li><a href="' . $this->getUrl('genkey') . '">Crear llave</a></li>'
									. '<li><a href="' . $this->getUrl('import') . '">Importar llave</a></li>'
									. '<li><a href="' . $this->getUrl('sign') . '">Firmar llave</a></li>'
								. '</ul>'
							. '</li>'
						. '</ul>'
					. '</div><!--/.nav-collapse -->'
				. '</div>'
			. '</nav>'
			. '<div class="container" role="main">';
	}

	/**
	 * Inicio de la aplicación
	 * @return void
	 */
	private function home()
	{
		print '<ol class="breadcrumb" class="active"><li><a href="' . $this->getUrl() . '">Inicio</a></li></ol>'
			. '<div class="jumbotron">'
			. '<h1>Bienvenido al administrador de llaves de <span class="text-success">Place to Pay</span></h1>'
			. '<p>Mediante esta interfaz usted podrá administrar las llaves que contiene en su anillo.  Esta aplicación debe ser eliminada cuando no sea requerida'
			. ' debido a las acciones que permite.</p>'
			. '<p>Para un correcto funcionamiento se debe asegurar que tenga permisos completos sobre la carpeta del anillo de llaves.</p>'
			. '<p><a class="btn btn-success btn-lg" href="' . $this->getUrl('list') . '">Listar llaves &raquo;</a></p>'
			. '</div>';
	}

	/**
	 * Cierra la generación del layout
	 */
	private function footer()
	{
		print '<hr>'
			. '<footer><div class="pull-left">Copyright &copy; 2014 EGM Ingeniería sin fronteras S.A.S.</div>'
				. '<div class="pull-right"><a href="//www.placetopay.com/" target="_blank" title="Place to Pay"><img src="//www.placetopay.com/images/PLACETOPAY.png" alt="Place to Pay"></a></div>'
			. '</footer>'
			. '</div>';
		foreach ($this->jsFiles as $js) {
			print '<script src="' . $js . '"></script>';
		}
		print '<script type="text/javascript">' . $this->jsInline . '</script>';
		print '</body></html>';
	}

	/**
	 * Retorna la Url para la acción solicitada
	 * @param string $action
	 */
	private function getUrl($action = null, $args = null)
	{
		if (empty($args)) $args = [];
		if (!empty($action)) $args['action'] = $action;

		$params = '';
		foreach ($args as $key => $value) {
			$params .= '&' . $key . '=' . urlencode($value);
		}

		return $_SERVER['PHP_SELF'] . ($params ? '?' . substr($params, 1): '');
	}

	/**
	 * Traduce el mensaje
	 * @param string $text
	 * @return string
	 */
	private function __($text)
	{
		// si llega en blanco nada que hacer
		if(empty($text) || !function_exists('gettext')) return $text;

		// retorna el texto traducido
		return gettext($text);
	}

	/**
	 * Redirige el flujo a la URL especificada
	 * @param string $url
	 */
	private function forward($url)
	{
		header('Location: ' . $url);
		exit();
	}

	/**
	 * Lee el archivo de configuración
	 * @param $configFile
	 * @return void
	 */
	private function readConfig($configFile)
	{
		if (!file_exists($configFile) || !is_readable($configFile))
			throw new Exception('El archivo de configuración no existe o no puede ser leído.', 1);

		// lee el archivo de configuración y lo parsea por secciones
		$config = parse_ini_file($configFile, false);

		// verifica la definición de las etiquetas requeridas
		if (empty($config['programPath']))
			throw new Exception('La ruta del archivo ejecutable gpg no fue definida [programPath].', 1);
		if (empty($config['homeDirectory']))
			throw new Exception('La ruta del anillo de llaves no fue definida [homeDirectory].', 1);

		// valida que exista y sea ejecutable
		if (!file_exists($config['programPath']) || !is_executable($config['programPath']))
			throw new Exception(sprintf('El archivo ejecutable del gpg [%s] no existe no es un archivo ejecutable.', $config['programPath']), 1);

		// valida que exista, sea un directorio y sea escribible
		if (!file_exists($config['homeDirectory'])) {
			if (!mkdir($config['homeDirectory'], 0700))
				throw new Exception(sprintf('La ruta del anillo de llaves [%s] no existe y no pudo ser creada.', $config['homeDirectory']), 1);
		}

		// valida que el directorio exista y sea escribible
		if (!is_dir($config['homeDirectory']) || !is_writable($config['homeDirectory']))
			throw new Exception(sprintf('La ruta del anillo de llaves [%s] no es un directorio o no se puede escribir en ella.', $config['homeDirectory']), 1);

		// asigna la configuración
		$this->programPath = $config['programPath'];
		$this->homeDirectory = $config['homeDirectory'];
	}

	/**
	 * Lista las llaves disponibles en el anillo
	 * @return void
	 */
	private function listKeys()
	{
		$gpg = new egmGnuPG($this->programPath, $this->homeDirectory);
		$keys = $gpg->ListKeys();

		print '<ol class="breadcrumb"><li><a href="' . $this->getUrl() . '">Inicio</a></li><li class="active">Listado de llaves</li></ol>'
			. '<div class="page-header"><h1>Listado de llaves</h1></div>';
		if ($keys === false)
			print '<div class="alert alert-danger" role="alert">' . nl2br($gpg->error) . '</div>';
		else {
			print '<div id="delete-alert" class="alert alert-warning fade in" role="alert" style="display:none;">'
					. '<button type="button" class="close">&times;</button>'
					. '<span>Aquí se muestran los mensajes</span>'
				. '</div>'
				. '<table class="table table-striped table-hover">'
				. '<thead><tr><th width="10%">ID</th><th width="60%">Nombre</th><th width="10%">Creación</th><th width="10%">Vencimiento</th><th>Acciones</th></tr></thead>'
				. '<tbody>';
			if (empty($keys))
				print '<tr><td colspan="4">No hay llaves en el anillo.</td></tr>';
			else
			foreach ($keys as $key) {
				print '<tr><td><a href="' . $this->getUrl('view', array('keyID' => $key['KeyID'])) . '">' . $key['KeyID'] . '</a>'
					. '</td><td>' . $key['UserID']
					. '</td><td>' . $key['CreationDate']
					. '</td><td>' . $key['ExpirationDate']
					. '</td><td class="text-center"><a href="javascript:void(deleteKey(\'' . $key['KeyID'] . '\'))"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';
			}
			print '<tbody></table>';

			// agrega los scripts de eliminación
			$this->jsInline = '$(".alert .close").on("click", function(e) {
					$(this).parent().hide();
				});
				function deleteKey(keyid) {
					bootbox.dialog({
						title: "Atención!",
						message: "<p>Está seguro de eliminar la llave <b>" + keyid + "</b>?</p><p>Tenga en cuenta que esta operación es irreversible.</p>",
						buttons: {
							cancel: {
								label: "Cancelar",
							},
							delete: {
								label: "Eliminar",
								className: "btn-danger",
								callback: function() {
									$.ajax({
										type: "POST",
										url: "' . $this->getUrl('delete') . '",
										data: { keyID: keyid }
									}).done(function(result) {
										if (result.status == "ok") {
											window.location.href = "' . $this->getUrl('list') . '";
										} else {
											$("#delete-alert").find("span").html(result.message);
											$("#delete-alert").show();
										}
									})
								}
							}
						}
					});
				}' . PHP_EOL;
		}
	}

	private function viewKey($keyID)
	{
		// consulta toda la información de la llave generada
		$gpg = new egmGnuPG($this->programPath, $this->homeDirectory);
		$key = $gpg->ListKeys('public', substr($keyID, -16));
		$key = reset($key);
		$block = $gpg->Export($keyID);

		print '<ol class="breadcrumb"><li><a href="' . $this->getUrl() . '">Inicio</a></li><li class="active">Información de llave</li></ol>'
			. '<div class="page-header"><h1>Información de la llave</h1></div>'
			. '<table class="table">'
			. '<tr><td>ID</td><td>' .  $key['KeyID'] . '</td></tr>'
			. '<tr><td>Nombre</td><td>' .  $key['UserID'] . '</td></tr>'
			. '<tr><td>Creación</td><td>' .  $key['CreationDate'] . '</td></tr>'
			. '<tr><td>Vencimiento</td><td>' .  $key['ExpirationDate'] . '</td></tr>'
			. '<tr><td>Longitud</td><td>' .  $key['KeyLength'] . '</td></tr>'
			. '<tr><td>Bloque de llave</td><td><pre>' .  $block . '</pre></td></tr>'
			. '</table>';
	}

	/**
	 * Genera una nuevo par de llaves
	 * @return void
	 */
	private function genKey()
	{
		$error_message = '';

		// si fue posteada la información para generar la llave
		if (!empty($_POST['info'])) {
			$name = empty($_POST['info']['name']) ? '': $_POST['info']['name'];
			$email = empty($_POST['info']['email']) ? '': $_POST['info']['email'];
			$comment = empty($_POST['info']['comment']) ? '': $_POST['info']['comment'];
			$password = empty($_POST['info']['password']) ? '': $_POST['info']['password'];
			$password_confirm = empty($_POST['info']['password_confirm']) ? '': $_POST['info']['password_confirm'];

			// verifica que la clave y la confirmación correspondan
			if (!filter_var($name, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE))
				$error_message .= 'Debe indicar la Razón social.' . PHP_EOL;
			if (!filter_var($email, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE))
				$error_message .= 'Debe indicar la Razón social.' . PHP_EOL;
			if (!filter_var($comment, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE))
				$error_message .= 'Debe indicar el nombre del sitio o tienda.' . PHP_EOL;
			if ($password != $password_confirm)
				$error_message .= 'La clave y la confirmación deben coincidir.' . PHP_EOL;

			// si no hay errores entonces procede a generar la llave
			if (empty($error_message)) {
				$gpg = new egmGnuPG($this->programPath, $this->homeDirectory);
				$key = $gpg->GenKey(utf8_decode($name), utf8_decode($comment), utf8_decode($email), $password, 0, 'DSA', 2048, 'ELG-E', 2048);
				if ($key) {
					// redirige a la llave
					$this->forward($this->getUrl('view', array('keyID' => substr($key, -16))));

					return;
				} else
					$error_message = nl2br($gpg->error);
			}
		} else {
			$name = '';
			$email = '';
			$comment = '';
			$password = '';
			$password_confirm = '';
		}

		// genera el formulario
		print '<ol class="breadcrumb"><li><a href="' . $this->getUrl() . '">Inicio</a></li><li class="active">Creación de llave</li></ol>'
			. '<div class="page-header"><h1>Creación de nueva llave</h1></div>'
			. ($error_message ? '<div class="alert alert-warning" role="alert">' . $error_message . '</div>': '')
			. '<form class="form-horizontal" role="form" id="form-genkey" action="' . $this->getUrl('genkey') . '" method="post">'
				. '<div class="form-group">'
					. '<label for="info_name" class="col-sm-2 control-label">Razón social</label>'
					. '<div class="col-sm-10">'
						. '<input type="text" class="form-control" id="info_name" name="info[name]" placeholder="Nombre de la compañía" value="' . htmlspecialchars($name) . '" required data-bv-notempty="true" data-bv-stringlength="true" data-bv-stringlength-min="4" data-bv-stringlength-max="128">'
					. '</div>'
				. '</div>'
				. '<div class="form-group">'
					. '<label for="info_email" class="col-sm-2 control-label">Email</label>'
					. '<div class="col-sm-10">'
						. '<input type="email" class="form-control" id="info_email" name="info[email]" placeholder="Correo electrónico del responsable técnico" value="' . htmlspecialchars($email) . '" required data-bv-notempty="true" data-bv-stringlength="true" data-bv-stringlength-min="4" data-bv-stringlength-max="64">'
					. '</div>'
				. '</div>'
				. '<div class="form-group">'
					. '<label for="info_comment" class="col-sm-2 control-label">Sitio</label>'
					. '<div class="col-sm-10">'
						. '<input type="text" class="form-control" id="info_comment" name="info[comment]" placeholder="Nombre del sitio o tienda" value="' . htmlspecialchars($comment) . '" required data-bv-notempty="true" data-bv-stringlength="true" data-bv-stringlength-min="4" data-bv-stringlength-max="128">'
					. '</div>'
				. '</div>'
				. '<div class="form-group">'
					. '<label for="info_password" class="col-sm-2 control-label">Contraseña</label>'
					. '<div class="col-sm-4">'
						. '<input type="password" class="form-control" id="info_password" name="info[password]" placeholder="Clave de la llave" value="' . htmlspecialchars($password) . '" required data-bv-notempty="true" data-bv-stringlength="true" data-bv-stringlength-min="8" data-bv-identical="true" data-bv-identical-field="info[password_confirm]">'
					. '</div>'
				. '</div>'
				. '<div class="form-group">'
					. '<label for="info_password_confirm" class="col-sm-2 control-label">Confirmación contraseña</label>'
					. '<div class="col-sm-4">'
						. '<input type="password" class="form-control" id="info_password_confirm" name="info[password_confirm]" max placeholder="Confirmación de la clave" value="' . htmlspecialchars($password_confirm) . '" required required data-bv-notempty="true" data-bv-stringlength="true" data-bv-stringlength-min="8" data-bv-identical="true" data-bv-identical-field="info[password]">'
					. '</div>'
				. '</div>'
				. '<div class="form-group">'
					. '<div class="col-sm-offset-2 col-sm-10">'
						. '<button type="submit" id="btnSubmit" class="btn btn-primary">Generar llave</button>'
					. '</div>'
				. '</div>'
			. '</form>';

		// agrega los scripts de validación
		$this->jsInline = '$(document).ready(function() {
				$("#form-genkey").bootstrapValidator({
					onSuccess: function(e) {
						$("#btnSubmit").text("Generando la llave...");
						$("input[type=submit]").attr("disabled","disabled");
					}
				});
			});';
	}

	/**
	 * Importa una llave al anillo
	 * @return void
	 */
	private function importKey()
	{
		$error_message = '';
		$block = '';

		// si fue posteada la información para importar la llave
		if (!empty($_POST['info']['block'])) {
			$block = $_POST['info']['block'];

			$gpg = new egmGnuPG($this->programPath, $this->homeDirectory);
			$importedKeys = $gpg->Import($block);
			if ($importedKeys) {
				// Muestra la lista de llaves importadas
				print '<ol class="breadcrumb"><li><a href="' . $this->getUrl() . '">Inicio</a></li><li class="active">Importación de llaves</li></ol>'
					. '<div class="page-header"><h1>Llaves importadas</h1></div>'
					. '<table class="table table-striped table-hover">'
					. '<thead><tr><th width="10%">ID</th><th width="90%">Nombre</th></tr></thead>'
					. '<tbody>';
				foreach ($importedKeys as $key) {
					print '<tr><td><a href="' . $this->getUrl('view', array('keyID' => $key['KeyID'])) . '">' . $key['KeyID'] . '</a>'
						. '</td><td>' . $key['UserID'] . '</td></tr>';
				}
				print '<tbody></table>';

				return;
			} else
				$error_message = nl2br($gpg->error);
		}

		// genera el formulario
		print '<ol class="breadcrumb"><li><a href="' . $this->getUrl() . '">Inicio</a></li><li class="active">Importación de llaves</li></ol>'
			. '<div class="page-header"><h1>Importación de llaves</h1></div>'
			. ($error_message ? '<div class="alert alert-warning" role="alert">' . $error_message . '</div>': '')
			. '<form class="form-horizontal" role="form" id="form-import" action="' . $this->getUrl('import') . '" method="post">'
				. '<div class="form-group">'
					. '<label for="info_name" class="col-sm-2 control-label">Bloque de llave</label>'
					. '<div class="col-sm-10">'
						. '<textarea class="form-control" id="info_block" name="info[block]" placeholder="Copie aquí el bloque completo de la llave" rows="10" required data-bv-notempty="true">' . htmlspecialchars($block) . '</textarea>'
					. '</div>'
				. '</div>'
				. '<div class="form-group">'
					. '<div class="col-sm-offset-2 col-sm-10">'
						. '<button type="submit" id="btnSubmit" class="btn btn-primary">Importar llave</button>'
					. '</div>'
				. '</div>'
			. '</form>';

		// agrega los scripts de validación
		$this->jsInline = '$(document).ready(function() {
				$("#form-import").bootstrapValidator({
					onSuccess: function(e) {
						$("#btnSubmit").text("Importando la llave...");
						$("input[type=submit]").attr("disabled","disabled");
					}
				});
			});';
	}

	/**
	 * Genera las opciones para un select a partir de una lista de llaves de GnuPG
	 * @param array
	 * @return string
	 */
	private function gnuPGHtmlOptions($keyList, $selected)
	{
		$htmlOptions = '';
		foreach ($keyList as $key) {
			$htmlOptions .= '<option value="' . $key['KeyID'] . '"' . ($key['KeyID'] == $selected ? ' selected="selected"': '') . '>' . htmlspecialchars($key['UserID']) . '</option>';
		}
		return $htmlOptions;
	}

	/**
	 * Firma una llave pública con una privada
	 * @return void
	 */
	private function signKey()
	{
		$error_message = '';

		// si fue posteada la información para generar la llave
		if (!empty($_POST['info'])) {
			$key = empty($_POST['info']['key']) ? '': $_POST['info']['key'];
			$recipient = empty($_POST['info']['recipient']) ? '': $_POST['info']['recipient'];
			$password = empty($_POST['info']['password']) ? '': $_POST['info']['password'];
			$trustlevel = empty($_POST['info']['trustlevel']) ? '0': $_POST['info']['trustlevel'];

			// verifica que la clave y la confirmación correspondan
			if (!filter_var($key, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE))
				$error_message .= 'Debe proporcionar la llave con la cual desea firmar.' . PHP_EOL;
			if (empty($password))
				$error_message .= 'Se requiere una contraseña de la llave firmante.' . PHP_EOL;
			if (!filter_var($recipient, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE))
				$error_message .= 'Debe seleccionar la llave que desea firmar.' . PHP_EOL;
			if ($key == $recipient)
				$error_message .= 'Las llaves no pueden ser las mismas.' . PHP_EOL;
			if (!preg_match('/^[1-3]$/', $trustlevel))
				$error_message .= 'El nivel de confianza debe ser entre supuesto y completo.' . PHP_EOL;

			if (empty($error_message)) {
				$gpg = new egmGnuPG($this->programPath, $this->homeDirectory);
				if ($gpg->SignKey(utf8_decode($key), utf8_decode($password), utf8_decode($recipient), (int)$trustlevel)) {
					print '<ol class="breadcrumb"><li><a href="' . $this->getUrl() . '">Inicio</a></li><li class="active">Firmado de llaves</li></ol>'
						. '<div class="page-header"><h1>Firmado de llaves</h1></div>'
						. '<div class="alert alert-info" role="alert">' . nl2br($gpg->error) . '</div>';
					return;
				} else
					$error_message = nl2br($gpg->error);
			}
		} else {
			$key = '';
			$recipient = '';
			$password = '';
			$trustlevel = '0';
		}

		// obtiene las llaves para el firmado
		$gpg = new egmGnuPG($this->programPath, $this->homeDirectory);
		$secretKeys = $gpg->ListKeys('secret');
		$publicKeys = $gpg->ListKeys('public');

		// genera el formulario
		print '<ol class="breadcrumb"><li><a href="' . $this->getUrl() . '">Inicio</a></li><li class="active">Firmado de llaves</li></ol>'
			. '<div class="page-header"><h1>Firmado de llaves</h1></div>'
			. ($error_message ? '<div class="alert alert-warning" role="alert">' . $error_message . '</div>': '')
			. '<form class="form-horizontal" role="form" id="form-import" action="' . $this->getUrl('sign') . '" method="post">'
				. '<div class="form-group">'
					. '<label for="info_key" class="col-sm-2 control-label">Llave firmante</label>'
					. '<div class="col-sm-10">'
						. '<select class="form-control" id="info_key" name="info[key]" required data-bv-notempty="true" data-bv-different="true" data-bv-different-field="info[recipient]">'
						. '<option></option>'
						. $this->gnuPGHtmlOptions($secretKeys, $key)
						. '</select>'
					. '</div>'
				. '</div>'
				. '<div class="form-group">'
					. '<label for="info_password" class="col-sm-2 control-label">Contraseña</label>'
					. '<div class="col-sm-4">'
						. '<input type="password" class="form-control" id="info_password" name="info[password]" placeholder="Clave de la llave" value="' . htmlspecialchars($password) . '" required data-bv-notempty="true" data-bv-stringlength="true">'
					. '</div>'
				. '</div>'
				. '<div class="form-group">'
					. '<label for="info_recipient" class="col-sm-2 control-label">Llave a firmar</label>'
					. '<div class="col-sm-10">'
						. '<select class="form-control" id="info_recipient" name="info[recipient]" required data-bv-notempty="true" data-bv-different="true" data-bv-different-field="info[key]">'
						. '<option></option>'
						. $this->gnuPGHtmlOptions($publicKeys, $recipient)
						. '</select>'
					. '</div>'
				. '</div>'
				. '<div class="form-group">'
					. '<label for="info_trustlevel" class="col-sm-2 control-label">Nivel de confianza</label>'
					. '<div class="col-sm-10">'
						. '<select class="form-control" id="info_trustlevel" name="info[trustlevel]" required data-bv-notempty="true" data-bv-between-inclusive="true" data-bv-between-min="1" data-bv-between-max="3">'
						. '<option value="0">Ninguno, usted no confia en la procedencia</option>'
						. '<option value="1">Sin comprobación, usted cree que la llave es propiedad de quien la envia</option>'
						. '<option value="2">Informal, usted realizó una verificación mínima</option>'
						. '<option value="3">Completo, usted verificó exhaustivamente</option>'
						. '</select>'
						. '<p class="help-block">Usted como mínimo debe realizar una verificación informal del origen de la llave.</p>'
					. '</div>'
				. '</div>'
				. '<div class="form-group">'
					. '<div class="col-sm-offset-2 col-sm-10">'
						. '<button type="submit" id="btnSubmit" class="btn btn-primary">Firmar llave</button>'
					. '</div>'
				. '</div>'
			. '</form>';

		// agrega los scripts de validación
		$this->jsInline = '$(document).ready(function() {
				$("#form-import").bootstrapValidator({
					onSuccess: function(e) {
						$("#btnSubmit").text("Firmando la llave...");
						$("input[type=submit]").attr("disabled","disabled");
					}
				});
			});';
	}

	/**
	 * Elimina la llave que se remite por POST
	 * @return array
	 */
	private function deleteKey()
	{
		$result = null;
		try {
			$keyID = filter_input(INPUT_POST, 'keyID', FILTER_SANITIZE_STRING);
			if (!$keyID) throw new Exception('Se requiere el identificador de la llave a eliminar.', 1);

			// inicia el componente y elimina la llave
			$gpg = new egmGnuPG($this->programPath, $this->homeDirectory);
			$res = $gpg->DeleteKey($keyID, 'public');
			if ($res === true)
				$result = array(
					'status' => 'ok'
				);
			else {
				$oldMessage = $gpg->error;
				$fullKey = $gpg->ListKeys('secret', $keyID);
				if (!empty($fullKey) && !empty($fullKey[0]['Fingerprint'])) {
					$res = $gpg->DeleteKey($fullKey[0]['Fingerprint'], 'secret');
					if ($res === true)
						$res = $gpg->DeleteKey($keyID, 'public');
					if ($res === true)
						$result = array(
							'status' => 'ok'
						);
					else
						throw new Exception(nl2br($gpg->error), 1);
				} else
					throw new Exception(nl2br($oldMessage), 1);
			}
		} catch (Exception $e) {
			$result = array(
					'status' => 'fail',
					'message' => $e->getMessage()
				);
		}
		return $result;
	}
}
