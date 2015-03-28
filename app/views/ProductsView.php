<?php

namespace app\views;

use app\models\DataAccessLayer\WebPageMapper;
use app\models\DataAccessLayer\ProductMapper;

/**
 * This is the view containing the binding logic for the 'products' page (using the templates/products.tpl).
 *
 * @package  Blop/app/views
 * @author   Thomas Punt
 * @license  MIT
 */
class ProductsView
{
    /**
     * @var Twig_Environment|null $tplEngine      The instance of the template engine
     * @var string|               $route          The route taken by the application
     * @var ProductMapper|null    $productMapper  The instance of the Product data mapper
     * @var WebPageMapper|null    $pageMapper     The instance of the WebPage data mapper
     */
    private $tplEngine = null,
            $route = '',
            $productMapper = null,
            $pageMapper = null;

    /**
     * Assigns the arguments to instance variables to be used by the render() method.
     *
     * @param Twig_Environment $tplEngine      The instance of the template engine
     * @param string|          $route          The route taken by the application
     * @param WebPageMapper    $pageMapper     The instance of the WebPage data mapper
     * @param ProductMapper    $productMapper  The instance of the Product data mapper
     */
    public function __construct(\Twig_Environment $tplEngine, $route, WebPageMapper $pageMapper, ProductMapper $productMapper)
    {
        $this->tplEngine = $tplEngine;
        $this->route = $route;
        $this->pageMapper = $pageMapper;
        $this->productMapper = $productMapper;
    }

    /**
     * Contains all of the binding logic in order to render the products.tpl file.
     *
     * @param array   $globalBindings  The information to be bound to every template
     * @return string                  The rendered template
     */
    public function render(array $globalBindings = [])
    {
        $route = strpos($this->route, '/') !== false ? explode('/', $this->route)[0] : $this->route;
        $webPage = $this->pageMapper->getPage($this->route);

        $tpl = $this->tplEngine->loadTemplate("{$route}.tpl");

        $bindings = ['loggedIn' => (isset($_SESSION['user']) ? $_SESSION['user']['user_id'] : ''),
                     'pLevel' => $_SESSION['user']['pLevel'],
                     'pageTitle' => $webPage->getPageTitle(),
                     'pageDescription' => $webPage->getPageDescription(),
                     'pageKeywords' => $webPage->getPageKeywords(),
                     'products' => $this->productMapper->getProducts()];

        return $tpl->render(array_merge($bindings, $globalBindings));
    }
}
