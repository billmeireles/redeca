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
 * Guilherme Cabrini da Silva  - S2it		   		15/02/2012	                       Create file 
 * 
 */

class SocialProgramParser extends BasicParser{

	public static function getSocialProgramValues($object){
				
		$idSocialProgram									= array();
		$idSocialProgram[1]									= self::validateValue(trim($object['_270_IndBenefPetiPessoa']), Parser::is_string(), TRUE);
		$idSocialProgram[2]									= self::validateValue(trim($object['_270_IndBenefLoasBpcPessoa']), Parser::is_string(), TRUE);
		$idSocialProgram[3]									= null;
		$idSocialProgram[4]									= null;
		$idSocialProgram[5]									= null;
		$idSocialProgram[6]									= null;
		$idSocialProgram[7]									= null;
		$idSocialProgram[8]									= self::validateValue(trim($object['_270_IndOutrosBeneficiosPessoa']), Parser::is_string(), TRUE);
		$haveSop = false;
		foreach($idSocialProgram as $sop):
		if($sop != 0) $haveSop = true;
		endforeach;
		if($haveSop == true){
			$socialProgram									= array();
			$registerDate									= array();
			$registerDate[1]								= self::dateFormat(self::validateValue(trim($object['_270_DtaIncPetiPessoa']), Parser::is_numeric()));
			$registerDate[2]								= null;
			$registerDate[3]								= self::dateFormat(self::validateValue(trim($object['_270_DtaIncAgjPessoa']), Parser::is_numeric()));
			$registerDate[4]								= null;
			$registerDate[5]								= null;
			$registerDate[6]								= self::dateFormat(self::validateValue(trim($object['_270_DtaIncProgerPessoa']), Parser::is_numeric()));
			$registerDate[7]								= null;
			$registerDate[8]								= null;
			$socialProgram[SPG_ID_PERSON]					= null;
			$socialProgram[SPG_ID_SOCIAL_PROGRAM]			= $idSocialProgram;
			$socialProgram[SPG_REGISTER_DATE]				= $registerDate;
		}
		else
		{
			$socialProgram									= null;
		}
			
		return $socialProgram;
	}
}