<?php

$POLY_DIR="POLY";
$multi=180;

$paths="\n";  
$viewbox=array(10000,10000,0,0);
$minmax=array(10000,10000,0,0);
    
$d=dir($POLY_DIR);
while($fr=$d->read()){
if(substr($fr,-4)=="poly"){
    $coords = array();
    $of=fopen("$POLY_DIR/$fr","r");
    
    $path_name = array_slice(explode(" ",fgets($of)),2);
    
    $paths.="<path id=\"".trim(implode("_",$path_name))."\"".
            " style=\"fill:#". str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."\"\n".
            "d=\"M ";

    while($line = fgets($of)){
        
        $exp=explode(" ", trim($line));
        
        if(count($exp)>1){
            if(count($exp)>2 ){
            $coords[] = " z M \n";
            }else{
            $xy=array(floatval($exp[0])*$multi,floatval($exp[1])*$multi);
            $coords[] = implode(",",$xy);
            $minmax[0]=min($minmax[0],$xy[0]);
            $minmax[1]=min($minmax[1],$xy[1]);
            $minmax[2]=max($minmax[2],$xy[0]);
            $minmax[3]=max($minmax[3],$xy[1]);
            }
        }
        
    }
    
    
    fclose($of);
    $paths.=implode(" ",$coords)." z\"/>\n";
}
}

    $viewbox[0]=$minmax[0];
    $viewbox[1]=$minmax[1];
    $viewbox[2]=$minmax[2]-$minmax[0];
    $viewbox[3]=$minmax[3]-$minmax[1];
    
header('Content-Type: image/svg+xml');

$header="<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>\n".
        "<svg width=\"".$viewbox[2]."\" height=\"".$viewbox[3]."\" ".
        "version=\"1.1\" ".
        "xmlns=\"http://www.w3.org/2000/svg\" ";
 
$header.="\nviewBox=\"".implode(" ",$viewbox)."\"";

$header.="><g transform=\"translate(0,".($minmax[1]+$minmax[3]).") scale(1,-1)\">\n";

$footer="</g></svg>";

echo $header."\n".
        $paths."\n".
        $footer;
?>
