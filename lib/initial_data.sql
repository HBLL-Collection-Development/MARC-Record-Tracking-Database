-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 27, 2013 at 10:21 PM
-- Server version: 5.5.20
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `marc_record_loads`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `phone` text,
  `email` text,
  `type` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `vendor_id`, `name`, `phone`, `email`, `type`) VALUES
(1, 1, 'Tom Nagy', '', 'tnagy@accessible.com', 'Sales'),
(2, 2, 'Lauren Edwards', '', 'Lauren@amdigital.co.uk', 'Sales'),
(3, 2, 'Adam Matthew Support', '', 'support@amdigital.co.uk', 'Technical Support'),
(4, 3, 'Jenni Wilson', '', 'jwilson@alexanderstreet.com', 'Sales'),
(5, 4, 'Ann Duchene', '', 'Ann.Duchene@brepols.net', 'Sales'),
(6, 5, 'Kathyrn Paoletti', '781-740-8355 (office), 781-927-9344 (cell)', 'kathyrnpaoletti@casalini.it', 'Sales'),
(7, 6, 'Amy Wood', '', 'AWood@CRL.EDU', 'Sales'),
(8, 7, 'Lysa Burns', '', 'lburns@ebscohost.com', 'Sales'),
(9, 8, 'Tom Thayer', '', 'T.Thayer@Elsevier.com', 'Sales'),
(10, 9, 'Yaala.Ariel-Joel@exlibrisgroup.com', '847-227-4874', 'Yaala.Ariel-Joel@exlibrisgroup.com', 'Technical Support'),
(11, 10, 'Technical Support', '', 'video.support@infobaselearning.com', 'Technical Support'),
(12, 11, 'Rob Hoyer', '1-800-877-4253', 'rob.hoyer@cengage.com', 'Sales'),
(13, 11, 'Gale Technical Support', '', 'gale.technicalsupport@cengage.com', 'Technical Support'),
(14, 12, 'Wendy McCarville', '847-841-1580 (office), 773-793-6383 (cell)', 'w.mccarville@ieee.org', 'Sales'),
(15, 14, 'Mike Jones', '', 'jones@morganclaypool.com', 'Sales'),
(16, 15, 'Laurel Kaczor', '', 'laurel.kaczor@oup.com', 'Sales'),
(17, 15, 'Laura Chittivej', '', 'lchittiv@bcr.org', 'Sales'),
(18, 16, 'Lisa Fisher', '', 'lisa.fisher@il.proquest.com', 'Sales'),
(19, 17, 'Georgia Frederick', '', 'gfrederick@readex.com', 'Sales'),
(20, 18, 'Benjamin Knysak', '410-662-6015', 'bknysak@ripm.org', 'Sales'),
(21, 19, 'David Celano', '', 'david.celano@springer.com', 'Sales'),
(22, 20, 'Lauren Kadziel', '', 'lkadziel@statref.com', 'Sales'),
(23, 21, 'Carin Bringelson', '', 'carin@TeachingBooks.net', 'Sales'),
(24, 22, 'Linda Rowley', '', 'lrowley@umich.edu', 'Sales'),
(25, 23, 'Lily Rodriguez', '', 'lrodrigu@wiley.com', 'Sales');

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL,
  `resource_name` text NOT NULL,
  `url` text,
  `username` text,
  `password` text,
  `frequency` enum('Once','Weekly','Monthly','Quarterly','Semiannually','Annually','When notified') DEFAULT NULL,
  `last_load` date DEFAULT NULL,
  `next_load` date DEFAULT NULL,
  `num_records` int(11) DEFAULT NULL,
  `notes` text,
  `lending_status` text,
  `sharable_status` set('Y','N') DEFAULT NULL,
  `unicode` set('Y','N') DEFAULT NULL,
  `primo_central` set('Y','N') NOT NULL,
  `load_records` set('Y','N') NOT NULL DEFAULT 'Y',
  `file_exists` set('Y','N') NOT NULL DEFAULT 'N',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `vendor_id` (`vendor_id`),
  KEY `next_load` (`next_load`),
  KEY `last_updated` (`last_updated`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=76 ;

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`id`, `vendor_id`, `resource_name`, `url`, `username`, `password`, `frequency`, `last_load`, `next_load`, `num_records`, `notes`, `lending_status`, `sharable_status`, `unicode`, `primo_central`, `load_records`, `file_exists`, `last_updated`) VALUES
(1, 1, 'County History Collection', 'ftp://marcrecords.accessible.com', 'marcrecords', 'access=archives', 'When notified', '2013-01-10', NULL, 0, 'Primo Central: Another problem to note. We would often have records loaded for things we do not have because the wrong set was grabbed or they loaded all the records the publisher had available instead of just our custom set.  bCounty Histories load $c20120803$knh?; 583:cat ebooks$bCounty History load$c20130110$knh', NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(2, 2, 'Perdita Manuscripts', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'When notified', '2011-12-07', NULL, NULL, '583: Adam Matthew Perdita Manuscripts load', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(3, 2, 'American West', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'When notified', '2012-02-08', NULL, NULL, '583:Adam_Matthew American West load', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(4, 2, 'Literary Manuscripts (Berg)', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'When notified', '2012-04-04', '2012-01-20', NULL, 'http://www.amdigital.co.uk/Marc-Collections.aspx?; 583: Literary Manuscripts-Berg 20120402', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(5, 2, 'London Low Life', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'When notified', '2012-04-04', '2012-01-20', NULL, 'http://www.amdigital.co.uk/Marc-Collections.aspx?; 583: London Low Life 20120402', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(6, 2, 'Medieval Family Life', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'When notified', '2012-04-04', '2012-01-20', NULL, 'http://www.amdigital.co.uk/Marc-Collections.aspx?; 583: Medieval Family Life load 20120402', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(7, 2, 'Virginia Company Archives', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'When notified', '2012-04-05', '2012-01-20', NULL, 'http://www.amdigital.co.uk/Marc-Collections.aspx?; 583: VirginiaCompanyArchives 20120404', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(8, 2, 'Foreign Office Files China, 1949-1980', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'When notified', NULL, NULL, NULL, NULL, 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(9, 2, 'Slavery Abolition and Social Justice', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'When notified', NULL, NULL, NULL, NULL, 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(10, 3, 'The Sixties', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2010-03-31', NULL, NULL, '2010-10, new content available; no MARC records available yet.', 'ILL (print or fax)', 'Y', NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(11, 3, 'Counseling and Psychotherapy Transcripts, Client Narratives, and Reference Works', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2008-09-30', NULL, NULL, '583: 20080213', 'ILL (print or fax)', 'Y', NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(12, 3, 'American Civil War Letters and Diaries', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2012-02-02', NULL, NULL, '583: AmericanCivilWar letters_diaries load', 'ILL (print or fax)', 'Y', NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(13, 3, 'American Civil War Research Database', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', NULL, NULL, NULL, 'ASP has a product-level MARC record available. ASP will not be doing a book-level set of MARC records for this db. 583: Action', 'ILL (print or fax)', 'Y', NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(14, 3, 'African American Music Reference', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2012-02-03', NULL, NULL, 'Primo Central: PC results not showing in search.  583: Afri_American Music load', 'ILL (print or fax)', 'Y', 'N', 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(15, 3, 'Contemporary World Music', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2011-12-07', NULL, NULL, 'Primo Central: Can''t find any MARC records to compare.  583: ASP Contemporary World Music load', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(16, 3, 'Classical Scores Library', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2011-12-07', NULL, NULL, 'Primo Central: MARC record better (includes ISBN). To compare http://search.lib.byu.edu/byu/Dance+Figures/set:byuall/field:any/match:exact.  583: ASP Classical Scores Library load', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(17, 3, 'Smithsonian Global Sound', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2012-01-10', NULL, NULL, 'Primo Central: MARC record better http://search.lib.byu.edu/byu/17+Popular+Ukrainian+Dances/set:byuall/field:any/match:exact  c20120110', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(18, 3, 'North American Immigrant Letters, Diaries, and Oral Histories', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2012-02-03', '2010-11-01', NULL, 'Primo Central: MARC record better http://search.lib.byu.edu/byu/Jewish+Grandmothers/set:byuall/field:any/match:exact  OOPS: 583: Early Encounters in North America load $c20120104 (will pull two files!)', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(19, 3, 'Black Women Writers', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2011-12-07', NULL, NULL, 'Primo Central: MARC record better. To compare record quality: http://search.lib.byu.edu/byu/between+Goodbyes/set:byuall/field:any/match:exact/expand:on  Yank and load: remove all ?Black women writers suppl. 3 load,? ?Black women writers suppl. 2 load,? ?Black women writers suppl. 1 load? and ?Black women writers load? and replace with this new file. 583: ASP Black Women Writers load', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(20, 3, 'British and Irish Women''s Letters and Diaries', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2008-09-26', NULL, NULL, 'Primo Central: MARC record better. To compare record quality: http://search.lib.byu.edu/byu/Mother%27s+Diary+During+Blitz+1940/set:byuall/field:any/match:exact/expand:on  ', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(21, 3, 'Classical Music Reference Library', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2011-12-07', NULL, NULL, 'Primo Central: MARC record better. To compare record quality: http://search.lib.byu.edu/byu/The+Liszt+companion/set:byuall/field:any/match:exact  583: ASP Classical Music Reference Library load', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(22, 3, 'Garland Encyclopedia of World Music', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2012-02-08', '2010-11-01', NULL, 'Primo Central: MARC record much better. See http://search.lib.byu.edu/byu/Classic+mountain+songs+from+Smithsonian+Folkways/set:byuall/field:any/match:exact  583: Garland Encyclopedia of World Music load $c20120104', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(23, 3, 'American Song', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2013-01-23', NULL, NULL, 'Primo Central: MARC record much better. See http://search.lib.byu.edu/byu/Classic+mountain+songs+from+Smithsonian+Folkways/set:byuall/field:any/match:exact  c20130123', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(24, 3, 'North American Indian Drama', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2010-04-22', NULL, NULL, 'Primo Central: MARC record slightly better. To compare record quality: http://search.lib.byu.edu/byu/The+women+who+loved+house+trailers/set:byuall/field:any/match:exact (no mention of Indian drama on PC record).  ', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(25, 3, 'Black Drama', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2005-09-02', NULL, NULL, 'Primo Central: MARC record slightly better. To compare record quality: http://search.lib.byu.edu/byu/Will+he+bop%2C+will+he+drop/set:byuall/field:any/match:exact  BYU doesn''t own Black Drama second edition and its supplements; Jenni (email) 1/11/2011. 583: Action: mod. Action ID: url. Time of action: 20091215. Action agent: vw.', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(26, 3, 'Classical Music Library', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2011-12-07', NULL, NULL, 'Primo Central: PC results not showing in search.  583: ASP Classical Music Library load', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(27, 3, 'Audio Drama', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', NULL, NULL, NULL, 'Primo Central: PC results not showing in search.  see email from Jenni Feb. 1, 2011 before loading. 583: Action: cat. Action ID: dbo. Time of action: 20091214. Action agent: vw.', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(28, 3, 'Opera in Video', 'http://alexanderstreet.com/marc', NULL, NULL, 'When notified', '2010-12-17', '2010-08-19', NULL, 'Primo Central: PC results slightly worse (missing creation date), and labeled as video instead of streaming video.  ', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(29, 4, 'Brepolis Library of Latin Texts (CLCLT)', 'http://clt.brepolis.net/marc/pages/Export.aspx', 'Unlock code: 3222BY', NULL, 'When notified', '2012-02-08', NULL, NULL, '583:Library of Latin Texts load', 'No', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(30, 4, 'Brepolis Monumenta Germaniae Historica (MGH)', 'http://clt.brepolis.net/marc/pages/Export.aspx', 'Unlock code: 3222BY', NULL, 'When notified', NULL, NULL, NULL, 'Coming soon', 'No', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(31, 5, 'EIO Italian Studies eng2006', NULL, NULL, NULL, 'When notified', '2012-05-16', NULL, NULL, 'Primo Central: We have a limited collection.  583 EIO ItalianStudies eng2006 load; www.casalini.it, www.torrossa.it. Delivered via email.', NULL, NULL, NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(32, 6, 'Center for Research Libraries', 'http://images.crl.edu/', 'ecrl', 'g3tR3c0rde', 'When notified', '2009-10-14', NULL, NULL, 'Primo Central: Not sure if the PC is the same as our MARC load.  AC canceled CRL membership 2010-12', NULL, NULL, NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(33, 7, 'PsycBOOKS', 'http://eadmin.ebscohost.com/EAdmin/DBTitlesDownload/TitleListForm.aspx?ft=1', 's8440600', 'br1ghmY0uNgU4', 'When notified', '2012-12-10', '2012-07-01', NULL, 'Primo Central: PC has APA records. Could rely on SFX to link to EBSCO or could load EBSCO records.  bPsychBooks load $c20120806$knh; 201 records bPsychBOOKS load$c20121206', 'ILL (all)', NULL, NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(34, 8, 'Referex Engineering', NULL, NULL, NULL, 'Annually', '2010-03-30', NULL, NULL, 'Order through OCLC. Make sure to clean up the 856 links that reference sciencedirect. Leave the engineeringvillage link instead. This is where the full text is and not in the former.', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(35, 8, 'Elsevier ScienceDirect', 'https://admintool.elsevier.com/admintool/marcRequest.url?id=21830', 'bspackman', 'byuadmin', 'When notified', '2013-01-28', NULL, NULL, 'Primo Central: PC at chapter level.  c20121001; cat ebooks|bScienceDirect load|c20130128|knh', 'ILL (print or fax)', 'Y', NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(36, 9, 'MarcIt!', 'http://sfx.lib.byu.edu/sfxadmin/byucentral', NULL, NULL, 'Monthly', '2012-05-01', '2011-10-01', NULL, 'Primo Central: But we have promoted results tool to query SFX.  For updates, do a "match and load". Use the ''byucentral'' acct. to order records.', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(37, 10, 'Films on Demand', 'http://admin.films.com/MARC.aspx', 'byuutahadmin', 'byuutahadmin1', 'When notified', '2012-04-09', '2012-01-27', NULL, '583: Films on Demand load 20120229?;Films on Demand load 20120329', NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(38, 11, 'Overland Journeys: Travels in the West', 'http://www.gale.cengage.com/marc_records/au.htm', NULL, NULL, 'When notified', '2012-04-05', '2012-01-20', NULL, '583: OverlandJourneys 20120404', 'No', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(39, 11, 'Gale Cengage 17th and 18th Century Burney Collection Newspapers', NULL, NULL, NULL, 'Once', '2010-08-05', NULL, NULL, 'Must contact rep to order', 'No', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(40, 11, 'Something About the Author', 'http://www.gale.cengage.com/marc_records/', NULL, NULL, 'Quarterly', NULL, NULL, NULL, 'Unable to load records without losing data; Lee has contacted Gale 2010-12-29.', 'No', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(41, 11, 'Making of the Modern World', 'http://www.gale.cengage.com/marc_records/', NULL, NULL, 'When notified', '2007-03-31', NULL, NULL, NULL, 'No', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(42, 11, 'Literary Criticism Online (LCO) Digital Archive', 'http://www.gale.cengage.com/marc_records/', NULL, NULL, 'Monthly', NULL, NULL, NULL, NULL, 'No', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(43, 11, 'Gale World Scholar: Latin America and the Caribbean', 'http://www.gale.cengage.com/marc_records/', NULL, NULL, 'Once', '2012-04-05', '2012-01-20', NULL, 'This collection is for Provo only. http://www.gale.cengage.com/tlist/marc/WSLAC.mrc; 583: WorldScholarLatinAmericaCaribbean 20120404', NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(44, 11, 'Gale Cengage Literature Resource Center', 'http://admin.galegroup.com/galeadmin/content/requestMarcRecords.gale?loc=byuprovo&context=location', 'brigham/byu_main', 'admin', 'Quarterly', NULL, NULL, NULL, 'Primo Central: Can''t find any MARC records in catalog to compare.  ', 'No', NULL, NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(45, 11, 'Eighteenth Century Collections Online (ECCO)', NULL, NULL, NULL, 'Once', NULL, NULL, NULL, 'Primo Central: PC results not showing in search.  Contact rep for full records.', 'No', NULL, NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(46, 11, 'Gale''s Scribner-Twayne Series', 'http://access.gale.com/cgi-bin/marc_merge/listfiles.pl', 'Location ID: byuprovo', NULL, 'Once', '2011-02-11', NULL, NULL, 'Primo Central: As part of GVRL. GVRL PC records better than MARC.  ', NULL, NULL, NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(47, 12, 'IEEE Ebooks (GWLA)', 'http://collectionsets.oclc.org/JustLooking?cmd=displayElectronicSets', '100-019-291', 'warden', 'Quarterly', '2012-05-17', NULL, NULL, 'Records will be complete in 2016', NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(48, 13, 'LexisNexis Congressional Hearings Digital Collection Historical Archive', NULL, NULL, NULL, 'Once', NULL, NULL, NULL, 'Coming Soon. Available but have to contact PQ to order?', 'No', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(49, 14, 'Synthesis Digital Library', 'http://www.morganclaypool.com/page/marc', NULL, NULL, 'Monthly', '2012-06-14', '2012-04-30', NULL, 'Primo Central: MARC record better. To compare: http://search.lib.byu.edu/byu/Systems+engineering+building+successful+systems/set:byuall/field:any/match:exact.  583: Synthesis Digital Library load; Last load was the February 2012 batch?;583: SynthesisDigitalLibrary load $c20120419?;aSynthesisDigitalLibrary load $c20120501?;DO NOT "Verify URLs" in MarcEdit (M&C site will block IP address)', 'ILL (all)', NULL, NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:57'),
(50, 15, 'Berg Fashion Library', 'http://www.bergfashionlibrary.com/page/65/marc-records', NULL, NULL, 'When notified', '2012-04-04', '2012-01-20', NULL, '583: aBergFashionLibrary load $c20120329$5UPB', NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(51, 15, 'Oxford Islamic Studies Online', 'http://www.oup.com/uk/academic/online/librarians/marcrecords/', 'byu', 'Byu', 'When notified', '2012-02-10', '2010-11-01', NULL, '583: Oxford Islamic Studies load $c20120112', NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(52, 15, 'Oxford Reference Library', 'http://www.oup.com/uk/academic/online/librarians/marcrecords/', 'byu', 'Byu', 'Quarterly', '2012-11-19', NULL, NULL, '583: OxfordReference load$c20121119$knh; OxfordReferenceLibrary load$c20130208$knh', NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(53, 15, 'Oxford Language Dictionaries Online', 'http://www.oup.com/uk/academic/online/librarians/marcrecords/', 'byu', 'Byu', 'When notified', '2012-02-10', '2010-11-01', NULL, 'Filepath Oxford/Dict?; OOPS: 583: Oxford language dictionaries load $c20120112 (will pull this and Safari Tech Books Online)', NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(54, 16, 'Gerritsen''s Women''s History Online', 'http://gerritsen.chadwyck.com/infoCentre/marc.jsp', NULL, NULL, 'Once', NULL, NULL, NULL, 'Available for purchase', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(55, 16, 'Bible in English', 'http://collections.chadwyck.com/marketing/librarians/faqs.jsp#marc', 'logadmin', 'LIONstats', 'Once', NULL, NULL, NULL, 'Coming soon', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(56, 16, 'Editions and Adaptations of Shakespeare', 'http://collections.chadwyck.com/marketing/librarians/faqs.jsp#marc', 'logadmin', 'LIONstats', 'Once', NULL, NULL, NULL, 'Coming soon', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:57'),
(57, 16, 'ProQuest Safari Tech Books Online', 'https://secure.safaribooksonline.com/bo3/', 'UALC_MARC', 'welcome', 'Monthly', '2012-06-06', '2011-02-01', NULL, 'OOPS: 583: Oxford language dictionaries load $c20120112 (will pull this and oxford language dictionaries); 583ProquestSafariTechBooks load $c20120606$knh; 583: ProquestSafariTechBooks load$c20130117', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(58, 16, 'Kafka', NULL, NULL, NULL, 'Once', NULL, NULL, NULL, 'Too dirty to load. Available but have to contact PQ to order?', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(59, 16, 'Schillers Werke Online Access', NULL, NULL, NULL, 'Once', NULL, NULL, NULL, 'Too dirty to load. Available but have to contact PQ to order?', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(60, 16, 'Early English Books Online (EEBO)', 'http://eebo.chadwyck.com.erl.lib.byu.edu/admin_res/admin_res.htm#marc', 'logadmin', 'EEBOstats', 'When notified', '2010-09-13', NULL, NULL, NULL, 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(61, 16, 'Literature Online', 'http://lion.chadwyck.com/infoCentre/librarians/admin/marc.jsp', 'logadmin', 'LIONstats', 'Annually', NULL, NULL, NULL, NULL, 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(62, 17, 'U.S. Congressional Serial Set', 'http://www.newsbank.com/readex/index.cfm?content=296', NULL, NULL, 'Once', NULL, NULL, NULL, 'Available for purchase.', 'ILL (print or fax & secure electronic transmission)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(63, 18, 'RIPM Online Archive (Retrospective Index and Online Archive)', 'http://marc.ripm.org/', 'ripm_marc2012', 'hanslick', 'Semiannually', '2012-08-01', '2013-01-01', NULL, 'Load in January and July. 583: cat ebooks$bRIPM Online Archive load', NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(64, 19, '02-12-518199-0 Springer Protocols', 'http://www.springer.com/librarians/e-content/springer+marc+records?SGWID=0-1488', 'Jared01', 'cougars', 'Monthly', '2011-09-07', NULL, NULL, 'Primo Central: MARC record better. To compare record quality: http://search.lib.byu.edu/byu/9781617792304/set:byuall.  35 new', NULL, NULL, NULL, 'Y', 'Y', 'N', '2013-02-26 19:53:58'),
(65, 20, 'STAT!Ref', NULL, NULL, NULL, 'Annually', '2012-10-26', NULL, NULL, 'Load every January. Will be emailed. 583: Stat!Ref load$c20121026', 'ILL (all)', NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(66, 21, 'TeachingBooks.net', 'http://TeachingBooks.net/MARCrecords', NULL, NULL, 'When notified', '2012-04-04', '2012-03-28', NULL, '583: TeachingBooks load $c20120329$5UPB', NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(67, 22, 'ICPSR', 'http://www.icpsr.umich.edu/icpsrweb/membership/or/metadata/marc/index.jsp', NULL, NULL, 'Annually', '2012-02-09', '2011-10-20', NULL, 'They have changed their linking structure. The new records with correct URLs can be found here. 583: ICPSR load 20120103', NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(68, 23, 'Blackwell Reference Online', NULL, NULL, NULL, 'When notified', NULL, NULL, NULL, 'Primo Central: We have a limited collection.  Available for purchase through OCLC. We cannot get these except through OCLC.', NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(69, 2, 'Confidential Print: Latin America, 1833-1969', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'Once', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(70, 2, 'Defining Gender, 1450-1910', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'Once', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(71, 2, 'Medieval Travel Writing', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'Once', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(72, 2, 'Rock and Roll, Counterculture, Peace and Protest', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'Once', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(73, 2, 'Romanticism: Life, Literature and Landscape', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'Once', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(74, 2, 'The First World War: Personal Experiences', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'Once', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58'),
(75, 2, 'The Nixon Years', 'http://www.amdigital.co.uk/librarians-resources/marc-records/', NULL, NULL, 'Once', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'N', 'Y', 'N', '2013-02-26 19:53:58');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`) VALUES
(1, 'Accessible Archives'),
(2, 'Adam Matthew'),
(3, 'Alexander Street Press'),
(4, 'Brepolis Press'),
(5, 'Casalini'),
(6, 'Center for Research Libraries'),
(7, 'EBSCO'),
(8, 'Elsevier'),
(9, 'Ex Libris'),
(10, 'Films on Demand'),
(11, 'Gale Cengage'),
(12, 'IEEE'),
(13, 'LexisNexis'),
(14, 'Morgan Claypool'),
(15, 'Oxford'),
(16, 'ProQuest'),
(17, 'Readex'),
(18, 'RIPM'),
(19, 'Springer'),
(20, 'STAT!Ref'),
(21, 'Teaching Books'),
(22, 'University of Michigan'),
(23, 'Wiley');
