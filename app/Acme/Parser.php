<?php
namespace App\Acme;


class Parser
{
    public static function Parse($rowData, $key) {
        foreach ($rowData as $rowGroup) {
            foreach ($rowGroup as $row) {
                foreach ($row as $index => $cell) {
                    if (is_array($cell) && in_array("" . $key . "", $cell)) {
                        // 找到“收款单位”所在的数组
                        $position = array_search($key, $cell);
                        if (isset($cell[$position + 1])) {
                            return $cell[$position + 1];
                        }
                    }
                }
            }
        }
        return null; // 如果没有找到，返回 null
    }

    public static function ParseArray($rowData, $keys) {
        $results = array_fill_keys($keys, null); // 初始化结果数组，所有键的初始值为 null

        foreach ($rowData as $rowGroup) {
            foreach ($rowGroup as $row) {
                foreach ($row as $index => $cell) {
                    if (is_array($cell)) {
                        foreach ($keys as $key) {
                            if (in_array($key, $cell)) {
                                // 找到键所在的数组
                                $position = array_search($key, $cell);
                                if (isset($cell[$position + 1])) {
                                    $results[$key] = $cell[$position + 1];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $results;
    }
}
