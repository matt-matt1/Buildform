<?php
namespace Yuma;
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
		/*		echo '<pre>'. print_r($_GET, true). '</pre>';*/
//		if (isset($_GET['url'])) {
			//echo '<pre>'. print_r($_GET, true). '</pre>';
			//$_GET['url'] = explode('/', $_GET['url']);
			//$_GET = array_merge( explode('/', trim( strip_tags($_GET['url']), '/' ) ), $_GET );
			//unset( $_GET['url'] );
			//$_GET = array_merge( explode('/', trim( strip_tags($uri), '/' ) ), $_GET );
/*			$_GET = explode('/', trim( strip_tags($uri), '/' ) );
			$_GET['page'] = (count($_GET) % 2) ? $_GET[count($_GET)-1] : $_GET[count($_GET)-2];
			for ($i=0; $i<count($_GET); $i++) {
				$a = $_GET[$i];
				if (isset($_GET[$i+1])) {
					$_GET[$a] = $_GET[$i+1];
					unset($_GET[$i]);
					++$i;
					unset($_GET[$i]);
				}
				unset($_GET[$i]);
			}
			echo '<pre>'. print_r($_GET, true). '</pre>';*/
			//echo '<pre>'. print_r($_GET, true). '</pre>';
			// store pairs into $_GET ie. (v1 = k1, v2 = k2, ...)
            ///////$_GET = array_pairs(trim(strip_tags($uri), '/'));
            if (isset($_GET['url'])) {
                $_GET = array_pairs(trim(strip_tags($_GET['url']), '/'));
            }
			if (defined('DEBUG_SHOW_HTTP_VARS') && \DEBUG_SHOW_HTTP_VARS)
			//if (isset($GLOBALS['data']['DEBUG_SHOW_HTTP_VARS']) && $GLOBALS['data']['DEBUG_SHOW_HTTP_VARS'])
			{
				echo '<pre>'. print_r($_GET, true). '</pre>'. "<br>\n";
				exit();
			}
			//Redirect("pages/{$parts[0]}.php");
/*			if (empty($_GET['page']))
			{
				throw new Exception("No valid page");
				exit();
			}*/
			$file = "pages/{$_GET['page']}.php";
			if (is_readable($file)) {
				include $file;
			} else {
				if ( isset($_POST['pressed']) && $_POST['pressed'] === 'ok' ) {
					Redirect( BASE. 'databases' );
				}
/*				header("HTTP/1.1 404 Not Found");
				Link::style( "home", "css/style.css", filemtime("css/style.css") );
				echo makeHead( t("Welcome to BuildForm") ), '<div class="container">', "\n";
				$note = new Note(Array(
					'message' => sprintf( t('Cannot find "%s"'), $file ),
					'type' => Note::error,
					'details' => t('_Try: /databases or /database/add or /database/<~database name~> [?remove or /table/add or /table/<~table name~> [?remove or /columns or /column/add or /column/<~column name~>]]'),
					//'type' => 'error',
					//'fatal' => true
				));
				echo $note->display(), '</div>', "\n";*/
				notFound($file);
			}
/*		} else {
			notFound();
			//open file
		}*/
