window.onload=function(){
const password = document.getElementById("password");
const email = document.getElementById("email");
const age = document.getElementById("age");
const name = document.getElementById("name");
const password_icon =document.getElementById("password-status");
const email_icon =document.getElementById("email-status");
const age_icon =document.getElementById("age-status");
const name_icon =document.getElementById("name-status");
const genderSelect = document.getElementById("gender");


login_button=document.getElementById("login-button");
login_button.addEventListener("click",validate);


function validatePassword(){
    if (password.value.length>8 && /[ -/:-@[-`{-~]/.test(password.value) && /[A-Z]/.test(password.value) ){
        password_icon.classList.remove("fa-xmark");
        password_icon.classList.add("fa-check");
        password_correct=true;
    }
    else{
        password_icon.classList.add("fa-xmark");
        password_icon.classList.remove("fa-check");
        password_correct=false;
    }
}
function validateAge(){
    if (age.value>18 ){
        console.log(">")
        age_icon.classList.remove("fa-xmark");
        age_icon.classList.add("fa-check");
        age_correct=true;
    }
    else{
        console.log("<")
        age_icon.classList.add("fa-xmark");
        age_icon.classList.remove("fa-check");
        age_correct=false;
    }
}
function validateName(){
    if (name.value.length>2 ){
        name_icon.classList.remove("fa-xmark");
        name_icon.classList.add("fa-check");
        name_correct=true;
    }
    else{
        name_icon.classList.add("fa-xmark");
        name_icon.classList.remove("fa-check");
        name_correct=false;
    }
}


function validateEmail(){
    if (email.value.trim().match(/[^\s@]+@[^\s@]+\.[^\s@]+/gi)){
        email_icon.classList.remove("fa-xmark");
        email_icon.classList.add("fa-check");
        email_correct=true;

    }
    else{
        email_icon.classList.remove("fa-check");
        email_icon.classList.add("fa-xmark");
        email_correct=false;

    }
}

function validate (){
    function toggleClassShake(){
        login_button.classList.toggle("shake");
    }
    validatePassword();
    validateEmail();
    validateAge();
    validateName();

    if (password_correct && email_correct && name_correct && age_correct){
        let selectedGender = genderSelect.value;
        let data = new FormData();
        data.append('email', email.value);
        data.append('password', password.value);
        data.append('age', age.value);
        data.append('name', name.value);
        data.append('gender', selectedGender);
        console.log(data);

        axios.post('http://localhost:8000/api/register', data).then(function (res) {
            console.log(res.data)

            // localStorage.setItem('user', JSON.stringify(res.data.user));
            
            // localStorage.setItem('token', res.data.authorisation.token);

            // window.location.href = '/userprofile.html';

            }).catch(function (err) {
                console.log(err);
                alert('Incorrect email or password');
            })
    }
    else{
        login_button.classList.toggle("shake");
        setTimeout(toggleClassShake, 500 ) ;
    }
}


}