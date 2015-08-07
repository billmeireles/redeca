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
	 
define('DS', DIRECTORY_SEPARATOR );

/** Constantes usadas na setup*/
define('LOCALE_UNIX', 'ptb');
define('CONFIG_INI','config.ini');
define('LOCALE_WINDOWS', 'pt_BR');
define('CONFIG_DB','dbconfig.ini');
define('SCRIPT_DEMO','dump-demo.sql');
define('DIRECTORY_INSTALL','install');
define('SCRIPT_DROP','drop-tables.sql');
define('DIRECTORY_SQL','.'.DS.'sql'.DS);
define('FILE_INSTALLED','installed.txt');
define('SCRIPT_PRODUCTION', 'tables.sql');
define('DIRECTORY_LOGS','.'.DS.'..'.DS.'logs'.DS);
define('DIRECTORY_APP','application');
define('DIRECTORY_CONFIG','.'.DS.'..'.DS.DIRECTORY_APP.DS);

/** Mensagens de Erros*/
//01MN - Erros genericos da aplicação
define('DIRETORY_NON_EXISTENT','[0101] O diretório informado não existe.');
define('DIRETORY_SEARCH_ERROR','[0102] Erro na busca de diretorio.');
define('FILE_NOT_FOUND','[0103] Arquivo não foi encontrado ou não existe.');
define('FILE_SEARCH_ERROR','[0104] Erro na busca de arquivo.');
define('FILE_READ_ERROR','[0105] Erro ao ler arquivo.');
define('FILE_HANDLE_ERROR','[0106] Erro ao manipular arquivo.');
define('FILE_WRITE_ERROR','[0107] Erro ao escreve em arquivo.');
define('FILE_PARSE_ERROR','[0108] Erro ao fazer o parse em arquivo.');
define('FILE_HANDLE_ERROR_DROPS','[0109] Erro ao manipular arquivo de drop tables.');

//02MN - Erros especificos a arquivos
define('MANIPULA_DBCONFIG','[0201] Erro ao manipular o arquivo dbconfig.ini');
define('FILE_NOT_FOUND_DBCONFIG','[0202] O arquivo dbconfig.ini não foi encontrado ou não existe.');
define('CORRUPTED_FILE_DBCONFIG','[0203] O arquivo dbconfig.ini esta corrompido, é necessario que ele seja recuperado');
define('FILE_NOT_CLOSED','[0204] Erro ao manipular(fechar) o arquivo dbconfig.ini');

//03MN - Erros atrelados ao banco de dados.
define('CONNECT_ERRO_DATABASE','[0301] Erro ao conectar na base de dados.');
define('CREATE_DATABASE','[0302] Erro ao criar a base de dados. ');
define('ERROR_RUNNING_SCRIPTS','[0303] Erro na durante a execução dos scripts');
define('ERROR_RUNNING_SCRIPTS_DROPS','[0304] Erro na durante a execução dos scripts drops');
define('CHECK_PERMISSIONS',' Verifique as permissões do usuário informado.');

/** Menssagem de arquivo, instalação concluída com sucesso*/
define('SHOUT_SUCESS_INSTALLATION_FILE','A instalação foi concluída com sucesso no dia ');
define('MESSAGE_DATABASE','<p><b>Mensagem do banco:</b></p>');

/** Cofingurações do setup*/
ini_set('track_errors',1);
setlocale(LC_ALL, LOCALE_WINDOWS, LOCALE_UNIX);
date_default_timezone_set('America/Sao_Paulo');

/** Variáveis globais da aplicação */
$file_array = null;
if(file_exists(DIRECTORY_CONFIG.CONFIG_INI))		
	$file_array = parse_ini_file(DIRECTORY_CONFIG.CONFIG_INI);
else if(file_exists(DIRECTORY_APP.DS.CONFIG_INI))
	$file_array = parse_ini_file(DIRECTORY_APP.DS.CONFIG_INI);

$versionRedeca = '';
if(sizeof($file_array) > 0 && isset($file_array['version.number']))	
	$versionRedeca = $file_array['version.number'];

$arr_request_uri = explode('/',$_SERVER['REQUEST_URI']);

$url = 'http://'. $_SERVER['HTTP_HOST'];

?>