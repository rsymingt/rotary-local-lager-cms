<?php

  include("../google/vendor/autoload.php");

  class Events{

    private $_client;
    private $_calendarId;
    private $_events;
    private $_page;
    private $_total;
    private $_limit;

    public function getClient()
    {
      $client = new Google_Client();
      $client->setApplicationName('Google Calendar');
      $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
      $client->setDeveloperKey('AIzaSyDvAFjlPhHY_VFth49_CFGFLh3lSgdFq1E');

      return $client;
    }

    public function __construct($limit, $page){
      $this->_client = $this->getClient();

      $this->_calendarId = "events@rotarylocallager.ca";

      $service = new Google_Service_Calendar($this->_client);

      $params = array(
        'singleEvents' => true,
        'orderBy' => 'startTime',
        'timeMin' => date(DateTime::ATOM),
        'maxResults' => 100
      );

      //THIS IS WHERE WE ACTUALLY PUT THE RESULTS INTO A VAR
      $this->_events = $service->events->listEvents($this->_calendarId, $params);

      // for($i = 0; $i < count($this->_events); $i++)
      // {
      //   $event = $this->_events[$i];
      //   $eventDateStr = $event->start->dateTime;
      //
      //   if(empty($eventDateStr))
      //   {
      //     $eventDateStr = $event->start->date;
      //   }
      //
      //   // echo "<p>$eventDateStr</p>";
      //
      //   $this->_events[$i] = $this->_events[$i+1];
      //   $this->_events[$i+1] = $event;
      //   break;
      // }

      // $params = array(
      //   'singleEvents' => true,
      //   'orderBy' => 'startTime',
      //   // 'timeMax' => date(DateTime::ATOM),
      //   'maxResults' => 100
      // );

      //THIS IS WHERE WE ACTUALLY PUT THE RESULTS INTO A VAR
      // $this->_events->setItems(array($this->_events->getItems(), $service->events->listEvents($this->_calendarId, $params)->getItems()));



      // $this->_events = $this->_events->reverse();

      // $this->_events = array_merge($this->_events, $this->_events_past);
      // array_push()
      // array_push($this->_events, $this->_events_past);
      // $this->_events = array_values($this->_events);

      // echo "<p>".count($this->_events_past)."</p>";
      //
      // foreach($this->_events_past as $event)
      // {
      //   $this->_events[] = $event;
      // }
      //
      // echo "<p>".count($this->_events)."</p>";

      // for($i = 0; $i < count($this->_events_past); $i++)
      // {
      //   $this->_events[] = $this->_events_past[$i];
      // }

      // $this->_events = array_merge($this->_events->getItems(), $this->_events_past->getItems());

      // echo "<p>".print_r($this->_events)."</p>";

      $this->_total = count($this->_events);
      // $this->_past_total = count($this->_events_past);
      $this->_page = $page;
      $this->_limit = $limit;
    }

    public function listevents($events, $calTimeZone, $count)
    {
      $delay = 0;
      $i = ($this->_page-1)*$this->_limit;
      for(; $i < $this->_total && $i < $this->_page*$this->_limit; $i++)
      {
        $event = $events[$i];
        $eventDateStr = $event->start->dateTime;

        // $interval = date_diff(new DateTime(date(DateTime::ATOM)),new DateTime($event->start->date));
        // // echo "<p>".date(DateTime::ATOM).$event->start->date."</p><br>";
        // // echo "<p>".$interval->invert.$interval->format('%R%a days')."</p>";
        //
        // if($new && $interval->invert)
        //   continue;
        // if(!$new && !$interval->invert)
          // continue;
        if(empty($eventDateStr))
        {
          $eventDateStr = $event->start->date;
        }

        $temp_timezone = $event->start->timeZone;
        if(!empty($temp_timezone))
        {
          $timeZone = new DateTimeZone($temp_timezone);
        }
        else
        {
          $timeZone = new DateTimeZone($calTimeZone);
        }

        $eventDate = new DateTime($eventDateStr, $timeZone);
        $link = $event->htmlLink;
        $TZlink = $link . "&ctz=" . $calTimeZone;//ADD TZ TO EVENT LINK
                                  //PREVENTS GOOGLE FROM DISPLAYING EVERYTHING IN GMT

        $year = $eventDate->format("Y");
        $month = $eventDate->format("M");
        $day = $eventDate->format("d");

        $title = $event->summary;
        $description = $event->description;

        ?>
              <article class='ispost animation-element slideInUp' data-article_color='transparent' style='background-color:transparent; transition-delay: <?php echo $delay; ?>ms;'>
                <div class='content post ' data-edit='no' data-animation_delay='<?php echo $delay; ?>' data-type='post' data-animation='slideInUp'>

                  <div class="blog-entry">
                    <h3 class="blog-title"><a class="post-link lager-orange" target="_blank" href="<?php echo $TZlink; ?>"><?php echo $title; ?></a></h3>
                  </div>

                  <div class="blog-meta">
                    <time><?php echo $month . " " . $day . ", " . $year . " " . $tok; ?></time>
                  </div>

                  <div class="blog-excerpt">
                    <?php echo $description ?>
                  </div>

                </div>
              </article>

        <?php
        $delay += 100;
      }
    }

    public function getResults(){

      $events = $this->_events;
      $calTimeZone = $events->timeZone; //GET THE TZ OF THE CALENDAR

      //SET THE DEFAULT TIMEZONE SO PHP DOESN'T COMPLAIN. SET TO YOUR LOCAL TIME ZONE.
       date_default_timezone_set($calTimeZone);

       ?>
         <section id="blog-section" class='section flex center-flex white-section no-edit' data-type='white-section' data-layout='center-flex' data-body='regular-big'>
           <span id="blog-wrapper" class='wrapper flex vertical-flex regular-big'  data-article_color='transparent'>
       <?php

       $this->listevents($events, $calTimeZone, count($events));
       // $this->listevents($events, $calTimeZone, false);
      ?>
          </span>
        </section>
      <?php
    }

    public function createLinks( $links, $list_class ) {
      if ( $this->_limit == 'all' ) {
          return '';
      }else if($this->_total == 0)
      {
        return '';
      }

      $last       = ceil( $this->_total / $this->_limit );

      $start      = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
      $end        = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;


      $html = "<section class='section flex center-flex white-section no-edit' data-type='white-section' data-layout='center-flex' ".
            "data-body='regular-big'>\n";

      $html .= "<span class='wrapper flex vertical-flex regular-big'  data-article_color='transparent' >";

      $html       .= '<ul class="' . $list_class . '">';

      $class      = ( $this->_page == 1 ) ? "disabled" : "";
      $html       .= '<li class="' . $class . ' page-item"><a class="page-link" href="?limit=' . $this->_limit . '&page=' . ( $this->_page - 1 ) . '">&laquo;</a></li>';

      if ( $start > 1 ) {
          $html   .= '<li class="page-item"><a class="page-link" href="?limit=' . $this->_limit . '&page=1">1</a></li>';
          $html   .= '<li class="page-item disabled"><span>...</span></li>';
      }

      for ( $i = $start ; $i <= $end; $i++ ) {
          $class  = ( $this->_page == $i ) ? "active" : "";
          $html   .= '<li class="' . $class .' page-item"><a class="page-link" href="?limit=' . $this->_limit . '&page=' . $i . '">' . $i . '</a></li>';
      }

      if ( $end < $last ) {
          $html   .= '<li class="page-item disabled"><span>...</span></li>';
          $html   .= '<li class="page-item"><a class="page-link" href="?limit=' . $this->_limit . '&page=' . $last . '">' . $last . '</a></li>';
      }

      $class      = ( $this->_page == $last ) ? "disabled" : "";
      $html       .= '<li class="' . $class . ' page-item"><a class="page-link" href="?limit=' . $this->_limit . '&page=' . ( $this->_page + 1 ) . '">&raquo;</a></li>';

      $html       .= '</ul>';

      $html .= "</span></section>";

      return $html;
    }
  }

?>
