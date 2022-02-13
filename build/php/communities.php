<?php

  function generate_communities($edit, $page){
    if($mysqli = connect_or_create_db())
    {
      $sql = "SELECT id,community FROM availability_communities GROUP BY(community)";
      if($result = $mysqli->query($sql))
      {
        echo "<section id='communities-nav' class='no-edit section flex center-flex white-section' data-type='white-section' data-layout='center-flex' data-body='regular'>\n
        <span class='wrapper flex center-flex regular p-0'  data-article_color='transparent'>\n";

        echo "<article style='overflow:visible !important; max-width:100% !important;' class=' istable animation-element slideInUp' data-article_color='transparent' style='background-color:transparent; transition-delay: 0;'>\n
            <div style='min-height: 250px;' class='content table' data-edit='no' data-animation_delay='0' data-type='table' data-animation='slideInUp' >\n";


        $tab_content = "<div class='tab-content' id='communities-tab-content'>\n";

        // $objs = array();

        // while($obj = $result2->fetch_object)
        // {
        //   $objs[] = $obj;
        // }

        $first = true;

        while($obj = $result->fetch_object())
        {
          $id = $obj->id;
          // $champion = $obj->champion;
          $community = $obj->community;
          // $address = $obj->address;

          if($first)
            $ul = '<ul class="nav nav-tabs">
            <li class="nav-item dropdown" style="overflow: visible;">
              <a onmouseup="setInput(\'#myInput\')" style="margin:1px;" class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Please Select a City</a>
                <div id="myDropdown" class="dropdown-menu scrollable-menu">';

          // if($first)
          //   $ul .= "<a class='nav-link active' id='community-link-$id-tab' data-toggle='tab' href='#community-link-$id' tole='tab' aria-controls='home' aria-selected='true'>$community</a>";
          // else
          //   $ul .= "<a class='nav-link' id='community-link-$id-tab' data-toggle='tab' href='#community-link-$id' tole='tab' aria-controls='home' aria-selected='false'>$community</a>";

          if($first){
            $ul .= '<input class="dropdown-item" type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">';
            $ul .= "<a class='dropdown-item active' id='community-link-p-tab' data-toggle='tab' href='#community-link-p' role='tab' aria-controls='home' aria-selected='true'>Please Select a City</a>";
            $ul .= "<a class='dropdown-item' id='community-link-all-tab' data-toggle='tab' href='#community-link-all' role='tab' aria-controls='home' aria-selected='false'>ALL</a>";
            $ul .= "<a class='dropdown-item' id='community-link-$id-tab' data-toggle='tab' href='#community-link-$id' role='tab' aria-controls='home' aria-selected='false'>$community</a>";
          }
          else
            $ul .= "<a class='dropdown-item' id='community-link-$id-tab' data-toggle='tab' href='#community-link-$id' role='tab' aria-controls='home' aria-selected='false'>$community</a>";


          if($first)
          {
            $tab_content .= "<div class='tab-pane fade show active' id='community-link-p' role='tabpanel' aria-labelledby='community-link-p-tab'>\n</div>";



            $tab_content .= "<div class='tab-pane fade show' id='community-link-$id' role='tabpanel' aria-labelledby='community-link-$id-tab'>\n";
          }
          else
            $tab_content .= "<div class='tab-pane fade show' id='community-link-$id' role='tabpanel' aria-labelledby='community-link-$id-tab'>\n";

          $tab_content .= "<table id='avail-table' class='table table-bordered table-design responsive-table'>\n
                            <thead>
                              <tr>\n
                                <th>Community</th>\n
                                <th>Beer Store Address</th>\n
                                <th>Phone</th>
                                <th>Store Inventory</th>\n
                              </tr>\n
                            </thead>\n
                            <tbody>\n";


          $sql = "SELECT * FROM availability_communities WHERE community='$community'";
          if($result2 = $mysqli->query($sql))
          {
            while($obj2 = $result2->fetch_assoc())
            {
              $id = $obj2['id'];
              $phone = $obj2['phone'];
              $community = $obj2['community'];
              $address = $obj2['address'];
              $store_inv = $obj2['store_inventory'];

              $tab_content .= "<tr>\n";

              // $tab_content .= "<td>$champion</td>";
              $tab_content .= "<td>$community</td>";
              $tab_content .= "<td>$address</td>";
              $tab_content .= "<td>".
              "<a class='blue-text' href='tel:$phone'>$phone</a>"."</td>";
              $tab_content .= "<td>".(filter_var($store_inv, FILTER_VALIDATE_URL) ?
              "<a class='blue-text' target='_blank' href='$store_inv'>$store_inv</a>" : $store_inv)."</td>";

              if($edit)
                $tab_content .= "<td><form method='post' action='/php/delete_community_rec.php'>\n
                  <input type='hidden' name='page' value='$page'>\n
                  <input type='hidden' name='id' value='$id'>\n
                  <button type='submit' class='fa-button'><i class='fa fa-close' aria-hidden='true'></i></button>\n
                </form></td>\n";

              $tab_content .= "</tr>\n";
            }
            $result2->close();
          }

          if($edit)
          {
            $tab_content .= "<tr>\n
              <form method='post' action='/php/add_community_rec.php'>
                <td><input type='text' name='champion'></td>\n
                <td><input type='text' name='community'</td>\n
                <td><input type='text' name='address'</td>\n
                <td><button type='submit' class='fa-button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
              </form>
            </tr>\n";
          }

          $tab_content .= "</tbody>\n
                            </table>\n
                              </div>\n";

          $first = false;
        }

        $tab_content .= "<div class='tab-pane fade show' id='community-link-all' tole='tabpanel' aria-labelledby='community-link-all-tab'>\n";
        $tab_content .= "<table id='avail-table' class='table table-bordered table-design responsive-table'>\n
                          <thead>\n
                            <tr>\n
                              <th>Community</th>\n
                              <th>Beer Store Address</th>\n
                              <th>Phone</th>
                              <th>Store Inventory</th>\n
                            </tr>\n
                          </thead>\n
                          <tbody>\n";

        $sql = "SELECT * FROM availability_communities ORDER BY champion";
        if($result2 = $mysqli->query($sql))
        {
          while($obj2 = $result2->fetch_assoc())
          {
              $id = $obj2['id'];
              $phone = $obj2['phone'];
              $community = $obj2['community'];
              $address = $obj2['address'];
              $store_inv = $obj2['store_inventory'];

              $tab_content .= "<tr>\n";

              // $tab_content .= "<td>$champion</td>";
              $tab_content .= "<td>$community</td>";
              $tab_content .= "<td>$address</td>";
              $tab_content .= "<td>".
              "<a class='blue-text' href='tel:$phone'>$phone</a>"."</td>";
              $tab_content .= "<td>".(filter_var($store_inv, FILTER_VALIDATE_URL) ?
              "<a class='blue-text' target='_blank' href='$store_inv'>$store_inv</a>" : $store_inv)."</td>";

            if($edit)
              $tab_content .= "<td><form method='post' action='/php/delete_community_rec.php'>\n
                <input type='hidden' name='page' value='$page'>\n
                <input type='hidden' name='id' value='$id'>\n
                <button type='submit' class='fa-button'><i class='fa fa-close' aria-hidden='true'></i></button>\n
              </form></td>\n";

            $tab_content .= "</tr>\n";
          }

          $result2->close();
        }

        if($edit)
        {
          $tab_content .= "<tr>\n
            <form method='post' action='/php/add_community_rec.php'>
              <td><input type='text' name='champion'></td>\n
              <td><input type='text' name='community'</td>\n
              <td><input type='text' name='address'</td>\n
              <td><button type='submit' class='fa-button'><i class='fa fa-plus' aria-hidden='true'></i></button></td>
            </form>
          </tr>\n";
        }

        $tab_content .= "</tbody>\n
                          </table>\n
                            </div>\n";

        $ul .= "</div></li></ul>\n";

        $tab_content .= "</div>\n";

        echo $ul;

        echo $tab_content;

        echo "</div>\n
        </article>\n";

        echo "</span>\n</section>\n";

      }
      $mysqli->close();
    }
  }

 ?>
