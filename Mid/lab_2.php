<!DOCTYPE html>
<html>
<head>
  <title>Paricpant Registration Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      background-color: #f0f8ff;
    }

    h2 {
      text-align: center;
      color: #003366;
    }

    form {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 10px;
      width: 300px;
      margin: 0 auto;
    }

    input {
      width: 100%;
      padding: 8px;
      margin-top: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    button {
      width: 40%;
      padding: 8px;
      margin-top: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      background-color: #003366;
      color: white;
      cursor: pointer;
    }

    button:hover {
      background-color: #0055aa;
    }

    #output {
      margin-top: 20px;
      text-align: center;
      font-size: 16px;
      color: #003366;
    }

    #error {
      margin-top: 10px;
      color: blue;
      text-align: center;
    }

  </style>
</head>
<body>

  <h2>Registration Form</h2>

  <form onsubmit="return handleSubmit()">
    <label="name">Full Name:</label>
    <input type="text" id="name" />

    <label="email">Email:</label>
    <input type="email" id="email" />

    <label="phoneNumber">Phone Number:</label>
    <input type="text" id="phone" />

    <label="passord">Password:</label>
    <input type="password" id="password" />

    <label"confirmPassord>Confirm Password:</label>
    <input type="password" id="confirmPassword" />

    <button type="submit">Register</button>
  </form>


  <div id="error"></div>
  <div id="output"></div>


  <script>
    function handleSubmit() {
      var name = document.getElementById("name").value.trim();
      var email = document.getElementById("email").value.trim();
      var phone = document.getElementById("phone").value.trim();
      var password = document.getElementById("password").value;
      var confirmPassword = document.getElementById("confirmPassword").value;

      var errorDiv = document.getElementById("error");
      var outputDiv = document.getElementById("output");

      errorDiv.innerHTML = "";
      outputDiv.innerHTML = "";


      if (name === "" || email === "" || phone === "" || password === "" || confirmPassword === "") {
        errorDiv.innerHTML = "Please fill all required fields";
        return false;
      }

      if (!email.includes("@")) {
        errorDiv.innerHTML = "Email must contain '@'";
        return false;
      }

      if (isNaN(phone)) {
        errorDiv.innerHTML = "Phone number must be numeric";
        return false;
      }

      if (password !== confirmPassword) {
        errorDiv.innerHTML = "Passwords must be mached";
        return false;
      }

      outputDiv.innerHTML = `
        <strong>Registration Complete!</strong><br><br>
        Name: ${name}<br>
        Email: ${email}<br>
        Phone: ${phone}
      `;

      return false; 
    }
  </script>

</body>
</html>
