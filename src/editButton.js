	let code = "";

    document.getElementById("editButton").onclick = function () {
        code = document.getElementById("code").value;
        location.href = "edit/"+code;
    };
