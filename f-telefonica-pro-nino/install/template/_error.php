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
?>
<form action="index.php" method="post" name="adminForm">
	<div id="right">
		<div id="rightpad">
		
			<div id="step">
				<div class="m" style="padding-top: 5px; height: 42px;">
					<div class="far-right">
						<div class="buttonGreen previousStep">
							<a onclick="returnForm(adminForm,'dataBase.php');" alt="Anterior">Anterior</a>
						</div>
					</div>
					<h1 style="padding-top: 9px; margin-left: 15px;">Erro</h1>
				</div>
			</div>
			
			<div id="installer">

				<div class="m" style="float: left; width: 544px;">
					<h2 class="borderBotton">Ocorreu um erro.:</h2>
					<div class="install-text-error">
						<p><b>Erro na construção da base de dados.</b></p>
						<em>
							<span style="color:red;">
							<?php foreach ($error as $an_error){
								echo "<p>".$an_error."</p>";
							};?>
							</span>
						</em>
					</div>	
				</div>
			</div>
			
		</div>

	</div>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="dbhostname" id="dbhostname" value="<?php if($dbHost) echo trim($dbHost);?>" />
	<input type="hidden" name="dbusername" id="dbusername" value="<?php if($dbUserName) echo trim($dbUserName);?>" />
	<input type="hidden" name="vars_dbpassword" id="vars_dbpassword" value="<?php if($dbPassword) echo trim($dbPassword);?>" />
	<input type="hidden" name="vars_dbname" id="vars_dbname" value="<?php if($dbName) echo trim($dbName);?>" />
</form>
