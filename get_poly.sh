#!/bin/bash

minlevel=6
maxlevel=8

if [ ! -e "apikey" ]
then
  echo "Please, fill apikey file with your OSM API key"
  echo "If file don't exists create it"
  exit
fi


apikey=$(cat "apikey")   


if [ -z "$1" ] && [ -z "$2" ]
then

    echo "Usage: ./get_poly.sh zipname country_code [level_from level_to]"
    exit

fi


if [ ! -z "$3" ] && [ ! -z "$4" ]
then

    minlevel=$3
    maxlevel=$4
fi

#mkdir tmp
#cd tmp

curl -f -o $1.zip --url "https://osm.wno-edv-service.de/boundaries/exportBoundaries?apiversion=1.0&apikey=$apikey&exportFormat=poly&exportLayout=split&exportAreas=land&from_al=$minlevel&to_al=$maxlevel&union=false&selected=$2"

echo "DONE!"
