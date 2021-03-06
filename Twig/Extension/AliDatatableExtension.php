<?php

namespace Ali\DatatableBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Ali\DatatableBundle\Util\Datatable;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AliDatatableExtension extends \Twig_Extension
{

    /** @var \Symfony\Component\DependencyInjection\ContainerInterface */
    protected $_container;

    /**
     * class constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('datatable', array($this, 'datatable'), array("is_safe" => array("html"))),
            new \Twig_SimpleFunction('datatable_html', array($this, 'datatableHtml'), array("is_safe" => array("html"))),
            new \Twig_SimpleFunction('datatable_js', array($this, 'datatableJs'), array("is_safe" => array("html")))
        );
    }

    /**
     * Converts a string to time
     *
     * @param string $string
     * @return int
     */
    public function datatable($options)
    {
        if (!isset($options['id']))
        {
            $options['id'] = 'ali-dta_' . md5(rand(1, 100));
        }
        $dt                       = Datatable::getInstance($options['id']);
        $config                   = $dt->getConfiguration();
        $options['js_conf']       = json_encode($config['js']);
        $options['js']            = json_encode($options['js']);
        $options['action']        = $dt->getHasAction();
        $options['action_twig']   = $dt->getHasRendererAction();
        $options['fields']        = $dt->getFields();
        $options['delete_form']   = $this->createDeleteForm('_id_')->createView();
        $options['search']        = $dt->getSearch();
        $options['global_search'] = $dt->getGlobalSearch();
        $options['search_fields'] = $dt->getSearchFields();
        $options['multiple']      = $dt->getMultiple();
        $options['sort']          = is_null($dt->getOrderField()) ? NULL : array(array_search(
            $dt->getOrderField(), array_values($dt->getFields())), $dt->getOrderType());
        $main_template            = 'AliDatatableBundle:Main:index.html.twig';
        if (isset($options['main_template']))
        {
            $main_template = $options['main_template'];
        }

        return $this->_container
            ->get('templating')
            ->render(
                $main_template, $options);
    }

    /**
     * Converts a string to time
     *
     * @param string $string
     * @return int
     */
    public function datatableJs($options)
    {
        if (!isset($options['id']))
        {
            $options['id'] = 'ali-dta_' . md5(rand(1, 100));
        }
        $dt                                 = Datatable::getInstance($options['id']);
        $config                             = $dt->getConfiguration();
        $options['js_conf']                 = json_encode($config['js']);
        $options['js']                      = json_encode($options['js']);
        $options['action']                  = $dt->getHasAction();
        $options['action_twig']             = $dt->getHasRendererAction();
        $options['fields']                  = $dt->getFields();
        $options['delete_form']             = $this->createDeleteForm('_id_')->createView();
        $options['search']                  = $dt->getSearch();
        $options['global_search']           = $dt->getGlobalSearch();
        $options['search_fields']           = $dt->getSearchFields();
        $options['not_filterable_fields']   = $dt->getNotFilterableFields();
        $options['not_sortable_fields']     = $dt->getNotSortableFields();
        $options['hidden_fields']           = $dt->getHiddenFields();
        $options['multiple']                = $dt->getMultiple();
        $options['sort']                    = is_null($dt->getOrderField()) ? NULL : [array_search(
            $dt->getOrderField(), array_values($dt->getFields())), $dt->getOrderType()];
        $main_template            = 'AliDatatableBundle:Main:datatableJs.html.twig';
        if (isset($options['js_template']))
        {
            $main_template = $options['js_template'];
        }

        return $this->_container
            ->get('templating')
            ->render(
                $main_template, $options);
    }

    /**
     * Converts a string to time
     *
     * @param string $string
     * @return int
     */
    public function datatableHtml($options)
    {
        if (!isset($options['id']))
        {
            $options['id'] = 'ali-dta_' . md5(rand(1, 100));
        }
        $dt                       = Datatable::getInstance($options['id']);
        $config                   = $dt->getConfiguration();
//        $options['js_conf']       = json_encode($config['js']);
//        $options['js']            = json_encode($options['js']);
        $options['action']        = $dt->getHasAction();
        $options['action_twig']   = $dt->getHasRendererAction();
        $options['fields']        = $dt->getFields();
//        $options['delete_form']   = $this->createDeleteForm('_id_')->createView();
        $options['search']        = $dt->getSearch();
        $options['search_fields'] = $dt->getSearchFields();
        $options['multiple']      = $dt->getMultiple();
//        $options['sort']          = is_null($dt->getOrderField()) ? NULL : [array_search(
//                    $dt->getOrderField(), array_values($dt->getFields())), $dt->getOrderType()];
        $main_template            = 'AliDatatableBundle:Main:datatableHtml.html.twig';
        if (isset($options['html_template']))
        {
            $main_template = $options['html_template'];
        }

        return $this->_container
            ->get('templating')
            ->render(
                $main_template, $options);
    }

    /**
     * create delete form
     *
     * @param type $id
     * @return type
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', HiddenType::class)
            ->getForm();
    }

    /**
     * create form builder
     *
     * @param type $data
     * @param array $options
     * @return type
     */
    public function createFormBuilder($data = null, array $options = array())
    {
        return $this->_container->get('form.factory')->createBuilder(FormType::class, $data, $options);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'DatatableBundle';
    }

}
