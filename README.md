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

### Download and configure JSON credentials file

Download your JSON credentials file from Google Console to _/config_ folder within Symfony and add a variable to _.env_ file:

  `VATRI_DRIVE_CREDENTIALS_FILE=config/google-drive-api-client_secrets.json-example.json`

### Use service in your controller or another Symfony part:

controller method:

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

### Use authentication controller route

Add following code to /config/routes.yaml:

```
controllers:
    ...
    resource: '@VatriGoogleDriveBundle/Controller/'
    type:     annotation
```

Now you should have a new route:

```
vatri_google_drive_auth       ANY      ANY      ANY    <href=>/vatri_google_drive/auth
```

Now in order to authenticate user, you need to add link to the route like this:

```
<a href="{{ path('vatri_google_drive_auth') }}">
    Login
</a>
```

### Check if Drive API access token is expired and authorize:

In your controller add following code:

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

- [ ] Automatically refresh using refresh_token
- [ ] Automatically create auth route after installation
- [ ] Automatically add VATRI_DRIVE_CREDENTIALS_FILE= to .evn on installation
- [ ] Parameter vatri_google_drive.redirect_after_login_url to .env variable (auto add to .env as well)
- [ ] Uniformed responses from DriveService
