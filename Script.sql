alter table chests drop constraint chests_users;
/
alter table files drop constraint files_chests;
/
alter table files_tags drop constraint files_tags_tag ;
/
alter table files_tags drop constraint files_tags_file ;
/
alter table files_relatives drop constraint files_relatives_relative ;
/
alter table files_relatives drop constraint files_relatives_file;
/


drop table chests;
/
create table chests (
chest_id number(10,0) PRIMARY KEY,
user_id number(10,0),
capacity number(10,0),
freeSlots number(10,0),
description varchar2(2000),
name varchar2(200),
createdat date
)
;
/


alter table chests add constraint chests_users foreign key (user_id) references users(id) ;
/




drop table files;
/
create table files (
file_id number(10,0) PRIMARY KEY,
chest_id number(10,0),
name varchar2(255),
type varchar2(255),
path varchar2(255),
createdat date,
origin varchar2(255)
)
;


alter table files add constraint files_chests foreign key (chest_id) references chests(chest_id) ;
/



drop table tags;
/
create table tags( 
tag_id number(10,0) Primary key,
name varchar2(100)
);
/


drop table files_tags;

create table files_tags (
tag_id number(10,0),
file_id number(10,0)
);
alter table files_tags add constraint files_tags_tag foreign key(tag_id) references tags(tag_id);
/
alter table files_tags add constraint files_tags_file foreign key(file_id) references files(file_id);
/

drop table relatives;
/

create table relatives( 
relative_id number(10,0) Primary key,
name varchar2(100)
);
/

drop table files_relatives;

create table files_relatives (
relative_id number(10,0),
file_id number(10,0)
);
/

alter table files_relatives add constraint files_relatives_relative foreign key (relative_id) references relatives(relative_id);
/
alter table files_relatives add constraint files_relatives_file foreign key (file_id) references files(file_id);
/




