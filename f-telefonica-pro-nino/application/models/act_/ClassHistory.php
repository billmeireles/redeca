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
 * Pedro Henrique Ferro Gorla  - S2it		   		11/04/2012	                       Create file 
 * 
 */

define('CH_ID_CLASS_HISTORY',			'id_class_history');
define('CH_ID_USER',	 				'id_user');
define('CH_DATE_OPERATION',				'dat_operation');
define('CH_CLASS',						'id_class');
define('CH_ID_PERSON',					'id_person');
define('CH_TYPE_OPERATION',				'type_operation');
define('CH_ID_PROGRAM',					'id_program');
define('CH_VACANCY',					'vacancy');
define('CH_SCHEDULE',					'schedule');
define('CH_PERIOD',						'period');
define('CH_NAME',						'name');
define('CH_START_DATE',					'start_date');
define('CH_END_DATE',					'end_date');

require_once (CLS_CLASSMODEL.".php");
require_once (CLS_PERSON.".php");

class ClassHistory extends Zend_Db_Table_Abstract
{
	protected $_name = TBL_CLASS_HISTORY;
	protected $_primary = CH_ID_CLASS_HISTORY; 
	
}
