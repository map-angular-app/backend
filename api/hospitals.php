<?php

include '../Database/Query/SqlClauses.php';
include '../Database/Query/BaseSqlBuilder.php';
include '../Database/Query/MySqlBuilder.php';
include '../connection.php';
include '../Constants/Filter.php';
require_once '../Helpers/Str2UTF8.php';

use Database\Query\MySqlBuilder;
use Constants\Filter;

// create a new mysql instance.
$builder = new MySqlBuilder($connection);
$query = $builder;

$type = isset($_GET['type']) ? $_GET['type'] : null;
$value = isset($_GET['value']) ? $_GET['value'] : null;
$lat = isset($_GET['lat']) ? $_GET['lat'] : null;
$lng = isset($_GET['lng']) ? $_GET['lng'] : null;
$radius = isset($_GET['radius']) ? $_GET['radius'] : 20;
if (isset($type)) {
    switch ($type) {
        case FILTER::CURRENT:
            if (isset($lat) && isset($lng)) {
                $query->selectRaw(['*', "2*6371*ASIN(SQRT(SIN(($lat-lat)*PI()/360)*SIN(($lat-lat)*PI()/360)
                                    +COS(($lat*PI()/180))*COS((lat*PI()/180))
                                    *SIN(($lng-lng)*PI()/360)*SIN(($lng-lng)*PI()/360)))
                                    AS distance"])
                    ->from('hospitals');
                $query->having('distance', '<=', $radius);
                $query->orderBy('distance', false);
            }
            break;
        case FILTER::CITY:
            if (isset($lat) && isset($lng)) {
                $query->selectRaw(['*', "2*6371*ASIN(SQRT(SIN(($lat-lat)*PI()/360)*SIN(($lat-lat)*PI()/360)
                                +COS(($lat*PI()/180))*COS((lat*PI()/180))
                                *SIN(($lng-lng)*PI()/360)*SIN(($lng-lng)*PI()/360)))
                                AS distance"])
                    ->from('hospitals');
                $query->having('distance', '<=', $radius);
                $query->orderBy('distance', false);
            }
            break;
        case FILTER::HOSPITAL:
            if (isset($value)) $query->select()->where('hospital_name', 'like', "'$value%'")->from('hospitals');
            break;
        default:
            break;
    }
}

$data = $builder->all();
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');
echo json_encode(utf8ize($data));
