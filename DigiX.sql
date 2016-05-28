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
  function checkFileOrigin(id_fisier files.file_id%type) return integer;
  function checkChestOwnership(id_user users.id%type,id_chest chests.chest_id%type) return integer;
  function newChest(id_user users.id%type,cap chests.capacity%type,frees chests.freeSlots%type,descr chests.description%type,nume chests.name%type,datac date) return integer;
  function addFile(id_chest files.chest_id%type,nume files.name%type, tip files.type%type,cale files.path%type,datac date,origine files.origin%type) return integer;
  procedure addTagToFile(id_file files.file_id%type,tag varchar2);
  procedure addRelativeToFile(id_file files.file_id%type , rudenie varchar2);
  function getFilePath(id_fisier files.file_id%type ) return varchar2;
end DigiX;
/

  
  
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
  cale varchar2(255);
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
  function checkFileOrigin(id_fisier files.file_id%type) return integer is
  begin 
    select 1 into counter from files where file_id=id_fisier and origin='local';
    if(counter=1) then 
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
  
  function addFile(id_chest files.chest_id%type,nume files.name%type, tip files.type%type,cale files.path%type,datac date,origine files.origin%type) return integer is 
  begin
  select count(*) into nrRecords from files;
    if(nrRecords=0) then
    maxId:=1;
    else
   select max(file_id) into maxID from files;
   maxID:=maxID+1;
   end if;
   insert into files values(maxID,id_chest,nume,tip,cale,datac,origine);
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


