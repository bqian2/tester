create database if not exists db_main;
create database if not exists db_session;

use db_session;
create table if not exists user_session (
    user_id bigint AUTO_INCREMENT,
    secure varchar(64),
    token char(64),
    last_access DATETIME,
    primary key (user_id));


use db_main;


create table if not exists user (
    seq_id bigint AUTO_INCREMENT,
    user_id varchar(20),
    hashed_password varchar(64),
    user_state char(4),
    unique key (user_id),
    primary key (seq_id));

create table if not exists user_sid (
    user_id varchar(20),
    sid varchar(64),
    expiry datetime,
    unique key (user_id));

create table if not exists user_profile (
    user_seq_id bigint,
    firstname varchar(40),
    lastname varchar(40),
    email varchar(40),
    register_date datetime,
    activate_date datetime,
    unique key (email),
    primary key (user_seq_id),
    foreign key (user_seq_id)
       references user(seq_id));

create table if not exists sp_list (
    user_seq_id bigint,
    spl_id bigint AUTO_INCREMENT,
    name varchar(16),
    spl_type varchar(4),
    spl_state varchar(2),
    primary key (spl_id),
    foreign key (user_seq_id)
       references user(seq_id));

create table if not exists spl_item(
    spl_id bigint,
    spli_id bigint,
    name varchar(16),
    valume int,
    spli_unit varchar(4),
    spli_state varchar(2),
    primary key (spli_id),
    foreign key (spl_id)
       references sp_list(spl_id));


DROP PROCEDURE IF EXISTS create_user;

DELIMITER //
CREATE PROCEDURE create_user(IN u_id varchar(20), IN hashed_password varchar(64), IN email varchar(40), OUT seq_id bigint)
BEGIN
     START TRANSACTION;
     INSERT IGNORE INTO `user` set user.user_id=u_id, user.hashed_password=hashed_password, user.user_state='ct';
     SELECT LAST_INSERT_ID() into @user_seq_id;
     IF @user_seq_id > 0 THEN INSERT IGNORE INTO `user_profile` SET user_profile.user_seq_id = @user_seq_id, user_profile.email = email, register_date=now();
     END IF;
     COMMIT;
     select seq_id, user_state, email, register_date from user inner join user_profile on user.seq_id = user_profile.user_seq_id where user.seq_id = @user_seq_id;
END //
DELIMITER ;


GRANT ALL PRIVILEGES ON db_main.* to 'bqian'@'localhost';
GRANT ALL PRIVILEGES ON db_session.* to 'bqian'@'localhost';
GRANT ALL PRIVILEGES ON db_main.* to 'apache'@'localhost';
GRANT ALL PRIVILEGES ON db_session.* to 'apache'@'localhost';

