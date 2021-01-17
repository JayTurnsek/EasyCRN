let copyCodes = [];

// Calendar init
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    height: 450,
    dayHeaderFormat: { weekday: 'long' },
    initialView: 'timeGridWeek',
    slotMinTime: "8:00:00",
    slotMaxTime: "22:00:00",
    allDaySlot: false,
    hiddenDays: [0, 6],
    headerToolbar: {
      start: '',
      center: '',
      end: ''
    },
  });
  calendar.render();

  // Add by button init
  var buttons = document.getElementsByClassName('btn-sm btn-primary');
  for (var i = 0; i < buttons.length; i++) {
    (function(){
      var curCRN = buttons[i].getAttribute('id').substr(3);
      var curTitle = courseArr[curCRN][0];
      var times = courseArr[curCRN][1].split('/');
      var clicked = false;

      // Grey hover when adding
      buttons[i].addEventListener('mouseleave', function(){
        if (clicked === false){
          for (var i = 0; i < times.length; i++){
            var e = calendar.getEventById(curCRN);
            e.remove();
          }
        }
      });
      buttons[i].addEventListener('mouseover', function(){
        var i;
        if (clicked === false){
          for (i = 0; i < times.length; i++){
            calendar.addEvent({
              title: curTitle,
              daysOfWeek: [ timeArr[times[i]][2] ],
              startTime: timeArr[times[i]][0],
              endTime: timeArr[times[i]][1],
              color: "#8CCAFF",
              id: curCRN,
            });
          }
        }
      });

      // Actual event adding
      buttons[i].addEventListener('click', function(){
        var i;
        var span = 'span'.concat(curCRN);
        for (i = 0; i < times.length; i++){
          var e = calendar.getEventById(curCRN);
          e.remove();
        }
        clicked = true;
        for (i = 0; i < times.length; i++){
          calendar.addEvent({
            title: curTitle,
            daysOfWeek: [ timeArr[times[i]][2] ],
            startTime: timeArr[times[i]][0],
            endTime: timeArr[times[i]][1],
            id: curCRN,
          });
        }

        // Adding to copy-able list of CRNs
        copyCodes.push(curCRN);
        var listText = '';
        for (i = 0; i < copyCodes.length; i++) {
          listText += copyCodes[i] + "\n";
        }
        document.getElementById('finalList').value = listText;



        // My Course List Handling
        var span = 'span'.concat(curCRN);
        document.getElementById(span).addEventListener('click', function() {
          for (var i = 0; i < times.length; i++){
            var e = calendar.getEventById(curCRN);
            e.remove();
          }

          // copyable CRN list handling
          var index = copyCodes.indexOf(curCRN);
          if (index > -1) {
            copyCodes.splice(index, 1);
          }
          listText = '';
          for (i = 0; i < copyCodes.length; i++) {
            listText += copyCodes[i] + "\n";
          }
          document.getElementById('finalList').value = listText;

          var textarea = document.getElementById("finalList");  //intead of "input"
          var data = textarea.value;
          for (var i = 0; i < data.length; i++) {
              if (data.substr(i, data) == curCRN) {
                  textarea.value = "";
              }
          }
          clicked = false;
        });

        // Red hover when removing
        document.getElementById(span).addEventListener('mouseover', function() {
          for (var i = 0; i < times.length; i++){
            var e = calendar.getEventById(curCRN);
            e.remove();
          }
          for (i = 0; i < times.length; i++){
            calendar.addEvent({
              title: curTitle,
              daysOfWeek: [ timeArr[times[i]][2] ],
              startTime: timeArr[times[i]][0],
              endTime: timeArr[times[i]][1],
              id: curCRN,
              color: "#FF7D62"
            });
          }
        });
        document.getElementById(span).addEventListener('mouseleave', function() {
          for (var i = 0; i < times.length; i++){
            var e = calendar.getEventById(curCRN);
            e.remove();
          }
          for (i = 0; i < times.length; i++){
            calendar.addEvent({
              title: curTitle,
              daysOfWeek: [ timeArr[times[i]][2] ],
              startTime: timeArr[times[i]][0],
              endTime: timeArr[times[i]][1],
              id: curCRN,
            });
          }
        });
      });

    }());
  }

});
