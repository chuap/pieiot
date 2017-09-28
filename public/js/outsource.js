
var rootContext = document.body.getAttribute("data-root");
if (!rootContext)
    rootContext = '';
CKEDITOR.replace('dtl', {customConfig: rootContext + 'ckeditor/customconfig_edoc.js'});

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
function deltr(tr) {
    //var tr = $(this).closest('tr');
    $('#' + tr).css("background-color", "#FF3700");
    $('#' + tr).fadeOut(400, function () {
        $('#' + tr).remove();
        sumdata();
    });
    //return false;
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


function submit_f(p, ty) {
    var rootContext = document.body.getAttribute("data-root");
    if (!rootContext)
        rootContext = '';
    var tid = $('#tid').val();
    var c1 = 0;
    var c2 = 0;
    var er = false;
    if ($('#txcourse').val() == "") {
        er = true;
        $.msgBox({title: "CAT-HRD System", content: "กรุณาระบุชื่อเรื่องหรือหลักสูตร!!!",
            success: function (result) {
                $('#txcourse').focus();
            }});
    }
    else {
        $(".ipck").each(function () {
            c1++;
            if ($(this).val() > 0) {
                c2++;
            }
        });
        if (c1 != c2) {
            er = true;
            $.msgBox({title: "CAT-HRD System", content: "คุณตอบแบบประเมินไม่ครบ!!!",
                success: function (result) {
                    //scto('#t3');
                }});
        }
    }
    if (!er) {
        $.msgBox({
            title: "Confirm",
            content: "ยืนยันการส่งผลประเมิน ?",
            type: "confirm",
            buttons: [{value: "Yes"}, {value: "No"}],
            success: function (result) {
                if (result == "Yes") {
                    //$('.command').addClass('hidden');
                    $('.divload').removeClass('hidden');
                    $.ajax({
                        url: rootContext + 'tools/action?at=submit_eval',
                        type: "POST",
                        datatype: "json",
                        data: $("#f1").serialize()
                    }).success(function (result) {
                        var obj = jQuery.parseJSON(result);
                        if (obj.STATUS == true) {

                            $("#dstatus").html("บันทึกเรียบร้อยแล้ว!!");
                            $.msgBox({title: "CAT-HRD System", content: "บันทึกเรียบร้อยแล้ว!!!",
                                success: function (result) {
                                    //scto('#t3');
                                    parent.$.colorbox.close();
                                    parent.location.reload();
                                    if (ty == 'me') {
                                        //window.location.href = rootContext + 'followup/me';
                                    } else {
                                        // window.location.href = rootContext + 'followup/courseinfo?p=' + p;
                                    }

                                }});
                            //setTimeout("plan(1)", 300);
                        } else {
                            $("#dstatus").html(obj.ERROR_MSG);
                            $.msgBox({
                                title: "HRD System",
                                content: obj.ERROR_MSG
                            });
                        }
                    });
                }
            }
        });
    }
}
function outsource_del(mn, hid, pretxt) {
    // alert(mn);
    $.msgBox({
        title: "Are You Sure",
        content: "ยืนยันการลบข้อมูล [" + mn + ":" + hid + "] ?",
        type: "confirm",
        buttons: [{value: "Yes"}, {value: "No"}],
        success: function (result) {
            if (result == "Yes") {
                $.ajax({
                    url: rootContext + 'tools/action',
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