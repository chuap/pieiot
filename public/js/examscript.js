$(document).ready(function() {
    var tleft = $('#tleft').val(); 
    var d = new Date();
    var nd=new Date(d.getTime() + tleft*1000);
    savescore(0, 's', 0, 0);
    $('.divload').addClass('hidden');
   // newYear.setSeconds(newYear.getSeconds() + tleft*1000); 
   // alert(tleft);
   // var dt = $('#tstop').val();
   // newYear = new Date(dt);
    //alert(newYear);
    //$('#countdown').countdown({until: newYear});
    $('#countdown').countdown({until: nd, compact: true,
        layout: 'เหลือเวลา <b> {hnn}{sep}{mnn}{sep}{snn}</b> {desc}',
        description: '', onExpiry: liftOff,
        onTick: everyFive, tickInterval: 30});
});

function everyFive(periods) {
    //  $('#everyFive').html(periods); 
}
function liftOff() {
    var tid = $('#tid').val();
    $.msgBox({title: "HRD System", content: 'หมดเวลาการทำแบบทดสอบ...', type: "error",
        success: function() {
            $('.command').addClass('hidden');
            $('.divload').removeClass('hidden');
            $.ajax({
                url: 'action',
                type: "GET",
                datatype: "json",
                data: "at=submitexam&tid=" + tid
            }).success(function(result) {
                //jQuery.colorbox.close();
                $('.command').removeClass('hidden');
                $('.divload').addClass('hidden');
                var obj = jQuery.parseJSON(result);
                if (obj.STATUS == true) {
                    $.colorbox({inline: true, fadeIn: 0, fadeOut: 0, href: "#divload", width: "200px", height: "80px", overlayClose: false, onLoad: function() {
                            $('#cboxClose').remove();
                        }});
                    $.msgBox({title: "HRD System", content: 'กรุณารอสักครู่', type: "info"});
                    location.reload();
                } else {
                    $.msgBox({title: "HRD System", content: obj.ERROR_MSG, type: "error"});
                }
            });
        }});
}
function takeexam(id) {
    $.msgBox({
        title: "Are You Ready",
        content: "ยืนยันเริ่มทำแบบทดสอบ ? <br> คุณจะต้องตอบแบบทดสอบภายในระยะเวลาที่กำหนด",
        type: "confirm",
        buttons: [{value: "Yes"}, {value: "No"}],
        success: function(result) {
            if (result == "Yes") {
//                $.colorbox({inline:true,fadeIn: 0,fadeOut: 0, href:"#divload",width:"200px",height:"80px", overlayClose:false,onLoad: function() {
//    $('#cboxClose').remove();
//}});
                $('.command').addClass('hidden');
                $('.divload').removeClass('hidden');
                $.ajax({
                    url: 'action',
                    type: "GET",
                    datatype: "json",
                    data: "at=takeexam&id=" + id
                }).success(function(result) {
                    //jQuery.colorbox.close();
                    $('.command').removeClass('hidden');
                    $('.divload').addClass('hidden');
                    var obj = jQuery.parseJSON(result);
                    if (obj.STATUS == true) {
                        $.colorbox({inline: true, fadeIn: 0, fadeOut: 0, href: "#divload", width: "200px", height: "80px", overlayClose: false, onLoad: function() {
                                $('#cboxClose').remove();
                            }});
                        $.msgBox({title: "HRD System", content: 'กรุณารอสักครู่', type: "info"});
                        location.reload();
                    } else {
                        $.msgBox({title: "HRD System", content: obj.ERROR_MSG, type: "error"});
                    }
                });
            }
        }
    });
}

function removetag(t) {
    $('#' + t).css("background-color", "#ff6666");
    $('#' + t).fadeOut(400, function() {
        $('#' + t).remove();
    });
    //return false;
}

function chclick(qid, c) {

    var i = $('#ccount_' + qid).val();
    if ($('#qtype_' + qid).val() == 's') {
        //alert($('#ck' + qid + '_' + c).prop('checked'));
        if ($('#ck' + qid + '_' + c).prop('checked')) {
            for (var j = 1; j <= i; j++) {
                if (j != c) {
                    $('#ck' + qid + '_' + j).attr('checked', false);
                    $('#sp' + qid + '_' + j).removeClass('label-primary');
                }
            }
            $('#goto_' + qid).addClass('btn-primary');
            $('#sp' + qid + '_' + c).addClass('label-primary');
            savescore(qid, 's', c, 1);
        }
        else {
            $('#goto_' + qid).removeClass('btn-primary');
            $('#sp' + qid + '_' + c).removeClass('label-primary');
            savescore(qid, 's', c, 0);
        }

    }
    else {
        $('#sp' + qid + '_' + c).removeClass('label-primary');
    }

    takesum();
}
function takesum() {
    var c = $('#qcount').val();
    //alert(c);
    var x = 0;
    for (var i = 1; i <= c; i++) {
        var t = $('#qid_' + i).val();
        if ($("#goto_" + t).hasClass("btn-primary")) {
            x++;
        }
    }
    $('#taked').html(x);
    $('#tcount').val(x);
}

function savescore(qid, qtype, c, v) {
    //alert('44');
    var tid = $('#tid').val();
    $('.divload').removeClass('hidden');
    $.ajax({
        url: 'action',
        type: "get",
        datatype: "json",
        data: "at=savescore&tid=" + tid + "&qid=" + qid + "&qtype=" + qtype + "&c=" + c + "&v=" + v
    }).success(function(result) {
        $('.divload').addClass('hidden');
        var obj = jQuery.parseJSON(result);
        if (obj.STATUS == true) {


        } else {
            if (obj.ERROR == 'TIMEOUT') {
                liftOff();
            }
            else {
                $.msgBox({title: "HRD System", content: obj.ERROR_MSG, type: "error"});
            }
        }
    });

}
function submitexam() {
    var tid = $('#tid').val();
    if ($('#tcount').val() < 1) {
        return  $.msgBox({title: "HRD System", content: 'กรุณาตอบแบบทดสอบ...', type: "error"});
    }
    else if (($('#tcount').val() < $('#qcount').val())&&($('#take_all').val()=='1')) {
        return  $.msgBox({title: "HRD System", content: 'กรุณาทำแบบทดสอบให้ครบทุกข้อ...', type: "error"});
    }
    $.msgBox({
        title: "Confirm",
        content: "ยืนยันส่งผลการทำแบบทดสอบ ?",
        type: "confirm",
        buttons: [{value: "Yes"}, {value: "No"}],
        success: function(result) {
            if (result == "Yes") {
                $('.command').addClass('hidden');
                $('.divload').removeClass('hidden');
                $.ajax({
                    url: 'action',
                    type: "GET",
                    datatype: "json",
                    data: "at=submitexam&tid=" + tid
                }).success(function(result) {
                    //jQuery.colorbox.close();
                    $('.command').removeClass('hidden');
                    $('.divload').addClass('hidden');
                    var obj = jQuery.parseJSON(result);
                    if (obj.STATUS == true) {
                        $.colorbox({inline: true, fadeIn: 0, fadeOut: 0, href: "#divload", width: "200px", height: "80px", overlayClose: false, onLoad: function() {
                                $('#cboxClose').remove();
                            }});
                        $.msgBox({title: "HRD System", content: 'กรุณารอสักครู่', type: "info"});
                        location.reload();
                    } else {
                        $.msgBox({title: "HRD System", content: obj.ERROR_MSG, type: "error"});
                    }
                });
            }
        }
    });
}