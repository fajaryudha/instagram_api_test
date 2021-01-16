<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Main extends CI_Model
{

    function convertDate($srcFormat, $desFormat, $date)
    {
        $arrDate = date_parse_from_format($srcFormat, $date);
        if ($date != '') {
            switch ($desFormat) {
                case 'Y-m-d':
                    $rtn = $arrDate['year'] . '-' . substr('00' . $arrDate['month'], -2) . '-' . substr('00' . $arrDate['day'], -2);
                    break;
                case 'd-m-Y':
                    $rtn = substr('00' . $arrDate['day'], -2) . '-' . substr('00' . $arrDate['month'], -2) . '-' . $arrDate['year'];
                    break;
            }
        }
        return $rtn;
    }

    function manual_date($date)
    {
        $ret = "";
        $bulan = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        $tgl = explode('-', $date);
        if ($tgl[2] < 10) {
            $tgl[2] = str_replace('0', '', $tgl[2]);
        }
        $tgla = (int) $tgl[1];
        $tgla = $bulan[$tgla];
        $ret = "$tgl[2] $tgla $tgl[0]";
        return $ret;
    }


    // FUNCTION insertUpdate

    function insertUpdate($strTable, $arrData, $arrPk = array())
    {

        if (count(array_keys($arrPk)) == 0) {
            $jml = 0;
        } else {
            $queryLegal = $this->db->select("*")
                ->where($arrPk)
                ->limit(1)
                ->get_compiled_select($strTable);
            $jml = count($this->Main->get_rows($queryLegal, TRUE));
        }
        if ((int) $jml == 0) {
            $exec = $this->db->insert($strTable, $arrData);
            // $exec = $this->db->set($arrData)->get_compiled_insert($strTable);
        } else if ($jml > 0) {
            $this->db->where($arrPk);
            $exec = $this->db->update($strTable, $arrData);
        }
        return $exec;
    }


    function get_uraian($query, $select)
    {
        $data = $this->db->query($query);
        if ($data->num_rows() > 0) {
            $row = $data->row();
            return $row->$select;
        } else {
            return "";
        }
        return 1;
    }

    function get_rows($query, $many = false)
    {
        #get who is the date
        $data = $this->db->query($query);
        // dd($data);
        if ($data) {
            // dd($data);
            $arr = $data->result_array();

            if ($many) {
                $dataarray = $arr;
            } else {
                $arrEnd = end($arr);

                if ($arrEnd != '') {
                    foreach ($arrEnd as $k => $v) {
                        $dataarray[$k] = $v;
                    }
                } else {
                    $dataarray = '';
                }
            }
        } else {
            $dataarray = '';
        }

        return $dataarray;
    }
}
