<?php 
if (isset($_GET['q'])) {
    $search = validate_data($_GET['q']);
    $handle = fopen('dummy_text.txt', 'r');
    $line_number = 1;
    $found = false;

    if ($handle) {
        while (($line = fgets($handle)) !== FALSE) {

            $result = search($search, $line, 101);
            if ($result) {
                $found = true;
                foreach ($result as $index) {
                    echo('Searched word is found at ' . $index . ' in line number: ' . $line_number . '.<br>');
                }
            }
            $line_number++;
        }

        if (!$found) {
            echo("Sorry. Your search word can't be found in the file.<br>");
            echo("<a href='index.php'>Go Back</a>");
        }
        
        fclose($handle);
    }
}

function validate_data($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//Rabin-Karp Pattern Search Algorithm
function search($search, $text, $q){
    $search_length = strlen($search);
    $text_length = strlen($text);

    $search_hash = 0;
    $text_hash = 0;

    $d = 256;
    $h = 1;

    for ($i=0; $i < $search_length-1; $i++) { 
        $h = ($h * $d) % $q;
    }

    for ($i=0; $i < $search_length; $i++) {
        $search_hash = ($d * $search_hash + ord($search[$i])) % $q;
        $text_hash = ($d * $text_hash + ord($text[$i])) % $q;
    }

    $index = [];

    for ($i=0; $i <= $text_length - $search_length; $i++) { 
        
        if ($search_hash == $text_hash) {
            for ($j=0; $j < $search_length; $j++) { 
                if ($text[$i + $j] !== $search[$j]) {
                    break;
                }
            }
            if ($j == $search_length) {
                $index[] = $i;
            }
        }
        if ($i < $text_length - $search_length) {

            // $text_hash = ($d * ($t - $txt[$i] * $h) + $txt[$i + $M]) % $q;
            $text_hash = ($d * ($text_hash - ord($text[$i]) * $h) + ord($text[$i + $search_length])) % $q;
            if ($text_hash < 0) {
                $text_hash = ($text_hash + $q);
            }
        }
    }
    return $index;
}
