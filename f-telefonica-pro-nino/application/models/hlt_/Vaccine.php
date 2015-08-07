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
 * @copyright Funda��o Telef�nica - http://www.fundacaotelefonica.org.br 
 * 
 * @copyright Prefeitura Municipal de Ara�atuba - http://www.aracatuba.sp.gov.br 
 * @copyright Prefeitura Municipal de Bebedouro - http://www.bebedouro.sp.gov.br 
 * @copyright Prefeitura Municipal de Diadema - http://www.diadema.sp.gov.br 
 * @copyright Prefeitura Municipal de Guaruj� - http://www.guaruja.sp.gov.br 
 * @copyright Prefeitura Municipal de Itapecerica - http://www.itapecerica.sp.gov.br 
 * @copyright Prefeitura Municipal de Mogi das Cruzes - http://www.pmmc.com.br 
 * @copyright Prefeitura Municipal de S�o Carlos - http://www.saocarlos.sp.gov.br 
 * @copyright Prefeitura Municipal de V�rzea Paulista - http://www.varzeapaulista.sp.gov.br 
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
 * Fabio Montezuma  - S2it		   					26/10/2010	                       Create file 
 * 
 */

define('VAC_ID_VACCINE','id_vaccine');
define('VAC_NAME','name');
define('VAC_ID_VACCINE_TYPE','id_vaccine_type');
define('VAC_ID_PERIOD','id_period');
define('VAC_STATUS','status');

class Vaccine extends Zend_Db_Table_Abstract
{	
	var $data;
	
	protected $_name = TBL_VACCINE;
	protected $_primary = VAC_ID_VACCINE;
	
	protected $_dependentTables = array(CLS_VACCINATION);
	
	protected $_referenceMap    = array(
        CLS_VACCINE_TYPE => array(
            'columns'           => VAC_ID_VACCINE,
            'refTableClass'     => CLS_VACCINE_TYPE,
            'refColumns'        => VCT_ID_VACCINE_TYPE
        ),
        CLS_VACCINE_PERIOD => array(
            'columns'           => VAC_ID_PERIOD,
            'refTableClass'     => CLS_PERIOD,
            'refColumns'        => PER_ID_PERIOD
        )
    );
    
    /**
     * Instantiate a vaccine passing a form array
     */ 
    function __construct($arrValues)
    {
    	$this->data[VAC_ID_VACCINE] = isset($arrValues[0]) ? $arrValues[0] : 0;
    	$this->data[VAC_NAME] = trim($arrValues[1]);
    	$this->data[VAC_ID_VACCINE_TYPE] = $arrValues[2];
    	$this->data[VAC_ID_PERIOD] = $arrValues[3];
    	$this->data[VAC_STATUS] = isset($arrValues[4]) ? $arrValues[4] : '';
    }
}