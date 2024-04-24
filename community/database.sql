CREATE TABLE `member` (
	`mb_seq` INT(10) NOT NULL AUTO_INCREMENT,
	`mb_name` CHAR(50) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`mb_id` CHAR(50) NOT NULL DEFAULT '0' COLLATE 'utf8mb4_0900_ai_ci',
	`mb_pw` VARCHAR(50) NOT NULL DEFAULT '0' COLLATE 'utf8mb4_0900_ai_ci',
	`mb_nick` CHAR(50) NOT NULL DEFAULT '0' COLLATE 'utf8mb4_0900_ai_ci',
	`mb_exp` INT(10) NOT NULL DEFAULT '0',
	`mb_mobile` CHAR(50) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`mb_profile` VARCHAR(300) NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`mb_register` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`mb_current` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`mb_permission` CHAR(3) NOT NULL DEFAULT '000' COMMENT '000 : 일반 900 : 관리자 권한' COLLATE 'utf8mb4_0900_ai_ci',
	UNIQUE INDEX `mb_seq` (`mb_seq`) USING BTREE,
	UNIQUE INDEX `mb_id` (`mb_id`) USING BTREE,
	UNIQUE INDEX `mb_nick` (`mb_nick`) USING BTREE,
	UNIQUE INDEX `mb_mobile` (`mb_mobile`) USING BTREE,
	UNIQUE INDEX `mb_name` (`mb_name`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
;

CREATE TABLE `board_up` (
	`bu_seq` INT(10) NOT NULL AUTO_INCREMENT,
	`bc_seq` INT(10) NOT NULL DEFAULT '0',
	`mb_seq` INT(10) NOT NULL DEFAULT '0',
	PRIMARY KEY (`bu_seq`) USING BTREE,
	INDEX `FK__board_bu` (`bc_seq`) USING BTREE,
	INDEX `FK__member_bu` (`mb_seq`) USING BTREE,
	CONSTRAINT `FK__board_bu` FOREIGN KEY (`bc_seq`) REFERENCES `board` (`bc_seq`) ON UPDATE CASCADE ON DELETE NO ACTION,
	CONSTRAINT `FK__member_bu` FOREIGN KEY (`mb_seq`) REFERENCES `member` (`mb_seq`) ON UPDATE CASCADE ON DELETE NO ACTION
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
;




CREATE TABLE `category` (
	`ca_seq` INT NOT NULL AUTO_INCREMENT,
	`ca_name` CHAR(15) NOT NULL DEFAULT '0',
	`ca_url` CHAR(15) NOT NULL,
	`ca_enable` TINYINT NOT NULL DEFAULT 0 COMMENT '카테고리 사용여부',
	`ca_order` TINYINT NOT NULL DEFAULT 0 COMMENT '카테고리 순서 작을수록 가장 앞으로 0 > 1 > 2 ...',
    `ca_delete` TINYINT(1) NOT NULL COMMENT '카테고리 제거 여부',
	`ca_write` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '카테고리 일반 유저 글작성 여부',
	UNIQUE INDEX `ca_seq` (`ca_seq`)
)
COLLATE='utf8mb4_0900_ai_ci'
;

CREATE TABLE `board` (
	`bc_seq` INT(10) NOT NULL AUTO_INCREMENT,
	`ca_seq` INT(10) NOT NULL,
	`bc_title` CHAR(50) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`bc_content` TEXT NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`bc_read` INT(10) NOT NULL DEFAULT '0',
	`bc_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	`bc_delete` TINYINT NOT NULL DEFAULT 0 COMMENT '삭제여부',
	UNIQUE INDEX `cb_seq` (`bc_seq`) USING BTREE,
	INDEX `FK_board_category` (`ca_seq`) USING BTREE,
	CONSTRAINT `FK_board_category` FOREIGN KEY (`ca_seq`) REFERENCES `category` (`ca_seq`) ON UPDATE CASCADE ON DELETE NO ACTION
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
;

CREATE TABLE `comment` (
	`cm_seq` INT(10) NOT NULL AUTO_INCREMENT,
	`mb_seq` INT(10) NOT NULL DEFAULT '0',
	`bc_seq` INT(10) NOT NULL DEFAULT '0',
	`cm_comment` TEXT NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`cm_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`cm_delete` TINYINT(3) NOT NULL DEFAULT '0',
	PRIMARY KEY (`cm_seq`) USING BTREE,
	INDEX `FK__member` (`mb_seq`) USING BTREE,
	INDEX `FK__board` (`bc_seq`) USING BTREE,
	CONSTRAINT `FK__board` FOREIGN KEY (`bc_seq`) REFERENCES `board` (`bc_seq`) ON UPDATE CASCADE ON DELETE NO ACTION,
	CONSTRAINT `FK__member` FOREIGN KEY (`mb_seq`) REFERENCES `member` (`mb_seq`) ON UPDATE CASCADE ON DELETE NO ACTION
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
;
