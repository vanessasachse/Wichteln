	let code = "";

    document.getElementById("editButton").onclick = function() {
        code = document.getElementById("code").value;
        if (code != "") {
            location.href = "edit/"+code;
        }
        else {
            alert("Bitte gib deinen Teilnehmercode ein!")
        }
        
    };
