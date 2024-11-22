var Hclient_btn = document.getElementById("Hclient_btn");
var Hfreelancer_btn = document.getElementById("Hfreelancer_btn");
var Ccontainer = document.getElementById("Ccontainer");
var Fcontainer = document.getElementById("Fcontainer");
Hclient_btn.addEventListener('click',()=>{
    Ccontainer.style.display="block";
    Fcontainer.style.display="none";
});
Hfreelancer_btn.addEventListener('click',()=>{
    Ccontainer.style.display="none";
    Fcontainer.style.display="block";
});


function openStudentLoginModal() {
    var modal = document.getElementById("studentModal");
    modal.style.display = "block";
  }
  
  // Function to close the create modal
  function closeStudentModal() {
    var modal = document.getElementById("studentModal");
    modal.style.display = "none";
  }

    
let studenteyeicon = document.getElementById("studenteyeicon");
let studentpassword = document.getElementById("studentPassword");

studenteyeicon.onclick = function(){
    if(studentpassword.type == "password"){
        studentpassword.type = "text";
        studenteyeicon.src = "../images/hide.png";
    }
    else{
        studentpassword.type = "password";
        studenteyeicon.src = "../images/show.png";
    }
}

let teachereyeicon = document.getElementById("teachereyeicon");
let teacherpassword = document.getElementById("teacherPassword");

teachereyeicon.onclick = function(){
    if(teacherpassword.type == "password"){
        teacherpassword.type = "text";
        teachereyeicon.src = "../images/hide.png";
    }
    else{
        teacherpassword.type = "password";
        teachereyeicon.src = "../images/show.png";
    }
}


