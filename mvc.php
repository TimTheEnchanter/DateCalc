<?php

//Model
class DayCalculator {

			 
	public function set($startDate, $endDate) {
		//Remove forward slashes from dates				
		$startDate = explode("/", $startDate);
		$endDate = explode("/", $endDate);
		
		$days = $this->calculateDifference($startDate, $endDate);//Call function to handle calculating difference between the two dates
		
        return $days;
    }
	
	public function calculateDifference($startDate, $endDate)
	{
		$totalNumberOfDays = 0;
		$daysInMonth = array(31,29,31,30,31,30,31,31,30,31,30,31);//Populate array with the days of each month
		$output = 0;
		
		//Piece out array created into respective variables for each part of each date 
		$startMonth = $startDate[0];
		$startMonth = (int)$startMonth;//Remove Zeroes from startMonth
		$startDay = $startDate[1];
		$startDay = (int)$startDay;//Remove Zeroes from startDay
		$startYear = $startDate[2];
		
		$endMonth = $endDate[0];
		$endMonth = (int)$endMonth;//Remove Zeroes from endMonth
		$endDay = $endDate[1];
		$endDay = (int)$endDay;//Remove Zeroes from endDay
		$endYear = $endDate[2];

		
		$yearDiff = $endYear - $startYear; //Determines number of years between start and end-date
		
		if($startYear <= $endYear)//Check to make sure start date begins before end date
		{
			if($startMonth == $endMonth && $startYear == $endYear)//If the two dates are in the same month
			{
				
					$output = $endDay - $startDay;
				
			}
			else if($startYear == $endYear)//If the two dates are separate months but in the same year
			{
				$monthDiff = $endMonth - $startMonth;//Gets the difference between the month of the end date and start date
				$startMonthDays = $daysInMonth[$startMonth-1] - $startDay;//Calculate the remaining number of days in starting month
								
				$totalNumberOfDays=$startMonthDays+$endDay; //Add previous calculation to the number of days in the ending month
				
				if($monthDiff > 1)//Check if there are any months in-between the start and end month
				{
					for($i=0; $i<($monthDiff-1); $i++)//If there are any use for loop to tally up number of days and add to $totalNumberOfDays
					{
						$totalNumberOfDays += $daysInMonth[$startMonth+$i];
					}
					
				}
				$output = $totalNumberOfDays;
			}
			else if($endYear > $startYear)//If the two dates have separate months and years
			{
								
				if($startMonth > $endMonth)//Checks to see if ending month comes before where the starting month would be ie. less than 365,730,etc days
				{
					$monthDiff = $startMonth-$endMonth;
					
					$endMonthDays = $daysInMonth[$endMonth-1] - $endDay;//Calculate the remaining number of days
								
					$totalNumberOfDays=($startDay+$endMonthDays);
					//Calculates the number of days between end date and completetion of a full year
					if($monthDiff > 1)
					{
						for($i=0; $i<($monthDiff-1); $i++)
						{	
							
							$totaltotalNumberOfDays += $daysInMonth[$endMonth+$i];
						}
					
					}
					
					$output = (365-$totaltotalNumberOfDays)*$yearDiff;//This number is then subtracted from the full year to get the number of days between the dates
					
				}
				else if($startMonth < $endMonth)//Checks if ending month is greater than starting month ie. greater than 365,730,etc days
				{
					$monthDiff = $endMonth - $startMonth;
					$startMonthDays = $daysInMonth[$startMonth-1] - $startDay;
								
					$totalNumberOfDays=$startMonthDays+$endDay;
					//Calculates the number of days between end of full year and the end date
					if($monthDiff > 1)
					{
						for($i=0; $i<($monthDiff-1); $i++)
						{
							$totalNumberOfDays += $daysInMonth[$startMonth+$i];
						}
					
					}
					$output = $totalNumberOfDays+(365*$yearDiff);//Add this number to the full year to get the total number of days
					
				}
				else
				{
					$output = ($endDay - $startDay)+(365*$yearDiff); //Calculates the number of days if the start and end dates share the same month but not year
					
				}
				
			}
			$output = $output." days";
			return $output;//returns the number of days
		}
		else//If the order of years was entered incorrectly ie Start Date 2018 - End Date 2016 output message warning user of this fact
		{
			$output = "<span style=\"color:red;\">The Start date was after the End date</span>";
			return $output;
		}
	}
	
}

//View 
class DayCalculatorView {

	public function output() { //Function that creates form seen on page.
			$html = '<form action="?action=calculate" method="post">
					<p>Start Date: <input name="start" type="text" class="date"></p>
					<p>End Date: <input name="end" type="text" class="date"></p>
					<input type="submit" value="Calculate" />
					</form>';

			return $html;
	}

}

//Controller
class DayCalculatorController {

	private $model;
    
    public function __construct($model) {
        $this->model = $model;
    }
    
    public function calculate($request) { //Function that is called when the form is submitted
        if (isset($request['start']) && isset($request['end'])) {
			if(!empty($request['start']) && !empty($request['end']))//Check to make sure fields are populated
			{
				$output = $this->model->set($request['start'], $request['end']);//Retrieve number of days between two dates
				echo $output;
			}
			else
			{
				$output = "<span style=\"color:red;\">Please make sure all form fields are filled out.</span>";
				echo $output;
			}
        }
		
    }


}







?>