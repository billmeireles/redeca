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

class ResidenceParser extends BasicParser{

	public static function getResidenceValues($object){

		$residence = array();
		$residence[RES_ID_RESIDENCE]					= null;
		$residence[RES_ID_ADDRESS]						= null;
		if(is_string($object['_204_NumResidenciaDomic']))
		$residence[RES_NUMBER]						    = null;
		else
		$residence[RES_NUMBER]						    = (int)self::validateValue(trim($object['_204_NumResidenciaDomic']), Parser::is_numeric());
		$residence[RES_COMPLEMENT] 						= self::validateValue(trim($object['_205_NomComplResidenciaDomic']), Parser::is_string(), TRUE);
		$residence[RES_REFERENCE_POINT]					= null;
		$residence[RES_ID_STATUS]		 				= (int)self::validateValue(trim($object['_213_SitDomicilioDomic']), Parser::is_numeric());
		$residence[RES_ID_MORADA]		 				= (int)self::validateValue(trim($object['_214_TipDomicilioDomic']), Parser::is_numeric());
		$residence[RES_ACCOMMODATION]		 			= (int)self::validateValue(trim($object['_215_NumComodosDomic']), Parser::is_numeric());
		$residence[RES_ID_BUILDING]		 				= (int)self::validateValue(trim($object['_216_TipConstrucaoDomic']), Parser::is_numeric());
		$residence[RES_ID_SUPPLY]						= (int)self::validateValue(trim($object['_217_TipAbastecimentoAguaDomic']), Parser::is_numeric());
		$residence[RES_ID_WATER]						= (int)self::validateValue(trim($object['_218_TipTratamentoAguaDomic']), Parser::is_numeric());
		$residence[RES_ID_ILLUMINATION]					= (int)self::validateValue(trim($object['_219_TipIluminacaoAguaDomic']), Parser::is_numeric());
		$residence[RES_ID_SANITARY]						= (int)self::validateValue(trim($object['_220_TipEscoamentoSanitarioDomic']), Parser::is_numeric());
		$residence[RES_ID_TRASH]						= (int)self::validateValue(trim($object['_221_TipDestinoLixoDomic']), Parser::is_numeric());
		$residence[RES_ID_LOCALITY] 					= (int)self::validateValue(trim($object['_211_TipLocalDomic']), Parser::is_numeric());
		if($residence[RES_ID_BUILDING] == 0)
		$residence[RES_ID_BUILDING] = null;
		if($residence[RES_ID_ILLUMINATION] == 0)
		$residence[RES_ID_ILLUMINATION] = null;
		if($residence[RES_ID_LOCALITY] == 0)
		$residence[RES_ID_LOCALITY] = null;
		if($residence[RES_ID_MORADA] == 0)
		$residence[RES_ID_MORADA] = null;
		if($residence[RES_ID_SANITARY] == 0)
		$residence[RES_ID_SANITARY] = null;
		if($residence[RES_ID_STATUS] == 0)
		$residence[RES_ID_STATUS] = null;
		if($residence[RES_ID_SUPPLY] == 0)
		$residence[RES_ID_SUPPLY] = null;
		if($residence[RES_ID_TRASH] == 0)
		$residence[RES_ID_TRASH] = null;
		if($residence[RES_ID_WATER] == 0)
		$residence[RES_ID_WATER] = null;

		return $residence;
	}
}