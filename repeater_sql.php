CREATE TABLE repeater (
	repeater_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	repeater_callsign CHAR(10) DEFAULT NULL,
	repeater_band_fk SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	repeater_channel_fk CHAR(10) DEFAULT NULL,
	repeater_rx DECIMAL(12,4) DEFAULT NULL,
	repeater_tx DECIMAL(12,4) DEFAULT NULL,
	repeater_split DECIMAL(12,4) DEFAULT NULL,
	repeater_thing CHAR(10) NULL DEFAULT NULL,
	repeater_type_fk SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	repeater_locator CHAR(10) DEFAULT NULL,
	repeater_town VARCHAR(30) DEFAULT NULL,
	repeater_ngr CHAR(15) DEFAULT NULL,
	repeater_region_fk SMALLINT(6) DEFAULT NULL,
	repeater_ctcss_fk CHAR(2) DEFAULT NULL,
	repeater_keeper VARCHAR(30) DEFAULT NULL,
	repeater_lat DECIMAL(10,6) DEFAULT NULL,
	repeater_long DECIMAL(10,6) DEFAULT NULL,
	repeater_op TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
	repeater_status_fk SMALLINT(5) UNSIGNED NOT NULL DEFAULT '1',
	repeater_lastupdated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (repeater_id),
	INDEX repeater_callsign (repeater_callsign),
	INDEX repeater_long (repeater_long),
	INDEX repeater_lat (repeater_lat)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE repeater_band (
	repeater_band_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	repeater_band_name CHAR(10) DEFAULT NULL,
	repeater_band_lastupdated TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (repeater_band_id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE repeater_type (
	repeater_type_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	repeater_type_name VARCHAR(15) DEFAULT NULL,
	repeater_typeDescription TINYTEXT NULL,
	repeater_type_lastupdated TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (repeater_type_id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE repeater_region (
	repeater_region_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	repeater_region_name VARCHAR(20) DEFAULT NULL,
	repeater_region_code CHAR(6) NOT NULL,
	repeater_region_lastupdated TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (repeater_region_id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE repeater_ctcss (
	repeater_ctcss_id CHAR(2) NOT NULL DEFAULT '0',
	repeater_ctcss DECIMAL(6,1) DEFAULT NULL,
	repeater_ctcss_lastupdated TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (repeater_ctcss_id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE e107_repeater_status (
	repeater_status_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	repeater_status VARCHAR(20)  DEFAULT NULL,
	repeater_statusDescription TINYTEXT NULL,
	repeater_status_lastupdated TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (repeater_status_id)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE repeater_users (
  repeater_user_id int(10) unsigned NOT NULL DEFAULT '0',
  repeater_user_settings text,
  repeater_user_lastupdated TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (repeater_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE repeater_audit (
  repeater_audit_id int(10) unsigned NOT NULL AUTO_INCREMENT,
  repeater_audit_settings text,
  repeater_audit_userid int(10) unsigned NOT NULL DEFAULT '0',
  repeater_audit_ip char(20) DEFAULT NULL,
  repeater_audit_datetime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (repeater_audit_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE repeater_channel (
	repeater_channelID SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	repeater_channelName CHAR(10) NOT NULL,
	repeater_channelBandfk SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	repeater_channelTx DECIMAL(12,4) UNSIGNED NOT NULL DEFAULT '0.00',
	repeater_channelRx DECIMAL(12,4) UNSIGNED NOT NULL DEFAULT '0.00',
	repeater_channelLastupdate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (repeater_channelID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;