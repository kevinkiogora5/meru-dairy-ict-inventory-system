<?php

namespace Mcdcu\Projects\controllers\dashboard;

use sigawa\mvccore\Request;
use sigawa\mvccore\Response;
use sigawa\mvccore\Controller;

class dashboardController extends Controller
{
    public function index(Request $request, Response $response) {
         $this->setLayout("admin");
         return $this->render('dashboard');
    }

}