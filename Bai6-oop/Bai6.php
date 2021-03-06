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
    array_push($member_fulltime, $x = new Member($listMemberFullTime[$i]['code'], $listMemberFullTime[$i]['full_name'],$listMemberFullTime[$i]['age'],$listMemberFullTime[$i]['marital_status'],$listMemberFullTime[$i]['salary'],$listMemberFullTime[$i]['start_work_time'], $listMemberFullTime[$i]['work_hour'],$listMemberFullTime[$i]['has_lunch_break']));

}

$member_parttime = [];

for ($i = 0; $i < count($listMemberPartTime); $i++) {
    array_push($member_parttime, $y = new Member($listMemberPartTime[$i]['code'], $listMemberPartTime[$i]['full_name'],$listMemberPartTime[$i]['age'],$listMemberPartTime[$i]['marital_status'],$listMemberPartTime[$i]['salary'],$listMemberPartTime[$i]['start_work_time'], $listMemberPartTime[$i]['work_hour'],$listMemberPartTime[$i]['has_lunch_break']));

}

$worktime = [];

for ($i = 0; $i < count($listWorkTime); $i++) {
    array_push($worktime, $z = new ListWorkTime($listWorkTime[$i]['member_code'], $listWorkTime[$i]['start_datetime'], $listWorkTime[$i]['end_datetime']));
}


class WorkDay
{
    public static function getWorkingDays($start, $end)
    {
        $end->modify('+1 day');

        $interval = $end->diff($start);

        $days = $interval->days;

        $period = new DatePeriod($start, new DateInterval('P1D'), $end);

        $holidays = ["2019-01-01", "2019-02-04", "2019-02-05", "2019-02-06", "2019-02-07", "2019-02-08", "2019-04-14", "2019-04-30", "2019-05-01", "2019-09-02"];

        foreach ($period as $dt) {
            $curr = $dt->format('D');

            if ($curr == 'Sat' || $curr == 'Sun') {
                $days--;
            }

            if (in_array($dt->format('Y-m-d'), $holidays)) {
                $days--;
            }
        }

        return $days;
    }
}

class ListMonthWorks
{
    private $listMonth = [[], [], [], [], [], [], [], [], [], [], [], []];
    private $arrayAllMonth = [];
    private $money = [];
    private $day_of_work = [];

    public function get_day_of_work_fulltime($member_fulltime, $workday)
    {
        foreach ($member_fulltime as $v) {
            $count = 0;
            foreach ($workday as $value) {
                if ($v->getCode() == $value->getMemberCode()) {
                    if ((((strtotime($value->getEndDatetime()) - strtotime($value->getStartDatetime()) - 90 * 60) / 3600) >= 4) && (((strtotime($value->getEndDatetime()) - strtotime($value->getStartDatetime()) - 90 * 60) / 3600) < 8)) {
                        $count = $count + 0.5;
                    }
                    if (((strtotime($value->getEndDatetime()) - strtotime($value->getStartDatetime()) - 90 * 60) / 3600) >= 8) {
                        $count = $count + 1;
                    }
                }
            }
            array_push($this->day_of_work, $count);
        }

        return $this->day_of_work;

    }

    public function get_day_of_work_parttime($member_parttime, $workday)
    {
        foreach ($member_parttime as $v) {
            $count = 0;
            foreach ($workday as $value) {
                if ($v->getCode() == $value->getMemberCode()) {
                    if (((strtotime($value->getEndDatetime()) - strtotime($value->getStartDatetime())) / 3600) >= $v->getWorkhour()) {
                        $count = $count + 1;
                    }
                }
            }
            array_push($this->day_of_work, $count);
        }

        return $this->day_of_work;

    }

    public function day_of_work($member)
    {
        for ($i = 0; $i < count($member); $i++) {
            $member[$i]->setWorkdays($this->day_of_work[$i]);
        }
    }

    public function Month($workday)
    {
        foreach ($workday as $item) {
            $date = getdate(strtotime($item->getStartDateTime()));

            for ($i = 1; $i <= count($this->listMonth); $i++) {
                if ($date['mon'] == $i) {
                    array_push($this->listMonth[$i - 1], $item);
                }
            }

        }

        return $this->listMonth;
    }

    public function AllMonth_fulltime($member_fulltime)
    {
        foreach ($this->listMonth as $month) {
            $people = [];
            foreach ($member_fulltime as $member) {
                $count = 0;
                foreach ($month as $array) {
                    if ($array->getMemberCode() == $member->getCode()) {
                        if ((((strtotime($array->getEndDatetime()) - strtotime($array->getStartDatetime()) - 90 * 60) / 3600) >= 4)
                            && (((strtotime($array->getEndDatetime()) - strtotime($array->getStartDatetime()) - 90 * 60) / 3600) < 8)) {
                            $count = $count + 0.5;
                        }
                        if (((strtotime($array->getEndDatetime()) - strtotime($array->getStartDatetime()) - 90 * 60) / 3600) >= 8) {
                            $count = $count + 1;
                        }

                        $people[(int)$member->getCode()] = $count;
                    }
                }

            }
            $this->arrayAllMonth[] = $people;

        }

        return $this->arrayAllMonth;
    }

    public function AllMonth_parttime($member_parttime)
    {
        foreach ($this->listMonth as $month) {
            $people = [];
            foreach ($member_parttime as $member) {
                $count = 0;
                foreach ($month as $array) {
                    if ($array->getMemberCode() == $member->getCode()) {
                        if (((strtotime($array->getEndDatetime()) - strtotime($array->getStartDatetime())) / 3600) >= $member->getWorkhour()) {
                            $count = $count + 1;
                        }

                        $people[(int)$member->getCode()] = $count;
                    }
                }

            }
            $this->arrayAllMonth[] = $people;

        }

        return $this->arrayAllMonth;
    }

    public function Money($member)
    {
        for ($k = 0; $k < count($this->arrayAllMonth); $k++) {
            for ($i = 0; $i < count($member); $i++) {
                if (!isset($this->arrayAllMonth[$k][(int)$member[$i]->getCode()])) {
                    $this->arrayAllMonth[$k][(int)$member[$i]->getCode()] = 0;
                }

            }
        }

//        for ($i = 0; $i < count($member); $i++) {
//            if (!isset($this->arrayAllMonth[0][(int)$member[$i]->getCode()])) {
//                $this->arrayAllMonth[0][(int)$member[$i]->getCode()] = 0;
//            }
//            if (!isset($this->arrayAllMonth[1][(int)$member[$i]->getCode()])) {
//                $this->arrayAllMonth[1][(int)$member[$i]->getCode()] = 0;
//            }
//            if (!isset($this->arrayAllMonth[2][(int)$member[$i]->getCode()])) {
//                $this->arrayAllMonth[2][(int)$member[$i]->getCode()] = 0;
//            }
//            if (!isset($this->arrayAllMonth[3][(int)$member[$i]->getCode()])) {
//                $this->arrayAllMonth[3][(int)$member[$i]->getCode()] = 0;
//            }
//            if (!isset($this->arrayAllMonth[4][(int)$member[$i]->getCode()])) {
//                $this->arrayAllMonth[4][(int)$member[$i]->getCode()] = 0;
//            }
//            if (!isset($this->arrayAllMonth[5][(int)$member[$i]->getCode()])) {
//                $this->arrayAllMonth[5][(int)$member[$i]->getCode()] = 0;
//            }
//            if (!isset($this->arrayAllMonth[6][(int)$member[$i]->getCode()])) {
//                $this->arrayAllMonth[6][(int)$member[$i]->getCode()] = 0;
//            }
//            if (!isset($this->arrayAllMonth[7][(int)$member[$i]->getCode()])) {
//                $this->arrayAllMonth[7][(int)$member[$i]->getCode()] = 0;
//            }
//            if (!isset($this->arrayAllMonth[8][(int)$member[$i]->getCode()])) {
//                $this->arrayAllMonth[8][(int)$member[$i]->getCode()] = 0;
//            }
//            if (!isset($this->arrayAllMonth[9][(int)$member[$i]->getCode()])) {
//                $this->arrayAllMonth[9][(int)$member[$i]->getCode()] = 0;
//            }
//            if (!isset($this->arrayAllMonth[10][(int)$member[$i]->getCode()])) {
//                $this->arrayAllMonth[10][(int)$member[$i]->getCode()] = 0;
//            }
//            if (!isset($this->arrayAllMonth[11][(int)$member[$i]->getCode()])) {
//                $this->arrayAllMonth[11][(int)$member[$i]->getCode()] = 0;
//            }
//        }

        for ($i = 0; $i < count($member); $i++) {
            $real_money =
                $member[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-01-01'), new DateTime('2019-01-31')) * $this->arrayAllMonth[0][(int)$member[$i]->getCode()]
                + $member[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-02-01'), new DateTime('2019-02-28')) * $this->arrayAllMonth[1][(int)$member[$i]->getCode()]
                + $member[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-03-01'), new DateTime('2019-03-31')) * $this->arrayAllMonth[2][(int)$member[$i]->getCode()]
                + $member[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-04-01'), new DateTime('2019-04-30')) * $this->arrayAllMonth[3][(int)$member[$i]->getCode()]
                + $member[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-05-01'), new DateTime('2019-05-31')) * $this->arrayAllMonth[4][(int)$member[$i]->getCode()]
                + $member[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-06-01'), new DateTime('2019-06-30')) * $this->arrayAllMonth[5][(int)$member[$i]->getCode()]
                + $member[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-07-01'), new DateTime('2019-07-31')) * $this->arrayAllMonth[6][(int)$member[$i]->getCode()]
                + $member[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-08-01'), new DateTime('2019-08-31')) * $this->arrayAllMonth[7][(int)$member[$i]->getCode()]
                + $member[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-09-01'), new DateTime('2019-09-30')) * $this->arrayAllMonth[8][(int)$member[$i]->getCode()]
                + $member[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-10-01'), new DateTime('2019-10-31')) * $this->arrayAllMonth[9][(int)$member[$i]->getCode()]
                + $member[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-11-01'), new DateTime('2019-11-30')) * $this->arrayAllMonth[10][(int)$member[$i]->getCode()]
                + $member[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-12-01'), new DateTime('2019-12-31')) * $this->arrayAllMonth[11][(int)$member[$i]->getCode()];

            array_push($this->money, $real_money);
        }

        return $this->money;
    }

    public function member($member)
    {
        for ($i = 0; $i < count($member); $i++) {
            $member[$i]->setSalary($this->money[$i]);
        }
    }

}

$fulltime = new ListMonthWorks();
$fulltime->Month($worktime);
$fulltime->AllMonth_fulltime($member_fulltime);
$fulltime->Money($member_fulltime);
$fulltime->member($member_fulltime);
$fulltime->get_day_of_work_fulltime($member_fulltime, $worktime);
$fulltime->day_of_work($member_fulltime);


$parttime = new ListMonthWorks();
$parttime->Month($worktime);
$parttime->AllMonth_parttime($member_parttime);
$parttime->Money($member_parttime);
$parttime->member($member_parttime);
$parttime->get_day_of_work_parttime($member_parttime, $worktime);
$parttime->day_of_work($member_parttime);


print_r($member_fulltime);
print_r($member_parttime);

