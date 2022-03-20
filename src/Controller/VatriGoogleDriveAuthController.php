<?php

namespace Vatri\GoogleDriveBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Vatri\GoogleDriveBundle\Service\DriveApiService;


class VatriGoogleDriveAuthController extends AbstractController
{
	
	/**
	 * @var SessionInterface $session
	 **/
	private $session;

	/**
	 * @var ParameterBagInterface $session
	 **/
	private $parameterBag;

    /**
     * @var DriveApiService
     */
    private $driveApiService;

	public function __construct(ParameterBagInterface $parameterBag, RequestStack $requestSatck, DriveApiService $driveApiService)
    {
		try {
            $this->session = $requestStack->getSession();
        } catch (\Exception $exception){}
		$this->parameterBag      = $parameterBag;
        $this->driveApiService = $driveApiService;
	}
    /**
     * @Route("/vatri_google_drive/auth", name="vatri_google_drive_auth")
     */
    public function index()
    {
		
    	$client = new \Google_Client();
    	try{
			$client->setAuthConfig($this->parameterBag->get('vatri_google_drive.credentials_file'));
		} catch(\Exception $e){
			return new \Symfony\Component\HttpFoundation\Response('ERROR: ' . $e->getMessage() . '. Please download credentials file from Google Console to '.$this->parameterBag->get('vatri_google_drive.credentials_file'));
		}
		
		$client->setRedirectUri($this->generateUrl('vatri_google_drive_auth',array(), \Symfony\Component\Routing\Generator\UrlGeneratorInterface::ABSOLUTE_URL));
		$client->addScope(\Google_Service_Drive::DRIVE);

		// This will generate refresh_token on initial ALLOW permissions
		$client->setAccessType('offline');
		$client->setPrompt('consent');
		
		if (! isset($_GET['code'])) {
//			$access_token = $this->drive_api_service->getTokenStorage()->getToken();
			// if(isset($access_token['access_token'])){
			// 	$client->revokeToken($access_token['access_token']);
			// }
			$auth_url = $client->createAuthUrl();
			return $this->redirect($auth_url);
		} else {
			$client->fetchAccessTokenWithAuthCode($_GET['code']);
			//$this->session->set($this->access_token_key, $client->getAccessToken() );
            $this->driveApiService->getTokenStorage()->setToken($client->getAccessToken());
            $session_redirect = $this->session->get(
                $this->parameterBag->get('vatri_google_drive__session__key__redirect_path_after_auth')
            );
            if(!empty($session_redirect)){
				return $this->redirect($session_redirect);
			}

		  	return $this->redirect($this->parameterBag->get('vatri_google_drive.redirect_after_login_url'));

		}
    }
}
