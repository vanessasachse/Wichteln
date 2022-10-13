const text = document.querySelector("#box");

function delCode() {
    let code = prompt();
    async function calldel(cc) {
        console.log("delete" +cc);
        const response = await fetch("deletecode.php?code="+cc);
        let data = await response.text();
        alert(data);
    }
    if(code != null  && code.match(/^[A-z0-9\-]+$/)) {
        calldel(code);
    }
    else {
        console.log("Invalid Input");
        console.log(code);
    }
}


document.getElementById("gen").onclick = function() {

    text.value = "Lade.."
    async function generate() {
        const response = await fetch("generate.php");
        let data = await response.text();
        data = data.replace(/Teilnehmercode (.*) wurde angelegt!/, "$1")
        text.value = data;
        text.select();
    }
    generate();
};

text.onclick = () =>  {
    text.select();
}