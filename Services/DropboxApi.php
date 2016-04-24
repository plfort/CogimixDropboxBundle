<?php
namespace Cogipix\CogimixDropboxBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;

use Cogipix\CogimixDropboxBundle\Entity\AccessToken;

use Dropbox\Client;

use Dropbox\AccessType;

use Dropbox\WebAuth;

use Dropbox\AppInfo;

use Dropbox\Config;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Dropbox\ArrayEntryStore;

class DropboxApi{


    private $config;
    private $om;
    private $client;
    private $router;

    public function __construct($apiKey,$secret,ObjectManager $om,RouterInterface $router){
        $this->om=$om;
        $appInfo = new AppInfo($apiKey, $secret);
        $csrfTokenStore = new ArrayEntryStore($_SESSION, 'dropbox-auth-csrf-token');
        $this->webAuth = new WebAuth($appInfo, "Cogimix/1.0", $router->generate('_dropbox_login_finish',array(),UrlGeneratorInterface::ABSOLUTE_URL),$csrfTokenStore);
    }

    public function getWebAuthUrl($callbackUrl){

          return $this->webAuth->start();
    }

    public function finishWebAuth($code){


        try{
        return $this->webAuth->finish($code);
        }catch(\Exception $ex){
            return false;
        }
    }

    public function createClient($accessToken){
        //$accessToken = new \Dropbox\AccessToken($accessToken->getAccessKey(), $accessToken->getAccessSecret());
        $this->client = new Client($accessToken->getAccessKey(),"Cogimix/1.0");

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