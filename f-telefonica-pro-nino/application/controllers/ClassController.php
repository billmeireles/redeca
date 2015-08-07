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
 * Jefferson Barros Lima  - S2it		    			05/05/2008	                       Create file
 *
 */

require_once ('BasicController.php');
require_once ('HistoryBusiness.php');

class ClassController extends BasicController {
	/**
	 * Inicializa��o
	 */
	function init() {
		parent::init ();
		parent::setControllerResources ( 'Class' );
		parent::setControllerHelp ( 'Class' );
		
		Zend_Loader::loadClass ( 'ClassForm' );
		Zend_Loader::loadClass ( 'ClassBusiness' );
		Zend_Loader::loadClass ( 'ActivityBusiness' );
		Zend_Loader::loadClass ( 'ClassAssistance' );
		Zend_Loader::loadClass ( 'ClassValidator' );
		Zend_Loader::loadClass ( 'ClassModel' );
		Zend_Loader::loadClass ( 'ActivityClass' );
		Zend_Loader::loadClass ( 'ProgramBusiness' );
		Zend_Loader::loadClass ( 'AssistanceBusiness' );
		Zend_Loader::loadClass ( 'Program' );
		Zend_Loader::loadClass ( 'Utils' );
		
		$frm = new ClassForm ();
		$frm->assembleRequest ( $this->_request );
		$this->view->form = $frm;
	}
	
	/**
	 * Exibe a tela que confirma o encerramento da turma
	 *
	 */
	function confirmAction() {
		$readOnly = ResourcePermission::isResourceReadOnly ( $this->_request );
		if ($readOnly) {
			trigger_error ( BasicBusiness::getLabelResources ()->notPermission, E_USER_ERROR );
		} else {
			//action a ser chamada caso o usu�rio confirma a exclus�o
			$this->view->action = DEFAULT_CLOSE_ACTION;
			
			//valida as informa��es inseridas no form pelo usu�rio
			$errorMessages = ClassValidator::validateClassId ( $this->view->form );
			ClassValidator::validateClassHaveEntity ( $this->view->form, $errorMessages );
			
			if (sizeof ( $errorMessages ) > 0) {
				$this->view->errorMessages = $errorMessages;
				return;
			}
			
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId ();
			
			//carrega as todos os programas de uma entidade espec�fica
			$this->view->programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity, null );
		}
	}
	
	/**
	 * Lista todas as turmas cadastradas no sistema
	 */
	function indexAction() {
		//recupera o id da Entidade do usu�rio logado
		$idEntity = UserLogged::getEntityId ();
		if ($idEntity) {
			//carrega todas as turmas de uma Entidade espec�fica
			$classes = ClassBusiness::loadAllClassesByEntity ( null, $idEntity );
			
			$this->view->classes = $classes;
			//carrega todas as atividades de uma turma especifica
			$this->view->acts = ClassBusiness::loadActivitiesClass ( $this->view->classes );
			
			//carrega as todos os programas de uma entidade espec�fica
			$this->view->programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity );
			
			$this->view->form->setIdEntity ( $idEntity );
		} else {
			$this->view->classes = $this->view->labels->required->user->entity;
		}
		
		//funcionalidade somente-leitura?
		if (ResourcePermission::isResourceReadOnly ( $this->_request )) {
			$this->view->readOnly = TRUE;
		} else {
			$this->view->readOnly = FALSE;
		}
	}
	
	/**
	 * Exibe formul�rio para cria��o de uma nova turma
	 */
	function newAction() {
		$readOnly = ResourcePermission::isResourceReadOnly ( $this->_request );
		if ($readOnly) {
			trigger_error ( BasicBusiness::getLabelResources ()->notPermission, E_USER_ERROR );
		} else {
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId ();
			
			//carrega as todos os programas de uma entidade espec�fica
			$programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity, null );
			
			//seta objeto no template
			$this->view->programs = $programs;
			
			//seta objeto no template
			$this->view->activities = $programs;
			
			//carrega os per�odos poss�veis
			$this->view->allPeriod = Constants::getPeriodMap ();
			
			//seta no form o id da entidade
			$this->view->form->setIdEntity ( $idEntity );
		}
	}
	
	function loadProgramAction() {
		$readOnly = ResourcePermission::isResourceReadOnly ( $this->_request );
		if ($readOnly) {
			trigger_error ( BasicBusiness::getLabelResources ()->notPermission, E_USER_ERROR );
		} else {
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId ();
			
			//valida as informa��es inseridas no form pelo usu�rio
			$errorMessages = ClassValidator::validateProgramId ( $this->view->form );
			ClassValidator::validateClassHaveEntity ( $this->view->form, $errorMessages );
			
			if (sizeof ( $errorMessages ) > 0) {
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//carrega a combo com os programas referentes a uma entidade espec�fica
				$this->view->programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity, null );
				
				//carrega a combo com as atividades referentes a uma entidade espec�fica
				$this->view->activities = ProgramBusiness::loadProgramGroupByEntity ( $idEntity, $this->view->form->getIdProgram () );
				
				//retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//carrega a combo com os programas referentes a uma entidade espec�fica
			$this->view->programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity );
			
			//carrega a combo com as atividades referentes a uma entidade espec�fica
			$this->view->activities = ProgramBusiness::loadProgramGroupByEntity ( $idEntity, $this->view->form->getIdProgram () );
			
			//carrega os per�odos poss�veis
			$this->view->allPeriod = Constants::getPeriodMap ();
		}
	}
	
	function loadProgramByClassAction() {
		$readOnly = ResourcePermission::isResourceReadOnly ( $this->_request );
		if ($readOnly) {
			trigger_error ( BasicBusiness::getLabelResources ()->notPermission, E_USER_ERROR );
		} else {
			//valida as informa��es inseridas no form pelo usu�rio
			$errorMessages = ClassValidator::validateProgramId ( $this->view->form );
			ClassValidator::validateClassId ( $this->view->form, $errorMessages );
			ClassValidator::validateClassHaveEntity ( $this->view->form, $errorMessages );
			
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId ();
			
			if (sizeof ( $errorMessages ) > 0) {
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//carrega a combo com os programas referentes a uma entidade espec�fica
				$this->view->programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity );
				
				//carrega a combo com as atividades referentes a uma entidade espec�fica
				$this->view->activities = ProgramBusiness::loadProgramGroupByEntity ( $idEntity, $this->view->form->getIdProgram () );
				
				//carrega uma turma espec�fica
				$this->view->classes = ClassBusiness::load ( $this->view->form->getIdClass () );
				
				//retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//carrega a combo com os programas referentes a uma entidade espec�fica
			$this->view->programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity );
			
			//carrega a combo com as atividades referentes a uma entidade espec�fica
			$this->view->activities = ProgramBusiness::loadProgramGroupByEntity ( $idEntity, $this->view->form->getIdProgram () );
			
			//carrega uma turma espec�fica
			$this->view->classes = ClassBusiness::load ( $this->view->form->getIdClass () );
			
			//carrega os per�odos poss�veis
			$this->view->allPeriod = Constants::getPeriodMap ();
		}
	}
	
	/**
	 * Exibe formul�rio para edi��o de uma turma
	 */
	function viewAction() {
		$readOnly = ResourcePermission::isResourceReadOnly ( $this->_request );
		if ($readOnly) {
			trigger_error ( BasicBusiness::getLabelResources ()->notPermission, E_USER_ERROR );
		} else {
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId ();
			
			//valida as informa��es inseridas no form pelo usu�rio
			$errorMessages = ClassValidator::validateProgramId ( $this->view->form );
			ClassValidator::validateClassHaveEntity ( $this->view->form, $errorMessages );
			
			if (sizeof ( $errorMessages ) > 0) {
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//carrega todos os programas de uma entidade espec�fica
			$programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity );
			
			//seta objeto no template
			$this->view->programs = $programs;
			
			//seta objeto no template
			$this->view->activities = $programs;
			
			//carrega uma turma espec�fica
			$classes = ClassBusiness::load ( $this->view->form->getIdClass () );
			
			$vacancy = ClassBusiness::getVacancyByClassId ( $classes->{CLS_ID_CLASS}, $classes->{CLS_VACANCY} );
			
			$this->view->person = ClassBusiness::getPersonByClass ( $classes->{CLS_ID_CLASS}, $classes->{CLS_VACANCY} );
			$wait = 0;
			foreach ( $this->view->person as $person ) {
				if ($person ['id_status'] == 2) {
					$wait ++;
				}
			}
			
			$this->view->classes = $classes;
			
			$this->view->vacancy = $vacancy;
			
			$this->view->wait = $wait;
			//carrega os per�odos poss�veis
			$this->view->allPeriod = Constants::getPeriodMap ();
			
			//seta flag para carregar somente atividades de um programa espec�fico
			$this->view->flagEdit = true;
		}
	
	}
	
	/**
	 * Exibe propriedades da turma
	 */
	function propertiesAction() {
		$readOnly = ResourcePermission::isResourceReadOnly ( $this->_request );
		if ($readOnly) {
			trigger_error ( BasicBusiness::getLabelResources ()->notPermission, E_USER_ERROR );
		} else {
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId ();
			
			$this->view->form->IdEntity = $idEntity;
			//valida as informa��es inseridas no form pelo usu�rio
			$errorMessages = ClassValidator::validateProgramId ( $this->view->form );
			ClassValidator::validateClassHaveEntity ( $this->view->form, $errorMessages );
			
			if (sizeof ( $errorMessages ) > 0) {
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//carrega todos os programas de uma entidade espec�fica
			$programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity );
			
			//seta objeto no template
			$this->view->programs = $programs;
			
			//seta objeto no template
			$this->view->activities = $programs;
			
			//carrega uma turma espec�fica
			$classes = ClassBusiness::load ( $this->view->form->getIdClass () );
			
			$vacancy = ClassBusiness::getVacancyByClassStatusId ( $classes->{CLS_ID_CLASS}, $classes->{CLS_VACANCY} );
			
			$this->view->person = ClassBusiness::getPersonByClass ( $classes->{CLS_ID_CLASS}, $classes->{CLS_VACANCY} );
			$wait = 0;
			foreach ( $this->view->person as $person ) {
				if (! empty ( $person ['beginning_date'] )) {
					
					//cria array de data sem /
					$date_part = explode ( '-', $person ['beginning_date'] );
					//inverte as posicoes do array para gerar data formatada
					$date_par = explode ( ' ', $date_part [2] );
					$date [] = ($date_par [0] . '/' . $date_part [1] . '/' . $date_part [0]);
				}
				if ($person ['id_status'] == 2) {
					$wait ++;
				}
			}
			
			$this->view->date = $date;
			
			$this->view->classes = $classes;
			
			$this->view->vacancy = $vacancy;
			
			$this->view->wait = $wait;
			//carrega os per�odos poss�veis
			$this->view->allPeriod = Constants::getPeriodMap ();
			
			//seta flag para carregar somente atividades de um programa espec�fico
			$this->view->flagEdit = true;
		}
	
	}
	
	/**
	 * Lista fila de espera
	 */
	function waitAction() {
		$id_class = $this->_request->getParam ( 'id_class' );
		$vacancy = $this->_request->getParam ( 'vacancy' );
		
		//carrega uma turma espec�fica
		$classes = ClassBusiness::load ( $id_class );
		//carrega lista de pessoas na fila de espera
		$person = ClassBusiness::getPersonByClass ( $id_class, $vacancy );
		//carrega lista
		$this->view->vacancy = ClassBusiness::getVacancyByClassStatusId ( $classes->{CLS_ID_CLASS}, $classes->{CLS_VACANCY} );
		
		$this->view->classes = $classes;
		
		$this->view->person = $person;
		
		$this->view->entity = $idEntity = UserLogged::getEntityId ();
		
		foreach ( $this->view->person as $person ) {
			//gera data no formato esperado
			if (! empty ( $person ['beginning_date'] )) {
				
				//cria array de data sem /
				$date_part = explode ( '-', $person ['beginning_date'] );
				//inverte as posicoes do array para gerar data formatada
				$date_par = explode ( ' ', $date_part [2] );
				$date [] = ($date_par [0] . '/' . $date_part [1] . '/' . $date_part [0]);
			}
		}
		
		$this->view->date = $date;
	
	}
	
	/**
	 * Delete pessoa da turma
	 */
	function deleteAction() {
		//recupera o id da Entidade do usu�rio logado
		$idEntity = UserLogged::getEntityId ();
		$readOnly = ResourcePermission::isResourceReadOnly ( $this->_request );
		if ($readOnly) {
			trigger_error ( BasicBusiness::getLabelResources ()->notPermission, E_USER_ERROR );
		} else {
			$id_personList = $this->_request->getParam ( '$personList' );
			$id_person = $this->_request->getParam ( 'person' );
			$id_class = $this->_request->getParam ( 'class' );
			
			//carrega uma turma espec�fica
			$class = ClassBusiness::load ( $id_class );
			
			if ($id_personList != null) {
				//deleta pessoa da turma/fila
				ClassBusiness::deletePerson ( $id_personList, $class, "Exclus�o Fila" );
			}
			
			if ($id_person != null) {
				//deleta pessoa da turma/fila
				ClassBusiness::deletePerson ( $id_person, $class, "Exclus�o Turma" );
			}
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect ( CLASS_CONTROLLER . '/' . DEFAULT_SUCCESS_ACTION . '/' . $this->view->form->idEntity () . '/' . $idEntity );
		}
	
	}
	
	/**
	 * Inclui pessoa da fila na turma
	 */
	function includeAction() {
		//recupera o id da Entidade do usu�rio logado
		$idEntity = UserLogged::getEntityId ();
		$readOnly = ResourcePermission::isResourceReadOnly ( $this->_request );
		if ($readOnly) {
			trigger_error ( BasicBusiness::getLabelResources ()->notPermission, E_USER_ERROR );
		} else {
			$id_person = $this->_request->getParam ( 'person' );
			
			$id_class = $this->_request->getParam ( 'class' );
			
			//carrega uma turma espec�fica
			$class = ClassBusiness::load ( $id_class );
			
			//inclui pessoa na turma
			ClassBusiness::includePerson ( $id_person, $class, "Inser��o da Fila de Espera na Turma" );
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect ( CLASS_CONTROLLER . '/' . DEFAULT_SUCCESS_ACTION . '/' . $this->view->form->idEntity () . '/' . $idEntity );
		}
	
	}
	
	/**
	 * Gera csv para turma selecionada
	 */
	function generateCsvAction() {
		
		$id_class = $this->_request->getParam ( 'class' );
		
		//carrega uma turma espec�fica pelo id
		$classes = ClassBusiness::load ( $id_class );
		
		$this->view->person = ClassBusiness::getPersonByClass ( $classes->{CLS_ID_CLASS}, $classes->{CLS_VACANCY} );
		
		$wait = 0;
		//retorna quantidade de pessoas na lista de espera
		foreach ( $this->view->person as $person ) {
			if ($person ['id_status'] == 2) {
				$wait ++;
			}
			//cria data no formato esperado
			if (! empty ( $person ['beginning_date'] )) {
				
				//cria array de data sem -
				$date_part = explode ( '-', $person ['beginning_date'] );
				//inverte as posicoes do array para gerar data formatada
				$date_par = explode ( ' ', $date_part [2] );
				$date [] = ($date_par [0] . '/' . $date_part [1] . '/' . $date_part [0]);
			}
		}
		$vacancy = ClassBusiness::getVacancyByClassStatusId ( $classes->{CLS_ID_CLASS}, $classes->{CLS_VACANCY} );
		
		$this->view->vacancy = $vacancy;
		
		$this->view->date = $date;
		
		$this->view->classes = $classes;
		
		$this->view->wait = $wait;
	}
	
	/**
	 * Transfere pessoa da turma
	 */
	function migratePersonAction() {
		$readOnly = ResourcePermission::isResourceReadOnly ( $this->_request );
		if ($readOnly) {
			trigger_error ( BasicBusiness::getLabelResources ()->notPermission, E_USER_ERROR );
		} else {
			$id_person = $this->_request->getParam ( 'person' );
			$confirm = $this->_request->getParam ( 'confirm' );
			$old_class = $this->_request->getParam ( 'class' );
			
			$this->view->person = ClassBusiness::getPersonById ( $id_person );
			$this->view->oldClass = ClassBusiness::load ( $old_class );
			
			$idEntity = UserLogged::getEntityId ();
			if ($idEntity) {
				//carrega todas as turmas de uma Entidade espec�fica
				$classes = ClassBusiness::loadAllClassesByEntity ( null, $idEntity );
				$this->view->classes = $classes;
				//carrega todas as atividades de uma turma especifica
				$this->view->acts = ClassBusiness::loadActivitiesClass ( $this->view->classes );
				
				//carrega as todos os programas de uma entidade espec�fica
				$this->view->programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity );
				
				$this->view->form->setIdEntity ( $idEntity );
			} else {
				$this->view->classes = $this->view->labels->required->user->entity;
			}
			
			if ($this->getRequest ()->isPost ()) {
				//recebe o id da nova classe
				$new_class = $this->_request->getPost ( 'idNewClass' );
				//recebe o id da pessoa a ser transferida
				$id_person = $this->_request->getPost ( 'id_person' );
				//recebe o id da classe antida
				$old_class = $this->_request->getPost ( 'oldClass' );
				
				if ($new_class == '') {
					$this->view->personEr = ClassBusiness::getPersonById ( $id_person );
					$this->view->oldClassEr = ClassBusiness::load ( $old_class );
					$this->view->errorMessages = 'Selecione a turma para a qual deseja transferir a pessoa';
				} else {
					
					//carrega uma turma espec�fica pelo id
					$class = ClassBusiness::load ( $new_class );
					
					//carrega uma turma espec�fica pelo id
					$older_class = ClassBusiness::load ( $new_class );
					
					ClassBusiness::transferPerson ( $id_person, $new_class, $older_class, "Transfer�ncia para a Turma " . $class->{CLS_NAME} . " " );
					
					//redireciona fluxo da aplica��o para p�gina de sucesso
					$this->_redirect ( CLASS_CONTROLLER . '/' . DEFAULT_SUCCESS_ACTION . '/' . $this->view->form->idEntity () . '/' . $idEntity );
				}
			
			}
		}
	}
	
	/**
	 * Hist�rico de altera��es
	 */
	function classHistoryAction() {
		
		$id_class = $this->_request->getParam ( 'class' );
		
		$this->view->history = ClassBusiness::loadAllHistory ( $id_class );
		
		foreach ( $this->view->history as $history ) {
			
			if (! empty ( $history ['id_user'] )) {
				$user [$history ['id_class_history']] = ClassBusiness::getUserById ( $history ['id_user'] );
			}
			
			if ($history ['type_operation'] != "Propriedades") {
				$person [$history ['id_class_history']] = ClassBusiness::getPersonById ( $history ['id_person'] );
			}
			
			if ($history ['type_operation'] == "Propriedades") {
				$program [$history ['id_class_history']] = ClassBusiness::getProgramType ( $history ['id_program'] );
			}
			//gera data no formato esperado
			if (! empty ( $history ['dat_operation'] )) {
				
				//cria array de data sem /
				$date_part = explode ( '-', $history ['dat_operation'] );
				//inverte as posicoes do array para gerar data formatada
				$date_par = explode ( ' ', $date_part [2] );
				$date [$history ['id_class_history']] = ($date_par [0] . '/' . $date_part [1] . '/' . $date_part [0]);
			}
		}
		
		$this->view->entity = $idEntity = UserLogged::getEntityId ();
		//carrega uma turma espec�fica
		$this->view->class = ClassBusiness::load ( $id_class );
		$this->view->activity = ClassBusiness::getCategoryByClass ( $id_class );
		$this->view->program = $program;
		$this->view->person = $person;
		$this->view->date = $date;
		$this->view->user = $user;
	
	}
	
	/**
	 * Hist�rico de altera��es
	 */
	function viewHistoryAction() {
		
		$id_class = $this->_request->getParam ( 'class' );
		
		$this->view->history = ClassBusiness::loadAllHistory ( $id_class );
		
		foreach ( $this->view->history as $history ) {
			if (! empty ( $history ['id_user'] )) {
				$user [] = ClassBusiness::getUserById ( $history ['id_user'] );
			}
			//gera data no formato esperado
			if (! empty ( $history ['dat_operation'] )) {
				
				//cria array de data sem /
				$date_part = explode ( '-', $history ['dat_operation'] );
				//inverte as posicoes do array para gerar data formatada
				$date_par = explode ( ' ', $date_part [2] );
				$date [] = ($date_par [0] . '/' . $date_part [1] . '/' . $date_part [0]);
			}
		}
		
		$this->view->date = $date;
		$this->view->user = $user;
	
	}
	/**
	 * Salva nova turma (cadastro)
	 */
	function addAction() {
		$readOnly = ResourcePermission::isResourceReadOnly ( $this->_request );
		if ($readOnly) {
			trigger_error ( BasicBusiness::getLabelResources ()->notPermission, E_USER_ERROR );
		} else {
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId ();
			
			//valida as informa��es inseridas no form pelo usu�rio
			$errorMessages = ClassValidator::validateClass ( $this->view->form );
			ClassValidator::validateClassEqualName ( $this->view->form, $errorMessages );
			ClassValidator::validateClassHaveEntity ( $this->view->form, $errorMessages );
			
			if (sizeof ( $errorMessages ) > 0) {
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//carrega a combo com os programas referentes a uma entidade espec�fica
				$this->view->programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity, null );
				
				//carrega a combo com as atividades referentes a uma entidade espec�fica
				$this->view->activities = ProgramBusiness::loadProgramGroupByEntity ( $idEntity, $this->view->form->getIdProgram () );
				
				//carrega os per�odos poss�veis
				$this->view->allPeriod = Constants::getPeriodMap ();
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//converte vari�veis do form para objeto do tipo "bean"
			$bean = $this->assembleFormToBean ( $this->view->form );
			
			//persiste as informa��es referentes a turma na base de dados
			ClassBusiness::saveClass ( $bean );
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect ( CLASS_CONTROLLER . '/' . DEFAULT_SUCCESS_ACTION . '/' . $this->view->form->idEntity () . '/' . $idEntity );
		}
	}
	
	/**
	 * Salva turma (edi��o)
	 */
	function editAction() {
		$readOnly = ResourcePermission::isResourceReadOnly ( $this->_request );
		if ($readOnly) {
			trigger_error ( BasicBusiness::getLabelResources ()->notPermission, E_USER_ERROR );
		} else {
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId ();
			$operation = "Propriedades";
			
			//valida as informa��es inseridas no form pelo usu�rio
			$errorMessages = ClassValidator::validateClass ( $this->view->form );
			ClassValidator::validateClassId ( $this->view->form, $errorMessages );
			ClassValidator::validateClassHaveEntity ( $this->view->form, $errorMessages );
			
			if (sizeof ( $errorMessages ) > 0) {
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//carrega a combo com os programas referentes a uma entidade espec�fica
				$this->view->programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity );
				
				//carrega a combo com as atividades referentes a uma entidade espec�fica
				$this->view->activities = ProgramBusiness::loadProgramGroupByEntity ( $idEntity, $this->view->form->getIdProgram () );
				
				//carrega uma turma espec�fica
				$this->view->classes = ClassBusiness::load ( $this->view->form->getIdClass () );
				
				//carrega os per�odos poss�veis
				$this->view->allPeriod = Constants::getPeriodMap ();
				
				//Retorna para o template atual exibindo as mensagens de valida��o
				return;
			}
			
			//converte vari�veis do form para objeto do tipo "bean"
			$bean = $this->assembleFormToBean ( $this->view->form );
			
			//recupera as infoma��es antes de alterar
			$old_properties = ClassBusiness::load ( $bean [CLS_ID_CLASS] );
			//persiste as informa��es referentes a educa��o na base de dados
			ClassBusiness::saveClass ( $bean, "Propriedades", $old_properties );
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect ( CLASS_CONTROLLER . '/' . DEFAULT_SUCCESS_ACTION . '/' . $this->view->form->idEntity () . '/' . $idEntity );
		}
	}
	
	function validAction() {
		//recupera o id da Entidade do usu�rio logado
		$idEntity = UserLogged::getEntityId ();
		
		$existPersonInClass = ClassValidator::validatePersonInClass ( $this->view->form );
		ClassValidator::validateClassHaveEntity ( $this->view->form, $existPersonInClass );
		
		if (sizeof ( $existPersonInClass ) > 0) {
			//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
			$this->view->notPersonInClass = $existPersonInClass;
			
			return;
		}
		
		//carrega as todos os programas de uma entidade espec�fica
		$this->view->programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity, null );
		
		//seta no template vari�vel para carregar div
		$this->view->viewDivMigrate = TRUE;
		
		//Retorna para o template atual exibindo as mensagens de valida��o
		return;
	}
	
	/**
	 * Encerra uma turma
	 */
	function closeAction() {
		$readOnly = ResourcePermission::isResourceReadOnly ( $this->_request );
		if ($readOnly) {
			trigger_error ( BasicBusiness::getLabelResources ()->notPermission, E_USER_ERROR );
		} else {
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId ();
			
			//valida as informa��es inseridas no form pelo usu�rio
			$errorMessages = ClassValidator::validateClassId ( $this->view->form );
			ClassValidator::validateClassHaveEntity ( $this->view->form, $errorMessages );
			
			if (sizeof ( $errorMessages ) > 0) {
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//redirecionar para template de par�metros inv�lidos
				return;
			}
			
			if ($this->view->form->getFlagMigrate ()) {
				$errorMessages = ClassValidator::validateFlagMigrate ( $this->view->form );
				ClassValidator::validateConfidentiality ( $this->view->form, $errorMessages );
				ClassValidator::validateNewClassId ( $this->view->form, $errorMessages );
				ClassValidator::validateEndDate ( $this->view->form, $errorMessages );
				
				if (sizeof ( $errorMessages ) > 0) {
					//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
					$this->view->errorMessages = $errorMessages;
					
					//carrega as todos os programas de uma entidade espec�fica
					$this->view->programs = ProgramBusiness::loadProgramGroupByEntity ( $idEntity, null );
					
					//redirecionar para template de par�metros inv�lidos
					return;
				}
				
				//formata a data inserida pelo usu�rio
				$dateEndPrevision = BasicForm::dateFormat ( $this->view->form->getEndDate () );
				
				//migra a turma e logo ap�s encerra a mesma
				ClassBusiness::migrateClass ( $this->view->form->getIdClass (), $this->view->form->getIdNewClass (), $dateEndPrevision, $this->view->form->getConfidentiality () );
			} else {
				//encerra uma turma espec�fica
				ClassBusiness::closeClass ( $this->view->form->getIdClass () );
			}
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect ( CLASS_CONTROLLER . '/' . DEFAULT_SUCCESS_ACTION . '/' . $this->view->form->idEntity () . '/' . $idEntity );
		}
	}
	
	/**
	 * Encerra uma atividade de uma turma
	 */
	function closeActivityAction() {
		$readOnly = ResourcePermission::isResourceReadOnly ( $this->_request );
		if ($readOnly) {
			trigger_error ( BasicBusiness::getLabelResources ()->notPermission, E_USER_ERROR );
		} else {
			//recupera o id da Entidade do usu�rio logado
			$idEntity = UserLogged::getEntityId ();
			
			//valida as informa��es inseridas no form pelo usu�rio
			$errorMessages = ClassValidator::validateActivityClassId ( $this->view->form );
			ClassValidator::validateClassHaveEntity ( $this->view->form, $errorMessages );
			if (sizeof ( $errorMessages ) > 0) {
				//carrega vari�vel com a(s) mensagem(ens) de erro de valida��o
				$this->view->errorMessages = $errorMessages;
				
				//redirecionar para template de par�metros inv�lidos
				return;
			}
			
			//atualiza o relacionamento entre turma e atividades
			ActivityBusiness::updateActivityClass ( $this->view->form->getIdActivityClass () );
			
			//redireciona fluxo da aplica��o para p�gina de sucesso
			$this->_redirect ( CLASS_CONTROLLER . '/' . DEFAULT_SUCCESS_ACTION . '/' . $this->view->form->idEntity () . '/' . $idEntity );
		}
	}
	
	/**
	 * Recupera as informa��es do form e retorna no array
	 */
	function assembleFormToBean(ClassForm $frm) {
		if (! Utils::isEmpty ( $frm )) {
			//cria uma vari�vel array de nome "bean"
			$bean = array ();
			
			if (! Utils::isEmpty ( $frm->getIdClass () )) {
				//adiciona uma vari�vel - idClass - do form no array "bean"
				$bean [CLS_ID_CLASS] = $frm->getIdClass ();
			} else {
				$bean [CLS_ID_CLASS] = null;
			}
			
			if (! Utils::isEmpty ( $frm->getIdProgram () )) {
				//adiciona uma vari�vel - idProgram - do form no array "bean"
				$bean [CLS_ID_PROGRAM] = $frm->getIdProgram ();
			}
			
			if (! Utils::isEmpty ( $frm->getIdActivityDetail () )) {
				//adiciona uma vari�vel - idActivityDetail - do form no array "bean"
				$bean [ACC_ID_ACTIVITY_DETAIL] = $frm->getIdActivityDetail ();
			}
			
			if (! Utils::isEmpty ( $frm->getIdActivityClass () )) {
				//adiciona uma vari�vel - idActivityClass - do form no array "bean"
				$bean [ACC_ID_ACTIVITY_CLASS] = $frm->getIdActivityClass ();
			}
			
			if (! Utils::isEmpty ( $frm->getClassName () )) {
				//adiciona uma vari�vel - className - do form no array "bean"
				$bean [CLS_NAME] = $frm->getClassName ();
			}
			
			if (! Utils::isEmpty ( $frm->getVacancy () )) {
				//adiciona uma vari�vel - vacancy - do form no array "bean"
				$bean [CLS_VACANCY] = $frm->getVacancy ();
			}
			
			if (! Utils::isEmpty ( $frm->getPeriod () )) {
				//adiciona uma vari�vel - period - do form no array "bean"
				$bean [CLS_PERIOD] = $frm->getPeriod ();
			}
			
			if (! Utils::isEmpty ( $frm->getTimeClass () )) {
				//adiciona uma vari�vel - time - do form no array "bean"
				$bean [CLS_SCHEDULE] = $frm->getTimeClass ();
			}
			
			return $bean;
		}
		
		return null;
	}
	
	//redireciona aplica��o para tela de sucesso do respectivo controller
	function successAction() {
		;
	}
}