document.addEventListener("DOMContentLoaded", function () {
    var form = document.forms["register"];
    var passwordInput = form.elements["password"];
    var confirmPasswordInput = form.elements["confirm-password"];
    var passwordError = document.getElementById("password-error");
    var emailInput = form.elements["email"];
    var emailError = document.getElementById("email-error");
  
    form.addEventListener("submit", function (event) {
      var password = passwordInput.value;
      var confirmPassword = confirmPasswordInput.value;
  
      // Check for minimum password length
      if (password.length < 3) {
        passwordError.textContent = "Password must be at least 3 characters long.";
        setPasswordBorderColor(passwordInput, "weak");
        setPasswordBorderColor(confirmPasswordInput, "weak");
        event.preventDefault(); // Prevent form submission
      } else if (password !== confirmPassword) {
        passwordError.textContent = "Passwords do not match. Please try again.";
        setPasswordBorderColor(passwordInput, "mismatch");
        setPasswordBorderColor(confirmPasswordInput, "mismatch");
        event.preventDefault(); // Prevent form submission
      } else {
        passwordError.textContent = ""; // Clear the error message
        setPasswordBorderColor(passwordInput, "match");
        setPasswordBorderColor(confirmPasswordInput, "match");
      }
    });
  
    // Add an input event listener to clear the error message as the user types
    passwordInput.addEventListener("input", function () {
      passwordError.textContent = "";
      setPasswordBorderColor(passwordInput, getPasswordStrength(passwordInput.value));
      setPasswordBorderColor(confirmPasswordInput, getPasswordStrength(confirmPasswordInput.value));
    });
  
    confirmPasswordInput.addEventListener("input", function () {
      passwordError.textContent = "";
      setPasswordBorderColor(passwordInput, getPasswordStrength(passwordInput.value));
      setPasswordBorderColor(confirmPasswordInput, getPasswordStrength(confirmPasswordInput.value));
    });
  
    emailInput.addEventListener("input", function () {
      var email = emailInput.value;
      if (!isValidEmail(email)) {
        emailError.textContent = "Invalid email address.";
        setEmailBorderColor(emailInput, "invalid");
      } else {
        emailError.textContent = ""; // Clear the error message
        setEmailBorderColor(emailInput, "valid");
      }
    });
  
    function setPasswordBorderColor(input, strength) {
      switch (strength) {
        case "weak":
          input.style.border = "2px solid red";
          break;
        case "medium":
          input.style.border = "2px solid orange";
          break;
        case "strong":
          input.style.border = "2px solid green";
          break;
        case "match":
          input.style.border = "2px solid green";
          break;
        case "mismatch":
          input.style.border = "2px solid red";
          break;
        default:
          input.style.border = "";
      }
    }
  
    function getPasswordStrength(password) {
      if (password.length < 5) {
        return "weak";
      } else if (password.length < 8) {
        return "medium";
      } else {
        return "strong";
      }
    }
  
    function isValidEmail(email) {
      // A simple email validation regex (you may want to use a more robust solution)
      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    }
  
    function setEmailBorderColor(input, validity) {
      switch (validity) {
        case "valid":
          input.style.border = "2px solid green";
          break;
        case "invalid":
          input.style.border = "2px solid red";
          break;
        default:
          input.style.border = "";
      }
    }
  });
  