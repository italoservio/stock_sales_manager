<?php
namespace App\Services;

use \App\Database\Database;
use \App\Entities\Client;

session_start();

class Auth {

	public static function getSession() {
		$User = $_SESSION['user'];
		return $User;
	}

	public static function getCompleteSession() {
		$User = $_SESSION['user'];
		$Client = $_SESSION['client'];
		return [
			'user' => $User,
			'client' => $Client
		];
	}

	public static function setSession($p_User) {
		$em = Database::manager();
		$clientRepository = $em->getRepository(Client::class);
		$Client = $clientRepository->findOneBy(['userid' => $p_User->getId()]);

		$_SESSION['user'] = $p_User;
		$_SESSION['client'] = $Client;
	}

	public static function clearSession() {
		if(isset($_SESSION['user'])) unset($_SESSION['user']);
		if(isset($_SESSION['client'])) unset($_SESSION['client']);
	}

	public static function hasSession() {
		return isset($_SESSION['user']);
	}

}