# Google-Drive-Bundle
Google Drive API Bundle for Symfony 4

# Features

- Authorize user via Google API
- Create a folder (recursively)
- Check if a folder exists
- Delete a file
- List files
- Copy a file to specific directory
- Upload a file
- Add "starred" flag to a file/folder

# Install

## Step 1: Download the Bundle

`composer require vatri/google-drive-bundle`

## Step 2: Download and configure credentials file

Download your JSON credentials file from Google Console to _/config_ folder within Symfony and add a variable to _.env_ file:

  `VATRI_DRIVE_CREDENTIALS_FILE=config/google-drive-api-client_secrets.json-example.json`

## Step 3: TODO
