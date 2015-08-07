function returnForm(frm, actionURL) {
	frm.action = actionURL;
	frm.submit();
}

function validateForm(frm,bool) {
	var DBhostname = document.getElementById('dbhostname');
	var DBname = document.getElementById('vars_dbname');

	if (DBhostname.value == '') {
		alert( 'Por favor, informe o nome do servidor' );
		return;
	} else if (DBname.value == '') {
		alert( 'Por favor, informe o nome do banco de dados' );
		return;
	} else if (DBname.value.length > 64) {
		alert('O nome do banco de dados no MySQL deve ter no m�ximo 64 caracteres');
		return;
	} else {
		document.getElementById('example').value = bool;
		document.getElementById('alreadyInstalled').value = true;
		frm.submit();
	}
}

function validateRecommendationsForm(frm){
	var message = 'Verifique os itens, pois o ambiente n�o est� adequado.';

	var isValidationEnvironment = document.getElementById('isValidationEnvironment');
	var isRecommendedSettings = document.getElementById('isRecommendedSettings');

	if(!isValidationEnvironment.value)
		alert(message);
	else
		frm.submit();
}

function installExample(frm,task){
	var bool = confirm("Tem certeza que deseja instalar o conte�do de exemplo?");
	if (bool) 
		validateForm(frm,bool);
}