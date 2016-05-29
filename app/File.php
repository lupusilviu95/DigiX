<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public $fileid, $chestid, $type, $name, $path, $createdat, $origin, $tags, $relative;

    public function getFormatedTags()
    {
        $result = null;
        foreach ($this->tags as $tag) {
            $result = $result . $tag . ",";
        }
        $result = rtrim($result, ",");
        return $result;
    }

    public function getFormatedRelative()
    {
        return $this->relative;
    }
}
