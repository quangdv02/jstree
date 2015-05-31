<?php
include('db.php');
if (isset($_GET['operation'])) {
    switch ($_GET['operation']) {
        case "get_content":
            $node = isset($_GET['id']) && $_GET['id'] !== '#' ? $_GET['id'] : 0;
            break;
        case 'create_node':
            $node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
            $name = isset($_GET['text']) ? $_GET['text'] : "new node";
            $pos = isset($_GET['position']) && $_GET['position'] !== '#' ? (int)$_GET['position'] : 0;
            $sql = "insert into categories(name,pid,pos) values('" . $name . "','" . $node . "','" . $pos . "')";
            $data = exec_update($sql);
            $rslt = array('id' => $data);
            break;
        case 'rename_node':
            $node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
            $name = isset($_GET['text']) ? $_GET['text'] : "new node";
            $sql = "update categories set name= '" . $name . "' where id = $node";
            $data = exec_update($sql);
            $rslt = array('id' => $data);
            break;
        case 'delete_node':
            $node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
            delete_node($node);
            $rslt = array('id' => $node);
            break;
        case 'move_node':
            $node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
            $parn = isset($_GET['parent']) && $_GET['parent'] !== '#' ? (int)$_GET['parent'] : 0;
            $pos = isset($_GET['position']) && $_GET['position'] !== '#' ? (int)$_GET['position'] : 0;
            $sql1 = "select * from categories where id = $node";
            $data1 = fetchOne($sql1);
            $oldpos = $data1['pos'];
            if($parn == $data1['pid']){
               $sql2 = "update categories set pos= pos + 1 where pid = $parn and id != $node and (pos >= $pos and pos < $oldpos)";
            } else {
                $sql2 = "update categories set pos= pos + 1 where pid = $parn and id != $node and pos >= $pos";
            }
            $data2 = exec_update($sql2);
            $sql = "update categories set pid= '" . $parn . "', pos= '" . $pos . "'  where id = $node";
            $data = exec_update($sql);
            $rslt = array('id' => $data);
            break;
        default:
            break;
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($rslt);
}