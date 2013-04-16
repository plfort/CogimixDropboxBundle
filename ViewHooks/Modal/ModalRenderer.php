<?php
namespace Cogipix\CogimixDropboxBundle\ViewHooks\Modal;

use Cogipix\CogimixCommonBundle\ViewHooks\Modal\ModalItemInterface;
/**
 *
 * @author plfort - Cogipix
 *
 */
class ModalRenderer implements ModalItemInterface
{

    public function getModalTemplate()
    {
        return 'CogimixDropboxBundle:Modal:modals.html.twig';

    }

}
