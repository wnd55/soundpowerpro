

var source, chattext, last_data, chat_btn, conx_btn, disconx_btn, text;
var hr = new XMLHttpRequest();


var nick = document.getElementById("name");
var btnOk = document.getElementById("btn_ok");
document.getElementById("text").style.display = "none";
document.getElementById("chat_btn").style.display = "none";
document.getElementById("conx_btn").style.display = "none";
document.getElementById("disconx_btn").style.display = "none";


btnOk.onclick = function () {
    hr.open("POST", "/chat/chat-intake", true);
    hr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    hr.onreadystatechange = function () {
        if (hr.readyState == 4 && hr.status == 200) {
            if (hr.responseText == "success") {
                chattext = document.getElementById("chattext");
                document.getElementById("chat_btn").style.display = "block";
                chat_btn = document.getElementById("chat_btn");

                document.getElementById("conx_btn").style.display = "inline-block";


                conx_btn = document.getElementById("conx_btn");

                document.getElementById("disconx_btn").style.display = "inline-block";
                disconx_btn = document.getElementById("disconx_btn");
                document.getElementById("text").style.display = "block";
                text = document.getElementById("text");
                conx_btn.disabled = false;
                chattext.innerHTML += "<p>"+"Добро пожаловать в чат " +"<b>" + nick.value + "</b>" +", нажмите Connect, когда будете готовы."+"</p>" + "<hr>";
                document.querySelector("#userName").remove();


            }
        }
    }

    hr.send("uname=" + nick.value);


}


function connect() {
    if (window.EventSource) {
        source = new EventSource("/chat/server");
        source.addEventListener("message", function (event) {
            if (event.data != last_data && event.data != "") {
                chattext.innerHTML += event.data + "<hr>";
            }
            last_data = event.data;
        });
        chat_btn.disabled = false;
        conx_btn.disabled = true;
        disconx_btn.disabled = false;
    } else {
        alert("event source does not work in this browser, author a fallback technology");
        // Program Ajax Polling version here or another fallback technology like flash
    }
}


function disconnect() {
    source.close();
    disconx_btn.disabled = true;
    conx_btn.disabled = false;
    chat_btn.disabled = true;
}


function chatPost() {
    chat_btn.disabled = true;
    hr.open("POST", "/chat/chat-post", true);
    hr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    hr.onreadystatechange = function () {
        if (hr.readyState == 4 && hr.status == 200) {
            chat_btn.disabled = false;
            text.value = "";

        }
    }
    hr.send("text=" + text.value);

}

function openForm() {
    document.getElementById("myForm").style.display = "block";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}

