<?php
namespace Cogipix\CogimixDropboxBundle\Entity;

use Cogipix\CogimixCommonBundle\Entity\TrackResult;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;

/**
 * @JMSSerializer\AccessType("public_method")
 * @author plfort - Cogipix
 */

class DropboxResult extends TrackResult
{

    public function __construct(){
        parent::__construct();
        // $this->pluginProperties=array('test'=>array('url'=>'','test'=>'hello'));
    }

     public function getEntryId(){
        return $this->getId();
    }

    public function setPath($path){
        $this->pluginProperties['url']=$path;
    }

}
