<?php

namespace Bellwether\BWCMSBundle\Controller;

use Bellwether\BWCMSBundle\Classes\Base\BaseController;
use Bellwether\BWCMSBundle\Classes\Base\BackEndControllerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Page controller.
 *
 * @Route("/admin/preference")
 * @Security("has_role('ROLE_AUTHOR')")
 */
class PreferenceController extends BaseController  implements BackEndControllerInterface
{
    /**
     * @Route("/{type}/index.php",name="_bwcms_admin_preference_page")
     * @Template("BWCMSBundle:Preference:save.html.twig")
     */
    public function loadAction($type)
    {
        $preferenceClass = $this->pref()->getPreferenceClass($type);
        $form = $preferenceClass->getForm();
        $form = $this->pref()->loadFormData($form, $preferenceClass);

        return array(
            'title' => $preferenceClass->getName() . ' Preference',
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/{type}/save.php",name="_bwcms_admin_preference_save_page")
     * @Method({"POST"})
     * @Template("BWCMSBundle:Preference:save.html.twig")
     */
    public function saveAction($type, Request $request)
    {

        $preferenceClass = $this->pref()->getPreferenceClass($type);
        $form = $preferenceClass->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->pref()->saveFormData($form, $preferenceClass);
            return $this->redirect($this->generateUrl('_bwcms_admin_preference_page', array('type' => $type)));
        }

        return array(
            'title' => $preferenceClass->getName() . ' Preference',
            'form' => $form->createView()
        );


    }


}
