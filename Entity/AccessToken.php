<?php
namespace Cogipix\CogimixDropboxBundle\Entity;
use Cogipix\CogimixCommonBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;
/**
 *
 * @author plfort - Cogipix
 * @ORM\Entity
 * @ORM\Table(name="accesstoken")
 */
class AccessToken
{

    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string")
     * @var unknown_type
     */
    protected $accessKey;

    /**
     * @ORM\Column(type="string")
     * @var unknown_type
     */
    protected $dropboxUserId;

    /**
     * @ORM\OneToOne(targetEntity="Cogipix\CogimixCommonBundle\Entity\User")
     * @var User $user
     */

    protected $user;

    public function getId()
    {
        return $this->id;
    }

    public function getDropboxUserId()
    {
        return $this->dropboxUserId;
    }

    public function setDropboxUserId($dropboxUserId)
    {
        $this->dropboxUserId = $dropboxUserId;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getAccessKey()
    {
        return $this->accessKey;
    }

    public function setAccessKey($accessKey)
    {
        $this->accessKey = $accessKey;
    }

}
