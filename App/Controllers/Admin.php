<?php

namespace App\Controllers;

use Core\Controller;
use \Core\View;

/**
 * Admin controller
 *
 */
class Admin extends Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Admin/index.html');
    }
}
