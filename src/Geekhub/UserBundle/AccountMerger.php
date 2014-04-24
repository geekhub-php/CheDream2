<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22.04.14
 * Time: 21:44
 */

namespace Geekhub\UserBundle;


class AccountMerger 
{
    protected $facebookResourceOwner;

    protected $vkontakteResourceOwner;

    protected $odnoklassnikiResourceOwner;

    public function mergeAccountsByEmail($email, $user)
    {

    }

    public function setFacebookResourceOwner($facebookResourceOwner)
    {
        $this->facebookResourceOwner = $facebookResourceOwner;
    }

    public function setVkontakteResourceOwner($vkontakteResourceOwner)
    {
        $this->vkontakteResourceOwner = $vkontakteResourceOwner;
    }

    public function setOdnoklassnikiResourceOwner($odnoklassnikiResourceOwner)
    {
        $this->odnoklassnikiResourceOwner = $odnoklassnikiResourceOwner;
    }
    
    
} 
