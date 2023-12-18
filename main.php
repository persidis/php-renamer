<?php
require_once './alt_autoload.php-dist';
$arrayForCSV = array(array()); // Two-dimensional array
$path = './putFilesHere';
$csvFile = './log.csv';
$myString = testInput($_POST["name"]);
switch (testInput($_POST["options"])) {
  case "space":
    $option = 1;
    break;
  case "together":
    $option = 0;
    break;
  case "twoSpaces":
    $option = 2;
    break;
  case "threeSpaces":
    $option = 3;
    break;	
  case "fourSpaces":
    $option = 4;
    break;	
  default:
    $option = 1;
}

// ----------------- Globals ---------------------

function testInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function print2D($array, $csvFile) {
	$myfile = fopen($csvFile, "w") or die("Unable to open file!");
	$keys = array_keys($array);
	for($i = 1; $i < count($array); $i++) { // Η πρώτη γραμμή του πίνακα είναι κενή και δεν τη θέλουμε
		fwrite($myfile, $keys[$i] . ";");
		$int_count=0;
		foreach($array[$keys[$i]] as $key => $value) {
			if(++$int_count < count($array[$keys[$i]])) {
				fwrite($myfile, $value . ";");
			}
			else
				fwrite($myfile, $value);
		}
		fwrite($myfile, "\r\n");
	}
	fclose($myfile);
}
function findKey($array, $keySearch) {   // Not used
    foreach ($array as $key => $item) {
        if ($key === $keySearch) {
            //echo 'yes, it exists';
            return true;
        }
    }
    return false;
}

function findKey2D($array, $keySearch) {   // Not used
    foreach ($array as $key => $item) {
        if ($key === $keySearch) {
            echo 'yes, it exists';
            return true;
        } elseif (is_array($item) && findKey($item, $keySearch)) {
            return true;
        }
    }
    return false;
}
// --------------------------------------------------------------------------



// Scan Directory for Files
echo "Όρος προς αναζήτηση: " . $myString . '<br><br>';
$files = array_diff(scandir($path), array('.', '..'));

// Loop all files and look for data
$parser = new \Smalot\PdfParser\Parser();
foreach($files as $file) {
	$pdf = $parser->parseFile($path . '/' . $file);
	$textFromFile = $pdf->getText();
	echo $textFromFile;
	if($substr = strstr( $textFromFile, $myString )) {  // If data was found ...
		$piecesUnfiltered = explode(" ", $substr);
		$piecesFiltered = array_filter($piecesUnfiltered);
		$pieces = array_values($piecesFiltered);
		echo '<br>' . "Text after search word" . '<br>';
		// echo $substr;
		print_r($pieces);
		echo '<br>';
		// Βάζουμε επίθεμα στο αρχείο
		// pieces[1] για να βρει την επόμενη λέξη μετά το κενό. pieces[0] για κάτι κολλητό σε αυτό που ψάχνουμε!
		if(!empty($arrayForCSV [strtok($pieces[$option], chr(10))])) {  // Αν υπάρχει ήδη κι άλλο αρχείο
			$counter = count($arrayForCSV[strtok($pieces[$option], chr(10))]);
			$counter++;
			$renameTo = strtok($pieces[$option], chr(10)) . '_' . $counter . ".pdf";
			$arrayForCSV[strtok($pieces[$option], chr(10))][] = $renameTo;
		}
		else {
			$renameTo = strtok($pieces[$option], chr(10)) . '_' . '4_06' . ".pdf";
			$arrayForCSV[strtok($pieces[$option], chr(10))][] = $renameTo;
		}
		copy($path . '/' . $file, $path . '/' . trim($renameTo));
		echo "Επιτυχής μετονομασία του " . $file . " σε " . trim($renameTo);
		echo '<br>';		
	}
}

print2D($arrayForCSV, $csvFile);
//var_dump($arrayForCSV);
