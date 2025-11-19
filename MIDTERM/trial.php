<html>
 
<head>
<style>
.container {
max-width: 800px;
margin: 0 auto;
background-color: white;
padding: 30px;
border-radius: 8px;
box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
background-color: lightgrey;
}
 
body {
margin-left: 20%;
margin-right: 20%;
}
 
table {
margin-left: auto;
margin-right: auto;
 
}
 
.center-text {
text-align: center;
}
 
.right-alignment {
text-align: right;
font-weight: bold;
}
.left-alignment {
text-align: left;
font-weight: bold;
}
 
#header1 {
font-size: small;
}
 
#header2 {
font-weight: bold;
}
 
.sm {
 
font-size: 15px;
}
 
.amount-container {
display: flex;
align-items: left;
justify-content: flex-start;
gap: 8px;
}
 
 
.amount-container input {
width: 100px;
box-sizing: border-box;
padding: 6px 8px;
min-width: 0;
font-size: 14px;
}
 
 
.testbox {
width: 60px !important;
height: 20px;
padding: 0;
 
}
.testbox1 {
width: 150px !important;
height: 20px;
padding: 0;
 
}
.input-noborder {
border: none;
outline: none;
width: 800px;
height: 15px;
background-color: white;
font-size: 14px;
}
.btn {
background-color: white;
color: black;
border: none;
border-radius: 20px;
padding: 10px 30px;
font-size: 15px;
cursor: pointer;
margin: 5px;
transition: background-color 0.3s ease;
}
.input-noborder1 {
border: none;
outline: none;
width: 100px;
height: 15px;
background-color: lightgray;
font-size: 14px;
 
}
 
 
 
 
/* .amount-container input {
width: 100px;
}
 
.testbox {
width: 20x;
} */
 
 
</style>
 
<script>
console.log("Hello world from js console");
var abc = "Education";
console.log("The value of abc is ..", abc);
var abc;
console.log("The value of abc is still..", abc);
 
var first_name = "";
function validateForm() {
first_name = document.getElementById("first_name").value;
console.log("first name is...", first_name);
var line = "Hello " + first_name;
alert(line);
}
 
</script>
</head>
 
<body>
<div class="container">
<p id="header1"><span style="color:red">*</span> -Denotes Required Information</p>
<p> <span id="header2">> 1 Donation</span> <span> > 2 Confirmation</span> <span> > Thank You! </span></s></p>
<form action="" onsubmit="validateForm()" method="get">
<h1 style="color:red ;font-size:30px;"> Doner Infromation </h1>
<table>
 
<tr>
<td class="right-alignment">First Name <span style="color:red">*</span></td>
<td><input type="text" id="first_name" /></td>
</tr>
<tr>
<td class="right-alignment" name="last_name">Last Name <span style="color:red">*</span></td>
<td><input type="" /></td>
</tr>
<tr>
<td class="right-alignment">Company </td>
<td><input type="" /></td>
</tr>
<tr>
<td class="right-alignment">Address 1 <span style="color:red">*</span></td>
<td><input type="" /></td>
</tr>
<tr>
<td class="right-alignment">Address 2</td>
<td><input type="" /></td>
</tr>
<tr>
<td class="right-alignment">City <span style="color:red">*</span></td>
<td><input type="" /></td>
</tr>
<tr>
<td class="right-alignment">State <span style="color:red">*</span></td>
<td>
<select style="width:120px; height: 20px; border-radius: 5px;">
<option disabled selected>Select a Satate</option>
<option>Alabama</option>
<option>Idaho</option>
<option>California</option>
</select>
</td>
</tr>
 
<tr>
<td class="right-alignment">Zip Code <span style="color:red">*</span></td>
<td><input type="text" id="first_name" /></td>
</tr>
<tr>
<td class="right-alignment">Country <span style="color:red">*</span></td>
<td>
<select style="width: 250px; height: 20px; border-radius: 5px;">
<option disabled selected>Select a Country</option>
<option>Bangladesh</option>
<option>Indonesia</option>
<option>USA</option>
</select>
</td>
</tr>
 
<tr>
<td class="right-alignment">Phone</td>
<td><input type="" /></td>
</tr>
<tr>
<td class="right-alignment">Fax</td>
<td><input type="" /></td>
</tr>
<tr>
<td class="right-alignment">Email <span style="color:red">*</span></td>
<td><input type="text" id="first_name" /></td>
</tr>
</tr>
<td class="right-alignment">Donation Amount <span style="color:red">*</span></td>
<td>
<input type="radio" name="Donation Amount" value="None" />None
<input type="radio" name="Donation Amount" value="$50" />$50
<input type="radio" name="Donation Amount" value="$75" />$75
<input type="radio" name="Donation Amount" value="$100" />$100
<input type="radio" name="Donation Amount" value="$other" />$Other
</td>
</tr>
 
<tr>
<td class="sm" style="text-align: right;">
 
(Check a buttom or type in your amount)
</td>
<td class="right-alignment">
<div class="amount-container">
<label >Other Amount $</label>
<input type="text" class="testbox1" />
</div>
</td>
</tr>
 
<tr>
<td class="right-alignment">Recurring Donation </td>
<td colspan="2">
<input type="checkbox" /> I am interested in giving a regular basis.
</td>
</tr>
 
 
<tr>
<td class="sm" style="text-align: right;">
(Check if yes)
</tr>
<tr>
<td>
 
</td>
 
 
 
</table>
</form>
</div>
 
</body>
 
</html>