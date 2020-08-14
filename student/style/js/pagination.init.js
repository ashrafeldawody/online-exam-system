var alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");
var container= '';
    $(document).ready(function(){
      function getQuestions(handleData) {
        $.ajax({
          type: "GET",
          url: "app/controller/test.inc.php?action=getQuestions",
          dataType: "json",
          success: function(data) {
            handleData(data);
            if("ii" in localStorage){
              var ii = ((parseInt(window.localStorage.getItem('ii'))>0)?parseInt(window.localStorage.getItem('ii')):1);
              $('#pagination-container').pagination(ii);
            }else{
              window.localStorage.setItem('ii',1);
            }
          }
        });
        }
    getQuestions(function(questions){
      $('#pagination-container').pagination({
        dataSource: questions,
        pageSize: 1,
        showPageNumbers: false,
        showNavigator: true,
        formatResult: function(data) {
            container = '';
            $.each(data, function (z, question) {
               container += '<div class="card-header">'
                +'<strong class="card-title" id="questionInc">Question ' + ((window.localStorage.getItem('ii') == null || parseInt(window.localStorage.getItem('ii')) < 1)? '1':(window.localStorage.getItem('ii'))) +' </strong>'
               	+'<small><span class="badge badge-success float-right mt-1">'+ question.points +' Points</span></small>'
                +'</div><input type="hidden" value="'+ question.id +'" id="questionID">'
               +'<input type="hidden" value="'+ question.type +'" id="questionType"><div class="card-body"> <blockquote class="blockquote">'
               +'<p class="mb-0">'+ question.question +'</p>'
               +'</blockquote>';
               if(question.image){
                 container += '<div class="text-center"><a href="../style/images/uploads/' + question.image + '.jpg" data-toggle="lightbox"><img style="max-width: 100%;height: 250px;" class="rounded mx-auto" src="../style/images/uploads/' + question.image + '.jpg"></a></div>';
               }
               container +='<hr>';
               if(question.type == 0){
                 var char_index = 0;
                 container += '<div class="row">';
               $.each(question.answers, function(m, answer){
                 var selected = (answerExist(question.id,parseInt(answer.id)) ? 'selected':'');
                 container += '<div class="col-6 mcqChoise '+ selected +'" data-ansid="'+ answer.id +'"><div class="badge badge-primary text-wrap m-0">'+ alphabet[char_index]+ ') </div><div class="ml-5">' + answer.answer + '</div></div>';
                 char_index++;
               })
             container += '</div>';
           }else if(question.type == 3){
             var char_index = 0;
             container += '<small class="text-muted">This Question might have a multiple Answers</small><ul class="list-group">';
             container += '<div class="row">';
           $.each(question.answers, function(m, answer){
             var selected = (answerExist(question.id,parseInt(answer.id)) ? 'selected':'');
             container += '<div class="col-6 msqChoise '+ selected +'" data-ansid="'+ answer.id +'"><div class="badge badge-primary text-wrap m-0">'+ alphabet[char_index]+ ') </div><div class="ml-5">' + answer.answer + '</div></div>';
             char_index++;
           })
           container += '</div>';
         }else if(question.type == 1){
           container += '<div class="container">'+
           '											  <div class="row">'+
           '											    <div class="col selectable text-center '+ (answerExist(question.id,null,1) ? 'selected':'') +'" data-istrue="1" style="cursor: pointer;font-size: 6em;">'+
           '											      <i class="fa fa-check"></i>'+
           '											    </div>'+
           '											    <div class="col selectable text-center '+ (answerExist(question.id,null,0) ? 'selected':'') +'" data-istrue="0" style="cursor: pointer;font-size: 6em;">'+
           '											      <i class="fa fa-close"></i>'+
           '											    </div>'+
           '											  </div>';
         }else if(question.type == 2){
           container += '<div class="form-group">'+
           ' <label for="CompleteAnswer">Answer</label>'+
           ' <input type="text" class="form-control" id="CompleteAnswer" autocomplete="off" placeholder="Enter Your Answer" value="' + getTextAnswer(question.id) +'">'+
           ' </div>';
         }else if(question.type == 5){
           container += '<textarea class="form-control" id="EssayAnswer" rows="5">' + getTextAnswer(question.id) +'</textarea>';
         }else if(question.type == 4){
            container += '<ul id="MatchingAnswers">';
           $.each(question.answers, function(m, answer){
             container +=
             '<li class="list-group-item matchli">'+
                 '<div class="row">'+
                   '<div class="col-5">'+
                    '<input type="hidden" class="form-control match" value="' + answer.id + '" name="ansID">'+
                    '<span class="badge badge-primary">' + answer.answer + '</span>'+
                   '</div>'+
                   '<div class="col-2 text-center">'+
                     '<i class="fa fa-arrow-right mt-2" aria-hidden="true"></i>'+
                   '</div>'+
                   '<div class="col-5">'+
                     '<select class="form-control matchOpt" value=""><option></option>';
                     $.each(question.matches, function(n, match){
                       container += '<option ' + ((getTextAnswer(question.id,parseInt(answer.id)) == match.matchAnswer)?'selected':'') +'>' + match.matchAnswer + '</option>';
                     });
                     container += '</select>'+
                   '</div>'+
                 '</div>'+
                '</li>';
             char_index++;
           });

           container += '</ul>';
         }else if(question.type == 5){
             }

          }

         );
    },callback: function(data, pagination) {
        $('#questionsContainer').html('<div class="preloader"></div>');
        $('#questionsContainer').html(container);
        $('.preloader').fadeOut('slow');

    }
    });

  })
})
$("body").on("click", ".msqChoise", function(e) {
    $(this).toggleClass("selected");
});
$("body").on("click", ".mcqChoise", function(e) {
  if ($(this).hasClass("selected")) {
    $('.mcqChoise').removeClass("selected");
  } else {
    $('.mcqChoise').removeClass("selected");
    $(this).addClass("selected");
  }
});

$("#questionsContainer").on('click', '.selectable', function() {
		if($(this).hasClass("selected") == false) {
            $(".selectable").removeClass("selected");
            $(this).addClass("selected");
        } else {
            $(".selectable").removeClass("selected");
        }
});
$(".container").on('click', '#NextQuestion', function(){
  if ($(".paginationjs-next").hasClass("disabled")) {
          Swal.fire({
            title: 'Your Reached The Last Question',
            text: "Do your want to submit your answers now?",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Submit it!',
            cancelButtonText: 'No!',
          }).then((result) => {
            if (result.value) {
              $(this).attr("disabled", true);
              saveAnswer();
              submitAnswers();
              return false;
            }
          })
  }
  saveAnswer();
  $(".J-paginationjs-next").trigger('click');
  var str = 'Question ' + $(".J-paginationjs-nav").html();
  var q = str.replace('/','of');
  $("#questionInc").text(q);
  window.localStorage.setItem('ii',$('#pagination-container').data('pagination').model.pageNumber);


});

$(".container").on('click', '#PreviousQuestion', function(){
  var cva = $('#cva').val();
  if(cva == 0){
    Swal.fire({
      icon: 'error',
      title: 'Action is not allowed!',
      text: 'The test doesn\'t allow this action',
    })
  }else{
    saveAnswer();
    $(".J-paginationjs-previous").trigger('click');
    var str = 'Question ' + $(".J-paginationjs-nav").html();
    var q = str.replace('/','of');
    $("#questionInc").text(q);
    window.localStorage.setItem('ii',$('#pagination-container').data('pagination').model.pageNumber);

  }
});

function saveAnswer(){
      var qType = $("#questionType").val();
      var questionID = $("#questionID").val();
        clearQuestion(questionID);
      if(qType == 0 || qType == 3){
        $(".selected").each(function () {
          var answerID = $(this).data('ansid');
          if(answerID != null) insertAnswer(questionID,answerID);
          });
        }else if(qType == 1){
          var answer = $(".selected").data("istrue");
          if(answer != null) insertAnswer(questionID,null,answer);
        }else if(qType == 2){
            var answer = $('#CompleteAnswer').val();
            if(answer != null) insertAnswer(questionID,null,null,answer);
        }else if(qType == 5){
            var answer = $('#EssayAnswer').val()
            if(answer != null) insertAnswer(questionID,null,null,answer);
        }else if(qType == 4){
          $(".matchli").each(function() {
            var match = $(this).find('.match').val();
            var matchAnswer = $(this).find('.matchOpt option:selected').text();
            if (matchAnswer == 'Select your option')
              matchAnswer = '';
            if (match != '') insertAnswer(questionID,match,null,matchAnswer);
          });
        }
        }

function getTextAnswer(qID,aID = null){
  if('data' in localStorage){
    answers = JSON.parse(atob(localStorage.getItem('data')));
    var result = answers.find(({ questionID,answerID }) => questionID === parseInt(qID) && answerID === aID);
    if(result)
      return result['textAnswer'];
    else
      return '';
  }else{
    return '';
  }
}
function answerExist(qID,aID,it = 1,ta = null){
  if('data' in localStorage){
    answers = JSON.parse(atob(localStorage.getItem('data')));
    var result = answers.find(({ questionID,answerID,isTrue,textAnswer }) => questionID === parseInt(qID) && answerID === aID && isTrue === it && textAnswer === ta);
    if(result != undefined)
      return true;
    else
      return false;
  }else{
    return false;
  }
}
function clearQuestion(qID){
  if('data' in localStorage){
  answers = JSON.parse(atob(localStorage.getItem('data')));
  var answers = $.grep(answers, function(e){
     return e.questionID != parseInt(qID);
  });
  localStorage.setItem('data',btoa(JSON.stringify(answers)));
}
}
function insertAnswer(questionID,answerID,isTrue = 1,textAnswer = null){
  var answers = [];
  if('data' in localStorage){
    answers = JSON.parse(atob(localStorage.getItem('data')));
  }
  answers.push({ questionID: parseInt(questionID), answerID: parseInt(answerID),isTrue: isTrue,textAnswer:textAnswer});
  localStorage.setItem('data',btoa(JSON.stringify(answers)));
  return true;
}
