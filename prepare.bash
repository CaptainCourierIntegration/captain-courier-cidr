#! /bin/bash

mkdir -p ./res
touch res/credentials.yml
ls -r ./

echo "ParcelForce:" >> ${credentialsFile}
echo "    username: ${parcelForceUsername}" >> ${credentialsFile}
echo "    password: ${parcelForcePassword}" >> ${credentialsFile}
echo "P4D:" >> res/credentials.yml
echo "    username: ${p4dUsername}" >> ${credentialsFile}
echo "    apiKey: ${p4dApiKey}" >> ${credentialsFile}

