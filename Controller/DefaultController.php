<?php

namespace Cogipix\CogimixDropboxBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Cogipix\CogimixDropboxBundle\Entity\AccessToken;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Cogipix\CogimixCommonBundle\Utils\AjaxResult;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
/**
 * @Route("/dropbox")
 * @author plfort - Cogipix
 *
 */
class DefaultController extends Controller
{
    /**
     * @Route("/login",name="_dropbox_login", options={"expose"=true})
     *
     */
    public function loginAction()
    {
        $response = new AjaxResult();
        $dropboxApi = $this->get('cogimix_dropbox.dropbox_api');
        $response->setSuccess(true);
        list($requestToken, $authorizeUrl) = $dropboxApi->getWebAuthUrl($this->generateUrl('_dropbox_login_finish',array(),true));
        $this->get('session')->set('dropboxRequestToken',$requestToken);
        $response->addData('authUrl',$authorizeUrl);
        return $response->createResponse();
    }

    /**
     * @Secure("ROLE_USER")
     * @Route("/loginfinish",name="_dropbox_login_finish", options={"expose"=true})
     *
     */
    public function loginFinishAction(){
        $dropboxApi = $this->get('cogimix_dropbox.dropbox_api');
        $requestToken=$this->get('session')->get('dropboxRequestToken');
        $this->get('session')->remove('dropboxRequestToken');
        $result =$dropboxApi->finishWebAuth($requestToken);
        $success = false;
        if($result !== false){
            $success=true;

            list($accessToken, $dropboxUserId)=$result;
            $user = $this->getUser();
            $accessTokenDb = $em->getRepository('CogimixDropboxBundle:AccessToken')->findOneByUser($user);
            if($accessTokenDb ===null){
                $accessTokenDb = new AccessToken();
                $accessTokenDb->setUser($user);
            }
            $accessTokenDb->setDropboxUserId($dropboxUserId);
            $accessTokenDb->setAccessKey($accessToken->getKey());
            $accessTokenDb->setAccessSecret($accessToken->getSecret());
            $user->addRole('ROLE_DROPBOX');
            $em->persist($accessTokenDb);
            $em->flush();
            $this->get('security.context')->getToken()->setAuthenticated(false);
        }

        return $this->render('CogimixDropboxBundle:Login:finish.html.twig',array('success'=>$success));

    }

    /**
     * @Secure("ROLE_DROPBOX")
     * @Route("/tmpUrl",name="_dropbox_tmp_url", options={"expose"=true})
     *
     */

    public function getTmpUrlAction(Request $request){
        $response = new AjaxResult();
        $dropboxApi = $this->get('cogimix_dropbox.dropbox_api');
        $em=  $this->getDoctrine()->getEntityManager();
        $user = $this->getUser();
        $accessTokenDb = $em->getRepository('CogimixDropboxBundle:AccessToken')->findOneByUser($user);
        if($accessTokenDb){
            $path = $request->request->get('path');
            $dropboxApi->createClient($accessTokenDb);

            $url = $dropboxApi->getTmpUrl($path);
            if(!empty($url)){
                $response->setSuccess(true);
                $response->addData('url',$url);
            }
        }
        return $response->createResponse();
    }

}
