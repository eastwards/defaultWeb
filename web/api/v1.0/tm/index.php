<?php
/*****************************************************************************
 *
 * name     restful api by trade site
 * desc     restful api, get, post, put, delete,...
 * author   Xuni
 * package  api/v1.0/tm/
 * include  slim3.4.2, Spring, webadmin
 * date     2016-05-30
 *
 ******************************************************************************/
error_reporting(E_ALL^E_NOTICE);//close php notice info
define('AppResourceDir', realpath('../../../'));

//use composer package
require 'vendor/autoload.php';

// create Slim app
$app = new \Slim\App;


// defined route by get
$app->get('/', function ($request, $response, $args) {

    try{
        //get params
        $data   = $request->getQueryParams();//$_GET $request->getParam()//$_GET、$_POST
        //$data   = $request->getParsedBody();//$_POST
        $params = empty($data['data']) ? array() : json_decode($data['data'],1);

        //include Spring framework (webadmin)
        $obj    = require_once( AppResourceDir.'/include.php' );
        $result = Spring::out('openapi')->request( $params );

        $flag   = json_encode( $result );
        //return $response->withStatus(200)->withHeader('Content-type', 'application/json')->write( $flag );
        return $response->write( $flag );

    } catch(Exception $e) {
        $response = $response->withStatus(500)->withHeader('Content-type', 'application/json');
        $response->getBody()->write(json_encode(
            [
                'status' => 500,
                'error' => $e->getMessage(),
                'datas' => ''
            ]
        ));
        return $response;
    }

});


// run app
$app->run();