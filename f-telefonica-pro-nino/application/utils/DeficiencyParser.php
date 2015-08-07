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

class DeficiencyParser extends BasicParser{

	public static function getDeficiencyValues($object){

		$idDeficiency										= array();
		$idDeficiency[1]									= (int)self::validateValue(trim($object['_214_CodDefiCegueiraPessoa']), Parser::is_numeric());
		$idDeficiency[2]									= (int)self::validateValue(trim($object['_214_CodDefiMudezPessoa']), Parser::is_numeric());
		$idDeficiency[3]									= (int)self::validateValue(trim($object['_214_CodDefiSurdezPessoa']), Parser::is_numeric());
		$idDeficiency[4]									= (int)self::validateValue(trim($object['_214_CodDefiMentalPessoa']), Parser::is_numeric());
		$idDeficiency[5]									= (int)self::validateValue(trim($object['_214_CodDefiFisicaPessoa']), Parser::is_numeric());
		$idDeficiency[6]									= (int)self::validateValue(trim($object['_214_CodOutrasDefiPessoa']), Parser::is_numeric());
		$haveDef = false;
		foreach($idDeficiency as $def):
		if($def != 0) $haveDef = true;
		endforeach;
		if($haveDef == true){
			$deficiency										= array();
			$deficiency[DFY_ID_PERSON]						= null;
			$deficiency[DFY_ID_DEFICIENCY]					= $idDeficiency;
		}
		else
		{
			$deficiency										= null;
		}
				
		return $deficiency;
	}
}