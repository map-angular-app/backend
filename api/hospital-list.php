<?php

include '../Database/Query/SqlClauses.php';
include '../Database/Query/BaseSqlBuilder.php';
include '../Database/Query/MySqlBuilder.php';
include '../connection.php';
require_once '../Helpers/Str2UTF8.php';

use Database\Query\MySqlBuilder;

// create a new mysql instance.
$builder = new MySqlBuilder($connection);
$builder = $builder->select(['distinct(hospital_name)', 'count(hospital_name) as total'])
    ->from('hospitals')
    ->groupBy('hospital_name');
if (isset($page)) {
    $builder->paginate($page, isset($perpage) ? $perpage : 10);
} else {
    $builder->paginate(1, 20);
}

if (isset($sort)) {
    $builder->orderBy($sort, isset($sort_desc) ? $sort_desc : false);
} else {
    $builder->orderBy('total', true);
}

$data = $builder->all();
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');
echo json_encode(utf8ize($data));
