DROP TABLE IF EXISTS Person;
DROP TABLE IF EXISTS Organization;
DROP TABLE IF EXISTS Winners;
DROP TABLE IF EXISTS Prizes;
DROP TABLE IF EXISTS Affiliations;

CREATE TABLE Person (
    id int not null,
    givenName varchar(50),
    familyName varchar(50),
    gender varchar(10),
    birthdate varchar(12),
    city varchar(50),
    country varchar(50),
    PRIMARY KEY(id)
);


CREATE TABLE Organization (
    id int not null,
    orgName varchar(100),
    foundeddate varchar(12),
    city varchar(50),
    country varchar(50),
    PRIMARY KEY(id)
);

CREATE TABLE Winners (
    id int not null,
    awardYear int,
    category varchar(50),
    sortOrder int,
    portion varchar(5),
    prizeStatus varchar(20),
    motivation varchar(500)
);

CREATE TABLE Prizes (
    awardYear int,
    category varchar(50),
    dateAwarded varchar(12),
    prizeAmount int,
    PRIMARY KEY(awardYear, category)
);

CREATE TABLE Affiliations (
    id int,
    dateAwarded varchar(12),
    prizeAmount int,
    aff_name varchar(200),
    aff_city varchar(100),
    aff_country varchar(100)
);

LOAD DATA LOCAL INFILE '/home/cs143/shared/convert/person.del' INTO TABLE Person
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"';

LOAD DATA LOCAL INFILE '/home/cs143/shared/convert/organization.del' INTO TABLE Organization
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"';

LOAD DATA LOCAL INFILE '/home/cs143/shared/convert/winners.del' INTO TABLE Winners
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"';

LOAD DATA LOCAL INFILE '/home/cs143/shared/convert/prizes.del' INTO TABLE Prizes
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"';

LOAD DATA LOCAL INFILE '/home/cs143/shared/convert/aff.del' INTO TABLE Affiliations
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"';
