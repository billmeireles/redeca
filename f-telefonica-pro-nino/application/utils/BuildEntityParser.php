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

class BuildEntityParser{

	public static function insertIntoDatabase($personData){

		$uniqueRegisterArray[$i] = $personData;


		foreach($uniqueRegisterArray as $array)
		{

			if($array[Constants::ARRAY_TYPE] == Constants::ROWTYPE_FAMILY)
			{
				if(is_array($array[CLS_ADDRESS]))
				{
					$address = $array[CLS_ADDRESS];
					$metanameAddress = $address[ADR_ADDRESS_METAFONE];
					$res = AddressBusiness::searchByZipCode($address[ADR_ZIP_CODE]);
					if(count($res) == 0)
					$res = AddressBusiness::searchByMetafone($metanameAddress);

					if(count($res) > 0)
					foreach($res as $address)
					{
						$idAddress = $address->{ADR_ID_ADDRESS};
					}
					else{
						$idAddress = null;
						Logger::loggerImport('id_address é nulo. [Line]: '.implode(' | ',$res));
					}

					unset($res);
					unset($address);
				}

				if(is_array($array[CLS_RESIDENCE]))
				{
					$residence = $array[CLS_RESIDENCE];

					$result = AddressBusiness::searchByMetafone($metanameAddress);

					if($result != null){
						foreach($result as $res)
						$resIdAddress[] = $res->{ADR_ID_ADDRESS};
							
						if($resIdAddress->{ADR_ID_ADDRESS}){
							foreach($resIdAddress as $resId)
							$resResidence[] = ResidenceBusiness::findByAddress($resId);

							if($resResidence->{RES_ID_RESIDENCE}){
								foreach($resResidence as $res)
								if(($res->{RES_NUMBER} == $residence->{RES_NUMBER}) && ($res->{RES_COMPLEMENT} == $residence->{RES_COMPLEMENT}))
								{
									$idResidence = $res->{RES_ID_RESIDENCE};
									break;
								}
							}else{
								$residence[RES_ID_ADDRESS] = $idAddress;
								$idResidence = ResidenceBusiness::save($residence);
							}
						}
						else
						$residence[RES_ID_ADDRESS] = $idAddress;
						$idResidence = ResidenceBusiness::save($residence);
					}
					else
					$residence[RES_ID_ADDRESS] = $idAddress;
					$idResidence = ResidenceBusiness::save($residence);

					unset($result);
					unset($resIdAddress);
					unset($resResidence);
					unset($residence);
				}

				if(is_array($array[CLS_FAMILY_ID]))
				{
					$familyId = $array[CLS_FAMILY_ID];
					$idFamily = FamilyBusiness::saveFamilyId();
					unset($familyId);
				}

				if(is_array($array[CLS_FAMILYRESIDENCE]))
				{
					$familyResidence = $array[CLS_FAMILYRESIDENCE];
					$familyResidence[FRS_ID_RESIDENCE] = $idResidence;
					$familyResidence[FRS_ID_FAMILY] = $idFamily;
					FamilyResidenceBusiness::save($familyResidence);
					unset($familyResidence);
				}

				if(is_array($array[CLS_FAMILY]) && is_array($array[CLS_REPRESENTATIVE]))
				{
					$famFamily = $array[CLS_FAMILY];
					$famRepresetation = $array[CLS_REPRESENTATIVE];
				}
			}

			if($array[Constants::ARRAY_TYPE] == Constants::ROWTYPE_PERSON)
			{

				if(is_array($array[CLS_PERSON]))
				{
					$person = $array[CLS_PERSON];
					if($person[PRS_ID_NATIONALITY] == 0)
					$person[PRS_ID_NATIONALITY] = null;
					else if($person[PRS_ID_NATIONALITY] > 1)
					$person[PRS_ID_NATIONALITY] = 2;
					$idPerson = PersonBusiness::save($person);
					unset($person);
				}

				if(is_array($famFamily))
				{
					$famFamily[FAM_ID_PERSON] = $idPerson;
					$famFamily[FAM_ID_FAMILY] = $idFamily;

					if(is_array($array[CLS_CONSANGUINE]))
					{
						$csg = $array[CLS_CONSANGUINE];
						$famFamily[FAM_ID_KINSHIP] = $csg[CSG_ID_CONSANGUINE_TYPE];
						if($famFamily[FAM_ID_KINSHIP] == 0) $famFamily[FAM_ID_KINSHIP] = 20;
						FamilyBusiness::save($famFamily);

						if($csg[CSG_ID_PERSON_FROM] == 1 && $csg[CSG_ID_PERSON_TO] == 1)
						{
							if(is_array($famRepresetation))
							{
								$famRepresetation[REP_ID_PERSON] = $idPerson;
								$famRepresetation[REP_ID_FAMILY] = $idFamily;
								RepresentativeBusiness::save($famRepresetation);
							}
						}

						if(is_array($array[CLS_EXPENSE]))
						{
							$expense = $array[CLS_EXPENSE];
							$arrExpense = array();
							$arrIdFamily[0] = 0;
							$expense[EXP_ID_FAMILY] = $idFamily;
							$value = $expense[EXP_EXPENSE_VALUE];
							foreach($value as $k=>$v)
							{
								if($v != 0)
								{
									if($k > 8)
									$expense[EXP_ID_EXPENSE_TYPE] = 8;
									else
									$expense[EXP_ID_EXPENSE_TYPE] = $k;

									$int = substr($v, 0, count($v)-3);
									$dec = substr($v, -2);
									$float = (float)$int.'.'.$dec;
									$expense[EXP_EXPENSE_VALUE] = $float;

									if(!array_search($expense[EXP_ID_FAMILY], $arrIdFamily))
									{
										if(count($arrExpense) > 0)
										{
											$flagExp = false;
											foreach($arrExpense as $arr)
											{
												if($arr[EXP_ID_EXPENSE_TYPE] == $expense[EXP_ID_EXPENSE_TYPE])
												{
													$arr[EXP_EXPENSE_VALUE] += $expense[EXP_EXPENSE_VALUE];
													$flagExp = true;
												}
											}

											if($flagExp === false)
											{
												$arrExpense[] = $expense;
											}
										}
										else
										{
											$arrExpense[] = $expense;
										}
										$arrIdFamily[] = $expense[EXP_ID_FAMILY];
									}
								}
							}
							if(count($arrExpense) > 0)
							{
								foreach($arrExpense as $arr)
								{
									if($arr)
									{
										ExpenseBusiness::save($arr);
									}
								}
							}
						}
					}
					unset($csg);
					unset($expense);
					unset($arrExpense);
				}
					
				if(is_array($array[CLS_DEFICIENCY]))
				{
					$def = $array[CLS_DEFICIENCY];
					$def[DFY_ID_PERSON] = $idPerson;

					$defTypes = $def[DFY_ID_DEFICIENCY];
					foreach($defTypes as $k=>$v){
						if($v != 0){
							$def[DFY_ID_DEFICIENCY] = $k;
							DeficiencyBusiness::save($def);
						}
					}
					unset($def);
				}

				if(is_array($array[CLS_DOCUMENT]))
				{
					$docs = $array[CLS_DOCUMENT];
					$docs[DOC_ID_PERSON] = $idPerson;
					if($docs[DOC_RG_NUMBER] != 0)
					{
						if(is_string($docs[DOC_ID_RG_UF]))
						{
							$idUf = UFBusiness::findByUf($docs[DOC_ID_RG_UF]);
							$docs[DOC_ID_RG_UF] = $idUf->{UF_ID_UF};
						}
						else
						{
							$docs[DOC_ID_RG_UF] = null;
						}
						DocumentBusiness::save($docs);
					}
					unset($idUf);
					unset($docs);
				}

				if(is_array($array[CLS_CTPS]))
				{
					$ctps = $array[CLS_CTPS];
					$ctps[CTS_ID_PERSON] = $idPerson;
					if($ctps[CTS_NUMBER] != 0)
					{
						if(is_string($ctps[CTS_ID_UF]))
						{
							$idUf = UFBusiness::findByUf($ctps[CTS_ID_UF]);
							$ctps[CTS_ID_UF] = $idUf->{UF_ID_UF};
						}
						else
						{
							$ctps[CTS_ID_UF] = null;
						}
						CtpsBusiness::save($ctps);
					}
					unset($idUf);
					unset($ctps);
				}

				if(is_array($array[CLS_CIVILCERTIFICATE]))
				{
					$civil = $array[CLS_CIVILCERTIFICATE];
					$civil[CCF_ID_PERSON] = $idPerson;
					if($civil[CCF_CERTIFICATE_TYPE] != 0)
					{
						if(is_string($civil[CCF_ID_UF]))
						{
							$idUf = UFBusiness::findByUf($civil[CCF_ID_UF]);
							$civil[CCF_ID_UF] = $idUf->{UF_ID_UF};
						}
						else
						{
							$civil[CCF_ID_UF] = null;
						}
						CivilCertificateBusiness::save($civil);
					}
					unset($idUf);
					unset($civil);
				}

				if(is_array($array[CLS_INCOME]))
				{
					$income = $array[CLS_INCOME];
					$income[ICM_ID_PERSON] = $idPerson;
					$income[ICM_REGISTER_DATE] = date("Y-m-d");
					$value = $income[ICM_VALUE];
					foreach($value as $k=>$v){
						if($v != 0)
						{
							$income[ICM_ID_INCOME] = $k;
							$int = substr($v, 0, count($v)-3);
							$dec = substr($v, -2);
							$float = (float)$int.'.'.$dec;
							$income[ICM_VALUE] = $float;
							$idIncome = IncomeBusiness::save($income);

							if(is_array($array[CLS_EMPLOYMENT]))
							{
								$emp = $array[CLS_EMPLOYMENT];
								$emp[EMP_ID_INCOME] = $idIncome;
								EmploymentBusiness::save($emp);
							}
						}
						unset($int);
						unset($dec);
						unset($float);
					}
					unset($income);
					unset($value);
				}

				if(is_array($array[CLS_SOCIALPROGRAM]))
				{
					$social = $array[CLS_SOCIALPROGRAM];
					$social[SPG_ID_PERSON] = $idPerson;
					$benefit = $social[SPG_ID_SOCIAL_PROGRAM];
					$date = $social[SPG_REGISTER_DATE];
					$social[SPG_REGISTER_DATE] = null;
					foreach($benefit as $k=>$v)
					{
						if($v != 0 && $v != null)
						{
							$social[SPG_ID_SOCIAL_PROGRAM] = $k;
							foreach($date as $j=>$i){
								if($k == $j){
									$social[SPG_REGISTER_DATE] = $i;
								}
							}
							SocialProgramBusiness::save($social);
						}
					}
					unset($social);
					unset($benefit);
					unset($date);
				}

				if(is_array($array[CLS_LEVELINSTRUCTION]))
				{
					$level = $array[CLS_LEVELINSTRUCTION];
					$level[LIT_ID_PERSON] = $idPerson;
					if($level[LIT_ID_DEGREE] > 0)
					$idLevel = LevelInstructionBusiness::save($level);
					unset($level);
				}

				if(is_array($array[CLS_SCHOOL]))
				{
					$school = $array[CLS_SCHOOL];
					$res = SchoolBusiness::findByInepCode($school[SCH_INEP]);
					if($res == null)
					$res = SchoolBusiness::findByName($school[SCH_NAME]);

					if($res == true)
					{
						$idSchool = $res->{SCH_ID_SCHOOL};
					}

					if($idSchool)
					{
						if(is_array($array[CLS_REGISTRATION])){
							$registration = $array[CLS_REGISTRATION];
							$registration[REG_ID_LEVEL_INSTRUCTION] = $idLevel;
							$registration[REG_ID_SCHOOL] = $idSchool;
							RegistrationBusiness::save($registration);
						}
					}
					unset($res);
					unset($school);
					unset($registration);
				}
			}
			unset($uniqueRegisterArray);
		}



	}


}