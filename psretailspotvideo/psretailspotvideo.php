<?php
if (!defined('_PS_VERSION_')) {
  exit;
}

class PsRetailSpotVideo extends Module
{
  public function __construct()
  {
    $this->name = 'psretailspotvideo';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'RetailSpot';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = [
        'min' => '1.6',
        'max' => '1.7.99',
    ];
    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = $this->l('RetailSpot video advertisement');
    $this->description = $this->l('Allows traffic monetization by showing video ads in a smart way.');

    $this->confirmUninstall = $this->l('Are you sure you want to stop monetizing traffic?');

    if (!Configuration::get('RETAILSPOTVIDEO_NAME')) {
        $this->warning = $this->l('No name provided');
    }
  }

  public function install()
  {
    if (Shop::isFeatureActive()) {
      Shop::setContext(Shop::CONTEXT_ALL);
    }

    return (
      parent::install() 
      && $this->registerHook('header')
      && Configuration::updateValue('RETAILSPOTVIDEO_NAME', 'RetailSpotVideo')
    ); 
  }

  public function uninstall()
  {
    return (
      parent::uninstall() 
      && Configuration::deleteByName('RETAILSPOTVIDEO_NAME')
    );
  }

  /**
 * This method handles the module's configuration page
 * @return string The page's HTML content 
 */
  public function getContent()
  {
    $output = '';

    // this part is executed only when the form is submitted
    if (Tools::isSubmit('submit' . $this->name)) {
      // retrieve the value set by the user
      $placement = (string) Tools::getValue('rsvideo_placement');

      // check that every values are valid
      if (empty($placement) ) {
        // invalid value, show an error
        $output = $this->displayError($this->l('Invalid Placement Id'));
      } else {
        // value is ok, update it and display a confirmation message
        Configuration::updateValue('rsvideo_placement', $placement);
        Configuration::updateValue('rsvideo_intext_enabled', $placement);
        Configuration::updateValue('rsvideo_slider_enabled', $placement);
        $output = $this->displayConfirmation($this->l('Settings updated val : '.Tools::getValue('rsvideo_intext_enabled')));
      }
    }

    // display any message, then the form
    return $output . $this->displayForm();
  }

  /**
 * Builds the configuration form
 * @return string HTML code
 */
  public function displayForm()
  {
    // Init Fields form array
    $form = [
        'form' => [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Placement ID'),
                    'name' => 'rsvideo_placement',
                    'size' => 32,
                    'required' => true,
                    'hint' => $this->l('Get in touch with your RetailSpot contact to generate a plecement id')
                ],
                [
                  'type' => 'checkbox',
                  'name' => 'rsvideo_intext',
                  'desc' => 'Shows an expandable video inside article lists',
                  'values' => [
                    'query' => [
                      [
                          'id' => 'enabled',
                          'name' => $this->l('Enable expandable video'),
                          'val' => '1'
                      ],
                    ],
                    'id' => 'id',
                    'name' => 'name'
                  ]
                ],
                [
                  'type' => 'checkbox',
                  'name' => 'rsvideo_slider',
                  'desc' => 'Shows a sliding video overlay',
                  'values' => [
                    'query' => [
                      [
                          'id' => 'enabled',
                          'name' => $this->l('Enable sliding video'),
                          'val' => '1'
                      ]
                    ],
                    'id' => 'id',
                    'name' => 'name',
                    'value' => '1'
                  ]
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
            ],
        ],
    ];

    $helper = new HelperForm();

    // Module, token and currentIndex
    $helper->table = $this->table;
    $helper->name_controller = $this->name;
    $helper->token = Tools::getAdminTokenLite('AdminModules');
    $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
    $helper->submit_action = 'submit' . $this->name;

    // Default language
    $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');

    // Load current value into the form
    $helper->fields_value['rsvideo_placement'] = Tools::getValue('rsvideo_placement', Configuration::get('rsvideo_placement'));

    $helper->fields_value['rsvideo_intext_enabled'] = Tools::getValue('rsvideo_intext_enabled', Configuration::get('rsvideo_intext_enabled'));
    $helper->fields_value['rsvideo_slider_enabled'] = Tools::getValue('rsvideo_slider_enabled', Configuration::get('rsvideo_slider_enabled'));

    return $helper->generateForm([$form]);
  }
}