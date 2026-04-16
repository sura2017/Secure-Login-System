// Function to reset the form fields
function clearForm(formId) {
    document.getElementById(formId).reset();
    
    // Also clear the red error message if it exists
    const errorDisplay = document.getElementById('passError');
    if (errorDisplay) {
        errorDisplay.innerText = "";
    }
}

// Logic to check Password Strength before the form is sent to PHP
document.addEventListener('DOMContentLoaded', function() {
    const regForm = document.getElementById('rForm');

    // We only run this if we are on the registration page (where rForm exists)
    if (regForm) {
        regForm.addEventListener('submit', function(event) {
            const password = document.getElementById('regPassword').value;
            const errorDisplay = document.getElementById('passError');

            /*
               Password Rules (Regex):
               - (?=.*[a-z]) : Must have at least one lowercase letter
               - (?=.*[A-Z]) : Must have at least one uppercase letter
               - (?=.*\d)    : Must have at least one number
               - (?=.*[@$!%*?&]) : Must have at least one special symbol
               - {8,}        : Must be at least 8 characters long
            */
            const strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!strongPasswordRegex.test(password)) {
                // STOP the form from submitting
                event.preventDefault(); 
                
                // Show the error message in red
                errorDisplay.innerText = "❌ Password too weak! Must be 8+ characters and include Uppercase, Lowercase, Number, and a Symbol (@$!%*?&).";
            } else {
                // Clear the error message if the password is good
                errorDisplay.innerText = ""; 
            }
        });
    }
});