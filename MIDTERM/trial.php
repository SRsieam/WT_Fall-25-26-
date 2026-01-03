<!DOCTYPE html>
<html>
<head>
<title>Event Management System</title>
</head>
<body>

    <h2>Participant Registration</h2>

    <label>Name: </label>
    <input type="text" id="name"><br><br>

    <label>Age: </label>
    <input type="text" id="age"><br><br>

    <!-- Gender Radio Buttons -->
    <label>Gender: </label>
    <input type="radio" name="gender" value="Male"> Male
    <input type="radio" name="gender" value="Female"> Female
    <br><br>

    <!-- Hobby Checkbox -->
    <label>Hobby: </label>
    <input type="checkbox" id="hobby" value="Sports"> Sports
    <input type="checkbox" id="hobby" value="asif"> asif
    <br><br>


    <button onclick="addParticipant()">Submit</button>

    <h3>Participant List</h3>

    <table id="participantTable" border="1" cellpadding="8">
        <tr>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Hobby</th>
            <th>Action</th>
        </tr>
    </table>

    <script>
        function addParticipant() 
        {
            let name = document.getElementById("name").value.trim();
            let age = document.getElementById("age").value.trim();

            // Name validation (A-Z only)
            let namePattern = /^[A-Za-z]+$/;
            if (!namePattern.test(name)) {
                alert("Invalid name! Use only letters A–Z with no spaces.");
                return;
            }

            // Age validation
            age = Number(age);
            if (isNaN(age) || age < 18 || age > 100) {
                alert("Age must be a number between 18 and 100!");
                return;
            }

            // Gender validation
            let genderInput = document.querySelector('input[name="gender"]:checked');
            if (!genderInput) {
                alert("Please select Gender!");
                return;
            }
            let gender = genderInput.value;

            // Hobby checkbox
            let hobby = document.getElementById("hobby").checked ? "Sports" : "None";

            // Table reference
            let table = document.getElementById("participantTable");

            // Insert new row
            let row = table.insertRow(-1);

            let nameCell = row.insertCell(0);
            let ageCell = row.insertCell(1);
            let genderCell = row.insertCell(2);
            let hobbyCell = row.insertCell(3);
            let actionCell = row.insertCell(4);

            nameCell.textContent = name;
            ageCell.textContent = age;
            genderCell.textContent = gender;
            hobbyCell.textContent = hobby;

            // Remove button
            let removeBtn = document.createElement("button");
            removeBtn.textContent = "Remove";
            removeBtn.onclick = function() {
                table.deleteRow(row.rowIndex);
            };
            actionCell.appendChild(removeBtn);

            // Row color based on age
            if (age < 30) {
                row.style.backgroundColor = "lightblue";
            } else {
                row.style.backgroundColor = "lightyellow";
            }

            // Clear fields
            document.getElementById("name").value = "";
            document.getElementById("age").value = "";
            document.getElementById("hobby").checked = false;
            genderInput.checked = false;
        }
    </script>

</body>
</html>
