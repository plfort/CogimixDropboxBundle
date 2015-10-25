<?php
namespace Cogipix\CogimixDropboxBundle\Services;

use Cogipix\CogimixDropboxBundle\Services\DropboxMusicSearch;

use Cogipix\CogimixDropboxBundle\Entity\AccessToken;

use Symfony\Component\DependencyInjection\ContainerInterface;

class DropboxPluginFactory{

    private $container;

    public function __construct(ContainerInterface $container){

        $this->container=$container;
    }

    public function createDropboxPlugin(AccessToken $accessToken){

        $resultBuilder = $this->container->get('cogimix_dropbox.result_builder');
        $dropboxApi = $this->container->get('cogimix_dropbox.dropbox_api');
        $dropboxPlugin = new DropboxMusicSearch($accessToken,$dropboxApi,$resultBuilder);
        $dropboxPlugin->setLogger($this->container->get('logger'));
        $dropboxPlugin->setSongManager($this->container->get('cogimix.song_manager'));
        //$dropboxPlugin->setAccessToken($accessToken);

       return $dropboxPlugin;
    }
}