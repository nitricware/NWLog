<?php
	/*
		   /\
		  /  \   NitricWare Studios
		 / /\ \  Linz/Danube
		/\/\/\/
		\/
	
		NitricWare presents
		NWWriteLog 1.0.3
		
		Development started
		15. December 2012
		Github release
		7. May 2015
		
		This function is designed to create a log
		and add lines to the created log.
		
		The filename is the timestamp of the
		current day at 00:00 by default. This
		function automatically finds the correct
		log in the given directory. If log does
		not exist, file will be created.
		
		The time (hh:mm:ss) is added too.
	*/
	
	namespace NitricWare;
	
	/*
		NWWriteLog
			Adds a line to the log. Filename is timestamp.
		
		$line
			The line you want to add.
			
		$customBacktrace
			Send your own debug_backtrace() with the function call.
			This can be handy when you call NWWriteLog() from within
			an error handling function.
			
		$path
			The path to your logs.
	*/
	
	function NWWriteLog($line, $customBacktrace = false, $path = "./Logs/"){
		
		$date = strtotime(date("d-m-y", time()));
		$file = "log_".$date.".txt";
		$time = date("h:i:s", time());
		
		$backtrace = debug_backtrace();
		
		if ($customBacktrace){
			$backtrace = $customBacktrace;
		}			
		
		$pathinfo = pathinfo($backtrace[0]["file"]);

		if (array_key_exists(1, $backtrace)){
			$t = $backtrace[1];
		} else {
			$t = array("function" => "file");	
		}
		
		if ($t["function"] == "include"){
			$sender = $t["function"]."(".$pathinfo["basename"].":".$backtrace[0]["line"].")";
		} elseif (array_key_exists("class", $t)){
			// Function is called from within a class
			$sender = $t["class"].$t["type"].$t["function"]."() (".$pathinfo["basename"].":".$backtrace[0]["line"].")";
		} else {
			$sender = $t["function"]."() (".$pathinfo["basename"].":".$backtrace[0]["line"].")";
		}
		
		file_put_contents($path.$file, "$time $sender - $line\n", FILE_APPEND);
	}
	
	/*
		NWDeleteLog
			Deletes a log file.
		
		$date
			"today": today's timestamp is used as filename
			any int: timestamp of the file
			
		$path
			The path to your logs.
	*/
	
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
	
	/*
		NWPrintLog
			Returns the specified lines of a log file.
		
		$offset
			int: where to begin
			
		$maxLenghts
			int: where to stop
			false: til the end
		
		$date
			"today": today's timestamp is used as filename
			any int: timestamp of the file
		
		$path
			The path to your logs.
	*/
	
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