#!/usr/bin/env bash

TIMESTAMP=$(date +"%s");
DATE=$(date +"%Y-%m-%d");
RESOLUTION="960x720";
APPENDIX="";

helpFunction()
{
   echo ""
   echo "Usage: $0 -a my-appendix -b /var/www -r 960x720"
   echo ""
   echo "\t-a Filename appendix - Eg. my-appendix"
   echo "\t-b Image storage base path - Eg. /var/www"
   echo "\t-r Image resolution - Eg. 960x720"
   echo ""
   exit 1 # Exit script after printing help
}
BASEPATH="/var/www/harvester/data/webcam/";
while getopts "a:b:r:?:" opt
do
   case "$opt" in
      a ) APPENDIX="$OPTARG" ;;
      b ) BASEPATH="$OPTARG" ;;
      r ) PARAM_R="$OPTARG" ;;
      ? ) helpFunction ;; # Print helpFunction in case parameter is non-existent
   esac
done;
IMGPATH=${BASEPATH}""${DATE};
FILENAME=${IMGPATH}"/"${TIMESTAMP}""${APPENDIX}"-capture.jpg";

if [ ! -e ${IMGPATH} ]
then
    mkdir ${IMGPATH}
fi;


fswebcam -r ${RESOLUTION} --no-banner ${FILENAME}
