<?php

/**
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
function v() {
    $route_views = [
        'index'             => 'index',
        'about'             => 'about',
        'contacts'          => 'contacts',
        'create_article'	=> null,
        'admin_articles'	=> 'admin/articles',
        'articles'			=> 'articles',
        'article'			=> 'article',
        'update_article'	=> 'admin/article_change',
        'delete_article'	=> null,
        'services'          => 'services',
        'service'           => 'service',
        'admin'				=> 'admin/admin',
        'login'				=> 'admin/login',
        'logging'			=> null,
        'logout'			=> null,
        'admin_feedback'    =>'admin/feedback',
        'admin_subscribers' =>'admin/subscribers',

    ];

    return view($route_views[Route::currentRouteName()]);
}

function l($route_name, $params=[]) {
    return URL::route($route_name, $params);
}

function r() {
    return Route::currentRouteName();
}

function min_round($number, $dozens=2, $divide=1) {
    if (0 == $divide) {
        throw new Exception("Division by zero!");
    }

    $number = round($number/pow(10, $dozens))*pow(10, $dozens);
    return $number/$divide;
}

function make_options($array, $key, $value) {
    $options = [];

    foreach ($array as $element) {
        $options[$element->$key] = $element->$value;
    }
    return $options;
}

function log_sql($switch="on") {
    if ('off' == $switch) {
        DB::listen(function($sql) { return null; });
        return 'Sql logging is OFF. BUT CURRENTLY NOT WORKING!';
    } else {
        DB::listen(function($sql) { var_dump($sql); });
        return 'Sql logging is ON.';
    }
}

function dir_path($path='root') {
    if ('layout' == $path) {
        return public_path().DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'layout';
    } elseif ('carousel' == $path) {
        return public_path().DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'carousel';
    } elseif ('articles' == $path) {
        return public_path().DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'photos'.DIRECTORY_SEPARATOR.'articles';
    } elseif ('estates' == $path) {
        return public_path().DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'photos'.DIRECTORY_SEPARATOR.'estates';
    } else {
        return public_path();
    }
}

function url_path($path='root') {
    if ('layout' == $path) {
        return asset('img/layout');
    } elseif ('carousel' == $path) {
        return asset('img/carousel');
    } elseif ('articles' == $path) {
        return asset('img/photos/articles');
    } elseif ('estates' == $path) {
        return asset('img/photos/estates');
    } else {
        return asset('');
    }
}

function dir_sep() {
    return DIRECTORY_SEPARATOR;
}

function read_dir($dir='D:\\EasyPHP-DevServer-14.1VC11\\data\\localweb\\projects\\villa\\public\\img\\photos') {
    $result = [];

    if (!file_exists($dir)) {
        echo "<span style='color: red'>ERROR: no \"$dir\" directory found!</span></br>";
        return;
    }

    $files = scandir($dir);
    foreach ($files as $key => $value) {
        if (!in_array($value, array(".",".."))) {
            if (is_dir($dir.DIRECTORY_SEPARATOR.$value)) {
                $result[$value] = read_dir($dir.DIRECTORY_SEPARATOR.$value);
            } else {
                $result[] = iconv('Windows-1251', "UTF-8", $value);
            }
        }
    }

    return $result;
}

function columnize($array, $columns, $current) {
    $current--; // set indexes from 1
    $array = method_exists($array, 'all') ? $array->all() : $array;
    $count = count($array);
    $rest = $count % $columns;
    $base = ($count - $rest)/$columns;
    $borders = [];

    for ($i=0; $i<$columns; $i++) {
        if ($i > 0) {
            $borders[$i] = $base + $borders[$i-1];
        } else {
            $borders[$i] = $base;
        }
        if ($rest > 0) {
            $borders[$i]++;
            $rest--;
        }
    }

    if ($current > 0) {
        $start = $borders[$current-1];
        $length = $borders[$current]-$borders[$current-1];
    } else {
        $start = 0;
        $length = $borders[$current];
    }

    return array_slice($array, $start, $length);
}

function s($str, $options = array()) {
    // URL SLUG https://gist.github.com/sgmurphy/3098978
    // Make sure string is in UTF-8 and strip invalid UTF-8 characters
    $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

    $defaults = array(
        'delimiter' => '_',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(
            '/\?/i' => '\_',
            '/\//i' => '\_',
            '/\^/i' => '\_',
        ),
        'transliterate' => true,
    );

    // Merge options
    $options = array_merge($defaults, $options);

    $char_map = array(
        // Latin
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
        'ÿ' => 'y',

        // Latin symbols
        '©' => '(c)',

        // Greek
        'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
        'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
        'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
        'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
        'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
        'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
        'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
        'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

        // Turkish
        'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
        'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',

        // Russian
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
        'я' => 'ya',

        // Ukrainian
        'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
        'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

        // Czech
        'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
        'ž' => 'z',

        // Polish
        'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
        'ż' => 'z',

        // Latvian
        'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
        'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
        'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
        'š' => 's', 'ū' => 'u', 'ž' => 'z'
    );

    // Make custom replacements
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

    // Transliterate characters to ASCII
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }

    // Replace non-alphanumeric characters with our delimiter
    $str = preg_replace('/[^\p{L}\p{Nd}^\/]+/u', $options['delimiter'], $str);

    // Remove duplicate delimiters
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

    // Truncate slug to max. characters
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

    // Remove delimiter from ends
    $str = trim($str, $options['delimiter']);

    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}

/*------------------------------------------------
| FILTERS
------------------------------------------------*/
function apply_filters(Illuminate\Database\Eloquent\Builder $query, $filters='') {
    parse_str($filters, $filters);

    foreach ($filters as $filter => $value) {
        if (''==$value or ';'==$value or '[]'==$value) {
            continue;
        }

        //resetting dependencies
        // if ('type'==$filter or 'commercial'==$filter) {
        // 	$filters = reset_dependencies($filters);
        // }

        $type = detect_filter_type($value);

        if ('check'==$type) {
            $query->where($filter, 1);
        } else if ('list'==$type) {
            $list = trim($value, '[]');
            $items = explode(';', $list);
            $query->whereIn($filter, $items);
        } else if ('range'==$type) {
            $range = explode(';', $value);
            $query->where($filter, '>=', $range[0])->where($filter, '<=', $range[1]);
        } else if ('type'==$type) {
            /*------------------------------------------------
            | JOIN DEPENDENCIES
            ------------------------------------------------*/
            if ('town_id'==$filter) {
                $filter = 'towns.town_id';
            }
            if ('district_id'==$filter) {
                $filter = 'districts.district_id';
            }
            /*----------------------------------------------*/

            $query->where($filter, $value);
        }
    }

    return $query;
}

function reset_dependencies($filters) {
    if (!in_array($filters['type'], ['flat', 'cottage', 'commercial'])) {
        unset($filters['house_area']);
        unset($filters['rooms']);
    }
    if (!in_array($filters['type'], ['cottage', 'parcel', 'commercial'])) {
        unset($filters['yard_area']);
    }
    if (!in_array($filters['commercial'], ['rent'])) {
        unset($filters['period']);
    }

    return $filters;
}

function detect_filter_type($value) {
    if ('bool'==$value) {
        return 'check';
    } else if ('['==$value[0]) {
        return 'list';
    } else if (strpos($value, ';')) {
        return 'range';
    } else {
        return 'type';
    }
}

// function urlencode2($string) {
// 	$string = urlencode($string);
// 	$string = str_replace("%2F", "-", $string);
// 	return $string;
// }

// public static function flat_array($arr) {
// 	$output = [];
// 	foreach($arr as $key => $val) {
// 		if (is_array($val)) {
// 			$output = array_merge($output, $val);
// 		} else {
// 			$output[$key] = $val;
// 		}
// 	}
// 	return $output;
// }

// private static function tofloat($num) {
// 	$dotPos = strrpos($num, '.');
// 	$commaPos = strrpos($num, ',');
// 	$sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
// 		((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

// 	if (!$sep) {
// 		return floatval(preg_replace("/[^0-9]/", "", $num));
// 	}

// 	return floatval(
// 		preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
// 		preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
// 	);
// }

// public static function url_slug(array $arr) {
// 	$url = '';
// 	foreach ($arr as $chunk) {
// 		if ($chunk == '/') {
// 			$url .= $chunk;
// 		} else {
// 			$url .= static::url_slug_chunk($chunk);
// 		}
// 	}

// 	return $url;
// }

// public static function sendMail($data, $subject, $view, $email=null) {
// 	if (! $email) {
// 		$email = HELP::$admin_email;
// 	}

// 	$mail = new PHPMailer;
// 	$mail->CharSet = "UTF-8";

// 	$mail->isSMTP(); // Set mailer to use SMTP
// 	$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
// 	$mail->SMTPAuth = true; // Enable SMTP authentication
// 	$mail->Username = HELP::$site_email; // SMTP username
// 	$mail->Password = HELP::$site_password; // SMTP password
// 	$mail->SMTPSecure = 'tls'; // Enable encryption, 'ssl' also accepted
// 	$mail->Port = 587;         // TCP port to connect to

// 	// $mail->From = 'sportsecretshop@gmail.com';
// 	$mail->From = 'ZIPO';
// 	$mail->FromName = 'Zipo';
// 	$mail->addAddress($email); // Add a recipient
// 	// $mail->addAddress('ellen@example.com'); // Name is optional
// 	// $mail->addReplyTo('info@example.com', 'Information');
// 	// $mail->addCC('cc@example.com');
// 	// $mail->addBCC('bcc@example.com');

// 	// $mail->WordWrap = 50; // Set word wrap to 50 characters
// 	// $mail->addEmbeddedImage('public/img/vsx15.jpg', 'embed_1'); // Add attachments
// 	// $mail->addAttachment('public/img/vsx15.jpg', ''); // Add attachments
// 	// $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name
// 	$mail->isHTML(true); // Set email format to HTML

// 	// $mail->Subject = 'Заказ оформлен';
// 	$mail->Subject = $subject;
// 	$mail->Body = View::make($view, $data);
// 	// $mail->Body = 'This is the HTML message body <b>in bold!</b>';
// 	// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

// 	if ( ! $mail->send()) {
// 		echo 'Message could not be sent.';
// 		echo 'Mailer Error: ' . $mail->ErrorInfo;
// 	}
// }

// public static function __delete($Model, $message, $title, $redirect='back') {
// 	$instance = new $Model; // create instance to get primaryKey
// 	$object_id = Input::get($instance->primaryKey);
// 	$object = $Model::find($object_id);
// 	$Model::destroy($object_id);
// 	$message = sprintf($message, $object->$title);

// 	if ($redirect =='back') {
// 		return Redirect::back()->with('message', $message);
// 	} else {
// 		return Redirect::to($redirect)->with('message', $message);
// 	}
// }

// public static function formatDate($date) {
// 	$newDate = date("d-m-Y", strtotime($date));
// 	return $newDate;
// }