<?php

class Azdgcrypt {

	var $k = '3d11@0s5'; 

	function setKey($m) {
		$this->k = $m;
	}

	function ed($t) {
		$r = md5($this->k);
		$c = 0;
		$v = "";
		for ($i = 0; $i < strlen($t); $i++) {
			if ($c == strlen($r)) {
				$c = 0;
			}

			$v .= substr($t, $i, 1)^substr($r, $c, 1);
			$c++;
		}
		return $v;
	}

	function crypt($t) {
		srand((double) microtime()*1000000);
		$r  = md5(rand(0, 32000));
		$c  = 0;
		$v  = "";
		$ln = strlen($t);
		for ($i = 0; $i < $ln; $i++) {
			if ($c == strlen($r)) {
				$c = 0;
			}

			$v .= substr($r, $c, 1).
			(substr($t, $i, 1)^substr($r, $c, 1));
			$c++;
		}
		$rtn = str_replace('/', 'test', base64_encode($this->ed($v)));
		return $rtn;
	}

	function decrypt($t) {
		$t = str_replace('test', '/', $t);
		$t = $this->ed(base64_decode($t));
		$v = "";
		for ($i = 0; $i < strlen($t); $i++) {
			$md5 = substr($t, $i, 1);
			$i++;
			$v .= (substr($t, $i, 1)^$md5);
		}
		return $v;
	}

	function enkrip($str) {
		$kunci = '0ss2o!9!!!';
        $key = base64_encode($kunci);
        $enkrip = base64_encode($str);
        $hasil = '';

        for($a = 0; $a < strlen($enkrip); $a++) {
            $hasil .= $enkrip[$a];
            if($a == 5) {
                $hasil .= $key;
            }
        }

        echo $hasil;
	}
	

    function dekrip($str) {
		$kunci = '0ss2o!9!!!';
        $key = base64_encode($kunci);
        $hasil = base64_decode(str_replace($key, '', $str));

        echo $hasil;
    }

}
