<?php
    class csv extends mysqli
    {
        private $state_csv = false;
        public function __construct(){
            parent::__construct("localhost","root", "", "csv");
            if ($this -> connect_error){
                echo "Failed To Connect To Database: ".$this->connect_error;
            }
        }

        public function import($file){
            $file = fopen($file, 'r');
            while ($row = fgetcsv($file)){
                // Convert array to string
                $value = "'" . implode("','",$row) . "'" ;
                $query  = "INSERT INTO csv(last,first,age) VALUES(".$value.")";
                if ($this->query($query)){
                    $this->state_csv = true;
                }
                else
                {
                    $this->state_csv = false;
                }
            }

            if ($this->state_csv) {
                echo "Successfully Imported";
            }
            else
            {
                echo "Successfully Failed";
            }

            fclose($file);

        }

        public function export(){
            $this->state_csv = false;
            $query  = "SELECT t.first, t.last, t.age FROM csv as t";
            $run = $this->query($query);

            if ($run -> num_rows > 0){
                $fn = "./file/csv_".uniqid().".csv";
                $file = fopen($fn, "w");

                // Get each row data to file
                while($row = $run->fetch_array(MYSQLI_NUM)){
                    if ( fputcsv($file, $row)) {
                        $this->state_csv = true;
                    }
                    else
                    {
                        $this->state_csv = false;
                    }
                }

                if ($this->state_csv) {
                    echo "Successfully Exported";
                }
                else
                {
                    echo "Export Failed";
                }
            }
            else
            {
                echo "Not Data Found";
            }
        }
    }
?>