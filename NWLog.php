<?php
	/*
		   /\
		  /  \   NitricWare Studios
		 / /\ \  Linz/Danube
		/\/\/\/
		\/
	
		NitricWare presents
		NWWriteLog 1.0
		Oyster FSS CMD Edition
		Version 1.0
		
		Development started
		15. December 2012
		
		This function is designed to create a log
		and add lines to the created log.
		
		The filename is the timestamp of the
		current day at 00:00 by default. This
		function automatically finds the correct
		log in the given directory. If log does
		not exist, file will be created.
		
		The time (hh:mm:ss) is added too.
	*/
	
	function NWWriteLog($line, $path = "./Logs/"){
		
		$date = strtotime(date("d-m-y", time()));
		$file = "log_".$date.".txt";
		$time = date("h:i:s", time());
		
		$backtrace = debug_backtrace();
		if (array_key_exists(1, $backtrace)){
			$t = $backtrace[1];
		} else {
			$t = array("function" => "NWWriteLog");	
		}
		
		if ($t["function"] == "include"){
			$p = pathinfo($t["args"][0]);
			$sender = $t["function"]."(".$p["basename"].":".$backtrace[0]["line"].")";
		} elseif (array_key_exists("class", $t)){
			$p = pathinfo($backtrace[2]["args"][0]);
			$sender = $t["class"].$t["type"].$t["function"]."() (".$p["basename"].":".$backtrace[0]["line"].")";
		} else {
			$p = pathinfo($backtrace[2]["args"][0]);
			$sender = $t["function"]."() (".$p["basename"].":".$backtrace[0]["line"].")";
		}
		
		file_put_contents($path.$file, "$time $sender: $line\n", FILE_APPEND);
	}
	
	function NWDeleteLog($date = "today", $path = "./Logs/"){
		
		if ($date = "today"){
			$date = strtotime(date("d-m-y", time()));
		}
		
		$file = "log_".$date.".txt";
		
		if (file_exists($path.$file)){
			if (!NWDelete($path.$file, false)){
				if (DEBUG) NWWriteLog("Couldn't clear history.");
				return false;
			} else {
				if (DEBUG) NWWriteLog("Log cleared.");
				return true;
			}
		} else {
			return false;
		}
	}
	
	function NWPrintLog($offset = 0, $maxLenght = false, $date = "today",$path = "./Logs/"){
		if ($date = "today"){
			$date = strtotime(date("d-m-y", time()));
		}
		$file = "log_".$date.".txt";
		
		if ($logRaw = file($path.$file)){
			$logLinesCount = count($logRaw);
		} else {
			if (DEBUG) NWWriteLog("Requested log does not exist.");
			return false;
		}
		
		if ($offset > $logLinesCount OR $offset < 0){
			if (DEBUG) NWWriteLog("Parameter offset failed.");
			return false;	
		}
		
		if (!$maxLenght){
			$maxLenght = $logLinesCount - 1;	
		}
		
		if ($maxLenght > $logLinesCount OR $maxLenght < 0){
			if (DEBUG) NWWriteLog("Parameter maxLenght failed.");
			return false;	
		}
		
		$logString = "";
		
		foreach($logRaw as $key => $value){
			if ($key >= $offset AND $key <= $maxLenght){
				$logString .= $value;	
			}
		}
		
		return $logString;
	}
?>