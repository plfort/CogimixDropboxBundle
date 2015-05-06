<?php
namespace Cogipix\CogimixDropboxBundle\Entity;

use Cogipix\CogimixCommonBundle\Entity\Song;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @JMSSerializer\AccessType("public_method")
 * @ORM\MappedSuperclass()
 * @author plfort - Cogipix
 */

class DropboxResult extends Song
{
    protected $shareable = false;


     public function getEntryId(){
        return $this->getId();
    }

    public function setPath($path){
        $this->pluginProperties['url']=$path;
    }

}
