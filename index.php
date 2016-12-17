<?php
header('Content-Type: image/svg+xml');
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>\n".
        "<svg width=\"400\" height=\"200\" ".
        "version=\"1.1\" ".
        "xmlns=\"http://www.w3.org/2000/svg\" ".
        "viewBox=\"5 94 10 10\">\n";

$POLY_DIR="POLY";
        
$d=dir($POLY_DIR);
while($fr=$d->read()){
if(substr($fr,-4)=="poly"){
// for($i=4;$i<=4;$i++){
    $coords = array();
    $of=fopen("$POLY_DIR/$fr","r");
//     $of=fopen("path$i.poly","r");

    $path_name = str_replace(array("Landkreis "," Kreis"),"",array_slice(explode(" ",fgets($of)),2));
    
    echo "<path id=\"".trim(implode("_",$path_name))."\"".
            " style=\"fill:#". str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."\"\n".
            "d=\"M ";

    while($line = fgets($of)){
        
        $exp=explode(" ", trim($line));
        
        if(count($exp)>1){
            if(count($exp)>2 ){
            $coords[] = " z M \n";
            }else{
            $coords[] = ((floatval($exp[0])*1)).",".(150-(floatval($exp[1])*1))." ";
            }
        }
        
    }

    fclose($of);

    echo implode(" ",$coords);

    echo " z\"/>\n";
}
}

echo "</svg>";
?>
