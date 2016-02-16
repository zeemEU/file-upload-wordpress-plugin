<?php
    define( 'BLOCK_LOAD', true );
    define('MYPATH', dirname(__FILE__));
    if ( file_exists( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php') ) {
      require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
    } else {
      // trying probable locations of wp-config.php
      if (file_exists( MYPATH . '/../../../wp-config.php' )) {
        require_once( MYPATH . '/../../../wp-config.php' );
      } elseif (file_exists( MYPATH . '/../../wp-config.php' )) {
        require_once( MYPATH . '/../../wp-config.php' );
      } elseif (file_exists( MYPATH . '/../wp-config.php' )) {
        require_once( MYPATH . '/../wp-config.php' );
      } elseif (file_exists( MYPATH . '/wp-config.php' )) {
        require_once( MYPATH . '/wp-config.php' );
      } else {
        // shit, could not find wp-config.php
        die();
      }
    }
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php') ) {
      require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );
    } else {
      // trying probable locations of wp-config.php
      if (file_exists( MYPATH . '/../../../wp-includes/wp-db.php' )) {
        require_once( MYPATH . '/../../../wp-includes/wp-db.php' );
      } elseif (file_exists( MYPATH . '/../../wp-includes/wp-db.php' )) {
        require_once( MYPATH . '/../../wp-includes/wp-db.php' );
      } elseif (file_exists( MYPATH . '/../wp-includes/wp-db.php' )) {
        require_once( MYPATH . '/../wp-includes/wp-db.php' );
      } elseif (file_exists( MYPATH . '/wp-includes/wp-db.php' )) {
        require_once( MYPATH . '/wp-includes/wp-db.php' );
      } else {
        // shit, could not find wp-includes/wp-db.php
        die();
      }
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'cloudwok';
    $noncetxt = "cWn0Nc€4tW";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // update
     if(!array_diff(array('cwnonce','ref','code'), array_keys($_POST)) && wp_verify_nonce( $_POST['cwnonce'], $noncetxt ))
     {
          $ref=(int)$_POST['ref'];
          $code=$_POST['code'];
          $wpdb->update(
            $table_name,
            array(
                'code' => $code
              ),
              array( 'id' => $ref ),
              array(
                '%s'
              ),
              array( '%d' )
          );
          echo "Updated";
      } else if(!array_diff(array('cwnonce','code'), array_keys($_POST)) && wp_verify_nonce( $_POST['cwnonce'], $noncetxt )) {
         // create
        $wpdb->insert(
          $table_name,
          array(
              'code' => $_POST['code']
          ),
          array(
              '%s'
          )
        );
        echo $wpdb->insert_id;
      } else {
        echo "No/wrong POST params or nonce did not verify";
      }
    }

   if ($_SERVER['REQUEST_METHOD'] === 'GET') {
     if (
         ! isset( $_GET['cwnonce'] )
         || ! wp_verify_nonce( $_GET['cwnonce'], $noncetxt )
     ) {
        print 'Sorry, your nonce did not verify.';
        exit;
     }
      // get one
     if(isset($_GET['ref']))
     {
         $ref=(int)$_GET['ref'];
         $query="SELECT * FROM $table_name WHERE id=$ref";
         $result=$wpdb->get_results($query);
         $to_return = array();
         foreach ($result as $row)
         {
           $to_return[] = $row;
         }
         echo json_encode($to_return[0]);
     }
   }

   if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
     $_DELETE = array();
     parse_str(file_get_contents('php://input'), $_DELETE );
      // delete row
     if(!array_diff(array('cwnonce','ref'), array_keys($_DELETE)) && wp_verify_nonce( $_DELETE['cwnonce'], $noncetxt ))
     {
         $ref=(int)$_DELETE['ref'];
         $wpdb->delete( $table_name, array( 'id' => $ref ) );
         echo "DELETED "+$ref;
     } else {
         echo "No/wrong DELETE params or nonce did not verify";
     }
   }

?>
