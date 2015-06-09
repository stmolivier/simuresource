<?php

namespace CPASimUSante\SimuResourceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PluginconfigType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('param')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CPASimUSante\SimuResourceBundle\Entity\Pluginconfig'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cpasimusante_simuresourcebundle_pluginconfig';
    }
}
