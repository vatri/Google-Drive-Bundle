# Google-Drive-Bundle

Google Drive API Bundle for Symfony 4

# Features

- Authorize via Google API
- Manage Google Drive files and folders
  - Create a folder (recursively)
  - Check if a folder exists
  - Delete a file
  - List files
  - Copy a file to specific directory
  - Upload a file
  - Add "starred" flag to a file/folder

Installation
============

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require vatri/google-drive-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require vatri/google-drive-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    App\Vatri\GoogleDriveBundle\VatriGoogleDriveBundle::class => ['dev' => true, 'test' => true],
];
```

# Usage

### 1. If you don't use Symfony Flex

If you don't use Symfony Flex, you will need to:

1. Add following variables to your _.env_ file:

```
VATRI_GOOGLE_DRIVE_CREDENTIALS_FILE=config/YOUR_FILENAME.json`
VATRI_GOOGLE_DRIVE_REDIRECT_AFTER_AUTH
```

**Don't forget to replace default ones with your values.**

2. Create _/config/routes/vatri_google_drive.yaml_ file with following contents:

```
vatri_google_drive:
    resource: '@VatriGoogleDriveBundle/Controller/'
    type:     annotation
```

### 2. Download and configure JSON credentials file

Download your JSON credentials file from Google Console to _/config_ folder within Symfony project.

Edit following variables in _.env_ file:

  `VATRI_GOOGLE_DRIVE_CREDENTIALS_FILE=config/google-drive-api-client_secrets.json-example.json`
  `VATRI_GOOGLE_DRIVE_REDIRECT_AFTER_AUTH=/path/to/your/route`

### 3. Check and use AuthController

1. Run

`php bin/console debug:router`

and check if you have a route like this:

```
vatri_google_drive_auth       ANY      ANY      ANY    <href=>/vatri_google_drive/auth
```

2. Now in order to authenticate users, you need to add link to the route like this:

```
<a href="{{ path('vatri_google_drive_auth') }}">
    Login
</a>
```

### 4. Use DriveApiService in your controller or another Symfony part like this:

```
   use App\Vatri\GoogleDriveBundle\Service\DriveApiService;
   ...

   public function test(Request $request, DriveApiService $driveApiService): Response
   {
     if($driveApiService->isTokenExpired()){
        return $this->redirectToRoute( $driveApiService->getAuthRouteName() );
     }
     $folderId = '[YOUR ID]';
     $res = $driveApiService->listFiles($folderId, false, true );
     dd($res);
   }
```


### 5. Check if Drive API access token is expired and authorize if required:

Add the following code to your controller or other part:

```
if($driveApiService->isTokenExpired()){
   
   // When auth is finished, redirect back to this URL:
   $driveApiService->setRedirectPathAfterAuth(
      $this->get('request_stack')->getCurrentRequest()->getRequestUri()
   );
   
   // Redirect
   return $this->redirectToRoute( $driveApiService->getAuthRouteName() );
}
```

# Roadmap

### Version 0.2

- [x] Automatically refresh access_token using refresh_token
- [x] Uniformed responses from _DriveApiService_ , a class
- [x] Automatically create the auth route on installation
- [x] Automatically add VATRI_DRIVE_CREDENTIALS_FILE= to _.env_ on installation
- [x] Parameter _vatri_google_drive.redirect_after_login_url_ to _.env_ variable (auto add to _.env_ as well)
- [ ] Test Symfony Flex recipe