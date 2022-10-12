<?php return array (
  'name' => 'akira',
  'display_name' => 'Akira',
  'version' => '2.3.9',
  'author' => 
  array (
    'name' => 'AxonVip Team',
    'email' => 'axonvip@gmail.com',
    'url' => 'https://themeforest.net/user/axonviz/portfolio',
  ),
  'meta' => 
  array (
    'compatibility' => 
    array (
      'from' => '1.7.7.4',
      'to' => NULL,
    ),
    'available_layouts' => 
    array (
      'layout-full-width' => 
      array (
        'name' => 'Full Width',
        'description' => 'No side columns, ideal for distraction-free pages such as product pages.',
      ),
      'layout-both-columns' => 
      array (
        'name' => 'Three Columns',
        'description' => 'One large central column and 2 side columns.',
      ),
      'layout-left-column' => 
      array (
        'name' => 'Two Columns, small left column',
        'description' => 'Two columns with a small left column',
      ),
      'layout-right-column' => 
      array (
        'name' => 'Two Columns, small right column',
        'description' => 'Two columns with a small right column',
      ),
    ),
  ),
  'assets' => NULL,
  'global_settings' => 
  array (
    'configuration' => 
    array (
      'PS_IMAGE_QUALITY' => 'png',
      'BLOCK_CATEG_ROOT_CATEGORY' => 0,
      'PS_DISABLE_OVERRIDES' => 0,
    ),
    'modules' => 
    array (
      'to_enable' => 
      array (
        0 => 'nrtthemecustomizer',
        1 => 'nrtmegamenu',
        2 => 'axoncreator',
        3 => 'nrtpopupnewsletter',
        4 => 'nrtcompare',
        5 => 'nrtwishlist',
        6 => 'nrtproductslinknav',
        7 => 'nrtaddthisbutton',
        8 => 'nrtzoom',
        9 => 'nrtvariant',
        10 => 'nrtproducttags',
        11 => 'nrtsearchbar',
        12 => 'nrtsociallogin',
        13 => 'nrtsocialbutton',
        14 => 'nrtshoppingcart',
        15 => 'nrtcustomtab',
        16 => 'nrtreviews',
        17 => 'nrtcountdown',
        18 => 'nrtsizechart',
        19 => 'nrtcookielaw',
        20 => 'nrtproductvideo',
        21 => 'nrtcaptcha',
        22 => 'nrtshippingfreeprice',
        23 => 'smartblog',
        24 => 'smartblogsearch',
        25 => 'smartblogcategories',
        26 => 'smartblogrecentposts',
        27 => 'smartblogarchive',
        28 => 'smartbloglatestcomments',
        29 => 'smartblogpopularposts',
        30 => 'smartblogtag',
      ),
      'to_disable' => 
      array (
        0 => 'welcome',
        1 => 'ps_linklist',
        2 => 'ps_mainmenu',
        3 => 'ps_searchbar',
        4 => 'ps_featuredproducts',
        5 => 'ps_banner',
        6 => 'ps_imageslider',
        7 => 'ps_shoppingcart',
        8 => 'ps_customtext',
        9 => 'ps_customeraccountlinks',
        10 => 'ps_themecusto',
        11 => 'ps_sharebuttons',
        12 => 'productcomments',
      ),
    ),
    'hooks' => 
    array (
      'modules_to_hook' => 
      array (
        'actionAdminControllerSetMedia' => 
        array (
          0 => 'dashactivity',
          1 => 'dashgoals',
          2 => 'dashtrends',
          3 => 'graphnvd3',
          4 => 'gamification',
          5 => 'psgdpr',
          6 => 'ps_mbo',
          7 => 'nrtthemecustomizer',
        ),
        'actionAdminCurrenciesControllerSaveAfter' => 
        array (
          0 => 'ps_currencyselector',
        ),
        'actionAdminGroupsControllerSaveAfter' => NULL,
        'actionAdminMetaControllerUpdate_optionsAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionAdminPerformanceControllerSaveAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionAdminPreferencesControllerUpdate_optionsAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionAdminSpecificPriceRuleControllerSaveAfter' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'actionAdminStoresControllerSaveAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionAdminStoresControllerUpdate_optionsAfter' => 
        array (
          0 => 'ps_contactinfo',
          1 => 'gamification',
        ),
        'actionAdminThemesControllerUpdate_optionsAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionAdminWebserviceControllerSaveAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionAfterCreateFeatureFormHandler' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'actionAfterUpdateFeatureFormHandler' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'actionAttributeGroupDelete' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'actionAttributeGroupSave' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'actionAttributePostProcess' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'actionAttributeSave' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'actionCategoryAdd' => 
        array (
          0 => 'ps_facetedsearch',
          1 => 'nrtthemecustomizer',
          2 => 'nrtmegamenu',
        ),
        'actionCategoryDelete' => 
        array (
          0 => 'ps_facetedsearch',
          1 => 'nrtthemecustomizer',
          2 => 'nrtmegamenu',
        ),
        'actionCategoryUpdate' => 
        array (
          0 => 'ps_facetedsearch',
          1 => 'nrtthemecustomizer',
          2 => 'nrtmegamenu',
        ),
        'actionCustomerAccountAdd' => 
        array (
          0 => 'ps_emailsubscription',
          1 => 'psgdpr',
        ),
        'actionCustomerAccountUpdate' => 
        array (
          0 => 'ps_emailsubscription',
        ),
        'actionDeleteGDPRCustomer' => 
        array (
          0 => 'ps_emailsubscription',
          1 => 'psgdpr',
          2 => 'nrtwishlist',
        ),
        'actionExportGDPRData' => 
        array (
          0 => 'ps_emailsubscription',
          1 => 'nrtwishlist',
        ),
        'actionFeatureDelete' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'actionFeatureFormBuilderModifier' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'actionFeatureSave' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'actionFeatureValueDelete' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'actionFeatureValueSave' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'actionFrontControllerSetMedia' => 
        array (
          0 => 'ps_emailsubscription',
          1 => 'blockreassurance',
        ),
        'actionModuleInstallAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionModuleRegisterHookAfter' => NULL,
        'actionModuleUnRegisterHookAfter' => NULL,
        'actionObjectBlogDeleteAfter' => 
        array (
          0 => 'axoncreator',
        ),
        'actionObjectCarrierAddAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionObjectCartAddAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionObjectCartRuleAddAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionObjectCategoryAddAfter' => NULL,
        'actionObjectCategoryDeleteAfter' => 
        array (
          0 => 'nrtmegamenu',
          1 => 'axoncreator',
        ),
        'actionObjectCategoryUpdateAfter' => 
        array (
          0 => 'nrtmegamenu',
        ),
        'actionObjectCmsAddAfter' => 
        array (
          0 => 'gamification',
          1 => 'nrtthemecustomizer',
        ),
        'actionObjectCmsDeleteAfter' => 
        array (
          0 => 'nrtthemecustomizer',
          1 => 'nrtmegamenu',
          2 => 'axoncreator',
        ),
        'actionObjectCmsUpdateAfter' => 
        array (
          0 => 'nrtthemecustomizer',
          1 => 'nrtmegamenu',
        ),
        'actionObjectContactAddAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionObjectCustomerAddAfter' => 
        array (
          0 => 'dashactivity',
          1 => 'gamification',
        ),
        'actionObjectCustomerMessageAddAfter' => 
        array (
          0 => 'dashactivity',
        ),
        'actionObjectCustomerThreadAddAfter' => 
        array (
          0 => 'dashactivity',
          1 => 'gamification',
        ),
        'actionObjectCustomerUpdateBefore' => 
        array (
          0 => 'ps_emailsubscription',
        ),
        'actionObjectEmployeeAddAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionObjectImageAddAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionObjectLanguageAddAfter' => NULL,
        'actionObjectManufacturerAddAfter' => NULL,
        'actionObjectManufacturerDeleteAfter' => 
        array (
          0 => 'nrtmegamenu',
          1 => 'axoncreator',
        ),
        'actionObjectManufacturerUpdateAfter' => 
        array (
          0 => 'nrtmegamenu',
        ),
        'actionObjectOrderAddAfter' => 
        array (
          0 => 'dashactivity',
          1 => 'dashproducts',
          2 => 'gamification',
        ),
        'actionObjectOrderReturnAddAfter' => 
        array (
          0 => 'dashactivity',
        ),
        'actionObjectProductAddAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionObjectProductDeleteAfter' => 
        array (
          0 => 'nrtmegamenu',
          1 => 'axoncreator',
          2 => 'nrtreviews',
        ),
        'actionObjectProductUpdateAfter' => 
        array (
          0 => 'nrtmegamenu',
        ),
        'actionObjectShopAddAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionObjectShopGroupAddAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionObjectShopUpdateAfter' => 
        array (
          0 => 'gamification',
        ),
        'actionObjectSpecificPriceRuleUpdateBefore' => 
        array (
          0 => 'ps_facetedsearch',
        ),
        'actionObjectSupplierAddAfter' => NULL,
        'actionObjectSupplierDeleteAfter' => 
        array (
          0 => 'nrtmegamenu',
          1 => 'axoncreator',
        ),
        'actionObjectSupplierUpdateAfter' => 
        array (
          0 => 'nrtmegamenu',
        ),
        'actionOrderStatusPostUpdate' => 
        array (
          0 => 'dashtrends',
          1 => 'ps_crossselling',
        ),
        'actionOrderStatusUpdate' => 
        array (
          0 => 'gamification',
        ),
        'actionProductAdd' => 
        array (
          0 => 'nrtmegamenu',
        ),
        'actionProductDelete' => 
        array (
          0 => 'nrtthemecustomizer',
          1 => 'nrtmegamenu',
          2 => 'nrtwishlist',
          3 => 'nrtcustomtab',
          4 => 'nrtsizechart',
          5 => 'nrtproductvideo',
        ),
        'actionProductSave' => 
        array (
          0 => 'ps_facetedsearch',
          1 => 'nrtthemecustomizer',
          2 => 'nrtcustomtab',
          3 => 'nrtsizechart',
          4 => 'nrtproductvideo',
        ),
        'actionProductSearchAfter' => 
        array (
          0 => 'nrtthemecustomizer',
        ),
        'actionProductSearchComplete' => 
        array (
          0 => 'nrtthemecustomizer',
        ),
        'actionProductUpdate' => 
        array (
          0 => 'nrtmegamenu',
        ),
        'actionsbappcomment' => 
        array (
          0 => 'smartblog',
        ),
        'actionsbcat' => 
        array (
          0 => 'smartblog',
        ),
        'actionsbdeletecat' => 
        array (
          0 => 'smartblog',
          1 => 'smartblogcategories',
        ),
        'actionsbdeletepost' => 
        array (
          0 => 'smartblog',
          1 => 'smartblogrecentposts',
          2 => 'smartblogarchive',
          3 => 'smartblogpopularposts',
          4 => 'smartblogtag',
        ),
        'actionsbheader' => 
        array (
          0 => 'smartblog',
        ),
        'actionsbnewcat' => 
        array (
          0 => 'smartblog',
          1 => 'smartblogcategories',
        ),
        'actionsbnewpost' => 
        array (
          0 => 'smartblog',
          1 => 'smartblogrecentposts',
          2 => 'smartblogarchive',
          3 => 'smartblogpopularposts',
          4 => 'smartblogtag',
        ),
        'actionsbpostcomment' => 
        array (
          0 => 'smartblog',
          1 => 'smartbloglatestcomments',
        ),
        'actionsbsearch' => 
        array (
          0 => 'smartblog',
        ),
        'actionsbsingle' => 
        array (
          0 => 'smartblog',
          1 => 'smartblogpopularposts',
        ),
        'actionsbtogglecat' => 
        array (
          0 => 'smartblog',
          1 => 'smartblogcategories',
        ),
        'actionsbtogglepost' => 
        array (
          0 => 'smartblog',
          1 => 'smartblogrecentposts',
          2 => 'smartblogarchive',
          3 => 'smartblogpopularposts',
          4 => 'smartblogtag',
        ),
        'actionsbupdatecat' => 
        array (
          0 => 'smartblog',
          1 => 'smartblogcategories',
        ),
        'actionsbupdatepost' => 
        array (
          0 => 'smartblog',
          1 => 'smartblogrecentposts',
          2 => 'smartblogarchive',
          3 => 'smartblogpopularposts',
          4 => 'smartblogtag',
        ),
        'actionSearch' => 
        array (
          0 => 'dashproducts',
          1 => 'statssearch',
        ),
        'actionShopDataDuplication' => NULL,
        'actionSubmitAccountBefore' => 
        array (
          0 => 'ps_dataprivacy',
        ),
        'actionUpdateLangAfter' => NULL,
        'additionalCustomerFormFields' => 
        array (
          0 => 'ps_dataprivacy',
          1 => 'ps_emailsubscription',
          2 => 'psgdpr',
        ),
        'addproduct' => NULL,
        'addWebserviceResources' => 
        array (
          0 => 'smartblog',
        ),
        'AdminStatsModules' => 
        array (
          0 => 'pagesnotfound',
          1 => 'statsbestcategories',
          2 => 'statsbestcustomers',
          3 => 'statsbestproducts',
          4 => 'statsbestsuppliers',
          5 => 'statsbestvouchers',
          6 => 'statscarrier',
          7 => 'statscatalog',
          8 => 'statscheckup',
          9 => 'statsforecast',
          10 => 'statsnewsletter',
          11 => 'statspersonalinfos',
          12 => 'statsproduct',
          13 => 'statsregistrations',
          14 => 'statssales',
          15 => 'statssearch',
          16 => 'statsstock',
        ),
        'authentication' => 
        array (
          0 => 'statsdata',
        ),
        'backOfficeHeader' => 
        array (
          0 => 'nrtthemecustomizer',
          1 => 'nrtproductvideo',
        ),
        'categoryUpdate' => 
        array (
          0 => 'nrtmegamenu',
        ),
        'createAccount' => 
        array (
          0 => 'statsdata',
        ),
        'customhookname' => NULL,
        'dashboardData' => 
        array (
          0 => 'dashactivity',
          1 => 'dashgoals',
          2 => 'dashproducts',
          3 => 'dashtrends',
        ),
        'dashboardZoneOne' => 
        array (
          0 => 'dashactivity',
        ),
        'dashboardZoneTwo' => 
        array (
          0 => 'dashgoals',
          1 => 'dashproducts',
          2 => 'dashtrends',
        ),
        'deleteproduct' => NULL,
        'dislayMyAccountBlock' => NULL,
        'display404PageBuilder' => 
        array (
          0 => 'axoncreator',
        ),
        'displayAdminProductsExtra' => 
        array (
          0 => 'nrtthemecustomizer',
          1 => 'nrtcustomtab',
          2 => 'nrtsizechart',
          3 => 'nrtproductvideo',
        ),
        'displayAfterBodyOpeningTag' => 
        array (
          0 => 'blockreassurance',
        ),
        'displayBackOfficeCategory' => 
        array (
          0 => 'nrtthemecustomizer',
        ),
        'displayBackOfficeHeader' => 
        array (
          0 => 'ps_faviconnotificationbo',
          1 => 'gamification',
          2 => 'axoncreator',
          3 => 'smartblog',
          4 => 'ps_facebook',
        ),
        'displayBanner' => NULL,
        'displayNav1' => NULL,
        'displayNav2' => 
        array (
          0 => 'nrtcountdown',
        ),
        'displayBeforeBodyClosingTag' => 
        array (
          0 => 'statsdata',
          1 => 'nrtwishlist',
          2 => 'nrtcookielaw',
          3 => 'nrtreviews',
        ),
        'displayBeforeCarrier' => NULL,
        'displayBlogShareButtons' => 
        array (
          0 => 'nrtsocialbutton',
        ),
        'displayBodyBottom' => 
        array (
          0 => 'nrtthemecustomizer',
          1 => 'nrtmegamenu',
          2 => 'nrtpopupnewsletter',
          3 => 'nrtsearchbar',
          4 => 'nrtshoppingcart',
          5 => 'axoncreator',
        ),
        'displayButtonCartNbr' => 
        array (
          0 => 'nrtshoppingcart',
        ),
        'displayButtonCompare' => 
        array (
          0 => 'nrtcompare',
        ),
        'displayButtonCompareNbr' => 
        array (
          0 => 'nrtcompare',
        ),
        'displayButtonSearch' => 
        array (
          0 => 'nrtsearchbar',
        ),
        'displayButtonWishList' => 
        array (
          0 => 'nrtwishlist',
        ),
        'displayButtonWishListNbr' => 
        array (
          0 => 'nrtwishlist',
        ),
        'displayCarrierList' => NULL,
        'displayContactPageBuilder' => 
        array (
          0 => 'axoncreator',
        ),
        'displayCountDown' => 
        array (
          0 => 'nrtcountdown',
        ),
        'displayCrossSellingShoppingCart' => 
        array (
          0 => 'axoncreator',
        ),
        'displayCustomerAccount' => 
        array (
          0 => 'psgdpr',
          1 => 'nrtwishlist',
        ),
        'displayNrtCaptchaContact' => 
        array (
          0 => 'nrtcaptcha',
        ),
        'displayCustomerAccountForm' => 
        array (
          0 => 'nrtcaptcha',
        ),
        'displayNrtCartInfo' => 
        array (
          0 => 'nrtshippingfreeprice',
        ),
        'displayCustomerAccountFormTop' => NULL,
        'displayDashboardTop' => 
        array (
          0 => 'ps_mbo',
        ),
        'displayFollowButtons' => 
        array (
          0 => 'nrtsocialbutton',
        ),
        'displayFooter' => 
        array (
          0 => 'axoncreator',
        ),
        'displayFooterAfter' => 
        array (
          0 => 'axoncreator',
        ),
        'displayFooterBefore' => 
        array (
          0 => 'axoncreator',
        ),
        'displayFooterPageBuilder' => 
        array (
          0 => 'axoncreator',
        ),
        'displayFooterProduct' => 
        array (
          0 => 'axoncreator',
        ),
        'displayGDPRConsent' => 
        array (
          0 => 'psgdpr',
        ),
        'displayHeader' => 
        array (
          0 => 'nrtmegamenu',
          1 => 'nrtwishlist',
          2 => 'nrtcountdown',
          3 => 'nrtcookielaw',
          4 => 'nrtproductvideo',
          5 => 'smartblog',
          6 => 'smartblogcategories',
          7 => 'smartblogarchive',
          8 => 'nrtreviews',
        ),
        'displayHeaderMobileLeft' => 
        array (
          0 => 'nrtmegamenu',
        ),
        'displayHeaderMobileRight' => 
        array (
          0 => 'nrtsearchbar',
          1 => 'nrtshoppingcart',
          2 => 'ps_customersignin',
        ),
        'displayHome' => 
        array (
          0 => 'axoncreator',
        ),
        'displayIncludePageBuilder' => 
        array (
          0 => 'axoncreator',
        ),
        'displayProductSummary' => 
        array (
          0 => 'axoncreator',
        ),
        'displayFooterCategory' => 
        array (
          0 => 'axoncreator',
        ),
        'displayLeftColumn' => 
        array (
          0 => 'ps_categorytree',
          1 => 'ps_facetedsearch',
          2 => 'axoncreator',
          3 => 'nrtmegamenu',
        ),
        'displayLeftColumnProduct' => 
        array (
          0 => 'blockreassurance',
          1 => 'axoncreator',
        ),
        'displayMenuHorizontal' => 
        array (
          0 => 'nrtmegamenu',
        ),
        'displayMenuMobileCanVas' => 
        array (
          0 => 'nrtcompare',
          1 => 'nrtwishlist',
        ),
        'displayMenuVertical' => 
        array (
          0 => 'nrtmegamenu',
        ),
        'displayMyAccountBlock' => NULL,
        'displayMyAccountBlockfooter' => NULL,
        'displayMyAccountCanVas' => 
        array (
          0 => 'nrtcompare',
          1 => 'nrtwishlist',
        ),
        'displayNavFullWidth' => 
        array (
          0 => 'axoncreator',
        ),
        'displayOrderConfirmation' => NULL,
        'displayOrderConfirmation2' => 
        array (
          0 => 'axoncreator',
        ),
        'displayPaymentReturn' => NULL,
        'displayPaymentTop' => NULL,
        'displayProductAccessories' => 
        array (
          0 => 'axoncreator',
        ),
        'displayProductAdditionalInfo' => NULL,
        'displayProductButtons' => NULL,
        'displayProductExtraComparison' => 
        array (
          0 => 'nrtreviews',
        ),
        'displayProductExtraContent' => 
        array (
          0 => 'nrtcustomtab',
          1 => 'nrtreviews',
        ),
        'displayProductListReviews' => 
        array (
          0 => 'nrtreviews',
        ),
        'displayProductRating' => 
        array (
          0 => 'nrtreviews',
        ),
        'displayProductSameCategory' => 
        array (
          0 => 'axoncreator',
        ),
        'displayProductShareButtons' => 
        array (
          0 => 'nrtsocialbutton',
        ),
        'displayProductSizeGuide' => 
        array (
          0 => 'nrtsizechart',
        ),
        'displayProductsLinkNav' => 
        array (
          0 => 'nrtproductslinknav',
        ),
        'displayProductTags' => 
        array (
          0 => 'nrtproducttags',
        ),
        'displayProductVideoBtn' => 
        array (
          0 => 'nrtproductvideo',
        ),
        'displayReassurance' => 
        array (
          0 => 'blockreassurance',
        ),
        'displayRevSlider' => NULL,
        'displayRightColumn' => 
        array (
          0 => 'ps_categorytree',
          1 => 'ps_facetedsearch',
          2 => 'axoncreator',
          3 => 'nrtmegamenu',
        ),
        'displayRightColumnProduct' => 
        array (
          0 => 'blockreassurance',
          1 => 'axoncreator',
        ),
        'displaySearch' => 
        array (
          0 => 'nrtsearchbar',
        ),
        'displayShoppingCart' => NULL,
        'displayShoppingCartFooter' => 
        array (
          0 => 'axoncreator',
        ),
        'displaySmartBlogLeft' => 
        array (
          0 => 'smartblogsearch',
          1 => 'smartblogcategories',
          2 => 'smartblogrecentposts',
          3 => 'smartblogarchive',
          4 => 'smartbloglatestcomments',
          5 => 'smartblogpopularposts',
          6 => 'smartblogtag',
        ),
        'displaySmartBlogRight' => 
        array (
          0 => 'smartblogsearch',
          1 => 'smartblogcategories',
          2 => 'smartblogrecentposts',
          3 => 'smartblogarchive',
          4 => 'smartbloglatestcomments',
          5 => 'smartblogpopularposts',
          6 => 'smartblogtag',
        ),
        'displaySocialLogin' => 
        array (
          0 => 'nrtsociallogin',
        ),
        'displayTop' => NULL,
        'displayTopColumn' => NULL,
        'displayVariant' => 
        array (
          0 => 'nrtvariant',
        ),
        'displayWishListShareButtons' => 
        array (
          0 => 'nrtaddthisbutton',
        ),
        'overrideLayoutTemplate' => 
        array (
          0 => 'axoncreator',
        ),
        'filterBlogContent' => 
        array (
          0 => 'axoncreator',
        ),
        'filterCategoryContent' => 
        array (
          0 => 'axoncreator',
        ),
        'filterCmsContent' => 
        array (
          0 => 'axoncreator',
        ),
        'filterManufacturerContent' => 
        array (
          0 => 'axoncreator',
        ),
        'filterProductContent' => 
        array (
          0 => 'axoncreator',
        ),
        'filterProductSearch' => 
        array (
          0 => 'nrtthemecustomizer',
        ),
        'filterSupplierContent' => 
        array (
          0 => 'axoncreator',
        ),
        'GraphEngine' => 
        array (
          0 => 'graphnvd3',
        ),
        'GridEngine' => 
        array (
          0 => 'gridhtml',
        ),
        'header' => 
        array (
          0 => 'nrtthemecustomizer',
          1 => 'axoncreator',
          2 => 'nrtpopupnewsletter',
          3 => 'nrtcompare',
          4 => 'nrtproductslinknav',
          5 => 'nrtzoom',
          6 => 'nrtvariant',
          7 => 'nrtsearchbar',
          8 => 'nrtsociallogin',
          9 => 'nrtsocialbutton',
          10 => 'nrtshoppingcart',
          11 => 'nrtsizechart',
          12 => 'nrtcaptcha',
        ),
        'moduleRoutes' => 
        array (
          0 => 'smartblog',
        ),
        'newOrder' => 
        array (
          0 => 'gamification',
        ),
        'paymentOptions' => 
        array (
          0 => 'ps_checkpayment',
          1 => 'ps_wirepayment',
        ),
        'paymentReturn' => 
        array (
          0 => 'ps_checkpayment',
          1 => 'ps_wirepayment',
        ),
        'productSearchProvider' => 
        array (
          0 => 'ps_facetedsearch',
          1 => 'nrtthemecustomizer',
          2 => 'nrtsearchbar',
        ),
        'registerGDPRConsent' => 
        array (
          0 => 'contactform',
          1 => 'ps_emailsubscription',
          2 => 'psgdpr',
          3 => 'nrtpopupnewsletter',
          4 => 'nrtwishlist',
        ),
        'top' => 
        array (
          0 => 'pagesnotfound',
        ),
        'updateproduct' => NULL,
      ),
    ),
    'image_types' => 
    array (
      'cart_default' => 
      array (
        'width' => 125,
        'height' => 155,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'small_default' => 
      array (
        'width' => 190,
        'height' => 236,
        'scope' => 
        array (
          0 => 'products',
          1 => 'categories',
          2 => 'manufacturers',
          3 => 'suppliers',
        ),
      ),
      'medium_default' => 
      array (
        'width' => 600,
        'height' => 745,
        'scope' => 
        array (
          0 => 'products',
          1 => 'manufacturers',
          2 => 'suppliers',
        ),
      ),
      'home_default' => 
      array (
        'width' => 390,
        'height' => 484,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'large_default' => 
      array (
        'width' => 700,
        'height' => 869,
        'scope' => 
        array (
          0 => 'products',
          1 => 'manufacturers',
          2 => 'suppliers',
        ),
      ),
      'category_default' => 
      array (
        'width' => 1920,
        'height' => 400,
        'scope' => 
        array (
          0 => 'categories',
        ),
      ),
      'category_boxed' => 
      array (
        'width' => 870,
        'height' => 240,
        'scope' => 
        array (
          0 => 'categories',
        ),
      ),
      'stores_default' => 
      array (
        'width' => 170,
        'height' => 115,
        'scope' => 
        array (
          0 => 'stores',
        ),
      ),
      'square_cart_default' => 
      array (
        'width' => 125,
        'height' => 125,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'square_small_default' => 
      array (
        'width' => 190,
        'height' => 190,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'square_medium_default' => 
      array (
        'width' => 600,
        'height' => 600,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'square_home_default' => 
      array (
        'width' => 390,
        'height' => 390,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'square_large_default' => 
      array (
        'width' => 700,
        'height' => 700,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'rectangular_cart_default' => 
      array (
        'width' => 126,
        'height' => 84,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'rectangular_small_default' => 
      array (
        'width' => 192,
        'height' => 128,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'rectangular_medium_default' => 
      array (
        'width' => 600,
        'height' => 400,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'rectangular_home_default' => 
      array (
        'width' => 390,
        'height' => 260,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
      'rectangular_large_default' => 
      array (
        'width' => 696,
        'height' => 464,
        'scope' => 
        array (
          0 => 'products',
        ),
      ),
    ),
  ),
  'theme_settings' => 
  array (
    'default_layout' => 'layout-full-width',
    'layouts' => 
    array (
      'module-smartblog-details' => 'layout-right-column',
    ),
  ),
);
