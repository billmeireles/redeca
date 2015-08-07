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
	
	$ok_corret  = "<span style='color: #9DCA90;text: bold'><b>Ok</b></span>";
	$on_corret  = "<span style='color: #9DCA90;text: bold'><b>On</b></span>";
	$off_error  = "<span style='color: red;'><b>Off</b></span>";
	
	$verificationSettings = array ();
	//This sets the maximum time in seconds a script is allowed to run before it is terminated by the parser.
	$verificationSettings['Tempo máximo de execução'] = array('120 segundos', ini_get('max_execution_time') >= 120 ? $ok_corret : $off_error);
	//Tells PHP whether the short form (<?) of PHP's open tag should be allowed
    $verificationSettings['Reconhecer curtas tags PHP'] = array('On', ini_get('short_open_tag')== 1 ? $on_corret : $off_error);
	 
	$isRecommendedSettings = true;						
	foreach ($verificationSettings as $value){
		if(strstr($value[1], 'red')){
			$isRecommendedSettings = false;
			break;
		}
	}
