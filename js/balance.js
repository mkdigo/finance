const page = "Balance";

const fullDate = new Date();
const year = fullDate.getFullYear();
const month = fullDate.getMonth() + 1;

$("#year").value = year;
$("#month").value = month;

const formBalance = $("#formBalance select, #formBalance input");
for(let x = 0; x < formBalance.length; x++){
    formBalance[x].addEventListener("change", ()=>{
        request();
    });
}

function request(){
	let load = new Load();
	load.loadOn();

	let form = new FormData($("#formBalance"));

	fetch('balanceApi.php', {
		method: 'post',
		body: form
	}).then((response)=>{
		if(!response.ok) throw new Error('Erro ao executar requisição, status ' + response.status);
		return response.json();
	}).then((data)=>{
		close();
		if(data.error.number == 0){
            $("#assetsAmount").innerHTML = data.assetsAmount;
            $("#currentAssetsData").innerHTML = data.currentAssetsData;
            $("#currentAssetsAmount").innerHTML = data.currentAssetsAmount;
            $("#longTermAmount").innerHTML = data.longTermAmount;
            $("#longTermData").innerHTML = data.longTermData;
            $("#permanentAmount").innerHTML = data.permanentAmount;
            $("#permanentData").innerHTML = data.permanentData;
            $("#currentLiabilitiesAmount").innerHTML = data.currentLiabilitiesAmount;
            $("#currentLiabilitiesData").innerHTML = data.currentLiabilitiesData;
            $("#longTermLiabilitiesAmount").innerHTML = data.longTermLiabilitiesAmount;
            $("#longTermLiabilitiesData").innerHTML = data.longTermLiabilitiesData;
            $("#equityAmount").innerHTML = data.equityAmount;
            $("#equityData").innerHTML = data.equityData;
            $("#revenuesAmount").innerHTML = data.revenuesAmount;
            $("#revenuesData").innerHTML = data.revenuesData;
            $("#expensesAmount").innerHTML = data.expensesAmount;
            $("#expensesData").innerHTML = data.expensesData;
            $("#netIncome").innerHTML = data.netIncome;
            $("#liabilitiesAmount").innerHTML = data.liabilitiesAmount;
			
		}else{
			$("#boxError").innerHTML = data.error.msg;
            box("boxError");
            setTimeout(()=>{close();}, 2500);
		}

		load.loadOff();
	}).catch((err)=>{
		console.log(err);
	});
}

request();