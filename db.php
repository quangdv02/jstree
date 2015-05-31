<?php
global $conn;

function logDebug($mess){
    error_log(gmdate('y-m-d H:i:s', time()+3600*7)." $mess".PHP_EOL,3,"mylog.log");
}

function logObj($obj){
    $fp = fopen('myfile.log', 'w');
    fwrite($fp, print_r($obj, TRUE));
    fclose($fp);
}

function connectDb(){
    global $conn;
    $conn = mysql_connect('localhost','root','') or die('connectDB fail');
    mysql_select_db('js_tree',$conn) or die('select db fail');
    mysql_set_charset('utf8',$conn);
}

function close(){
    global $conn;
    mysql_close($conn);
}

function exec_select($sql){
    global $conn;
    logDebug("sql=[$sql]");
    connectDb();
    $result = mysql_query($sql,$conn);
    $error = mysql_error();
    if($error){
        echo "cant select";
        logDebug("error=[$error]");
    }
    $data = array();
    while($row = mysql_fetch_assoc($result)){
        $data[] = $row;
    }
    close();
    return $data;
}

function exec_update($sql){
    global $conn;
    logDebug("sql=[$sql]");
    connectDb();
    $result = mysql_query($sql,$conn);
    $error = mysql_error();
    if($error){
        echo "cant select";
        logDebug("error=[$error]");
    }
    $lastid = mysql_insert_id();
    close();
    return $lastid;
}

function fetchAll($sql){
    $data = exec_select($sql);
    return $data;
}

function fetchOne($sql){
    $data = exec_select($sql);
    return $data[0];
}

function validateForm($arr = array()){
    if($arr){
        $data = array();
        foreach ($arr as $k => $v) {
            if($v == null){
                $data['error'][$k] = "Mời nhập ".$k;
            } else {
                $data[$k] = $v;
            }
        }
        return $data;
    }
}

function create_list($list, $parent_id = 0){
    $html = '<ul>';
    foreach($list as $key => $row){
        if($row['pid'] == $parent_id){
            $html .= "<li id='".$row['id']."'>".$row['name'] . create_list($list, $row['id']) .'</li>';
        }
    }
    $html .= '</ul>';
    return $html;
}

function delete_node($id){
    delCatByid($id);
    $data = getCatByPid($id);
    $count  = count($data);
    if($count){
        foreach ($data as $val) {
            delete_node($val['id']);
        }
    }
}

function getCatByPid($id){
    $sql="select * from categories where pid =$id";
    return fetchAll($sql);
}

function delCatByid($id){
    $sql="delete from categories where id=$id";
    exec_update($sql);
}
