//Fonction de validation du formulaire
function ValiderPdsContact(){
	//Syntaxe pour la v�rification de l'email Merci � DynamicDrive.com
	var EmailFilter=/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i;
	var ReturnVal=EmailFilter.test($("#ChampEmailContact").val());
	//On commence par v�rifier que tous les champs sont remplis
	if ($("#ChampEmailContact").val()=="" || $("#ChampSujetContact").val()=="" || $("#ChampMessageContact").val()=="" || $("#ChampAntiSpamContact").val()=="") {
		$("#ResultatPdsContactSimple").addClass("FormulaireContactSimpleRequis").fadeIn(1000).html("Vous devez remplir tous les champs !");
	}
	else if (ReturnVal==false){
		$("#ResultatPdsContactSimple").addClass("FormulaireContactSimpleRequis").fadeIn(1000).html("Votre email est incorrect");
	}
	else{
		//requete Ajax pour l'envoi du mail
		$.ajax({
		type: "POST",
		url: "EnvoyerMessage.php",
		data: $("#FormulaireContactSimple").serialize(),
		//Succes de ajax, on v�rifie la r�ponse
			success: function(msg){
				if (msg==0) {
					$("#ResultatPdsContactSimple").addClass("FormulaireContactSimpleRequis").html("Tous les champs sont obligatoires");
				}
				else if (msg==1) {
					$("#ResultatPdsContactSimple").addClass("FormulaireContactSimpleRequis").html("Le code anti spam n'est pas correct");
				}
				else if (msg==2) {
					$("#ResultatPdsContactSimple").addClass("FormulaireContactSimpleOk").html("Le message a bien �t� envoy�. Vous recevrez une r�ponse d�s que possible");
				}
				else{
					$("#ResultatPdsContactSimple").addClass("FormulaireContactSimpleRequis").html(msg);
				}
		    }
		});
	}
}