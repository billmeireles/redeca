function changeDiv(divId){
	
	document.getElementById('home').style.display = 'none';
	
	// la�o para implementa�oes
	for(var i=1; i <= 2; i++){
		document.getElementById('dev'+i).style.display = 'none';
	}
	
	// exibe a div clicada
	document.getElementById(divId).style.display = 'block';
}