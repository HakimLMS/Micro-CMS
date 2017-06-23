/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Hakim
 * Created: 23 juin 2017
 */
create database if not exists microcms character set utf8 collate utf8_unicode_ci;
use microcms;

grant all privileges on microcms.* to 'microcms_user'@'localhost' identified by 'secret';

