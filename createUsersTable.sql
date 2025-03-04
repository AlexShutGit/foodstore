CREATE DATABASE UF NOT EXISTS foodstore
USE foodstore
CREATE TABLE IF NOT EXISTS users (
    user_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    username varchar(255) NOT NULL,
    login varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    PRIMARY KEY (user_id)
) ENGINE = InnoDB;