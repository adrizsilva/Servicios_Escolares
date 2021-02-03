
function acumular() {    
    var valor = parseInt(document.getElementById("flagdata").value);
    valor = isNaN(valor) ? 0 : valor;
    document.getElementById("flagdata").value = valor +1;
}

function showUser(str) {
  if (str == "") {
    document.getElementById("info").innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        //document.getElementById("info").style.display='block';
        document.getElementById("info").innerHTML = this.responseText;
        document.getElementById("info").style.display='block';
      }
    };
    xmlhttp.open("GET","getuser.php?q="+str,true);
    xmlhttp.send();
  }
}
    
