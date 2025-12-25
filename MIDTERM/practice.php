<!DOCTYPE html>
<html>
<head>
  <title>Event Registration & Activities</title>
</head>
<body>

  <h1>Event Registration & Activities</h1>

  <h2>Participant Registration</h2>

  <form onsubmit="return registerUser()">
    Full Name: <br>
    <input type="text" id="name"><br><br>

    Email: <br>
    <input type="text" id="email"><br><br>

    Phone Number: <br>
    <input type="text" id="phone"><br><br>

    Password: <br>
    <input type="password" id="password"><br><br>

    Confirm Password: <br>
    <input type="password" id="confirmPassword"><br><br>

    <button type="submit">Register</button>
  </form>

  <h3 id="result"></h3>

  <h2>Activity Selection</h2>

  Activity Name:  
  <input type="text" id="activityName">
  <button onclick="addActivity()">Add Activity</button>

  <h3>Activities Table</h3>

  <!-- Activity Table -->
  <table border="1" width="50%" cellpadding="8">
    <thead>
      <tr>
        <th>Activity Name</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="activityTable"></tbody>
  </table>

  <script>
    // ---------------------------
    // Registration Function
    // ---------------------------
    function registerUser() {
      var name = document.getElementById("name").value.trim();
      var email = document.getElementById("email").value.trim();
      var phone = document.getElementById("phone").value.trim();
      var password = document.getElementById("password").value;
      var confirmPassword = document.getElementById("confirmPassword").value;

      if (name === "" || email === "" || phone === "" || password === "" || confirmPassword === "") {
        alert("All fields are required.");
        return false;
      }

      if (!email.includes("@")) {
        alert("Invalid Email. Must contain @");
        return false;
      }

      if (!/^\d+$/.test(phone)) {
        alert("Phone number must contain digits only.");
        return false;
      }

      if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
      }

      document.getElementById("result").innerHTML =
        "Registration Successful!<br>" +
        "Name: " + name + "<br>" +
        "Email: " + email + "<br>" +
        "Phone: " + phone;

      return false;
    }

    // ---------------------------
    // Add Activity to Table
    // ---------------------------
    function addActivity() {
      var activityName = document.getElementById("activityName").value.trim();

      if (activityName === "") {
        alert("Activity name cannot be empty.");
        return;
      }

      var table = document.getElementById("activityTable");

      // Create new row
      var row = document.createElement("tr");

      // Create Activity cell
      var activityCell = document.createElement("td");
      activityCell.textContent = activityName;

      // Create Remove button cell
      var actionCell = document.createElement("td");

      var removeBtn = document.createElement("button");
      removeBtn.textContent = "Remove";

      // Remove row event
      removeBtn.onclick = function() {
        table.removeChild(row);
      };

      actionCell.appendChild(removeBtn);

      // Add cells to row
      row.appendChild(activityCell);
      row.appendChild(actionCell);

      // Add row to table
      table.appendChild(row);

      // Clear input field
      document.getElementById("activityName").value = "";
    }
  </script>

</body>
</html>
