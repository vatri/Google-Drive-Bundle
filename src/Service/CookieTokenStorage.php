<?php

namespace Vatri\GoogleDriveBundle\Service;

use function dd;
use function serialize;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use function unserialize;

class CookieTokenStorage implements TokenStorageInterface
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $access_token_key;

	public function __construct(RequestStack $requestStack, string $access_token_key)
	{
	    $this->request = $requestStack->getCurrentRequest();
	    $this->access_token_key = $access_token_key;
	}

	public function setToken(array $token)
	{
	    $token = serialize($token);
        $cookie = new Cookie($this->access_token_key, $token, 0, '/');
        $response = new Response();
        $response->headers->setCookie($cookie);
        $response->send();
	}

	public function getToken() : ?array
	{
	    $token = $this->request->cookies->get($this->access_token_key);

        return $token != null ? unserialize($token) : null;
    }
}