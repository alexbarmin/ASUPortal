ALTER TABLE `kadri` CHANGE `stag_ugatu` `experience_ugatu` CHAR(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'стаж в УГАТУ',
 CHANGE `stag_pps` `experience_pps` CHAR(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'стаж ППС',
  CHANGE `stag_itogo` `experience_itogo` CHAR(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'стаж общий';