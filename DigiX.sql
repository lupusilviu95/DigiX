

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



alter table chests drop constraint chests_users;
alter table chests add constraint chests_users foreign key (user_id) references users(id) ON DELETE CASCADE;
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


select files.name,tags.name from files join files_tags on files.file_id=files_tags.file_id join tags on tags.tag_id = files_tags.tag_id;
select * from files_tags;
select * from tags;

/
alter table files drop constraint files_chests;
alter table files add constraint files_chests foreign key (chest_id) references chests(chest_id) ON DELETE CASCADE;


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

select * from files_tags;
delete from files_tags where tag_id=2; 

----RELATIVES

create table relatives( 
relative_id number(10,0) Primary key,
name varchar2(100)
);
/

select * from relatives;

insert into relatives values(1,'frate');

create table files_relatives (
relative_id number(10,0),
file_id number(10,0)
);
/

alter table files_relatives add constraint files_relatives_relative foreign key (relative_id) references relatives(relative_id);
/
alter table files_relatives add constraint files_relatives_file foreign key (file_id) references files(file_id);
/






create or replace package DigiXExceptions is 
inexistent_file EXCEPTION;
PRAGMA EXCEPTION_INIT(inexistent_file,-20010);
inexistent_chest EXCEPTION;
PRAGMA EXCEPTION_INIT(inexistent_chest,-20011);
end DigiXExceptions;
/

create or replace package DigiX is
  procedure deleteFile(id_fisier files.file_id%type);
  procedure deleteChest(id_chest chests.chest_id%type);
  function checkChestOwnership(id_user users.id%type,id_chest chests.chest_id%type) return integer;
  function newChest(id_user users.id%type,cap chests.capacity%type,frees chests.freeSlots%type,descr chests.description%type,nume chests.name%type) return integer;
  function addFile(id_chest files.chest_id%type,nume files.name%type, tip files.type%type,cale files.path%type) return integer;
  procedure addTagToFile(id_file files.file_id%type,tag varchar2);
  procedure addRelativeToFile(id_file files.file_id%type , rudenie varchar2);
  function getFilePath(id_fisier files.file_id%type ) return varchar2;
end DigiX;
  
  
  
  
create or replace package body DigiX is 
  counter INTEGER;
  maxId integer;
  nrRecords integer;
  
  function checkExistsFile(id_file files.file_id%type) return boolean as
  begin 
    select count(*) into counter from files where file_id=id_file;
    if counter=1 then
        return true;
      else return false;
    end if;
  end checkExistsFile;
  function getFilePath(id_fisier files.file_id%type ) return varchar2 is
  cale varchar2(100);
  begin
     if(checkExistsFile(id_fisier)=false)
      then raise DigixExceptions.inexistent_file;
     end if;
     select path into cale from files where file_id =id_fisier;
     return cale;
     EXCEPTION
     when DigixExceptions.inexistent_file then
     RAISE_APPLICATION_ERROR(-20010,'FISIERUL CU ID-UL SPECIFICAT NU EXISTA');
  end;
  procedure deleteFile(id_fisier files.file_id%type) is
  begin 
     if(checkExistsFile(id_fisier)=false)
      then raise DigixExceptions.inexistent_file;
     end if;
     delete from files_relatives where file_id=id_fisier;
     delete from files_tags where file_id=id_fisier;
     delete from files where file_id=id_fisier;
     EXCEPTION
     when DigixExceptions.inexistent_file then
     RAISE_APPLICATION_ERROR(-20010,'FISIERUL CU ID-UL SPECIFICAT NU EXISTA');
  end;
  
  function checkExistsChest(id_chest chests.chest_id%type) return boolean as
  begin 
    select count(*) into counter from chests where chest_id=id_chest;
    if counter=1 then
        return true;
      else return false;
    end if;
  end checkExistsChest;
  
  procedure deleteChest(id_chest chests.chest_id%type) is
  begin
   if(checkExistsChest(id_chest)=false)
      then raise DigixExceptions.inexistent_chest;
     end if;
     delete from files where chest_id=id_chest;
     delete from chests where chest_id=id_chest;
     EXCEPTION
     when DigixExceptions.inexistent_chest then
     RAISE_APPLICATION_ERROR(-20011,'Cufarul CU ID-UL SPECIFICAT NU EXISTA');
  end;
  function checkChestOwnership(id_user users.id%type,id_chest chests.chest_id%type) return integer is
  begin
    select 1 into counter from chests join users on id=user_id where users.id=id_user and chest_id=id_chest;
    if(counter =1) then 
      return 1;
    end if;
    EXCEPTION
    when no_data_found then
    return 0;
    
  end;
  
  function newChest(id_user users.id%type,cap chests.capacity%type,frees chests.freeSlots%type,descr chests.description%type,nume chests.name%type) return integer is 
  begin
    select count(*) into nrRecords from chests;
    if(nrRecords=0) then
    maxId:=1;
    else
    select max(chest_id) into maxID from chests;
    maxID:=maxID+1;
    end if;
    insert into chests values(maxID,id_user,cap,frees,descr,nume);
    return maxID;
  end;
  
  function addFile(id_chest files.chest_id%type,nume files.name%type, tip files.type%type,cale files.path%type) return integer is 
  begin
  select count(*) into nrRecords from files;
    if(nrRecords=0) then
    maxId:=1;
    else
   select max(file_id) into maxID from files;
   maxID:=maxID+1;
   end if;
   insert into files values(maxID,id_chest,nume,tip,cale);
   return maxID;
  end;
  
  procedure addTagToFile(id_file files.file_id%type,tag varchar2) is 
  begin 
   select count(*) into counter from tags where name=tag;
   if(counter=1)  then  -- tagul exista deja 
    select tag_id into maxID from tags where name=tag;
   insert into files_tags values(maxID,id_file);
   else 
        select count(*) into nrRecords from tags;
        if(nrRecords=0) then
        maxId:=1;
        else
        select max(tag_id) into maxID from tags;
        maxID:=maxID+1;
        end if;
        insert into tags values(maxID,tag);
        insert into files_tags values(maxID,id_file);
   end if;
  end;
  
  
  procedure addRelativeToFile(id_file files.file_id%type , rudenie varchar2) is 
  begin 
   select count(*) into counter from relatives where name=rudenie;
   if(counter=1)  then  -- gradul de rudenie exista deja 
    select relative_id into maxID from relatives where name=rudenie;
   insert into files_relatives values(maxID,id_file);
   else 
        select count(*) into nrRecords from relatives;
        if(nrRecords=0) then
        maxId:=1;
        else
        select max(relative_id) into maxID from relatives;
        maxID:=maxID+1;
        end if;
        insert into relatives values(maxID,rudenie);
        insert into files_relatives values(maxID,id_file);
   end if;
  end;
  
end DigiX;
/


create or replace trigger decreaseFreeSlots after insert on files
for each row 
declare 
old_slots number;
chest number;
begin
  chest:=:new.chest_id;
  select freeSlots into old_slots from chests where chest_id=chest;
  old_slots:=old_slots-1;
  update chests set freeSlots=old_slots where chest_id=chest;
end decreaseFreeSlots;

/
create or replace trigger increaseFreeSlots after delete on files
for each row 
declare 
old_slots number;
chest number;
begin
  chest:=:old.chest_id;
  select freeSlots into old_slots from chests where chest_id=chest;
  old_slots:=old_slots+1;
  update chests set freeSlots=old_slots where chest_id=chest;
end decreaseFreeSlots;
/



commit;
select * from relatives;

select * from files_relatives;


select f.name,t.name,r.name from files f join files_tags ft on f.file_id=ft.file_id join tags t on t.tag_id = ft.tag_id left outer join files_relatives fr on f.file_id=fr.file_id left outer join relatives r on r.relative_id=fr.relative_id;


select files.fi,files.name,tags.name from files join files_tags on files.file_id=files_tags.file_id join tags on tags.tag_id = files_tags.tag_id ;
select  files.name,relatives.name from files left outer join files_relatives on files.file_id=files_relatives.file_id left outer join  relatives on relatives.relative_id=files_relatives.relative_id;



-----------------------------------CAUTARE

select * from files;
select * from users;




  select f.file_id,count(f.file_id) from files_tags ft, files f, tags t 
  where ft.tag_id=t.tag_id
  and (t.name in ('amazon','test','internship'))
  and f.file_id=ft.file_id and f.chest_id in (select chest_id from chests where user_id=2)
  group by f.file_id;
  
  
  select f.file_id,count(f.file_id) from files f join files_tags ft on f.file_id=ft.file_id join tags t on t.tag_id = ft.tag_id left outer join files_relatives fr on f.file_id=fr.file_id left outer join relatives r on r.relative_id=fr.relative_id
  where (((t.name in ('amazon','test','internship'))
  or r.name in ('mama')))
  and f.chest_id in (select chest_id from chests where user_id=2)
  group by f.file_id;
