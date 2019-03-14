CREATE DATABASE misc;
GRANT ALL ON misc.* TO 'dan'@'localhost' IDENTIFIED BY 'php123';
GRANT ALL ON misc.* TO 'dan'@'127.0.0.1' IDENTIFIED BY 'php123';

ALTER TABLE [table_name] AUTO_INCREMENT = [x];

DROP TABLE IF EXISTS User;
CREATE TABLE User (
	user_id INTEGER NOT NULL AUTO_INCREMENT,
	name VARCHAR(128),
    email VARCHAR(128),
    password VARCHAR(128),
    PRIMARY KEY(user_id)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE User ADD INDEX(email);
ALTER TABLE User ADD INDEX(password);

CREATE TABLE Profile (
    profile_id INTEGER NOT NULL AUTO_INCREMENT,
    user_id INTEGER NOT NULL,
    first_name TEXT,
    last_name TEXT,
    email TEXT,
    headline TEXT,
    summary TEXT,
    PRIMARY KEY(profile_id),
    CONSTRAINT profile_ibfk_2
        FOREIGN KEY (user_id)
        REFERENCES User (user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Position (
    position_id INTEGER NOT NULL AUTO_INCREMENT,
    profile_id INTEGER,
    rank INTEGER,
    year INTEGER,
    description TEXT,
    PRIMARY KEY (position_id),
    CONSTRAINT position_ibfk_1
        FOREIGN KEY (profile_id)
        REFERENCES Profile (profile_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Institution (
    institution_id INTEGER NOT NULL KEY AUTO_INCREMENT,
    name VARCHAR(255),
    UNIQUE (name),
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Education (
    profile_id INTEGER,
    institution_id INTEGER,
    rank INTEGER,
    year INTEGER,
    CONSTRAINT education_ibfk_1
        FOREIGN KEY (profile_id)
        REFERENCES Profile (profile_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT education_ibfk_2
        FOREIGN KEY (institution_id)
        REFERENCES Institution (institution_id
        ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (profile_id, institution_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO User (name, email, password)
VALUES ('danilo', 'a@a.com', 'php123');
VALUES ('ricardo', 'b@b.com', 'senha');

UPDATE table SET column = new_value WHERE condition