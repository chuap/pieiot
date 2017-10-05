var rootContext = document.body.getAttribute("data-root");
if (!rootContext)
    rootContext = '';

function editportname(pie, pno, pid) {
    var pname = $('.portname_' + pid).html();
    bootbox.prompt({
        size: "small",
        title: "port " + pno + ' : ' + pname,
        value: pname,
        callback: function (result) {
            if (result) {
                $.ajax({
                    url: rootContext + 'adminaction',
                    type: "POST",
                    datatype: "json",
                    data: "ac=editportname&pie=" + pie + '&pno=' + pno + '&pid=' + pid + '&pname=' + result
                }).success(function (rt) {

                    var obj = jQuery.parseJSON(rt);
                    //alert(result); 
                    if (obj.STATUS == true) {
                        $('.portname_' + pid).html(result);
                    } else {
                        alertbox(obj.MSG + '');
                    }
                });
            }
        }
    })
}
function enabletask(tid, pie) {
    $('#tr_'+tid).removeClass('danger');
    $('.mnenable_'+tid).addClass('hidden');
    $('.mndelete_'+tid).addClass('hidden');
    $('.sync_'+tid).html('<img class="w20" src="' + rootContext + 'images/loading2.gif" /> <small>Enabling</small>');
    $.ajax({
        url: rootContext + 'adminaction',
        type: "POST",
        datatype: "json",
        data: "ac=enabletask&tid=" + tid + '&pie=' + pie
    }).success(function (result) {
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {
           $('.mndisable_'+tid).removeClass('hidden'); 
           
        } else {
            
        }
    });
}
function disabletask(tid, pie) {
    $('#tr_'+tid).addClass('danger');
    $('.mndisable_'+tid).addClass('hidden');
    $('.sync_'+tid).html('<img class="w20" src="' + rootContext + 'images/loading2.gif" /> <small>Disabling</small>');
    $.ajax({
        url: rootContext + 'adminaction',
        type: "POST",
        datatype: "json",
        data: "ac=disabletask&tid=" + tid + '&pie=' + pie
    }).success(function (result) {
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {
            $('.mnenable_'+tid).removeClass('hidden');
        } else {
            
        }
    });
}
function deletetask(tid, pie) {
    bootbox.confirm({
        title: "ยืนยันลบข้อมูล?",
        message: "Task : " + $('#lb_' + tid).html(),
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm'
            }
        },
        callback: function (result) {
            if (result == true) {
                $('.mndelete_'+tid).addClass('hidden');
                $.ajax({
                    url: rootContext + 'adminaction',
                    type: "POST",
                    datatype: "json",
                    data: "ac=deletetask&tid=" + tid + '&pie=' + pie
                }).success(function (result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.STATUS == true) {
                        delete_tag('tr_' + tid)
                    } else {
                        alertbox(obj.MSG + '');
                    }
                });
            }
        }
    });
}
function deletepie(pie) {
    bootbox.confirm({
        title: "ยืนยันลบข้อมูล?",
        message: "<h4>Project : " + $('#lbpie_' + pie).html() + '</h4>',
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm'
            }
        },
        callback: function (result) {
            if (result == true) {
                $.ajax({
                    url: rootContext + 'adminaction',
                    type: "POST",
                    datatype: "json",
                    data: "ac=deletepie&pie=" + pie 
                }).success(function (result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.STATUS == true) {
                        delete_tag('lbpie_' + pie)
                        window.location.href = rootContext + "pies";
                    } else {
                        alertbox(obj.MSG + '');
                    }
                });
            }
        }
    });
}
function deleteproject(pie, pro) {
    bootbox.confirm({
        title: "ยืนยันลบข้อมูล?",
        message: "<h4>Project : " + $('#lbpro_' + pro).html() + '</h4>',
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm'
            }
        },
        callback: function (result) {
            if (result == true) {
                $.ajax({
                    url: rootContext + 'adminaction',
                    type: "POST",
                    datatype: "json",
                    data: "ac=deleteproject&pie=" + pie + '&pro=' + pro
                }).success(function (result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.STATUS == true) {
                        delete_tag('lbpro_' + pro)
                        window.location.href = rootContext + "projects";
                    } else {
                        alertbox(obj.MSG + '');
                    }
                });
            }
        }
    });
}
function delete_tag(tr) {
    $('#' + tr).css("background-color", "#FF3700");
    $('#' + tr).fadeOut(400, function () {
        $('#' + tr).remove();
    });
}

