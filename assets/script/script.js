$(document).ready(function(){
    $(".dropdown-button").dropdown({
        belowOrigin: true
      });
      $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 100, // Creates a dropdown of 15 years to control year
        format: 'dd-mm-yyyy',
        formatSubmit: 'yyyy-mm-dd',
        max: '2020-31-12',
        today: 'I dag',
        clear: 'Ryd',
        close: 'Ok',
        wrap: 'picker__wrap',
        labelMonthNext: 'Næste måned',
    labelMonthPrev: 'Tidligere måned',
    labelMonthSelect: 'Vælg måned fra dropdown',
    labelYearSelect: 'Vælg år fra dropdown',
        monthsFull:
  [
    'Januar',
    'Februar',
    'Marts',
    'April',
    'Maj',
    'June',
    'July',
    'August',
    'September',
    'Oktober',
    'November',
    'December'
  ],
  monthsShort: [ 'jan', 'feb', 'mar', 'apr', 'maj', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dec' ],
  weekdaysFull:[
    'Søndag',
    'Mandag',
    'Tirsdag',
    'Onsdag',
    'Torsdag',
    'Fredag',
    'Lørdag'
  ],
  weekdaysShort: [ 'søn', 'man', 'tir', 'ons', 'tor', 'fre', 'lør' ],                  
  weekdaysLetter: 	['S','M','T','O','T','F','S'],              
        closeOnSelect: true, // Close upon selecting a date,
        container: 'fieldset', // ex. 'body' will append picker to body
      });
  
      $('select').material_select();
  
      $('.collapsible').collapsible();
  
      $(".button-collapse").sideNav();
  
      $('.modal').modal();
  
      $('.slider').slider();
      // $('.slider').slider('pause');
  // smooth scrolling
//   $("a").on('click', function(event) {

//     if (this.hash !== "") {
//       event.preventDefault();
//       var hash = this.hash;

      
//       $('html, body').animate({
//         scrollTop: $(hash).offset().top
//       }, 800, function(){

//         window.location.hash = hash;
//       });
//     } 
//   });
});