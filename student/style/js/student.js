$(document).bind('contextmenu', function(e) {
    e.preventDefault();
});
$(window).on("load", function() {
    $('.preloader').fadeOut('slow');
});
$(document).on('click', '.mcqAnswer',function() {
    $(this).find('input').click();
});

$(document).on('click', '[data-toggle="lightbox"]', function(event) {
	event.preventDefault();
	$(this).ekkoLightbox();
});
$("#loginForm").submit(function(e) {
  e.preventDefault();
  e.stopPropagation();
  var form = $(this);
  var url = form.attr('action');
  var posting = $.post(url,form.serialize());
    posting.done(function(msg){
      if (msg == 'success') {
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'You Logged In Successfully',
          showConfirmButton: false,
          timer: 1000,
          onClose: () => {
            $(location).attr('href','?home');
            }
        })
      }
        else {
          Swal.fire({
            icon: 'error',
            title: 'Something Went Wrong!',
            text: msg,
          })
        }

    });

  });
  $("#updatePassword").submit(function(e) {
    e.preventDefault();
    var form = $(this);
    var url = form.attr('action');
    var posting = $.post(url,form.serialize());
      posting.done(function(msg){
        if (msg == 'success') {
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Your Password Has Been Updated!',
            footer: 'Please Sign In Again',
            showConfirmButton: false,
            timer: 3000,
            onClose: () => {
              $(location).attr('href','?logout');
              }
          })
        }
          else {
            Swal.fire({
              icon: 'error',
              title: 'Something Went Wrong...',
              text: msg,
            })
          }
      });
    });
    $("#updateInfo").submit(function(e) {
      e.preventDefault();
      var form = $(this);
      var url = form.attr('action');
      var posting = $.post(url,form.serialize());
        posting.done(function(msg){
          if (msg == 'success') {
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Your Information Has Been Updated!',

            })
          }
            else {
              Swal.fire({
                icon: 'error',
                title: 'Something Went Wrong...',
                text: msg,
              })
            }
        });
      });
$("#requestResetForm").submit(function(e) {
  e.preventDefault();
  var form = $(this);
  var url = form.attr('action');
  var posting = $.post(url,form.serialize());
    posting.done(function(msg){
      if (msg == 'success') {
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Account Recovery link has sent to your Email',
          footer: 'The link is valid for one hour',
          showConfirmButton: false,
          timer: 3000,

        })
      }
        else {
          Swal.fire({
            icon: 'error',
            title: 'Something Went Wrong...',
            text: msg,
          })
        }

    });
  });
  $("#StartTest").click(function(e) {
    e.preventDefault();
	var code = $(this).data('code');
	var iscode = $(this).data('iscode');
    var url = 'app/controller/test.inc.php?action=initiateTest';

    var posting = $.post(url,{
      code: code,
	  iscode: iscode
	  });
      posting.done(function(msg){
        if (msg == 'success') {
          localStorage.clear();
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Preparing Your Test',
            footer: 'Don\'t forget to submit your Answers',
            timerProgressBar: true,
            showConfirmButton: false,
            timer: 2000,
            onBeforeOpen: () => {
                Swal.showLoading()
                timerInterval = setInterval(() => {
                  const content = Swal.getContent()
                  if (content) {
                    const b = content.querySelector('b')
                    if (b) {
                      b.textContent = Swal.getTimerLeft()
                    }
                  }
                }, 100)
              },
            onClose: () => {
              $(location).attr('href','?tests&resume');
              }
          })
        }
          else {
            console.log(posting);
            Swal.fire({
              icon: 'error',
              title: 'Something Went Wrong...',
              text: msg,
            })
          }

      });
    });
function submitAnswers(){
    var url = 'app/controller/test.inc.php?action=submitAnswers';
    var posting = $.post(url,{
      questions : JSON.parse(atob(localStorage.getItem('data')))
    });
      posting.done(function(msg){
        if (msg == 'success'){
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Submitting Your Answers',
            timerProgressBar: true,
            showConfirmButton: false,
            timer: 1000,
            onClose: () => {
              $(location).attr('href','?results&id=Last');
              }
          })
          localStorage.clear();
        }
          else {
            console.log(posting);
            Swal.fire({
              icon: 'error',
              title: 'Something Went Wrong...',
              text: msg,
            })
          }

      });
  }

$("#resetForm").submit(function(e) {
  e.preventDefault();
  var form = $(this);
  var url = form.attr('action');
  var posting = $.post(url,form.serialize());
    posting.done(function(msg){
      if (msg == 'success') {
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Your Password Has Been Changed!',
          footer: 'You Can Login Now',
          showConfirmButton: false,
          timer: 3000,
          onClose: () => {
            $(location).attr('href','?login');
            }
        })
      }
        else {
          Swal.fire({
            icon: 'error',
            title: 'Something Went Wrong...',
            text: msg,
          })
        }

    });
  });
$("#registerForm").submit(function(e) {
  e.preventDefault();
  var form = $(this);
  var url = form.attr('action');
  var posting = $.post(url,form.serialize());
    posting.done(function(msg){
      if (msg == 'success') {
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Registration Was Successful',
          footer: 'You Can Login Now',
          showConfirmButton: false,
          timer: 1500,
          onClose: () => {
            $(location).attr('href','?login');
            }
        })
      }else {
          Swal.fire({
            icon: 'error',
            title: 'Something Went Wrong.',
            text: msg,
          })
        }

    });

  });
$("#checkIDForm").submit(function(e) {
  e.preventDefault();
  var form = $(this);
  var id = $(this).find('input[name="id"]').val();
  var url = form.attr('action');
  var posting = $.post(url,form.serialize());
    posting.done(function(msg){
      if (msg == 'success') {
          $(location).attr('href','?register&id=' + id);
      }else{
          Swal.fire({
            icon: 'error',
            title: 'something went wrong.',
            text: msg,
          })
        }

    });

  });

$(".leaveGroupbtn").click(function(e) {
  e.preventDefault();
  var id = $(this).data('id');
  var url = 'app/controller/group.inc.php?action=leaveGroup';
  Swal.fire({
  title: 'Are you sure?',
  text: "You won't get any tests from this Group!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Yes, Leave Group!'
}).then((result) => {
  if (result.value) {
    var posting = $.post(url,{
      id : id,
    });
    posting.done(function(msg){
      if (msg == 'success') {
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Group Left!',
          showConfirmButton: false,
          timer: 1500,
          onClose: () => {
            $(location).attr('href','?groups');
            }
        })
      }else {
          Swal.fire({
            icon: 'error',
            title: 'Something Went Wrong.',
            text: msg,
          })
        }

    });
  }
})
});

$("#joinGroupModal").submit(function(e) {
  e.preventDefault();
  var code = $('#code').val();
  var form = $(this);
  var url = form.attr('action');
  var posting = $.post(url,{
    code : code,
  });
  posting.done(function(msg){
    if (msg == 'success') {
      Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'You Have Successfully Joined The Group',
        showConfirmButton: false,
        timer: 1500,
        onClose: () => {
          $(location).attr('href','?groups');
          }
      })
    }else {
        Swal.fire({
          icon: 'error',
          title: 'Something Went Wrong.',
          text: msg,
        })
      }

  });
});
