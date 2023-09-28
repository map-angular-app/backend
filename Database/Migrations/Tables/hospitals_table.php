<?php

$tables[] = 'hospitals';
$statements[] = 'CREATE TABLE IF NOT EXISTS hospitals( 
        hosidno1        VARCHAR(10) NOT NULL,
        hospital_name   VARCHAR(255) NOT NULL,
        address1        TEXT NULL, 
        address2        TEXT NULL,
        city_name       VARCHAR(255) NOT NULL,
        state_name      VARCHAR(255) NOT NULL,
        pin_code        VARCHAR(10) NULL,
        lat             FLOAT(12, 10) NULL,
        lng             FLOAT(13, 10) NULL,
        PRIMARY KEY(hosidno1)
    );';
