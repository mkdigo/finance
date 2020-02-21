document.addEventListener("DOMContentLoaded", function(){
    if(page != null){
        let selected = document.getElementById("menu"+page);
        selected.style.backgroundColor = "rgb(25, 19, 36)";
        selected.style.color = "#CCC";
    }

    //MENU BUTTON ANIMATION
    const select = document.querySelectorAll(".menu li");
    for(let x = 0; x < select.length; x++){
         select[x].addEventListener("click", function(){
            select[x].style.backgroundColor = "rgba(255, 255, 255, 0.2)";
            select[x].style.color = "#CCC";
            setTimeout(()=>{
                select[x].style.backgroundColor = "rgba(38, 30, 52, 1)";
                select[x].style.color = "#FFF";
            },300);
        });
    }


    //BANK LIST
    var menuBankListActive = false;
    const menuBank = document.querySelector("#menuBank");
    menuBank.addEventListener("click", ()=>{
        let menuBankList = document.querySelector("#menuBankList");
        let maxHeight;
        if(menuBankListActive == false){
            menuBankListActive = true;
            maxHeight = "500px";
        }else{
            menuBankListActive = false;
            maxHeight = "0px";
        }
        menuBankList.style.maxHeight = maxHeight;
    });

    
    //BOX CLOSE
    const closeButton = document.querySelectorAll(".close");
    for(let x = 0; x < closeButton.length; x++){
         closeButton[x].addEventListener("click", function(){
            close();
        });
    }

    //TOOLS BUTTON ANIMATION
    const toolsButton = document.querySelectorAll(".tools ul li");
    for(let x = 0; x < toolsButton.length; x++){
        toolsButton[x].addEventListener("click", function(){
            toolsButton[x].style.background = "rgba(120, 120 , 120, 1)";
            setTimeout(()=>{
                toolsButton[x].style.background = "rgba(180, 180 , 180, 1)";
            }, 500);
       });
   }

   //ADJUSTMENT
   $("#menuAdjustment").addEventListener("click", ()=>{
       box("boxAdjustment");
       $("#adjustmentDate").value = now();
   })
   
    // var menu = false;

    // function menuActive(){
    //     let nav = document.querySelector("nav.pages");
    //     let layer = document.querySelector(".layer");
    //     if(menu === false){
    //         menu = true;
    //         layer.style.display = "flex";
    //         nav.style.width = "140px";
    //         nav.style.height = "437px";
    //     }else{
    //         menu = false;
    //         layer.style.display = "none";
    //         nav.style.width = "0px";
    //         nav.style.height = "0px";
    //     }
    // }

    // const menuButton = document.querySelector("#mobilePages");
    // menuButton.addEventListener("click", ()=>{
    //     menuActive();
    // });
});


function openPage(p){
    window.location.href = p+".php";
}


function openBank(id){
    window.location.href = "bank.php?id=" + id;
}


function $(el){
    let element = document.querySelectorAll(el);
    if(element.length > 1){
        return element;
    }else{
        return element[0];
    }
}


function num(e){
    let key = (window.event) ? event.keyCode : e.which;

    if((key > 47 && key < 58)) return true
    else{
    	if (key == 8 || key == 0) return true
	    else  return false
    }
}


function now(){
    let fullDate = new Date;

    let day = fullDate.getDate();
    day = (day < 10) ? "0" + day : day;

    let month = fullDate.getMonth()+1;
    month = (month < 10) ? "0" + month : month;
    
    let year = fullDate.getFullYear();

    let full = year + "-" + month + "-" + day;

    return full;
}


function maskVal(field){
    let f = document.getElementById(field);
    let v = f.value;
	if(v != ""){
		if(v.indexOf('.') != -1){
			while(v.indexOf('.') != -1){
				v = v.replace(".","");
			}
		}
		v = parseInt(v).toLocaleString('de-DE');
        f.value = v;
	}
}

function maskValClear(n){
	if(n != ""){
        if(n.indexOf('.') != -1){
            while(n.indexOf('.') != -1){
                n = n.replace(".","");
            }
        }
        n = parseInt(n);
    }
	return n;
}



function box(boxId, focus=null){
    let layer = document.querySelector(".layer");
    layer.style.display = "flex";
    
    let box = document.querySelector("#"+boxId);
    box.style.display = "block";

    if(focus != null){
        let f = document.querySelector("#"+focus);
        f.focus();
    }

    let dateFields = document.querySelectorAll("div.box input[type=date]");
    for(let x = 0; x< dateFields.length; x++){
        dateFields[x].value = now();
    }
}


function close(){
    let layer = document.querySelector(".layer");
    layer.style.display = "none";

    let box = document.querySelectorAll(".box");
    for(let x = 0; x < box.length; x++){
        box[x].style.display = "none";
    }

    let input = document.querySelectorAll(".box form input");
    for(let x = 0; x < input.length; x++){
        input[x].value = "";
        errorClear(input[x]);
    }
}


function error(field){
    //let f = document.querySelector("#"+field);
    field.style.backgroundColor = "#EEF46C";
    field.focus();
}


function errorClear(field){
	//let f = document.querySelector("#"+field);
    field.style.backgroundColor = "#FFF";
}


function Load(){
    this.l = document.getElementById("load");
    this.loadOn = () => {
        this.l.style.display = "flex";
    }
    this.loadOff = () => {
        this.l.style.display = "none";
    }
}


//ENTER
function keyEnter(e, button){
    if(e.which == 13){
        document.querySelector(button).click();
    }
}


// ESC
document.addEventListener("keyup", function(e){
    if(e.which == 27){
        close();
    }
})


//GET URL VARIABLE
function getUrlVar(){
    let query = location.search.slice(1);
    let parts = query.split('&');
    let get = {};
    parts.forEach(function (part) {
        let keySeparate = part.split('=');
        let key = keySeparate[0];
        let val = keySeparate[1];
        get[key] = val;
    });
    return get;
}


/*
KEY_DOWN = 40; 
KEY_UP = 38; 
KEY_LEFT = 37; 
KEY_RIGHT = 39; 

KEY_END = 35; 
KEY_BEGIN = 36; 

KEY_BACK_TAB = 8; 
KEY_TAB = 9; 
KEY_SH_TAB = 16; 
KEY_ENTER = 13; 
KEY_ESC = 27; 
KEY_SPACE = 32; 
KEY_DEL = 46; 

KEY_A = 65; 
KEY_B = 66; 
KEY_C = 67; 
KEY_D = 68; 
KEY_E = 69; 
KEY_F = 70; 
KEY_G = 71; 
KEY_H = 72; 
KEY_I = 73; 
KEY_J = 74; 
KEY_K = 75; 
KEY_L = 76; 
KEY_M = 77; 
KEY_N = 78; 
KEY_O = 79; 
KEY_P = 80; 
KEY_Q = 81; 
KEY_R = 82; 
KEY_S = 83; 
KEY_T = 84; 
KEY_U = 85; 
KEY_V = 86; 
KEY_W = 87; 
KEY_X = 88; 
KEY_Y = 89; 
KEY_Z = 90; 

KEY_PF1 = 112; 
KEY_PF2 = 113; 
KEY_PF3 = 114; 
KEY_PF4 = 115; 
KEY_PF5 = 116; 
KEY_PF6 = 117; 
KEY_PF7 = 118; 
KEY_PF8 = 119;
*/