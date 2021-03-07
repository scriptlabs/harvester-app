#!/bin/bash

TIMESTAMP=$(date +"%s")
DATE=$(date +"%Y-%m-%d")
TIME=$(date +"%T")
RESOLUTION="960x720"
IMGPATH="/var/www/harvester/data/webcam/live"

if [[ ! -e ${IMGPATH} ]]; then
    mkdir ${IMGPATH}
fi;
 
fswebcam -r ${RESOLUTION} --no-banner ${IMGPATH}/${TIMESTAMP}-capture.jpg
