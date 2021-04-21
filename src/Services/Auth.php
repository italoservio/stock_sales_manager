<?php
namespace App\Services;

session_start();

class Auth {

	public static function getSession() {
		$User = $_SESSION['user'];
		return $User;
	}

	public static function setSession($p_User) {
		$_SESSION['user'] = $p_User;
	}

	public static function clearSession() {
		unset($_SESSION['user']);
	}

	public static function hasSession() {
		return isset($_SESSION['user']);
	}

}