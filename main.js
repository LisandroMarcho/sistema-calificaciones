var nb = false;

function navbar (navbar) {
    if(!nb){
        navbar.style.height = "100vh";
        nb = true;
    }else {
        navbar.style.height = "60px";
        nb = false;
    }
}