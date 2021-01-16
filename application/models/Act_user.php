<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Act_user extends CI_Model
{
    //login
    function login($arrData)
    {
        $sqlUser = $this->db->select('*')
            ->where('a.username', $arrData['username'])
            ->get_compiled_select('user a');
        $arrResult['user'] = $this->Main->get_rows($sqlUser);

        if (empty($arrResult['user'])) {
            $error['status'] = 1;
            $error['keterangan'] = 'Username Tidak Ditemukan';
        } else {

            $sqlUserProfile = $this->db->select('*')
                ->where('a.id_user', $arrResult['user']['id_user'])
                ->get_compiled_select('user_profile a');
            $arrResult['user_profile'] = $this->Main->get_rows($sqlUserProfile);

            $arrResult['user']['status_login'] = 'Y';
            $arrResult['user']['logged_in'] = date("Y-m-d h:i:sa");

            if (!$this->Main->insertUpdate('user', $arrResult['user'], array('id_user' => $arrResult['user']['id_user']))) {
                $error['status'] = 1;
                $error['keterangan'] = 'Kesalahan Sistem';
            } else {
                $error['status'] = 0;
                $error['keterangan'] = 'Data Ditemukan Silahkan Masuk';
                $error['data'] = $arrResult;
            };
        }

        return $error;
    }

    function logout($arrData)
    {
        $sqlUser = $this->db->select('*')
            ->where('username', $arrData['username'])
            ->get_compiled_select('user');
        $arrResult = $this->Main->get_rows($sqlUser);
        $arrResult['logout_time'] = date("Y-m-d h:i:sa");
        $arrResult['status_login'] = 'N';
        if (!$this->Main->insertUpdate('user', $arrResult, array('id_user' => $arrResult['id_user']))) {
            $error['status'] = 1;
            $error['keterangan'] = 'Kesalahan Sistem';
        } else {
            $error['status'] = 0;
            $error['keterangan'] = 'Logout Berhasil';
        }
        return $error;
    }

    function registrasi($arrData)
    {
        $arrAuth = $arrData['auth'];
        $arrUser = $arrData['user'];
        $arrUserProfile = $arrData['user_profile'];

        $arrUser['password'] = $this->azdgcrypt->crypt($arrUser['password']);

        $sqlGetAuth = $this->db->select('*')
            ->where('user', $arrAuth['user'])
            ->get_compiled_select('auth');
        $arrGetAuth = $this->Main->get_rows($sqlGetAuth);
        $arrGetAuth['password'] = $this->azdgcrypt->decrypt($arrGetAuth['password']);

        $arrUser['id_au'] = $arrGetAuth['id_auth'];

        if ($arrGetAuth['password'] != $arrAuth['password']) {
            $error['status'] = 0;
            $error['keterangan'] = "Data Auth Tidak Di temukan";
            return $error;
        }

        $sqlCekUser =  $this->db->select('count(id_user) as jml')
            ->where('username', $arrUser['username'])
            ->get_compiled_select('user');
        $arrCekUser = $this->Main->get_uraian($sqlCekUser, 'jml');

        if ($arrCekUser > 0) {
            $error['status'] = 1;
            $error['keterangan'] = "Email Sudah Terdaftar";
            return $error;
        }

        if (!$this->Main->insertUpdate('user', $arrUser)) {
            $error['status'] = 1;
            $error['keterangan'] = "Kesalahan Sistem";
        } else {
            $sqlGetUserId = $this->db->select('id_user')
                ->where('username', $arrUser['username'])
                ->get_compiled_select('user');
            $arrUserProfile['id_user'] = $this->Main->get_uraian($sqlGetUserId, 'id_user');
            $arrUserProfile['created'] = date("Y-m-d h:i:sa");
            if (!$this->Main->insertUpdate('user_profile', $arrUserProfile)) {
                $error['status'] = 1;
                $error['keterangan'] = "Kesalahan Sistem";
            } else {

                $error['status'] = 0;
                $error['keterangan'] = "Registrasi Berhasil";
            }
        }
        return $error;
        # code...
    }
}
