'use strict';

/**
 * NewUploaderHelper by Choonewza
 */
var NewUploaderHelper = {
    ajaxUploder: {},
    fileStack: {},
    fileStackIndex: {},
    boxIdStack: {},
    respondOfBoxIds: {},
    getRespondOfBoxIds: function (tagId, boxId) {
        return this.respondOfBoxIds[tagId][boxId];
    },
    setRespondOfBoxIds: function (tagId, boxId, respond) {
        if (!this.respondOfBoxIds.hasOwnProperty(tagId)) {
            this.respondOfBoxIds[tagId] = {};
        }
        this.respondOfBoxIds[tagId][boxId] = respond;
    },
    deleteRespondOfBoxIds: function (tagId, boxId) {
        if (this.respondOfBoxIds.hasOwnProperty(tagId) && this.respondOfBoxIds[tagId].hasOwnProperty(boxId)) { //for refresh web page
            delete this.respondOfBoxIds[tagId][boxId];
        }
    },
    destroyRespondOfBoxIds: function (tagId) {
        if (this.respondOfBoxIds.hasOwnProperty(tagId)) { //for refresh web page
            delete this.respondOfBoxIds[tagId];
        }
    },
    onClickBtnRemove: undefined,
    removeBoxOfFile: function (tagId, boxId) {
        $("#" + boxId).remove();
        this.deleteRespondOfBoxIds(tagId, boxId);
    },
    generateTagId: function () {
        return this.randomString(5, "#aA");
    },
    fileExtension: function (sFileName) {
        return sFileName.substr(sFileName.lastIndexOf(".") + 1).toLowerCase();
    },
    isFileAccept: function (sFileName, fileAccept, fileExclusions) {
        if (fileAccept.length <= 0 && fileExclusions.length <= 0) {
            return true; //accept all file
        } else if (fileAccept.length > 0) {
            for (var i = 0; i < fileAccept.length; i++) {
                if (this.fileExtension(sFileName) === fileAccept[i].toLowerCase()) {
                    return true; // unaccept file
                }
            }
            return false; // unaccept file
        } else {
            for (var i = 0; i < fileExclusions.length; i++) {
                if (this.fileExtension(sFileName) === fileExclusions[i].toLowerCase()) {
                    return false; // unaccept file
                }
            }
            return true; // accept file
        }
    },
    resetFieldForm: function (fieldSelector) {
        var field = $(fieldSelector);
        field.wrap('<form>').parent('form').trigger('reset');
        field.unwrap();
        field.prop("disabled", false);
    },
    randomString: function (length, chars) {
        var mask = '';
        if (chars.indexOf('a') > -1)
            mask += 'abcdefghijklmnopqrstuvwxyz';
        if (chars.indexOf('A') > -1)
            mask += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if (chars.indexOf('#') > -1)
            mask += '0123456789';
        if (chars.indexOf('!') > -1)
            mask += '~`!@#$%^&*()_+-={}[]:";\'<>?,./|\\';
        var result = '';
        for (var i = length; i > 0; --i)
            result += mask[Math.round(Math.random() * (mask.length - 1))];
        return result;
    },
    abortUploader: function (tagId, boxId) {
        console.log(tagId + " [" + boxId + "]");
        if (tagId in this.ajaxUploder && boxId in this.ajaxUploder[tagId]) {
            console.log("abort");
            NewUploaderHelper.ajaxUploder[tagId][boxId].abort();
            $("#" + boxId).remove();

            delete NewUploaderHelper.ajaxUploder[tagId][boxId];
            delete NewUploaderHelper.fileStackIndex[tagId][boxId];
        } else {
            this.fileStack[tagId].splice(this.fileStackIndex[tagId][boxId], 1); //remove item from fileStack
            this.boxIdStack[tagId].splice(this.fileStackIndex[tagId][boxId], 1); //remove item from fileStack
            $("#" + boxId).remove();

            delete NewUploaderHelper.fileStackIndex[tagId][boxId];
        }
    },
    escapeRegExp: function (string) {
        return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
    },
    replaceAll: function (string, find, replace) {
        return string.replace(new RegExp(this.escapeRegExp(find), 'g'), replace);
    },
    ltrim: function (str) {
        if (str == null) {
            return null;
        }
        for (var i = 0; str.charAt(i) == " "; i++)
            ;
        return str.substring(i, str.length);
    },
    rtrim: function (str) {
        if (str == null) {
            return null;
        }
        for (var i = str.length - 1; str.charAt(i) == " "; i--)
            ;
        return str.substring(0, i + 1);
    },
    trim: function (str) {
        return this.ltrim(this.rtrim(str));
    }
};

function NewUploader(options, files) {
    this.tagId = "";
    this.options = options; //custom setting
    this.files = files;
    this.fileTotal = 0;
    this.fileStack = [];
    this.boxIdStack = [];
    this.fileWaiting = {};
    this.boxProgressBar = {}; //for keep progress bar of boxId for perfomance.
    this.successFiles = [];
    this.failFiles = [];
    this.mainProgressBar = undefined;
    this.mainProgressBarValue = undefined;
}


//public function
NewUploader.prototype = {
    run: function () {
        var that = this;
        this.tagId = this.options.tagId !== "" ? this.options.tagId : NewUploaderHelper.generateTagId();
        NewUploaderHelper.ajaxUploder[this.tagId] = {};
        NewUploaderHelper.fileStackIndex[this.tagId] = {};

        this.fileTotal = this.files.length;

        this.generateMainProgressBar();
        for (var i = this.files.length - 1; i >= 0; i--) {
            var file = this.files[i];
            if (!NewUploaderHelper.isFileAccept(file.name, this.options.fileAccept, this.options.fileExclusions)) {
                this.options.unsupportFileFunc(file);
            } else {
                var boxId = this.generateBoxId();
                NewUploaderHelper.fileStackIndex[this.tagId][boxId] = this.files.length - i - 1;
                this.boxIdStack.push(boxId);
                this.fileStack.push(file);
//                this.fileWaiting[boxId] = file; version 2 waiting upload action
            }
        }//for loop

        NewUploaderHelper.fileStack[this.tagId] = this.fileStack; //pass by reference
        NewUploaderHelper.boxIdStack[this.tagId] = this.boxIdStack; //pass by reference

        //generate box item
        for (var i = this.boxIdStack.length - 1; i >= 0; i--) {
            if (this.options.showPlayback) {
                this.generateBoxItem(this.boxIdStack[i], this.fileStack[i].name);
            }
        }
        this.uploadFile();
        console.log("-- end run() --");
    },
    uploadFile: function () {
        if (this.fileStack.length > 0) {
            var file = this.fileStack.pop(); //get file in stack
            var boxId = this.boxIdStack.pop();
            var formData = new FormData();

            for (var k in this.options.data) {
                if (this.options.data.hasOwnProperty(k)) {
                    formData.append(k, this.options.data[k]);
                }
            }

            if (file.type.match('image.*')) {
                this.actionUploadImage(file, boxId, formData);
            } else if (file.type.match('audio.*')) {
                this.actionUploadAudio(file, boxId, formData);
            } else if (file.type.match('video.*')) {
                this.actionUploadVideo(file, boxId, formData);
            } else {
                this.actionUploadOther(file, boxId, formData);
            }
        } else {
            //upload finish
            this.clearAll();
            this.options.completeFunc(this.successFiles, this.failFiles);
        }
    },
    actionUploadImage: function (file, boxId, formData) {
        var that = this; //NewUploader Class
        var imgWidth = 0;
        var imgHeight = 0;
        var imgQuality = 100;
        if (!this.options.imgHD) {
            imgWidth = this.options.imgMaxWidth;
            imgHeight = this.options.imgMaxHeight;
            imgQuality = this.options.imgQuality;
        }

        canvasResize(file, {
            width: imgWidth,
            height: imgHeight,
            crop: that.options.imgCrop,
            quality: imgQuality,
            rotate: that.options.imgRotate,
            callback: function (mimeFile, width, height) {
                var playback = {
                    type: "image",
                    isRaw: false,
                    file: mimeFile,
                    fileName: file.name,
                    fileType: file.type,
                    fileSize: file.size,
                    fileExtension: NewUploaderHelper.fileExtension(file.name),
                    title: function (fileName, filePath) {
                        return "<a href=\"" + filePath + "\" target=\"_blank\" download>" + fileName + "</a>";
                    },
                    preview: function (fileName, filePath, fileType) {
                        return "<a href=\"" + filePath + "\" target=\"_blank\" class='fancybox'>"
                                + "<img src=\"" + filePath + "\" title=\"" + fileName + "\" alt=\"" + fileName + "\" >"
                                + "</a>";
                    }
                };
                that.ajaxUpload(file, boxId, formData, playback);
            }//callback
        });//canvasResize
    },
    actionUploadAudio: function (file, boxId, formData) {
        var playback = {
            type: "audio",
            isRaw: true,
            file: file,
            fileName: file.name,
            fileType: file.type,
            fileSize: file.size,
            fileExtension: NewUploaderHelper.fileExtension(file.name),
            title: function (fileName, filePath) {
                return "<a href=\"" + filePath + "\" target=\"_blank\">" + fileName + "</a>";
            },
            preview: function (fileName, filePath, fileType) {
                return "<div class=\"newuploader-thumbnail-audio\">"
                        + "<audio class=\"newuploader-thumbnail-audio-item\" controls>"
                        + "<source src=\"" + filePath + "\" type=\"" + fileType + "\">"
                        + "Your browser does not support the audio tag."
                        + "</audio>"
                        + "</div>";
            }
        };
        this.ajaxUpload(file, boxId, formData, playback);
    },
    actionUploadVideo: function (file, boxId, formData) {
        var playback = {
            type: "video",
            isRaw: true,
            file: file,
            fileName: file.name,
            fileType: file.type,
            fileSize: file.size,
            fileExtension: NewUploaderHelper.fileExtension(file.name),
            title: function (fileName, filePath) {
                return "<a href=\"" + filePath + "\" target=\"_blank\">" + fileName + "</a>";
            },
            preview: function (fileName, filePath, fileType) {
                return "<div class=\"embed-responsive embed-responsive-4by3\">"
                        + "<video class=\"embed-responsive-item\" width=\"250\" height=\"250\" controls>"
                        + "<source src=\"" + filePath + "\" type=\"" + fileType + "\">"
                        + "Your browser does not support the video tag."
                        + "</video>"
                        + "</div>";
            }
        };
        this.ajaxUpload(file, boxId, formData, playback);
    },
    actionUploadOther: function (file, boxId, formData) {
        var playback = {
            type: "other",
            isRaw: true,
            file: file,
            fileName: file.name,
            fileType: file.type,
            fileSize: file.size,
            fileExtension: NewUploaderHelper.fileExtension(file.name),
            title: function (fileName, filePath) {
                return "<a href=\"" + filePath + "\" target=\"_blank\">" + fileName + "</a>";
            },
            preview: function (fileName, filePath, fileType) {
                return "<div>"
//                        + "<a href='" + filePath + "' target='_blank' title='" + escape(fileName) + "'><span class='glyphicon glyphicon-download'></span> Download<br/>" + fileName + "</a>"
                        + "<a href='" + filePath + "' target='_blank' download title='" + escape(fileName) + "'>"+'<img src="'+laravel5.url('/img/download-icon-1.png')+'" title="'+fileName+'" alt="'+fileName+'"/>' + "</a>"
                        + "</div>";
            }
        };
        this.ajaxUpload(file, boxId, formData, playback);
    },
    ajaxUpload: function (file, boxId, formData, playback) {
        var that = this; //NewUploader Class
        formData.append('nupFile', playback.file);
        formData.append('nupIsRawFile', playback.isRaw);
        formData.append('nupFileName', playback.fileName);
        formData.append('nupFileType', playback.fileType);
        formData.append('nupFileSize', playback.fileSize);
        formData.append('nupFileExtension', playback.fileExtension);
        NewUploaderHelper.ajaxUploder[this.tagId][boxId] = $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        if (that.options.areaPreview !== undefined) {
                            that.updateProgressBarPercent(that.boxProgressBar[boxId], percentComplete);
                        }
                        if (percentComplete >= 100) {
                            delete that.boxProgressBar[boxId]; //delete progress bar for performance.

                            //Update main progress
                            var mainPercentComplete = (that.fileTotal - that.fileStack.length) / that.fileTotal;
                            mainPercentComplete = parseInt(mainPercentComplete * 100);
                            that.updateMainProgressBarPercent(mainPercentComplete);
                        }
                    }
                }, false);
                return xhr;
            },
            url: that.options.url,
            type: that.options.actionType,
            cache: false, // important
            processData: false, // important
            contentType: false, // important
            data: formData,
            dataType: that.options.dataType,
            beforeSend: function () {
                if (that.options.areaPreview !== undefined) {
                    //set default progress bar
                    that.generateBoxProgressBar(boxId);
                }
            },
            success: function (respond, status) {
                delete NewUploaderHelper.ajaxUploder[that.tagId][boxId];
                delete NewUploaderHelper.fileStackIndex[that.tagId][boxId];

                NewUploaderHelper.setRespondOfBoxIds(that.tagId, boxId, respond); //keep respond for custom action

                $("#" + boxId + "-cancel-toolbar").remove(); //remove cancel button

                if (typeof respond === "string") {
                    that.pushSuccessFiles(playback.fileName, playback.fileType, playback.fileSize);
                    $("#output").append("<p>" + respond + "</p>");
                } else {
                    if (respond.success) {
                        that.pushSuccessFiles(playback.fileName, playback.fileType, playback.fileSize);
                        $("#" + boxId + "-progress").remove();  //remove progress button
                        that.generateBtnRemove(boxId);
                        that.generateTitle(boxId, playback.title(respond.fileInfo.fileName, respond.fileInfo.filePath));
                        that.generatePreview(boxId, playback.preview(respond.fileInfo.fileName, respond.fileInfo.filePath, respond.fileInfo.fileType));
                        that.generateCustomFormFields(boxId);
                        that.generateToolbar(boxId);
                    } else {
                        that.pushFailFiles(file, playback.fileName, playback.fileType, playback.fileSize, respond.error);
                    }

                    $("#output").append("<p>" + JSON.stringify(respond) + "</p>");
                }

                var fileInfo = {
                    file: file,
                    name: playback.fileName,
                    type: playback.fileType,
                    size: playback.fileSize,
                    extension: NewUploaderHelper.fileExtension(playback.fileName)
                };
                that.options.successFunc(that.tagId, boxId, fileInfo, respond, status); //custom success function
                that.uploadFile(); //Upload next file.
            },
            error: function (request, status, errorMsg) {
                if (status == "abort") {
                    //abort
                    that.pushFailFiles(file, playback.fileName, playback.fileType, playback.fileSize, "abort");
                } else {
                    that.pushFailFiles(file, playback.fileName, playback.fileType, playback.fileSize, errorMsg);
                }
                var fileInfo = {
                    file: file,
                    name: playback.fileName,
                    type: playback.fileType,
                    size: playback.fileSize,
                    extension: NewUploaderHelper.fileExtension(playback.fileName)
                };
                that.options.errorFunc(that.tagId, boxId, fileInfo, request, status, errorMsg); //custom error function
                that.uploadFile();  //Upload next file.
            }
        });
    },
    pushSuccessFiles: function (fileName, fileType, fileSize) {
        this.successFiles.push({
            name: fileName,
            type: fileType,
            size: fileSize,
            extension: NewUploaderHelper.fileExtension(fileName)
        });
    },
    pushFailFiles: function (file, fileName, fileType, fileSize) {
        this.failFiles.push({
            file: file,
            name: fileName,
            type: fileType,
            size: fileSize,
            extension: NewUploaderHelper.fileExtension(fileName)
        });
    },
    generateBoxId: function () {
        return this.tagId + "_" + NewUploaderHelper.randomString(5, "#aA");
    },
    generateMainProgressBar: function () {
        //have only one
        this.removeMainProgressBar();
        var $progressBarHtml = "<div class=\"progress\" style=\"margin-bottom:10px;\">"
                + "<div class=\"progress-bar progress-bar-success main-progress-bar\" role=\"progressbar\" aria-valuenow=\"0\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 0%;\">"
                + "0%"
                + "</div>"
                + "</div>";
        this.options.areaPreview.prepend($progressBarHtml, null);
        this.mainProgressBar = this.options.areaPreview.children(".progress");
        this.mainProgressBarValue = this.mainProgressBar.children(".main-progress-bar");
    },
    generateBoxProgressBar: function (boxId) {
        var $progressBarHtml = "<div class=\"progress\">"
                + "<div class=\"progress-bar progress-bar-success\" role=\"progressbar\" aria-valuenow=\"0\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 0%;\" id=\"" + boxId + "-progress-bar\">"
                + "0%"
                + "</div>"
                + "</div>";
        $("#" + boxId + "-progress").append($progressBarHtml);
        this.boxProgressBar[boxId] = $("#" + boxId + "-progress-bar");
    },
    generateBtnRemove: function (boxId) {
        if (this.options.btnRemove !== undefined) {
            var that = this;
            NewUploaderHelper.onClickBtnRemove = function (tagId, boxId) {
                if (that.options.btnRemove.hasOwnProperty("onclick")) {
//                    if (that.options.btnRemove.onclick(tagId, boxId, NewUploaderHelper.getRespondOfBoxIds(tagId, boxId))) { //must return true.
//                        NewUploaderHelper.deleteRespondOfBoxIds(tagId, boxId);
//                        $("#" + boxId).remove();
//                    }

                    that.options.btnRemove.onclick(tagId, boxId);
                } else {
                    alert("Onclick() undefined.");
                }
            };
            var name = this.options.btnRemove.hasOwnProperty("name") ? this.options.btnRemove.name : "<span class=\"glyphicon glyphicon-remove\"></span>";
            var attr = ""
            if (this.options.btnRemove.hasOwnProperty("attr")) {
                for (var a in this.options.btnRemove.attr) {
                    attr += " " + a + "=\"" + this.options.btnRemove.attr[a] + "\"";
                }
            }
            var btnRemove = "<a href=\"javascript:void(0)\" onclick=\"NewUploaderHelper.onClickBtnRemove('" + this.tagId + "','" + boxId + "')\" " + attr + ">" + name + "</a>";
            $("#" + boxId + "-btnremove").html(btnRemove);
        }
    },
    generateTitle: function (boxId, title) {
        $("#" + boxId + "-title").html(title);
    },
    generatePreview: function (boxId, preview) {
        $("#" + boxId + "-preview").html(preview);
    },
    generateCustomFormFields: function (boxId) {
        var formHtml = "";
        for (var i = 0; i < this.options.forms.length; i++) {
            var form = $(this.options.forms[i]);
            if (form.attr("id") !== undefined) {
                form.attr("id", boxId + form.attr("id"));
            }
            formHtml += $("<div />").append(form.clone()).html();
        }
        $("#" + boxId + "-form").html(formHtml);
    },
    generateToolbar: function (boxId) {
        var btnHtml = "";
        for (var k in this.options.toolbar) {
            if (this.options.toolbar.hasOwnProperty(k) && this.options.toolbar[k].hasOwnProperty("onclickFunc")) {
                var btnData = this.options.toolbar[k];

                btnHtml += "<button onclick=\"" + btnData.onclickFunc + "('" + this.tagId + "','" + boxId + "')\" type=\"button\"";
                btnHtml += " class=\"" + (btnData.hasOwnProperty("class") ? btnData.class : "btn btn-default btn-sm") + "\"";
                if (btnData.hasOwnProperty("attr")) {
                    for (var a in btnData.attr) {
                        btnHtml += " " + a + "=\"" + btnData.attr[a] + "\"";
                    }
                }
                btnHtml += ">";
                btnHtml += btnData.hasOwnProperty("name") ? btnData.name : "undefined";
                btnHtml += "</button>";
            } else {
                alert("The " + k + " tool not implement onclick().");
            }
        }
        $("#" + boxId + "-toolbar").html(btnHtml);
    },
    updateMainProgressBarPercent: function (percentComplete) {
        this.updateProgressBarPercent(this.mainProgressBarValue, percentComplete);
    },
    updateProgressBarPercent: function (progressObj, percentComplete) {
        progressObj.html("Uploaded " + percentComplete + "%")
                .css("width", percentComplete + "%")
                .attr("aria-valuenow", percentComplete);
    },
    removeMainProgressBar: function () {
        //remove old main progress bar
        var oldMainProgressBar = this.options.areaPreview.children(".progress");
        if (oldMainProgressBar.length > 0) {
            oldMainProgressBar.remove();
        }

        //remove current main progress bar
        if (this.mainProgressBar !== undefined) {
            this.mainProgressBar.remove();
        }
    },
    generateBoxItem: function (boxId, sFileName) {
        var box;
        if (this.options.template.hasOwnProperty("box")) {
            box = this.options.template.box;
        } else {
            box = "<div class=\"col-xs-12 col-sm-4 col-md-3 col-lg-3 newuploader-element\" id=\"##boxId##\">"
                    + "<div class=\"newuploader-element-content\">"
                    + "<div class=\"newuploader-thumbnail\" id=\"##boxId##-thumbnail\">"

                    + "<div class=\"newuploader-title\" id=\"##boxId##-title\"></div>"
                    + "<div class=\"newuploader-btnremove\" id=\"##boxId##-btnremove\"></div>"
                    + "<div class=\"newuploader-preview\" id=\"##boxId##-preview\"></div>"
                    + "<div class=\"newuploader-progress\" id='##boxId##-progress'>##waiting##</div>"

                    + "</div>" //newuploader-thumbnail

                    + "<div class=\"newuploader-form\" id=\"##boxId##-form\"></div>"
                    + "<div class=\"newuploader-toolbar\" id=\"##boxId##-toolbar\">"
                    + "<div style='text-align: center;' id=\"##boxId##-cancel-toolbar\">##btncancel##</div>"
                    + "</div>" //newuploaderlery-toolbar
                    + "</div>" //newuploader-element-content
                    + "</div>"; //newuploader-element
        }

        var waitingMsg;
        if (this.options.template.hasOwnProperty("waitingMsg")) {
            waitingMsg = NewUploaderHelper.replaceAll(this.options.template.waitingMsg, "##filename##", sFileName);
            waitingMsg = NewUploaderHelper.replaceAll(waitingMsg, "##tagId##", this.tagId);
            waitingMsg = NewUploaderHelper.replaceAll(waitingMsg, "##boxId##", boxId);
        } else {
            waitingMsg = "<p>" + sFileName + "</p><p>Waiting for upload.</p>";
        }

        var btnCancel;
        if (this.options.template.hasOwnProperty("waitingMsg")) {
            btnCancel = NewUploaderHelper.replaceAll(this.options.template.btnCancel, "##filename##", sFileName);
            btnCancel = NewUploaderHelper.replaceAll(btnCancel, "##tagId##", this.tagId);
            btnCancel = NewUploaderHelper.replaceAll(btnCancel, "##boxId##", boxId);
        } else {
            btnCancel = "<button onclick=\"NewUploaderHelper.abortUploader('" + this.tagId + "','" + boxId + "')\" type=\"button\" class=\"btn btn-danger btn-sm newuploader-btn-cancel\"><span class=\"glyphicon glyphicon-remove\"></span> Cancel</button>";
        }

        box = NewUploaderHelper.replaceAll(box, "##tagId##", this.tagId);
        box = NewUploaderHelper.replaceAll(box, "##boxId##", boxId);
        box = NewUploaderHelper.replaceAll(box, "##waiting##", waitingMsg);
        box = NewUploaderHelper.replaceAll(box, "##btncancel##", btnCancel);
        this.options.areaPreview.append(box);
    },
    resetFieldForm: function () {
        this.options.inputFileField.wrap('<form>').parent('form').trigger('reset');
        this.options.inputFileField.unwrap();
        this.options.inputFileField.prop("disabled", false);
    },
    clearAll: function () {
        this.resetFieldForm();
        delete NewUploaderHelper.ajaxUploder[this.tagId];
        delete NewUploaderHelper.fileStack[this.tagId];
        delete NewUploaderHelper.fileStackIndex[this.tagId];
        delete NewUploaderHelper.boxIdStack[this.tagId];
    }
};

//JQuery Function
$.fn.newUploader = function (options) {
    var defaultOptions = {
        inputFileField: this,
        areaPreview: (options.hasOwnProperty("areaPreview")) ? $(options.areaPreview) : undefined, //string of selector jquery
        showPlayback: (options.hasOwnProperty("showPlayback")) ? options.showPlayback : true,
        boxStyle: (options.hasOwnProperty("boxStyle")) ? options.boxStyle : "col-xs-12 col-sm-4 col-md-3 col-lg-3",
        url: (options.hasOwnProperty("url")) ? options.url : '',
        uploadSlots: (options.hasOwnProperty("uploadSlots")) ? options.uploadSlots : 1,
        display: (options.hasOwnProperty("display")) ? options.display : "box",
        actionType: (options.hasOwnProperty("actionType")) ? options.actionType : "POST",
        dataType: (options.hasOwnProperty("dataType")) ? options.dataType : "html",
        tagId: (options.hasOwnProperty("tagId")) ? options.tagId : "",
        template: (options.hasOwnProperty("template")) ? options.template : {},
        data: (options.hasOwnProperty("data")) ? options.data : {},
        imgMaxWidth: (options.hasOwnProperty("imgMaxWidth")) ? options.imgMaxWidth : 0,
        imgMaxHeight: (options.hasOwnProperty("imgMaxHeight")) ? options.imgMaxHeight : 0,
        imgQuality: (options.hasOwnProperty("imgQuality")) ? options.imgQuality : 80,
        imgCrop: (options.hasOwnProperty("imgCrop")) ? options.imgCrop : false,
        imgRotate: (options.hasOwnProperty("imgRotate")) ? options.imgRotate : 0,
        imgHD: (options.hasOwnProperty("imgHD")) ? options.imgHD : false,
        forms: (options.hasOwnProperty("forms")) ? options.forms : [],
        fileAccept: (options.hasOwnProperty("fileAccept")) ? options.fileAccept : [],
        fileExclusions: (options.hasOwnProperty("fileExclusions")) ? options.fileExclusions : [],
        btnRemove: (options.hasOwnProperty("btnRemove")) ? options.btnRemove : undefined,
        toolbar: (options.hasOwnProperty("toolbar")) ? options.toolbar : {},
        unsupportFileFunc: null,
        successFunc: null,
        errorFunc: null,
        completeFunc: null
    };
    defaultOptions.unsupportFileFunc = function (file) {
        if ("unsupportFileFunc" in options) {
            options.unsupportFileFunc(file, NewUploaderHelper.fileExtension(file.name));
        }
    };

    defaultOptions.successFunc = function (json, status, boxId, itemObj) {
        if (options.hasOwnProperty("successFunc")) {
            options.successFunc(json, status, boxId, itemObj);
        }
    };

    defaultOptions.errorFunc = function (request, status, errorMsg) {
        if (options.hasOwnProperty("errorFunc")) {
            options.errorFunc(request, status, errorMsg);
        }
    };

    defaultOptions.completeFunc = function (successFiles, failFiles) {
        if (options.hasOwnProperty("completeFunc")) {
            options.completeFunc(successFiles, failFiles);
        }
    };

    var inputFileField = this;

    //dropbox style
    if (inputFileField.closest(".newuploader-dropzone").length) {
        var dropzone = inputFileField.parent(".newuploader-dropzone");
        inputFileField.hover(
                function () {
                    dropzone.addClass("newuploader-dropzone-active");
                }
        ,
                function () {
                    dropzone.removeClass("newuploader-dropzone-active");
                }
        );
        inputFileField.on("dragleave", function (evt) {
            evt.stopPropagation();
            evt.preventDefault();
            dropzone.removeClass("newuploader-dropzone-active");
        });
        inputFileField.on("dragover", function (evt) {
            evt.stopPropagation();
            evt.preventDefault();
            evt.originalEvent.dataTransfer.dropEffect = 'copy'
            dropzone.addClass("newuploader-dropzone-active");
        });
        inputFileField.on("drop", function (evt) {
            evt.stopPropagation();
            evt.preventDefault();
            try {
                var files = evt.originalEvent.dataTransfer.files; // FileList object.
                new NewUploader(defaultOptions, files).run();
            } catch (e) {
                inputFileField.prop("disabled", false);
            }
        });
    }
    inputFileField.change(function (evt) {
        try {
            var files = evt.target.files; // FileList object
            new NewUploader(defaultOptions, files).run();
        } catch (e) {
            inputFileField.prop("disabled", false);
        }
    });
};

