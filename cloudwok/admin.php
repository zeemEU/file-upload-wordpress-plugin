<?php
    define( 'BLOCK_LOAD', true );
    require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
    require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );

    global $wpdb;
    $table_name = $wpdb->prefix . 'cloudwok';
    $noncetxt = "cWn0Nc€4tW";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (
          ! isset( $_POST['cwnonce'] )
          || ! wp_verify_nonce( $_POST['cwnonce'], $noncetxt )
      ) {
         print 'Sorry, your nonce did not verify.';
         exit;
      }
      // update
     if(!array_diff(array('ref','code'), array_keys($_POST)))
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
      } else if(isset($_POST['code'])) {
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
        echo "No POST params";
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
     if (
         ! isset( $_DELETE['cwnonce'] )
         || ! wp_verify_nonce( $_DELETE['cwnonce'], $noncetxt )
     ) {
        print 'Sorry, your nonce did not verify.';
        exit;
     }
      // delete row
     if(isset($_DELETE['ref']))
     {
         $ref=(int)$_DELETE['ref'];
         $wpdb->delete( $table_name, array( 'ID' => $ref ) );
         echo "DELETED "+$ref;
     }
   }
    ?>
