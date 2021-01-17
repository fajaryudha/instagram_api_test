<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Act_post_like extends CI_Model
{

    function save($arrData)
    {
        $arrResult = $arrData;
        $arrResult['created'] = date("Y-m-d h:i:sa");

        if (!$this->Main->insertUpdate('t_post_profile_like', $arrResult)) {
            $error['status'] = 1;
            $error['keterangan'] = "Kesalahan Sistem";
        } else {
            $error['status'] = 0;
            $error['keterangan'] = "Data Berhasil Ditambahkan";
        }
        return $error;
    }

    function delete($arrData)
    {
        $arrResult = $arrData;
        if (empty($arrResult['id_like'])) {
            $error['status'] = 1;
            $error['keterangan'] = "Kesalahan Sistem";
            return $error;
        }

        if (!$this->db->delete('t_post_profile_like', array('id_like' => $arrResult['id_like']))) {
            $error['status'] = 1;
            $error['keterangan'] = "Kesalahan Sistem";
        } else {
            $error['status'] = 0;
            $error['keterangan'] = "Data Berhasil Dihapus";
        }
        return $error;
    }

    function select($arrData)
    {
        $arrResult = $arrData;
        if (empty($arrResult['id_post_profile'])) {
            $error['status'] = 1;
            $error['keterangan'] = "Kesalahan Sistem";
            return $error;
        }
        $sqlGetData = $this->db->select('*')
            ->where('id_post_profile', $arrResult['id_post_profile'])
            ->get_compiled_select('t_post_profile_like');

        $arrReturn = $this->Main->get_rows($sqlGetData, TRUE);

        $error['status'] = 0;
        $error['keterangan'] = "Data Ditemukan";
        $error['data'] = $arrReturn;

        return $error;
    }
}
