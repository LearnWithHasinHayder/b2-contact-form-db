<?php 
$data = ['9apple ','10 ',' 16orrage '];

// $clean = [];
// foreach($data as $item){
//     $clean[] = intval($item);
// }
// print_r($clean);

$clean = array_map('intval',$data);
 print_r($clean);

 trim(' apples  ');

// echo filter_var('orrage16',FILTER_SANITIZE_NUMBER_INT);
print_r(explode(",","phone,address,size"));