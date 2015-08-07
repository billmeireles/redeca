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

class EmploymentParser extends BasicParser{

	public static function getEmploymentParserValues($object){

		$employment											= array();
		$employment[EMP_ID_EMPLOYMENT_STATUS]				= self::validateValue(trim($object['_242_SitMercadoTrabPessoa']), Parser::is_numeric());
		$employment[EMP_COMPANY_NAME]						= self::validateValue(trim($object['_243_NomEmpresaTrabPessoa']), Parser::is_string(), TRUE);
		if($employment[EMP_COMPANY_NAME] != null){
			$employment[EMP_ID_EMPLOYMENT]					= null;
			$employment[EMP_ID_ADDRESS]						= null;
			$employment[EMP_START_DATE]						= self::dateFormat(self::validateValue(trim($object['_245_DtaAdmisEmpresaPessoa']), Parser::is_numeric()), TRUE);
			$employment[EMP_END_DATE]						= null;
			$employment[EMP_NUMBER]							= null;
			$employment[EMP_COMPLEMENT]						= null;
			$employment[EMP_REFERENCE_POINT]				= null;
			$employment[EMP_ID_INCOME]						= null;

		}
		else
		{
			$employment 									= null;
		}
		return $employment;
	}
}