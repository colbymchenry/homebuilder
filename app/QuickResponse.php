<?php

namespace App;

class QuickResponse 
{
    public static function success($msg, ...$data) {
        $array = ['icon' => 'success', 'msg' => $msg];
        if(!empty($data) && is_array($data[0])) {
            foreach($data[0] as $key => $value) {
                $array[$key] = $value;
            }
        }

        return response()->json($array);
    }

    public static function warning($msg, ...$data) {
        $array = ['icon' => 'warning', 'msg' => $msg];
        if(!empty($data) && is_array($data[0])) {
            foreach($data[0] as $key => $value) {
                $array[$key] = $value;
            }
        }

        return response()->json($array);
    }

    public static function error($msg, ...$data) {
        $array = ['icon' => 'danger', 'msg' => $msg];
        if(!empty($data) && is_array($data[0])) {
            foreach($data[0] as $key => $value) {
                $array[$key] = $value;
            }
        }

        return response()->json($array);
    }

    public static function info($msg, ...$data) {
        $array = ['icon' => 'info', 'msg' => $msg];
        if(!empty($data) && is_array($data[0])) {
            foreach($data[0] as $key => $value) {
                $array[$key] = $value;
            }
        }

        return response()->json($array);
    }
}
