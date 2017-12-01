-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 27, 2016 at 06:15 PM
-- Server version: 5.6.34
-- PHP Version: 5.6.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: erms
--
CREATE DATABASE IF NOT EXISTS erms DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE erms;

-- --------------------------------------------------------

--
-- Table structure for table capabilities
--

CREATE TABLE capabilities (
  ResourceID int(11) NOT NULL,
  Capability varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table capabilities
--

INSERT INTO capabilities (ResourceID, Capability) VALUES
(1, 'Carrying Heavy Loads'),
(1, 'Run Over Things'),
(2, 'Barking'),
(2, 'Running'),
(5, 'Extinguishing Fires'),
(5, 'Jaws of Life'),
(5, 'Saving People'),
(6, 'Energy Efficient'),
(10, 'Dog Leash');

-- --------------------------------------------------------

--
-- Table structure for table company
--

CREATE TABLE company (
  Username varchar(30) NOT NULL,
  HQLocation varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table company
--

INSERT INTO company (Username, HQLocation) VALUES
('company_a', 'NYC'),
('company_b', 'Dallas');

-- --------------------------------------------------------

--
-- Table structure for table deploys
--

CREATE TABLE deploys (
  IncidentID int(11) NOT NULL,
  ResourceID int(11) NOT NULL,
  ReturnDate date NOT NULL,
  StartDate date NOT NULL,
  Active bit(1) NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table deploys
--

INSERT INTO deploys (IncidentID, ResourceID, ReturnDate, StartDate, Active) VALUES
(2, 1, '2016-11-30', '2016-11-27', b'1'),
(5, 2, '2016-11-26', '2016-11-25', b'1'),
(7, 10, '2016-11-24', '2016-11-14', b'1');

-- --------------------------------------------------------

--
-- Table structure for table functions
--

CREATE TABLE functions (
  ESFId int(11) NOT NULL,
  ESFDescription varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table functions
--

INSERT INTO functions (ESFId, ESFDescription) VALUES
(1, 'Transportation'),
(2, 'Communication'),
(3, 'Public Works and Engineering'),
(4, 'Firefighting'),
(5, 'Emergency Management'),
(6, 'Mass Care, Emergency Assistance, Housing, and Human Services'),
(7, 'Logistics Management and Resource Support'),
(8, 'Public Health and Medical Services'),
(9, 'Search and Rescue'),
(10, 'Oil and Hazardous Material Response'),
(11, 'Agriculture and Natural Resources'),
(12, 'Energy'),
(13, 'Public Safety and Security'),
(14, 'Long-Term Community Recovery'),
(15, 'External Affairs');

-- --------------------------------------------------------

--
-- Table structure for table govtagency
--

CREATE TABLE govtagency (
  Username varchar(30) NOT NULL,
  Jurisdiction varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table govtagency
--

INSERT INTO govtagency (Username, Jurisdiction) VALUES
('fbi', 'Federal'),
('fema', 'Federal');

-- --------------------------------------------------------

--
-- Table structure for table incidents
--

CREATE TABLE incidents (
  IncidentID int(11) NOT NULL,
  Description varchar(30) NOT NULL,
  IncidentDate date NOT NULL,
  Longitude decimal(12,9) NOT NULL,
  Latitude decimal(12,9) NOT NULL,
  Username varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table incidents
--

INSERT INTO incidents (IncidentID, Description, IncidentDate, Longitude, Latitude, Username) VALUES
(1, 'Food Shortage', '2016-11-01', '20.000000000', '10.000000000', 'tim'),
(2, 'Forest Fire', '2016-11-08', '-16.000000000', '-59.000000000', 'tim'),
(3, 'Food Shortage', '2016-11-01', '20.000000000', '10.000000000', 'city_atlanta'),
(4, 'Earthquake', '2016-11-03', '-120.000000000', '36.000000000', 'city_atlanta'),
(5, 'Tsunami', '2016-11-23', '0.000000000', '0.000000000', 'city_atlanta'),
(6, 'Blizzard', '2016-11-24', '-178.000000000', '0.000000000', 'city_atlanta'),
(7, 'Zombie Apocalypse', '2016-11-25', '19.000000000', '-3.000000000', 'tim');

-- --------------------------------------------------------

--
-- Table structure for table individual
--

CREATE TABLE individual (
  Username varchar(30) NOT NULL,
  JobTitle varchar(30) NOT NULL,
  HireDate date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table individual
--

INSERT INTO individual (Username, JobTitle, HireDate) VALUES
('chaitali', 'title_a', '2016-01-01'),
('cwp', 'title_b', '2015-07-01'),
('daniel', 'title_c', '2016-03-01'),
('tim', 'title_a', '2014-02-01');

-- --------------------------------------------------------

--
-- Table structure for table municipality
--

CREATE TABLE municipality (
  Username varchar(30) NOT NULL,
  PopSize decimal(10,0) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table municipality
--

INSERT INTO municipality (Username, PopSize) VALUES
('city_atlanta', '12202'),
('city_austin', '54200');

-- --------------------------------------------------------

--
-- Table structure for table payperiods
--

CREATE TABLE payperiods (
  TimePeriod varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table payperiods
--

INSERT INTO payperiods (TimePeriod) VALUES
('Day'),
('Hour'),
('Week');

-- --------------------------------------------------------

--
-- Table structure for table primaryesf
--

CREATE TABLE primaryesf (
  ResourceID int(11) NOT NULL,
  ESFId int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table primaryesf
--

INSERT INTO primaryesf (ResourceID, ESFId) VALUES
(1, 1),
(2, 9),
(3, 5),
(4, 13),
(5, 4),
(6, 11),
(7, 9),
(8, 2),
(9, 12),
(10, 9);

-- --------------------------------------------------------

--
-- Table structure for table `repair`
--

CREATE TABLE `repair` (
  ResourceID int(11) NOT NULL,
  StartDate date NOT NULL,
  NumDays int(11) NOT NULL,
  Started bit(1) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `repair`
--

INSERT INTO `repair` (ResourceID, StartDate, NumDays, Started) VALUES
(1, '2016-11-30', 2, b'0'),
(5, '2016-11-27', 7, b'1'),
(6, '2016-11-27', 2, b'1'),
(2, '2016-11-26', 3, b'0'),
(10, '2016-11-24', 1, b'0');

-- --------------------------------------------------------

--
-- Table structure for table requests
--

CREATE TABLE requests (
  IncidentID int(11) NOT NULL,
  ResourceID int(11) NOT NULL,
  ReturnDate date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table requests
--

INSERT INTO requests (IncidentID, ResourceID, ReturnDate) VALUES
(1, 4, '2016-11-29'),
(2, 1, '2016-11-30'),
(5, 2, '2016-11-27'),
(6, 1, '2016-11-28'),
(7, 9, '2016-12-25'),
(7, 10, '2016-11-24');

-- --------------------------------------------------------

--
-- Table structure for table resourceesfs
--

CREATE TABLE resourceesfs (
  ResourceID int(11) NOT NULL,
  ESFId int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table resourceesfs
--

INSERT INTO resourceesfs (ResourceID, ESFId) VALUES
(5, 5),
(7, 5),
(8, 5),
(2, 6),
(7, 6),
(3, 9),
(6, 13),
(1, 14);

-- --------------------------------------------------------

--
-- Table structure for table resources
--

CREATE TABLE resources (
  ResourceID int(11) NOT NULL,
  RscName varchar(50) NOT NULL,
  Model varchar(15) DEFAULT NULL,
  Longitude decimal(12,9) NOT NULL,
  Latitude decimal(12,9) NOT NULL,
  Cost decimal(10,0) UNSIGNED NOT NULL,
  TimePeriod varchar(10) NOT NULL,
  RscStatus set('Available','In Use','In Repair') NOT NULL,
  Username varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table resources
--

INSERT INTO resources (ResourceID, RscName, Model, Longitude, Latitude, Cost, TimePeriod, RscStatus, Username) VALUES
(1, 'Large Truck', 'F150', '-10.000000000', '30.000000000', '50', 'Day', 'In Use', 'tim'),
(2, 'Rescue Dog', 'German Shephard', '90.000000000', '-30.000000000', '15', 'Day', 'In Use', 'tim'),
(3, 'Bulldozer', NULL, '179.000000000', '-70.000000000', '1000', 'Week', 'Available', 'tim'),
(4, 'Snow Plow', 'John Deere', '10.000000000', '50.000000000', '50', 'Hour', 'Available', 'city_atlanta'),
(5, 'Fire Truck', 'Big Red', '-20.000000000', '-10.000000000', '300', 'Day', 'In Repair', 'city_atlanta'),
(6, 'Lawn Mower', 'Hybrid', '-50.000000000', '30.000000000', '5', 'Day', 'In Repair', 'tim'),
(7, 'Jackhammer', NULL, '43.000000000', '0.000000000', '10', 'Hour', 'Available', 'city_atlanta'),
(8, 'Walkie Talkies', 'Sony', '3.000000000', '1.000000000', '10', 'Day', 'Available', 'city_atlanta'),
(9, 'Batteries', 'Energizer', '-10.000000000', '32.000000000', '5', 'Week', 'Available', 'city_atlanta'),
(10, 'Rope', 'Nylon', '34.113500000', '-82.442100000', '36', 'Week', 'In Use', 'city_atlanta');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  Username varchar(30) NOT NULL,
  Name varchar(50) NOT NULL,
  Password varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (Username, `Name`, `Password`) VALUES
('chaitali', 'Chaitali Patil', 'chaitali123'),
('city_atlanta', 'City of Atlanta', 'cityatlanta123'),
('city_austin', 'City of Austin', 'cityaustin123'),
('company_a', 'Company A', 'companya123'),
('company_b', 'Company B', 'companyb123'),
('cwp', 'Cody Pope', 'cody123'),
('daniel', 'Daniel Rozen', 'daniel123'),
('fbi', 'FBI', 'fbi123'),
('fema', 'FEMA', 'fema123'),
('tim', 'Tim Green', 'tim123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table capabilities
--
ALTER TABLE capabilities
  ADD PRIMARY KEY (ResourceID,Capability);

--
-- Indexes for table company
--
ALTER TABLE company
  ADD PRIMARY KEY (Username);

--
-- Indexes for table deploys
--
ALTER TABLE deploys
  ADD PRIMARY KEY (IncidentID,ResourceID),
  ADD KEY ResourceID (ResourceID);

--
-- Indexes for table functions
--
ALTER TABLE functions
  ADD PRIMARY KEY (ESFId);

--
-- Indexes for table govtagency
--
ALTER TABLE govtagency
  ADD PRIMARY KEY (Username);

--
-- Indexes for table incidents
--
ALTER TABLE incidents
  ADD PRIMARY KEY (IncidentID),
  ADD KEY Username (Username);

--
-- Indexes for table individual
--
ALTER TABLE individual
  ADD PRIMARY KEY (Username);

--
-- Indexes for table municipality
--
ALTER TABLE municipality
  ADD PRIMARY KEY (Username);

--
-- Indexes for table payperiods
--
ALTER TABLE payperiods
  ADD PRIMARY KEY (TimePeriod);

--
-- Indexes for table primaryesf
--
ALTER TABLE primaryesf
  ADD PRIMARY KEY (ResourceID,ESFId),
  ADD UNIQUE KEY ResourceID (ResourceID),
  ADD KEY ESFId (ESFId);

--
-- Indexes for table `repair`
--
ALTER TABLE `repair`
  ADD PRIMARY KEY (ResourceID,StartDate);

--
-- Indexes for table requests
--
ALTER TABLE requests
  ADD PRIMARY KEY (IncidentID,ResourceID),
  ADD KEY ResourceID (ResourceID);

--
-- Indexes for table resourceesfs
--
ALTER TABLE resourceesfs
  ADD PRIMARY KEY (ResourceID,ESFId),
  ADD KEY ESFId (ESFId);

--
-- Indexes for table resources
--
ALTER TABLE resources
  ADD PRIMARY KEY (ResourceID),
  ADD KEY Username (Username),
  ADD KEY TimePeriod (TimePeriod);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (Username);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table functions
--
ALTER TABLE functions
  MODIFY ESFId int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table incidents
--
ALTER TABLE incidents
  MODIFY IncidentID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table resources
--
ALTER TABLE resources
  MODIFY ResourceID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table capabilities
--
ALTER TABLE capabilities
  ADD CONSTRAINT capabilities_ibfk_1 FOREIGN KEY (ResourceID) REFERENCES resources (ResourceID);

--
-- Constraints for table company
--
ALTER TABLE company
  ADD CONSTRAINT company_ibfk_1 FOREIGN KEY (Username) REFERENCES `user` (Username);

--
-- Constraints for table deploys
--
ALTER TABLE deploys
  ADD CONSTRAINT deploys_ibfk_1 FOREIGN KEY (ResourceID) REFERENCES resources (ResourceID),
  ADD CONSTRAINT deploys_ibfk_2 FOREIGN KEY (IncidentID) REFERENCES incidents (IncidentID);

--
-- Constraints for table govtagency
--
ALTER TABLE govtagency
  ADD CONSTRAINT govtagency_ibfk_1 FOREIGN KEY (Username) REFERENCES `user` (Username);

--
-- Constraints for table incidents
--
ALTER TABLE incidents
  ADD CONSTRAINT incidents_ibfk_1 FOREIGN KEY (Username) REFERENCES `user` (Username);

--
-- Constraints for table individual
--
ALTER TABLE individual
  ADD CONSTRAINT individual_ibfk_1 FOREIGN KEY (Username) REFERENCES `user` (Username);

--
-- Constraints for table municipality
--
ALTER TABLE municipality
  ADD CONSTRAINT municipality_ibfk_1 FOREIGN KEY (Username) REFERENCES `user` (Username);

--
-- Constraints for table primaryesf
--
ALTER TABLE primaryesf
  ADD CONSTRAINT primaryesf_ibfk_1 FOREIGN KEY (ResourceID) REFERENCES resources (ResourceID),
  ADD CONSTRAINT primaryesf_ibfk_2 FOREIGN KEY (ESFId) REFERENCES `functions` (ESFId);

--
-- Constraints for table `repair`
--
ALTER TABLE `repair`
  ADD CONSTRAINT repair_ibfk_1 FOREIGN KEY (ResourceID) REFERENCES resources (ResourceID);

--
-- Constraints for table requests
--
ALTER TABLE requests
  ADD CONSTRAINT requests_ibfk_1 FOREIGN KEY (ResourceID) REFERENCES resources (ResourceID),
  ADD CONSTRAINT requests_ibfk_2 FOREIGN KEY (IncidentID) REFERENCES incidents (IncidentID);

--
-- Constraints for table resourceesfs
--
ALTER TABLE resourceesfs
  ADD CONSTRAINT resourceesfs_ibfk_1 FOREIGN KEY (ResourceID) REFERENCES resources (ResourceID),
  ADD CONSTRAINT resourceesfs_ibfk_2 FOREIGN KEY (ESFId) REFERENCES `functions` (ESFId);

--
-- Constraints for table resources
--
ALTER TABLE resources
  ADD CONSTRAINT resources_ibfk_1 FOREIGN KEY (Username) REFERENCES `user` (Username),
  ADD CONSTRAINT resources_ibfk_2 FOREIGN KEY (TimePeriod) REFERENCES payperiods (TimePeriod);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
