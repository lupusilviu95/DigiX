


  CREATE TABLE "STUDENT"."USERS" 
   (	"ID" NUMBER(10,0) NOT NULL ENABLE, 
	"NAME" VARCHAR2(255) NOT NULL ENABLE, 
	"EMAIL" VARCHAR2(255) NOT NULL ENABLE, 
	"password" VARCHAR2(255) NOT NULL ENABLE, 
	"REMEMBER_TOKEN" VARCHAR2(100), 
	"CREATED_AT" TIMESTAMP (6), 
	"UPDATED_AT" TIMESTAMP (6), 
	 CONSTRAINT "USERS_ID_PK" PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1 BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE, 
	 CONSTRAINT "USERS_EMAIL_UK" UNIQUE ("EMAIL")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1 BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1 BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
  /


----CHESTS

create table chests (
chest_id number(10,0) PRIMARY KEY,
user_id number(10,0),
capacity number(10,0),
freeSlots number(10,0),
description varchar2(2000),
name varchar2(200)
)
;
/


alter table chests add constraint chests_users foreign key (user_id) references users(id);
/


------FILES

create table files (
file_id number(10,0) PRIMARY KEY,
chest_id number(10,0),
name varchar2(255),
type varchar2(255),
path varchar2(255)
)
;
/
alter table files add constraint files_chests foreign key (chest_id) references chests(chest_id);
/

-----TAGS
create table tags( 
tag_id number(10,0) Primary key,
name varchar2(100)
);
/

create table files_tags (
tag_id number(10,0),
file_id number(10,0)
);
alter table files_tags add constraint files_tags_tag foreign key(tag_id) references tags(tag_id);
/
alter table files_tags add constraint files_tags_file foreign key(file_id) references files(file_id);
/


----RELATIVES

create table relatives( 
relative_id number(10,0) Primary key,
name varchar2(100)
);
/


create table files_relatives (
relative_id number(10,0),
file_id number(10,0)
);
/

alter table files_relatives add constraint files_relatives_relative foreign key (relative_id) references relatives(relative_id);
/
alter table files_relatives add constraint files_relatives_file foreign key (file_id) references files(file_id);
/


commit;
/
