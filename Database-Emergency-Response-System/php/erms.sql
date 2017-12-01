-- Team 66 SQL


SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";




/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


--
-- Database: 'erms'
--


CREATE DATABASE IF NOT EXISTS erms;
USE erms;


-- --------------------------------------------------------


--
-- Table structure for table 'User'
--


DROP TABLE IF EXISTS User;
CREATE TABLE IF NOT EXISTS User (
 Username Varchar(30)  NOT NULL,
 Name Varchar(50) NOT NULL,
 Password Varchar(15) NOT NULL,
 PRIMARY KEY (Username)) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Dumping data for table 'User'
--






-- --------------------------------------------------------


--
-- Table structure for table 'attend'
--


DROP TABLE IF EXISTS Company;
CREATE TABLE IF NOT EXISTS Company (
 Username Varchar(30) NOT NULL,
 HQLocation Varchar(30) NOT NULL,
 PRIMARY KEY (Username),
 FOREIGN KEY (Username) REFERENCES User(Username)) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Dumping data for table 'attend'
--


-- --------------------------------------------------------


DROP TABLE IF EXISTS Individual;
CREATE TABLE IF NOT EXISTS Individual (
 Username Varchar(30) NOT NULL,
 JobTitle Varchar(30) NOT NULL,
 HireDate Date NOT NULL,
 PRIMARY KEY (Username),
 FOREIGN KEY (Username) REFERENCES User(Username)) ENGINE=InnoDB DEFAULT CHARSET=latin1;






DROP TABLE IF EXISTS GovtAgency;
CREATE TABLE IF NOT EXISTS GovtAgency (
 Username Varchar(30) NOT NULL,
 Jurisdiction Varchar(10) NOT NULL,
 PRIMARY KEY (Username),
 FOREIGN KEY (Username) REFERENCES User(Username)) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS Municipality;
CREATE TABLE IF NOT EXISTS Municipality (
 Username Varchar(30) NOT NULL,
 PopSize Decimal Unsigned NOT NULL,
 PRIMARY KEY (Username),
 FOREIGN KEY (Username) REFERENCES User(Username)) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS Functions;
CREATE TABLE IF NOT EXISTS Functions (
 ESFId int NOT NULL AUTO_INCREMENT,
 ESFDescription Varchar(60) NOT NULL,
 PRIMARY KEY (ESFId)) ENGINE=InnoDB DEFAULT CHARSET=latin1;






DROP TABLE IF EXISTS PayPeriods;
CREATE TABLE IF NOT EXISTS PayPeriods(
 TimePeriod Varchar(10) NOT NULL,
 PRIMARY KEY (TimePeriod)) ENGINE=InnoDB DEFAULT CHARSET=latin1;






DROP TABLE IF EXISTS Resources;
CREATE TABLE IF NOT EXISTS Resources (
 ResourceID int NOT NULL AUTO_INCREMENT,
 RscName Varchar(50) NOT NULL,
 Model Varchar(15),
 Longitude Decimal(12,9) NOT NULL,
 Latitude Decimal(12,9) NOT NULL,
 Cost Decimal Unsigned NOT NULL,
 TimePeriod Varchar(10) NOT NULL,
 RscStatus Set('Available', 'In Use', 'In Repair') NOT NULL,
 Username Varchar(30) NOT NULL,
 PRIMARY KEY (ResourceID),
 FOREIGN KEY (Username) REFERENCES User(Username),
 FOREIGN KEY (TimePeriod) REFERENCES PayPeriods(TimePeriod)) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS ResourceESFs;
CREATE TABLE IF NOT EXISTS ResourceESFs(
 ResourceID int NOT NULL,
 ESFId int NOT NULL,
 PRIMARY KEY (ResourceID, ESFId),
 FOREIGN KEY (ResourceID) REFERENCES Resources(ResourceID),
 FOREIGN KEY (ESFId) REFERENCES Functions(ESFId)) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS PrimaryESF;
CREATE TABLE IF NOT EXISTS PrimaryESF(
 ResourceID int NOT NULL,
 ESFId int NOT NULL,
 PRIMARY KEY (ResourceID, ESFId),
 UNIQUE (ResourceID),
 FOREIGN KEY (ResourceID) REFERENCES Resources(ResourceID),
 FOREIGN KEY (ESFId) REFERENCES Functions(ESFId)) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS Repair;
CREATE TABLE IF NOT EXISTS Repair (
 ResourceID int NOT NULL,
 StartDate date NOT NULL,
 NumDays int NOT NULL,
 Started BIT NOT NULL DEFAULT 0,
 PRIMARY KEY (ResourceID, StartDate),
 FOREIGN KEY (ResourceID) REFERENCES Resources(ResourceID)) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS Capabilities;
CREATE TABLE IF NOT EXISTS Capabilities(
 ResourceID int NOT NULL,
 Capability Varchar(30) NOT NULL,
 PRIMARY KEY (ResourceID, Capability),
 FOREIGN KEY (ResourceID) REFERENCES Resources(ResourceID)) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS Incidents;
CREATE TABLE IF NOT EXISTS Incidents (
 IncidentID int NOT NULL AUTO_INCREMENT,
 Description Varchar(30) NOT NULL,
 IncidentDate date NOT NULL,
 Longitude Decimal(12,9) NOT NULL,
 Latitude Decimal(12,9) NOT NULL,
 Username Varchar(30) NOT NULL,
 PRIMARY KEY (IncidentID),
 FOREIGN KEY (Username) REFERENCES User(Username)) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS Requests;
CREATE TABLE IF NOT EXISTS Requests (
 IncidentID int NOT NULL,
 ResourceID int NOT NULL,
 ReturnDate date NOT NULL,
 PRIMARY KEY (IncidentID, ResourceID),
 FOREIGN KEY (ResourceID) REFERENCES Resources(ResourceID),
 FOREIGN KEY (IncidentID) REFERENCES Incidents(IncidentID)) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS Deploys;
CREATE TABLE IF NOT EXISTS Deploys (
 IncidentID int NOT NULL,
 ResourceID int NOT NULL,
 ReturnDate date NOT NULL,
 StartDate date NOT NULL,
 Active BIT NOT NULL DEFAULT 1,
 PRIMARY KEY (IncidentID, ResourceID),
 FOREIGN KEY (ResourceID) REFERENCES Resources(ResourceID),
 FOREIGN KEY (IncidentID) REFERENCES Incidents(IncidentID)) ENGINE=InnoDB DEFAULT CHARSET=latin1;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO  user (Username, Name, Password) VALUES ('tim','Tim Green','tim123');
INSERT INTO  user (Username, Name, Password) VALUES ('cwp','Cody Pope','cody123');
INSERT INTO  user (Username, Name, Password) VALUES ('daniel','Daniel Rozen','daniel123');
INSERT INTO  user (Username, Name, Password) VALUES ('chaitali','Chaitali Patil','chaitali123');
INSERT INTO  user (Username, Name, Password) VALUES ('city_atlanta','City of Atlanta','cityatlanta123');
INSERT INTO  user (Username, Name, Password) VALUES ('city_austin','City of Austin','cityaustin123');
INSERT INTO  user (Username, Name, Password) VALUES ('fema','FEMA','fema123');
INSERT INTO  user (Username, Name, Password) VALUES ('fbi','FBI','fbi123');
INSERT INTO  user (Username, Name, Password) VALUES ('company_a','Company A','companya123');
INSERT INTO  user (Username, Name, Password) VALUES ('company_b','Company B','companyb123');

INSERT INTO  company (Username, HQLocation) VALUES ('company_a','NYC');
INSERT INTO  company (Username, HQLocation) VALUES ('company_b','Dallas');

INSERT INTO  individual (Username, JobTitle, HireDate) VALUES ('tim','title_a','2014-02-01');
INSERT INTO  individual (Username, JobTitle, HireDate) VALUES ('cwp','title_b','2015-07-01');
INSERT INTO  individual (Username, JobTitle, HireDate) VALUES ('daniel','title_c','2016-03-01');
INSERT INTO  individual (Username, JobTitle, HireDate) VALUES ('chaitali','title_a','2016-01-01');

INSERT INTO  govtagency (Username, Jurisdiction) VALUES ('fema','Federal');
INSERT INTO  govtagency (Username, Jurisdiction) VALUES ('fbi','Federal');

INSERT INTO municipality (Username, PopSize) VALUES ('city_atlanta', 12202);
INSERT INTO municipality (Username, PopSize) VALUES ('city_austin', 54200);

INSERT INTO functions (ESFDescription) VALUES ('Transportation');
INSERT INTO functions (ESFDescription) VALUES ('Communication');
INSERT INTO functions (ESFDescription) VALUES ('Public Works and Engineering');
INSERT INTO functions (ESFDescription) VALUES ('Firefighting');
INSERT INTO functions (ESFDescription) VALUES ('Emergency Management');
INSERT INTO functions (ESFDescription) VALUES ('Mass Care, Emergency Assistance, Housing, and Human Services');
INSERT INTO functions (ESFDescription) VALUES ('Logistics Management and Resource Support');
INSERT INTO functions (ESFDescription) VALUES ('Public Health and Medical Services');
INSERT INTO functions (ESFDescription) VALUES ('Search and Rescue');
INSERT INTO functions (ESFDescription) VALUES ('Oil and Hazardous Material Response');
INSERT INTO functions (ESFDescription) VALUES ('Agriculture and Natural Resources');
INSERT INTO functions (ESFDescription) VALUES ('Energy');
INSERT INTO functions (ESFDescription) VALUES ('Public Safety and Security');
INSERT INTO functions (ESFDescription) VALUES ('Long-Term Community Recovery');
INSERT INTO functions (ESFDescription) VALUES ('External Affairs');

INSERT INTO payperiods (TimePeriod) VALUES ('Hour');
INSERT INTO payperiods (TimePeriod) VALUES ('Day');
INSERT INTO payperiods (TimePeriod) VALUES ('Week');


