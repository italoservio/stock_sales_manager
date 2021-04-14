<?php
namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User {

	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
 	 */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $login;

    /**
     * @ORM\Column(type="string")
     */
    protected $pass;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="integer")
     */
    protected $admin;

    /**
     * @ORM\Column(type="string")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="string")
     */
    protected $updatedAt;

    /*
     * Getters
     */
    public function getId() {
        return $this->id;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getPass() {
        return $this->pass;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAdmin() {
        return $this->admin;
    }

    /*
     * Setters
     */
    public function setId($p_id) {
        $this->id = $p_id;
    }

    public function setLogin($p_login) {
        $this->login = $p_login;
    }

    public function setPass($p_pass) {
        $this->pass = $p_pass;
    }

    public function setName($p_name) {
        $this->name = $p_name;
    }

    public function setEmail($p_email) {
        $this->email = $p_email;
    }

    public function setAdmin($p_admin) {
        $this->admin = $p_admin;
    }

}