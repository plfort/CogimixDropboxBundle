<?php
namespace Cogipix\CogimixDropboxBundle\Services;

use Cogipix\CogimixDropboxBundle\Entity\DropboxResult;

use Cogipix\CogimixCommonBundle\Entity\TrackResult;

class ResultBuilder{


    public function createFromDropboxFile($dropboxEntry){
        $track=null;

        if($dropboxEntry['is_dir']==false && $this->checkMimeType($dropboxEntry['mime_type'])){

            $track = new DropboxResult();
            $pathinfo = pathinfo($dropboxEntry['path']);
            $track->setId($dropboxEntry['rev']);
            $track->setEntryId($dropboxEntry['rev']);
            $artistTitle = explode('-',$pathinfo['filename']);
            if(count($artistTitle)>1){
                $track->setArtist(trim($artistTitle[0]));
                $track->setTitle(trim($artistTitle[1]));
            }else{
                $track->setTitle(trim($pathinfo['filename']));
            }
            $track->setPath($dropboxEntry['path']);
            $track->setTag($this->getResultTag());
            $track->setThumbnails($this->getDefaultIcon());
            $track->setIcon($this->getDefaultIcon());
           // var_dump($track);die();
        }

        return $track;
    }

    public function createArrayFromDropboxFiles($dropboxFiles){
        //var_dump($dropboxFiles);die();
        $count=count($dropboxFiles);

        $tracks = array();
        for($i=0;$i<$count;$i++){

            $track = $this->createFromDropboxFile($dropboxFiles[$i]);

            if($track!=null){
                $tracks[]=$track;
            }
        }
        return $tracks;
    }

    public function getResultTag()
    {
        return 'db';
    }

    public function getDefaultIcon(){
        return 'bundles/cogimixdropbox/images/dropbox-icon.png';
    }

    private function checkMimeType($mime){
        return strstr($mime, 'audio/')===false?false:true;
    }
}
