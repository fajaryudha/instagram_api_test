<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Act_post_like extends CI_Model
{
    function cek_auth($arrData)
    {
        if (empty($arrData)) {
            $error['keterangan'] = 'Periksa Username Atau Password';
            $error['status'] = 1;
        } else {
            $sqlAuth = $this->db->select('*')
                ->where('user', $arrData['user'])
                ->get_compiled_select('auth');
            $arrResult = $this->Main->get_rows($sqlAuth, false);
          
            if (empty($arrResult)) {
                $error['keterangan'] = 'Data Tidak Di temukan';
                $error['status'] = 1;
            } else {
                $arrResult['password'] = $this->azdgcrypt->decrypt($arrResult['password']);
                if ($arrResult['password'] == $arrData['password']) {
                    $error['keterangan'] = 'Data Berhasil Ditemkan';
                    $error['status'] = 0;
                } else {
                    $error['keterangan'] = "Data Tidak sama/Password Salah";
                    $error['status'] = 1;
                }
            }
        }
        
        return $error;
    }
}
