<?php

namespace CPASimUSante\SimuResourceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SimuResourceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field')
            ->add('resourceNode')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CPASimUSante\SimuResourceBundle\Entity\SimuResource'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cpasimusante_simuresourcebundle_simuresource';
    }
}
