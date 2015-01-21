-- 创建用户表
drop table IF EXISTS shujuxia_microhome_user;

create table shujuxia_microhome_user(
id int(4) unsigned not null auto_increment,
open_id varchar(30) not null,
app_id varchar(30) not null,
username varchar(30),
primary key(id)
)engine= InnoDB default charset = utf8;

-- 创建信息表
 drop table IF EXISTS shujuxia_microhome_info;

 create table shujuxia_microhome_info(

 id int(4) unsigned not null auto_increment,
 create_at int(16) not null,
 praise int(4) not null default 0,
 comment_num int(4) not null default 0,
 content varchar(100) not null,
 number varchar(32) default null,
 info_status int(1) not null default 0,
 open_id varchar(32) not null,
 primary key(id)
 )engine = InnoDB default charset=utf8;

insert into shujuxia_microhome_info(create_at,praise,comment_num,content,number,info_status,open_id) values(20141219,40,5,'gfsadbfeabare','',1,'f57383ufktdjmsrnm');
insert into shujuxia_microhome_info(create_at,praise,comment_num,content,number,info_status,open_id) values('NOW()',82,14,'ggfsnymmuyre','',1,'sfnfdnym5rnm');


-- 创建图片表
drop table IF EXISTS shujuxia_microhome_img;

create table shujuxia_microhome_img(
id int(4) unsigned not null auto_increment,
img_max_url varchar(32) not null,
img_min_url varchar(32) not null,
info_id int(4) not null,
primary key(id)
)engine = InnoDB default charset=utf8;

-- 创建审核表
drop table IF EXISTS shujuxia_microhome_scrutiny;

create table shujuxia_microhome_scrutiny(
id int(4) unsigned not null auto_increment,
no_allow_content varchar(255) not null,
info_id int(4) not null,
create_at int(16) not null,
scrutiny_user varchar(12) not null,
primary key(id)
)engine = InnoDB default charset=utf8;
-- 创建评论表

drop table IF EXISTS  shujuxia_microhome_comment;

create table shujuxia_microhome_comment(
id int(4) unsigned not null auto_increment,
content varchar(255) not null,
open_id varchar (32) not null,
create_at int(16) not null,
info_id int(4) not null,
primary key(id)
)engine = InnoDB default charset=utf8;


--创建点赞表

drop table IF EXISTS  shujuxia_microhome_praise;

create table shujuxia_microhome_praise(
id int(4) unsigned not null auto_increment,
info_id int(4) not null,
open_id varchar(32) not null,
flag int(1) default 1,
primary key(id)
)engine=InnoDB default charset=utf8;