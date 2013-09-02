<?php

error_reporting(E_ALL); 

echo "Starting. Connecting..."; 

//setup php for working with Unicode data
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler'); 

include('dblayer.php'); 

echo "Connected to database."; 

//make the table
$result=dbquery("CREATE TABLE IF NOT EXISTS lang_dict ( 
	language VARCHAR(30),
	code CHAR(3)
	)") or die("Failed to create table."); 

//make the table Unicode-compatable
$result=dbquery("ALTER TABLE etym_dict CHARACTER SET utf8 COLLATE utf8_general_ci")
	or die("Failed to create table."); 

function parse($line) { 
		$data=str_getcsv($line); 
		$lang=$data[0]; 
		$code=$data[1]; 
		$query="INSERT INTO etym_dict(language, code) VALUES (\"$lang\",\"$code\")"; 
		$result=dbquery($query)
			or die ("There was a problem inserting language codes into the database."); 
	} 
} 

//open the etymological wordnet for parsing
$handle = fopen("langs.csv", 'r'); 
if ($handle) {
    while (($line = fgets($handle, 4096)) !== false ) {
        parse($line);
   }
 if (!feof($handle)) {
      echo "Error: unexpected fgets() fail\n";
    }
   fclose($handle);
}

echo "Closing database connection."; 
dbclose($dbc); 

?> 