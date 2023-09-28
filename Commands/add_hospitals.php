<?php

include "vendor/autoload.php";

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

$env = parse_ini_file('.env');
$dir_path = dirname(__FILE__);

$input_path = isset($argv[1]) ? $argv[1] : null;
$output_path = isset($argv[2]) ? $argv[2] : $env['OUTPUT_PATH'];
if (empty($input_path)) die('Not givent input path');

$process = new Process(["{$env['PYTHON_PATH']}", "$dir_path/script.py", $input_path, $output_path]);
$process->run();
if (!$process->isSuccessful()) {
    throw new ProcessFailedException($process);
}
print_r($process->getOutput());
echo ("\nImport data into hospitals table start");

$commands = [];
$mysql_script = "load data local infile '$output_path' replace into table hospitals
fields terminated by ',' enclosed by '\\\"' lines terminated by '\\n'
ignore 1 lines (hosidno1, hospital_name, address1, address2, city_name, state_name, pin_code, lat, lng)";

if (isset($env['DB_PASSWORD']) && $env['DB_PASSWORD'] != "") {
    $commands = [
        $env['MYSQL_PATH'],
        "--host={$env['DB_HOST']}",
        "--port={$env['DB_PORT']}",
        "-u",
        "{$env['DB_USERNAME']}",
        "-p{$env['DB_PASSWORD']}",
        "{$env['DB_DATABASE']}",
        "-e",
        $mysql_script
    ];
} else {
    $commands = [
        $env['MYSQL_PATH'],
        "--host={$env['DB_HOST']}",
        "--port={$env['DB_PORT']}",
        "-u",
        "{$env['DB_USERNAME']}",
        "{$env['DB_DATABASE']}",
        "-e",
        $mysql_script
    ];
}
$process = new Process($commands);
$process->run();
if (!$process->isSuccessful()) {
    throw new ProcessFailedException($process);
}

echo ("\nImport success\n");

// if (file_exists($output_path)) {
//     unlink($output_path);
// }

exit();
