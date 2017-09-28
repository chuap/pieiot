
var rootContext = document.body.getAttribute("data-root");
if (!rootContext)rootContext = '';
CKEDITOR.replace('content', {customConfig: rootContext + 'ckeditor/customconfig_topic.js'});
CKEDITOR.replace('dtl', {customConfig: rootContext + 'ckeditor/customconfig_topic.js'});
var resizeTimer;

function InsertText(f, b, n) {
    //alert('dd');
    var editor = CKEDITOR.instances.content;
    if (editor.mode == 'wysiwyg')
    {
        var x = n.substring(n.length - 4);
        //alert(x);
        if (b) {
            var t = '<img class="img-responsive" src="' + f + '">';
        }
        else if (' .wav'.indexOf(x) > 0) {

            var t = '<audio type="audio/x-wav" src="' + f + '" controls></audio>';
        }
        else if (' .wma'.indexOf(x) > 0) {

            var t = '<audio type="audio/wma" src="' + f + '" controls></audio>';
        }
        else if (' .mp3'.indexOf(x) > 0) {
            var t = '<audio type="audio/mp3" src="' + f + '" controls></audio>';
        }
        else {
            var t = '<a href="' + f + '" >' + n + '</a>';
        }
        editor.insertHtml(t);
    }
    else
        alert('You must be in WYSIWYG mode!');
}


$(function () {

    $("#txfind").autocomplete({
        minLength: 1,
        source: "../hrjson",
        focus: function (event, ui) {
            $("#project").val(ui.item.label);
            return false;
        },
        select: function (event, ui) {
            $("#data").html(ui.item.label);
            addlist2(ui.item.id, ui.item.label);
            $("#txfind").val('');
            return false;
        }
    })
            .autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "<br><small class='green'> " + item.info + "</small></a>")
                .appendTo(ul);
    };
    $("#txfind2").autocomplete({
        minLength: 1,
        source: "../hrjson",
        focus: function (event, ui) {
            $("#project").val(ui.item.label);
            return false;
        },
        select: function (event, ui) {
            $("#data").html(ui.item.label);
            addassign(ui.item.id, ui.item.label);
            $("#txfind2").val('');
            return false;
        }
    })
            .autocomplete("instance")._renderItem = function (ul, item) {
        return $("<li>")
                .append("<a>" + item.value + "<br><small class='green'> " + item.info + "</small></a>")
                .appendTo(ul);
    };

});
function tx_message_key(e) {
    if (e.keyCode == 13) {
        addmsg();
        return false;
    }
}
function tx_message_tl(e, id) {
    if (e.keyCode == 13) {
        addmsg_tl(id);
        return false;
    }
}
function apcept_click(hid) {
    $(".apceptbtn").addClass('hidden');
    $(".loading2").removeClass('hidden');
    $.ajax({
        url: rootContext + 'edoc/action',
        type: "GET",
        datatype: "json",
        data: "at=apcept" + "&id=" + hid
    }).success(function (result) {
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {
            $("#showapcept").removeClass('hidden');
            $(".loading2").addClass('hidden');
            $('#apcept_val').html(obj.DATA);
        } else {
            $.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
            $(".apceptbtn").removeClass('hidden');
            $(".loading2").addClass('hidden');
            //$('#divapcept').html(maxi);
        }
    });
}
function hidetoppic2(hid) {
    $(".apceptbtn").addClass('hidden');
    $(".loading2").removeClass('hidden');
    $.ajax({
        url: rootContext + 'edoc/action',
        type: "GET",
        datatype: "json",
        data: "at=hidetoppic" + "&id=" + hid
    }).success(function (result) {
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {
            $(".showapcept").removeClass('hidden');
            $(".loading2").addClass('hidden');
            $('#tid_' + hid).remove();
        } else {
            $.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
            $(".apceptbtn").removeClass('hidden');
            $(".loading2").addClass('hidden');
            //$('#divapcept').html(maxi);
        }
    });
}
function hidetoppic(id) {
    // alert('go');
    //bootbox.confirm("ยืนยันการดำเนินการ ?", function (result) {
    //    if (result) {
    $('#btn_tohrd_').addClass('hidden');
    $.ajax({
        url: rootContext + 'edoc/action',
        type: "GET",
        datatype: "json",
        data: "at=hidetoppic" + "&id=" + id
    }).success(function (result) {
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {
            removetag('tl_' + id);
        } else {
            bootbox.dialog({
                title: "ล้มเหลว!!",
                message: 'msg: <span class="red">' + obj.MSG + '</span>'
            });
            $('#btn_tohrd_').removeClass('hidden');

        }
    });
    //    }
    //});
}
function addfavorites(id) {

    $.ajax({
        url: rootContext + 'edoc/action',
        type: "GET",
        datatype: "json",
        data: "at=addfavorites" + "&id=" + id
    }).success(function (result) {
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {
            //removetag('tl_' + id);
            bootbox.dialog({
                title: "สำเร็จ!!",
                message: 'msg: <span class="green">บันทึกเรื่องนี้เป็นรายการโปรดแล้ว</span>'
            });
        } else {
            bootbox.dialog({
                title: "ล้มเหลว!!",
                message: 'msg: <span class="red">' + obj.MSG + '</span>'
            });
            $('#btn_tohrd_').removeClass('hidden');

        }
    });

}

function agree_click(hid) {
    $(".apceptbtn").addClass('hidden');
    $(".loading2").removeClass('hidden');
    $.ajax({
        url: rootContext + 'edoc/action',
        type: "GET",
        datatype: "json",
        data: "at=agree" + "&id=" + hid
    }).success(function (result) {
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {
            $(".showapcept").removeClass('hidden');
            $(".loading2").addClass('hidden');
            $('#agree_val').html(obj.DATA);
        } else {
            $.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
            $(".apceptbtn").removeClass('hidden');
            $(".loading2").addClass('hidden');
            //$('#divapcept').html(maxi);
        }
    });
}
function disagree_click(hid) {
    $(".apceptbtn").addClass('hidden');
    $(".loading2").removeClass('hidden');
    $.ajax({
        url: rootContext + 'edoc/action',
        type: "GET",
        datatype: "json",
        data: "at=disagree" + "&id=" + hid
    }).success(function (result) {
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {
            $(".showapcept").removeClass('hidden');
            $(".loading2").addClass('hidden');
            $('#disagree_val').html(obj.DATA);
        } else {
            $.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
            $(".apceptbtn").removeClass('hidden');
            $(".loading2").addClass('hidden');
            //$('#divapcept').html(maxi);
        }
    });
}
function topdf() {
    exit();
    $.ajax({
        url: rootContext + 'edoc/action',
        type: "GET",
        datatype: "json",
        data: "at=topdf"
    }).success(function (result) {
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {
        } else {

        }
    });
}
function likeclick(hid) {
    $(".likebtn" + hid).addClass('hidden');
    var maxi = parseFloat($('#likeval' + hid).html());
    $('#likeval' + hid).html(maxi + 1);
    $.ajax({
        url: rootContext + 'edoc/action',
        type: "GET",
        datatype: "json",
        data: "at=like" + "&id=" + hid
    }).success(function (result) {
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {
            $(".unlikebtn" + hid).removeClass('hidden');
            $('#likeval' + hid).html(obj.DATA);
        } else {
            $.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
            $(".likebtn" + hid).removeClass('hidden');
            $('#likeval' + hid).html(maxi);
        }
    });
    $("#tx_message").val('');
}
function unlikeclick(hid) {
    $(".unlikebtn" + hid).addClass('hidden');
    var maxi = parseFloat($('#likeval' + hid).html());
    $('#likeval' + hid).html(maxi - 1);
    $.ajax({
        url: rootContext + 'edoc/action',
        type: "GET",
        datatype: "json",
        data: "at=unlike" + "&id=" + hid
    }).success(function (result) {
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {
            $(".likebtn" + hid).removeClass('hidden');
            $('#likeval' + hid).html(obj.DATA);
        } else {
            $.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
            $(".unlikebtn" + hid).removeClass('hidden');
            $('#likeval' + hid).html(maxi);
        }
    });
    $("#tx_message").val('');
}
function addlist2(bid, lb1) {
    var maxi = parseFloat($('#tmaxi').val()) + 1;
    var t0 = '<input id="tbid' + maxi + '" name="tbid' + maxi + '" type="hidden" value="' + bid + '">';
    var t = '<div id="ct' + bid + '" class="contact-itm">' + t0 + lb1 + ' <a onclick="dellist2(\'ct' + bid + '\')" class="btn btn-xs btn-minier btn-danger pt0">x</a></div>';
    if ($('#ct' + bid).length < 1) {
        $("#divto").append(t);
        $('#tmaxi').val(maxi);
        sumdata();
    }

}
function dellist2(tr) {
    //var tr = $(this).closest('tr');
    //alert(tr);
    $('#' + tr).css("background-color", "#FF3700");
    $('#' + tr).fadeOut(400, function () {
        $('#' + tr).remove();
        sumdata();
    });
    //return false;
}
function sumdata() {
    var maxi = parseFloat($('#tmaxi').val());
    var x = 0;
    for (var i = 1; i <= maxi; i++) {
        if ($('#tbid' + i).val() > 0) {
            x++;
        }
    }
    if (x < 1) {
        $("#divto").removeClass('well');
    } else {
        $("#divto").addClass('well');
    }
}
function deltr(tr) {
    //var tr = $(this).closest('tr');
    $('#' + tr).css("background-color", "#FF3700");
    $('#' + tr).fadeOut(400, function () {
        $('#' + tr).remove();
        sumdata();
    });
    //return false;
}
function addstatus() {
    //$('.modal').modal('hide');
    if (!$("#st_name").val()) {
        $("#st_name").focus();
    } else {

        var t1 = '';
        var t2 = '';
        var cl = $('input[name=st_color]:checked', '#f1');
        var ico = $('input[name=st_icon]:checked', '#f1');
        if (cl.length > 0) {
            t1 = cl.val();
        }
        if (ico.length > 0) {
            t2 = ico.val();
        }
        $.ajax({
            url: rootContext + 'edoc/action',
            type: "POST",
            datatype: "json",
            data: $("#f1").serialize()
        }).success(function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.STATUS == true) {
                //var tx = '<a href="#" title=""><span class="pull-left m1 label label-large label-' + t1 + ' arrowed-right"><i class="' + t2 + '"></i> Larger</span></a>';
                //var tx = '<span id="delid' + obj.LASTID + '" class="pr0 m04 pull-left label label-large label-' + t1 + ' arrowed-right"><i class="' + t2 + '"></i> ' + $("#st_name").val() + ' <a class="ml1 black" onclick="edoc_del(\'status\',\'' + obj.LASTID + '\',\'\')" href="javascript:">x</a></span>';
                //$('#div_status').append(tx);
                $('#div_status').html(obj.MSG);
                $("#st_name").val('');
                $("#st_dtl").val('');
                $(".doc_status").colorbox({iframe: true, width: g_width(), height: g_height(), overlayClose: true});

                //alert(obj.LASTID);

            } else {
                $("#dstatus").html(obj.ERROR_MSG);
                $.msgBox({
                    title: "HRD System",
                    content: obj.ERROR_MSG
                });
            }
        });

        $('.modal').modal('hide');
    }

}
function addassign(bid, lb1) {
    var maxi = parseFloat($('#tmaxassign').val()) + 1;
    var t0 = '<input id="assignid' + maxi + '" name="assignid' + maxi + '" type="hidden" value="' + bid + '">';
    var t = '<div id="ac' + bid + '" class="contact-itm bg_cat">' + t0 + lb1 + ' <a onclick="delassign(\'ac' + bid + '\')" class="btn btn-xs btn-minier btn-danger pt0">x</a></div>';

    if ($('#ac' + bid).length < 1) {
        $("#divassign").append(t);
        $('#tmaxassign').val(maxi);
        sumassign();
        $('#eassign').val('');
        $('#sac' + bid).addClass('list_sl');
    }

}
function assign_select() {
    var lb1 = $("#eassign option:selected").text();
    var bid = $('#eassign').val();
    addassign(bid, lb1);

}
function delassign(tr) {
    //var tr = $(this).closest('tr');
    //alert(tr);
    $('#' + tr).css("background-color", "#FF3700");
    $('#' + tr).fadeOut(400, function () {
        $('#' + tr).remove();
        $('#s' + tr).removeClass('list_sl');
        sumassign();

    });
    //return false;
}
function sumassign() {
    var maxi = parseFloat($('#tmaxassign').val());
    var x = 0;
    for (var i = 1; i <= maxi; i++) {
        if ($('#assignid' + i).val() > 0) {
            x++;
        }
    }
    if (x < 1) {
        $("#divassign").removeClass('well');
        //$("#assign_dtl").addClass('hidden');
    } else {
        $("#divassign").addClass('well');
        //$("#assign_dtl").removeClass('hidden');

    }
}
function gritter_show(t,m,i) {
        $.gritter.add({
            title: '<span class="black ">'+t+'</span>',
            text: '<span class="f14 ">'+m+'</span>',
            sticky: false,
            time: i,
            class_name: 'gritter-warning'
        });

}
function etype_click() {
    var t = $("#etype option:selected").val();
    //alert($("#oldetype").val());
    if ((t != $("#oldetype").val()) && ($("#oldetype").val() != '')) {
        $.gritter.add({
            title: '<span class="black ">แจ้งเตือน!</span>',
            text: '<span class="f14 ">หากมีการเปลี่ยนแปลงประเภทเอกสาร ระบบจะยกเลิกเลขที่เอกสารเดิมและสร้างเลขที่ใหม่ให้อัตโนมัติ</span>',
            sticky: false,
            time: '8000',
            class_name: 'gritter-warning'
        });
    }
    //alert(t);
    if ((t == 't7')) {
        $("input[name=tag_type][value=d]").prop('checked', true);
    } else if ((t == 't1') || (t == 't2') || (t == 't5') || (t == 't6')) {
        $("input[name=tag_type][value=s]").prop('checked', true);
    } else {
        $("input[name=tag_type][value='']").prop('checked', true);
    }
    etype_change(t); 
}
function etype_change(t) {
    $(".inp").removeClass("hidden");
    $(".no"+t).addClass("hidden");
    if(t=='faq'){
       $("#efrom").attr("placeholder", "ระบุประเภท FAQ"); 
       $('#lefrom').html('กลุ่ม FAQ');
    }else{
       $('#efrom').attr("placeholder", "เจ้าของเรื่อง"); 
       $('#lefrom').html('เจ้าของเรื่อง');
    }
    //alert($("#efrom").attr("placeholder"));
}
function atype_click() {
    var t = $("#atype option:selected").text();
    var id = $("#atype").val();
    $("#st_name").val(t);
    var x1 = 'default';
    var x2 = 'icon-info';
    if (id == 't0') {
        x1 = 'warning';
        x2 = 'icon-info';
    } else if (id == 't01') {
        x1 = 'default';
        x2 = 'icon-info';
    } else if (id == 't1') {
        x1 = 'pink';
        x2 = 'icon-info';
    } else if (id == 't2') {
        x1 = 'info';
        x2 = 'icon-info';
    } else if (id == 'cancel') {
        x1 = 'default';
        x2 = 'icon-exclamation-sign';
    } else if (id == 'success') {
        x1 = 'success';
        x2 = 'icon-smile';
    } else if (id == 'sent') {
        x1 = 'primary';
        x2 = 'icon-ok';
    }
    $("input[name=st_color][value=" + x1 + "]").prop('checked', true);
    $("input[name=st_icon][value=" + x2 + "]").prop('checked', true);
}
function edoc_del(mn, hid, pretxt) {
    // alert(mn);
    $.msgBox({
        title: "Are You Sure",
        content: "ยืนยันการลบข้อมูล [" + mn + ":" + hid + "] ?",
        type: "confirm",
        buttons: [{value: "Yes"}, {value: "No"}],
        success: function (result) {
            if (result == "Yes") {
                $.ajax({
                    url: rootContext + 'edoc/action',
                    type: "GET",
                    datatype: "json",
                    data: "at=del" + mn + "&id=" + hid
                }).success(function (result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.STATUS == true) {
                        removetag('delid' + pretxt + hid);
                        //alert('delid'+pretxt + hid);
                    } else {
                        //$.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
                        bootbox.dialog({
                            title: "ล้มเหลว!!",
                            message: '<h5>msg: <span class="red">' + obj.ERROR_MSG + '</span></h5>',
                            buttons: {main: {label: "OK!", className: "btn-danger"}}

                        });
                    }
                });

            }
        }
    });
}

function addstatusx() {
    if (!$("#cmsave2").hasClass('disabled')) {
        $("#toplan").val("1");
        $("#dstatus").html("กำลังบันทึกข้อมูล");
        $(".cmaction").addClass("disabled");
        $.ajax({
            url: 'fn/action.php',
            type: "POST",
            datatype: "json",
            data: $("#f1").serialize()
        }).success(function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.STATUS == true) {

                $("#dstatus").html("บันทึกเรียบร้อยแล้ว!!");
                //$("#dstatus").html(obj.ERROR_MSG);
                setTimeout("plan(1)", 300);
            } else {
                $("#dstatus").html(obj.ERROR_MSG);
                setTimeout("plan(0)", 300);
                $.msgBox({
                    title: "IDP System",
                    content: obj.ERROR_MSG
                });
            }
        });
    }
}
function myskin_things() {
    var b = $('input[name=skin_sl]:checked').val();
    $('#myskin').val(b);
    setskin(b);
    $.ajax({
        url: rootContext + 'edoc/action',
        type: "GET",
        datatype: "json",
        data: "at=saveskin" + "&skin=" + b
    }).success(function (result) {

    });
}
function setlastnotification() {
    $.ajax({
        url: rootContext + 'edoc/action',
        type: "GET",
        datatype: "json",
        data: "at=setlastnotification"
    }).success(function (result) {

    });
}
function fixtop() {
    $(".navbar").toggleClass("navbar-fixed-top");
    $(document.body).toggleClass("navbar-fixed")
}



