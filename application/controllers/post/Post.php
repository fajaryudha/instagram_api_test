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
class Post extends REST_Controller
{
    public $slash = DIRECTORY_SEPARATOR;

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('post/Act_post');

        // print_r('test');
        // die;
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        // $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        // $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        // $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    function index_post($jenis)
    {
        $arrData = $this->post();


        // $input = file_get_contents('php://input');

        if ($jenis == 'save') {

            $arrData = json_decode($arrData['data'], true);
            $arrData['upload'] = $_FILES;
            if (!$arrData['upload']) {
                $response['status'] = 400;
                $response['error'] = false;
                $response['keterangan'] = "File Kosong";
                $this->response($response, 400);
            }
            if (!$arrData['posting']) {
                $response['status'] = 400;
                $response['error'] = false;
                $this->response($response, 400);
            }

            $return = $this->Act_post->save($arrData['posting']);

            if ($return['status'] == 1) {
                $response['status'] = 400;
                $response['error'] = false;
                $response['keterangan'] = $return['keterangan'];
            } else {

                $file = $this->upload($arrData, $return['id_post_profile']);

                $arrData['posting']['id_post_profile'] = $return['id_post_profile'];
                $arrData['posting']['foto'] = $file['file'];
                $return = $this->Act_post->update($arrData['posting']);

                if ($file['status'] == 1 || $return['status'] == 1) {
                    $response['status'] = 400;
                    $response['error'] = true;
                    $response['keterangan'] = $return['keterangan'];
                } else {
                    $response['status'] = 200;
                    $response['error'] = true;
                    $response['keterangan'] = $return['keterangan'];
                    $response['keterangan_file'] = $file['keterangan'];
                }
            }
            $this->response($response);
        } else if ($jenis == 'delete') {
            if (!$arrData['posting']) {
                $response['status'] = 400;
                $response['error'] = false;
                $this->response($response, 400);
            }
            $return = $this->Act_post->delete($arrData['posting']);

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

            $arrData = json_decode($arrData['data'], true);
            $arrData['upload'] = $_FILES;
            
            if (!$arrData['upload']) {
                $response['status'] = 400;
                $response['error'] = false;
                $response['keterangan'] = "File Kosong";
                $this->response($response, 400);
            }

            if (!$arrData['posting']) {
                $response['status'] = 400;
                $response['error'] = false;
                $this->response($response, 400);
            }
            $file = $this->upload($arrData, $arrData['posting']['id_post_profile']);
            $arrData['posting']['foto'] = $file['file'];
            $return = $this->Act_post->update($arrData['posting']);

            if ($return['status'] == 1) {
                $response['status'] = 400;
                $response['error'] = false;
                $response['keterangan'] = $return['keterangan'];
            } else {

                if ($file['status'] == 1) {
                    $response['status'] = 400;
                    $response['error'] = true;
                    $response['keterangan'] = $return['keterangan'];
                } else {
                    $response['status'] = 200;
                    $response['error'] = false;
                    $response['keterangan'] = $return['keterangan'];
                    $response['keterangan_file'] = $file['keterangan'];
                }
            }
            $this->response($response);
        } else if ($jenis == 'select') {
            if (!$arrData['posting']) {
                $response['status'] = 400;
                $response['error'] = false;
                $this->response($response, 400);
            }
            $return = $this->Act_post->select($arrData['posting']);
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

    function upload($arrData, $id_post_profile)
    {
        $dirRoot = getcwd();
        $file = $arrData['upload']['file'];
        $type_file = pathinfo($file['name'], PATHINFO_EXTENSION);

        if (strtolower($type_file) != 'jpg' && strtolower($type_file) != 'png'  && strtolower($type_file) != 'jpeg') {
            $error['status'] = 1;
            $error['keterangan'] = "File Tidak Cocok Sistem";
            return $error;
        }

        $path = $dirRoot . $this->slash . "upload" . $this->slash . "post" . $this->slash . $id_post_profile . '.' . $type_file;

        if (move_uploaded_file($file['tmp_name'], $path)) {
            $error['status'] = 0;
            $error['keterangan'] = "File Terupload";
            $error['file'] = $path;
        } else {
            $error['status'] = 1;
            $error['keterangan'] = "File Gagal Terupload";
        }
        return $error;
    }
}
