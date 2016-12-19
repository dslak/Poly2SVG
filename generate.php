<?php

$POLY_DIR="POLY";
$multi=180;
$coeff=1.35;

// $paths=;  
$viewbox=array(10000,10000,0,0);
$minmax=array(10000,10000,0,0);
    
$d=dir($POLY_DIR);
while($fr=$d->read()){
if(substr($fr,-4)=="poly"){
    $coords = array();
    $of=fopen("$POLY_DIR/$fr","r");
    
    $path_name = array_slice(explode(" ",fgets($of)),2);
    $path_name = str_replace(" ","_",str_replace(".poly","",$fr));

    while($line = fgets($of)){
        
        $exp=explode(" ", trim($line));
        
        if($exp[0]!="END"){
        
        switch(count($exp)){
        
            case 1:
                $coords[] = " z M \n";
            break;
            
            case 2:
                $xy=array(floatval($exp[0])*$multi,floatval($exp[1])*$multi);
                $coords[] = implode(",",$xy);
                $minmax[0]=min($minmax[0],$xy[0]);
                $minmax[1]=min($minmax[1],$xy[1]);
                $minmax[2]=max($minmax[2],$xy[0]);
                $minmax[3]=max($minmax[3],$xy[1]);
            break;
            
            default:
                 $coords[] = " z M \n";
            
        }
        }
        
    }
    
    
    fclose($of);
    $paths[]="<path id=\"".trim($path_name)."\" ".
                "data-code=\"".trim($path_name)."\" ".
                "style=\"fill:#". str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT)."\"\n".
                "d=\"M ".implode(" ",$coords)." z\" ".
                "transform=\"scale(1,-$coeff)\"/>\n";
}
}

    $viewbox[0]=$minmax[0];
    $viewbox[1]=$minmax[1];
    $viewbox[2]=$minmax[2]-$minmax[0];
    $viewbox[3]=($minmax[3]-$minmax[1])*$coeff;
    
header('Content-Type: image/svg+xml');

$header="<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>\n".
        "<svg width=\"".$viewbox[2]."\" height=\"".$viewbox[3]."\" ".
        "version=\"1.1\" ".
        "xmlns=\"http://www.w3.org/2000/svg\" ";
 
$header.="\nviewBox=\"".implode(" ",$viewbox)."\"";

$header.="><g transform=\"translate(0,".($minmax[1]+($minmax[3])*$coeff).")\">\n";

$footer="</g></svg>";

echo $header."\n".
        implode("",$paths)."\n".
        $footer;
?>
