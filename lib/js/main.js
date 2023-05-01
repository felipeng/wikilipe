// Resposive view, change header
$("#search_icon").click(function (){
  $("#tab-edit, #tab-preview, #page_path, #search").toggle("fast")
});

// Insert image
function insertImg(img){
  showUpload();
  insertTag("image",img);
}

// Copy code to clipboard
function clipboard(elem){

  $(elem).append("<textarea id='clipboard' style='left: -9999px; position: absolute;'></textarea>");
  const code = elem.parentElement.getElementsByTagName("code")[0].innerText;
  $("#clipboard").val(code).select();
  document.execCommand("copy");
}

// Insert html tag
function insertTag(tagname,img) {
  const tags = {
    headline1:      "\n# Headline1\n",
    bold:           " **bold** ",
    italic:         " *italic* ",
    strikethrough:  " ~~strikethrough~~ ",
    internallink:   " [Internal Link](dir/page) ",
    externallink:   " [External Link](http://www.link.com) ",
    blockcode:      "\n```\necho $VAR\n```\n",
    code:           " `echo $VAR` ",
    table:          "\n| Column1 | Column2 | Column3 |\n|---------|:-------:|--------:|\n| row1    | row1    | row1    |\n| row2    | row2    | row2    |\n",
    quote:          "\n>line one\n>line two\n>line three\n",
    orderedlist:    "\n1. Item 1\n    1. Sub-item 1\n        1. Sub-sub-item 1\n    1. Sub-item 2\n1. Item 2\n",
    unorderedlist:  "\n* Item 1\n    * Sub-item 1\n        * Sub-sub-item 1\n    * Sub-item 2\n* Item 2\n",
    tasklist:       "\n- [x] This task is done\n- [ ] This is still pending\n",
    horizontalrule: "\n---\n",
    image:          " ![" + img + "](data/imgs/" + img + ") ",
  }

  const code = document.getElementById("code");
  const tag = tags[tagname];
  const position = code.selectionStart;
  const positionAfter = position+tag.length;
  code.focus()
  code.value = code.value.substring(0,position)+tag+code.value.substring(position,code.value.length);
  code.setSelectionRange(positionAfter,positionAfter);

  // auto update preview
  code.onkeyup();
}

// Show upload dialog
function showUpload(){
  // replace data-src to src (load images on demand)
  let imgs = document.getElementsByClassName("images");
  for (let i=0; i <imgs.length; i++){
    imgs[i].src = imgs[i].getAttribute("data-src");
  }

  $("#page-images").toggle()
  $("#mask").toggle()
}

// goTop
function goTop(){
  $("article").animate({
    scrollTop : 0
      }, 500);
}

// Show tabs and pages
function showTab(name, string){

  const tabButtons = document.getElementsByClassName("tab-button");

  // tabs
  for (const tabButton of tabButtons) {
    tabButton.classList.remove("selected");
  }
  document.getElementById("tab-" + name).classList.add("selected");

  // pages
  if (name == "side"){
    document.getElementsByTagName("article")[0].style.display = "flex";

    document.getElementById("page-edit").style.display = "block";
    document.getElementById("page-preview").style.display = "block";

    document.getElementById("page-edit").classList.add("edit-sbs");
    document.getElementById("page-preview").classList.add("preview-sbs");
  } else {
    document.getElementsByTagName("article")[0].style.display = "block";

    document.getElementById("page-edit").style.display = "none";
    document.getElementById("page-preview").style.display = "none";

    document.getElementById("page-edit").classList.remove("edit-sbs");
    document.getElementById("page-preview").classList.remove("preview-sbs");

    document.getElementById("page-" + name).style.display = "block";
  }
  Jump(string.innerHTML);
}

function Jump(string){
  const code = document.getElementById("code");

  if (string == null) {
    string = "";
  }
  // pega o texto e move o cursor para a posicao correta
  const stringPosition = code.value.indexOf("# " + string);
  code.setSelectionRange(stringPosition,stringPosition);

  // pega as linhas e monta um array e retorna o tamanho do array
  const cursorLine = code.value.substr(0,code.selectionStart).split("\n").length - 1;

  // move o scroll para a linha textarea = line-height = 17 (dont change this)
  code.scrollTop = (cursorLine * 17);
}

// If the page does not exist, create it
function newPage(page){
  $("#code").text("[toc]\n# " + page);
  showTab("edit","");
}

// showdown.js
showdown.setOption("tables", true);                     // tables
showdown.setOption("tablesHeaderId", true);             //
showdown.setOption("noHeaderId", false);                // enable anchor on h[1-6] tags
showdown.setOption("prefixHeaderId", "anchor_");        // add anchor_ prefix on h[1-6] tags
showdown.setOption("tasklists", true);                  // enable tasklist
showdown.setOption("strikethrough", true);              // enable crossed out text: ~~text~~
showdown.setOption("simpleLineBreaks", true);           // enable line breake
showdown.setOption("parseImgDimension", true);          //
showdown.setOption("smoothLivePreview", true);          // not tested yet
showdown.setOption("simplifiedAutoLink", true);         // enable links automatic recognition
showdown.setOption("parseImgDimensions", true);         // enable image dimensions
showdown.setOption("literalMidWordUnderscores", true);  // underscore on links

// Convert markdown
function text2markdown(source,target){
  const converter = new showdown.Converter({ extensions: ["toc"]});
  const text = document.getElementById(source).value.replace(/]\((?!http|\?upload|data)/g,"](?p="); // and replace internal link with: ?p=
  $("#"+target).html(converter.makeHtml(text));
}

// Convert markdown to html
function convert(){
  // Convert content
  text2markdown('code','page-preview');

  // Call highlighjs, convert all pre code tag into highlightjs block
  $('pre code').each(function(i, block) {
    hljs.highlightBlock(block);
  });

  // Add "Table of Contents" in ToC element
  if ($("#code:contains('[toc]')")) {
    $(".toc").prepend('<a href="javascript:" onclick="$(\'.toc ul\').slideToggle(\'fast\');">Table of Contents</a>')
  }
}

// Markdown to HTML on side by side mode
const source = document.getElementById('code');
  source.onkeyup = function(){
  convert();
};

// show icon goTop
$('article').scroll(function() {
    if ($(this).scrollTop() >= 50) {
        $('#gotop').fadeIn(200);
    } else {
        $('#gotop').fadeOut(200);
    }
});

// OnLoad function
window.onload = function(){
  // Call markdown converter
  convert();

  // Convert menu
  text2markdown('menu-text','menu');

  // load the default page view
  showTab('preview','');

  // add onclick attribute in all h tags except on search page
  if (! window.location.search.substring(1).startsWith("search=")) {
    $('#page-preview h1, h2, h3, h4, h5, h6').attr('onclick',"showTab('edit',this)")
    $('h1, h2, h3, h4, h5, h6').css("cursor","pointer")
  }

  $('pre').prepend("<div class='clipboard' onclick='clipboard(this);'><img class='clipboard_img' src='lib/imgs/clipboard.png'></div>");
};
