<?php
/**
 * @since 1.0.0
 *
 * @package Plugin
 */
class AatopTracking
{
    private static $vacatures;
    private static $searches;
    private static $id;

    public function __construct($id = null)
    {
        if (!self::getUser($id)) {
            if (!self::saveUser($id)) {
                return null;
            }
        }
        self::$id = $id;
    }

    public static function getId()
    {
        return self::$id;
    }

    public static function setVacature($id = null)
    {
       return self::$vacatures = self::getTags($id,"vacature");
    }

    public static function setSearches($id = null)
    {
       return self::$searches[] = self::getTags($id,"search");
    }

    public static function getTags($id = null, $type = null)
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table = $wpdb->prefix . 'aatop_visitors';
        $vacature = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT tags FROM $table WHERE session_id = %s",
                $id ?? self::$id,
            )
        );

        if(count($vacature) == 1){
            $vacatures = json_decode($vacature[0], true)[0];
            
            if($type && isset($vacatures[$type])){
                return $vacatures[$type];
            }
            return $vacatures;
        }
        return [];
    }

    public static function getSearches($id = null)
    {
        return self::$searches;
    }

    public static function getVacatures($id = null)
    {
        return self::$vacatures;
    }

    public static function saveData(array $data)
    {
        $keys = ['identified','tags','type'];
        foreach ($keys as $key ) {
            if(!isset($data[$key])) {
                return false;
            }
        }
        if($tags = self::upDateHits($data['identified'], $data['tags'], $data['type'])) {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            $table_visitors = $wpdb->prefix . 'aatop_visitors';
            $user = $wpdb->query(
                $wpdb->prepare(
                    "REPLACE INTO $table_visitors (session_id,type,tags) VALUES (%s,%s,%s)",
                    $data['identified'],
                    $data['type'],
                    json_encode([ $tags]),
                )
            );
            return is_numeric($user) ? $user : false;
        }
        return false;
    }
    
    public static function getUser($id)
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_session = $wpdb->prefix . 'aatop_session';
        $user = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_session WHERE identified = %s",
                $id
            ),
            OBJECT
        );
        if (count($user) == 1) {
            return $user[0]->identified;
        }
        return false;
    }

    public static function saveUser($id = null)
    {
        global $wpdb;
        $table_session = $wpdb->prefix . 'aatop_session';
        $user = $wpdb->query(
            $wpdb->prepare(
                "INSERT INTO $table_session (identified) VALUES (%s)",
                $id ?? self::$id,
            )
        );
        return $user;
    }

    public static function upDateHits(string $id, array $data, string  $type)
    {
        if(!$data || !$type) {
            return false;
        }
        $existing_data = self::getTags($id) ?? [];
        foreach ($data as $key) {
            $counter = $existing_data[$type][$key] ?? 0;
            $counter = (int) $counter;
            $existing_data[$type][$key] = ++$counter;
        }
        if(count($existing_data[$type]) > 0) {
            return $existing_data;
        }
        return false;
    }
    
    public static function autocomplete_tags($id = null, $type = 'vacature', $num = 2)
    {
        if(!$id && self::$id != null) {
            $id = self::$id;
        }

        if(empty($id)) {
            return false;
        }

        $existing_data = self::getTags($id, $type);
        if(count($existing_data) > 0) {
            arsort($existing_data);
            return array_keys(
                array_slice(
                    $existing_data
                    ,0, $num
                )
            );
        }
        return false;
    }
}
