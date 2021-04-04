<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Task;

/**
 * Home controller
 *
 */
class Home extends Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::render('dist/index.html');
    }
}
