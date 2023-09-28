<?php

include '../Database/Query/SqlClauses.php';
include '../Database/Query/BaseSqlBuilder.php';
include '../Database/Query/MySqlBuilder.php';
include '../connection.php';
require_once '../Helpers/Str2UTF8.php';

use Database\Query\MySqlBuilder;

// create a new mysql instance.
$builder = new MySqlBuilder($connection);
$builder = $builder->select()
    ->from('citys');
if (isset($page)) {
    $builder->paginate($page, isset($perpage) ? $perpage : 10);
}

if (isset($sort)) {
    $builder->orderBy($sort, isset($sort_desc) ? $sort_desc : false);
}

$data = $builder->all();
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');
echo json_encode(utf8ize($data));
