	let code = "";

    document.getElementById("editButton").onclick = function() {
        code = document.getElementById("code").value;
        if (code.match(/^[A-z0-9\-]+$/)) {
            location.href = "edit/"+code;
        }
        else {
            alert("Dein Teilnehmercode hat ein ungültiges Format!")
        }
        
    };
