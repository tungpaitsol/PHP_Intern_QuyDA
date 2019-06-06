<?php
require 'Bai-1.data.php';

class Member
{
    private $code;
    private $full_name;
    private $age;
    private $marital_status;
    private $total_work_time;
    private $salary;
    private $start_work_time;
    private $workdays;
    private $workhour;
    private $has_lunch_break;

    public function __construct($_code, $_full_name, $_age, $_marital_status, $_salary, $_start_work_time, $_workhour, $_has_lunch_break, $_total_work_time = 0, $_workdays = 0)
    {
        $this->code = $_code;
        $this->full_name = $_full_name;
        $this->age = $_age;
        $this->marital_status = $_marital_status;
        $this->total_work_time = $_total_work_time;
        $this->salary = $_salary;
        $this->start_work_time = $_start_work_time;
        $this->workdays = $_workdays;
        $this->workhour = $_workhour;
        $this->has_lunch_break = $_has_lunch_break;
    }

    public function setSalary($_salary)
    {
        $this->salary = $_salary;
    }

    public function setWorkdays($_workdays)
    {
        $this->workdays = $_workdays;
    }

    public function setWorkhour($_workhour)
    {
        $this->workhour = $_workhour;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getFullName()
    {
        return $this->full_name;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function getMaritalStatus()
    {
        return $this->marital_status;
    }

    public function getTotalWorkTime()
    {
        return $this->total_work_time;
    }

    public function getSalary()
    {
        return $this->salary;
    }

    public function getStartWorkTime()
    {
        return $this->start_work_time;
    }

    public function getWorkdays()
    {
        return $this->workdays;
    }

    public function getWorkhour()
    {
        return $this->workhour;
    }

    public function getHasLunchBreak()
    {
        return $this->has_lunch_break;
    }
}

class ListWorkTime
{
    private $member_code;
    private $start_datetime;
    private $end_datetime;

    public function __construct($_member_code, $_start_datetime, $_end_datetime)
    {
        $this->member_code = $_member_code;
        $this->start_datetime = $_start_datetime;
        $this->end_datetime = $_end_datetime;
    }

    public function getMemberCode()
    {
        return $this->member_code;
    }

    public function getStartDatetime()
    {
        return $this->start_datetime;
    }

    public function getEndDatetime()
    {
        return $this->end_datetime;
    }
}

$member_fulltime = [];
for ($i = 0; $i < count($listMemberFullTime); $i++) {
    array_push($member_fulltime, $x = new Member($listMemberFullTime[$i]['code'], $listMemberFullTime[$i]['full_name'], $listMemberFullTime[$i]['age'], $listMemberFullTime[$i]['marital_status'], $listMemberFullTime[$i]['salary'], $listMemberFullTime[$i]['start_work_time'], $listMemberFullTime[$i]['work_hour'], $listMemberFullTime[$i]['has_lunch_break']));
}

$member_parttime = [];
for ($i = 0; $i < count($listMemberPartTime); $i++) {
    array_push($member_parttime, $y = new Member($listMemberPartTime[$i]['code'], $listMemberPartTime[$i]['full_name'], $listMemberPartTime[$i]['age'], $listMemberPartTime[$i]['marital_status'], $listMemberPartTime[$i]['salary'], $listMemberPartTime[$i]['start_work_time'], $listMemberPartTime[$i]['work_hour'], $listMemberPartTime[$i]['has_lunch_break']));
}

$worktime = [];
for ($i = 0; $i < count($listWorkTime); $i++) {
    array_push($worktime, $z = new ListWorkTime($listWorkTime[$i]['member_code'], $listWorkTime[$i]['start_datetime'], $listWorkTime[$i]['end_datetime']));
}


class ListMonthWorks
{
    private $holidays = ["2019-01-01", "2019-02-04", "2019-02-05", "2019-02-06", "2019-02-07", "2019-02-08", "2019-04-14", "2019-04-30", "2019-05-01", "2019-09-02"];


    public function WorkingDays($worktime)
    {
        $month = $worktime[0]->getStartDatetime();
        $y = date("Y",strtotime($month));
        $m = date("m",strtotime($month));

        $day_off = 0;

        for ($i = 1; $i <= date("t",strtotime($month)); $i++) {
            if (Date('D', strtotime(sprintf("%u-%u-%u", $y, $m, $i))) == 'Sat' || Date('D', strtotime(sprintf("%u-%u-%u", $y, $m, $i))) == 'Sun') {
                $day_off++;
            }
        }

        for ($i = 1; $i <= date("t",strtotime($month)); $i++) {
            foreach ($this->holidays as $day) {
                if (strtotime(sprintf("%u-%u-%u", $y, $m, $i)) == strtotime($day)) {
                    $day_off++;
                }
            }
        }

        return date("t",strtotime($worktime[0]->getStartDatetime())) - $day_off;
    }

    public function WorkDay($member, $workday)
    {
        foreach ($member as $v) {
            if ($v->getHasLunchBreak() == 1) {
                $has_lunch_break = 90 * 60;
            } else {
                $has_lunch_break = 0;
            }
            $count = 0;
            foreach ($workday as $value) {
                if ($value->getMemberCode() == $v->getCode()) {
                    $end_time = strtotime($v->getStartWorkTime()) + $v->getWorkHour() * 3600 + $has_lunch_break;
                    if ((date("H:i:s", strtotime($value->getStartDatetime())) <= date("H:i:s", strtotime($v->getStartWorkTime()))) && (date("H:i:s", strtotime($value->getEndDatetime())) >= date("H:i:s", $end_time))) {
                        $count = $count + 1;
                    } else {
                        $count = $count + 0.5;
                    }
                }
            }

            $v->setWorkdays($count);
        }

    }

    public function Salary($member, $worktime)
    {
        foreach ($member as $item) {
            $real_money = $item->getSalary() / $this->WorkingDays($worktime) * $item->getWorkdays();

            $item->setSalary($real_money);
        }
    }
}

$fulltime = new ListMonthWorks();

$fulltime->WorkDay($member_fulltime, $worktime);
$fulltime->Salary($member_fulltime, $worktime);
$fulltime->WorkingDays($worktime);

$parttime = new ListMonthWorks();

$parttime->WorkDay($member_parttime, $worktime);
$parttime->Salary($member_parttime, $worktime);

print_r($member_fulltime);
print_r($member_parttime);
