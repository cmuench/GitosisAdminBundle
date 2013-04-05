<?php

namespace N98\GitosisAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Repository extends AbstractType
{
    /**
     * @var bool
     */
    protected $hideName = false;

    /**
     * @param bool $hideName
     */
    public function __construct($hideName = false)
    {
        $this->hideName = $hideName;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$this->hideName) {
            $builder->add('name', 'text', array(
                'trim' => true,
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Regex(array('pattern' => '/^[a-zA-Z0-9-_]+$/')),
                )
            ));
        }
        $builder->add('owner', 'text', array(
            'trim' => true,
            'constraints' => array(
                new Assert\NotBlank()
            )
        ))
        ->add('gitweb', 'checkbox', array(
            'required' => false
        ))
        ->add('daemon', 'checkbox', array(
            'required' => false
        ));
    }

    public function getName()
    {
        return 'repository';
    }
}