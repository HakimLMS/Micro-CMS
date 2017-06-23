/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Hakim
 * Created: 23 juin 2017
 */
drop table if exists t_articles;

create table t_articles
(
art_id integer not null primary key auto_increment,
art_title varchar (100) not null, 
art_content varchar (2000) not null)
engine=innodb character set utf8 collate utf8_unicode_ci;
