<?php

/* Configuration */
define("UPLOAD_DIR", "uploads" . DIRECTORY_SEPARATOR);

//Delete inactive files
$cleanup_dir = true;
$max_file_age = 5* 3600; // Temp file age in seconds
@set_time_limit(5 * 60);

// HTTP headers for no cache etc
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/*
 * write_meta_information_to_file
 *  @meta_file: file to write to
 *  @md5sum: checksum of all uploaded chunks
 *  @chunk: chunk number
 *  @chunks: total chunks to upload
 */
function write_meta_information_to_file($meta_file, $md5sum, $chunk, $chunks){
    if($chunk < ($chunks - 1)){
        $upload_meta_data = "status=uploading&chunk=$chunk&chunks=$chunks&md5=$md5sum";

        //write meta details to file
        $fh = fopen($meta_file, 'w') or die("Cannot open file");
        fwrite($fh, $upload_meta_data);
        fclose($fh);
    } else {
        //last chunk, delete meta file
        unlink($meta_file);
    }
}

function clean_filename($filename){
    $i = strrpos($filename, ".");
    if($i){
        $filename = substr($filename, 0, $i) . strtolower(substr($filename, $i));
    }
    $filename = preg_replace('/[^\w\._]+/', '', $filename);
    return $filename;
}


function get_or_create_file($chunk, $dst){
    if($chunk == 0){
        $fh = fopen($dst, 'wb');
    } else {
        $fh = fopen($dst, 'ab');
    }
    return $fh;
}

/*
 * upload_with_checksum
 *  @dst: the destination filepath
 *  @chunk: the chunk number
 *  @chunks: the total number of chunks
 *  @md5chunk: md5sum of chunk
 *  @md5total: md5sum of all currently sent chunks
 */
function upload_with_checksum($dst, $md5chunk, $md5total, $chunk, $chunks){

    $f = get_or_create_file($chunk, $dst);
    $in = fopen("php://input", "rb");

    while($buf = stream_get_contents($in)){
        $md5sum = md5($buf);

        if($md5sum != $md5chunk){
            //Throw a 400 BAD REQUEST error
            throw new Exception("Checksum error", 400);
        }
        //write to file
        fwrite($f, $buf);
    }

    fclose($f);

    $f_meta = $dst . '.meta';
    write_meta_information_to_file($f_meta, $md5total, $chunk, $chunks);
}

function upload_simple($dst, $chunk=0){
    $f = get_or_create_file($chunk, $dst);

    $in = fopen("php://input", "rb");
    while($buff = stream_get_contents($in)){
        fwrite($f, $buff);
    }

    fclose($f);
}

function upload(){

    if($_SERVER['REQUEST_METHOD'] != "POST"){
        return probe();
    }

    $filename = clean_filename($_REQUEST['name']);
    $dst = UPLOAD_DIR.$filename;

    $md5chunk = (isset($_REQUEST['md5chunk'])) ? $_REQUEST['md5chunk'] : false;
    $md5total = (isset($_REQUEST['md5total'])) ? $_REQUEST['md5total'] : false;

    $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
    $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;

    if($md5chunk && $md5total){
        upload_with_checksum($dst, $md5chunk, $md5total, $chunk, $chunks);
    } else {
        upload_simple($dst, $chunk);
    }

    return "uploaded";

}

function delete_temp_files($max_file_age){
    if(is_dir(UPLOAD_DIR) && ($dir = opendir(UPLOAD_DIR))){
        while (($file = readdir($dir)) !== false) {
            $tmp_file_path = UPLOAD_DIR . DIRECTORY_SEPARATOR . $file;

            //remove tmp file if it is older then the max age
            if(preg_match('/\.meta$/', $file) && (filemtime($tmp_file_path) < time() - $max_file_age)){
                @unlink($tmp_file_path);
                @unlink(str_replace(".meta", "", $tmp_file_path));
            }
        }
    }
}

function probe(){
    $filename = clean_filename($_REQUEST['name']);

    $dst = UPLOAD_DIR . $filename;

    if(file_exists($dst)){
        $f_meta_dst = $dst . ".meta";
        if(file_exists($f_meta_dst)){
            $f_meta = fopen($f_meta_dst, 'r');
            $data = fread($f_meta, filesize($f_meta_dst));
            fclose($f_meta);
            return $data;
        } else {
            //meta file deleted
            return "status=finished";
        }
    } else {
        return "status=unknown";
    }
}

if($cleanup_dir){
    delete_temp_files($max_file_age);
}

echo upload();
?>
