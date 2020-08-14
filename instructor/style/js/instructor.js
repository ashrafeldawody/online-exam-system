$(window).load(function() {
   $('.preloader').fadeOut('slow');
});
$(document).on('click', '[data-toggle="lightbox"]', function(event) {
	event.preventDefault();
	$(this).ekkoLightbox();
});

function customConfirm(lnk,conf,succ)
    {
      Swal.fire({
        title: 'Are you sure?',
        text: conf,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
      }).then((result) => {
        if (result.value) {
          Swal.fire({
            icon: 'success',
            title: 'Successful...',
            text: succ,
            onClose: () => {
              window.location.href = lnk;
            }
          })

        }
      })
    }

(function($) {
  $('.matchingTab').on('show.bs.tab', function(){
    $('.points-group').addClass('d-none');
  });
  $('.matchingTab').on('hide.bs.tab', function(){
    $('.points-group').removeClass('d-none');
  });

  $('#AddQuestion').click(function(e){
      var answerExist = 0;
      var qtype = -1;
      $('#TF.tab-pane.fade.active.show').each(function() {
         answerExist = 1;
      });
      $('#essay.tab-pane.fade.active.show').each(function() {
         answerExist = 1;
      });
      $('#MCQ.tab-pane.fade.active.show .mcqTextarea').each(function() {
        qtype = 0;
        if ((!$(this).summernote('isEmpty')) && $(this).closest("li").find("input[type=radio]").prop("checked")){
          answerExist += 1;
        }
      });
      $('#MSQ.tab-pane.fade.active.show .msqTextarea').each(function() {
        qtype = 3;
        if ((!$(this).summernote('isEmpty')) && $(this).closest("li").find("input[type=checkbox]").prop("checked")){
          answerExist += 1;
        }
      });
      $('#COMPLETE.tab-pane.fade.active.show .Completelist input').each(function() {
        if ($(this).val() != '') answerExist = 1;
      });
      $('#matching.tab-pane.fade.active.show #MatchingAnswers li').each(function() {
        if ($(this).find('.matchInp').val() != '' && $(this).find('.matchAnswerInp').val() != '' )
        answerExist = 1;
      });
      if($('#textarea-input').summernote('isEmpty')){
        Swal.fire({
           icon: 'error',
           title: 'Failed..',
           text: 'Question can\'t be empty!'
         });
         return false;
      }
      if(qtype == 0){
        if(answerExist == 0){
          Swal.fire({
             icon: 'error',
             title: 'Failed..',
             text: 'Multiple Choise question must have at least one correct answer!'
           });
           return false;
        }
      }else if(qtype == 3){
        if(answerExist < 2){
          Swal.fire({
             icon: 'error',
             title: 'Failed..',
             text: 'Multiple Select question must have at least two correct answer!'
           });
           return false;
        }
      }else{
        if(answerExist == 0){
          Swal.fire({
             icon: 'error',
             title: 'Failed..',
             text: 'Question Must have at least one valid answer!'
           });
           return false;
        }
      }

  });

  $('#assignButton').click(function(e){
    var questions = $(this).data('questions');

    if (questions == 0 && $('#RandomRules li').length == 0){
      Swal.fire({
         icon: 'error',
         title: 'Can\'t assign an empty test',
         text: 'You Must add questions to the test before assign it!'
       });
       return false;
    }else{
      if ($('li .fa-exclamation-circle').length){
        Swal.fire({
           icon: 'error',
           title: 'No Enought Questions!',
           text: 'Make sure you have enought Questions for random rules'
         });
         return false;
      }
      return true;
    }
  });

  $('.submitAssign').on('click',function(e){
    var start = moment($('input[name="startTime"]').val(), "MM/DD/YYYY h:mm a");
    var end = moment($('input[name="endTime"]').val(), "MM/DD/YYYY h:mm a");
    var diff = end.diff(start,"minutes");
    if($('#durationText').text() > diff){
      Swal.fire({
         icon: 'error',
         title: 'Oops...',
         text: 'The test duration is bigger then the difference between start and end times!'
       });
       return false;
     }else{
       return true;
     }
  })
  new ClipboardJS('.btn');

  $('#startTimePicker').datetimepicker();
  $('#endTimePicker').datetimepicker({
      useCurrent: false
  });
  $("#startTimePicker").on("change.datetimepicker", function (e) {
      $('#endTimePicker').datetimepicker('minDate', e.date);
  });
  $("#endTimePicker").on("change.datetimepicker", function (e) {
      $('#startTimePicker').datetimepicker('maxDate', e.date);
  });
  $('.datetimepicker-input').each(function() {
    var date = moment($(this).data('datetime'), 'YYYY-MM-DD hh:mm a').toDate();
    $(this).datetimepicker({date:date});
  });
$(document).on('click','.mcqCheckInput',function(){
  	$('.mcqCheckInput').each(function(){
    	$(this).prop('checked', false);
      $(this).closest('li').removeClass('correctAnswer');
    });
	$(this).prop('checked', true);
  $(this).closest('li').addClass('correctAnswer');

});
$('.custom-file-input').on('change',function(){
	var fileName = $(this).val().replace('C:\\fakepath\\', "");
	$(this).next('.custom-file-label').html(fileName);
})
$(document).on('click','.msqCheckInput',function(){
  $(this).closest('li').toggleClass('correctAnswer');

});
$('ul').on('change','#qtype',function(){
  if($(this).val() == 0)
    $('.mcqCheckInput').attr('type','radio');
  else if($(this).val() == 3)
    $('.mcqCheckInput').attr('type','checkbox');
})
  "use strict";
  $('.showStudentData').click(function(e) {
    e.preventDefault();
    var id =    $(this).data('id');
    var name =  $(this).data('name');
    var email = $(this).data('email');
    var phone = $(this).data('phone');
    Swal.fire({
  title: 'Student Information',
  html:
    '<div class="form-group"><label>Student ID</label><input type="text" class="form-control" value="'+ id +'" disabled></div>'
    + '<div class="form-group"><label>Name</label><input type="text" class="form-control" value="'+ name +'" disabled></div>'
    + '<div class="form-group"><label>Email address</label><input type="email" class="form-control" value="'+ email +'" disabled></div>'
    + '<div class="form-group"><label>Phone Number</label><input type="text" class="form-control" value="'+ phone +'" disabled></div>',
  focusConfirm: false,
})

  });

$('.showLink').click(function(e) {
    e.preventDefault();
    var id =    $(this).data('id');
    var link =  $(this).data('link');
    var duration =  $(this).data('duration');
    var random =  $(this).data('random');
    var passPercent =  $(this).data('pass');
    var sendToMail =  $(this).data('sendtostudent');
    var startTime = moment($(this).data('starttime'), 'YYYY-MM-DD hh:mm').format('YYYY-MM-DD hh:mm a');
    var endTime = moment($(this).data('endtime'), 'YYYY-MM-DD hh:mm').format('YYYY-MM-DD hh:mm a');
    Swal.fire({
  title: 'Link Details',
  customClass: 'swal-wide',
  html:
    '<div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Link</span></div><input type="text" id="lnk" class="form-control" value="'+ link +'" readonly><div class="input-group-append"><button class="btn btn-outline-secondary" type="button" data-clipboard-target="#lnk">Copy Link</button></div></div>'
    + '<div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Start Time</span></div><input type="text" class="form-control" value="'+ startTime +'" readonly></div>'
    + '<div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">End Time</span></div><input type="text" class="form-control" value="'+ endTime +'" readonly></div>'
    + '<div class="text-left">'
    + '<label class="control-label mb-3">Test Duration:  </label><label class="control-label mb-3"><span class="badge">'+ duration +'</span></label><br>'
    + '<label class="control-label mb-3">Pass Percent:  </label><label class="control-label mb-3"><span class="badge">'+ passPercent +'</span></label><br>'
    + '<label class="control-label mb-3">Random Selection:  </label><label class="control-label mb-3"><span class="badge">'+ random +'</span></label><br>'
    + '<label class="control-label mb-3">Send To Mail:  </label><label class="control-label mb-3"><span class="badge">'+ sendToMail +'</span></label><br>'
    + '</div>',
  focusConfirm: false,
})

  });

  $("#createVoucherform").submit(function(e) {
    e.preventDefault();
    e.stopPropagation();
    var form = $(this);
    var url = form.attr('action');
    var posting = $.post(url, $("#createVoucherform").serialize(), function(data) {
    }).done(function(msg) {
      if (msg.startsWith('http')) {
        (async () => {
        const { value: text } = await Swal.fire({
          icon:'success',
          title: 'Link Created Successfully',
          text: "Use the link below to give access to test",
          input: 'text',
          inputValue: msg,
          onClose: () => {
            $(location).attr('href','?tests');
            }
        })

        })()
      }else{
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Something Wrong Happened',
          footer: msg
        })
      }


    })

    });

  $('#RandomRules').on('click', '.deleteCrsQuestions', function(e) {
    e.preventDefault();
    var li = $(this).closest("li");
    var cID = $(this).data('cid');
    var diff = $(this).data('diff');
    var tID = $('#tstID').val();
    Swal.fire({
      title: 'Are you sure?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes'
    }).then((result) => {
      if (result.value) {
        $.post("app/controller/test.inc.php?deleteRandomRule",{
              testID : tID,
              courseID : cID,
              diff : diff
        }, function(data,status){
          li.remove();
        });
      }
    })

  });

  $(document).on('click', '.deleteAnswer', function(e) {
    e.preventDefault();
    var completeanswer = $(this).closest('.completeanswer');
    var li = $(this).closest("li");
    var ansID = $(this).data('ansid');
    if(ansID){
    Swal.fire({
      title: 'Are you sure?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes'
    }).then((result) => {
      if (result.value) {
        $.post("app/controller/question.inc.php?deleteAnswer",{
              ansID : ansID
        }, function(data,status){
          completeanswer.remove();
          li.remove();
        });
      }
    })
  }else{
    completeanswer.remove();
    li.remove();
  }
  });
  $("#createRandomRule").click(function() {
    var num = $('#numofQ').val();
    var diff = $('#difficulty').val();
    var diffText = $('#difficulty').children("option:selected").text();
    var cat = $('#select :selected').text();
    var catval = $('#select :selected').val();
    var tID = $('#tstID').val();
      $.post("app/controller/test.inc.php?addRandomRule", {
        testID: tID,
        courseID: catval,
        diff: diff,
        Count: num
      }, function(data, status) {
        if(data=='success'){
          var listItems = $('#RandomRules .crsname');
          listItems.each(function(idx, li) {
            var crs = $(li).text();
            var difficulty = $(li).closest('li').find('.diffspan').text();
            if (crs === cat && diffText === difficulty) {
              $(li).parent().parent().parent().remove();
            }
          });

        $("#RandomRules").append('<li class="list-group-item">' +
          '<div class="row">' +
          '<div class="col-auto">' +
          '  Adding' +
          '</div>' +
          '<div class="col-auto">' +
          '  <span class="badge badge-primary">' + num +'</span>' +
          '</div>' +
          '<div class="col-auto">' +
          '  <span class="badge badge-info diffspan">' + ((diff == 1)?'Easy':((diff == 2)?'Moderate':'Hard')) +'</span>' +
          '</div>' +
          '<div class="col-auto">' +
          '  Questions From' +
          '</div>' +
          '<div class="col-auto">' +
          ' <span class="badge badge-info crsname">' + cat + '</span>'+
          '</div>' +
          '<i class="fa fa-minus-circle deleteCrsQuestions" data-cid = "' + catval + '" data-diff = "' + diff + '" style="font-size:33px;color:red;cursor:pointer;"></i>' +
          '</div>' +
          '</li>');
        }else{
          Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: data
          })
        }
      });
  });
  $('#updateAssignedTest').on('show.bs.modal', function(e) {
    var gID = $(e.relatedTarget).data('id');
    var tID = $(e.relatedTarget).data('testid');
    var startTime = $(e.relatedTarget).data('starttime');
    var endTime = $(e.relatedTarget).data('endtime');
    var duration = $(e.relatedTarget).data('duration');
    var viewAnswers = $(e.relatedTarget).data('viewanswers');
    $(e.currentTarget).find('input[name="testID"]').val(tID);
    $(e.currentTarget).find('input[name="groupID"]').val(gID);
    $(e.currentTarget).find('input[name="startTime"]').val(startTime);
    $(e.currentTarget).find('input[name="endTime"]').val(endTime);
    $(e.currentTarget).find('input[name="duration"]').val(duration);
    if(viewAnswers == 2){
      $(e.currentTarget).find('#sh1').prop("checked", 1);
    }else if(viewAnswers == 1){
      $(e.currentTarget).find('#sh2').prop("checked", 1);
    }else{
      $(e.currentTarget).find('#sh3').prop("checked", 1);
    }
  });

    $('.deleteAssignedTest').on('click',function(e){
      var gID = $(this).data('gid');
      Swal.fire({
        title: 'Are you sure?',
        text: "The Assigned Test Will be removed",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.value) {
          window.location.href = "app/controller/assign.inc.php?deleteAssignedTest=" + gID;
        }
      })
    });
  $('#editcourse').on('show.bs.modal', function(e) {
    var name = $(e.relatedTarget).data('cname');
    var courseid = $(e.relatedTarget).data('cid');
    var parentid = $(e.relatedTarget).data('pid');
    var disabled = $(e.relatedTarget).data('cdisabled');
    $(e.currentTarget).find('input[name="courseName"]').val(name);
    $(e.currentTarget).find('input[name="id"]').val(courseid);
    $(e.currentTarget).find('select[name="course"]').prop("disabled", disabled);
    $(e.currentTarget).find('select[name="course"]').val(parentid);
  });
  $('#editgroup').on('show.bs.modal', function(e) {
    var name = $(e.relatedTarget).data('gname');
    var groupid = $(e.relatedTarget).data('gid');
    $(e.currentTarget).find('input[name="groupName"]').val(name);
    $(e.currentTarget).find('input[name="id"]').val(groupid);
  });
  $('#editTest').on('show.bs.modal', function(e) {
    var name = $(e.relatedTarget).data('tname');
    var testid = $(e.relatedTarget).data('tid');
    var course = $(e.relatedTarget).data('tcourse');
    $(e.currentTarget).find('input[name="testid"]').val(testid);
    $(e.currentTarget).find('input[name="testName"]').val(name);
    $(e.currentTarget).find('input[name="oldtestName"]').val(name);
    $(e.currentTarget).find('select[name="Course"]').val(course);
  });
  $('#menuToggle').on('click', function(event) {
    $('body').toggleClass('open');
  });
  $("#MCQaddChoise").click(function() {
    var qtype = document.getElementById("qtype").value;
    var lastAnswer = ++document.getElementById("MCQlastAnswer").value;
    $('.MCQchoiseslist').append('<li class="list-group-item">' +
      '<div class="row"><div class="col-6"><div class="icheck-success">' +
      '<input type="radio" class="mcqCheckInput" id="MCQcheck' + lastAnswer + '" name="MCQanswer[' + lastAnswer + '][isCorrect]" value="1">' +
      ' <label for="MCQcheck' + lastAnswer + '">Correct Answer</label>' +
      '	</div></div>' +
      '	<div class="col-lg-6"><i class="fa fa-trash deleteAnswer float-right mb-3 text-danger"></i></div></div>' +
      '	<hr>' +
      '	<textarea rows="4" placeholder="Answer ' + lastAnswer + '..." name="MCQanswer[' + lastAnswer + '][answertext]" class="form-control mcqTextarea moreansmcq' + lastAnswer + '"></textarea>' +
      '<br>' +
      '	</div></div>'

      +
      '	</li><br>');
      $('.moreansmcq' + lastAnswer).summernote();
    document.getElementById("MCQlastAnswer").value = lastAnswer;
  })
  $("#addChoise").click(function() {
    var qtype = document.getElementById("qtype").value;
    var lastAnswer = ++document.getElementById("MCQlastAnswer").value;
    $('.choiseslist').append('<li class="list-group-item">' +
      '<div class="row"><div class="col-6"><div class="icheck-success">' +
      '<input type="' + ((qtype == 0) ? 'radio' : 'checkbox') + '" class="answerCheck ' + ((qtype == 0) ? 'mcqCheckInput' : 'msqCheckInput' ) + '" id="isrightcheck' + lastAnswer + '" name="Qanswer[' + lastAnswer + '][isCorrect]" value="1">' +
      ' <label for="isrightcheck' + lastAnswer + '">Correct Answer</label>' +
      '	</div></div>' +
      '	<div class="col-lg-6"><i class="fa fa-trash deleteAnswer float-right mb-3 text-danger"></i></div></div>' +
      '	<hr>' +
      '	<textarea rows="2" placeholder="Answer ' + lastAnswer + '..." name="Qanswer[' + lastAnswer + '][answertext]" class="form-control moreansmcq' + lastAnswer + '"></textarea>' +
      '<br>' +
      '</div>'

      +'</li><br>');
      $('.moreansmcq' + lastAnswer).summernote();
    document.getElementById("MCQlastAnswer").value = lastAnswer;
  })
  $("#MSQaddChoise").click(function() {
    var qtype = document.getElementById("qtype").value;
    var lastAnswer = ++document.getElementById("MSQlastAnswer").value;
    $('.MSQchoiseslist').append('<li class="list-group-item">' +
      '<div class="row"><div class="col-6"><div class="icheck-success">' +
      '<input type="checkbox" class="msqCheckInput" id="isrightcheck' + lastAnswer + '" name="MSQanswer[' + lastAnswer + '][isCorrect]" value="1">' +
      ' <label for="isrightcheck' + lastAnswer + '">Correct Answer</label>' +
      '	</div></div>' +
      '	<div class="col-lg-6"><i class="fa fa-trash deleteAnswer float-right mb-3 text-danger"></i></div></div>' +
      '	<hr>' +
      '	<textarea rows="2" placeholder="Answer ' + lastAnswer + '..." name="MSQanswer[' + lastAnswer + '][answertext]" class="form-control msqTextarea moreans' + lastAnswer + '"></textarea>' +
      '<br>' +
      '</div></li><br>');
      $('.moreans' + lastAnswer).summernote();
    document.getElementById("MSQlastAnswer").value = lastAnswer;
  })

  $("#addComAnswer").click(function() {
    var lastCompleteAnswer = ++document.getElementById("lastCompleteAnswer").value;
    $('.Completelist').append('<div class="row form-group completeanswer">' +
      '<div class="col-12 col-md-9"><input type="text" id="answer' + lastCompleteAnswer + '" name="Canswer[' + lastCompleteAnswer + '][answertext]" placeholder="Answer ' + lastCompleteAnswer + '" class="form-control"></div>' +
      '<i class="fa fa-trash deleteAnswer float-right mb-3 text-danger"></i>' +
      '</div>')
    document.getElementById("lastCompleteAnswer").value = document.getElementById("lastCompleteAnswer").value++;
  })

  $("#addMatch").click(function() {
    $('#MatchingAnswers').append('<li class="list-group-item">'
       +'<div class="row">'
         +'<div class="col-4">'
           +'<input type="text" class="form-control" name="match[]">'
         +'</div>'
           +'<i class="fa fa-arrow-right mt-2" aria-hidden="true"></i>'
         +'<div class="col-4">'
           +'<input type="text" class="form-control" name="matchAnswer[]">'
         +'</div>'
         +'<div class="col-2">'
           +'<input type="number" class="form-control" placeholder="Points" value="1" name="matchPoints[]">'
         +'</div>'
         +'<i class="fa fa-trash deleteAnswer float-right mb-3 text-danger"></i>'
       +'</div>'
     +'</li>')
  });

})(jQuery);

function readURL(input, dist) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $(dist).attr('src', e.target.result);
      $(dist).removeClass('imgboxdisplaynon');
    }

    reader.readAsDataURL(input.files[0]);
  }
}
function RemoveById(input, i, id) {
  document.getElementById(input).remove();
  $('.choiseslist').append('<input type="hidden" name="Qanswer[' + i + '][Delete]" value="' + id + '">')
}

function generateCode(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}

function sliceSize(dataNum, dataTotal) {
  return (dataNum / dataTotal) * 360;
}

function addSlice(id, sliceSize, pieElement, offset, sliceID, color) {
  $(pieElement).append("<div class='slice "+ sliceID + "'><span></span></div>");
  var offset = offset - 1;
  var sizeRotation = -179 + sliceSize;

  $(id + " ." + sliceID).css({
    "transform": "rotate(" + offset + "deg) translate3d(0,0,0)"
  });

  $(id + " ." + sliceID + " span").css({
    "transform"       : "rotate(" + sizeRotation + "deg) translate3d(0,0,0)",
    "background-color": color
  });
}

function iterateSlices(id, sliceSize, pieElement, offset, dataCount, sliceCount, color) {
  var
    maxSize = 179,
    sliceID = "s" + dataCount + "-" + sliceCount;

  if( sliceSize <= maxSize ) {
    addSlice(id, sliceSize, pieElement, offset, sliceID, color);
  } else {
    addSlice(id, maxSize, pieElement, offset, sliceID, color);
    iterateSlices(id, sliceSize-maxSize, pieElement, offset+maxSize, dataCount, sliceCount+1, color);
  }
}

function createPie(id) {
  var
    listData      = [],
    listTotal     = 0,
    offset        = 0,
    i             = 0,
    pieElement    = id + " .pie-chart__pie"
    dataElement   = id + " .pie-chart__legend"

    color         = [
      "#28a745",
      "#dc3545"
    ];


  $(dataElement+" span").each(function() {
    listData.push(Number($(this).html()));
  });

  for(i = 0; i < listData.length; i++) {
    listTotal += listData[i];
  }

  for(i=0; i < listData.length; i++) {
    var size = sliceSize(listData[i], listTotal);
    iterateSlices(id, size, pieElement, offset, i, 0, color[i]);
    $(dataElement + " li:nth-child(" + (i + 1) + ")").css("border-color", color[i]);
    offset += size;
  }
}

function shuffle(a) {
    var j, x, i;
    for (i = a.length; i; i--) {
        j = Math.floor(Math.random() * i);
        x = a[i - 1];
        a[i - 1] = a[j];
        a[j] = x;
    }

    return a;
}

function createPieCharts() {
  createPie('.pieID--micro-skills' );
  createPie('.pieID--categories' );
  createPie('.pieID--operations' );
}

createPieCharts();
//Charts End


$(function() {
	$('#progress').circliful();
});


//Note -- I removed the respondCanvas function from the circiful library
/* PROGRESS CIRCLE COMPONENT */
(function ($) {

    $.fn.circliful = function (options, callback) {

        var settings = $.extend({
            // These are the defaults.
            startdegree: 0,
            fgcolor: "#556b2f",
            bgcolor: "#eee",
            fill: false,
            width: 15,
            dimension: 200,
            fontsize: 15,
            percent: 50,
            animationstep: 1.0,
            iconsize: '20px',
            iconcolor: '#999',
            border: 'default',
            complete: null,
            bordersize: 10
        }, options);

        return this.each(function () {

            var customSettings = ["fgcolor", "bgcolor", "fill", "width", "dimension", "fontsize", "animationstep", "endPercent", "icon", "iconcolor", "iconsize", "border", "startdegree", "bordersize"];

            var customSettingsObj = {};
            var icon = '';
            var endPercent = 0;
            var obj = $(this);
            var fill = false;
            var text, info;

            obj.addClass('circliful');

            checkDataAttributes(obj);

            if (obj.data('text') != undefined) {
                text = obj.data('text');

                if (obj.data('icon') != undefined) {
                    icon = $('<i></i>')
                        .addClass('fa ' + $(this).data('icon'))
                        .css({
                            'color': customSettingsObj.iconcolor,
                            'font-size': customSettingsObj.iconsize
                        });
                }

                if (obj.data('type') != undefined) {
                    type = $(this).data('type');

                    if (type == 'half') {
                        addCircleText(obj, 'circle-text-half', (customSettingsObj.dimension / 1.45));
                    } else {
                        addCircleText(obj, 'circle-text', customSettingsObj.dimension);
                    }
                } else {
                    addCircleText(obj, 'circle-text', customSettingsObj.dimension);
                }
            }

            if ($(this).data("total") != undefined && $(this).data("part") != undefined) {
                var total = $(this).data("total") / 100;

                percent = (($(this).data("part") / total) / 100).toFixed(3);
                endPercent = ($(this).data("part") / total).toFixed(3)
            } else {
                if ($(this).data("percent") != undefined) {
                    percent = $(this).data("percent") / 100;
                    endPercent = $(this).data("percent")
                } else {
                    percent = settings.percent / 100
                }
            }

            if ($(this).data('info') != undefined) {
                info = $(this).data('info');

                if ($(this).data('type') != undefined) {
                    type = $(this).data('type');

                    if (type == 'half') {
                        addInfoText(obj, 0.9);
                    } else {
                        addInfoText(obj, 1.25);
                    }
                } else {
                    addInfoText(obj, 1.25);
                }
            }

            $(this).width(customSettingsObj.dimension + 'px');

            var canvas = $('<canvas></canvas>').attr({
                width: customSettingsObj.dimension,
                height: customSettingsObj.dimension
            }).appendTo($(this)).get(0);

            var context = canvas.getContext('2d');
            var container = $(canvas).parent();
            var x = canvas.width / 2;
            var y = canvas.height / 2;
            var degrees = customSettingsObj.percent * 360.0;
            var radians = degrees * (Math.PI / 180);
            var radius = canvas.width / 2.5;
            var startAngle = 2.3 * Math.PI;
            var endAngle = 0;
            var counterClockwise = false;
            var curPerc = customSettingsObj.animationstep === 0.0 ? endPercent : 0.0;
            var curStep = Math.max(customSettingsObj.animationstep, 0.0);
            var circ = Math.PI * 2;
            var quart = Math.PI / 2;
            var type = '';
            var fireCallback = true;
            var additionalAngelPI = (customSettingsObj.startdegree / 180) * Math.PI;

            if ($(this).data('type') != undefined) {
                type = $(this).data('type');

                if (type == 'half') {
                    startAngle = 2.0 * Math.PI;
                    endAngle = 3.13;
                    circ = Math.PI;
                    quart = Math.PI / 0.996;
                }
            }

            /**
             * adds text to circle
             *
             * @param obj
             * @param cssClass
             * @param lineHeight
             */
            function addCircleText(obj, cssClass, lineHeight) {
                $("<span></span>")
                    .appendTo(obj)
                    .addClass(cssClass)
                    .text(text)
                    .prepend(icon)
                    .css({
                        'line-height': lineHeight + 'px',
                        'font-size': customSettingsObj.fontsize + 'px'
                    });
            }

            /**
             * adds info text to circle
             *
             * @param obj
             * @param factor
             */
            function addInfoText(obj, factor) {
                $('<span></span>')
                    .appendTo(obj)
                    .addClass('circle-info-half')
                    .css(
                        'line-height', (customSettingsObj.dimension * factor) + 'px'
                    )
                    .text(info);
            }

            /**
             * checks which data attributes are defined
             * @param obj
             */
            function checkDataAttributes(obj) {
                $.each(customSettings, function (index, attribute) {
                    if (obj.data(attribute) != undefined) {
                        customSettingsObj[attribute] = obj.data(attribute);
                    } else {
                        customSettingsObj[attribute] = $(settings).attr(attribute);
                    }

                    if (attribute == 'fill' && obj.data('fill') != undefined) {
                        fill = true;
                    }
                });
            }

            /**
             * animate foreground circle
             * @param current
             */
            function animate(current) {

                context.clearRect(0, 0, canvas.width, canvas.height);

                context.beginPath();
                context.arc(x, y, radius, endAngle, startAngle, false);

                context.lineWidth = customSettingsObj.bordersize + 1;

                context.strokeStyle = customSettingsObj.bgcolor;
                context.stroke();

                if (fill) {
                    context.fillStyle = customSettingsObj.fill;
                    context.fill();
                }

                context.beginPath();
                context.arc(x, y, radius, -(quart) + additionalAngelPI, ((circ) * current) - quart + additionalAngelPI, false);

                if (customSettingsObj.border == 'outline') {
                    context.lineWidth = customSettingsObj.width + 13;
                } else if (customSettingsObj.border == 'inline') {
                    context.lineWidth = customSettingsObj.width - 13;
                }

                context.strokeStyle = customSettingsObj.fgcolor;
                context.stroke();

                if (curPerc < endPercent) {
                    curPerc += curStep;
                    requestAnimationFrame(function () {
                        animate(Math.min(curPerc, endPercent) / 100);
                    }, obj);
                }

                if (curPerc == endPercent && fireCallback && typeof(options) != "undefined") {
                    if ($.isFunction(options.complete)) {
                        options.complete();

                        fireCallback = false;
                    }
                }
            }

            animate(curPerc / 100);

        });
    };
}(jQuery));
