<?php
namespace App\Controller;

use Rs\JsonLines\JsonLines;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonLinesController
{
    public function number(): Response
    {
        $file = "https://s3-ap-southeast-2.amazonaws.com/catch-code-challenge/challenge-1-in.jsonl";
        $jsonlines = (new JsonLines())->delineEachLineFromFile($file);
        $csv = "out.csv";

        // open csv file
        $fp = fopen($csv, 'w');

        // add the column names
        fputcsv($fp, array(
            'order_id',
            'order_datetime',
            'total_order_value',
            'average_unit_price',
            'distinct_unit_count',
            'total_units_count',
            'customer_state'));

        foreach ($jsonlines as $json_line) {
            $json = json_decode($json_line, true);
            $total_qty = 0;
            $total_price = 0;
            $items = array();
            foreach ($json['items'] as $item) {
                if (array_key_exists($item['product']['product_id'], $items)) {
                    $items[$item['product']['product_id']] += $item['quantity'];
                } else {
                    $items[$item['product']['product_id']] = $item['quantity'];
                }
                $total_qty += intval($item['quantity']);
                $total_price += floatval($item['unit_price']);
            }
            // reduce the price with discounts, if any
            foreach ($json['discounts'] as $discount) {
                $total_price -= floatval($discount['value']);
            }

            // only add order with total value more than 0
            if ($total_price > 0) {
                fputcsv($fp, array(
                    $json['order_id'],
                    gmdate('Y-m-d\TH:i:s.u\Z', strtotime($json['order_date'])),
                    $total_price,
                    $total_price / $total_qty,
                    count($items),
                    $total_qty,
                    $json['customer']['shipping_address']['state']
                ));
            }
        }

        // release file handle
        fclose($fp);

        $jsonlines_out = (new JsonLines())->enline($this->csvToJson($csv));
        file_put_contents("out.jsonl", $jsonlines_out);

        return new Response('Successfully converted to csv file. <a href="../' . $csv .
            '" target="_blank">Click here to open it.</a><br/>' .
            'Or the Jsonlines file <a href="../out.jsonl" target="_blank">here</a>');
    }

    // function to convert csv to json format
    function csvToJson($fname) {
        // open csv file
        if (!($fp = fopen($fname, 'r'))) {
            die("Can't open file...");
        }

        //read csv headers
        $key = fgetcsv($fp,"1024",",");

        // parse csv rows into array
        $json = array();
        while ($row = fgetcsv($fp,"1024",",")) {
            $json[] = array_combine($key, $row);
        }

        // release file handle
        fclose($fp);

        // encode array to json
        return $json;
    }

 }
