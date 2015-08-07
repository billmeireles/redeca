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

class PersonParser extends BasicParser{
	
	//pega as informações relacionadas a person de object e retorna person
	public static function getPersonValues($object)
	{
		//armazena o return na variavel object
		$object = BasicParser::parseSingleRegister($folder, $fileNames);
		
								$person 											= array();
								$person[PRS_ID_PERSON]								= null;
								$person[PRS_NAME] 									= self::validateValue(trim($object['_201_NomPessoa']), BasicParser::is_string());
								$person[PRS_METANAME]								= MetaPhoneClass::getMetaPhone($person[PRS_NAME]);
								$person[PRS_NICKNAME]								= null;
								$person[PRS_METANICKNAME]							= MetaPhoneClass::getMetaPhone($person[PRS_NICKNAME]);
								$person[PRS_TATTOO]									= null;
								$person[PRS_DEATH_DATE]								= null;
								$person[PRS_BIRTH_DATE] 							= self::dateFormat(self::validateValue(trim($object['_202_DtaNascPessoa']), BasicParser::is_numeric()));
								$person[PRS_SEX] 									= self::validateValue(trim($object['_203_CodSexoPessoa']), BasicParser::is_numeric());
								if($person[PRS_SEX] == 1)
								$person[PRS_SEX] = 'm';
								if($person[PRS_SEX] == 2)
								$person[PRS_SEX] = 'f';
								$person[PRS_ID_NATIONALITY] 						= (int)self::validateValue(trim($object['_204_CodNacionalidadePessoa']), BasicParser::is_numeric());

								$person[PRS_NATIVE_COUNTRY] 						= self::validateValue(trim($object['_205_CodPaisOrigemPessoa']), BasicParser::is_numeric());
								$person[PRS_ARRIVAL_DATE]							= self::dateFormat(self::validateValue(trim($object['_206_CodDtaChegadaPaisPessoa']), BasicParser::is_numeric()));
								$person[PRS_ID_MARITAL_STATUS]						= (int)self::validateValue(trim($object['_212_CodEstadoCivilPessoa']), BasicParser::is_numeric());
								$person[PRS_ID_RACE]								= (int)self::validateValue(trim($object['_215_CodRacaCorPessoa']), BasicParser::is_numeric());
								if($person[PRS_ID_RACE] == 0)
								$person[PRS_ID_RACE] = null;
								if($person[PRS_ID_MARITAL_STATUS] == 0)
								$person[PRS_ID_MARITAL_STATUS] = null;
								if($person[PRS_ID_NATIONALITY] == 0)
								$person[PRS_ID_NATIONALITY] = null;
		
		return $person;		
	}
}