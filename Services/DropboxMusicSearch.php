<?php
namespace Cogipix\CogimixDropboxBundle\Services;

use Dropbox\Config;


use Cogipix\CogimixCommonBundle\MusicSearch\AbstractMusicSearch;

class DropboxMusicSearch extends AbstractMusicSearch
{

   private $dropboxApi;
   private $accessToken;
   private $resutBuilder;



    public function __construct($accessToken,$dropboxApi,$resutBuilder)
    {
        $this->accessToken=$accessToken;
        $this->dropboxApi=$dropboxApi;
        $this->resutBuilder=$resutBuilder;

    }

    protected function parseResponse($output)
    {

       $tracks = array();
       try{


           $tracks=$this->resutBuilder->createArrayFromDropboxFiles($output);

        }catch(\Exception $ex){
            $this->logger->info($ex->getMessage());
            return array();
        }
        return $tracks;
    }

    protected function executeQuery()
    {
          $results=  $this->dropboxApi->search($this->searchQuery->getSongQuery());

        return $this->parseResponse($results);

    }

    protected function buildQuery()
    {
        $this->dropboxApi->createClient($this->accessToken);

    }

    public function getName()
    {
        return 'Dropbox';
    }

    public function getAlias()
    {
        return 'dropbox';
    }

    public function getResultTag()
    {
        return 'db';
    }

    public function getDefaultIcon(){
        return '/bundles/cogimixdropbox/images/dropbox-icon.png';
    }


}

?>