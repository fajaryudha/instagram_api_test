<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Auth extends REST_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('Act_auth');
        $this->load->model('Act_user');

        // print_r('test');
        // die;
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        // $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        // $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        // $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    function index_get()
    {
        // print_r('test');
        // $data = $this->azdgcrypt->crypt('test');

    }

    public function user_get()
    {

        // testing response
        $response['status'] = 200;
        $response['error'] = false;
        $response['user']['username'] = 'erthru';
        $response['user']['email'] = 'ersaka96@gmail.com';
        $response['user']['detail']['full_name'] = 'Suprianto D';
        $response['user']['detail']['position'] = 'Developer';
        $response['user']['detail']['specialize'] = 'Android,IOS,WEB,Desktop';

        //tampilkan response
        $this->response($response);
    }

    function index_post()
    {
        $arrData = $this->post();

        if (!empty($arrData['auth'])) {
            $return = $this->Act_auth->cek_auth($arrData['auth']);
            
            if ($return['status'] == 1) {
                $response['status'] = 400;
                $response['error'] = false;
                $response['keterangan'] = $return['keterangan'];
            } else {
                $response['status'] = 200;
                $response['error'] = true;
                $response['keterangan'] = $return['keterangan'];
            }
            $this->response($response);
        } else {
            $response['status'] = 400;
            $response['error'] = false;
            $this->response($response, 404);
        }
    }
}
