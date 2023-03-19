window.onload=function(){
    
    let nameElement = document.getElementById("name");
    let emailElement = document.getElementById("email");
    let ageElement = document.getElementById("age");
    let locationElement = document.getElementById("location");
    let bioElement = document.getElementById("bio");
    
    let btnElement = document.getElementById("submit_btn");

    
    getInfo = function () {
        let headers= {'Authorization': 'Bearer ' + localStorage.getItem('token')};
        axios.post("http://localhost:8000/api/profile",null, { headers })
            .then(response => {
                let user = response.data.users[0];
                let name = user.name;
                let email = user.email;
                let location = user.location;
                let age = user.age;
                let bio = user.bio;
                nameElement.value = name.toUpperCase();
                emailElement.value = email;
                ageElement.value = age;
                locationElement.value = location;
                bioElement.value = bio;
            })
            .catch(error => {
                console.log(error);
            });
    }
    getInfo();
    
    submitInfo = function (){
            let name = nameElement.value;
            let email = emailElement.value;
            let location = locationElement.value;
            let age = ageElement.value;
            let bio = bioElement.value;
            let data= {name,email,location,age,bio};
            console.log(data);
        let headers= {'Authorization': 'Bearer ' + localStorage.getItem('token')};
        axios.post("http://localhost:8000/api/editprofile",data, { headers })
            .then(response => {
                alert("you have successfully changed your data")
            })
            .catch(error => {
                console.log(error);
            });
    }
    btnElement.addEventListener("click",submitInfo);
}
