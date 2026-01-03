function loadDistricts() {

    var division = document.getElementById("division").value;
    var districtBox = document.getElementById("district");
    districtBox.innerHTML = "<option value=''>Select District</option>";

    var data = {
        Dhaka: ["Dhaka","Gazipur","Narayanganj","Narsingdi","Tangail",
                "Kishoreganj","Munshiganj","Manikganj","Faridpur",
                "Madaripur","Shariatpur","Gopalganj","Rajbari"],

        Chattogram: ["Chattogram","Cox's Bazar","Comilla","Noakhali","Feni",
                     "Chandpur","Bandarban","Rangamati","Khagrachari",
                     "Lakshmipur","Brahmanbaria"],

        Rajshahi: ["Rajshahi","Bogura","Naogaon","Natore","Pabna",
                   "Sirajganj","Joypurhat","Chapainawabganj"],

        Khulna: ["Khulna","Jashore","Satkhira","Bagerhat","Jhenaidah",
                 "Magura","Narail","Chuadanga","Meherpur"],

        Barishal: ["Barishal","Bhola","Patuakhali","Pirojpur",
                   "Jhalokathi","Barguna"],

        Sylhet: ["Sylhet","Moulvibazar","Habiganj","Sunamganj"],

        Rangpur: ["Rangpur","Dinajpur","Kurigram","Gaibandha",
                  "Lalmonirhat","Nilphamari","Panchagarh","Thakurgaon"],

        Mymensingh: ["Mymensingh","Jamalpur","Netrokona","Sherpur"]
    };

    if (data[division]) {
        data[division].forEach(d => {
            let opt = document.createElement("option");
            opt.value = opt.text = d;
            districtBox.add(opt);
        });
    }
}
