<?php

  class Paginator{
    private $_conn;
    private $_limit;
    private $_page;
    private $_query;
    private $_total;

    public function __construct($conn, $query){
      $this->_conn = $conn;
      $this->_query = $query;

      $rs = $this->_conn->query($this->_query);
      $this->_total = $rs->num_rows;
    }

    public function getData($page = 1, $limit = 10)
    {
        if($page < 1) return NULL;
      $this->_limit = $limit;
      $this->_page = $page;

      if($this->_limit == 'all')
      {
        $query = $this->_query;
      }else {
        $query = $this->_query . " LIMIT " . (($this->_page-1) * $this->_limit) . ", $this->_limit";
      }
      $rs = $this->_conn->query($query);

      while($row = $rs->fetch_object())
      {
        $results[] = $row;
      }

      if(count($results) == 0)
        return NULL;

      $result = new stdClass();
      $result->page = $this->_page;
      $result->limit = $this->_limit;
      $result->total = $this->_total;
      $result->data = $results;

      return $result;
    }

    public function createLinks( $links, $list_class ) {
      if ( $this->_limit == 'all' ) {
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