<?php
namespace App;
class DatabaseInteraction
{
    private $user;
    private $pass;
    private $db;
    private $conn;

    public function __construct($user = "", $pass = "", $db = "")
    {
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
    }

    public function connect()
    {
        $this->conn = oci_connect($this->user, $this->pass, $this->db);
        if (!$this->conn) {
            $error_message = oci_error();
            echo $message_error['message'] . "\n";
            exit;
        }
    }

    public function getConnection()
    {
        return $conn;
    }

    public function isLocal($fileid)
    {
        $sql = "begin 
               :r:=DIGIX.CHECKFILEORIGIN(:fileid);
               end;";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ":r", $result, 32);
        oci_bind_by_name($stid, ":fileid", $fileid);
        $r = @oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            echo $e['message'];
        } else {
            oci_free_statement($stid);
            return $result == 1;
        }
    }

    public function verifyOwnership($uid, $cid)
    {
        $sql = "begin 
               :r:=DIGIX.CHECKCHESTOWNERSHIP(:utilizator,:cufar);
               end;";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ":r", $result, 32);
        oci_bind_by_name($stid, ":utilizator", $uid);
        oci_bind_by_name($stid, ":cufar", $cid);
        $r = @oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            echo $e['message'];
        } else {
            oci_free_statement($stid);
            return $result == 1;
        }
    }

    public function verifyFileOwnership($uid, $fid)
    {
        $sql = "begin 
               :r:=DIGIX.CHECKFILEOWNERSHIP(:utilizator,:fisier);
               end;";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ":r", $result, 32);
        oci_bind_by_name($stid, ":utilizator", $uid);
        oci_bind_by_name($stid, ":fisier", $fid);
        $r = @oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            echo $e['message'];
        } else {
            oci_free_statement($stid);
            return $result == 1;
        }
    }

    public function newChest($userid, $capacity, $freeS, $descr, $name)
    {
        $sql = "begin 
               :r:=DIGIX.NEWCHEST(:iduser,:capacity,:freeslots,:description,:name,:datac);
               end;";
        $date = date('d-M-y');
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ":r", $result, 32);
        oci_bind_by_name($stid, ":iduser", $userid);
        oci_bind_by_name($stid, ":capacity", $capacity);
        oci_bind_by_name($stid, ":freeslots", $freeS);
        oci_bind_by_name($stid, ":description", $descr);
        oci_bind_by_name($stid, ":name", $name);
        oci_bind_by_name($stid, ':datac', $date);
        oci_execute($stid);
        oci_commit($this->conn);
        oci_free_statement($stid);
        return $result;
    }

    public function deleteFile($fileid)
    {
        $sql = "begin 
              DIGIX.DELETEFILE(:fileid);
              end;";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':fileid', $fileid);
        oci_execute($stid);
        oci_commit($this->conn);
        oci_free_statement($stid);
    }

    public function deleteChest($chestid)
    {
        $sql = "begin 
              DIGIX.DELETECHEST(:chestid);
              end;";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':chestid', $chestid);
        oci_execute($stid);
        oci_commit($this->conn);
        oci_free_statement($stid);
    }

    public function addFile($cufar, $nume, $tip, $cale, $sursa)
    {
        $sql = "begin 
              :r:=DIGIX.ADDFILE(:chestid,:filename,:filetype,:filepath,:datac,:org);
              end;";
        $date = date('d-M-y');
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ":r", $result, 32);
        oci_bind_by_name($stid, ':chestid', $cufar);
        oci_bind_by_name($stid, ':filename', $nume);
        oci_bind_by_name($stid, ':filetype', $tip);
        oci_bind_by_name($stid, ':filepath', $cale);
        oci_bind_by_name($stid, ':datac', $date);
        oci_bind_by_name($stid, ':org', $sursa);
        oci_execute($stid);
        oci_commit($this->conn);
        oci_free_statement($stid);
        return $result;
    }

    public function addTagToFile($file, $tag)
    {
        $sql = "begin 
              DIGIX.ADDTAGTOFILE(:file,:tag);
              end;";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':file', $file);
        oci_bind_by_name($stid, ':tag', $tag);
        oci_execute($stid);
        oci_commit($this->conn);
        oci_free_statement($stid);
    }

    public function addRelativeToFile($file, $relative)
    {
        $sql = "begin 
              DIGIX.ADDRELATIVETOFILE(:file,:relative);
              end;";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':file', $file);
        oci_bind_by_name($stid, ':relative', $relative);
        oci_execute($stid);
        oci_commit($this->conn);
        oci_free_statement($stid);
    }

    public function getFilePath($fileid)
    {
        $sql = "begin 
              :r:=DIGIX.GETFILEPATH(:fileid);
              end;";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':r', $response, 255);
        oci_bind_by_name($stid, ':fileid', $fileid);
        oci_execute($stid);
        oci_commit($this->conn);
        oci_free_statement($stid);
        return $response;
    }

    public function getFilesForChest($cid)
    {
        $sql = 'select name,type,file_id,chest_id,path,createdat as age,origin from files where chest_id=:idc';
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':idc', $cid);
        oci_execute($stid);
        $files = null;
        while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $file = new File();
            $file->fileid = $row['FILE_ID'];
            $file->chestid = $row['CHEST_ID'];
            $file->type = $row['TYPE'];
            $file->name = $row['NAME'];
            $file->path = $row['PATH'];
            $file->createdat = $row['AGE'];
            $file->origin = $row['ORIGIN'];
            $file->tags = $this->getTagsForFile($file->fileid);
            $file->relative = $this->getRelativeForFile($file->fileid);
            $files[] = $file;
        }
        oci_free_statement($stid);
        return $files;
    }

    public function searchFilesByTags($chestid, $tags)
    {
        $sql = "select f.file_id as fisier,f.name as nume , f.type as tip, f.path as cale,count(f.file_id) as relevance,f.createdat as age,f.origin from files_tags ft ,files f,tags t 
                where ft.tag_id=t.tag_id
                and (t.name in " . $tags . " ) and f.file_id=ft.file_id 
                and f.chest_id=:chestid 
                group by f.file_id,f.name,f.type,f.path,f.createdat,f.origin order by relevance desc";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':chestid', $chestid);
        oci_execute($stid);
        $files = null;
        while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $file = new File();
            $file->fileid = $row['FISIER'];
            $file->chestid = $chestid;
            $file->type = $row['TIP'];
            $file->name = $row['NUME'];
            $file->path = $row['CALE'];
            $file->createdat = $row['AGE'];
            $file->origin = $row['ORIGIN'];
            $file->tags = $this->getTagsForFile($file->fileid);
            $file->relative = $this->getRelativeForFile($file->fileid);
            $files[] = $file;
        }
        oci_free_statement($stid);
        return $files;
    }

    public function globalSearchFilesByTags($userid, $tags)
    {
        $sql = "select f.file_id as fisier,f.name as nume , f.type as tip, f.path as cale,f.chest_id as cufar ,count(f.file_id) as relevance,f.createdat as age,f.origin from files_tags ft ,files f,tags t 
                where ft.tag_id=t.tag_id
                and (t.name in " . $tags . " ) and f.file_id=ft.file_id 
                and f.chest_id in ( select chest_id from chests where user_id=:user_id)
                group by f.file_id,f.name,f.type,f.path,f.chest_id,f.createdat,f.origin order by relevance desc";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ":user_id", $userid);
        oci_execute($stid);
        $files = null;
        while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $file = new File();
            $file->fileid = $row['FISIER'];
            $file->chestid = $row['CUFAR'];
            $file->type = $row['TIP'];
            $file->name = $row['NUME'];
            $file->path = $row['CALE'];
            $file->createdat = $row['AGE'];
            $file->origin = $row['ORIGIN'];
            $file->tags = $this->getTagsForFile($file->fileid);
            $file->relative = $this->getRelativeForFile($file->fileid);
            $files[] = $file;
        }
        oci_free_statement($stid);
        return $files;
    }

    public function globalSearchFilesByTagsAndRelative($userid, $tags, $relative)
    {
        $sql = "select f.file_id as fisier,f.name as nume , f.type as tip, f.path as cale,f.chest_id as cufar, count (f.file_id) as relevance from files f 
              join files_tags ft on f.file_id=ft.file_id 
              join tags t on t.tag_id = ft.tag_id 
              left outer join files_relatives fr on f.file_id=fr.file_id 
              left outer join relatives r on r.relative_id=fr.relative_id
              where (((t.name in " . $tags . " ) 
              or r.name in " . $relative . " ))
              and f.chest_id in (select chest_id from chests where user_id=:userid)
              group by f.file_id,f.name,f.type,f.path,f.chest_id order by relevance desc";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':userid', $userid);
        oci_execute($stid);
        $files = null;
        while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $file = new File();
            $file->fileid = $row['FISIER'];
            $file->chestid = $row['CUFAR'];
            $file->type = $row['TIP'];
            $file->name = $row['NUME'];
            $file->path = $row['CALE'];
            $files[] = $file;
        }
        oci_free_statement($stid);
        return $files;
    }

    public function searchFilesByTagsAndRelative($chestid, $tags, $relative)
    {
        $sql = "select f.file_id as fisier,f.name as nume , f.type as tip, f.path as cale, count (f.file_id) as relevance from files f 
              join files_tags ft on f.file_id=ft.file_id 
              join tags t on t.tag_id = ft.tag_id 
              left outer join files_relatives fr on f.file_id=fr.file_id 
              left outer join relatives r on r.relative_id=fr.relative_id
              where (((t.name in " . $tags . " ) 
              or r.name in " . $relative . " ))
              and f.chest_id=:chestid 
              group by f.file_id,f.name,f.type,f.path order by relevance desc";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':chestid', $chestid);
        oci_execute($stid);
        $files = null;
        while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $file = new File();
            $file->fileid = $row['FISIER'];
            $file->chestid = $chestid;
            $file->type = $row['TIP'];
            $file->name = $row['NUME'];
            $file->path = $row['CALE'];
            $files[] = $file;
        }
        oci_free_statement($stid);
        return $files;
    }

    public function getRelativeForFile($fileid)
    {
        $sql = "select r.name as relative from files f join files_relatives fr on f.file_id=fr.file_id join relatives r on r.relative_id=fr.RELATIVE_ID where fr.file_id=:fileid";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':fileid', $fileid);
        oci_execute($stid);
        $relative = null;
        while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $relative = $row['RELATIVE'];

        }
        oci_free_statement($stid);
        if ($relative == null)
            $relative = "none";
        return $relative;

    }

    public function getTagsForFile($fileid)
    {
        $sql = "select t.name as tag from files f join files_tags ft on f.file_id=ft.file_id join tags t on t.tag_id= ft.tag_id where ft.file_id=:fileid";
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':fileid', $fileid);
        oci_execute($stid);
        $tags = null;
        while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $tag = $row['TAG'];
            $tags[] = $tag;
        }
        oci_free_statement($stid);
        return $tags;

    }

    public function getChestsForUser($userid)
    {
        $sql = 'SELECT  chests.name,chest_id,user_id,capacity,freeslots,description,createdat as age from users join chests on user_id=id and user_id=:idu';
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':idu', $userid);
        oci_execute($stid);
        $cufere = null;
        while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $cufar = new Chest();
            $cufar->id_cufar = $row['CHEST_ID'];
            $cufar->id_user = $row['USER_ID'];
            $cufar->capacity = $row['CAPACITY'];
            $cufar->freeSlots = $row['FREESLOTS'];
            $cufar->description = $row['DESCRIPTION'];
            $cufar->createdat = $row['AGE'];
            $cufar->name = $row['NAME'];
            $cufere[] = $cufar;
        }
        oci_free_statement($stid);
        return $cufere;
    }

    public function getChestData($chestid)
    {
        $sql = 'select name,chest_id,user_id,capacity,freeslots,description from chests where chest_id=:chestid';
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':chestid', $chestid);
        oci_execute($stid);
        while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            $cufar = new Chest();
            $cufar->id_cufar = $row['CHEST_ID'];
            $cufar->id_user = $row['USER_ID'];
            $cufar->capacity = $row['CAPACITY'];
            $cufar->freeSlots = $row['FREESLOTS'];
            $cufar->description = $row['DESCRIPTION'];
            $cufar->name = $row['NAME'];
        }
        oci_free_statement($stid);
        return $cufar;
    }

    public function updateChest($chestid, $name, $description)
    {
        $sql = 'update chests set name=:chestname ,description=:descr where chest_id=:chestid';
        $stid = oci_parse($this->conn, $sql);
        oci_bind_by_name($stid, ':descr', $description);
        oci_bind_by_name($stid, ':chestname', $name);
        oci_bind_by_name($stid, ':chestid', $chestid);
        oci_execute($stid);
        oci_commit($this->conn);
        oci_free_statement($stid);
    }

    public function __destruct()
    {
        # close the connection
        oci_close($this->conn);
    }
}