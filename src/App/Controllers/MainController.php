<?php
namespace Akmb\App\Controllers;

use Akmb\Core\Controllers\DefaultController;

class MainController extends DefaultController
{
    public function index()
    {
        return $this->render('Main:index');
    }
}
