<?php

define('DEFAULT_CONTROLLER', 'Tournament');

$config = Config::instance();

$config->sqlite_file = dirname(__FILE__).DIRECTORY_SEPARATOR.'7wonders.sqlite';
$config->sqlite_table = array(

		'PRAGMA foreign_keys = ON',

		'CREATE TABLE IF NOT EXISTS Players (
			`PlayerID` INTEGER PRIMARY KEY,
			`FirstName` TINYTEXT NOT NULL,
			`LastName` TINYTEXT NOT NULL,
			`Paid` TINYINT UNSIGNED,
			`Arrived` TINYINT UNSIGNED
		)',

		'CREATE TABLE IF NOT EXISTS Wonders (
			`WonderID` INTEGER PRIMARY KEY,
			`WonderName` TINYTEXT NOT NULL,
			`WonderSide` TINYTEXT,
			`SeatNum` TINYINT UNSIGNED,
			`WonderUse` TINYINT UNSIGNED
		)',

		'CREATE TABLE IF NOT EXISTS Games (
			`RoundNum` TINYINT UNSIGNED NOT NULL,
			`TableNum` TINYINT UNSIGNED,
			`PlayerID` INTEGER NOT NULL,
			`WonderID` INTEGER NOY NULL,
			`Rank` TINYINT UNSIGNED,
			`Points` TINYINT UNSIGNED,
			`TempTotalPts` TINYINT,
			`MilitaryPts` TINYINT,
			`MoneyPts` TINYINT,
			`WonderPts` TINYINT,
			`CivilPts` TINYINT,
			`SciencePts` TINYINT,
			`CommercePts` TINYINT,
			`GuildsPts` TINYINT,
			`Complete` TINYINT UNSIGNED,

			FOREIGN KEY(`PlayerID`) REFERENCES Players(`PlayerID`),
			FOREIGN KEY(`WonderID`) REFERENCES Wonders(`WonderID`)
		)',

		'CREATE UNIQUE INDEX IF NOT EXISTS `OneWonderPerTablePerRound` ON `Games` (
			`RoundNum`,
			`TableNum`,
			`WonderID`
		)',

		'CREATE UNIQUE INDEX IF NOT EXISTS `OnePlayerPerRound` ON `Games` (
			`RoundNum`,
			`PlayerID`
		)',

		'CREATE VIEW IF NOT EXISTS `GamesFull` AS
			SELECT
				`RoundNum`,
				`TableNum`,
				`PlayerID`,
				`WonderID`,
				`WonderName`,
				`WonderSide`,
				`SeatNum`,
				`Rank`,
				`MilitaryPts`,
				`MoneyPts`,
				`WonderPts`,
				`CivilPts`,
				`SciencePts`,
				`CommercePts`,
				`GuildsPts`,
				`Complete`,
				CASE `Complete`
					WHEN 1 THEN (`MilitaryPts` + `MoneyPts` + `WonderPts` + `CivilPts`
					+ `SciencePts` + `CommercePts` + `GuildsPts`)
					ELSE `TempTotalPts`
				END AS `TotalPts`
			FROM Games
			INNER JOIN Wonders USING (WonderID)
		',

	);

$config->points = [
					1 => 0,
					2 => 2,
					3 => 3,
					4 => 4,
					5 => 5,
					6 => 6,
					7 => 7
				];

// A list of categories that will be submitted with full results
$config->categoriesSubmit = [
	['MilitaryPts', 'Military'],
	['MoneyPts', 'Commerce'],
	['WonderPts', 'Wonder'],
	['CivilPts', 'Civilian'],
	['SciencePts', 'Science'],
	['CommercePts', 'Commerce'],
	['GuildsPts', 'Guilds']
		];

// The full list of categories
$config->categories = array_merge(
		$config->categoriesSubmit,
		[['TotalPts', 'Total']]
		);