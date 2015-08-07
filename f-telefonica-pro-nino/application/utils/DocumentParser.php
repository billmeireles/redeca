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

class DocumentParser extends BasicParser{

	public static function getDocumentValues($object){	

		$document											= array();
		$document[DOC_CPF]									= self::validateValue(trim($object['_233_NumCpfPessoa']), Parser::is_numeric(), TRUE);
		$document[DOC_NIS]									= self::validateValue(trim($object['_216_NumNisPessoa']), Parser::is_numeric(), TRUE);
		$document[DOC_SUS_CARD] 							= null;
		$document[DOC_RA] 									= null;
		$document[DOC_RG_NUMBER]							= (int)self::validateValue(trim($object['_224_NumIndentidadePessoa']), Parser::is_string(), TRUE);
		$document[DOC_RG_COMPLEMENT]						= self::validateValue(trim($object['_225_TxtComplementoPessoa']), Parser::is_string(), TRUE);
		$document[DOC_RG_EMISSION_DATE]						= self::dateFormat(self::validateValue(trim($object['_226_DtaEmissaoIdentPessoa']), Parser::is_numeric()), TRUE);
		$document[DOC_RG_SENDER]							= self::validateValue(trim($object['_228_SigOrgaoEmissaoPessoa']), Parser::is_string(), TRUE);
		$document[DOC_ID_RG_UF]								= self::validateValue(trim($object['_227_SigUfIdentPessoa']), Parser::is_string(), TRUE);
		if($document[DOC_RG_NUMBER] == 0)
		{
			$document[DOC_RG_COMPLEMENT] = null;
			$document[DOC_RG_EMISSION_DATE] = null;
			$document[DOC_RG_SENDER] = null;
			$document[DOC_ID_RG_UF]	= null;
		}
		$document[DOC_TITLE_NUMBER]							= self::validateValue(trim($object['_234_NumTituloEleitorPessoa']), Parser::is_numeric(), TRUE);
		$document[DOC_TITLE_ZONE]							= self::validateValue(trim($object['_235_NumZonaTitEleitorPessoa']), Parser::is_string(), TRUE);
		$document[DOC_TITLE_SECTION]						= self::validateValue(trim($object['_236_NumSecaoTitEleitorPessoa']), Parser::is_string(), TRUE);
		if($document[DOC_TITLE_NUMBER] == 0)
		{
			$document[DOC_TITLE_NUMBER] = null;
			$document[DOC_TITLE_SECTION] = null;
			$document[DOC_TITLE_ZONE] = null;
		}
		
		return $document;
	}
}