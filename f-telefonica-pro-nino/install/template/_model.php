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

/** sem acesso directo */
defined('_RESTRICTED_ACCESS') or die('Sem acesso direto');

/* se o arquivo não existir, obrigatoriamente a instalação tem que ocorrer;
 * mas se o arquivo existe e a flag é verdadeira, então o usuário quer executar
 * a instação novamente. */
if(!file_exists('.'.DS.FILE_INSTALLED) || $already_installed){
	define('ROOT','database');
	$php_errormsg = null;
	$dbHost       = $_POST["dbhostname"];
	$dbUserName   = $_POST["dbusername"];
	$dbPassword   = $_POST["vars_dbpassword"];
	$dbName       = $_POST["vars_dbname"];
	$dbExample    = $_POST["example"];
	$error        = array();
	$i = 0;

	/*executa conexao com o banco se não conseguir
	 * conectar com a base de dados, retorna o erro mysql. */
	if(!$id = @mysql_connect($dbHost,$dbUserName,$dbPassword)){
		$error[$i] = MESSAGE_DATABASE.'['.mysql_errno().'] '.mysql_error();
		header("Location:dataBase.php?dbhostname=".trim($_POST["dbhostname"])."&dbusername=".trim($_POST["dbusername"])."&vars_dbpassword=".trim($_POST["vars_dbpassword"])."&vars_dbname=".trim($_POST["vars_dbname"])."&error=".$error[$i]);

	}else{
		$con = null;
		/*executa a criacao do database de acordo
		 * com o nome informado pelo usuario*/
		$create = @mysql_query('CREATE DATABASE '.$dbName.';');
		$was_created = false;

		/*se nao criou, mostra a mensagem com o problema*/
		if(!$create){
			$errnosql = mysql_errno();
			$error[$i++] = MESSAGE_DATABASE.'['.mysql_errno().'] '.mysql_error();
			header("Location:dataBase.php?dbhostname=".trim($_POST["dbhostname"])."&dbusername=".trim($_POST["dbusername"])."&vars_dbpassword=".trim($_POST["vars_dbpassword"])."&vars_dbname=".trim($_POST["vars_dbname"])."&error=".$error[$i-1]);

		}else{
			$con = @mysql_select_db($dbName,$id)OR $error[$i++] = CONNECT_ERRO_DATABASE."  ".$php_errormsg;
		}


		$was_created = true;
		/*executa SCRIPT_PRODUCTION*/
		if(sizeof($error) <= 0 && $con == true){
			$fileName = null;

			$fileName = SCRIPT_PRODUCTION;
			$pathFile = DIRECTORY_SQL.$fileName;
			$isValid = @file_exists($pathFile)OR $error[$i++] = FILE_SEARCH_ERROR."  ".$php_errormsg;


			/*verifica existência de arquivo*/
			if($isValid && is_file($pathFile)){
				if($was_created){

					$file = @fopen($pathFile,"r")OR $error[$i++] = FILE_HANDLE_ERROR."  ".$php_errormsg;
					$sqlFile = @fread($file,filesize($pathFile))OR $error[$i++] = FILE_READ_ERROR."  ".$php_errormsg;
					@fclose($file)OR $error[$i++] = FILE_HANDLE_ERROR."  ".$php_errormsg;
					$sqlArray = explode(';',$sqlFile);
					// executa querys*/
					$isValid = true;
					foreach ($sqlArray as $stmt) {
						if (strlen($stmt) > 3){
							$result = @mysql_query($stmt)OR $error[$i++] = ERROR_RUNNING_SCRIPTS."  ".$php_errormsg;
							if (!$result){
								$isValid = false;
								$error[$i++] = MESSAGE_DATABASE.'['.mysql_errno().'] '.mysql_error();
								break;
							}
						}
					}
				}
				/*condicao do SCRIPT_DEMO*/
				if($dbExample)
				{
					/*execucao SCRIPT_DEMO*/
					$fileDemo = SCRIPT_DEMO;

					$pathFileDemo = DIRECTORY_SQL.$fileDemo;
					$isValid = @file_exists($pathFile)OR $error[$i++] = FILE_SEARCH_ERROR."  ".$php_errormsg;

					$file = @fopen($pathFileDemo,"r")OR $error[$i++] = FILE_HANDLE_ERROR."  ".$php_errormsg;
					$sqlFile = @fread($file,filesize($pathFileDemo))OR $error[$i++] = FILE_READ_ERROR."  ".$php_errormsg;
					@fclose($file)OR $error[$i++] = FILE_HANDLE_ERROR."  ".$php_errormsg;
					$sqlArrayDemo = explode(';',$sqlFile);

					/* executa querys */
					$isValid = true;
					foreach ($sqlArrayDemo as $stmt) {
						if (strlen($stmt) > 3){
							$result = @mysql_query($stmt)OR $error[$i++] = ERROR_RUNNING_SCRIPTS."  ".$php_errormsg;
							if (!$result){
								$isValid = false;
								$error[$i++] = MESSAGE_DATABASE.'['.mysql_errno().'] '.mysql_error();
								break;
							}
						}
					}
				}

				/* altera informações de conexão da aplicação *
				 *       verifica existência de arquivo       */
				if($isValid){
					$pathFile = DIRECTORY_CONFIG.CONFIG_DB;
					$isValid = @file_exists($pathFile)OR $error[$i++] = FILE_SEARCH_ERROR."  ".$php_errormsg;
					if(!$isValid){
						$error[$i++] = FILE_NOT_FOUND_DBCONFIG;
					}else {
						$file_array = @parse_ini_file($pathFile,true)OR $error[$i++] = FILE_PARSE_ERROR."  ".$php_errormsg;
						if(sizeof($file_array) <= 0){
							$error[$i++] = CORRUPTED_FILE_DBCONFIG;
							$isValid = false;
						}else{
							$file_array[ROOT]['db.adapter']         = 'PDO_MYSQL';
							$file_array[ROOT]['db.config.host']     = $dbHost;
							$file_array[ROOT]['db.config.username'] = $dbUserName;
							$file_array[ROOT]['db.config.password'] = $dbPassword;
							$file_array[ROOT]['db.config.dbname']   = $dbName;

							$content = "";
							foreach($file_array as $key => $item) {
								if(is_array($item)) {
									$content .= "[".$key."]\n";
									foreach ($item as $key2 => $item2) {
										if(is_numeric($item2) || is_bool($item2))
										$content .= $key2." = ".$item2."\n";
										else
										$content .= $key2." = ".$item2."\n";
									}
								} else {
									if(is_numeric($item) || is_bool($item))
									$content .= $key." = ".$item."\n";
									else
									$content .= $key." = ".$item."\n";
								}
							}
							$isValid = true;
						}

						$handle = @fopen($pathFile, 'w');
						if(!$handle){
							if(sizeof($error) <= 0)
							$error[$i++] = MANIPULA_DBCONFIG."  ".$php_errormsg;
							$isValid = false;
						}

						$fwrite = @fwrite($handle, $content);
						if(!$fwrite){
							if(sizeof($error) <= 0)
							$error[$i++] = MANIPULA_DBCONFIG."  ".$php_errormsg;
							$isValid = false;
						}

						if($isValid)
						@fclose($handle)OR $error[$i++] = FILE_NOT_CLOSED."  ".$php_errormsg;
					}

					$data = date('Y-m-d H:i:s');
					$installed = @fopen(FILE_INSTALLED, "w+")OR $error[$i++] = FILE_HANDLE_ERROR."  ".$php_errormsg;
					$conteudo = SHOUT_SUCESS_INSTALLATION_FILE." ". strftime("%A %d de %B de %Y as %H:%M", strtotime($data));
					@fwrite($installed, $conteudo)OR $error[$i++] = FILE_WRITE_ERROR."  ".$php_errormsg;
					@fclose($installed)OR $error[$i++] = FILE_HANDLE_ERROR."  ".$php_errormsg;
				}
			}else {
				$bool = @is_dir($pathFile)OR $error[$i++] = DIRETORY_SEARCH_ERROR."  ".$php_errormsg;
				if($bool)
				$error[$i++] = DIRETORY_NON_EXISTENT.$pathFile;
				else
				$error[$i++] = FILE_NOT_FOUND.$fileName;
			}
		}else{
			if(sizeof($error) <= 0)
			$error[$i++] = CONNECT_ERRO_DATABASE." ".mysql_error();
		}
	}
}