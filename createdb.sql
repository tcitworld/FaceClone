DROP TABLE IF EXISTS MP;
DROP TABLE IF EXISTS CONVERSATION;
DROP TABLE IF EXISTS POST;
DROP TABLE IF EXISTS COMMENT;
DROP TABLE IF EXISTS MEMBER;

CREATE TABLE MEMBER (
	idmembre int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom VARCHAR(40),
	prenom VARCHAR(40),
	mail VARCHAR(40),
	dateNaissance DATE,
	password VARCHAR(40),
	dateInscription DATE,
	dateLastConnexion DATE);

CREATE TABLE POST (
	idpost int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	idmembre int,
	contenupost TEXT,
	datemessage DATE,
	CONSTRAINT FKPostMembre FOREIGN KEY (idmembre) REFERENCES MEMBER(idmembre));

CREATE TABLE COMMENT (
	idcomment int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	idmembre int,
	contenucomment TEXT,
	datecommentaire DATE,
	CONSTRAINT FKCommentMembre FOREIGN KEY (idmembre) REFERENCES MEMBER(idmembre));

CREATE TABLE CONVERSATION (
	idconversation int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	idmembreorig int,
	idmembredest int,
	CONSTRAINT FKMembreOrig FOREIGN KEY (idmembreorig) REFERENCES MEMBER(idmembre),
	CONSTRAINT FKMembreDest FOREIGN KEY (idmembredest) REFERENCES MEMBER(idmembre));

CREATE TABLE MP (
	idmp int NOT NULL AUTO_INCREMENT PRIMARY KEY,
	idconversation int,
	contenump TEXT,
	datemp DATE,
	CONSTRAINT FKMPConversation FOREIGN KEY (idconversation) REFERENCES CONVERSATION(idconversation));