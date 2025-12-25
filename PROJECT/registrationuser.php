<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>

    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-box {
            background: white;
            padding: 20px;
            width: 360px;
            border-radius: 8px;
            box-shadow: 0 0 10px gray;
        }

        h2 {
            text-align: center;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
        }

        button {
            width: 100%;
            padding: 10px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: darkgreen;
        }

        .error {
            color: red;
            text-align: center;
            font-size: 14px;
        }

        .result {
            margin-top: 15px;
            padding: 10px;
            background: #e8f5e9;
            font-size: 14px;
        }
    </style>
</head>

<body>

<div class="form-box">
    <h2>Registration</h2>

    <p id="msg" class="error"></p>

    <input type="text" id="name" placeholder="Full Name">
    <input type="email" id="email" placeholder="Email">

    <select id="division" onchange="loadDistricts()">
        <option value="Dhaka">Dhaka</option>
        <option value="Chattogram">Chattogram</option>
        <option value="Rajshahi">Rajshahi</option>
        <option value="Khulna">Khulna</option>
        <option value="Barishal">Barishal</option>
        <option value="Sylhet">Sylhet</option>
        <option value="Rangpur">Rangpur</option>
        <option value="Mymensingh">Mymensingh</option>
    </select>

    <select id="area">
        <option value="">Select District</option>
    </select>

    <input type="date" id="dob">
    <input type="tel" id="phone" placeholder="Phone Number">

    <button onclick="register()">Register</button>

    <!-- Show Registration Data -->
    <div id="result" class="result"></div>
</div>

<script>
    function loadDistricts() {
        var division = document.getElementById("division").value;
        var area = document.getElementById("area");

        area.innerHTML = "<option value=''>Select District</option>";

        if (division == "Dhaka") {
            var districts = ["Dhaka", "Gazipur", "Narayanganj", "Narsingdi", "Tangail", "Kishoreganj", "Munshiganj", "Manikganj", "Faridpur", "Madaripur", "Shariatpur", "Gopalganj", "Rajbari"];
        } else if (division == "Chattogram") {
            var districts = ["Chattogram", "Cox's Bazar", "Comilla", "Noakhali", "Feni", "Chandpur", "Bandarban", "Rangamati", "Khagrachari", "Lakshmipur", "Brahmanbaria"];
        } else if (division == "Rajshahi") {
            var districts = ["Rajshahi", "Bogura", "Naogaon", "Natore", "Pabna", "Sirajganj", "Joypurhat", "Chapainawabganj"];
        } else if (division == "Khulna") {
            var districts = ["Khulna", "Jashore", "Satkhira", "Bagerhat", "Jhenaidah", "Magura", "Narail", "Chuadanga", "Meherpur"];
        } else if (division == "Barishal") {
            var districts = ["Barishal", "Bhola", "Patuakhali", "Pirojpur", "Jhalokathi", "Barguna"];
        } else if (division == "Sylhet") {
            var districts = ["Sylhet", "Moulvibazar", "Habiganj", "Sunamganj"];
        } else if (division == "Rangpur") {
            var districts = ["Rangpur", "Dinajpur", "Kurigram", "Gaibandha", "Lalmonirhat", "Nilphamari", "Panchagarh", "Thakurgaon"];
        } else if (division == "Mymensingh") {
            var districts = ["Mymensingh", "Jamalpur", "Netrokona", "Sherpur"];
        }

        for (var i = 0; i < districts.length; i++) {
            var option = document.createElement("option");
            option.text = districts[i];
            area.add(option);
        }
    }

    function register() {
        var name = document.getElementById("name").value;
        var email = document.getElementById("email").value;
        var division = document.getElementById("division").value;
        var area = document.getElementById("area").value;
        var dob = document.getElementById("dob").value;
        var phone = document.getElementById("phone").value;
        var msg = document.getElementById("msg");
        var result = document.getElementById("result");

        if (name == "" || email == "" || division == "" || area == "" || dob == "" || phone == "") {
            msg.innerText = "Please fill all details";
            result.innerHTML = "";
        } else {
            msg.innerText = "";
            result.innerHTML =
                "<b>Registered Data:</b><br>" +
                "Name: " + name + "<br>" +
                "Email: " + email + "<br>" +
                "Division: " + division + "<br>" +
                "District: " + area + "<br>" +
                "DOB: " + dob + "<br>" +
                "Phone: " + phone;
        }
    }
</script>

</body>
</html>
