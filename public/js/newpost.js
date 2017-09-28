
var rootContext = document.body.getAttribute("data-root");
if (!rootContext)rootContext = '';
if($('#dtl').length){
   CKEDITOR.replace('dtl', {customConfig: rootContext + 'ckeditor/customconfig_topic.js'}); 
}
if($('#dtl_topic').length){
   CKEDITOR.replace('dtl_topic', {customConfig: rootContext + 'ckeditor/customconfig_topic.js'}); 
}

var resizeTimer;
function resizeColorBox()
{
    $.colorbox.resize({
        width: window.innerWidth > parseInt(cboxOptions.maxWidth) ? cboxOptions.maxWidth : cboxOptions.width,
        height: window.innerHeight > parseInt(cboxOptions.maxHeight) ? cboxOptions.maxHeight : cboxOptions.height
    });
}
function g_width() {
    var x = $(window).width();
    if (x < 800)
        return "100%";
    else
        return '800px';
}
function g_height() {
    var x = $(window).height();
    if (x < 600)
        return "99%";
    else
        return '600px';
}
function InsertText(f, b, n) {
    var editor = CKEDITOR.instances.dtl;
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
function rand_avatar() {

    $(".loading").removeClass('hidden');
    $.ajax({
        url: rootContext + 'edoc/action',
        type: "GET",
        datatype: "json",
        data: "at=rand_avatar"
    }).success(function (result) {
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {
            $(".myavatar").attr("src", obj.MSG);
            $(".loading").addClass('hidden');
        } else {
        }
    });
    $("#tx_message").val('');


}
function addmsg() {
    var t = $("#tx_message").val();
    if (t) {
        $(".loading").removeClass('hidden');
        $.ajax({
            url: rootContext + 'edoc/action',
            type: "POST",
            datatype: "json",
            data: $("#form_msg").serialize()
        }).success(function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.STATUS == true) {

                $("#tx_message").val('');
                $("#div_msg").prepend(obj.MSG);
                $(".loading").addClass('hidden');
                //alert(obj.LASTID);

            } else {
                $("#div_msg").prepend('<div class="alert alert-error m04 f12 p04">ERROR !!</div>');
            }
        });
        $("#tx_message").val('');
    } else {
        $("#tx_message").focus();
    }

}
function addmsg_tl(id) {
    var t = $("#tx_message" + id).val();
    //alert(t);
    if (t) {
        $("#loading_msg" + id).removeClass('hidden');
        $("#tx_message" + id).addClass('hidden');
        $.ajax({
            url: rootContext + 'edoc/action',
            type: "POST",
            datatype: "json",
            data: "at=addmsg&tx_message=" + t + "&doc_id=" + id
        }).success(function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.STATUS == true) {

                $("#tx_message" + id).val('');
                $("#div_msg" + id).prepend(obj.MSG);
                $("#loading_msg" + id).addClass('hidden');
                $("#tx_message" + id).removeClass('hidden');
                $("#div_msg" + id).removeClass('hidden');
                //alert(obj.LASTID);

            } else {
                $("#div_msg" + id).prepend('<div class="alert alert-error m04 f12 p04">ERROR !!</div>');
            }
        });
        $("#tx_message" + id).val('');
    } else {
        $("#tx_message" + id).focus();
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
function setmenumin() {
    var x = 0;
    if ($("#sidebar").hasClass("menu-min")) {
        $("#sidebar").removeClass("menu-min");
        $('.iconsidebar').addClass("icon-double-angle-left");
        $('.iconsidebar').removeClass("icon-double-angle-right");
        $(".open > .submenu").addClass("open");
        $("#sw_menumin").prop('checked', false);
    } else {
        $("#sidebar").addClass("menu-min");
        $('.iconsidebar').addClass("icon-double-angle-right");
        $('.iconsidebar').removeClass("icon-double-angle-left");
        $(".open > .submenu").removeClass("open");
        $("#sw_menumin").prop('checked', true);
        x = 1;
    }
    $.ajax({
        url: rootContext + 'edoc/action',
        type: "GET",
        datatype: "json",
        data: "at=setmenumin" + "&menumin=" + x
    }).success(function (result) {
    });
}

function assign_click() {
    if ($("#div_assign").hasClass("hidden")) {
        $("#div_assign").removeClass("hidden");
        $("#sw_assign").prop('checked', true);
    } else {
        $("#div_assign").addClass("hidden");
        $("#sw_assign").prop('checked', false);
    }
}

function setskin(b) {
    //var b = $("#skin_sl").find("option:selected").prop('checked');
    //alert(b);
    var a = $(document.body);
    a.removeClass("skin-1 skin-2 skin-3");
    if (b != "default") {
        a.addClass(b)
    }
    if (b == "skin-1") {
        $(".ace-nav > li.grey").addClass("dark")
    } else {
        $(".ace-nav > li.grey").removeClass("dark")
    }
    if (b == "skin-2") {
        $(".ace-nav > li").addClass("no-border margin-1");
        $(".ace-nav > li:not(:last-child)").addClass("light-pink").find('> a > [class*="icon-"]').addClass("pink").end().eq(0).find(".badge").addClass("badge-warning")
    } else {
        $(".ace-nav > li").removeClass("no-border margin-1");
        $(".ace-nav > li:not(:last-child)").removeClass("light-pink").find('> a > [class*="icon-"]').removeClass("pink").end().eq(0).find(".badge").removeClass("badge-warning")
    }
    if (b == "skin-3") {
        $(".ace-nav > li.grey").addClass("red").find(".badge").addClass("badge-yellow")
    } else {
        $(".ace-nav > li.grey").removeClass("red").find(".badge").removeClass("badge-yellow")
    }

}

function tohrd(id, ty) {
    // alert('go');
    bootbox.confirm("ยืนยันการส่งข้อมูล ?", function (result) {
        if (result) {
            $('#btn_tohrd_' + ty).addClass('hidden');
            $.ajax({
                url: rootContext + 'edoc/action',
                type: "GET",
                datatype: "json",
                data: "at=tohrd" + "&id=" + id + "&ty=" + ty
            }).success(function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.STATUS == true) {
                    $('#tohrd_success_' + ty).removeClass('hidden');
                    $('#btn_tohrd_' + ty).remove();

                } else {
                    bootbox.dialog({
                        title: "ล้มเหลว!!",
                        message: 'msg: <span class="red">' + obj.MSG + '</span>'
                    });
                    $('#btn_tohrd_' + ty).removeClass('hidden');

                }
            });
        }
    });
}

function assign_edit(aid, cat) {
    var v1 = $('#assign_val_' + aid).val();
    var v2 = $('#assign_tx_' + aid).html();
    var v3 = 'checked';
    var d = [];
    for (var i = 1; i < 4; i++) {
        d[i] = '';
    }
    d[v1] = ' selected ';
    //if(v1<1){v3='checked'}
    bootbox.dialog({
        title: "บันทึกผลดำเนินการ",
        message: '' +
                '<form class="form-horizontal" id="f_assign"> ' +
                '<input name="assign_id" id="assign_id" type="hidden" value="' + aid + '">  ' +
                '<input name="cat" id="cat" type="hidden" value="' + cat + '">  ' +
                '<input name="at" id="at" type="hidden" value="assign_edit">  ' +
                '<div class=""><select id="a_assign" class="span2 " name="a_assign" >' +
                '<option  value=""><เลือก></option>' +
                '<option ' + d[1] + ' value="1">ทราบ</option>' +
                '<option ' + d[2] + ' value="2">กำลังดำเนินการ</option>' +
                '<option ' + d[3] + ' value="3">แล้วเสร็จ</option>' +
                '</select>' +
                '</div>' +
                '<textarea class="" style="width:98%" rows="3" placeholder="<รายละเอียด>" id="at_dtl" name="at_dtl" data-maxlength="200">' + v2 + '</textarea>' +
                '<label><input ' + v3 + ' id="assign_ck" name="assign_ck" value="1" type="checkbox" /><span class="lbl"> บันทึกเป็นสถานะเอกสาร</span></label>' +
                '</form>',
        buttons: {
            success: {
                label: "Save",
                className: "btn-success",
                callback: function () {
                    //alert($("#f_assign").serialize());
                    if ($('#a_assign').val() > 0) {
                        $.ajax({
                            url: rootContext + 'edoc/action',
                            type: "POST",
                            datatype: "json",
                            data: $("#f_assign").serialize()
                        }).success(function (result) {
                            var obj = jQuery.parseJSON(result);
                            if (obj.STATUS == true) {
                                $('#assign_dtl_' + aid).html(obj.MSG);
                                //alert(aid+obj.MSG);
                                if (obj.MSG2) {
                                    $('#div_status').html(obj.MSG2);
                                    $(".doc_status").colorbox({iframe: true, width: g_width(), height: g_height(), overlayClose: true});
                                }
                            } else {
                                bootbox.dialog({
                                    title: "ล้มเหลว!!",
                                    message: 'msg: <span class="red">' + obj.ERROR_MSG + '</span>',
                                    buttons: {main: {label: "OK!", className: "btn-danger"}
                                    }
                                });
                            }
                        });
                    } else {
                        assign_edit(aid, cat);
                        $('#a_assign').focus();
                    }
                }
            }
        }
    }
    );
}







function myskin_thingsXXXX() {
    $('.ace-nav [class*="icon-animated-"]').closest("a").on("click", function () {
        var b = $(this).find('[class*="icon-animated-"]').eq(0);
        var a = b.attr("class").match(/icon\-animated\-([\d\w]+)/);
        b.removeClass(a[0]);
        $(this).off("click")
    });
    $(".nav-list .badge[title],.nav-list .label[title]").tooltip({placement: "right"});
    $("#ace-settings-btn").on(ace.click_event, function () {
        $(this).toggleClass("open");
        $("#ace-settings-box").toggleClass("open")
    });
    $("#ace-settings-header").removeAttr("checked").on("click", function () {
        if (!this.checked) {
            if ($("#ace-settings-sidebar").get(0).checked) {
                $("#ace-settings-sidebar").click()
            }
        }
        $(".navbar").toggleClass("navbar-fixed-top");
        $(document.body).toggleClass("navbar-fixed")
    });
    $("#ace-settings-sidebar").removeAttr("checked").on("click", function () {
        if (this.checked) {
            if (!$("#ace-settings-header").get(0).checked) {
                $("#ace-settings-header").click()
            }
        } else {
            if ($("#ace-settings-breadcrumbs").get(0).checked) {
                $("#ace-settings-breadcrumbs").click()
            }
        }
        $("#sidebar").toggleClass("fixed")
    });
    $("#ace-settings-breadcrumbs").removeAttr("checked").on("click", function () {
        if (this.checked) {
            if (!$("#ace-settings-sidebar").get(0).checked) {
                $("#ace-settings-sidebar").click()
            }
        }
        $("#breadcrumbs").toggleClass("fixed");
        $(document.body).toggleClass("breadcrumbs-fixed")
    });
    $("#ace-settings-rtl").removeAttr("checked").on("click", function () {
        switch_direction()
    });
    $("#btn-scroll-up").on(ace.click_event, function () {
        var a = Math.max(100, parseInt($("html").scrollTop() / 3));
        $("html,body").animate({scrollTop: 0}, a);
        return false
    });
    $("#skin-colorpicker").ace_colorpicker().on("change", function () {
        var b = $(this).find("option:selected").data("class");
        var a = $(document.body);
        a.removeClass("skin-1 skin-2 skin-3");
        if (b != "default") {
            a.addClass(b)
        }
        if (b == "skin-1") {
            $(".ace-nav > li.grey").addClass("dark")
        } else {
            $(".ace-nav > li.grey").removeClass("dark")
        }
        if (b == "skin-2") {
            $(".ace-nav > li").addClass("no-border margin-1");
            $(".ace-nav > li:not(:last-child)").addClass("light-pink").find('> a > [class*="icon-"]').addClass("pink").end().eq(0).find(".badge").addClass("badge-warning")
        } else {
            $(".ace-nav > li").removeClass("no-border margin-1");
            $(".ace-nav > li:not(:last-child)").removeClass("light-pink").find('> a > [class*="icon-"]').removeClass("pink").end().eq(0).find(".badge").removeClass("badge-warning")
        }
        if (b == "skin-3") {
            $(".ace-nav > li.grey").addClass("red").find(".badge").addClass("badge-yellow")
        } else {
            $(".ace-nav > li.grey").removeClass("red").find(".badge").removeClass("badge-yellow")
        }
    })
}		