<?php

	class csv extends mysqli
	{
		private $state_csv = false;
    public function __construct()
    {
    	parent::__construct("localhost", "root", "", "csv");
    	if ($this->connect_error) {
    		echo 'Database connection Failed'. $this->connect_error;
    	}
    }

    public function import($file)
    {
    	$first = false;
    	$this->state_csv = false;

    	$file = fopen($file,'r');
    	while ($row = fgetcsv($file)) {
    		if (!$first ) {
    			$first = true;
    		}else {
    			$value = " ' ". implode(" ',' ", $row). " ' ";
	    		$sql = "INSERT INTO file(first, last,age) VALUES(".$value.")";
	    		$result = $this->query($sql);
	    		if ($result) {
	    			$this->state_csv = true;
	    		}else{
	    			$this->state_csv = false;
	    			echo $this->error;
	    		}
    		}
    	}

    	if ($this->state_csv) {
    		echo "Successfully Imported Data from CSV File";
    	}else {
    		echo "Not Import Data";
    	}
    }


    public function export()
    {
    	$this->state_csv = false;
    	$sql = "SELECT t.first, t.last, t.age FROM file as t";
    	$result = $this->query($sql);
    	if ($result->num_rows > 0) {
    		$fn = "csv_".uniqid().".csv";
    		$file = fopen("files/".$fn, "w");
    		while ($row = $result->fetch_array(MYSQLI_NUM)) {
    			if (fputcsv($file,$row)) {
    				$this->state_csv = true;
    			}else{
	    			$this->state_csv = false;
	    			echo $this->error;
	    		}
    		}
    		if ($this->state_csv) {
    			echo "Successfully Exported";
	    	}else {
	    		echo "Not Export Data";
	    	}
	    	fclose($file);
    	}else {
    		echo "No Data Found";
    	}

    }











	}

?>