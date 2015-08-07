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
 * Flavio Luiz dos Santos de Souza  - S2it		     18/05/2010	                       Create file 
 * 
 */
 
	/** sem acesso directo */ 
	defined('_RESTRICTED_ACCESS') or die('Sem acesso direto');
	if(file_exists('.'.DS.FILE_INSTALLED))
		header("Location: finish.php");
	else {
 ?>
	<form action="dataBase.php" method="post" name="adminForm">
		<div id="right">
			<div id="rightpad">
				<div id="step">
					
					<div class="m" style="padding-top: 5px; height: 42px;">
						<div class="far-right">
							<div class="buttonGreen refreshPage">
								<a onClick="window.location.reload()" alt="Verificar Novamente">Verificar Novamente</a>
							</div>
							<div class="buttonGreen nextStep">
								<a onclick="validateRecommendationsForm(adminForm);" alt="Avançar">Avançar</a>
							</div>
						</div>
						<h1 style="padding-top: 9px; margin-left: 15px;">Pré-Instalação</h1>
					</div>
					
				</div>
				
				<div id="installer">
					<div class="m" style="float: left; width: 544px;">
						<h2 class="borderBotton">Verificação de pré-instalação para o Redeca <?php echo $versionRedeca; ?></h2>
							
							<p>Antes de iniciar, certifique-se que o <b>Apache</b> esteja com as seguintes configurações:</p>
							<ul>
								<li>Módulo mod_rewrite.so habilitado.*</li>
								<li>AllowOverride All para o diretório/virtualHost aonde a aplicação está instalada.*</li>
							</ul>
							* Estes itens não são avaliados pelo instalador.
							<br /><br />
						<h2 class="borderBotton">Requisitos:</h2>
						<div class="install-text">
							<p>Se algum destes itens não forem suportados (destacado como <spam style='color: red;'>Não</spam>), 
							seu sistema não será compatível com os requisitos mínimos necessários.</p> 
							<p>Por favor, providencie a correção destes erros. A não observância destes requisitos poderá 
							fazer com que a instalação do Redeca não funcione corretamente.</p>
						</div>
						<div class="install-body">
							<div class="m" >
								<fieldset>
								<?php require_once('_validationEnvironment.php'); ?>
									<table class="content" cellpadding="0" cellspacing="0">
										<?php foreach ($verification as $key => $value) :?>
										<tr>
											<td class="item" valign="top" align="left">
											<?php echo $key; ?>
											</td>
											<td valign="top" align="center">
												<span class="small">
												<?php echo $verification[$key]; ?>
												<input type="hidden" name="<?php echo $key;?>" id="<?php echo $key;?>" value="<?php if($verification[$key] == $sim)  echo true; ?>" />
												</span>
											</td>
										</tr>
										<?php endforeach; ?>
										<tr>
											<td>
												<input type="hidden" id="isValidationEnvironment" name="isValidationEnvironment" value="<?php echo $isValidationEnvironment; ?>" />
											</td>
										</tr>
									</table>
								</fieldset>
							</div>
							<div class="clr"></div>
						</div>
						
						<div class="newsection"></div>
					
						<h2 class="borderBotton">Configurações Recomendadas:</h2>
						<div class="install-text">
							Estas configurações são recomendadas para o PHP, a fim de assegurar total compatibilidade com o Redeca.
							</br>
							Contudo, o Redeca ainda poderá funcionar caso suas configurações não estejam totalmente de acordo com o recomendado.
						</div>
						<div class="install-body">
							
							<div class="m">
								<fieldset>
								<?php require_once('_recommendedSettings.php'); ?>
									<table class="content" cellpadding="0" cellspacing="0">
										<tr>
											<td class="toggle">
												<b><em>Diretivas</em></b>
											</td>
											<td class="toggle">
												<b><em>Recomendado</em></<b>
											</td>
											<td class="toggle">
												<b><em>Atual</em></b>
											</td>
										</tr>
										<?php foreach ($verificationSettings as $key => $array) :?>
										<tr>
											<td class="item">
												<?php echo $key; ?> 	   	   
											</td>
											<td class="toggle">
												<?php echo $array[0];?>
											</td>
											<td>	
												<?php echo $array[1];?>
											<td>
										</tr>
										<?php endforeach; ?>
										<tr>
											<td>
												<input type="hidden" id="isRecommendedSettings" name="isRecommendedSettings" value="<?php echo$isRecommendedSettings; ?>" />
											</td>
										</tr>
									</table>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<input type="hidden" name="task" value="" />
	</form>
<?php }?>