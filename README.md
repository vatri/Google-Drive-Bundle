# Google-Drive-Bundle

Google Drive API Bundle for Symfony 6 and 7.

# Features

- Authorize via Google API
- Manage Google Drive files and folders
  - Create a folder (recursively)
  - Check if a folder exists
  - Find a file by ID
  - Delete a file
  - Download file
  - List files
  - Copy a file to specific directory
  - Upload a file
  - Add "starred" flag to a file/folder
  - Rename resource (file or folder)

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

Usage
============

### 1. Add/edit following variables in your _.env_ file:

```
VATRI_GOOGLE_DRIVE_CREDENTIALS_FILE=config/YOUR_FILENAME.json`
VATRI_GOOGLE_DRIVE_REDIRECT_AFTER_AUTH=/
```

**Don't forget to replace default values with yours.**

### 2. Create (or check if exists) _/config/routes/vatri_google_drive.yaml_ file with following contents:

```yaml
vatri_google_drive:
    resource: '@VatriGoogleDriveBundle/Controller/'
    type:     annotation
```

### 3. Download and configure JSON credentials file

Download your JSON credentials file from Google Console to _/config_ folder within Symfony project.

Edit following variables in _.env_ file:

  `VATRI_GOOGLE_DRIVE_CREDENTIALS_FILE=config/google-drive-api-client_secrets.json-example.json`
  `VATRI_GOOGLE_DRIVE_REDIRECT_AFTER_AUTH=/path/to/your/route`

### 4. Check and use AuthController

AuthController is default controller required for authorization of users via Google API. By default it saves an access token to cookie. 

Also note that if you use this controller for authorization, you will add below route as callback URL in Google Console Credentials configuration.

1. Run

`php bin/console debug:router`

and check if you have a route like this:

```
vatri_google_drive_auth       ANY      ANY      ANY    <href=>/vatri_google_drive/auth
```

2. Now in order to authenticate users, you need to add link to the route like this:

```html
<a href="{{ path('vatri_google_drive_auth') }}">
    Login
</a>
```

### 5. Use DriveApiService in your controller or another Symfony part like this:

```php
 use Vatri\GoogleDriveBundle\Service\DriveApiService;
 ...

 public function test(DriveApiService $driveApiService): Response
 {
   if($driveApiService->isTokenExpired()){
      return $this->redirectToRoute( $driveApiService->getAuthRouteName() );
   }
   $folderId = '[YOUR ID]';
   $res = $driveApiService->listFiles($folderId, false, true );
   dd($res);
 }
```


### 6. Check if Drive API access token is expired and authorize if required:

Add the following code to your controller or other part:

```php
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

### Version 1.2

- [ ] Symfony Flex recipe
- [ ] Logout controller
- [ ] Configure token storage (including custom one)

### Version 1.1

- [x] renameFolder() method

### Version 1.0

- [x] Automatically refresh access_token using refresh_token
- [x] Uniformed responses from _DriveApiService_ (a class)
