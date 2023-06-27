const validation = new JustValidate("#signup");

validation
    .addField("#name", [
        {
            rule: "required"
        }
    ])
    .addField("#email", [
        {
            rule: "required"
        },
        {
            rule: "email"
        },
        {
            validator: (value) => () => {
                return fetch("validate-email.php?email=" + encodeURIComponent(value))
                       .then(function(response) {
                           return response.json();
                       })
                       .then(function(json) {
                           return json.available;
                       });
            },
            errorMessage: "email already taken"
        }
    ])
    .addField("#password", [
        {
            rule: "required"
        },
        {
            rule: "password"
        }
    ])
    // .addField("#password_confirmation", [
    //     {
    //         validator: (value, fields) => {
    //             return value === fields["#password"].value;
    //         },
    //         errorMessage: "Passwords should match"
    //     }
    // ])

    //In this revised code, we're using document.querySelector("#password").value to get the value of the password field directly. 
    //The querySelector method allows us to select elements from the document using CSS selectors. 
    //Here, we're using the ID selector (#password) to get the password input field.
    .addField("#password_confirmation", [
        {
            validator: (value) => {
                const passwordValue = document.querySelector("#password").value;
                return value === passwordValue;
            },
            errorMessage: "Passwords should match"
        }
    ])
    
    
    .onSuccess((event) => {
        document.getElementById("signup").submit();
    });
    
    
    
    
    
    
    
    
    
    
    
    
    
