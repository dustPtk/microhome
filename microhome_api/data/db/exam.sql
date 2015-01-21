DROP database IF EXISTS shujuxia;

create database shujuxia;

use shujuxia;

-- 此表是存储试题(题库)
DROP table IF EXISTS shujuxia_exam_topic;
create table shujuxia_exam_topic(
id int(4) unsigned not null auto_increment,
type varchar(10) not null,
topic varchar(100) not null,
options varchar(100) not null,
answer varchar(100) not null,
score int not null,
primary key (id)
)engine =InnoDB default charset=utf8;



insert into shujuxia_exam_topic(type,topic,options,answer,score) values ('S','你是人吗?','是@@不是','是',5);
insert into shujuxia_exam_topic(type,topic,options,answer,score) values ('S','你是猪吗?','是@@不是','是',5);
insert into shujuxia_exam_topic(type,topic,options,answer,score) values ('S','你是狗吗?','是@@不是','是',5);
insert into shujuxia_exam_topic(type,topic,options,answer,score) values ('S','你是猫吗?','是@@不是','是',5);
insert into shujuxia_exam_topic(type,topic,options,answer,score) values ('S','你还好吗','好@@好个蛋','好个蛋',50);
insert into shujuxia_exam_topic(type,topic,options,answer,score) values ('S','今天吃什么？','蛋@@鸡蛋@@鸭蛋@@鹅蛋','蛋@@鹅蛋',5);
insert into shujuxia_exam_topic(type,topic,options,answer,score) values ('S','小明假有兄弟三人，老大叫大毛，老二叫二毛，老三叫什么？','三毛@@阿毛@@阿三@@小明','小明',5);
insert into shujuxia_exam_topic(type,topic,options,answer,score) values ('S','茉莉花、太阳花、玫瑰花哪一朵花最没力？','茉莉花@@太阳花@@玫瑰花','茉莉花',5);
insert into shujuxia_exam_topic(type,topic,options,answer,score) values ('S','关于龙的形象，自古以来就有“角似鹿，头似驼，眼似兔，项似蛇，腹似蜃，鳞似鱼，爪似鹰，掌似虎，耳似牛”的说法，这表明？','观念的东西是转入人脑并在人脑中改造过的物质的东西@@一切观念都是现实的模仿@@虚幻的观念也是对事物本质的反映@@任何观念都可以从现实世界中找到其物质“原型”','观念的东西是转入人脑并在人脑中改造过的物质的东西@@任何观念都可以从现实世界中找到其物质“原型”',5);

-- 此表存储pc用户信息

DROP table IF EXISTS shujuxia_exam_pcusers;
create table  shujuxia_exam_pcusers(
id int(4) unsigned not null auto_increment,
username varchar(25) not null,
password varchar(32) not null,
repassword varchar(32) not null,
name varchar(30) not null,
gender tinyint(1) not null,
email varchar(30) not null,
phonenum varchar(30) not null,
primary key (id)
) engine=InnoDB default charset=utf8;


-- 此表储存试卷信息

DROP table IF EXISTS shujuxia_exam_exampaper;

create table shujuxia_exam_exampaper(
id int(4) unsigned not null auto_increment,
title varchar(20)  default null,
time varchar(10) default 0,
intro varchar(100) default null,
primary key (id)
)engine=InnoDB default charset=utf8;

insert into shujuxia_exam_exampaper(title,time,intro) values('人格测试','90','本测试是检测你是否是正常的人类');
-- 此表存储试卷内容
DROP table IF EXISTS shujuxia_exam_examcontent;
create table shujuxia_exam_examcontent(
id int(4) unsigned not null auto_increment,
exampaper_id int(4) not null,
examtopic_id int(4) not null,
primary key(id)
)engine=InnoDB default charset=utf8;

insert into shujuxia_exam_examcontent(exampaper_id,examtopic_id) values(1,1);
insert into shujuxia_exam_examcontent(exampaper_id,examtopic_id) values(1,2);
insert into shujuxia_exam_examcontent(exampaper_id,examtopic_id) values(1,3);
insert into shujuxia_exam_examcontent(exampaper_id,examtopic_id) values(1,4);
-- insert into shujuxia_exam_examcontent(exampaper_id,examtopic_id) values(6,12);
-- insert into shujuxia_exam_examcontent(exampaper_id,examtopic_id) values(6,13);
-- insert into shujuxia_exam_examcontent(exampaper_id,examtopic_id) values(6,14);
-- insert into shujuxia_exam_examcontent(exampaper_id,examtopic_id) values(6,15);
-- insert into shujuxia_exam_examcontent(exampaper_id,examtopic_id) values(6,16);
-- 考生成绩表
DROP table IF EXISTS shujuxia_exam_userscore;
create table shujuxia_exam_userscore(
id int(4) unsigned not null auto_increment,
user_id int(4) not null,
exampaper_id int(4) not null,
score varchar(10) default null,
primary key(id)
)engine =InnoDB default charset=utf8;

insert into shujuxia_exam_userscore(user_id,exampaper_id,score) values('1','1','50');
insert into shujuxia_exam_userscore(user_id,exampaper_id,score) values('2','2','80');