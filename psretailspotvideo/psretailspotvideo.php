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
      && $this->registerHook('displayTop') 
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
      $placement1 = (string) Tools::getValue('rsvideo_slider_placement');
      $placement2 = (string) Tools::getValue('rsvideo_intext_placement');

      // check that every values are valid
      if (empty($placement1) && empty($placement2) ) {
        // invalid value, show an error
        $output = $this->displayError($this->l('You should set at least one placement Id.'));
      } else {
        // value is ok, update it and display a confirmation message
        Configuration::updateValue('rsvideo_slider_placement', $placement1);
        Configuration::updateValue('rsvideo_slider_width', Tools::getValue('rsvideo_slider_width'));
        Configuration::updateValue('rsvideo_slider_height', Tools::getValue('rsvideo_slider_height'));
        Configuration::updateValue('rsvideo_slider_align', Tools::getValue('rsvideo_slider_align'));
        Configuration::updateValue('rsvideo_intext_placement', $placement2);
        Configuration::updateValue('rsvideo_intext_width', Tools::getValue('rsvideo_intext_width'));
        Configuration::updateValue('rsvideo_intext_height', Tools::getValue('rsvideo_intext_height'));
        Configuration::updateValue('rsvideo_intext_target', Tools::getValue('rsvideo_intext_target'));
        $output = $this->displayConfirmation($this->l('Settings updated.'));
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
            'title' => $this->l('RetailSpot Ad settings'),
            'desc' => 'Setup a sliding and/or an expanding ad.',
        ],
        'input' => [
          [
            'type' => 'text',
            'label' => $this->l('Slider Placement ID'),
            'name' => 'rsvideo_slider_placement',
            'size' => 32,
            'hint' => $this->l('Get in touch with your RetailSpot contact to generate a placement id')
          ],
          [
            'type' => 'text',
            'label' => $this->l('Slider Width'),
            'name' => 'rsvideo_slider_width',
            'size' => 3,
            'value' => 320
          ],
          [
            'type' => 'text',
            'label' => $this->l('Slider Height'),
            'name' => 'rsvideo_slider_height',
            'size' => 3,
            'value' => 180,
            'desc' => $this->l('Slider video size in pixels')
          ],
          [
            'type' => 'select',                              // This is a <select> tag.
            'label' => $this->l('Slider alignment'),         // The <label> for this <select> tag.
            'desc' => $this->l('Define sliding video position on the page.'),  // A help text, displayed right next to the <select> tag.
            'name' => 'rsvideo_slider_align',                               // The content of the 'id' attribute of the <select> tag.
            'options' => [
              'query' => [
                [
                  'id_option' => "bottom right",// The value of the 'value' attribute of the <option> tag.
                  'name' => 'Bottom Right'      // The value of the text content of the  <option> tag.
                ],
                [
                  'id_option' => "bottom left",
                  'name' => 'Bottom Left'    
                ],
                [
                  'id_option' => "top left",
                  'name' => 'Top Left'    
                ],
                [
                  'id_option' => "top right",
                  'name' => 'Top Right'    
                ]
              ] ,                           
              'id' => 'id_option', // The value of the 'id' key must be the same as the key for 'value' attribute of the <option> tag in each $options sub-array.
              'name' => 'name'     // The value of the 'name' key must be the same as the key for the text content of the <option> tag in each $options sub-array.
            ]
          ],
          [
            'type' => 'text',
            'label' => $this->l('Expand Ad Placement ID'),
            'name' => 'rsvideo_intext_placement',
            'size' => 32,
            'hint' => $this->l('Get in touch with your RetailSpot contact to generate a placement id')
          ],
          [
            'type' => 'text',
            'label' => $this->l('Expanded Width'),
            'name' => 'rsvideo_intext_width',
            'size' => 3,
            'value' => 320,
          ],
          [
            'type' => 'text',
            'label' => $this->l('Expanded Height'),
            'name' => 'rsvideo_intext_height',
            'size' => 3,
            'val' => 180,
            'desc' => $this->l('Expand video size in pixels')
          ],
          [
            'type' => 'text',
            'label' => $this->l('Position CSS Selector'),
            'name' => 'rsvideo_intext_target',
            'size' => 50,
            'desc' => $this->l('Target an element in the page using CSS selector. If not provided expand video position will be chosen automatically.')
          ]
        ],
        'submit' => [
            'title' => $this->l('Save'),
            'class' => 'btn btn-default pull-right',
        ]
      ]
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
    $helper->fields_value['rsvideo_slider_placement'] = Tools::getValue('rsvideo_slider_placement', Configuration::get('rsvideo_slider_placement'));
    $helper->fields_value['rsvideo_slider_width'] = Tools::getValue('rsvideo_slider_width', Configuration::get('rsvideo_slider_width'));
    $helper->fields_value['rsvideo_slider_height'] = Tools::getValue('rsvideo_slider_height', Configuration::get('rsvideo_slider_height'));
    $helper->fields_value['rsvideo_slider_align'] = Tools::getValue('rsvideo_slider_align', Configuration::get('rsvideo_slider_align'));

    $helper->fields_value['rsvideo_intext_placement'] = Tools::getValue('rsvideo_intext_placement', Configuration::get('rsvideo_intext_placement'));
    $helper->fields_value['rsvideo_intext_width'] = Tools::getValue('rsvideo_intext_width', Configuration::get('rsvideo_intext_width'));
    $helper->fields_value['rsvideo_intext_height'] = Tools::getValue('rsvideo_intext_height', Configuration::get('rsvideo_intext_height'));
    $helper->fields_value['rsvideo_intext_target'] = Tools::getValue('rsvideo_intext_target', Configuration::get('rsvideo_intext_target'));


    return $helper->generateForm([$form]);
  }

  public function hookDisplayTop($params)
  {
    //provide json config to be accessible by javascript
    $configArr = [[
        'vastUrl' => 'https://ads.stickyadstv.com/www/delivery/swfIndex.php?reqType=AdsSetup&protocolVersion=2.0&zoneId='.Configuration::get('rsvideo_slider_placement'),
        'width'   => Configuration::get('rsvideo_slider_width'),
        'height'  => Configuration::get('rsvideo_slider_height'),
        'format'  => 'slider',
        'align'   => Configuration::get('rsvideo_slider_align'),
        // 'vmargin' =>50,   // not available from prestashop module
        // 'hmargin' =>50,   // not available from prestashop module
        // 'anim'    =>'top'    // not available from prestashop module. default is 'auto'=>minimal distance animation
      ],
      [
        'vastUrl'     => 'https://ads.stickyadstv.com/www/delivery/swfIndex.php?reqType=AdsSetup&protocolVersion=2.0&zoneId='.Configuration::get('rsvideo_intext_placement'),
        'width'       => Configuration::get('rsvideo_intext_width'),
        'height'      => Configuration::get('rsvideo_intext_height'),
        'format'      => 'intext',
        'CSSSelector' => Configuration::get('rsvideo_intext_target'),
      ],
    ];
    
    $this->smarty->assign('rs_videoad_config', json_encode($configArr, 0, 3));

    return $this->display(__FILE__, 'psretailspotvideo.tpl');
  }
}