<?php

$tables[] = 'citys';
$statements[] = 'CREATE TABLE IF NOT EXISTS citys( 
        id              INT AUTO_INCREMENT,
        city_name       VARCHAR(255) NOT NULL,
                lat             FLOAT(12, 10) NULL,
        lng             FLOAT(13, 10) NULL,
        PRIMARY KEY(id),
        UNIQUE(city_name)
    );';
