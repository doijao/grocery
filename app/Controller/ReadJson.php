<?php
declare(strict_types=1);

namespace App\Controller;

class ReadJson
{
    private $results;
                        
    public function __construct(string $path_source)
    {
        $datas = $this->readJsonFile($path_source);
        
        foreach ($datas['skus'] as $rows) {
            $this->results[] = $rows;
        }
        
        if (isset($this->results)) {
           // echo "--> Filtering data <br />";
        } else {
            //echo "--> Failed to filter data <br />";
        }
    }

    public function getResult()
    {
        return $this->results;
    }

    // Read json file
    private function readJsonFile(string $path_source): array
    {
        if (!file_exists($path_source)) {
            die("Terminated: Can't find " . $path_source);
        }
        
        $contents = file_get_contents($path_source);
        //die(var_dump($contents));
        $toArray = json_decode($contents, true);
       //die(var_dump($toArray));
        if (empty($toArray['status'])) {
            die("Terminated: We didn't find content in the file.");
        }
        
        //echo "--> Analyzing json file. <br />";

        return $toArray;
    }
}
