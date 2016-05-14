

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

alter table chests add createdat date;
select * from chests;
update chests set createdat=sysdate;
alter table chests drop constraint chests_users;
alter table chests add constraint chests_users foreign key (user_id) references users(id) ;
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

alter table files add createdat date;
update files set createdat=sysdate;
select * from files;
commit;
/
alter table files drop constraint files_chests;
alter table files add constraint files_chests foreign key (chest_id) references chests(chest_id) ;


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
  function checkFileOwnership(id_user users.id%type,id_file files.file_id%type) return integer;
  function checkChestOwnership(id_user users.id%type,id_chest chests.chest_id%type) return integer;
  function newChest(id_user users.id%type,cap chests.capacity%type,frees chests.freeSlots%type,descr chests.description%type,nume chests.name%type,datac date) return integer;
  function addFile(id_chest files.chest_id%type,nume files.name%type, tip files.type%type,cale files.path%type,datac date) return integer;
  procedure addTagToFile(id_file files.file_id%type,tag varchar2);
  procedure addRelativeToFile(id_file files.file_id%type , rudenie varchar2);
  function getFilePath(id_fisier files.file_id%type ) return varchar2;
end DigiX;
  
select * from files where file_id=119897;
  
  
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
     for c_i in (select file_id from files where chest_id=id_chest) loop
      deleteFile(c_i.file_id);
     end loop;
    
     delete from chests where chest_id=id_chest;
     EXCEPTION
     when DigixExceptions.inexistent_chest then
     RAISE_APPLICATION_ERROR(-20011,'Cufarul CU ID-UL SPECIFICAT NU EXISTA');
  end;
  function checkFileOwnership(id_user users.id%type , id_file files.file_id%type) return integer is 
  begin
    select 1 into counter from users join chests on users.id=chests.user_id join files on files.chest_id=chests.chest_id where files.file_id=id_file and chests.user_id=id_user;
    if(counter =1) then 
      return 1;
    end if;
    EXCEPTION
    when no_data_found then
    return 0;
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
  
  function newChest(id_user users.id%type,cap chests.capacity%type,frees chests.freeSlots%type,descr chests.description%type,nume chests.name%type,datac date) return integer is 
  begin
    select count(*) into nrRecords from chests;
    if(nrRecords=0) then
    maxId:=1;
    else
    select max(chest_id) into maxID from chests;
    maxID:=maxID+1;
    end if;
    insert into chests values(maxID,id_user,cap,frees,descr,nume,datac);
    return maxID;
  end;
  
  function addFile(id_chest files.chest_id%type,nume files.name%type, tip files.type%type,cale files.path%type,datac date) return integer is 
  begin
  select count(*) into nrRecords from files;
    if(nrRecords=0) then
    maxId:=1;
    else
   select max(file_id) into maxID from files;
   maxID:=maxID+1;
   end if;
   insert into files values(maxID,id_chest,nume,tip,cale,datac);
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
-------------------TRiggers

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




-----------------------------------CAUTARE

select * from files;
select * from users;
select * from tags;




  select f.file_id,count(f.file_id) as "relevance" from files_tags ft, files f, tags t 
  where ft.tag_id=t.tag_id
  and (t.name in ('amazon','test','internship'))
  and f.file_id=ft.file_id and f.chest_id in (select chest_id from chests where user_id=2)
  group by f.file_id;
  
  
  select f.chest_id,f.file_id,count(f.file_id) from files f join files_tags ft on f.file_id=ft.file_id join tags t on t.tag_id = ft.tag_id left outer join files_relatives fr on f.file_id=fr.file_id left outer join relatives r on r.relative_id=fr.relative_id
  where (((t.name in ('amazon','test','internship'))
  or r.name in ('mama')))
  and f.chest_id in (select chest_id from chests where user_id=2)
  group by f.file_id,f.chest_id;
  
  
  SELECT * FROM USER_CONSTRAINTS WHERE TABLE_NAME = 'USERS';
  
  select f.file_id ,count(f.file_id) as relevance from files_tags ft ,files f,tags t where ft.tag_id=t.tag_id and (t.name in ('amazon') ) and f.file_id=ft.file_id and f.chest_id=1 group by f.file_id;
  
  
  select * from users where id =1;
  
  
  select chests.chest_id,chests.user_id,files.file_id from users join chests on users.id=chests.user_id join files on files.chest_id=chests.chest_id;
  
  
  select count(*) from users join chests on users.id=chests.user_id join files on files.chest_id=chests.chest_id where files.file_id=:fileid and chests.user_id=:userid;
  
  
  
  
  
  
  
  
  select f.file_id as fisier, count (f.file_id) as relevance from files f join files_tags ft on f.file_id=ft.file_id join tags t on t.tag_id = ft.tag_id left outer join files_relatives fr on f.file_id=fr.file_id left outer join relatives r on r.relative_id=fr.relative_id where (((t.name in ('amazon','internship','test') ) or r.name in ('mama') )) and f.chest_id=:chestid group by f.file_id




SELECT  chests.name,chest_id,user_id,capacity,freeslots,description from users join chests on user_id=id and user_id=2;

-------------Indexare



---Interogari frecvente:
--Fisierele dintr-un cufar - poate folosi index
  select name,type,file_id,chest_id,path from files where chest_id=200;
--Cuferele unui user -poate folosi index
  SELECT  chests.name,chest_id,user_id,capacity,freeslots,description from users join chests on user_id=id and user_id=2;
--Info despre un cufar --foloeseste unique scan
  select name,chest_id,user_id,capacity,freeslots,description from chests where chest_id=2;
  
--Updatarea unui cufar  --foloseste unique scan
  update chests set name='cufar indexat' ,description='cva' where chest_id=2;   


----Indecsi ( plus cei pe PK)
select count(*) from files;
select count (*) from chests;

create or replace procedure populateChests is
v_user number;
v_capacity number;
v_nume varchar2(30);
v_descr varchar2(40);
v_rez integer;
begin
  for v_i in 1..10000 loop
    v_user:=dbms_random.value(1,4);
    v_capacity:=40;
    v_nume:='cufar'||v_i;
    v_descr:='descriere'||v_i;
    v_rez:=DIGIX.NEWCHEST(v_user,v_capacity,v_capacity,v_descr,v_nume);
    end loop;
    
end;
/
create or replace procedure populateChestNumber(p_chestnr integer,p_nr_of_files integer ) is 
v_name varchar2(20):='dummy.pdf';
v_type varchar2(20):='pdf';
v_rez integer;
v_path varchar2(100):='userdata/dummy/dummy.pdf';
begin
  for v_i in 1..p_nr_of_files loop
    v_rez:=digix.addfile(p_chestnr,v_name,v_type,v_path);
  end loop;
end;
/

begin
 populateChests();
end;

begin 
 for v_i in 5..4000 loop
 populateChestNumber(v_i,30);
 end loop;
end;



create  index files_for_chest on files(chest_id);
create index chests_for_users on chests(user_id);




-----------------------CSVExport;


create or replace procedure  exportCSV is
 
type cursor_type is ref cursor;
c cursor_type;
d cursor_type;
TYPE columns_names IS TABLE OF VARCHAR2(50);
v_columns columns_names;
stmt varchar2(10000);
rezultat varchar2(1000);
insertStmt varchar2(10000):='insert into ';
username varchar2(200);
obiect varchar2(200);
tip varchar2(200);
comanda varchar2(10000);
select_stmt varchar2(1000);
file_id UTL_FILE.FILE_TYPE;
begin
  select user into username from dual;
  for c_j in (select object_name,object_type FROM   USER_OBJECTS where object_type in ('TABLE') and object_name  in('TAGS','CHESTS','FILES','RELATIVES','FILES_TAGS','FILES_RELATIVES') ) loop
       file_id := UTL_FILE.FOPEN ('DIRECTOR', c_j.object_name||'.csv', 'W');
      SELECT column_name
      BULK COLLECT INTO v_columns
      FROM all_tab_columns 
      WHERE table_name =upper(c_j.object_name);
      stmt:='select ';
      for v_i in v_columns.first..v_columns.last loop
          stmt:=stmt||''''''||'||'|| v_columns(v_i)||'||'||'''''' ||'||'',''||';
      end loop;
      stmt:=substr(stmt,1,length(stmt)-7);
      stmt:=stmt||' from '||c_j.object_name;
      open d for stmt;
      insertStmt:='';
      
      loop
          fetch d into rezultat;
          exit when d%NOTFOUND;
          insertStmt:=insertStmt || rezultat;
          UTL_FILE.PUT(file_id,insertStmt);
          UTL_FILE.NEW_LINE(file_id,1);
          insertStmt:='';
       end loop;
       UTL_FILE.FCLOSE(file_id);
  end loop;
end;
/


BEGIN
EXPORTCSV();
END;

----CSVImport


create or replace procedure importCSVChests is 
v_rez integer;
type cursor_type is ref cursor;
c_i cursor_type;
v_user chests.user_id%type;
v_cap chests.capacity%type;
v_fs chests.freeSlots%type;
v_desc chests.description%type;
v_name chests.name%type;
begin 
  
  execute immediate 'create  table  xtern_chests(
                     user_id number(10,0),
                     capacity number(10,0),
                     freeSlots number(10,0),
                     description varchar2(2000),
                     name varchar2(200)
                      )
                     organization external 
                      (
                        default directory director
                        access parameters 
                        (records delimited by newline fields terminated by '','')
                        location (''CHEST_IMPORT.csv'')
                      )';
  open c_i for 'select * from xtern_chests';
  loop
    fetch c_i into v_user,v_cap,v_fs,v_desc,v_name;
    exit when c_i%NOTFOUND;
    v_rez:=digix.newchest(v_user,v_cap,v_fs,v_desc,v_name);
  end loop;
  execute immediate 'drop table xtern_chests';

end;

select * from chests where user_id=61;

begin 
importCSVChests();
end;