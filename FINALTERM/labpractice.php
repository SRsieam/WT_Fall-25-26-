<?php
$_GET['extra'] = 5;
$foods = [
"Burger"=>120, "Biriyani"=>250, "Tehari"=>80,
"Pizza"=>300, "Sandwich"=>150
];
function foodCheck($arr, $add) {
$i = 1;
foreach($arr as $item=>$price) {
$newPrice = $price + $add;
if($i == 1) {
// print length of food name
echo strlen($item)."\n";
} elseif($i == 2) {
// print first 3 letters
echo substr($item,0,3)."\n";
} elseif($i == 3) {
// print price with extra added
echo $newPrice."\n";
} elseif($i == 4) {
// print "Expensive" if > 250 else "Affordable"
echo ($newPrice > 250 ? "Expensive" : "Affordable")."\n";
} else {
// print reversed name
echo strrev($item)."\n";
}
$i++;
}
}
foodCheck($foods, $_GET['extra']);
?>