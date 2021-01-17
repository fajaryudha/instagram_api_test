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
class Post_komentar extends REST_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('post/Act_post_komentar');

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

    function index_post($jenis)
    {
        $arrData = $this->post();

        if ($jenis == 'save') {
            if (!$arrData['posting_komentar']) {
                $response['status'] = 400;
                $response['error'] = false;
                $this->response($response, 400);
            }
            $return = $this->Act_post_komentar->save($arrData['posting_komentar']);
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
        } else if ($jenis == 'delete') {
            if (!$arrData['posting_komentar']) {
                $response['status'] = 400;
                $response['error'] = false;
                $this->response($response, 400);
            }
            $return = $this->Act_post_komentar->delete($arrData['posting_komentar']);

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
        } else if ($jenis == 'update') {
            if (!$arrData['posting_komentar']) {
                $response['status'] = 400;
                $response['error'] = false;
                $this->response($response, 400);
            }
            $return = $this->Act_post_komentar->update($arrData['posting_komentar']);

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
        } else if ($jenis == 'select') {
            if (!$arrData['posting_komentar']) {
                $response['status'] = 400;
                $response['error'] = false;
                $this->response($response, 400);
            }
            $return = $this->Act_post_komentar->select($arrData['posting_komentar']);

            if ($return['status'] == 1) {
                $response['status'] = 400;
                $response['error'] = false;
                $response['keterangan'] = $return['keterangan'];
            } else {
                $response['status'] = 200;
                $response['error'] = true;
                $response['keterangan'] = $return['keterangan'];
                $response['data'] = $return['data'];
            }
            $this->response($response);
        }
    }
}
