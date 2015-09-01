<?php

namespace Bellwether\BWCMSBundle\Classes\Preference\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilder;
use Bellwether\BWCMSBundle\Classes\Preference\PreferenceType;
use Bellwether\BWCMSBundle\Classes\Constants\PreferenceFieldType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Bellwether\BWCMSBundle\Classes\Preference\Form\SampleForm;

class GeneralType Extends PreferenceType
{

    function __construct(ContainerInterface $container = null, RequestStack $request_stack = null)
    {
        $this->setContainer($container);
        $this->setRequestStack($request_stack);
    }

    protected function buildFields()
    {
        $this->addField('title', PreferenceFieldType::String);
        $this->addField('description', PreferenceFieldType::String);
        $this->addField('keywords', PreferenceFieldType::String);
        $this->addField('brandLogo', PreferenceFieldType::Content);
        $this->addField('adminEmail', PreferenceFieldType::String, true);
    }

    protected function buildForm()
    {
        $this->fb()->add('title', 'text',
            array(
                'label' => 'Title',
                'attr' => array(
                    'dir' => $this->sm()->getAdminCurrentSite()->getDirection()
                )
            )
        );
        $this->fb()->add('description', 'text',
            array(
                'label' => 'Description',
                'attr' => array(
                    'dir' => $this->sm()->getAdminCurrentSite()->getDirection()
                )
            )
        );
        $this->fb()->add('keywords', 'text',
            array(
                'label' => 'Keywords',
                'attr' => array(
                    'dir' => $this->sm()->getAdminCurrentSite()->getDirection()
                )
            )
        );
        $this->fb()->add('brandLogo', 'bwcms_content',
            array(
                'label' => 'Content',
                'contentType' => 'Media'
            )
        );
        $this->fb()->add('adminEmail', 'text',
            array(
                'label' => 'Admin Email'
            )
        );
    }

    function validateForm(FormEvent $event)
    {

    }

    public function getType()
    {
        return 'General';
    }

    public function getName()
    {
        return "General";
    }

}