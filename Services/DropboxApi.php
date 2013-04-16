<?php
namespace Cogipix\CogimixDropboxBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;

use Cogipix\CogimixDropboxBundle\Entity\AccessToken;

use Dropbox\Client;

use Dropbox\AccessType;

use Dropbox\WebAuth;

use Dropbox\AppInfo;

use Dropbox\Config;

class DropboxApi{


    private $config;
    private $om;
    private $client;

    public function __construct($apiKey,$secret,ObjectManager $om){
        $this->om=$om;
        $appInfo = new AppInfo($apiKey, $secret, AccessType::FullDropbox());
        $this->config = new Config($appInfo, "Cogimix/1.0");
    }

    public function getWebAuthUrl($callbackUrl){
        $webAuth=new WebAuth($this->config);
          return $webAuth->start($callbackUrl);
    }

    public function finishWebAuth($requestToken){
        $webAuth=new WebAuth($this->config);

        try{
        return $webAuth->finish($requestToken);
        }catch(\Exception $ex){
            return false;
        }
    }

    public function createClient($accessToken){
        $accessToken = new \Dropbox\AccessToken($accessToken->getAccessKey(), $accessToken->getAccessSecret());
        $this->client = new Client($this->config, $accessToken);

    }

    public function search($query){

       return $this->client->searchFileNames('/', $query);

    }

    public function getTmpUrl($path){
        try{
           list($url,$expire)= $this->client->createTemporaryDirectLink($path);
           return $url;
        }catch(\Exception $ex){
            return false;
        }

    }

}