<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EasyCRN</title>

    <!-- Styles -->

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <!-- Custom -->
    <link rel="stylesheet" href="src/styles/closable.css">
    <!-- FullCalendar -->
    <link href='src/vendors/fullCalender/lib/main.css' rel='stylesheet' />

    <!-- Scripts -->

    <!-- BootStrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <!-- FullCalendar -->
    <script src='src/vendors/fullCalender/lib/main.js'></script>
    <!-- courseCalendar -->
    <script src="src/scripts/calendar.js"></script>
    <!-- CourseList -->
    <script src="src/scripts/main.js"></script>

    <?php
    ## Course data init ##

    # Required functions #
    function startsWith( $haystack, $needle )
    {
     $length = strlen( $needle );
     return substr( $haystack, 0, $length ) === $needle;
    }

    # JSON init #
    $string = file_get_contents("./src/data/courses.json");
    $courses = json_decode($string);
    $string2 = file_get_contents("./src/data/timeblocks.json");
    $times = json_decode($string2);
    $string = file_get_contents("./src/data/departments.json");
    $depts = json_decode($string);

    # Course parent array; contains departments #
    // Initializing multidimentional array
    $deptArr = array();
    foreach($depts as $dept):
      $deptsArr[$dept->CODE] = array();
    endforeach;

    // Adding each course to corrosponding department subarray
    foreach($courses as $course) {
      $curCode = '';
      $tempStr = str_split($course->COURSE);

      // Isolating department code
      foreach($tempStr as $c) {
        if ($c != ' '){
          $curCode = $curCode.$c;
        }
        else {
          break;
        }
      }

      // Adding course
      $deptsArr[$curCode][] = $course;
    }

    # Course Array init #
    $courseArr = array();
    foreach($courses as $course):
      $courseArr[$course->CRN] = array($course->COURSE, $course->TIMEBLOCK);
    endforeach;

    # Time Array init #
    $timeArr = array();
    foreach($times as $time):
      $timeArr[$time->ID] = array($time->START, $time->END, $time->DAY);
    endforeach;
    ?>

    <script type='text/javascript'>
      // Conversion from php to JavaScript array
      <?php
      $js_array = json_encode($courseArr);
      echo "var courseArr = ". $js_array . ";\n";

      $js_array2 = json_encode($timeArr);
      echo "var timeArr = ". $js_array2 . ";\n";
      ?>
    </script>


  </head>

  <body>

    <nav class="navbar navbar-light bg-light">
      <span class="navbar-brand mb-0 h1">
      <img src="src\img\xLogo.jpg" height="25" length="15">
      EasyCRN</span>
    </nav>


    <div class="container">
      <div class="row">
        <div class="col-sm-8 rounded bg-light">
          <div class="row rounded bg-primary">
            <h4 style="text-align: center; color: white;">My Schedule</h4>
          </div>
          <div class="row">
            <div id='calendar'></div>
          </div>
        </div>


        <div class="col-sm-4">
          <br>

          <ul class="nav nav-tabs" style="text-align: center;">
            <li class="nav-item">
              <a class="nav-link active" data-bs-toggle="tab" href="#courseList" aria-controls="courseList">Course List</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#help" aria-controls="help">Help</a>
            </li>
          </ul>

          <div class="tab-content" style="height: 415px; border-left: 1px solid #E8E8E8; border-bottom: 1px solid #E8E8E8;">
            <div class="tab-pane active container" id="courseList">

              <!-- Course Search Bar -->
              <input class="form-control mb-3 mt-3" id="courseSearchBar" type="text" placeholder="Search.."/>

              <!-- Course List Accordion -->
              <div class="accordion rounded border" id="courses" style="overflow-y: scroll; height:350px;">

                  <?php
                    foreach($depts as $dept):
                      ?>

                      <div class="accordion-item">
                        <h2 class="accordion-header" id="<?= $dept->CODE ?>Head">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target='#<?= $dept->CODE ?>Collapse' aria-expanded="false" aria-controls="<?= $dept->CODE ?>Collapse">
                            <?= $dept->TITLE ?>
                          </button>
                        </h2>
                        <div id="<?= $dept->CODE ?>Collapse" class="accordion-collapse collapse" aria-labelledby="<?= $dept->CODE ?>Head" data-bs-parent="#courses">
                          <div class="accordion accordion-flush" id="<?= $dept->CODE ?>accordion">
                            <?
                            foreach($deptsArr[$dept->CODE] as $course){
                              $title = $course->COURSE;
                              $crn = strval($course->CRN);
                              $crn = 'crn' . $crn;
                              $crn2 = "#" . $crn;
                              $room = $course->ROOM;
                              $data = $course->TITLE." | ".$course->COURSE;
                                ?>
                                <div class="accordion-item" id="item<?=$crn ?>" style="border-left: 10px solid #F5F5F5;">
                                  <h3 class="accordion-header" id="<?= $crn?>Head">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="<?= $crn2 ?>" aria-expanded="false" aria-controls="<?= $crn?>">
                                      <?= $course->COURSE  ?>
                                    </button>
                                  </h3>
                                  <div id="<?= $crn ?>" class="accordion-collapse collapse" aria-labelledby="<?= $crn ?>Head" data-bs-parent="<?= $dept->CODE ?>accordion">
                                    <div class="container pt-2">
                                      <div class="row">
                                        <div class="col-md-8" style="padding: 10px 0;">
                                          <h6>
                                            <?= $course->TITLE ?>
                                          <h6>
                                        </div>
                                        <div class="col">
                                          <input type="button" value="Add" id="add<?= $course->CRN ?>" class="btn-sm btn-primary" onclick="addToList('<?= $data ?>', 'num<?= $course->CRN ?>')" type="submit" style="width: 60px; float: right; padding: 10px 0;">
                                        </div>
                                      </div>
                                      <br>

                                      <div class="row">
                                        <div class="col">
                                          <p>Professor:</p>
                                        </div>
                                        <div class="col">
                                          <p><?= $course->PROFS ?></p>
                                        </div>
                                      </div>

                                        <div class="row">
                                          <div class="col">
                                            <p>Term:</p>
                                          </div>
                                          <div class="col">
                                            <p><?= $course->TERM ?></p>
                                          </div>
                                        </div>

                                        <div class="row">
                                          <div class="col">
                                            <p>Location:</p>
                                          </div>
                                          <div class="col">
                                            <?
                                            if ($course->ROOM != ""){
                                              echo("<p>$course->ROOM</p>");
                                            }
                                            else{
                                              echo("<p>Online</p>");
                                            }
                                            ?>
                                          </div>
                                        </div>

                                      </div>
                                    </div>
                                  </div>
                                <?
                            } ?>
                            </div>
                          </div>
                        </div>
                    <?
                    endforeach;
                    ?>
              </div>


            </div>

            <!-- Basic Help Panel -->
            <div class="tab-pane container" id="help">
              <p>
                <h5>Step by Step Guide:</h5>
                <hr class="solid">
                <h5>Step 1:</h5>
                Browse for your desired courses, from either the Course List or search.
                <hr class="solid">
                <h5>Step 2:</h5>
                Add to your schedule, building your courses found in  the 'My Courses' tab.
                <hr class="solid">
                <h5>Step 3:</h5>
                Submit and recieve your course codes for the coming semester!
              </p>
            </div>

        </div>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="alert alert-dark " style="margin-left: 20px; margin-right: 20px;" role="alert">
        <h4 style="text-align: center; color: black">My Courses &nbsp<span id="courseCount" class="badge bg-danger"></span></h4>
        <br>
        <div class="row p-2">
          <div class="col-9 rounded bg-primary pt-2 border-myBorder" id="listJumbo">
            <h5 style="color: white">Courses Selected:</h5>
            <ul id="crnList">
            </ul>
            <p id="emptyCRN" style="color: white;">No courses picked yet.</p>
          </div>
          <div class="col-3">
            <textarea class="form-control" id="finalList" style="resize: none;" type="text" cols="40" rows="10" placeholder="Your added course codes will go here." readonly></textarea>
            <button id="copyCRNButton" class="btn btn-primary">Copy to clipboard</button>
          </div>
      </div>
      </div>
    </div>
  </div>
  </body>
</html>
