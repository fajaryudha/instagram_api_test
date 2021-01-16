<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Act_post extends CI_Model
{
    function save($arrData)
    {
        $arrResult = $arrData;
        $arrResult['created'] = date("Y-m-d h:i:sa");
        if (!$this->Main->insertUpdate('t_post_profile', $arrResult)) {
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
        if (empty($arrResult['id_post_profile'])) {
            $error['status'] = 1;
            $error['keterangan'] = "Kesalahan Sistem";
            return $error;
        }
        if (!$this->db->delete('t_post_profile', array('id_post_profile' => $arrResult['id_post_profile']))) {
            $error['status'] = 1;
            $error['keterangan'] = "Kesalahan Sistem";
        } else {
            $error['status'] = 0;
            $error['keterangan'] = "Data Berhasil Dihapus";
        }
        return $error;
    }

    function update($arrData)
    {
        $arrResult = $arrData;
        if (empty($arrResult['id_post_profile'])) {
            $error['status'] = 1;
            $error['keterangan'] = "Kesalahan Sistem";
            return $error;
        }
        if (!$this->Main->insertUpdate('t_post_profile', $arrResult, array('id_post_profile' => $arrResult['id_post_profile']))) {
            $error['status'] = 1;
            $error['keterangan'] = "Kesalahan Sistem";
        } else {
            $error['status'] = 0;
            $error['keterangan'] = "Data Berhasil Ditambahkan";
        }
        return $error;
        # code...
    }

    function select($arrData)
    {
        $arrResult = $arrData;
        if (empty($arrResult['id_profile'])) {
            $error['status'] = 1;
            $error['keterangan'] = "Kesalahan Sistem";
            return $error;
        }

        if ($arrResult['jenis_select'] == 'teman') {
            $sqlGetRelation = $this->db->select('*')
                ->where('id_profile_pemilik', $arrResult['id_profile'])
                ->get_compiled_select('t_relation');
            $arrRelation = $this->Main->get_rows($sqlGetRelation, TRUE);

            foreach ($arrRelation as $key => $value) {
                $id_profile_teman[] = $value['id_profile_teman'];
                # code...
            }
            array_push($id_profile_teman, $arrResult['id_profile']);

            $sqlGetData = $this->db->select('*')
                ->where_in('id_profile', $id_profile_teman)
                ->get_compiled_select('t_post_profile');
            $arrReturn = $this->Main->get_rows($sqlGetData, TRUE);

            $error['status'] = 0;
            $error['keterangan'] = "Data Ditemukan";
            $error['data'] = $arrReturn;
        } else {

            $sqlGetData = $this->db->select('*')
                ->where_in('id_profile', $arrResult['id_profile'])
                ->get_compiled_select('t_post_profile');
            $arrReturn = $this->Main->get_rows($sqlGetData, TRUE);

            $error['status'] = 0;
            $error['keterangan'] = "Data Ditemukan";
            $error['data'] = $arrReturn;
        }
        return $error;
    }
}
