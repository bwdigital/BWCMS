<?php

namespace Bellwether\BWCMSBundle\Classes\Service;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Bellwether\BWCMSBundle\Classes\Base\BaseService;
use Symfony\Component\HttpFoundation\Request;


class AdminMenuService extends BaseService
{
    private $factory;

    function __construct(FactoryInterface $factory = null, ContainerInterface $container = null, RequestStack $request_stack = null)
    {
        $this->setFactory($factory);
        $this->setContainer($container);
        $this->setRequestStack($request_stack);
    }

    /**
     * @return AdminMenuService
     */
    public function getManager()
    {
        return $this;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function buildRightMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $currentSite = $this->sm()->getAdminCurrentSite();
        $allSites = $this->sm()->getAllSites();
        if (count($allSites) > 1) {
            $menu->addChild('Site', array('uri' => '#', 'label' => 'Site: ' . $currentSite->getName()))->setAttribute('dropdown', true);
            foreach ($allSites as $siteInfo) {
                /**
                 * @var \Knp\Menu\MenuItem $siteMenu
                 */
                $siteMenu = $menu['Site']->addChild($siteInfo->getName(), array(
                    'route' => 'site_change_current',
                    'routeParameters' => array('siteId' => $siteInfo->getId())
                ));
                if ($currentSite->getId() == $siteInfo->getId()) {
                    $siteMenu->setCurrent(true);
                }
            }
        }


        $menu->addChild('Profile', array('uri' => '#', 'label' => $this->getUser()->getEmail()))->setAttribute('dropdown', true);
        $menu['Profile']->addChild('Profile', array('uri' => '#'));
        if ($this->getSecurityContext()->isGranted('ROLE_PREVIOUS_ADMIN')) {
            $menu['Profile']->addChild('Exit User', array(
                'route' => 'Homepage',
                'routeParameters' => array('_switch_user' => '_exit')
            ));
        }
        $menu['Profile']->addChild('Logout', array('route' => 'user_logout'));

        return $menu;
    }

    public function buildLeftMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild('Dashboard', array('route' => 'dashboard_home'));

        $menu->addChild('Manage', array('uri' => '#', 'label' => 'Manage'))->setAttribute('dropdown', true);
        $menu['Manage']->addChild('Content', array(
            'route' => 'content_home',
            'routeParameters' => array(
                'type' => 'Content'
            )
        ));
        $menu['Manage']->addChild('Media', array(
            'route' => 'content_home',
            'routeParameters' => array(
                'type' => 'Media'
            )
        ));
        $menu['Manage']->addChild('Navigation', array(
            'route' => 'content_home',
            'routeParameters' => array(
                'type' => 'Navigation'
            )
        ));
        $menu['Manage']->addChild('Widget', array(
            'route' => 'content_home',
            'routeParameters' => array(
                'type' => 'Widget'
            )
        ));
        $menu['Manage']->addChild('Image Thumb Styles', array(
            'route' => 'thumbstyle_home'
        ));


        $menu->addChild('Preference', array('uri' => '#', 'label' => 'Preference'))->setAttribute('dropdown', true);
        $registeredOptionTypes = $this->pref()->getRegisteredOptionTypes();
        foreach ($registeredOptionTypes as $optionType) {
            $menu['Preference']->addChild($optionType['name'], array(
                'route' => 'preference_page',
                'routeParameters' => array(
                    'type' => $optionType['type']
                )
            ));
        }


        $menu->addChild('Admin', array('uri' => '#', 'label' => 'Admin'))->setAttribute('dropdown', true);

        $menu['Admin']->addChild('Site', array(
            'route' => 'site_home'
        ));
        $menu['Admin']->addChild('User', array(
            'route' => 'user_home'
        ));


        return $menu;
    }


    /**
     * @param FactoryInterface $factory
     */
    public function setFactory(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

}