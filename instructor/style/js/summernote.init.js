var sqrt = function (context) {
  var ui = $.summernote.ui;
  var button = ui.button({
    contents: 'Copy & Paste Symbols',
    tooltip: 'sqrt',
    click: function () {
      Swal.fire({
  title: 'Copy & Paste Symbols<br>',
  html:
    '<table class="symbols-table">'+
			'<tbody><tr><td>¼</td><td>½</td><td>¾</td><td>¹</td><td>²</td><td>³</td><td>°</td><td>º</td><td>‰</td><td>∝</td><td>∞</td><td>∑</td><td>±</td><td>¶</td><td>π</td><td>Π</td><td>ϖ</td><td>·</td><td>¿</td><td>×</td><td>÷</td><td>+</td><td>-</td><td>‹›</td><td>™</td><td>®</td><td>©</td></tr>'+
			'<tr><td>∼</td><td>≅</td><td>≈</td><td>≉</td><td>≆</td><td>≇</td><td>≠</td><td>≊</td><td>≋</td><td>≌</td><td>≡</td><td>≂</td><td>≃</td><td>≄</td><td>≤</td><td>≥</td><td>«</td><td>»</td><td>⇔</td><td>⇐</td><td>⇑</td><td>⇒</td><td>⇓</td><td>←</td><td>↑</td><td>→</td><td>↓</td><td>¬</td></tr>'+
			'<tr><td>√</td><td>∛</td><td>∜</td><td>£</td><td>‾</td><td>¡</td><td>¢</td><td>¤</td><td>∴</td><td>∵</td><td>∷</td><td>∸</td><td>∹</td><td>∻</td><td>∽</td><td>∾</td><td>∿</td><td>≀</td><td>≁</td><td>∺</td><td>¨</td><td>¯</td><td>¸</td><td>∫</td><td>∂</td><td>∏</td><td>∟</td><td>⁄</td><td>∨</td><td>∠</td><td>◊</td></tr>'+
			'<tr><td>Ã</td><td>Ä</td><td>Å</td><td>À</td><td>Á</td><td>Â</td><td>Æ</td><td>Ç</td><td>Ë</td><td>È</td><td>É</td><td>Ê</td><td>Ì</td><td>Í</td><td>Î</td><td>Ï</td><td>Ð</td><td>Ñ</td><td>Ò</td><td>Ó</td><td>Ô</td><td>Õ</td><td>Ö</td><td>Ø</td><td>Ù</td><td>Ú</td><td>Û</td><td>Ü</td><td>Ý</td><td>Ÿ</td><td>Þ</td></tr>'+
			'<tr><td>ã</td><td>ä</td><td>å</td><td>à</td><td>á</td><td>â</td><td>æ</td><td>ç</td><td>ë</td><td>è</td><td>é</td><td>ê</td><td>ì</td><td>í</td><td>î</td><td>ï</td><td>ð</td><td>ñ</td><td>ò</td><td>ó</td><td>ô</td><td>õ</td><td>ö</td><td>ø</td><td>ù</td><td>ú</td><td>û</td><td>ü</td><td>ý</td><td>ÿ</td><td>þ</td></tr>'+
			'<tr><td>Α</td><td>Β</td><td>Γ</td><td>Δ</td><td>Ε</td><td>Ζ</td><td>Η</td><td>Θ</td><td>Ι</td><td>Κ</td><td>Λ</td><td>Μ</td><td>Ν</td><td>Ξ</td><td>Ο</td><td>Π</td><td>Ρ</td><td>Σ</td><td>Σ</td><td>Τ</td><td>Υ</td><td>Φ</td><td>Χ</td><td>Ψ</td><td>Ω</td></tr>'+
			'<tr><td>α</td><td>β</td><td>γ</td><td>δ</td><td>ε</td><td>ζ</td><td>η</td><td>θ</td><td>ι</td><td>κ</td><td>λ</td><td>µ</td><td>ν</td><td>ξ</td><td>ο</td><td>π</td><td>ρ</td><td>σ</td><td>ς</td><td>τ</td><td>υ</td><td>φ</td><td>χ</td><td>ψ</td><td>ω</td></tr>'+
			'<tr><td>‖</td><td>⊥</td><td>∩</td><td>∪</td><td>⊂</td><td>⊃</td><td>⊆</td><td>⊇</td><td>⌈</td><td>⌉</td><td>⌊</td><td>⌋</td><td>ƒ</td><td>∬</td><td>∭</td><td>∮</td><td>∯</td><td>∰</td><td>∱</td><td>∲</td><td>∳</td></tr>'+
			'<tr><td>∈</td><td>∉</td><td>∋</td><td>∆</td><td>∇</td><td>∅</td><td>∃</td><td>∀</td><td>ª</td><td>§</td><td>¦</td><td>¥</td><td>ν</td><td>ϒ</td><td>ß</td></tr>'+
			'</tbody></table>',
  customClass: 'swal-wide',
  showCloseButton: true,
})

    }
  });

  return button.render();
}

$('.summernote').summernote({
  height: 160,
  followingToolbar: false,
  tabsize: 2,
  toolbar: [
  ['style', ['style']],
  ['font', ['bold', 'underline']],
  ['fontname', ['fontname']],
  ['color', ['color']],
  ['para', ['ul', 'ol', 'paragraph']],
  ['table', ['table']],
  ['insert', ['link', 'picture']],
  ['sqrt', ['superscript', 'subscript','sqrt']]
  ],

  buttons: {
    sqrt: sqrt,
  },
  callbacks: {
  onImageUpload: function(files, editor, welEditable) {
          sendFile(files[0],$(this));
      },
  onMediaDelete : function(target) {
          deleteFile(target[0].src);
  }
    }
});

function sendFile(file, editor, welEditable) {
  data = new FormData();
  data.append("file", file);
  $.ajax({
      data: data,
      type: "POST",
      url: "app/controller/question.inc.php?uploadImage",
      cache: false,
      contentType: false,
      processData: false,
      success: function(url) {
          var img = $("<img>").attr({src: url, width: "100%"});
          $(editor).summernote('editor.insertNode', img[0]);
      }
  });
}

function deleteFile(src) {
  data = new FormData();
  data.append("src", src);
  $.ajax({
    data: data,
    type: "POST",
    url: "app/controller/question.inc.php?deleteImage",
    cache: false,
    contentType: false,
    processData: false,
  });
}
