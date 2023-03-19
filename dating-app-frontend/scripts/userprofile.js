window.onload=function(){
    let headers= {
        'Authorization': 'Bearer ' + localStorage.getItem('token')
      }
      console.log(headers)
axios.post("http://localhost:8000/api/profile",null, { headers })
    .then(response => {
        console.log(response)
      })
      .catch(error => {
        console.log(error);
      });
}
