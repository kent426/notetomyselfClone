DROP DATABASE notetome;
CREATE DATABASE notetome;

USE notetome;

CREATE TABLE users (
	user_id INTEGER NOT NULL auto_increment,
	email varchar(100) NOT NULL,
	password varchar(500) NOT NULL default '',
	isactive  INTEGER NULL,
  confirmCode varchar(100) NOT NULL,
	attackTime INTEGER NOT NULL,
	PRIMARY KEY (user_id),
  CONSTRAINT isactRange CHECK (isactive IN (0,1))
);

CREATE TABLE notes (
	notes_id INTEGER NOT NULL auto_increment,
	email varchar(100) NOT NULL ,
	websitesUrls VARCHAR(10000),
	image1 LONGBLOB,
	image2 LONGBLOB,
	image3 LONGBLOB,
	image4 LONGBLOB,
	notes 	TEXT,
	tbd 	TEXT,
	imagecount INTEGER NOT NULL,
	imageindex1 INTEGER,
	imageindex2 INTEGER,
	imageindex3 INTEGER,
	imageindex4 INTEGER,
	PRIMARY KEY (notes_id)
);



