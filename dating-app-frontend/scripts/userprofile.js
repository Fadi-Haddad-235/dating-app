window.onload=function(){
    let nameElement = document.getElementById("name");
    let emailElement = document.getElementById("email");
    let ageElement = document.getElementById("age");
    let locationElement = document.getElementById("location");
    let bioElement = document.getElementById("bio");
    let pictureElement = document.getElementById("user_picture");
    let edit_profile =document.getElementById("edit_profile")
    edit_profile.addEventListener("click",()=>{location.href = "editprofile.html";})
    
    let headers= {'Authorization': 'Bearer ' + localStorage.getItem('token')};

    axios.post("http://localhost:8000/api/profile",null, { headers })
        .then(response => {
            // console.log(response);
            let user = response.data.users[0];
            let name = user.name;
            let email = user.email;
            let location = user.location;
            let age = user.age;
            let bio = user.bio;
            let profile_picture = user.profile_picture;
            nameElement.innerText = name;
            emailElement.innerHTML = email;
            ageElement.innerHTML = age;
            locationElement.innerHTML = location;
            bioElement.innerHTML = bio;
            pictureElement.src = "images/"+profile_picture;
        })
        .catch(error => {
            console.log(error);
        });
}
