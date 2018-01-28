function check(val) {
    
    if (val < 10) {
        return "0" + val;
    }
    return val;
}
   
function updateTime() {
  
    var time = new Date();
    var hours = check(time.getHours());
    var minutes = check(time.getMinutes());
    var seconds = check(time.getSeconds());
    var days = check(time.getDate());
    var month = time.getMonth() + 1;
    var year = time.getFullYear();
    
    var t = hours + ":" + minutes + ":" + seconds
    + " (" + days + "." + month + " " + year + ")";
    
    setTimeout("updateTime()",1000);        
    document.getElementById('time').innerHTML = t;
}

updateTime();
