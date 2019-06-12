<?php

namespace Vatri\GoogleDriveBundle\Service;

class SessionTokenStorage implements TokenStorageInterface
{

	/**
	 * @
	 **/
	public function __construct(SessionInterface $session)
	{
		$this->session = $session;
	}

	public function setToken(array $token)
	{

	}

	public function getToken() : ?array
	{

	}
}