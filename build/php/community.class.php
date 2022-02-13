<?php

  class CommunityListing{
    private $_conn;
    private $_query;
    private $_total;
    private $_admin;
    private $_data;

    public function __construct(){
      $this->_conn = connect_or_create_db();;
      $this->_query = "SELECT * FROM contact_communities";
      $this->_admin = session_is_admin();

      $rs = $this->_conn->query($this->_query);
      $this->_total = $rs->num_rows;
    }

    //returns list of communities
    public function getListing($column){
      $query = "SELECT * FROM contact_communities ORDER BY community";

      $communities = array();
      if($rs = $this->_conn->query($query))
      {
        while($obj = $rs->fetch_assoc())
        {
          $communities[] = $obj;
        }
      }
      return $communities;
    }

    // returns listing for community
    public function get($column, $val){
      $query = "";
      if(strcasecmp($val, "please select a community") == 0)
        return NULL;
      else if(strcasecmp($val, "all") == 0)
        $query = $this->_query . " ORDER BY community";
      else
        $query = $this->_query . " WHERE $column='$val'";

      $data = array();
      if($rs = $this->_conn->query($query))
      {
        while($obj = $rs->fetch_assoc())
        {
          $data[] = $obj;
        }
      }
      return $data;
    }

    public function unique_community($array)
    {
      $temp = array();
      $key_array = array();

      foreach($array as $obj)
      {
        if(!in_array($obj['community'], $key_array))
        {
          $key_array[] = $obj['community'];
          $temp[] = $obj;
        }
      }

      return $temp;
    }

    public function generateTable($community)
    {
      $tab_content = "<table id='contact-table' class='table table-bordered table-design responsive-table'>\n
                    <thead>
                      <tr>\n
                        <th>Community</th>\n
                        <th>Participating Club</th>\n
                        <th>Club Contact Name</th>\n
                        <th>Title</th>\n
                        <th>Phone #</th>\n
                        <th>Contact Email</th>\n
                        <th>Club Address</th>\n
                      </tr>\n
                    </thead>\n
                    <tbody>\n";

      $listing = $this->get('community', $community);

      if(!$listing) return "";

      foreach($listing as $row)
      {
        $id = $row['id'];
        $part = $row['participating_club'];
        $community = $row['community'];
        $club_contact = $row['club_contact'];
        $title = $row['title'];
        $phone = $row['phone'];
        $contact_email = $row['contact_email'];
        $address = $row['club_address'];

        if(!$this->_admin)
        {
          if($title != 'Contact' and $title != 'Contact 2')
          {
            continue;
          }
        }

        $tab_content .= "<tr>\n";

        $tab_content .= "<td>$community</td>";
        $tab_content .= "<td>$part</td>";
        $tab_content .= "<td>$club_contact</td>";
        $tab_content .= "<td>$title</td>";
        $tab_content .= "<td>$phone</td>";
        $tab_content .= "<td>$contact_email</td>";
        $tab_content .= "<td>$address</td>";

        if($this->_admin)
          $tab_content .= "<td><form method='post' action='/php/delete_contact_rec.php'>\n
            <input type='hidden' name='page' value='contact'>\n
            <input type='hidden' name='id' value='$id'>\n
            <button type='submit' class='fa-button'><i class='fa fa-close' aria-hidden='true'></i></button>\n
          </form></td>\n";

        $tab_content .= "</tr>\n";
      }

      if($this->_admin)
      {
        $tab_content .= "<tr>\n
          <form method='post' action='/php/add_contact_rec.php'>
            <td><input type='text' name='participating_club'></td>\n
            <td><input type='text' name='community'</td>\n
            <td><input type='text' name='club_contact'</td>\n
            <td><input type='text' name='title'</td>\n
            <td><input type='text' name='phone'</td>\n
            <td><input type='text' name='contact_email'</td>\n
            <td><input type='text' name='club_address'</td>\n
            <td><button type='submit' class='fa-button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
          </form>
        </tr>\n";
      }

      $tab_content .= "</tbody>\n
                          </table>\n";

      $first = false;
      return $tab_content;
    }

    public function generateTabContent($listing, $distinct_listing, $column){
      $tab_content = "<div class='tab-content' id='communities-tab-content'>\n";

      $first = true;

      foreach($distinct_listing as $obj)
      {
        $id = $obj['id'];
        $s = $obj['community'];

        // $distinct_list = $this->get($column, $s);
        if($first){
          $tab_content .= "<div class='tab-pane fade show' id='community-link-$id' role='tabpanel' aria-labelledby='community-link-$id-tab'>\n";
        }else
          $tab_content .= "<div class='tab-pane fade show' id='community-link-$id' role='tabpanel' aria-labelledby='community-link-$id-tab'>\n";

        $tab_content .= "<table id='contact-table' class='table table-bordered table-design responsive-table'>\n
                      <thead>
                        <tr>\n
                          <th>Community</th>\n
                          <th>Participating Club</th>\n
                          <th>Club Contact Name</th>\n
                          <th>Title</th>\n
                          <th>Phone #</th>\n
                          <th>Contact Email</th>\n
                          <th>Club Address</th>\n
                        </tr>\n
                      </thead>\n
                      <tbody>\n";

        $distinct_list = array_filter($listing, function($obj) use($s){
            if ($obj['community'] == $s) {
                // foreach ($obj->admins as $admin) {
                //     if ($admin->member == 11) return false;
                // }
              return true;
            }
            return false;
        });

        foreach($distinct_list as $row)
        {
          $id = $row['id'];
          $part = $row['participating_club'];
          $community = $row['community'];
          $club_contact = $row['club_contact'];
          $title = $row['title'];
          $phone = $row['phone'];
          $contact_email = $row['contact_email'];
          $address = $row['club_address'];

          if(!$this->_admin)
          {
            if($title != 'Contact' and $title != 'Contact 2')
            {
              continue;
            }
          }

          $tab_content .= "<tr>\n";

          $tab_content .= "<td>$community</td>";
          $tab_content .= "<td>$part</td>";
          $tab_content .= "<td>$club_contact</td>";
          $tab_content .= "<td>$title</td>";
          $tab_content .= "<td>$phone</td>";
          $tab_content .= "<td>$contact_email</td>";
          $tab_content .= "<td>$address</td>";

          if($this->_admin)
            $tab_content .= "<td><form method='post' action='/php/delete_contact_rec.php'>\n
              <input type='hidden' name='page' value='contact'>\n
              <input type='hidden' name='id' value='$id'>\n
              <button type='submit' class='fa-button'><i class='fa fa-close' aria-hidden='true'></i></button>\n
            </form></td>\n";

          $tab_content .= "</tr>\n";
        }

        if($this->_admin)
        {
          $tab_content .= "<tr>\n
            <form method='post' action='/php/add_contact_rec.php'>
              <td><input type='text' name='participating_club'></td>\n
              <td><input type='text' name='community'</td>\n
              <td><input type='text' name='club_contact'</td>\n
              <td><input type='text' name='title'</td>\n
              <td><input type='text' name='phone'</td>\n
              <td><input type='text' name='contact_email'</td>\n
              <td><input type='text' name='club_address'</td>\n
              <td><button type='submit' class='fa-button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
            </form>
          </tr>\n";
        }

        $tab_content .= "</tbody>\n
                            </table>\n";

        $tab_content .= "</div>\n";

        $first = false;
      }
      $tab_content .= "<div class='tab-pane fade show active' id='community-link-p' tole='tabpanel' aria-labelledby='community-link-p-tab'>\n</div>";

      $tab_content .= "<div class='tab-pane fade show' id='community-link-all' tole='tabpanel' aria-labelledby='community-link-all-tab'>\n";

      $tab_content .= "<table id='contact-table' class='table table-bordered table-design responsive-table'>\n
                    <thead>
                      <tr>\n
                        <th>Community</th>\n
                        <th>Participating Club</th>\n
                        <th>Club Contact Name</th>\n
                        <th>Title</th>\n
                        <th>Phone #</th>\n
                        <th>Contact Email</th>\n
                        <th>Club Address</th>\n
                      </tr>\n
                    </thead>\n
                    <tbody>\n";

      foreach($listing as $row)
      {
        $id = $row['id'];
        $part = $row['participating_club'];
        $community = $row['community'];
        $club_contact = $row['club_contact'];
        $title = $row['title'];
        $phone = $row['phone'];
        $contact_email = $row['contact_email'];
        $address = $row['club_address'];

        if(!$this->_admin)
        {
          if($title != 'Contact' and $title != 'Contact 2')
          {
            continue;
          }
        }

        $tab_content .= "<tr>\n";

        $tab_content .= "<td>$community</td>";
        $tab_content .= "<td>$part</td>";
        $tab_content .= "<td>$club_contact</td>";
        $tab_content .= "<td>$title</td>";
        $tab_content .= "<td>$phone</td>";
        $tab_content .= "<td>$contact_email</td>";
        $tab_content .= "<td>$address</td>";

        if($this->_admin)
          $tab_content .= "<td><form method='post' action='/php/delete_contact_rec.php'>\n
            <input type='hidden' name='page' value='contact'>\n
            <input type='hidden' name='id' value='$id'>\n
            <button type='submit' class='fa-button'><i class='fa fa-close' aria-hidden='true'></i></button>\n
          </form></td>\n";

        $tab_content .= "</tr>\n";
      }

      if($this->_admin)
      {
        $tab_content .= "<tr>\n
          <form method='post' action='/php/add_contact_rec.php'>
            <td><input type='text' name='participating_club'></td>\n
            <td><input type='text' name='community'</td>\n
            <td><input type='text' name='club_contact'</td>\n
            <td><input type='text' name='title'</td>\n
            <td><input type='text' name='phone'</td>\n
            <td><input type='text' name='contact_email'</td>\n
            <td><input type='text' name='club_address'</td>\n
            <td><button type='submit' class='fa-button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
          </form>
        </tr>\n";
      }

      $tab_content .= "</tbody>\n
                          </table>\n";
      $tab_content .= "</div>\n";
      $tab_content .= "</tbody>\n
                          </table>\n";
      $tab_content .= "</div>\n";

      $tab_content .= "</div>\n";

      return $tab_content;
    }

    public function generateDropdown($listing, $distinct_listing, $column){
      $first = true;

      foreach($distinct_listing as $obj)
      {

        $id = $obj['id'];
        $s = $obj['community'];

        if($first){
          $ul = '<ul class="nav nav-tabs">
              <li class="nav-item dropdown" style="overflow: visible;">
                <a onmouseup="setInput(\'#myInput\')" style="margin:1px;" class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Please Select a Community</a>
                  <div id="myDropdown" class="dropdown-menu scrollable-menu">';
          $ul .= '<input class="dropdown-item" type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">';
          $ul .= "<a class='dropdown-item active' id='community-link-p-tab' data-toggle='tab' href='#community-link-p' role='tab' aria-controls='home' aria-selected='true'>Please Select a Community</a>";
          $ul .= "<a class='dropdown-item ' id='community-link-all-tab' data-toggle='tab' href='#community-link-all' role='tab' aria-controls='home' aria-selected='false'>All</a>";
          $ul .= "<a class='dropdown-item' id='community-link-$id-tab' data-toggle='tab' href='#community-link-$id' role='tab' aria-controls='home' aria-selected='false'>".$s."</a>";
        }
        else
          $ul .= "<a class='dropdown-item' id='community-link-$id-tab' data-toggle='tab' href='#community-link-$id' role='tab' aria-controls='home' aria-selected='false'>".$s."</a>";

        $first = false;
      }

      $ul .= "</div></li></ul>\n";

      return $ul;
    }

    public function generateHTML($column){
      $html = "<section id='communities-nav' class='section flex center-flex white-section no-edit' data-type='white-section' data-layout='center-flex' data-body='regular'>\n
        <span class='wrapper flex center-flex regular-big p-0'  data-article_color='transparent'>\n
        <article style='overflow:visible !important; max-width:100% !important;' class=' istable animation-element slideInUp' data-article_color='transparent' style='background-color:transparent; transition-delay: 0;'>\n
            <div style='min-height: 250px;' class='content table' data-edit='no' data-animation_delay='0' data-type='table' data-animation='slideInUp' >\n";


      $listing = $this->getListing($column);
      $distinct_listing = $this->unique_community($listing);

      $html .= $this->generateDropdown($listing, $distinct_listing, $column);
      $html .= "<div id='participating_table' class='tab-content' id='communities-tab-content'>\n</div>\n";

      // $html .= $this->generateTabContent($listing, $distinct_listing, $column);

      $html .= "</div>\n</article>\n</span>\n</section>\n";

      echo $html;
    }
  }

?>
