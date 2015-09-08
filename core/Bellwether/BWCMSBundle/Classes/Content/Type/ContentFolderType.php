<?php

namespace Bellwether\BWCMSBundle\Classes\Content\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Bellwether\BWCMSBundle\Classes\Constants\ContentFieldType;
use Bellwether\BWCMSBundle\Classes\Content\ContentType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\FormEvent;

use Bellwether\BWCMSBundle\Entity\ContentEntity;
use Bellwether\BWCMSBundle\Classes\Content\ContentTypeInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ContentFolderType Extends ContentType
{

    function __construct(ContainerInterface $container = null, RequestStack $request_stack = null)
    {
        $this->setContainer($container);
        $this->setRequestStack($request_stack);

        $this->setIsHierarchy(true);
        $this->setIsRootItem(true);

        $this->setIsSummaryEnabled(false);
        $this->setIsContentEnabled(false);
        $this->setIsUploadEnabled(false);
    }

    public function buildFields()
    {

    }

    public function buildForm($isEditMode = false, ContentEntity $contentEntity = null)
    {

    }

    public function addTemplates()
    {
        $this->addTemplate('Default','Default.html.twig','Default.png');
    }

    public function validateForm(FormEvent $event)
    {

    }

    public function loadFormData(ContentEntity $content = null, Form $form = null)
    {
        return $form;
    }

    public function prepareEntity(ContentEntity $content = null, Form $form = null)
    {
        return $content;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return '@BWCMSBundle/Resources/icons/content/Folder.png';
    }

    /**
     * @param ContentEntity $contentEntity
     * @return string|null
     */
    public function getPublicURL($contentEntity, $full = false)
    {
        $contentParents = $this->cm()->getContentRepository()->getPath($contentEntity);
        if (count($contentParents) < 1) {
            return null;
        }
        $folders = array();
        foreach ($contentParents as $parent) {
            $folders[] = $parent->getSlug();
        }
        $parameters = array(
            'folderSlug' => implode('/', $folders),
            'siteSlug' => $contentEntity->getSite()->getSlug()
        );
        return $this->container->get('router')->generate('contentFolder', $parameters, $full);
    }

    /**
     * @return null|RouteCollection
     */
    public function getRouteCollection()
    {
        $routes = new RouteCollection();
        $contentFolderRoute = new Route('/{siteSlug}/content/{folderSlug}/index.php', array(
            '_controller' => 'BWCMSBundle:FrontEnd:contentFolder',
        ), array(
            'siteSlug' => '[a-zA-Z0-9-]+',
            'folderSlug' => '[a-zA-Z0-9-_/]+'
        ));
        $routes->add('contentFolder', $contentFolderRoute);
        return $routes;
    }

    public function getType()
    {
        return "Content";
    }

    public function getSchema()
    {
        return "Folder";
    }

    public function getName()
    {
        return "Folder";
    }

}
