let username = "";
let password = "";
let email = "";
let phone = "";
let country = "";
let city = "";
let postalCode = "";
let countries = "";

$(function() {
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });
    showStep();
})

function showStep(step = 0, animate = true) {
    switch (step) {
        case 0:
            $("form").append(`
                <label for="username">Nombre de usuario: </label>
                <input type="text" id="username" name="username">
            `)
            break;
        case 1:
            $("form").append(`
                <label for="password">Contraseña: </label>
                <input type="password" id="password" name="password">
            `)
            $("#next-step").click(function() {
                password = $("#password").val()
                if (password.length < 8) {
                    showNotification("error", "La contraseña debe tener un mínimo de 8 carácteres")
                } else if (password.includes(";") || password.includes("--") || password.includes("/*") || password.includes("*/")) {
                    showNotification("error", "La contraseña contiene carácteres no permitidos")
                } else if (!/\d/.test(password)) { /* if password doesn't contain numbers */
                    showNotification("error", "La contraseña debe contener al menos un carácter numérico")
                } else if (!/[A-Z]/g.test(password)) { /* if password doesn't contain uppercase letters */
                    showNotification("error", "La contraseña debe contener al menos una mayúscula")
                } else if (!/[a-z]/g.test(password)) { /* if password doesn't contain lowercase letters */
                    showNotification("error", "La contraseña debe contener al menos una minúscula")
                } else if (!/[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/.test(password)) {/* if password doesn't contain special characters */
                    showNotification("error", "La contraseña debe contener al menos un carácter especial")
                } else {
                    showStep(step+1)
                }
            })
            break;
        case 2:
            $("form").append(`
                <label for="password2">Confirme la contraseña: </label>
                <input type="password" id="password2" name="password2">
            `)
            $("#next-step").click(function() {
                if ($("#password2").val() != password) {
                    showNotification("error", "Las contraseñas no coinciden")
                } else {
                    showStep(step+1)
                }
            })
            break;
        case 3:
            $("form").append(`
                <label for="email">Correo electrónico: </label>
                <input type="email" id="email" name="email">
            `)
            $("#next-step").click(function() {
                email = $("#email").val()
                if (!email.toLowerCase().match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) { /* if email address is not valid */
                    showNotification("error", "La dirección de correo electrónico no es válida")
                } else {
                    showStep(step+1)
                }
            })
            break;
        case 4:
            $("form").append(`
                <label for="phone">Introduzca su número de teléfono (con prefijo y sin espacios): </label>
                <input type="tel" id="phone" name="phone">
            `)
            $("#next-step").click(function() {
                phone = $("#phone").val()
                if (!/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im.test(phone)) {
                    showNotification("error", "El número de teléfono insertado no es válido")
                } else  if (Number.isInteger(phone) || phone.includes(";") || phone.includes("--") || phone.includes("/*") || phone.includes("*/")) {
                    showNotification("error", "El número de teléfono insertado contiene carácteres no permitidos")
                } else {
                    showStep(step+1)
                }
            })
            break;
        case 5:
            $("form").append(`
                <label for="country">País: </label>
                <input type="text" id="country" name="country">
            `)
            $("#next-step").click(function() {
                country = $("#country").val()
                if (country == "") {
                    showNotification("error", "Inserte un país") 
                } else if (country.includes(";") || country.includes("--") || country.includes("/*") || country.includes("*/")) {
                    showNotification("error", "El país insertado contiene carácteres no permitidos")
                } else {
                    showStep(step+1)
                }
            })
            break;
        case 6:
            $("form").append(`
                <label for="city">Ciudad: </label>
                <input type="text" id="city" name="city">
            `)
            $("#next-step").click(function() {
                city = $("#city").val()
                if (city.length == "") {
                    showNotification("error", "Inserte una ciudad") 
                } else if (city.includes(";") || city.includes("--") || city.includes("/*") || city.includes("*/")) {
                    showNotification("error", "La ciudad insertada contiene carácteres no permitidos")
                } else {
                    showStep(step+1)
                }
            })
            break;
        case 7:
            $("form").append(`
                <label for="postalCode">Código Postal: </label>
                <input type="number" id="postalCode" name="postalCode">
            `)
            $("#next-step").click(function() {
                postalCode = $("#postalCode").val()
                if (postalCode.length == 0) {
                    showNotification("error", "Inserte un código Postal")
                } else {
                    submitForm();
                }
            })
            break;
    }
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
        event.preventDefault();
        showStep(step+1)
        return false;
        }
    });
}

// Function to submit form since AJAX is not alllowed
function submitForm() {
    $("form").html(`
        <input type="hidden" name="username" value="${username}">
        <input type="hidden" name="password" value="${password}">
        <input type="hidden" name="email" value="${email}">
        <input type="hidden" name="phone" value="${phone}">
        <input type="hidden" name="country" value="${country}">
        <input type="hidden" name="city" value="${city}">
        <input type="hidden" name="postalCode" value="${postalCode}">
    `).submit();
}