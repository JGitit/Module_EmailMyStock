<?php

// Check if PrestaShop is installed to avoid direct access to the module
if (!defined('_PS_VERSION_'))
{
	exit;
}

// Module's main Class
class EmailMyStock extends Module
{
	public function __construct()
	{
		$this->name = 'emailmystock';
		$this->tab = 'others';
		$this->version = '1.0.0';
		$this->author = 'Jordi Sanchez';
		$this->need_instance = 0;
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Email My Stock');
		$this->description = $this->l('Send the admin an email whenever the quantity of a product changes on an order confirmation. Specifies the product name and the quantity left in stock.');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

		if (!Configuration::get('MYMODULE_NAME'))
		$this->warning = $this->l('No name provided');
	}

	public function install()
	{
		parent::install();
		// Assign a hook to the module on installation
		$this->registerHook('actionUpdateQuantity');
		
		return true;
	}

	// Minimal uninstall() method
	public function uninstall()
	{
		if (!parent::uninstall())
		return false;
		return true;
	}

	// Manage configuration form submission
	public function processConfiguration()
	{
		// Check if the value sent on form submit matches one of either $_POST or $_GET keys 
		if (Tools::isSubmit('submit_emailmystock_form'))
		{
			$enable_emails = Tools::getValue('enable_emails');
			// Update configuration value to database
			Configuration::updateValue('MYMOD_EMAILS', $enable_emails);
			// Allows the Smarty object to display a confirmation message when the configuration is saved (see top of getContent.tpl)
			$this->context->smarty->assign('confirmation', 'ok');
		}
	}

	// Keeps focus on the saved configuration option avoiding confusion for the user
	public function assignConfiguration()
	{
		$enable_emails = Configuration::get('MYMOD_EMAILS');
		$this->context->smarty->assign('enable_emails', $enable_emails);
	}

	public function getContent()
	{
		$this->processConfiguration();
		$this->assignConfiguration();
		return $this->display(__FILE__, 'getContent.tpl');
	}

	/* This function serves the main purpose of the module.
	This hook, assigned in the Install() method before, trigger an email notification to the admin whenever an order is confirmed */
	public function hookActionUpdateQuantity($params)
	{
		$context = Context::getContext();
        $id_shop = (int)$context->shop->id;
        $id_lang = (int)$context->language->id;

        $shop_name = strval(Configuration::get('PS_SHOP_NAME'));
        $shop_email = strval(Configuration::get('PS_SHOP_EMAIL'));
        $id_product = (int)$params['id_product'];
        $id_product_attribute = (int)$params['id_product_attribute'];
        $product_name = Product::getProductName($id_product, $id_product_attribute, $id_lang);
        $quantity = (int)$params['quantity'];

        // Prepares the variables used in the email template
        $templateVars = array(
        		'{shop_name}' => $shop_name,
        		'{shop_email}' => $shop_email,
        		'{id_product}' => $id_product,
        		'{product_name}' => $product_name,
        		'{quantity}' => $quantity
        );

        // Checks if the module is on  
        if(!Configuration::get('MYMOD_EMAILS') == false)
        {	
        	// Sends the email notification to '$shop_email'
			Mail::send($id_lang,
		        'stock_notification',
		        'Stock Update',
		        $templateVars,
		        $shop_email,
		        null,
		        null,
		        null,
		        null,
		        null,
		        'mails/'
		        );
		}
	}
}