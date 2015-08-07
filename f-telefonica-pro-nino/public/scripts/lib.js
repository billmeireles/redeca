function pagination(page) {
	frm = document.getElementById('navigation');
	objPage = document.getElementById('page');
	objPage.value = page;
	frm.submit();
}

/**
 * fun��o para mostrar e ocultar editar de informa��es complementates
 */
function showOrHideEdit(value, flag) {
	view = document.getElementById("view" + value);
	edit = document.getElementById("edit" + value);

	if (flag == 'view') {
		view.style.display = "none";
		edit.style.display = "";
		edit.className = "alert";
	} else if (flag == 'edit') {
		edit.style.display = "none";
		view.style.display = "";
	}
}
/**
 * Verifica se o objeto � do tipo array
 * 
 * @param {Object}
 *            obj
 */
function isArray(obj) {
	if (obj.constructor.toString().indexOf("Array") == -1)
		return false;
	else
		return true;
}
/**
 * Exibe componentes dado o Id ou um array de Id's
 * 
 * @param {Object}
 *            arrayObjId
 */
function show(arrayObjId) {
	if (isArray(arrayObjId)) {
		for ( var i = 0; i < arrayObjId.length; i++) {
			document.getElementById(arrayObjId[i]).style.display = 'inline';
		}
	} else {
		document.getElementById(arrayObjId).style.display = 'inline';
	}
}
/**
 * Oculta componentes dado o Id ou array de Id's
 * 
 * @param {Object}
 *            arrayObjId
 */
function hide(arrayObjId) {
	if (isArray(arrayObjId)) {
		for ( var i = 0; i < arrayObjId.length; i++) {
			document.getElementById(arrayObjId[i]).style.display = 'none';
		}
	} else {
		document.getElementById(arrayObjId).style.display = 'none';
	}
}

/**
 * Alterna entre exibir/esconder um componente html dado o ID
 */
function switchDisplay(idObj) {
	display = document.getElementById(idObj).style.display;
	if (display == 'none')
		document.getElementById(idObj).style.display = 'inline';
	else if (display == '' || display == 'inline')
		document.getElementById(idObj).style.display = 'none';
}

/**
 * Essa fun��o tem por finalidade exibir uma tag "div" que escondida ou
 * vice-versa.
 * 
 * @param {Object}
 *            nameDivPrimary : "div" que deve ser "exibido"
 * @param {Object}
 *            nameDivSecondary : "div" que deve ser "escondido"
 */
function showDiv(nameDivPrimary, nameDivSecondary) {
	var divPrimary = document.getElementById(nameDivPrimary);
	var divSecondary = document.getElementById(nameDivSecondary);

	divPrimary.style.display = "block";
	divSecondary.style.display = "none";
}
/**
 * Altera o valor action de um Form
 * 
 * @param {String}
 *            value - Contem o valor da action a ser utilizada
 * @param {Object}
 *            frm - referencia do form que deseja alterar
 */
function actionName(value, frm) {
	frm.action = value;
	return true;
}

function buildExport(actionValidate, actionExport) {

	// executa chamada ajax para validar se relat�rio tem ao menos uma linha
	var msgValidation = isEmptyReport(actionValidate);

	// relat�rio tem linhas para serem exibidas
	if (msgValidation == null || msgValidation == undefined
			|| msgValidation == "") {

		// chama action que exibe relat�rio
		var form = document.getElementById('reportFrm');
		form.action = actionExport;
		form.target = "_blank";
		form.submit();
		form.target = "_self";
		return false;
	} else {
		document.getElementById('errorValidate').innerHTML = msgValidation;
		document.getElementById('errorValidate').style.display = 'block';
		return false;
	}
}

function isEmptyReport(actionValidate) {
	bodyContent = $.ajax({
		url : actionValidate,
		global : false,
		type : "POST",
		data : $("#reportFrm").serialize(),
		dataType : "html",
		async : false,
		success : function(msgValidation) {
		}
	}).responseText;
	return bodyContent;
}

/**
 * Exibe ou n�o a div de emprego para renda
 * 
 * @param {String}
 *            divId - id da div que deve ou n�o ser mostrada.
 * @param {String}
 *            selectId - id do select que ser� resgatado o valor.
 * @param {Integer}
 *            typeEmp - numero com o tipo de situa��o de trabalho.
 */
function showEmployment(divId, selectId, typeEmp) {
	var divStyle = document.getElementById(divId);
	var selectOption = document.getElementById(selectId);

	var flag = true;
	for ( var i = 0; i < selectOption.options.length; i++) {
		if (selectOption.options[i].selected == true) {
			if ((selectOption.options[i].value == typeEmp)
					|| (selectOption.options[i].value == "")) {
				flag = false;
			}
		}
	}

	if (flag == true) {
		divStyle.style.display = 'block';
	} else {
		divStyle.style.display = 'none';
	}
}

/**
 * Seta um valor para o input hidden "checked" no relat�rio Atrav�s deste �
 * possivel saber qual select o usuario clicou pela ultima vez
 * 
 * @param {String}
 *            num - Contem o valor que ser� setado no hidden
 * @param {String}
 *            id - Contem o id do hidden
 */
function checkSelect(num, id) {
	var elemHidden = document.getElementById(id);
	elemHidden.value = num;
}

/**
 * Fun��o que envia informa��es do respectivo form informado
 * 
 * @param {Object}
 *            value: corresponde a "action" que deseja disparar
 * @param {Object}
 *            idForm: identifica��o do form a ser enviado
 */
function submitForm(value, idForm) {
	if (idForm != undefined || idForm != null) {
		if (value != undefined || value != null) {
			var objectForm = document.getElementById(idForm);
			objectForm.action = value;
			objectForm.submit();
		} else {
			alert("A��o para o respectivo form inv�lida");
		}
	} else {
		alert("Identifica��o do form inv�lida");
	}
}

/**
 * Essa fun��o verifica a quantidade de op��es selecionadas pelo usu�rio em uma
 * tag "select multiple". Retorna FALSE caso usu�rio tenha selecionado mais de
 * uma op��o
 * 
 * Se o usu�rio selecionou mais de um op��o, � retornado falso. Se o usu�rio
 * selecionou apenas uma op��o, o fluxo da action continua conforme par�metros
 * informados.
 * 
 * @param {Object}
 *            urlAction: endere�o URL da aplica��o a ser chamado ap�s
 *            verifica��o, caso usu�rio tenha selecionado apenas uma op��o.
 * @param {Object}
 *            form: nome do form que ter� as informa��es submetidas.
 * @param {Object}
 *            idSelect: identifica��o da tag "select" que ser� validado.
 * 
 */
function reportMultipleSelected(urlAction, form, idSelect) {
	// cria uma vari�vel que recebe informa��es da tag SELECT.
	var select = document.getElementById(idSelect);

	// cria uma vari�vel do tipo BOOLEANO.
	var verify = new Boolean(false);

	// verifica o tamanho do SELECT, e posteriormente faz a itera��o no mesmo
	// para ver a quantidade de "OPTION" que o usu�rio selecionou.
	for (count = 0; count < select.options.length; count++) {
		// verifica se "OPTION" foi selecionada pelo usu�rio.
		if (select.options[count].selected == true) {
			// se "OPTION" foi selecionada setar vari�vel "verify" para TRUE.
			if (verify == false) {
				// seta vari�vel "verify" para TRUE.
				verify = true;
			} else {
				// caso usu�rio tenha selecionado mais de uma OPTION, a mensagem
				// abaixo � exibida para o mesmo.
				alert('Selecione apenas uma op��o para ser editada.');

				// ao retornar FALSE, a aplica��o mant�m fluxo na mesma URL
				// chamada.
				return false;

				// p�ra fluxo da condi��o FOR.
				break;
			}
		}
	}

	// caso o usu�rio tenha selecionado apenas uma OPTION, a aplica��o continua
	// seu fluxo.
	actionName(urlAction, form);
}

/**
 * Mostrar e esconder senha
 */
function showAndHide(objDiv) {
	pass = document.getElementById("flgPassword");
	if (pass.checked) {
		document.getElementById("password").style.display = "block";
	} else {
		document.getElementById("password").style.display = "none";
	}

	/*
	 * div = document.getElementById('objDiv'); if(div != undefined || div !=
	 * null) { if(div.style.display == "block") { div.style.display = "none"; }
	 * else { div.style.display = "block"; } }
	 */
}

function setChecked(idElementForm, nameAtribute) {
	if (idElementForm != undefined && idElementForm != null) {
		var valParent = document.getElementById(idElementForm).value;
		document.getElementById("elementParent").value = valParent;
	}
}

/**
 * Popula os campos de endereco quando selecionado um dos enderecos do resultado
 * da busca
 * 
 * @param {Object}
 *            idFormFrom - Id do form da busca de CEP
 * @param {Object}
 *            idAddress - id do endereco desejado
 */
function addressHandler(idFormFrom, idAddress) {
	if (idFormFrom != undefined && idFormFrom != null && idFormFrom != '') {
		fields = [ "_adr_address_type", "_adr_id_address", "_adr_address",
				"_adr_zipcode", "_adr_uf", "_adr_city", "_adr_neighborhood" ];

		var formFrom = document.getElementById(idFormFrom);

		for ( var i = 0; i < fields.length; i++) {
			var fromField = idAddress + fields[i];
			var value = null;
			for ( var k = 0; k < document.forms[idFormFrom].elements.length; k++) {
				if (document.forms[idFormFrom].elements[k].name == fromField)
					value = document.forms[idFormFrom].elements[k].value;
			}
			forms = parent.document.forms;

			for ( var j = 0; j < forms.length; j++) {
				if (forms[j].elements[fields[i]] != undefined) {
					forms[j].elements[fields[i]].value = value;
					// N�O PERMITE EDI��O DOS CAMPOS PREENCHIDOS AUTOMATICAMENTE
					forms[j].elements[fields[i]].onkeypress = function() {
						return false;
					};
					// forms[j].elements[fields[i]].disabled=true;
				}
			}
		}
	}
}

/**
 * Adiciona mais uma linha de cadastro de telefone
 */
var my_div = null;
var newDiv = null;
function addPhone(id, ddd, number, type, option) {
	/*
	 * var div = document.createElement("div"); div.setAttribute("id",
	 * "divPhone1");
	 * 
	 * var inputDdd = document.createElement("input");
	 * inputDdd.setAttribute("name", ddd+"[]"); inputDdd.setAttribute("size",
	 * "5");
	 * 
	 * var inputNumber = document.createElement("input");
	 * inputNumber.setAttribute("name", number+"[]");
	 * inputNumber.setAttribute("size", "15");
	 * 
	 * var inputId = document.createElement("hidden");
	 * inputId.setAttribute("name", id+"[]");
	 * 
	 * var inputType = document.createElement("select");
	 * inputType.setAttribute("name", type+"[]");
	 * inputType.setAttribute("width", "15px");
	 * 
	 * inputType.options[inputType.length] = new Option("Fixo", 1);
	 * inputType.options[inputType.length] = new Option("Celular", 2);
	 * inputType.options[inputType.length] = new Option("Fax", 3);
	 * 
	 * div.appendChild(inputDdd); div.appendChild(inputNumber);
	 * div.appendChild(inputType); div.appendChild(inputId);
	 * 
	 * var father = document.getElementById("divPhone"); var fatherNode =
	 * father.parentNode; fatherNode.insertBefore(div, father.nextSibling);
	 */

	document.getElementById("divPhone").innerHTML = "<b>teste</b><input type='text' name'aaaa' value='aaaaa'>";
}

function disabledField(idCheck, idTextArea) {
	var checkBox = document.getElementById(idCheck);
	document.getElementById(idTextArea).disabled = !checkBox.checked;
}

function showCep() {
	var cep = document.getElementById("zip");
	var logra = document.getElementById("adress");

	cep.style.display = '';
	logra.style.display = 'none';

}

function hideCep() {
	var cep = document.getElementById("zip");
	var logra = document.getElementById("adress");

	cep.style.display = 'none';
	logra.style.display = '';

}

/**
 * fun��o para mostrar e ocultar plano de sa�de (outros)
 */
function showOrHideHealthPlan() {

	var val = document.getElementById('idHealthPlan').value;

	if (val == 'outro') {
		document.getElementById('otherHealthPlan').style.display = 'inline';
	} else {
		document.getElementById('otherHealthPlan').style.display = 'none';
	}
}

/**
 * fun��o usada para desabilitar uma vacina na ficha de saude
 */
function confirma(id, action) {
	if (confirm("Deseja realmente exluir este registro de vacina?")) {
		$.post(action, {
			idVaccination : id
		}, function(data) {
			window.location.reload();
		});
	} else {
		return false;
	}
};

/**
 * Salva ou Edita vacina��o
 */
function editVaccination(count, action, type) {

	if (type == 'edit') {
		var frmVaccination = document.getElementById('idVaccination' + count).value;
		var frmVaccine = document.getElementById('idVaccine' + count).value;
		var frmPerson = document.getElementById('idPerson').value;
		var frmDate = document.getElementById('date' + count).value;
		var frmLot = document.getElementById('lot' + count).value;
		var frmStatus = document.getElementById('status' + count).value;
	} else if (type == 'save') {
		var frmVaccination = '0';
		var frmVaccine = document.getElementById('idVaccine').value;
		var frmPerson = document.getElementById('idPerson').value;
		var frmDate = document.getElementById('date').value;
		var frmLot = document.getElementById('lot').value;
		var frmStatus = document.getElementById('status').value;
	}

	var vaccination = {
		idVaccination : frmVaccination,
		idVaccine : frmVaccine,
		date : frmDate,
		lot : frmLot,
		status : frmStatus,
		person : frmPerson
	}

	$.post(action, vaccination, function(data) {
		if (data == '1') {
			window.location.reload();
		} else if ($(data).find("#usernameDiv").html() == null) {
			$("#showErrors").html(data);
		} else {
			window.location.reload();
		}
	});
};

// abre e fecha DIV a partir do id
function openClose(obj) {
	var el = document.getElementById(obj);
	if (el.style.display != "block") {
		el.style.display = "block";
	} else {
		el.style.display = "none";
	}
}

function closeForm() {

var teste = document.getElementById('hidden').value = "close";

}
