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
?>

<form action="finish.php" method="post" name="adminForm" id="adminForm">
	<div id="right">

		<div id="rightpad">
			<div id="step">
			
				<div class="m" style="padding-top: 5px; height: 42px;">
					<div class="far-right">
						<div class="buttonGreen previousStep">
							<a onclick="returnForm(adminForm, 'index.php');" alt="Anterior">Anterior</a>
						</div>
						<div class="buttonGreen nextStep">
							<a onclick="validateForm(adminForm,'<?php echo false;?>');" alt="Avan�ar">Avan�ar</a>
						</div>
					</div>
					<h1 style="padding-top: 9px; margin-left: 15px;">Configura��o do banco de dados</h1>
				</div>
			</div>
		
		
			<input type="hidden" id="example" name="example" value="" />
			<input type="hidden" id="alreadyInstalled" name="alreadyInstalled" value="">
		
			<div id="installer">
				<div class="m" style="float: left; width: 544px;">
					<h2 class="borderBotton">Configura��es da conex�o:</h2>
					<div class="install-text">
						<p>Configurar o Redeca para acessar seu banco de dados envolve fornecer as
						seguintes informa��es:</p><br />
						<p>Informe o nome do servidor de banco de dados onde o Redeca ser� instalado.</p><br />
						
						<p><span style="color: red">Aten��o:</span> Este pode n�o ser necessariamente o mesmo IP/nome do servidor Web, 
						ent�o verifique com sua empresa de hospedagem para que n�o haja d�vidas.</p><br />
						<p>Informe o nome de usu�rio, senha e nome do banco de dados do MySQL, 
						os quais deseja que o Redeca utilize.</p>
						<br/>
						
						
						<?php if(sizeof($_GET) > 0 && isset($_GET['error']) || isset($_GET['errordb'])):?>
						<div class="errorInstall">						
							<span>
								<p>
									<b>Aten��o:</b>N�o foi poss�vel finalizar a instala��o com os dados informados.
									Verifique a mensagem abaixo:
								</p>
								<br/>
								<p>
									<em>
										<?php echo $_GET['error'];?>											
									</em>
								</p>
							</span>
							</div>
						<?php endif;?>						
					</div>
					
					<div class="install-body">

						<div class="m">
							<h2 class="borderBotton" title="Configura��es B�sicas">Configura��es B�sicas</h2>
							<div class="section-smenu">
								<table class="content2" cellpadding="0" cellspacing="0">
									<tr>
										<td colspan="2">
											<label for="dbhostname" class="noBorder">
												<span id="dbhostnamemsg"> Nome do Servidor</span>
											</label>
											</br>
											<input id="dbhostname" name="dbhostname" class="inputbox validate required none dbhostnamemsg" type="text"
												value="<?php if(isset($_GET['dbhostname'])) echo trim($_GET['dbhostname']); if(isset($_POST['dbhostname'])) echo trim($_POST['dbhostname']);?>" />
										</td>
									</tr>
									
									<tr>
										<td colspan="2">
											<em>
												Normalmente � <b>localhost</b> ou o nome de servidor fornecido por sua empresa de hospedagem.
											</em>
										</td>
									</tr>
									
									<tr>
										<td colspan="2">
											<label for="dbusername">
												<span id="dbusernamemsg"> Nome de Usu�rio</span>
											</label>
											<br />
											<input id="dbusername" name="dbusername" class="inputbox validate required none dbusernamemsg" type="text" 
												value="<?php if(isset($_GET['dbusername'])) echo trim($_GET['dbusername']); if(isset($_POST['dbusername'])) echo trim($_POST['dbusername']);?>" />
										</td>
									</tr>
									
									<tr>
										<td colspan="2">
											<em>
												Pode ser o nome de usu�rio padr�o do MySQL: root ou um nome de usu�rio fornecido pela sua empresa de hospedagem.
											</em>
										</td>
									</tr>
									
									<tr>
										<td colspan="2">
											<label for="vars_dbpassword">
												Senha 
											</label>
											<br />
											<input id="vars_dbpassword" name="vars_dbpassword" class="inputbox" type="password" 
												value="<?php if(isset($_GET['vars_dbpassword'])) echo trim($_GET['vars_dbpassword']); if(isset($_POST['vars_dbpassword'])) echo trim($_POST['vars_dbpassword']);?>" />
										</td>
									</tr>
									
									<tr>
										<td colspan="2">
											<em>
												Para seguran�a do site, � sugerido utilizar uma senha para a conta MySQL. Esta � a mesma senha usada para acessar seu banco de dados.
											</em>
										</td>
									</tr>
									
									<tr>
										<td colspan="2">
											<label for="vars_dbname">
												 Nome do Banco de Dados   
											</label>
											<br />
											<input id="vars_dbname" name="vars_dbname" class="inputbox validate required none dbnamemsg" type="text"
												 value="<?php if(isset($_GET['vars_dbname'])) echo trim($_GET['vars_dbname']); if(isset($_POST['vars_dbname'])) echo trim($_POST['vars_dbname']);?>" />
										</td>
									</tr>
									
									<tr>
										<td colspan="2"> 
											<em>
												Nome do banco de dados (obrigat�rio). <b style="color: red;">Se voc� informar um nome de banco de dados que j� exista, n�o conseguir� prosseguir com a instala��o</b>. Verifique com sua empresa de hospedagem caso n�o saiba. 
											</em>
										</td>
									</tr>
								</table>
								<br/>
								<span style="color: green;"><b>Importante:</b> Ao final da execu��o, o arquivo dbconfig ser� alterado!</span> 
								<br/>
							</div>
							<div class="clr"></div>	
						</div>
						
						<div class="clr"></div>
					
					</div>
				
					<div class="clr"></div>
				
					<form enctype="multipart/form-data" action="index.php" method="post" name="filename" id="filename">
						<h2 class="borderBotton">Carregar exemplo de conte�do</h2>
						<div class="install-text">
							<p><b><span style="color: red;">IMPORTANTE:</span></b> � recomendado a instala��o de exemplos de conte�do para conhecer o sistema
							e aplicar treinamentos. 
							Para isso, voc� precisa clicar no bot�o ao lado antes de passar para a pr�xima etapa.</p><br />
							<p>Resumo das op��es:</p><br />
							<p><b>1. Exemplo de conte�do</b> pode ser inserido. Para tal, clique no bot�o 
							"<b>Instalar exemplo de conte�do</b>".</p><br />
							<p><b>2. Instala��o Limpa</b> Se voc� deseja a instala��o limpa (para produ��o). 
							Ent�o, v� para a etapa final clicando em "<b>Avan�ar</b>".</p>
						</div>
						
						<div class="install-body">

							<div class="m">
								<fieldset>
									<table class="content2" cellpadding="0" cellspacing="0">
										<tr>
											<td>
												<span id="theDefault">
													<input class="button" type="button" name="instDefault" value="Instalar exemplo de conteudo" onclick="installExample(adminForm,'makedb');"/>
												</span>
											</td>
										</tr>
										<tr>
											<td>
												<em>
													Instala��o recomendada para conhecer o sistema e para aplica��o de treinamentos. 
													Esta op��o ir� instalar o exemplo de conte�do que est� incluso no pacote de instala��o do Redeca.
												</em>
											</td>	
										</tr>										
									</table>
								</fieldset>
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</form>

