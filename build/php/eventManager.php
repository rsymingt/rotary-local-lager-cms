
<?php
    session_start();
    include("session.php");
    $return = array();
    if(!session_logged_in())
    {
        $return['loggedIn'] = false;
        echo json_encode($return);
        return;
    }
    else
    {
        $return['loggedIn'] = true;
    }
    include("../google/vendor/autoload.php");

    class EventManager
    {
        public $_client;
        public $_calendarId;
        public $_events;
        public $_page;
        public $_total;
        public $_limit;

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

            // $this->_events = $service->events->listEvents($this->_calendarId);
      
            $this->_total = count($this->_events);
            $this->_page = $page;
            $this->_limit = $limit;
        }
          
        public function get_events()
        {
            $events = array();
            $calTimeZone = $this->_events->timeZone; //GET THE TZ OF THE CALENDAR
            date_default_timezone_set($calTimeZone);

            for($i = ($this->_page-1)*$this->_limit; $i < $this->_total; $i++)
            {
                $event = $this->_events[$i];
                $startDateStr = $event->start->dateTime;
                $endDateStr = $event->end->dateTime;
                $allDay = false;

                if(empty($startDateStr))
                {
                    $startDateStr = $event->start->date . "T00:00:00";
                    $allDay = true;
                }

                if(empty($endDateStr))
                {
                    $endDateStr = $event->end->date . "T00:00:00";
                    $allDay = true;
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

                $eventDate = new DateTime($startDateStr, $timeZone);
                $link = $event->htmlLink;
                $TZlink = $link . "&ctz=" . $calTimeZone;//ADD TZ TO EVENT LINK
                                        //PREVENTS GOOGLE FROM DISPLAYING EVERYTHING IN GMT

                $year = $eventDate->format("Y");
                $month = $eventDate->format("M");
                $day = $eventDate->format("d");

                $title = $event->summary;
                $description = $event->description;

                $events[] = array(
                    "title" => $title,
                    "description" => $description,
                    "start" => $startDateStr,
                    "end" => $endDateStr,
                    "allDay" => $allDay,
                    "location" => $event->location
                );
            }

            return $events;
        }

    }

    $eventManager = new EventManager(10, 1);

    if(isset($_POST['command']))
    {
        switch($_POST['command'])
        {
            case 'get':
            {
                $return['events'] = $eventManager->get_events();
                $return['count'] = count($eventManager->_events);
                break;
            }
            default:
            {
                break;
            }
        }
    }

    echo json_encode($return);

?>