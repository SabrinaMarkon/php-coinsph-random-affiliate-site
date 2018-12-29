CREATE TABLE `adminsettings` (
id integer unsigned not null primary key auto_increment,
adminuser varchar(255) not null,
adminpass varchar(255) not null,
adminname varchar(255) not null,
adminemail varchar(255) not null,
sitename varchar(255) not null,
domain varchar(255) not null,
adminratio integer unsigned not null default '5',
ratiocounter tinyint(4) unsigned not null default '0',
adminautoapprove tinyint(1) not null default '0',
admindefaultwalletid varchar(500) not null default '',
admindefaultcoinsphpid varchar(500) not null default '',
giveextratoadmin tinyint(1) not null default '1',
paysponsor decimal(9,2) not null default '5.00',
payrandom decimal(9,2) not null default '3.00',
adclickstogetad integer unsigned not null default '100'
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE adminwallets (
  id integer unsigned not null primary key auto_increment,
  name varchar(255) not null default 'Main Admin',
  walletid varchar(500) not null,
  coinsphpid varchar(500) not null
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `adminnotes` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(25) NOT NULL default '',
  `htmlcode` longtext NOT NULL,
  KEY `index` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

create table ads (
id integer unsigned not null primary key auto_increment,
username varchar(255) not null default 'admin',
name varchar(255) not null,
title varchar(255) not null,
url varchar(500) not null,
shorturl varchar(255) not null,
description varchar(255) not null,
imageurl varchar(500) not null,
added tinyint(1) not null default '0',
approved tinyint(1) not null default '0',
hits integer unsigned not null default '0',
clicks integer unsigned not null default '0',
adddate datetime not null
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `countries` (
`country_id` int(11) not null AUTO_INCREMENT,
`country_name` varchar(64) not null DEFAULT '',
`iso_code2` char(2) not null DEFAULT '',
`iso_code3` char(3) not null DEFAULT '',
`reserved1` int(11) not null DEFAULT '0',
PRIMARY KEY (`country_id`),
KEY `IDX_COUNTRIES_NAME` (`country_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE mail (
`id` int(10) unsigned not null auto_increment,
username varchar(255) not null default 'admin',
`subject` varchar(255) not null,
message longtext not null,
url varchar(255) not null,
needtosend tinyint(1) not null default '0',
sent datetime DEFAULT NULL,
clicks int(11) not null default '0',
save tinyint(1) not null default '0',
PRIMARY KEY (`id`),
KEY mail_username_foreign (username)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

create table members (
id integer unsigned not null primary key auto_increment,
username varchar(255) not null unique,
password varchar(255) not null,
walletid varchar(500) not null,
coinsphpid varchar(500) not null,
firstname varchar(255) not null,
lastname varchar(255) not null,
country varchar(255) not null,
email varchar(255) not null,
signupdate datetime not null,
signupip varchar(255) not null,
verificationcode varchar(255) not null,
verified varchar(255) not null,
referid varchar(255) not null,
lastlogin datetime not null,
adclicks integer unsigned not null default '0'
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `pages` (
  `id` int(10) unsigned not null auto_increment primary key,
  `name` varchar(255) not null,
  `htmlcode` longtext not null,
  `slug` varchar(255) not null,
  `core` varchar(4) not null default 'no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE `promotional` (
  `id` int(10) unsigned not null auto_increment primary key,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'banner',
  `promotionalimage` varchar(255) NOT NULL,
  `promotionalsubject` varchar(255) NOT NULL,
  `promotionaladbody` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

create table randomizer (
  id integer unsigned not null auto_increment primary key,
  username varchar(255) not null,
  walletid varchar(500) not null,
  coinsphpid varchar(500) not null,
  foreign key (username) references members(username)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

create table transactions (
  id integer unsigned not null auto_increment primary key,
  adid integer unsigned not null,
  randomizerid integer unsigned not null,
  username varchar(255) not null,
  amount decimal(9,2) not null default '0.00',
  recipient varchar(255) not null default 'admin',
  recipientwalletid varchar(500) not null,
  recipientcoinsphpid varchar(500) not null,
  recipienttype varchar(255) not null default 'sponsor',
  recipientapproved tinyint(1) not null default '0',
  datepaid varchar(255) not null,
  transaction varchar(255) not null default 'Bitcoin',
  foreign key (adid) references ads(id),
  foreign key (randomizerid) references randomizer(id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

insert into adminsettings (adminuser, adminpass, adminname, adminemail, sitename, domain) values ('Admin', 'admin', 'YOUR NAME', 'YOUR ADMIN EMAIL', 'YOUR SITE NAME','http://YOURDOMAIN.COM');

INSERT INTO `adminnotes` (`id`, `name`, `htmlcode`) values (1, 'Admin Notes', '');
INSERT INTO pages (name, htmlcode, slug, core) values ('Home Page', '', '', 'yes');
INSERT INTO pages (name, htmlcode, slug, core) values ('Login Page', '', 'login', 'yes');
INSERT INTO pages (name, htmlcode, slug, core) values ('Members Area Main Page', '', 'members', 'yes');
INSERT INTO pages (name, htmlcode, slug, core) values ('Members Area Profile Page', '', 'profile', 'yes');
INSERT INTO pages (name, htmlcode, slug, core) values ('Members Area Promotion Page', '', 'promotion', 'yes');
INSERT INTO pages (name, htmlcode, slug, core) values ('Members Area Randomizer Page', '', 'randomizer', 'yes');
INSERT INTO pages (name, htmlcode, slug, core) values ('Members Area Ads Page', '', 'ads', 'yes');
INSERT INTO pages (name, htmlcode, slug, core) values ('Registration Page', '', 'register', 'yes');
INSERT INTO pages (name, htmlcode, slug, core) values ('Thank You Page - New Member Signup', '', 'thankyou', 'yes');
INSERT INTO pages (name, htmlcode, slug, core) values ('Logout Page', '', 'logout', 'yes');
INSERT INTO pages (name, htmlcode, slug, core) values ('About Us Page', '', 'aboutus', 'yes');
INSERT INTO pages (name, htmlcode, slug, core) values ('Terms Page', '', 'terms', 'yes');
INSERT INTO pages (name, htmlcode, slug, core) values ('FAQ Page', '', 'faq', 'yes');
INSERT INTO pages (name, htmlcode, slug, core) values ('404 Page', 'Your custom 404 content here!', '404', 'yes');

INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (1, 'Afghanistan', 'AF', 'AFG', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (2, 'Albania', 'AL', 'ALB', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (3, 'Algeria', 'DZ', 'DZA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (4, 'American Samoa', 'AS', 'ASM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (5, 'Andorra', 'AD', 'AND', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (6, 'Angola', 'AO', 'AGO', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (7, 'Anguilla', 'AI', 'AIA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (8, 'Antarctica', 'AQ', 'ATA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (9, 'Antigua and Barbuda', 'AG', 'ATG', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (10, 'Argentina', 'AR', 'ARG', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (11, 'Armenia', 'AM', 'ARM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (12, 'Aruba', 'AW', 'ABW', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (13, 'Australia', 'AU', 'AUS', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (14, 'Austria', 'AT', 'AUT', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (15, 'Azerbaijan', 'AZ', 'AZE', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (16, 'Bahamas', 'BS', 'BHS', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (17, 'Bahrain', 'BH', 'BHR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (18, 'Bangladesh', 'BD', 'BGD', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (19, 'Barbados', 'BB', 'BRB', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (20, 'Belarus', 'BY', 'BLR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (21, 'Belgium', 'BE', 'BEL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (22, 'Belize', 'BZ', 'BLZ', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (23, 'Benin', 'BJ', 'BEN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (24, 'Bermuda', 'BM', 'BMU', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (25, 'Bhutan', 'BT', 'BTN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (26, 'Bolivia', 'BO', 'BOL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (27, 'Bosnia and Herzegowina', 'BA', 'BIH', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (28, 'Botswana', 'BW', 'BWA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (29, 'Bouvet Island', 'BV', 'BVT', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (30, 'Brazil', 'BR', 'BRA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (31, 'British Indian Ocean Territory', 'IO', 'IOT', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (32, 'Brunei Darussalam', 'BN', 'BRN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (33, 'Bulgaria', 'BG', 'BGR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (34, 'Burkina Faso', 'BF', 'BFA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (35, 'Burundi', 'BI', 'BDI', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (36, 'Cambodia', 'KH', 'KHM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (37, 'Cameroon', 'CM', 'CMR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (38, 'Canada', 'CA', 'CAN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (39, 'Cape Verde', 'CV', 'CPV', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (40, 'Cayman Islands', 'KY', 'CYM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (41, 'Central African Republic', 'CF', 'CAF', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (42, 'Chad', 'TD', 'TCD', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (43, 'Chile', 'CL', 'CHL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (44, 'China', 'CN', 'CHN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (45, 'Christmas Island', 'CX', 'CXR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (46, 'Cocos (Keeling) Islands', 'CC', 'CCK', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (47, 'Colombia', 'CO', 'COL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (48, 'Comoros', 'KM', 'COM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (49, 'Congo', 'CG', 'COG', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (50, 'Cook Islands', 'CK', 'COK', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (51, 'Costa Rica', 'CR', 'CRI', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (52, 'Cote D''Ivoire', 'CI', 'CIV', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (53, 'Croatia', 'HR', 'HRV', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (54, 'Cuba', 'CU', 'CUB', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (55, 'Cyprus', 'CY', 'CYP', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (56, 'Czech Republic', 'CZ', 'CZE', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (57, 'Denmark', 'DK', 'DNK', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (58, 'Djibouti', 'DJ', 'DJI', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (59, 'Dominica', 'DM', 'DMA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (60, 'Dominican Republic', 'DO', 'DOM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (61, 'East Timor', 'TP', 'TMP', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (62, 'Ecuador', 'EC', 'ECU', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (63, 'Egypt', 'EG', 'EGY', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (64, 'El Salvador', 'SV', 'SLV', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (65, 'Equatorial Guinea', 'GQ', 'GNQ', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (66, 'Eritrea', 'ER', 'ERI', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (67, 'Estonia', 'EE', 'EST', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (68, 'Ethiopia', 'ET', 'ETH', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (70, 'Faroe Islands', 'FO', 'FRO', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (71, 'Fiji', 'FJ', 'FJI', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (72, 'Finland', 'FI', 'FIN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (73, 'France', 'FR', 'FRA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (74, 'France, Metropolitan', 'FX', 'FXX', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (75, 'French Guiana', 'GF', 'GUF', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (76, 'French Polynesia', 'PF', 'PYF', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (77, 'French Southern Territories', 'TF', 'ATF', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (78, 'Gabon', 'GA', 'GAB', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (79, 'Gambia', 'GM', 'GMB', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (80, 'Georgia', 'GE', 'GEO', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (81, 'Germany', 'DE', 'DEU', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (82, 'Ghana', 'GH', 'GHA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (83, 'Gibraltar', 'GI', 'GIB', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (84, 'Greece', 'GR', 'GRC', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (85, 'Greenland', 'GL', 'GRL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (86, 'Grenada', 'GD', 'GRD', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (87, 'Guadeloupe', 'GP', 'GLP', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (88, 'Guam', 'GU', 'GUM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (89, 'Guatemala', 'GT', 'GTM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (90, 'Guinea', 'GN', 'GIN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (91, 'Guinea-bissau', 'GW', 'GNB', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (92, 'Guyana', 'GY', 'GUY', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (93, 'Haiti', 'HT', 'HTI', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (94, 'Heard and Mc Donald Islands', 'HM', 'HMD', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (95, 'Honduras', 'HN', 'HND', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (96, 'Hong Kong', 'HK', 'HKG', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (97, 'Hungary', 'HU', 'HUN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (98, 'Iceland', 'IS', 'ISL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (99, 'India', 'IN', 'IND', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (100, 'Indonesia', 'ID', 'IDN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (101, 'Iran (Islamic Republic of)', 'IR', 'IRN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (102, 'Iraq', 'IQ', 'IRQ', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (103, 'Ireland', 'IE', 'IRL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (104, 'Israel', 'IL', 'ISR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (105, 'Italy', 'IT', 'ITA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (106, 'Jamaica', 'JM', 'JAM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (107, 'Japan', 'JP', 'JPN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (108, 'Jordan', 'JO', 'JOR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (109, 'Kazakhstan', 'KZ', 'KAZ', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (110, 'Kenya', 'KE', 'KEN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (111, 'Kiribati', 'KI', 'KIR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (112, 'Korea', 'KP', 'PRK', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (114, 'Kuwait', 'KW', 'KWT', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (115, 'Kyrgyzstan', 'KG', 'KGZ', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (116, 'Lao People''s Democratic Republic', 'LA', 'LAO', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (117, 'Latvia', 'LV', 'LVA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (118, 'Lebanon', 'LB', 'LBN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (119, 'Lesotho', 'LS', 'LSO', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (120, 'Liberia', 'LR', 'LBR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (121, 'Libyan Arab Jamahiriya', 'LY', 'LBY', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (122, 'Liechtenstein', 'LI', 'LIE', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (123, 'Lithuania', 'LT', 'LTU', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (124, 'Luxembourg', 'LU', 'LUX', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (125, 'Macau', 'MO', 'MAC', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (126, 'Macedonia', 'MK', 'MKD', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (127, 'Madagascar', 'MG', 'MDG', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (128, 'Malawi', 'MW', 'MWI', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (129, 'Malaysia', 'MY', 'MYS', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (130, 'Maldives', 'MV', 'MDV', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (131, 'Mali', 'ML', 'MLI', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (132, 'Malta', 'MT', 'MLT', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (133, 'Marshall Islands', 'MH', 'MHL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (134, 'Martinique', 'MQ', 'MTQ', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (135, 'Mauritania', 'MR', 'MRT', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (136, 'Mauritius', 'MU', 'MUS', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (137, 'Mayotte', 'YT', 'MYT', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (138, 'Mexico', 'MX', 'MEX', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (139, 'Micronesia, Federated States of', 'FM', 'FSM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (140, 'Moldova, Republic of', 'MD', 'MDA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (141, 'Monaco', 'MC', 'MCO', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (142, 'Mongolia', 'MN', 'MNG', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (143, 'Montserrat', 'MS', 'MSR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (144, 'Morocco', 'MA', 'MAR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (145, 'Mozambique', 'MZ', 'MOZ', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (146, 'Myanmar', 'MM', 'MMR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (147, 'Namibia', 'NA', 'NAM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (148, 'Nauru', 'NR', 'NRU', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (149, 'Nepal', 'NP', 'NPL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (150, 'Netherlands', 'NL', 'NLD', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (151, 'Netherlands Antilles', 'AN', 'ANT', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (152, 'New Caledonia', 'NC', 'NCL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (153, 'New Zealand', 'NZ', 'NZL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (154, 'Nicaragua', 'NI', 'NIC', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (155, 'Niger', 'NE', 'NER', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (156, 'Nigeria', 'NG', 'NGA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (157, 'Niue', 'NU', 'NIU', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (158, 'Norfolk Island', 'NF', 'NFK', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (159, 'Northern Mariana Islands', 'MP', 'MNP', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (160, 'Norway', 'NO', 'NOR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (161, 'Oman', 'OM', 'OMN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (162, 'Pakistan', 'PK', 'PAK', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (163, 'Palau', 'PW', 'PLW', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (164, 'Panama', 'PA', 'PAN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (165, 'Papua New Guinea', 'PG', 'PNG', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (166, 'Paraguay', 'PY', 'PRY', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (167, 'Peru', 'PE', 'PER', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (168, 'Philippines', 'PH', 'PHL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (169, 'Pitcairn', 'PN', 'PCN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (170, 'Poland', 'PL', 'POL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (171, 'Portugal', 'PT', 'PRT', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (172, 'Puerto Rico', 'PR', 'PRI', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (173, 'Qatar', 'QA', 'QAT', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (174, 'Reunion', 'RE', 'REU', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (175, 'Romania', 'RO', 'ROM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (176, 'Russian Federation', 'RU', 'RUS', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (177, 'Rwanda', 'RW', 'RWA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (178, 'Saint Kitts and Nevis', 'KN', 'KNA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (179, 'Saint Lucia', 'LC', 'LCA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (181, 'Samoa', 'WS', 'WSM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (182, 'San Marino', 'SM', 'SMR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (183, 'Sao Tome and Principe', 'ST', 'STP', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (184, 'Saudi Arabia', 'SA', 'SAU', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (185, 'Senegal', 'SN', 'SEN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (186, 'Seychelles', 'SC', 'SYC', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (187, 'Sierra Leone', 'SL', 'SLE', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (188, 'Singapore', 'SG', 'SGP', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (189, 'Slovakia (Slovak Republic)', 'SK', 'SVK', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (190, 'Slovenia', 'SI', 'SVN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (191, 'Solomon Islands', 'SB', 'SLB', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (192, 'Somalia', 'SO', 'SOM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (193, 'South Africa', 'ZA', 'ZAF', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (194, 'South Georgia', 'GS', 'SGS', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (195, 'Spain', 'ES', 'ESP', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (196, 'Sri Lanka', 'LK', 'LKA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (197, 'St. Helena', 'SH', 'SHN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (198, 'St. Pierre and Miquelon', 'PM', 'SPM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (199, 'Sudan', 'SD', 'SDN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (200, 'Suriname', 'SR', 'SUR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (202, 'Swaziland', 'SZ', 'SWZ', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (203, 'Sweden', 'SE', 'SWE', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (204, 'Switzerland', 'CH', 'CHE', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (205, 'Syrian Arab Republic', 'SY', 'SYR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (206, 'Taiwan', 'TW', 'TWN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (207, 'Tajikistan', 'TJ', 'TJK', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (208, 'Tanzania, United Republic of', 'TZ', 'TZA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (209, 'Thailand', 'TH', 'THA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (210, 'Togo', 'TG', 'TGO', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (211, 'Tokelau', 'TK', 'TKL', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (212, 'Tonga', 'TO', 'TON', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (213, 'Trinidad and Tobago', 'TT', 'TTO', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (214, 'Tunisia', 'TN', 'TUN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (215, 'Turkey', 'TR', 'TUR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (216, 'Turkmenistan', 'TM', 'TKM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (217, 'Turks and Caicos Islands', 'TC', 'TCA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (218, 'Tuvalu', 'TV', 'TUV', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (219, 'Uganda', 'UG', 'UGA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (220, 'Ukraine', 'UA', 'UKR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (221, 'United Arab Emirates', 'AE', 'ARE', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (222, 'United Kingdom', 'GB', 'GBR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (223, 'United States', 'US', 'USA', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (224, 'United States Minor Outlying Islands', 'UM', 'UMI', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (225, 'Uruguay', 'UY', 'URY', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (226, 'Uzbekistan', 'UZ', 'UZB', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (227, 'Vanuatu', 'VU', 'VUT', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (228, 'Vatican City State (Holy See)', 'VA', 'VAT', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (229, 'Venezuela', 'VE', 'VEN', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (230, 'Viet Nam', 'VN', 'VNM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (231, 'Virgin Islands (British)', 'VG', 'VGB', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (232, 'Virgin Islands (U.S.)', 'VI', 'VIR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (233, 'Wallis and Futuna Islands', 'WF', 'WLF', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (234, 'Western Sahara', 'EH', 'ESH', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (235, 'Yemen', 'YE', 'YEM', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (236, 'Yugoslavia', 'YU', 'YUG', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (237, 'Zaire', 'ZR', 'ZAR', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (238, 'Zambia', 'ZM', 'ZMB', 0);
INSERT INTO `countries` (`country_id`, `country_name`, `iso_code2`, `iso_code3`, `reserved1`) values (239, 'Zimbabwe', 'ZW', 'ZWE', 0);