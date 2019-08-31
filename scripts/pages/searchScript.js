$(document).ready(function () {
   let inputTimeout;
   $("#search-input").on({
      input: function () {
         startSearching();
         if(inputTimeout) clearTimeout(inputTimeout);
         inputTimeout = setTimeout(() => {
            search($(this).val(), $("#search-articles").is(':checked'), $("#search-debates").is(':checked'));
         }, 200);
      }
   });
   if($("#search-input").val().trim() !== '') $("#search-input").trigger("input");
   $("#search-debates").change(function () {
       startSearching();
       search($("#search-input").val(), $("#search-articles").is(':checked'), $("#search-debates").is(':checked'));
   });
   $("#search-articles").change(function () {
       startSearching();
       search($("#search-input").val(), $("#search-articles").is(':checked'), $("#search-debates").is(':checked'));
   });
   function startSearching() {
       $("#search-results").removeClass("empty");
       $("#search-results").removeClass("no-result");
       $("#search-results").html(" ");
       $("#search-results").addClass("loading");
   }
   function search(val, articles, debates) {
      if(val.trim() === ""){
          $("#search-results").removeClass("loading");
          $("#search-results").addClass("empty");
          return;
      }
      $.post(
      "/search",
          {
             query: val,
             debates: debates,
             articles: articles
          }
      ).done(function (data) {
          $("#search-results").removeClass("loading");
          let debates = data.debates;
          let articles = data.articles;
          if(articles){
              $("#search-results").append("<div id='articles' data-title='Articles''>");
              $("#articles").html(articles);
          }
          if(debates){
              $("#search-results").append("<div id='debates' data-title='Débats'>");
              $("#debates").html(debates);
          }
          if(!articles && !debates){
              $("#search-results").addClass("no-result");
          } else {
              $("#search-results").removeClass("no-result");
          }
      });
   }
});