<?php
/**
 * Created by PhpStorm.
 * User: achref
 * Date: 20/05/2018
 * Time: 15:31
 */

namespace AppBundle\Security\Encoder;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;



class CustomEncoder extends BasePasswordEncoder {

    public function encodePassword($raw, $salt)
    {
        return md5($raw); // Retourne le mot de passe crypter en md5 sans le salt.
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $this->comparePasswords($encoded, $this->encodePassword($raw, $salt));
    }

}