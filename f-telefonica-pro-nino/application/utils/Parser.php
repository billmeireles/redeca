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
 * Jefferson Barros Lima  - S2it		   				26/02/2008	                       Create file
 * Guilherme Cabrini da Silva - S2it				    27/02/2012						   Alter file
 * 
 */


class Parser
{
	static function is_numeric(){ return 'is_numeric'; }
	static function is_string(){ return 'is_string'; }

	/**
	 * Faz a leitura do arquivo e popula um Array para persistencia na tabela UF
	 * @param String $fullFilePath - path para o arquivo incluindo o nome e extensão
	 * @param Integer $numberOfLines - numero de linhas que se deseja analisar
	 * @param flag - true para persistir em banco as informações do parse
	 * @return Array Array contendo informação de endereço ou NULL caso não seja realizado o parse
	 */
	public static function parseSingleRegister($folder, $fileNames, $numberOfLines=0, &$flag=NULL)
	{
		Zend_Loader::loadClass('AddressBusiness');
		Zend_Loader::loadClass('ResidenceBusiness');
		Zend_Loader::loadClass('FamilyResidenceBusiness');
		Zend_Loader::loadClass('PersonBusiness');
		Zend_Loader::loadClass('FamilyBusiness');
		Zend_Loader::loadClass('RepresentativeBusiness');
		Zend_Loader::loadClass('DeficiencyBusiness');
		Zend_Loader::loadClass('UfBusiness');
		Zend_Loader::loadClass('DocumentBusiness');
		Zend_Loader::loadClass('CtpsBusiness');
		Zend_Loader::loadClass('CivilCertificateBusiness');
		Zend_Loader::loadClass('IncomeBusiness');
		Zend_Loader::loadClass('ExpenseBusiness');
		Zend_Loader::loadClass('SocialProgramBusiness');
		Zend_Loader::loadClass('LevelInstructionBusiness');
		Zend_Loader::loadClass('SchoolBusiness');
		Zend_Loader::loadClass('RegistrationBusiness');
		Zend_Loader::loadClass('EmploymentBusiness');
		Zend_Loader::loadClass('BasicParser');
		Zend_Loader::loadClass(CLS_FAMILYRESIDENCE);
		Zend_Loader::loadClass(CLS_RESIDENCE);
		Zend_Loader::loadClass(CLS_ADDRESS);
		Zend_Loader::loadClass(CLS_PERSON);
		Zend_Loader::loadClass(CLS_DOCUMENT);
		Zend_Loader::loadClass(CLS_CTPS);
		Zend_Loader::loadClass(CLS_DEFICIENCY);
		Zend_Loader::loadClass(CLS_INCOME);
		Zend_Loader::loadClass(CLS_CIVILCERTIFICATE);
		Zend_Loader::loadClass(CLS_EMPLOYMENT);
		Zend_Loader::loadClass(CLS_EXPENSE);
		Zend_Loader::loadClass(CLS_CONSANGUINE);
		Zend_Loader::loadClass(CLS_SOCIALPROGRAM);
		Zend_Loader::loadClass(CLS_LEVELINSTRUCTION);
		Zend_Loader::loadClass(CLS_SCHOOL);
		Zend_Loader::loadClass(CLS_REGISTRATION);
		Zend_Loader::loadClass(CLS_FAMILY);
		Zend_Loader::loadClass(CLS_FAMILY_ID);
		Zend_Loader::loadClass(CLS_REPRESENTATIVE);
		Zend_Loader::loadClass('MetaPhoneClass');

		//verificação $fileNames
		if(!empty($fileNames) && $fileNames !== NULL)
		{
			$uniqueRegisterArray = NULL;
			foreach($fileNames as $currentFile)
			{
				//verificação que seleciona o arquivo da importação
				if($currentFile !== NULL && !is_dir($folder.'/'.$currentFile))
				{
					Logger::loggerImport("Arquivo aberto com sucesso. [nome=$folder/$currentFile]");
					$fileName = $folder."/".$currentFile;
					$lines = file($folder."/".$currentFile);	
					
					// Percorre o array, mostrando o fonte HTML com numeração de linhas.
					$i = 0;


					Logger::loggerImport("Extraindo e validando dados do arquivo.txt...");

					foreach ($lines as $line_num => $line){
						
						//Extraindo dados do arquivo.txt
						if(substr($line, 37, 2) == "01")
						{
							$obj1['type']								= Constants::ROWTYPE_FAMILY;
							$obj1['_103_DtaPesquisaDomic'] 				= self::getValue($line, 90, 8);  //bloco-1			//date
							$obj1['_201_CepResidenciaDomic'] 			= self::getValue($line, 439, 8); //bloco-1		    //double
							$obj1['_203_NomLogradouroDomic'] 			= self::getValue($line, 174, 82);//bloco-1         	//varchar(50)
							$obj1['_204_NumResidenciaDomic'] 			= self::getValue($line, 336, 7); //bloco-1          //varchar(7)
							$obj1['_205_NomComplResidenciaDomic'] 		= self::getValue($line, 364, 15);//bloco-1		    //varchar(15)
						}

						if(substr($line, 37, 2) == "02")
						{
							$obj2['type']								= Constants::ROWTYPE_FAMILY;
							$obj2['_211_TipLocalDomic'] 				= self::getValue($line, 40, 1);//bloco-2		    //byte
							$obj2['_213_SitDomicilioDomic']		 		= self::getValue($line, 40, 1);//bloco-2            //byte
							$obj2['_214_TipDomicilioDomic']		 		= self::getValue($line, 41, 1);//bloco-2 	        //byte
							$obj2['_215_NumComodosDomic']		 		= self::getValue($line, 42, 1);//bloco-2		    //double
							$obj2['_216_TipConstrucaoDomic']		 	= self::getValue($line, 47, 1);//bloco-2            //byte
							$obj2['_218_TipTratamentoAguaDomic']		= self::getValue($line, 48, 1);//bloco-2            //byte
							$obj2['_217_TipAbastecimentoAguaDomic']		= self::getValue($line, 49, 1);//bloco-2		    //byte
							$obj2['_219_TipIluminacaoAguaDomic']		= self::getValue($line, 53, 1);//bloco-2		    //byte
							$obj2['_220_TipEscoamentoSanitarioDomic']	= self::getValue($line, 51, 1);//bloco-2		    //byte
							$obj2['_221_TipDestinoLixoDomic']			= self::getValue($line, 52, 1);//bloco-2		    //byte
						}

						if(substr($line, 37, 2) == "03")
						{
							$obj3['type']								= Constants::ROWTYPE_PERSON;
							$obj3['_255_ValDespAlimentPessoa']			= self::getValue($line, 350, 18);//bloco-3		           //double
							$obj3['_256_ValDespAguaPessoa']				= self::getValue($line, 337, 18);//bloco-3		           //double
							$obj3['_257_ValDespLuzPessoa']				= self::getValue($line, 332, 18);//bloco-3		           //double
							$obj3['_258_ValDespTransporPessoa']			= self::getValue($line, 356, 18);//bloco-3		           //double
							$obj3['_259_ValDespMedicamentosPessoa']		= self::getValue($line, 368, 18);//bloco-3		           //double
							$obj3['_260_ValDespGazPessoa']				= self::getValue($line, 344, 18);//bloco-3		           //double
							$obj3['_261_ValOutrasDespPessoa']			= self::getValue($line, 362, 5); //bloco-3                 //double
						}

						if(substr($line, 37, 2) == "04")
						{
							$obj4['type']								= Constants::ROWTYPE_PERSON;
							$obj4['_102_NumOrdemPessoa'] 				= self::getValue($line, 57, 2);//bloco-4			      //byte
							$obj4['_201_NomPessoa'] 					= self::getValue($line, 59, 70);//bloco-4		          //varchar(70)
							$obj4['_202_DtaNascPessoa'] 				= self::getValue($line, 175, 8);//bloco-4		          //date
							$obj4['_203_CodSexoPessoa'] 				= self::getValue($line, 174, 1);//bloco-4		          //byte
							$obj4['_204_CodNacionalidadePessoa'] 		= self::getValue($line, 331, 1);//bloco-4		          //byte
							$obj4['_205_CodPaisOrigemPessoa'] 			= self::getValue($line, 415, 3);//bloco-4		          //integer(3)
							$obj4['_215_CodRacaCorPessoa']				= self::getValue($line, 185, 1);//bloco-4		          //byte
							$obj4['_216_NumNisPessoa']					= self::getValue($line, 129, 11);//bloco-4		          //double
							$obj4['_263_NumOrdemRespPessoa']			= self::getValue($line, 57, 2);  //bloco-4		          //byte
							$obj4['_264_CodParentRelMaePessoa']			= self::getValue($line, 183, 2); //bloco-4	              //byte
						}

						if(substr($line, 37, 2) == "05")
						{
							$obj5['type']								= Constants::ROWTYPE_PERSON;
							$obj5['_217_CodCertidCivilPessoa']			= self::getValue($line, 39,  2);//bloco-5		           //byte
							$obj5['_218_CodTermoCertidPessoa']			= self::getValue($line, 122, 8);//bloco-5		           //varchar(8)
							$obj5['_219_CodLivroTermoCertidPessoa']		= self::getValue($line, 110, 8);//bloco-5		           //varchar(8)
							$obj5['_220_CodFolhaTermoCertidPessoa']		= self::getValue($line, 118, 4);//bloco-5		           //varchar(4)
							$obj5['_221_DtaEmissaoCertidPessoa']		= self::getValue($line, 138, 8);//bloco-5		           //date
							$obj5['_222_SigUfCertidPessoa']				= self::getValue($line, 140, 2);//bloco-5		           //varchar(2)
							$obj5['_223_NomCartorioPessoa']				= self::getValue($line, 40, 48);//bloco-5		           //varchar(48)
							$obj5['_224_NumIndentidadePessoa']			= self::getValue($line, 208, 16);//bloco-5		           //varchar(16)
							$obj5['_225_TxtComplementoPessoa']			= self::getValue($line, 224, 5);//bloco-5                  //varchar(5)
							$obj5['_226_DtaEmissaoIdentPessoa']			= self::getValue($line, 229, 8);//bloco-5		           //date
							$obj5['_227_SigUfIdentPessoa']				= self::getValue($line, 237, 2);//bloco-5		           //varchar(2)
							$obj5['_228_SigOrgaoEmissaoPessoa']			= self::getValue($line, 239, 10);//bloco-5		           //varchar(10)
							$obj5['_229_NumCartTrabPrevSocPessoa']		= self::getValue($line, 247, 7);//bloco-5		           //especial?
							$obj5['_230_NumSerieTrabPrevSocPessoa']		= self::getValue($line, 254, 5);//bloco-5		           //especial?
							$obj5['_231_DtaEmissaoCartTrabPessoa']		= self::getValue($line, 259, 8);//bloco-5		           //date
							$obj5['_232_SigUfCartTrabPessoa']			= self::getValue($line, 267, 2);//bloco-5		           //varchar(2)
							$obj5['_233_NumCpfPessoa']					= self::getValue($line, 197, 11);//bloco-5		           //double
							$obj5['_234_NumTituloEleitorPessoa']		= self::getValue($line, 269, 13);//bloco-5		           //double
							$obj5['_235_NumZonaTitEleitorPessoa']		= self::getValue($line, 282, 4);//bloco-5		           //varchar(4)
							$obj5['_236_NumSecaoTitEleitorPessoa']		= self::getValue($line, 286, 4);//bloco-5		           //varchar(4)
						}

						if(substr($line, 37, 2) == "06")
						{
							$obj6['type']								= Constants::ROWTYPE_PERSON;
							$obj6['_214_CodDefiCegueiraPessoa']			= self::getValue($line, 41, 1);//bloco-6		           //byte
							$obj6['_214_CodDefiSurdezPessoa']			= self::getValue($line, 43, 1);//bloco-6		           //byte
							$obj6['_214_CodDefiMentalPessoa']			= self::getValue($line, 46, 1);//bloco-6		           //byte
							$obj6['_214_CodDefiFisicaPessoa']			= self::getValue($line, 45, 1);//bloco-6		           //byte
						}

						if(substr($line, 37, 2) == "07")
						{
							$obj7['type']								= Constants::ROWTYPE_PERSON;
							$obj7['_237_CodQualifEscolarPessoa']		= self::getValue($line, 40, 1);//bloco-7		           //byte
							$obj7['_237_CodGrauInstrucaoPessoa']		= self::getValue($line, 170, 2);//bloco-7	   	           //byte
							$obj7['_239_NumSerieEscolarPessoa']			= self::getValue($line, 168, 2);//bloco-7	               //byte
							$obj7['_240_NomEscolaPessoa']				= self::getValue($line, 42, 115);//bloco-7		           //varchar(115)
							$obj7['_241_CodCensoInepPessoa']			= self::getValue($line, 157, 8);//bloco-7		           //varchar(8) double
						}

						if(substr($line, 37, 2) == "08")
						{
							$obj8['type']								= Constants::ROWTYPE_PERSON;
							$obj8['_247_ValRemunerEmpregoPessoa']		= self::getValue($line, 45, 18);//bloco-8		           //double
							$obj8['_248_ValRendaAposentPessoa']			= self::getValue($line, 65, 18);//bloco-8		           //double
							$obj8['_249_ValRendaSeguroDesempPessoa']	= self::getValue($line, 71, 18);//bloco-8		           //double
							$obj8['_250_ValRendaPensaoAlimenPessoa']	= self::getValue($line, 77, 18);//bloco-8		           //double
							$obj8['_251_ValOutrasRendasPessoa']			= self::getValue($line, 88, 18);//bloco-8		           //double
						}

						if(substr($line, 37, 2) == "11")
						{
							$obj11['_270_IndBenefPetiPessoa']			= self::getValue($line, 103, 1);//Dados de família referentes formulário suplementar 1		   //varchar(1)
							$obj11['_270_IndBenefLoasBpcPessoa']		= self::getValue($line, 82, 1);//Dados de família referentes formulário suplementar 1		   //varchar(1)
							$obj11['_270_IndOutrosBeneficiosPessoa']	= self::getValue($line, 104, 1);//Dados de família referentes formulário suplementar 1		   //varchar(1)
						}
						
						//Monta um array com as informacoes de  uma familia
						if(!empty($obj8))
						{
							$data[$i] = array_merge($obj1, $obj2, $obj3, $obj4, $obj5, $obj6, $obj7, $obj8, $obj11);
							unset($obj1);
							unset($obj2);
							unset($obj3);
							unset($obj4);
							unset($obj5);
							unset($obj6);
							unset($obj7);
							unset($obj8);
							unset($obj11);
						}
							
						
						if(!empty($data[$i])){
							/*Setando conteudo extraido do arquivo.txt nos
							 * arrays 'copia' das classes abstratas do banco
							 * de dados e validando.
							 */
							if(!empty($data[$i]['_201_NomPessoa']))
							{
								//Arrays referentes a familia
								$familyResidence = array();
								$familyResidence[FRS_ID_FAMILY]					= null;
								$familyResidence[FRS_ID_RESIDENCE]				= null;
								$familyResidence[FRS_LIVE_SINCE] 				= self::dateFormat(self::validateParam($data[$i]['_103_DtaPesquisaDomic'], BasicParser::is_numeric()));

								$familyRelation = array();
								$familyRelation[FAM_ID_FAMILY]					= null;
								$familyRelation[FAM_ID_PERSON]					= null;
								$familyRelation[FAM_ID_KINSHIP]					= null;

								$familyId = array();
								$familyId[FID_ID_FAMILY]						= null;

								$representative	= array();
								$representative[REP_ID_REPRESENTATIVE]			= null;
								$representative[REP_ID_FAMILY]					= null;
								$representative[REP_ID_PERSON]					= null;

								$residence = array();
								$residence[RES_ID_RESIDENCE]					= null;
								$residence[RES_ID_ADDRESS]						= null;
								if(is_string($data[$i]['_204_NumResidenciaDomic']))
								$residence[RES_NUMBER]						    = null;
								else
								$residence[RES_NUMBER]						    = self::validateParam($data[$i]['_204_NumResidenciaDomic'], BasicParser::is_numeric());
								$residence[RES_COMPLEMENT] 						= self::validateParam($data[$i]['_205_NomComplResidenciaDomic'], BasicParser::is_string());
								$residence[RES_REFERENCE_POINT]					= null;
								$residence[RES_ID_STATUS]		 				= self::validateParam($data[$i]['_213_SitDomicilioDomic'], BasicParser::is_numeric());
								$residence[RES_ID_MORADA]		 				= self::validateParam($data[$i]['_214_TipDomicilioDomic'], BasicParser::is_numeric());
								$residence[RES_ACCOMMODATION]		 			= self::validateParam($data[$i]['_215_NumComodosDomic'], BasicParser::is_numeric());
								$residence[RES_ID_BUILDING]		 				= self::validateParam($data[$i]['_216_TipConstrucaoDomic'], BasicParser::is_numeric());
								$residence[RES_ID_SUPPLY]						= self::validateParam($data[$i]['_217_TipAbastecimentoAguaDomic'], BasicParser::is_numeric());
								$residence[RES_ID_WATER]						= self::validateParam($data[$i]['_218_TipTratamentoAguaDomic'], BasicParser::is_numeric());
								$residence[RES_ID_ILLUMINATION]					= self::validateParam($data[$i]['_219_TipIluminacaoAguaDomic'], BasicParser::is_numeric());
								$residence[RES_ID_SANITARY]						= self::validateParam($data[$i]['_220_TipEscoamentoSanitarioDomic'], BasicParser::is_numeric());
								$residence[RES_ID_TRASH]						= self::validateParam($data[$i]['_221_TipDestinoLixoDomic'], BasicParser::is_numeric());
								$residence[RES_ID_LOCALITY] 					= self::validateParam($data[$i]['_211_TipLocalDomic'], BasicParser::is_numeric());
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

								$address										= array();
								$address[ADR_ZIP_CODE]							= self::validateParam($data[$i]['_201_CepResidenciaDomic'], BasicParser::is_string());
								$address[ADR_ADDRESS]							= self::validateParam($data[$i]['_203_NomLogradouroDomic'], BasicParser::is_string());
								$address[ADR_ADDRESS_METAFONE]					= MetaPhoneClass::getMetaPhone($address[ADR_ADDRESS]);

								$family 										= array();
								$family[Constants::ARRAY_TYPE]					= Constants::ROWTYPE_FAMILY;
								$family[CLS_FAMILYRESIDENCE]					= $familyResidence;

								$family[CLS_RESIDENCE]							= $residence;
								$family[CLS_ADDRESS]							= $address;
								$family[CLS_FAMILY]								= $familyRelation;
								$family[CLS_REPRESENTATIVE]						= $representative;
								$family[CLS_FAMILY_ID]							= $familyId;

								$uniqueRegisterArray[$i] = $family;
								unset($familyResidence);
								unset($residence);
								unset($address);
								unset($familyRelation);
								unset($representative);

								unset($family);

								foreach($uniqueRegisterArray as $array)
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
										$status = FamilyResidenceBusiness::save($familyResidence);
										unset($familyResidence);
									}

									if(is_array($array[CLS_FAMILY]) && is_array($array[CLS_REPRESENTATIVE]))
									{
										$famFamily = $array[CLS_FAMILY];
										$famRepresetation = $array[CLS_REPRESENTATIVE];
									}
								}
							}



							//Arrays referentes a pessoa
							if($data[$i][Constants::ARRAY_TYPE] == Constants::ROWTYPE_PERSON)
							{
								$person 											= array();
								$person[PRS_ID_PERSON]								= null;
								$person[PRS_NAME] 									= self::validateParam($data[$i]['_201_NomPessoa'], BasicParser::is_string());
								$person[PRS_METANAME]								= MetaPhoneClass::getMetaPhone($person[PRS_NAME]);
								$person[PRS_NICKNAME]								= null;
								$person[PRS_METANICKNAME]							= MetaPhoneClass::getMetaPhone($person[PRS_NICKNAME]);
								$person[PRS_TATTOO]									= null;
								$person[PRS_DEATH_DATE]								= null;
								$person[PRS_BIRTH_DATE] 							= self::dateFormat(self::validateParam($data[$i]['_202_DtaNascPessoa'], BasicParser::is_numeric()));
								$person[PRS_SEX] 									= self::validateParam($data[$i]['_203_CodSexoPessoa'], BasicParser::is_numeric());
								if($person[PRS_SEX] == 1)
								$person[PRS_SEX] = 'm';
								if($person[PRS_SEX] == 2)
								$person[PRS_SEX] = 'f';
								$person[PRS_ID_NATIONALITY] 						= self::validateParam($data[$i]['_204_CodNacionalidadePessoa'], BasicParser::is_numeric());

								$person[PRS_NATIVE_COUNTRY] 						= self::validateParam($data[$i]['_205_CodPaisOrigemPessoa'], BasicParser::is_numeric());
								$person[PRS_ARRIVAL_DATE]							= self::validateParam($data[$i]['_206_CodDtaChegadaPaisPessoa'], BasicParser::is_numeric());
								$person[PRS_ID_MARITAL_STATUS]						= self::validateParam($data[$i]['_212_CodEstadoCivilPessoa'], BasicParser::is_numeric());
								$person[PRS_ID_RACE]								= self::validateParam($data[$i]['_215_CodRacaCorPessoa'], BasicParser::is_numeric());
								if($person[PRS_ID_RACE] == 0)
								$person[PRS_ID_RACE] = null;
								if($person[PRS_ID_MARITAL_STATUS] == 0)
								$person[PRS_ID_MARITAL_STATUS] = null;
								if($person[PRS_ID_NATIONALITY] == 0)
								$person[PRS_ID_NATIONALITY] = null;


								$idDeficiency										= array();
								$idDeficiency[1]									= self::validateParam($data[$i]['_214_CodDefiCegueiraPessoa'], BasicParser::is_numeric());
								$idDeficiency[2]									= self::validateParam($data[$i]['_214_CodDefiMudezPessoa'], BasicParser::is_numeric());
								$idDeficiency[3]									= self::validateParam($data[$i]['_214_CodDefiSurdezPessoa'], BasicParser::is_numeric());
								$idDeficiency[4]									= self::validateParam($data[$i]['_214_CodDefiMentalPessoa'], BasicParser::is_numeric());
								$idDeficiency[5]									= self::validateParam($data[$i]['_214_CodDefiFisicaPessoa'], BasicParser::is_numeric());
								$idDeficiency[6]									= self::validateParam($data[$i]['_214_CodOutrasDefiPessoa'], BasicParser::is_numeric());
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

								$document											= array();
								$document[DOC_CPF]									= self::validateParam($data[$i]['_233_NumCpfPessoa'], BasicParser::is_string());
								$document[DOC_NIS]									= self::validateParam($data[$i]['_216_NumNisPessoa'], BasicParser::is_numeric());
								$document[DOC_SUS_CARD] 							= null;
								$document[DOC_RA] 									= null;
								$document[DOC_RG_NUMBER]							= self::validateParam($data[$i]['_224_NumIndentidadePessoa'], BasicParser::is_numeric());
								$document[DOC_RG_COMPLEMENT]						= self::validateParam($data[$i]['_225_TxtComplementoPessoa'], BasicParser::is_string());
								$document[DOC_RG_EMISSION_DATE]						= self::dateFormat(self::validateParam($data[$i]['_226_DtaEmissaoIdentPessoa'], BasicParser::is_numeric()));
								$document[DOC_RG_SENDER]							= self::validateParam($data[$i]['_228_SigOrgaoEmissaoPessoa'], BasicParser::is_string());
								$document[DOC_ID_RG_UF]								= self::validateParam($data[$i]['_227_SigUfIdentPessoa'], BasicParser::is_string());
								if($document[DOC_RG_NUMBER] == 0)
								{
									$document[DOC_RG_COMPLEMENT] = null;
									$document[DOC_RG_EMISSION_DATE] = null;
									$document[DOC_RG_SENDER] = null;
									$document[DOC_ID_RG_UF]	= null;
								}
								$document[DOC_TITLE_NUMBER]							= self::validateParam($data[$i]['_234_NumTituloEleitorPessoa'], BasicParser::is_numeric());
								$document[DOC_TITLE_ZONE]							= self::validateParam($data[$i]['_235_NumZonaTitEleitorPessoa'], BasicParser::is_numeric());
								$document[DOC_TITLE_SECTION]						= self::validateParam($data[$i]['_236_NumSecaoTitEleitorPessoa'], BasicParser::is_numeric());
								if($document[DOC_TITLE_NUMBER] == 0)
								{
									$document[DOC_TITLE_NUMBER] = null;
									$document[DOC_TITLE_SECTION] = null;
									$document[DOC_TITLE_ZONE] = null;
								}

								$ctps												= array();
								$ctps[CTS_ID_UF]									= self::validateParam($data[$i]['_232_SigUfCartTrabPessoa'], BasicParser::is_string());
								$ctps[CTS_NUMBER]									= self::validateParam($data[$i]['_229_NumCartTrabPrevSocPessoa'], BasicParser::is_numeric());
								$ctps[CTS_SERIES]									= self::validateParam($data[$i]['_230_NumSerieTrabPrevSocPessoa'], BasicParser::is_numeric());
								$ctps[CTS_EMISSION]									= self::dateFormat(self::validateParam($data[$i]['_231_DtaEmissaoCartTrabPessoa'], BasicParser::is_numeric()));

								$civilCertificate									= array();
								$civilCertificate[CCF_ID_UF]						= self::validateParam($data[$i]['_222_SigUfCertidPessoa'], BasicParser::is_string());
								$civilCertificate[CCF_CERTIFICATE_TYPE]				= self::validateParam($data[$i]['_217_CodCertidCivilPessoa'], BasicParser::is_numeric());
								$civilCertificate[CCF_TERM]							= self::validateParam($data[$i]['_218_CodTermoCertidPessoa'], BasicParser::is_numeric());
								$civilCertificate[CCF_BOOK]							= self::validateParam($data[$i]['_219_CodLivroTermoCertidPessoa'], BasicParser::is_numeric());
								$civilCertificate[CCF_LEAF]							= self::validateParam($data[$i]['_220_CodFolhaTermoCertidPessoa'], BasicParser::is_numeric());
								$civilCertificate[CCF_EMISSION_DATE]				= self::dateFormat(self::validateParam($data[$i]['_221_DtaEmissaoCertidPessoa'], BasicParser::is_numeric()));
								$civilCertificate[CCF_REGISTRY_OFFICE_NAME]			= self::validateParam($data[$i]['_223_NomCartorioPessoa'], BasicParser::is_string());

								$valueIncome										= array();
								$valueIncome[1]										= self::validateParam($data[$i]['_247_ValRemunerEmpregoPessoa'], BasicParser::is_numeric());
								$valueIncome[2]										= self::validateParam($data[$i]['_248_ValRendaAposentPessoa'], BasicParser::is_numeric());
								$valueIncome[3]										= self::validateParam($data[$i]['_249_ValRendaSeguroDesempPessoa'], BasicParser::is_numeric());
								$valueIncome[4]										= self::validateParam($data[$i]['_250_ValRendaPensaoAlimenPessoa'], BasicParser::is_numeric());
								$valueIncome[5]										= self::validateParam($data[$i]['_251_ValOutrasRendasPessoa'], BasicParser::is_numeric());
								$haveInc = false;
								foreach($valueIncome as $inc):
								if($inc != 0) $haveInc = true;
								endforeach;
								if($haveInc == true){
									$income											= array();
									$income[ICM_VALUE]								= $valueIncome;
									$income[ICM_REGISTER_DATE]						= date("Y-m-d");
								}
								else
								{
									$income											= null;
								}

								$employment											= array();
								$employment[EMP_ID_EMPLOYMENT_STATUS]				= self::validateParam($data[$i]['_242_SitMercadoTrabPessoa'], BasicParser::is_numeric());
								$employment[EMP_COMPANY_NAME]						= self::validateParam($data[$i]['_243_NomEmpresaTrabPessoa'], BasicParser::is_string());
								if($employment[EMP_COMPANY_NAME] != null){
									$employment[EMP_ID_EMPLOYMENT]					= null;
									$employment[EMP_ID_ADDRESS]						= null;
									$employment[EMP_START_DATE]						= self::dateFormat(self::validateParam($data[$i]['_245_DtaAdmisEmpresaPessoa'], BasicParser::is_numeric()));
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

								$valueExpense										= array();
								$valueExpense[1]									= self::validateParam($data[$i]['_253_ValDespMesaisPessoa'], BasicParser::is_numeric());
								$valueExpense[2]									= self::validateParam($data[$i]['_254_ValDespPrestHabPessoa'], BasicParser::is_numeric());
								$valueExpense[3]									= self::validateParam($data[$i]['_255_ValDespAlimentPessoa'], BasicParser::is_numeric());
								$valueExpense[4]									= self::validateParam($data[$i]['_256_ValDespAguaPessoa'], BasicParser::is_numeric());
								$valueExpense[5]									= self::validateParam($data[$i]['_257_ValDespLuzPessoa'], BasicParser::is_numeric());
								$valueExpense[6]									= self::validateParam($data[$i]['_258_ValDespTransporPessoa'], BasicParser::is_numeric());
								$valueExpense[7]									= self::validateParam($data[$i]['_259_ValDespMedicamentosPessoa'], BasicParser::is_numeric());
								$valueExpense[8]									= self::validateParam($data[$i]['_260_ValDespGazPessoa'], BasicParser::is_numeric());
								$valueExpense[9]									= self::validateParam($data[$i]['_261_ValOutrasDespPessoa'], BasicParser::is_numeric());
								$haveExp = false;
								foreach($valueExpense as $exp):
								if($exp != 0) $haveExp = true;
								endforeach;
								if($haveExp == true){
									$expense										= array();
									$expense[EXP_ID_EXPENSE_TYPE]					= null;
									$expense[EXP_EXPENSE_VALUE]						= $valueExpense;
									$expense[EXP_ID_FAMILY]							= null;
								}
								else
								{
									$expense 										= null;
								}

								$consanguine										= array();
								$consanguine[CSG_ID_PERSON_FROM]					= self::validateParam($data[$i]['_263_NumOrdemRespPessoa'], BasicParser::is_numeric());
								$consanguine[CSG_ID_CONSANGUINE_TYPE]				= self::validateParam($data[$i]['_264_CodParentRelMaePessoa'], BasicParser::is_numeric());
								$consanguine[CSG_ID_PERSON_TO]						= self::validateParam($data[$i]['_102_NumOrdemPessoa'], BasicParser::is_numeric());

								$idSocialProgram									= array();
								$idSocialProgram[1]									= self::validateParam($data[$i]['_270_IndBenefPetiPessoa'], BasicParser::is_numeric());
								$idSocialProgram[2]									= self::validateParam($data[$i]['_270_IndBenefLoasBpcPessoa'], BasicParser::is_numeric());
								$idSocialProgram[3]									= null;
								$idSocialProgram[4]									= null;
								$idSocialProgram[5]									= null;
								$idSocialProgram[6]									= null;
								$idSocialProgram[7]									= null;
								$idSocialProgram[8]									= self::validateParam($data[$i]['_270_IndOutrosBeneficiosPessoa'], BasicParser::is_numeric());
								$haveSop = false;
								foreach($idSocialProgram as $sop):
								if($sop != 0) $haveSop = true;
								endforeach;
								if($haveSop == true){
									$socialProgram									= array();
									$registerDate									= array();
									$registerDate[1]								= self::dateFormat(self::validateParam($data[$i]['_270_DtaIncPetiPessoa'], BasicParser::is_numeric()));
									$registerDate[2]								= null;
									$registerDate[3]								= self::dateFormat(self::validateParam($data[$i]['_270_DtaIncAgjPessoa'], BasicParser::is_numeric()));
									$registerDate[4]								= null;
									$registerDate[5]								= null;
									$registerDate[6]								= self::dateFormat(self::validateParam($data[$i]['_270_DtaIncProgerPessoa'], BasicParser::is_numeric()));
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

								$levelInstruction									= array();
								$levelInstruction[LIT_ID_PERSON]					= null;
								$levelInstruction[LIT_ID_DEGREE]					= self::validateParam($data[$i]['_238_CodGrauInstrucaoPessoa'], BasicParser::is_numeric());
								$levelInstruction[LIT_LAST_YEAR_STUDIED]			= null;


								$school 											= array();
								$school[SCH_ID_SCHOOL]								= null;
								$school[SCH_NAME]									= self::validateParam($data[$i]['_240_NomEscolaPessoa'], BasicParser::is_string());
								if($school[SCH_NAME] == null){
									$school[SCH_INEP]								= null;
									Logger::loggerImport('Não há nome para escola, foi atribuido '.$school[SCH_NAME].' . [Line]: '.implode(' | ',$data[$i]));
								}
								else
								{
									$school[SCH_INEP]								= self::validateParam($data[$i]['_241_CodCensoInepPessoa'], BasicParser::is_numeric());
								}
								$school[SCH_ID_SCHOOL_TYPE]	                        = self::validateParam($data[$i]['_237_CodQualifEscolarPessoa'], BasicParser::is_numeric());
								if($school[SCH_ID_SCHOOL_TYPE] == 0)
								$school[SCH_ID_SCHOOL_TYPE] = null;

								if($school[SCH_NAME] == null || $school[SCH_INEP] == null)
								{
									$registration									= null;
								}
								else
								{
									$registration								    = array();
									$registration[REG_ID_LEVEL_INSTRUCTION]			= null;
									$idSchoolYear									= self::validateParam($data[$i]['_239_NumSerieEscolarPessoa'], BasicParser::is_numeric());
									if($idSchoolYear == null){
										$idSchoolYear = null;
										Logger::loggerImport('Pessoa não possui série, valor '. $idSchoolYear .' foi atribuido. [Line]: '.implode(' | ',$data[$i]));
									}
									$registration[REG_ID_SCHOOL_YEAR]				= $idSchoolYear;
									$registration[REG_ID_PERIOD]					= null;
									$registration[REG_ID_SCHOOL]					= null;
								}

								$personData 										= array();
								$personData[Constants::ARRAY_TYPE]					= Constants::ROWTYPE_PERSON;
								$personData[CLS_PERSON]								= $person;
								$personData[CLS_DEFICIENCY]							= $deficiency;
								$personData[CLS_DOCUMENT]							= $document;
								$personData[CLS_CTPS]								= $ctps;
								$personData[CLS_CIVILCERTIFICATE]					= $civilCertificate;
								$personData[CLS_INCOME]								= $income;
								$personData[CLS_EMPLOYMENT]							= $employment;
								$personData[CLS_EXPENSE]							= $expense;
								$personData[CLS_CONSANGUINE]						= $consanguine;
								$personData[CLS_SOCIALPROGRAM]						= $socialProgram;
								$personData[CLS_LEVELINSTRUCTION]					= $levelInstruction;
								$personData[CLS_SCHOOL]								= $school;
								$personData[CLS_REGISTRATION]						= $registration;

								$uniqueRegisterArray[$i] = $personData;

								unset($person);
								unset($deficiency);
								unset($document);
								unset($ctps);
								unset($civilCertificate);
								unset($income);
								unset($expense);
								unset($consanguine);
								unset($socialProgram);
								unset($school);
								unset($registration);
								unset($levelInstruction);
								unset($employment);

								unset($personData);

								foreach($uniqueRegisterArray as $array)
								{
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


										if(empty($idPerson)){
											if(is_array($famFamily))
											{
												$famFamily[FAM_ID_PERSON] = $idPerson;
												$famFamily[FAM_ID_FAMILY] = $idFamily;

												if(is_array($array[CLS_CONSANGUINE]))
												{
													$csg = $array[CLS_CONSANGUINE];
													$famFamily[FAM_ID_KINSHIP] = $csg[CSG_ID_CONSANGUINE_TYPE];
													if($famFamily[FAM_ID_KINSHIP] == 0) $famFamily[FAM_ID_KINSHIP] = 20;
													$status = FamilyBusiness::save($famFamily);
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
									}

									Logger::loggerImport('Dados persistidos no banco.');
									$i = $i + 1;
									unset($uniqueRegisterArray);
								}
							}
						}
						unset($data[$i]);
					}

					Logger::loggerImport('A importação foi concluida com sucesso.');
					echo "<center><table border=0 bgcolor=yellow>
						    <tr>
							<td colspan=2><b><font color =red>A importação foi concluida com sucesso</font></b>
						  	</td>
							</tr>	
						  </table></center>";
					//unlink($filename);
				}
			}
		}
		//fclose($handle);
		fclose($lines);
		//unlink($handle);
		//unlink($fileName);
		return 0;
	}	

	function getValue(&$line, $start, $length)
	{
		return substr($line, $start, $length);
	}

	/**
	 * Faz leitura do arquivo e popula um array para persistencia na tabela edu_school
	 */
	public static function parseSchool($folder, $fileNames, $numberOfLines=0, &$fileNameError=NULL, &$flag=false)
	{
		if(!empty($fileNames) && $fileNames !==NULL)
		{
			Zend_Loader::loadClass('SchoolBusiness');
			// Contador de linha inseridas no DB
			$lines = 0;
			// Contador de erros de parse
			$errors = 0;
			// Contador de arquivos analisados
			$parsed = 0;

			foreach($fileNames as $currentFile)
			{
				if(!is_dir($currentFile) && $currentFile !== NULL)
				{
					if($flag !== FALSE)Logger::loggerImportSchool('Abrindo o arquivo '.$currentFile);
						
					Zend_Loader::loadClass(CLS_SCHOOL);
					if($handle !== NULL)
					{
						$i = 0;
						while(!feof($handle) && $i <= $numberOfLines)
						{
							if($numberOfLines != 0) $i++;
							$line = fgets($handle);
								
							$row = self::parseLine($line, ';');
								
							if(!empty($row) && count($row)!=1 )
							{
								// Valida a quantidade de colunas do arquivo
								if(count($row) != 4)
								{
									$fileNameError[] = $currentFile;
									fclose($handle);
									unlink($folder.'/'.$currentFile);
									break;
								}

								$school = array();
									
								try
								{
									$ibge = self::validateValue(trim($row[0]), Parser::is_numeric());
									$school[SCH_INEP] = self::validateValue(trim($row[1]), Parser::is_numeric());
									$school[SCH_NAME] = self::validateValue(trim($row[2]), Parser::is_string());
									$school[SCH_ID_SCHOOL_TYPE] = self::validateValue(trim($row[3]), Parser::is_numeric());
										
									$parsed++;
										
									if($flag !== FALSE)
									{

										$resSchool = SchoolBusiness::findByInepCodeAndName($school[SCH_INEP],$school[SCH_NAME]);

										if(count($resSchool) > 0)
										foreach($resSchool as $result)
										$school[SCH_ID_SCHOOL] = $result->{SCH_ID_SCHOOL};

										SchoolBusiness::save($school);
										$lines++;
										unset($school);
									}
								}
								catch(Exception $e)
								{
									$fileNameError = $currentFile;
									Logger::loggerImportSchool('Falha ao analisar o arquivo de Escolas: '. $e .' [Line]: '.implode(' | ',$row));
									if($flag===FALSE)
									return FALSE;
									else
									{
										$errors++;
										continue;
									}
								}
							}
							unset($row);
						}
					}
				}
			}
			if($parsed == 0) return FALSE;
			if($flag !== FALSE) Logger::loggerImportSchool('Importação de Escolas: '.$lines.' registros.');
			if($fileNameError !== NULL)
			{
				$fileNameError = implode('<br/>',$fileNameError);
				return FALSE;
			}
			else return TRUE;
		}
		return FALSE;
	}

	/**
	 * Faz a leitura do arquivo e popula um Array para persistencia na tabela UF
	 * @param String $folder - path para o diretório onde estão os arquivos
	 * @param Array $fileNames - Array contendo o nome de todos os arquivos existentes em $folder
	 * @param Integer $numberOfLines - numero de linhas que se deseja analisar
	 * @param String $fileNameError - Armazena o nome do arquivo em caso de erro.
	 * @param Boolean $db - Flag que indica que os dados processados devem ou não ser persistidos
	 * @return Array Array contendo informação de endereço ou NULL caso não seja realizado o parse
	 */
	public static function parseUf($folder, $fileNames, $numberOfLines=0, &$fileNameError=NULL, &$db=NULL)
	{
		if(!empty($fileNames) && $fileNames !==NULL)
		{
			Zend_Loader::loadClass('UFBusiness');
			// Array que garante valor único para UF
			$unique = array();
			// Contador de linha inseridas no DB
			$lines = 0;
			// Contador de erros de parse
			$errors = 0;
			// Contador de arquivos analisados
			$parsed = 0;

			foreach($fileNames as $currentFile)
			{
				if(!is_dir($currentFile) && $currentFile !== NULL)
				{
					$handle = @fopen($folder.'/'.$currentFile, 'r');
					if($db != NULL)Logger::loggerImportAddress('Abrindo o arquivo '.$currentFile);
						
					Zend_Loader::loadClass(CLS_UF);
					if($handle !== NULL)
					{
						$i = 0;
						while(!feof($handle) && $i <= $numberOfLines)
						{
							if($numberOfLines != 0) $i++;
							$line = fgets($handle);
							$row = self::parseLine($line, '@');

							if(!empty($row) && count($row)!=1 )
							{
								// Valida a quantidade de colunas do arquivo
								if(count($row) < 3)
								{
									$fileNameError[] = $currentFile;
									fclose($handle);
									unlink($folder.'/'.$currentFile);
									Logger::loggerImportAddress('Falha ao ler o arquivo de UF. O arquivo não possui o número de colunas necessário. '.implode(' | ',$row));
									break;
								}

								$uf = array();
									
								try
								{
									$key = self::validateValue(trim($row[0]), Parser::is_string());
									// Valida o tipo dos demais campos para garantir que é o arquivo desejado.
									self::validateValue(trim($row[1]), Parser::is_numeric());
									self::validateValue(trim($row[2]), Parser::is_numeric());

									$parsed++;
									if(!array_key_exists($key,$unique))
									{
										//$nbhd[UF_ID_UF] 		= self::validateValue('');
										$uf[UF_ABBREVIATION] 	= $key;

										if($db !== NULL)
										{
											UFBusiness::insert($uf);
											$lines++;
											$ufArray[] = $uf;
											unset($uf);
										}
									}
									// Adiciona a UF no array para garantir a unicidade
									$unique[$key] = $key;
								}
								catch(Exception $e)
								{
									$fileNameError = $currentFile;
									Logger::loggerImportAddress('Falha ao analisar o arquivo de UF: '. $e .' [Line]: '.implode(' | ',$row));
									if($db===NULL)
									return FALSE;
									else
									{
										$errors++;
										continue;
									}
								}
							}
							unset($row);
						}
					}
				}
			}
			if($parsed == 0) return FALSE;
			if($db !== NULL) Logger::loggerImportAddress('Importação de UF: '.$lines.' registros.');
			unset($unique);
			if($fileNameError !== NULL)
			{
				$fileNameError = implode('<br/>',$fileNameError);
				return FALSE;
			}
			else return TRUE;
		}
		return FALSE;
	}

	/**
	 * Faz a leitura do arquivo e popula um Array para persistencia na tabela Address
	 * @param String $folder - path para o diretório onde estão os arquivos
	 * @param Array $fileNames - Array contendo o nome de todos os arquivos existentes em $folder
	 * @param Integer $numberOfLines - numero de linhas que se deseja analisar
	 * @param String $fileNameError - Armazena o nome do arquivo em caso de erro.
	 * @param Boolean $db - Flag que indica que os dados processados devem ou não ser persistidos
	 * @return Array Array contendo informação de endereço ou NULL caso não seja realizado o parse
	 */
	public static function parseAddress($folder, $fileNames, $numberOfLines=0, &$fileNameError=NULL, &$db=NULL)
	{
		if(!empty($fileNames) && $fileNames !==NULL)
		{
			Zend_Loader::loadClass('AddressBusiness');
			Zend_Loader::loadClass('AddressTypeBusiness');
			$addressType = NULL;
			// Contador de linha inseridas no DB
			$lines = 0;
			// Contador de erros de parse
			$errors = 0;
			// Contador de arquivos analisados
			$parsed = 0;

			foreach($fileNames as $currentFile)
			{
				if(!is_dir($currentFile) && $currentFile !== NULL)
				{
					$handle = @fopen($folder.'/'.$currentFile, 'r');
					if($db != NULL) Logger::loggerImportAddress('Abrindo o arquivo '.$currentFile);

					// Carregado para utilizar as contantes
					Zend_Loader::loadClass(CLS_ADDRESS);
					if($handle !== FALSE)
					{
						$i = 0;
						while(!feof($handle) && $i <= $numberOfLines)
						{
							if($numberOfLines != 0) $i++;
							$row = self::parseLine(fgets($handle), '@');
							if(!empty($row) && count($row)!=1 )
							{
								// Valida a quantidade de colunas do arquivo
								if(count($row) < 11)
								{
									$fileNameError[] = $currentFile;
									fclose($handle);
									unlink($folder.'/'.$currentFile);
									Logger::loggerImportAddress('Falha ao ler o arquivo de Logradouro. O arquivo não possui o número de colunas necessário. '.implode(' | ',$row));
									break;
								}
								$address = array();

								try
								{
									$address[ADR_ID_ADDRESS] 			= self::validateValue(trim($row[0]), Parser::is_numeric());
									$address[ADR_ID_NEIGHBORHOOD] 		= self::validateValue(trim($row[3]), Parser::is_numeric());
									$address[ADR_ID_ADDRESS_TYPE] 		= self::validateValue(trim($row[8]), Parser::is_string());
									$address[ADR_ZIP_CODE] 				= self::validateValue(trim($row[7]), Parser::is_numeric());
									$address[ADR_ADDRESS] 				= self::validateValue(trim($row[5]), Parser::is_string());
									$address[ADR_ADDRESS_METAFONE]		= MetaPhoneClass::getMetaPhone($address[ADR_ADDRESS]);
										
									// Valida o tipo dos demais campos para garantir que é o arquivo desejado.
									self::validateValue(trim($row[1]), Parser::is_string());
									self::validateValue(trim($row[2]), Parser::is_numeric());
									self::validateValue(trim($row[4]), Parser::is_numeric(), TRUE);
									self::validateValue(trim($row[6]), Parser::is_string(), TRUE);
									// Usado na tabela de Address_type
									$type = self::validateValue(trim($row[8]), Parser::is_string());
										
									self::validateValue(trim($row[9]), Parser::is_string(), TRUE);
									self::validateValue(trim($row[10]), Parser::is_string(), TRUE);

									$parsed ++;
									if($db !== NULL)
									{
										$row = AddressBusiness::load($address[ADR_ID_ADDRESS]);
										if(count($row) == 0)
										{
											if(!$addressType[$type])
											{
												// Moonta array de dados a serem persistidos na tabela de Tipo_Logradouro
												$data[ADT_DESCRIPTION] = $type;
												$id = AddressTypeBusiness::insert($data);
												$addressType[$type] = $id;
											}

											$address[ADR_ID_ADDRESS_TYPE] = $addressType[$type];
											AddressBusiness::insert($address);
											$lines++;
											unset($type);
										}
									}
								}
								catch(Exception $e)
								{
									$fileNameError = $currentFile;
									Logger::loggerImportAddress('Falha ao analisar o arquivo de Logradouro: '. $e .' [Line]: '.implode(' | ',$row));
									if($db === NULL)
									return FALSE;
									else
									{
										$errors++;
										continue;
									}
								}

							}
							unset($row);
						}
					}
				}
			}
			if($parsed == 0) return FALSE;
			if($db !== NULL) Logger::loggerImportAddress('Importação de Logradouro: '.$lines.' registros.');
			unset($addressType);
			if($fileNameError !== NULL)
			{
				$fileNameError = implode('<br/>',$fileNameError);
				return FALSE;
			}
			else return TRUE;
		}
		return FALSE;
	}

	/**
	 * Faz a leitura do arquivo e popula um Array para persistencia na tabela AddressNickname
	 * @param String $folder - path para o diretório onde estão os arquivos
	 * @param Array $fileNames - Array contendo o nome de todos os arquivos existentes em $folder
	 * @param Integer $numberOfLines - numero de linhas que se deseja analisar
	 * @param String $fileNameError - Armazena o nome do arquivo em caso de erro.
	 * @param Boolean $db - Flag que indica que os dados processados devem ou não ser persistidos
	 * @return Array Array contendo informação de endereço ou NULL caso não seja realizado o parse
	 */
	public static function parseAddressNickname($folder, $fileNames, $numberOfLines=0, &$fileNameError=NULL, &$db=NULL)
	{
		if(!empty($fileNames) && $fileNames !==NULL)
		{
			Zend_Loader::loadClass('AddressNicknameBusiness');
			// Contador de linha inseridas no DB
			$lines = 0;
			// Contador de erros de parse
			$errors = 0;
			// Contador de arquivos analisados
			$parsed = 0;

			foreach($fileNames as $currentFile)
			{
				if(!is_dir($currentFile) && $currentFile !== NULL)
				{
					$handle = @fopen($folder.'/'.$currentFile, 'r');
					if($db != NULL)Logger::loggerImportAddress('Abrindo o arquivo '.$currentFile);
					// Carregado para utilizar as contantes
					Zend_Loader::loadClass(CLS_ADDRESSNICKNAME);
					if($handle !== NULL)
					{
						$i = 0;
						while(!feof($handle) && $i <= $numberOfLines)
						{
							if($numberOfLines != 0) $i++;
							$line = fgets($handle);
							$row = self::parseLine($line, '@');
							$addressArray = NULL;
								
							if(!empty($row) && count($row)!=1 )
							{
								// Valida a quantidade de colunas do arquivo
								if(count($row) < 4)
								{
									$fileNameError[] = $currentFile;
									fclose($handle);
									unlink($folder.'/'.$currentFile);
									Logger::loggerImportAddress('Falha ao ler o arquivo de Logradouro_Apelido. O arquivo não possui o número de colunas necessário. '.implode(' | ',$row));
									break;
								}

								$address = array();
									
								try
								{
									$address[ADN_ID_ADDRESS] 		= self::validateValue(trim($row[0]), Parser::is_numeric());
									$address[ADN_ID_NICKNAME] 		= self::validateValue(trim($row[1]), Parser::is_numeric());
									$address[ADN_NICKNAME] 			= self::validateValue(trim($row[3]), Parser::is_string());
									$address[ADN_NICKNAME_METAFONE] = MetaPhoneClass::getMetaPhone($address[ADN_NICKNAME]);
										
									// Valida o tipo dos demais campos para garantir que é o arquivo desejado.
									self::validateValue(trim($row[2]), Parser::is_string());
										
									$parsed++;
									if($db !== NULL)
									{
										AddressNicknameBusiness::insert($address);
										$lines++;
										unset($address);
									}
								}
								catch(Exception $e)
								{
									$fileNameError = $currentFile;
									Logger::loggerImportAddress('Falha ao analisar o arquivo de Logradouro_Apelido: '. $e .' [Line]: '.implode(' | ',$row));
									if($db===NULL)
									return FALSE;
									else
									{
										$errors++;
										continue;
									}
								}
							}
							unset($row);
						}
					}
				}
			}
			if($parsed == 0) return FALSE;
			if($db !== NULL) Logger::loggerImportAddress('Importação de Logradouro_Apelido: '.$lines.' registros.');
			if($fileNameError !== NULL)
			{
				$fileNameError = implode('<br/>',$fileNameError);
				return FALSE;
			}
			else return TRUE;
		}
		return FALSE;
	}

	/**
	 * Faz a leitura do arquivo e popula um Array para persistencia na tabela City
	 * @param String $folder - path para o diretório onde estão os arquivos
	 * @param Array $fileNames - Array contendo o nome de todos os arquivos existentes em $folder
	 * @param Integer $numberOfLines - numero de linhas que se deseja analisar
	 * @param String $fileNameError - Armazena o nome do arquivo em caso de erro.
	 * @param Boolean $db - Flag que indica que os dados processados devem ou não ser persistidos
	 * @return Array Array contendo informação de endereço ou NULL caso não seja realizado o parse
	 */
	public static function parseCity($folder, $fileNames, $numberOfLines=0, &$fileNameError=NULL, &$db=NULL)
	{
		if(!empty($fileNames) && $fileNames !==NULL)
		{
			Zend_Loader::loadClass('UFBusiness');
			Zend_Loader::loadClass('CityBusiness');
			// Contador de linha inseridas no DB
			$lines = 0;
			// Contador de erros de parse
			$errors = 0;
			// Contador de arquivos analisados
			$parsed = 0;

			$ufMap = NULL;
			if($db !==NULL)
			$ufs = UFBusiness::loadAll();
			foreach($ufs as $uf)
			{
				$ufMap[$uf->{UF_ABBREVIATION}] = $uf->{UF_ID_UF};
			}
			unset($ufs);
			foreach($fileNames as $currentFile)
			{
				if(!is_dir($currentFile) && $currentFile !== NULL)
				{
					$handle = @fopen($folder.'/'.$currentFile, 'r');
					if($db != NULL)	Logger::loggerImportAddress('Abrindo o arquivo '.$currentFile);
					Zend_Loader::loadClass(CLS_CITY);
					if($handle !== NULL)
					{
						$i = 0;
						while(!feof($handle) && $i <= $numberOfLines)
						{
							if($numberOfLines != 0) $i++;
							$line = fgets($handle);
							$row = self::parseLine($line, '@');

							if(!empty($row) && count($row)!=1 )
							{
								// Valida a quantidade de colunas do arquivo
								if(count($row) < 8)
								{
									$fileNameError[] = $currentFile;
									fclose($handle);
									unlink($folder.'/'.$currentFile);
									Logger::loggerImportAddress('Falha ao ler o arquivo de Localidade. O arquivo não possui o número de colunas necessário. '.implode(' | ',$row));
									break;
								}

								$city = array();

								try
								{
									$city[CTY_ID_CITY] 			= self::validateValue(trim($row[0]), Parser::is_numeric());
									$city[CTY_ID_UF] 			= self::validateValue(trim($row[1]), Parser::is_string());
									$city[CTY_CITY] 			= self::validateValue(trim($row[2]), Parser::is_string());
										
									// Valida o tipo dos demais campos para garantir que é o arquivo desejado.
									self::validateValue(trim($row[3]), Parser::is_numeric(), TRUE);
									self::validateValue(trim($row[4]), Parser::is_numeric());
									self::validateValue(trim($row[5]), Parser::is_string());
									self::validateValue(trim($row[6]), Parser::is_numeric(), TRUE);
									self::validateValue(trim($row[7]), Parser::is_string(), TRUE);
									self::validateValue(trim($row[8]), Parser::is_numeric(), TRUE);
										
									$parsed++;
									if($db !== NULL)
									{
										if($ufMap !== NULL && count($ufMap) > 0)
										{
											$city[CTY_ID_UF] = $ufMap[ $city[CTY_ID_UF] ];
											CityBusiness::insert($city);
											$lines++;
											unset($city);
										}
									}
								}
								catch(Exception $e)
								{
									$fileNameError = $currentFile;
									Logger::loggerImportAddress('Falha ao analisar o arquivo de Localidade: '. $e .' [Line]: '.implode(' | ',$row));
									unset($city);
									if($db===NULL)
									{
										unset($ufMap);
										return FALSE;
									}
									else
									{
										$errors++;
										continue;
									}
								}
							}
							unset($row);
						}
					}
				}
			}
			if($parsed == 0) return FALSE;
			if($db !== NULL) Logger::loggerImportAddress('Importação de Localidade: '.$lines.' registros.');
			if($fileNameError !== NULL)
			{
				$fileNameError = implode('<br/>',$fileNameError);
				return FALSE;
			}
			else return TRUE;
		}
		return FALSE;
	}

	/**
	 * Faz a leitura do arquivo e popula um Array para persistencia na tabela Neighborhood
	 * @param String $folder - path para o diretório onde estão os arquivos
	 * @param Array $fileNames - Array contendo o nome de todos os arquivos existentes em $folder
	 * @param Integer $numberOfLines - numero de linhas que se deseja analisar
	 * @param String $fileNameError - Armazena o nome do arquivo em caso de erro.
	 * @param Boolean $db - Flag que indica que os dados processados devem ou não ser persistidos
	 * @return Array Array contendo informação de endereço ou NULL caso não seja realizado o parse
	 */
	public static function parseNeighborhood($folder, $fileNames, $numberOfLines=0, &$fileNameError=NULL, &$db=NULL)
	{
		if(!empty($fileNames) && $fileNames !==NULL)
		{
			Zend_Loader::loadClass('NeighborhoodBusiness');
			// Contador de linha inseridas no DB
			$lines = 0;
			// Contador de erros de parse
			$errors = 0;
			// Contador de arquivos analisados
			$parsed = 0;

			foreach($fileNames as $currentFile)
			{
				if(!is_dir($currentFile) && $currentFile !== NULL)
				{
					$handle = @fopen($folder.'/'.$currentFile, 'r');
					if($db != NULL)Logger::loggerImportAddress('Abrindo o arquivo '.$currentFile);
					Zend_Loader::loadClass(CLS_NEIGHBORHOOD);
					if($handle !== NULL)
					{
						$i = 0;
						while(!feof($handle) && $i <= $numberOfLines)
						{
							if($numberOfLines != 0) $i++;
							$line = fgets($handle);
							$row = self::parseLine($line, '@');

							if(!empty($row) && count($row)!=1 )
							{
								// Valida a quantidade de colunas do arquivo
								if(count($row) < 5)
								{
									$fileNameError[] = $currentFile;
									fclose($handle);
									unlink($folder.'/'.$currentFile);
									Logger::loggerImportAddress('Falha ao ler o arquivo de Bairro. O arquivo não possui o número de colunas necessário. '.implode(' | ',$row));
									break;
								}

								$nbhd = array();
									
								try
								{
									$nbhd[NHD_ID_NEIGHBORHOOD] 	= self::validateValue(trim($row[0]), Parser::is_numeric());
									$nbhd[NHD_ID_CITY] 			= self::validateValue(trim($row[2]), Parser::is_numeric());
									$nbhd[NHD_NEIGHBORHOOD] 	= self::validateValue(trim($row[3]), Parser::is_string());
										
									// Valida o tipo dos demais campos para garantir que é o arquivo desejado.
									self::validateValue(trim($row[1]), Parser::is_string());
									self::validateValue(trim($row[4]), Parser::is_string(), TRUE);
										
									$parsed++;
									if($db !== NULL)
									{
										NeighborhoodBusiness::insert($nbhd);
										$lines++;
										unset($nbhd);
									}
								}
								catch(Exception $e)
								{
									$fileNameError = $currentFile;
									Logger::loggerImportAddress('Falha ao analisar o arquivo de Bairro: '. $e .' [Line]: '.implode(' | ',$row));
									if($db===NULL)
									return FALSE;
									else
									{
										$errors++;
										continue;
									}
								}
							}
							unset($row);
						}
					}
				}
			}
			if($parsed == 0) return FALSE;
			if($db !== NULL) Logger::loggerImportAddress('Importação de Bairro: '.$lines.' registros.');
			if($fileNameError !== NULL)
			{
				$fileNameError = implode('<br/>',$fileNameError);
				return FALSE;
			}
			else return TRUE;
		}
		return NULL;
	}




	/**
	 * Função de validação dos valores do arquivo.
	 * Utilizada com funções do tipo 'is_numeric()', 'is_int()'
	 */
	public static function validateValue($value, $funcName=NULL, $nullable=FALSE)
	{
		if($funcName != NULL && !empty($funcName))
		{
			Zend_Loader::loadClass('Utils');
			if($funcName($value) && !Utils::isEmpty($value))
			{
				return $value;
			}
			else
			{
				if($nullable)
				{
					return NULL;
				}
				throw new Exception('O valor '. $value . ' não é um valor válido para a função '.$funcName);
			}
		}
		return NULL;
	}

	//	/**
	//	 * Faz a leitura do arquivo e retorna um array onde cada posição possui um array de valores
	//	 */
	//	public static function parseFile(&$handle, $numberOfLines=0, $splitToken=NULL)
	//	{
	//		$fileArray = NULL;
	//		if($handle !== FALSE)
	//		{
	//			$i = 0;
	//			while(!feof($handle) && $i <= $numberOfLines)
	//			{
	//				$row = fgets($handle);
	//
	//				$fileArray[] = self::parseLine($row, $splitToken);
	//
	//				if($numberOfLines != 0) $i++;
	//				unset($row);
	//			}
	//		}
	//		return $fileArray;
	//	}

	public static function parseLine(&$row, $splitToken=NULL)
	{
		if($row !== NULL)
		{
			if($splitToken != NULL)
			{
				return split($splitToken, $row);
			}
			else
			{
				return $row;
			}
		}
		return NULL;
	}

	public static function dateFormat($date)
	{
		$year	= substr($date,-4);
		$month	= substr($date,2,-4);
		$day	= substr($date,0,-6);

		$dateFormat = $year.'-'.$month.'-'.$day;

		return $dateFormat;
	}

	public static function validateParam($param, $funcName)
	{
		if($funcName($param))
		{
			return $param;
		}
		else
		{
			$param = null;
			return $param;
		}

	}


}