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
	
	$sim  = "<span style='color: #9DCA90 !important; text: bold'><b>Sim</b></span>";
	$nao  = "<span style='color: red;'><b>Não</b></span>";

	$handler_bdconfig    = (@file_exists(DIRECTORY_CONFIG.CONFIG_DB) && @ is_writable(DIRECTORY_CONFIG.CONFIG_DB));// || is_writable('..'.DS);
	$handler_dir_logs    = (@file_exists(DIRECTORY_LOGS) && @ is_writable(DIRECTORY_LOGS));
	$handler_dir_install = (@file_exists('.'.DS.'..'.DS.DIRECTORY_INSTALL) && @ is_writable('.'.DS.'..'.DS.DIRECTORY_INSTALL));

	$verification = array (
							'Versão PHP >= 5.1.4'                        => phpversion() < '5.1.4' ? $nao : $sim,
							'Suporte à compressão Zlib'                  => extension_loaded('zlib') ? $sim : $nao,
							'Suporte XML '                               => extension_loaded('xml') ? $sim : $nao,
							'Suporte MySQL'                              => (function_exists('mysql_connect') || function_exists('mysqli_connect')) ? $sim : $nao,
							'Suporte PDO'                                => extension_loaded('pdo') ? $sim : $nao,
							'Suporte PDO MySQL'                          => extension_loaded('pdo_mysql') ? $sim : $nao,
							'Suporte CURL '                         	 => extension_loaded('curl') ? $sim : $nao,
							'dbconfig.ini editável'                      => $handler_bdconfig ? $sim : $nao,
							'Permissão de escrita no diretório logs'     => $handler_dir_logs ? $sim : $nao,
							'Permissão de escrita no diretório install'  => $handler_dir_install ? $sim : $nao,
						);
						
	//flag para verificação 						
	$isValidationEnvironment = true;						
	foreach ($verification as $value){
		if($value == $nao){
			$isValidationEnvironment = false;
			break;
		}
	}							
