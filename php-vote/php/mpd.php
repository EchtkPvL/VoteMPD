<?php

/*
thx to http://sourceforge.net/projects/phpmp3/files/phpmp3/0.2.1/phpMp3-0.2.1.tar.bz2/download

status
seek _ INT
setvol INT
lsinfo PATH
playid ID
add _
update _
playlistinfo
shuffle
next
stop
pause
play
previous

*/

class MPD {
    var $link;

    function open() {
        global $MPD_HOST,$MPD_PORT;
        $this->link = fsockopen($GLOBALS["mpdip"],$GLOBALS["mpdport"],$errno,$errstr,2);
        stream_set_timeout($this->link,2);
        if (!$this->link) {
            die("cannot connect to mpd-server at $MPD_HOST:$MPD_PORT");
        }
        fgets($this->link);
    }

    function close() {
        fwrite($this->link,"close\n");
        fclose($this->link);
    }
    
    function cmd($cmd) {
        $this->open();
        $q = "";
        if (is_array($cmd)) {
            $q = "command_list_begin\n". implode("\n",$cmd) . "\ncommand_list_end\n";
        } else {
            $q = "$cmd\n";
        }
        fwrite($this->link,$q);
        
        $result = "";
        while (!feof($this->link)) {
            $buf = fgets($this->link,8192);
            
            if (substr($buf,0,2) == "OK") {
                 break;
            } else {
                $result .= $buf;
            }
         }

        $this->close();
        return $result;
    }

    function status() {
        $this->open();
        fwrite($this->link,"status\n");
        $status = "";
        while (!feof($this->link)) {
            $status .= fread($this->link,8192);
            
        }
        $this->close();
        return $status;
    }    
}
?>