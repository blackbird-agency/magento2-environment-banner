<?php

namespace Blackbird\EnvironmentBanner\Block\Adminhtml\System\Config\Form\Field;

class Environment extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    public $_helper;
    public function __construct(\Magento\Backend\Block\Template\Context $context, \Blackbird\EnvironmentBanner\Helper\Data $helper) {
        parent::__construct($context);

        $this->_helper = $helper;

        $this->addColumn('env', array(
            'label' => 'Environment Code',
            'style' => 'width:100px',
        ));
        $this->addColumn('fe_bgcolor', array(
            'label' => 'Frontend Banner Hex',
            'style' => 'width:100px',
        ));
        $this->addColumn('be_color', array(
            'label' => 'Backend Banner Hex',
            'style' => 'width:100px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = 'Add New Environment';
    }
}
