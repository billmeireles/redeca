<?php 
/*
 * This program is free software: you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation, either version 2 of the License, or 
 * (at your option) any later version. 
 * 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
 * GNU General Public License for more details. 
 *
 * You should have received a copy of the GNU General Public License 
 * along with this program.  If not, see <http://www.gnu.org/licenses/>. 
 * 
 * @copyright Fundação Telefônica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Araçatuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guarujá - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de São Carlos - http://www.saocarlos.sp.gov.br 
 * @copyright Prefeitura Municipal de Várzea Paulista - http://www.varzeapaulista.sp.gov.br 
 * 
 * @copyright Copyright (C) 2008
 * 
 * @license GNU General Public License (GPL) - http://www.gnu.org/licenses/gpl.html 
 * 
 * @author Consulting services for Social Networks Creation by Instituto Fonte para o Desenvolvimento Social  - < fonte@fonte.org.br> - http:// www.fonte.org.br 
 * 
 * @author Consulting services for Software Requirements  by WebUse - <webuse@webuse.com.br > - http://webuse.com.br 
 * 
 * @author Initial Software development by S2it Solutions - <s2it@s2it.com.br> - http://s2it.com.br 
 * 
 * Changelog
 * 
 * Author                                           Date                               History 
 * -----------------------------------------        ------------                       ------------------ 
 * Flavio Luiz dos Santos de Souza  - S2it		     18/05/2010	                       Create file 
 * 
 */

define('_RESTRICTED_ACCESS', 1 ); 
require_once('..'.DIRECTORY_SEPARATOR.'setup_constants.php');

$already_installed = false;

$step = 3;

if(isset($_POST['alreadyInstalled']))
	$already_installed = $_POST['alreadyInstalled'];

if(!file_exists('.'.DS.FILE_INSTALLED) &&  !$already_installed){
	//verifica se o arquivo dbconfig.ini esta preenchido
	header('Location: dataBase.php');
	//se o arquivo existe a instação foi concluída mas o diretorio 'install' não foi removido!
}else{
	require_once('template'.DIRECTORY_SEPARATOR.'_model.php');	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br" >
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=iso-8859-1" HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"/>
		<script type="text/javascript" src="template/scripts/installation.js"></script>
		<link rel="stylesheet" type="text/css" media="screen" href="template/style/template.css" />
	</head>
	<title>Instalação do Redeca</title>
	<body>
		<div class="centerBox">
			<?php require_once('template'.DIRECTORY_SEPARATOR.'_header.php'); ?>
			<div id="content-box">
				<div id="content-pad">
					<?php require_once('template'.DIRECTORY_SEPARATOR.'_menu.php'); ?>
					<?php require_once('template'.DIRECTORY_SEPARATOR.'_end.php'); ?>
				</div>
			</div> 
			<?php require_once('template'.DIRECTORY_SEPARATOR.'_footer.php'); ?>
		</div>
	</body>
</html>
<?php }?>