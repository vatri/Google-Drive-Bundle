<?php

namespace Vatri\GoogleDriveBundle\Service;


/**
 * Contract for 
 */
interface TokenStorageInterface
{
	public function setToken(array $token);
	public function getToken() : ?array;

}