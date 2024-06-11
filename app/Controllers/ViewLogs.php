<?php
namespace App\Controllers;

class ViewLogs extends BaseController
{
	public function view($date = NULL, $ln_from = 0)
	{
		if(empty($date)) $date = date('Y-m-d');
		$ln = 0;
		foreach (file(WRITEPATH . '/logs/log-'.$date.'.log') as $line) {
			$ln++;
			if(!empty($ln_from) && $ln < $ln_from) continue;
			echo $ln.". ".$line."<hr/>";
		}
		die();
	}
}
