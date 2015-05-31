$(document).ready(function () {
    $('#html1').jstree({
        "core": {
            "check_callback": true
        },
        "plugins": ["dnd",'contextmenu']
    })
    .on('delete_node.jstree', function (e, data) {
        $.get('process.php?operation=delete_node', {'id': data.node.id})
            .fail(function () {
                data.instance.refresh();
            });
    })
    .on('create_node.jstree', function (e, data) {
        $.get('process.php?operation=create_node', {'id': data.node.parent, 'text': data.node.text, 'position': data.position})
            .done(function (d) {
                data.instance.set_id(data.node, d.id);
            })
            .fail(function () {
                data.instance.refresh();
            });
    })
    .on('rename_node.jstree', function (e, data) {
        $.get('process.php?operation=rename_node', {'id': data.node.id, 'text': data.text})
            .fail(function () {
                data.instance.refresh();
            });
    })
    .on('move_node.jstree', function (e, data) {
            console.log(data);
        $.get('process.php?operation=move_node', {'id': data.node.id, 'parent': data.parent, 'position': data.position})
            .fail(function () {
                data.instance.refresh();
            });
    })
    .on('changed.jstree', function (e, data) {
            console.log(data);
        if (data && data.selected && data.selected.length) {
            $.get('process.php?operation=get_content&id=' + data.node.id,function(res){
                console.log(res);
            });
        }
    });
});
