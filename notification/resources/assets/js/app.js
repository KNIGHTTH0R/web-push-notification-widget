window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');
require('daterangepicker');
require('bootstrap-select');

$(window).scroll(function() {
    if ($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
    } else {
        $(".navbar-fixed-top").removeClass("top-nav-collapse");
    }
});

$('input[name="registration_date"]').daterangepicker(
  { 
    format: 'YYYY-MM-DD',
    startDate: '2013-01-01',
    endDate: '2013-12-31'
  },
  function(start, end, label) {
    alert('A date range was chosen: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  }
);
