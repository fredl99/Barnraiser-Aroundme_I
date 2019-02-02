-- 
-- Table structure for table `am_block`
-- 

CREATE TABLE `am_block` (
  `block_id` int(11) NOT NULL auto_increment,
  `webspace_id` int(11) NOT NULL default '0',
  `block_plugin` varchar(100) NOT NULL default '',
  `block_name` varchar(100) NOT NULL default '',
  `block_body` text,
  PRIMARY KEY  (`block_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `am_connection`
-- 

CREATE TABLE `am_connection` (
  `connection_id` int(11) NOT NULL auto_increment,
  `identity_id` int(11) NOT NULL default '0',
  `connection_openid` varchar(200) NOT NULL default '',
  `connection_create_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `connection_last_datetime` datetime default NULL,
  `connection_total` int(11) NOT NULL default '0',
  `connection_trusted` int(11) default NULL,
  `status_id` int(1) NOT NULL default '0',
  PRIMARY KEY  (`connection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `am_connection_attribute`
-- 

CREATE TABLE `am_connection_attribute` (
  `connection_id` int(11) NOT NULL default '0',
  `attribute_name` varchar(50) NOT NULL default '',
  `attribute_value` varchar(255) NOT NULL default '',
  `level_id` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Table structure for table `am_file`
-- 

CREATE TABLE `am_file` (
  `file_id` int(11) NOT NULL auto_increment,
  `file_type` varchar(20) default NULL,
  `file_size` int(11) default NULL,
  `file_name` varchar(255) default NULL,
  `identity_id` int(11) default NULL,
  `file_create_datetime` datetime default NULL,
  `file_title` varchar(255) NOT NULL default '',
  `file_is_avatar` int(1) default NULL,
  PRIMARY KEY  (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `am_identity`
-- 

CREATE TABLE `am_identity` (
  `identity_id` int(11) NOT NULL auto_increment,
  `identity_name` varchar(50) NOT NULL default '',
  `identity_create_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `language_code` varchar(3) default NULL,
  `status_id` int(1) default NULL,
  `identity_password` varchar(255) NOT NULL default '',
  `owner_connection_id` int(11) default NULL,
  `identity_email` varchar(255) NOT NULL default '',
  `identity_email_verified` int(1) NOT NULL default '0',
  `identity_email_key` varchar(255) NOT NULL default '',
  `identity_new_password_key` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`identity_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `am_log`
-- 

CREATE TABLE `am_log` (
  `log_id` int(11) NOT NULL auto_increment,
  `identity_id` int(11) default NULL,
  `log_title` varchar(50) NOT NULL default '',
  `log_body` text,
  `log_link` varchar(100) default NULL,
  `log_create_datetime` datetime default NULL,
  PRIMARY KEY  (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `am_plugin_blog_comment`
-- 

CREATE TABLE `am_plugin_blog_comment` (
  `comment_id` int(11) NOT NULL auto_increment,
  `identity_id` int(11) NOT NULL default '0',
  `blog_id` int(11) NOT NULL default '0',
  `connection_id` int(11) default NULL,
  `comment_body` text,
  `comment_create_datetime` datetime default NULL,
  `comment_hidden` int(11) default NULL,
  PRIMARY KEY  (`comment_id`),
  FULLTEXT KEY `comment_body` (`comment_body`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `am_plugin_blog_entry`
-- 

CREATE TABLE `am_plugin_blog_entry` (
  `blog_id` int(11) NOT NULL auto_increment,
  `identity_id` int(11) default NULL,
  `blog_title` varchar(255) default NULL,
  `connection_id` int(11) default NULL,
  `blog_body` text,
  `blog_create_datetime` datetime default NULL,
  `blog_edit_datetime` datetime default NULL,
  `blog_archived` int(1) default NULL,
  `blog_allow_comment` int(1) default '1',
  PRIMARY KEY  (`blog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------


CREATE TABLE `am_plugin_blog_preference` (
  `preference_id` int(11) NOT NULL auto_increment,
  `identity_id` int(11) default NULL,
  `default_webpage_id` int(11) default NULL,
  `rss_title` varchar(255) default NULL,
  `rss_title_comment` varchar(255) default NULL,
  `rss_description` varchar(255) default NULL,
  PRIMARY KEY  (`preference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Table structure for table `am_plugin_guestbook`
-- 

CREATE TABLE `am_plugin_guestbook` (
  `guestbook_id` int(11) NOT NULL auto_increment,
  `identity_id` int(11) default NULL,
  `connection_id` int(11) default NULL,
  `guestbook_body` varchar(255) default NULL,
  `guestbook_create_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`guestbook_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `am_site`
-- 

CREATE TABLE `am_site` (
  `site_id` int(11) NOT NULL auto_increment,
  `site_title` varchar(255) NOT NULL default '',
  `site_trusted` int(1) NOT NULL default '0',
  `site_datetime_first_visit` datetime NOT NULL default '0000-00-00 00:00:00',
  `site_datetime_last_visit` datetime NOT NULL default '0000-00-00 00:00:00',
  `identity_id` int(11) NOT NULL default '0',
  `site_realm` varchar(255) NOT NULL default '',
  `site_connections` int(11) NOT NULL default '0',
  `site_data_sent` text NOT NULL,
  PRIMARY KEY  (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `am_stylesheet`
-- 

CREATE TABLE `am_stylesheet` (
  `stylesheet_id` int(11) NOT NULL auto_increment,
  `webspace_id` int(11) NOT NULL default '0',
  `stylesheet_name` varchar(50) NOT NULL default '',
  `stylesheet_body` text,
  PRIMARY KEY  (`stylesheet_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `am_webpage`
-- 

CREATE TABLE `am_webpage` (
  `webpage_id` int(11) NOT NULL auto_increment,
  `webspace_id` int(11) NOT NULL default '0',
  `webpage_body` text,
  `webpage_name` varchar(50) NOT NULL default '',
  `webpage_create_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`webpage_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `am_webspace`
-- 

CREATE TABLE `am_webspace` (
  `webspace_id` int(11) NOT NULL auto_increment,
  `identity_id` int(11) NOT NULL default '0',
  `webspace_title` varchar(200) default NULL,
  `default_webpage_id` int(11) NOT NULL default '0',
  `default_permission` int(11) NOT NULL default '0',
  `stylesheet_id` int(11) default NULL,
  `webspace_allocation` int(11) default NULL,
  PRIMARY KEY  (`webspace_id`),
  FULLTEXT KEY `webspace` (`webspace_title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `am_openid_session`
-- 

CREATE TABLE `am_openid_session` (
  `assoc_handle` varchar(200) default NULL,
  `assoc_type` varchar(200) default NULL,
  `enc_mac_key` varchar(200) default NULL,
  `expires_in` varchar(200) default NULL,
  `session_type` varchar(200) default NULL,
  `mac_key` varchar(200) default NULL,
  PRIMARY KEY  (`assoc_handle`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
