<?php

namespace CPASimUSante\SimuResourceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SimuResourceEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('field')
            //->add('resourceNode')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CPASimUSante\SimuResourceBundle\Entity\SimuResource',
            'translation_domain' => 'resource'  //where are st the translations for this form
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cpasimusante_simuresourcebundle_simuresource_edit';
    }

    public function getParent()
    {
        return new SimuResourceType();
    }
}
