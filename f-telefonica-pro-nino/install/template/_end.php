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
 * Flavio Luiz dos Santos de Souza  - S2it		     18/05/2010	                       Create file 
 * 
 */
 
 /** sem acesso directo */ 
 defined('_RESTRICTED_ACCESS') or die('Sem acesso direto');
		
 if(isset($error) && sizeof($error) > 0):
 	require_once('_error.php'); 
 else: ?>
	<form action="index.php" method="post" name="adminForm" autocomplete="off">
	<div id="right">
		<div id="rightpad">
			<div id="step">

				<div class="m" style="padding-top: 5px; height: 42px;">
					<div class="far-right">
						<div class="buttonGreen nextStep">
							<a href="../" alt="Admin">Acessar o sistema</a>
						</div>
					</div>
					<h1 style="padding-top: 9px; margin-left: 15px;">Fim</h1>
				</div>
			</div>
			
			<div id="installer">

				<div class="m" style="float: left; width: 544px;">
					<h2 class="borderBotton">Parab�ns! O Redeca est� instalado.</h2>
					<div class="install-text" style="width: 544px;">
						<p>Clique no bot�o "Acessar o sistema" para conferir o sistema.</p>
					</div>
				
					<div class="install-body" style="width: 544px; margin-top: 10px;">
						
						<div class="m">
							<fieldset>
								<table class="final-table" cellpadding="0" cellspacing="0" align="left">
									<tr>
										<td class="error">
											LEMBRE-SE DE REMOVER O DIRET�RIO 'INSTALL' COMPLETAMENTE.
											Voc� n�o poder� passar deste ponto, at� que o diret�rio install seja removido. Esta � uma medida de seguran�a do Redeca.
										</td>
									</tr>
									<tr>
										<td>
											<h3 style="margin-top: 25px; font-size: 16px;">Detalhes da Sess�o do Administrador</h3>
											<p>Nome de Usu�rio: root</p>
										</td>
									</tr>

									<tr>
										<td class="notice">
											<div id="cpanel">
												<div class="icon" style="margin-top: 25px;">
													
													<b>Comunidade Redeca </b>
													<br />												
													Visite a comunidade do Redeca, atrav�s do <a style="text-decoration: underline;" href="http://www.softwarepublico.gov.br/ver-comunidade?community_id=18016032" target="_blank">Portal do Software P�blico Brasileiro</a> para maiores informa��es, documentos e download de novas vers�es. 
												
												</div>
											</div>
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
<?php endif; ?>
