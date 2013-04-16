<?php
namespace Cogipix\CogimixDropboxBundle\Services;


use Cogipix\CogimixCommonBundle\Plugin\PluginProviderInterface;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\Security\Core\SecurityContextInterface;

class DropboxPluginProvider implements PluginProviderInterface{

    private $om;
    private $securityContext;
    protected $plugins = array();
    protected $pluginProviders;

    private $pluginFactory;

    public function __construct(ObjectManager $om,SecurityContextInterface $securityContext,DropboxPluginFactory $factory){
        $this->om=$om;
        $this->securityContext=$securityContext;
        $this->pluginFactory=$factory;

    }

    public function getAvailablePlugins(){
     $user = $this->getCurrentUser();
     if($user!=null && $user->hasRole('ROLE_DROPBOX')){
        $accessToken=$this->om->getRepository('CogimixDropboxBundle:AccessToken')->findOneByUser($user);
        if(!empty($accessToken)){

                $this->plugins['dropbox']= $this->pluginFactory->createDropboxPlugin($accessToken);

        }
     }
        return $this->plugins;
    }


    public function getPluginChoiceList()
    {
        $choices = array();
        if(!empty($this->plugins)){
            foreach($this->plugins as $alias=>$plugin){
                $choices[$alias] = $plugin->getName();
            }
        }
        return $choices;
    }


    protected function getCurrentUser() {
        $user = $this->securityContext->getToken()->getUser();
        if ($user instanceof \FOS\UserBundle\Model\UserInterface)
            return $user;
        return null;
    }

    public function getAlias(){
        return 'dropboxpluginprovider';
    }
}