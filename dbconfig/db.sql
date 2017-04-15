DROP DATABASE notetome;
CREATE DATABASE notetome;

USE notetome;

CREATE TABLE users (
	user_id INTEGER NOT NULL auto_increment,
	email varchar(100) NOT NULL,
	password varchar(500) NOT NULL default '',
	isactive  INTEGER NULL,
  confirmCode varchar(100) NOT NULL,
	PRIMARY KEY (user_id),
  CONSTRAINT isactRange CHECK (isactive IN (0,1))
);

CREATE TABLE notes (

	notes_id INTEGER NOT NULL auto_increment,
	email varchar(100),
	websitesUrls VARCHAR(10000),
	image1 MEDIUMBLOB,
	image2 MEDIUMBLOB,
	image3 MEDIUMBLOB,
	image4 MEDIUMBLOB,
	notes 	TEXT,
	tbd 	TEXT,
	PRIMARY KEY (notes_id),
	FOREIGN KEY (email) REFERENCES users(email)
);

