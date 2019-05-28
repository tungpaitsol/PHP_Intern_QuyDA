<?php
require 'Bai-1.data.php';

class Member
{
    private $code;
    private $salary;
    private $workdays;
    private $workhour;

    public function __construct($_code, $_salary, $_workdays, $_workhour)
    {
        $this->code = $_code;
        $this->salary = $_salary;
        $this->workdays = $_workdays;
        $this->workhour = $_workhour;
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

    public function getSalary()
    {
        return $this->salary;
    }

    public function getWorkdays()
    {
        return $this->workdays;
    }

    public function getWorkhour()
    {
        return $this->workhour;
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
    array_push($member_fulltime, $x = new Member($listMemberFullTime[$i]['code'], $listMemberFullTime[$i]['salary'], $listMemberFullTime[$i]['workdays'], $listMemberFullTime[$i]['work_hour']));

}

$member_parttime = [];

for ($i = 0; $i < count($listMemberPartTime); $i++) {
    array_push($member_parttime, $y = new Member($listMemberPartTime[$i]['code'], $listMemberPartTime[$i]['salary'], $listMemberPartTime[$i]['workdays'], $listMemberPartTime[$i]['work_hour']));

}
//print_r($member_parttime);
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
    public $listMonth = [[], [], [], [], [], [], [], [], [], [], [], []];
    public $arrayAllMonth_fulltime = [];
    public $money_fulltime = [];
    public $day_of_work_fulltime = [];


    public $arrayAllMonth_parttime = [];
    public $money_parttime = [];
    public $day_of_work_parttime = [];

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
            array_push($this->day_of_work_fulltime, $count);
        }

        return $this->day_of_work_fulltime;

    }

    public function day_of_work_fulltime($member_fulltime)
    {
        for ($i = 0; $i < count($member_fulltime); $i++) {
            $member_fulltime[$i]->setWorkdays($this->day_of_work_fulltime[$i]);
        }
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
            array_push($this->day_of_work_parttime, $count);
        }

        return $this->day_of_work_parttime;

    }

    public function day_of_work_parttime($member_parttime)
    {
        for ($i = 0; $i < count($member_parttime); $i++) {
            $member_parttime[$i]->setWorkdays($this->day_of_work_parttime[$i]);
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
            $this->arrayAllMonth_fulltime[] = $people;

        }
//        print_r($this->arrayAllMonth_fulltime);
        return $this->arrayAllMonth_fulltime;
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
            $this->arrayAllMonth_parttime[] = $people;

        }
//print_r($this->arrayAllMonth_parttime);
        return $this->arrayAllMonth_parttime;
    }

    public function Money_fulltime($member_fulltime)
    {
        for ($i = 0; $i < count($member_fulltime); $i++) {
            if (!isset($this->arrayAllMonth_fulltime[0][(int)$member_fulltime[$i]->getCode()])) {
                $this->arrayAllMonth_fulltime[0][(int)$member_fulltime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_fulltime[1][(int)$member_fulltime[$i]->getCode()])) {
                $this->arrayAllMonth_fulltime[1][(int)$member_fulltime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_fulltime[2][(int)$member_fulltime[$i]->getCode()])) {
                $this->arrayAllMonth_fulltime[2][(int)$member_fulltime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_fulltime[3][(int)$member_fulltime[$i]->getCode()])) {
                $this->arrayAllMonth_fulltime[3][(int)$member_fulltime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_fulltime[4][(int)$member_fulltime[$i]->getCode()])) {
                $this->arrayAllMonth_fulltime[4][(int)$member_fulltime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_fulltime[5][(int)$member_fulltime[$i]->getCode()])) {
                $this->arrayAllMonth_fulltime[5][(int)$member_fulltime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_fulltime[6][(int)$member_fulltime[$i]->getCode()])) {
                $this->arrayAllMonth_fulltime[6][(int)$member_fulltime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_fulltime[7][(int)$member_fulltime[$i]->getCode()])) {
                $this->arrayAllMonth_fulltime[7][(int)$member_fulltime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_fulltime[8][(int)$member_fulltime[$i]->getCode()])) {
                $this->arrayAllMonth_fulltime[8][(int)$member_fulltime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_fulltime[9][(int)$member_fulltime[$i]->getCode()])) {
                $this->arrayAllMonth_fulltime[9][(int)$member_fulltime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_fulltime[10][(int)$member_fulltime[$i]->getCode()])) {
                $this->arrayAllMonth_fulltime[10][(int)$member_fulltime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_fulltime[11][(int)$member_fulltime[$i]->getCode()])) {
                $this->arrayAllMonth_fulltime[11][(int)$member_fulltime[$i]->getCode()] = 0;
            }

            $real_money =
                $member_fulltime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-01-01'), new DateTime('2019-01-31')) * $this->arrayAllMonth_fulltime[0][(int)$member_fulltime[$i]->getCode()]
                + $member_fulltime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-02-01'), new DateTime('2019-02-28')) * $this->arrayAllMonth_fulltime[1][(int)$member_fulltime[$i]->getCode()]
                + $member_fulltime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-03-01'), new DateTime('2019-03-31')) * $this->arrayAllMonth_fulltime[2][(int)$member_fulltime[$i]->getCode()]
                + $member_fulltime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-04-01'), new DateTime('2019-04-30')) * $this->arrayAllMonth_fulltime[3][(int)$member_fulltime[$i]->getCode()]
                + $member_fulltime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-05-01'), new DateTime('2019-05-31')) * $this->arrayAllMonth_fulltime[4][(int)$member_fulltime[$i]->getCode()]
                + $member_fulltime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-06-01'), new DateTime('2019-06-30')) * $this->arrayAllMonth_fulltime[5][(int)$member_fulltime[$i]->getCode()]
                + $member_fulltime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-07-01'), new DateTime('2019-07-31')) * $this->arrayAllMonth_fulltime[6][(int)$member_fulltime[$i]->getCode()]
                + $member_fulltime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-08-01'), new DateTime('2019-08-31')) * $this->arrayAllMonth_fulltime[7][(int)$member_fulltime[$i]->getCode()]
                + $member_fulltime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-09-01'), new DateTime('2019-09-30')) * $this->arrayAllMonth_fulltime[8][(int)$member_fulltime[$i]->getCode()]
                + $member_fulltime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-10-01'), new DateTime('2019-10-31')) * $this->arrayAllMonth_fulltime[9][(int)$member_fulltime[$i]->getCode()]
                + $member_fulltime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-11-01'), new DateTime('2019-11-30')) * $this->arrayAllMonth_fulltime[10][(int)$member_fulltime[$i]->getCode()]
                + $member_fulltime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-12-01'), new DateTime('2019-12-31')) * $this->arrayAllMonth_fulltime[11][(int)$member_fulltime[$i]->getCode()];

            array_push($this->money_fulltime, $real_money);
        }

        return $this->money_fulltime;
    }

    public function member_fulltime($member_fulltime)
    {
        for ($i = 0; $i < count($member_fulltime); $i++) {
            $member_fulltime[$i]->setSalary($this->money_fulltime[$i]);
        }
    }

    public function Money_parttime($member_parttime)
    {
        for ($i = 0; $i < count($member_parttime); $i++) {
            if (!isset($this->arrayAllMonth_parttime[0][(int)$member_parttime[$i]->getCode()])) {
                $this->arrayAllMonth_parttime[0][(int)$member_parttime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_parttime[1][(int)$member_parttime[$i]->getCode()])) {
                $this->arrayAllMonth_parttime[1][(int)$member_parttime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_parttime[2][(int)$member_parttime[$i]->getCode()])) {
                $this->arrayAllMonth_parttime[2][(int)$member_parttime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_parttime[3][(int)$member_parttime[$i]->getCode()])) {
                $this->arrayAllMonth_parttime[3][(int)$member_parttime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_parttime[4][(int)$member_parttime[$i]->getCode()])) {
                $this->arrayAllMonth_parttime[4][(int)$member_parttime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_parttime[5][(int)$member_parttime[$i]->getCode()])) {
                $this->arrayAllMonth_parttime[5][(int)$member_parttime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_parttime[6][(int)$member_parttime[$i]->getCode()])) {
                $this->arrayAllMonth_parttime[6][(int)$member_parttime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_parttime[7][(int)$member_parttime[$i]->getCode()])) {
                $this->arrayAllMonth_parttime[7][(int)$member_parttime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_parttime[8][(int)$member_parttime[$i]->getCode()])) {
                $this->arrayAllMonth_parttime[8][(int)$member_parttime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_parttime[9][(int)$member_parttime[$i]->getCode()])) {
                $this->arrayAllMonth_parttime[9][(int)$member_parttime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_parttime[10][(int)$member_parttime[$i]->getCode()])) {
                $this->arrayAllMonth_parttime[10][(int)$member_parttime[$i]->getCode()] = 0;
            }
            if (!isset($this->arrayAllMonth_parttime[11][(int)$member_parttime[$i]->getCode()])) {
                $this->arrayAllMonth_parttime[11][(int)$member_parttime[$i]->getCode()] = 0;
            }

            $real_money =
                $member_parttime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-01-01'), new DateTime('2019-01-31')) * $this->arrayAllMonth_parttime[0][(int)$member_parttime[$i]->getCode()]
                + $member_parttime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-02-01'), new DateTime('2019-02-28')) * $this->arrayAllMonth_parttime[1][(int)$member_parttime[$i]->getCode()]
                + $member_parttime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-03-01'), new DateTime('2019-03-31')) * $this->arrayAllMonth_parttime[2][(int)$member_parttime[$i]->getCode()]
                + $member_parttime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-04-01'), new DateTime('2019-04-30')) * $this->arrayAllMonth_parttime[3][(int)$member_parttime[$i]->getCode()]
                + $member_parttime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-05-01'), new DateTime('2019-05-31')) * $this->arrayAllMonth_parttime[4][(int)$member_parttime[$i]->getCode()]
                + $member_parttime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-06-01'), new DateTime('2019-06-30')) * $this->arrayAllMonth_parttime[5][(int)$member_parttime[$i]->getCode()]
                + $member_parttime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-07-01'), new DateTime('2019-07-31')) * $this->arrayAllMonth_parttime[6][(int)$member_parttime[$i]->getCode()]
                + $member_parttime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-08-01'), new DateTime('2019-08-31')) * $this->arrayAllMonth_parttime[7][(int)$member_parttime[$i]->getCode()]
                + $member_parttime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-09-01'), new DateTime('2019-09-30')) * $this->arrayAllMonth_parttime[8][(int)$member_parttime[$i]->getCode()]
                + $member_parttime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-10-01'), new DateTime('2019-10-31')) * $this->arrayAllMonth_parttime[9][(int)$member_parttime[$i]->getCode()]
                + $member_parttime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-11-01'), new DateTime('2019-11-30')) * $this->arrayAllMonth_parttime[10][(int)$member_parttime[$i]->getCode()]
                + $member_parttime[$i]->getSalary() / WorkDay::getWorkingDays(new DateTime('2019-12-01'), new DateTime('2019-12-31')) * $this->arrayAllMonth_parttime[11][(int)$member_parttime[$i]->getCode()];

            array_push($this->money_parttime, $real_money);
        }

//print_r($this->arrayAllMonth_parttime);
        return $this->money_parttime;
    }

    public function member_parttime($member_parttime)
    {
        for ($i = 0; $i < count($member_parttime); $i++) {
            $member_parttime[$i]->setSalary($this->money_parttime[$i]);

        }
    }

}

$z = new ListMonthWorks();
$z->Month($worktime);
$z->AllMonth_fulltime($member_fulltime);
$z->Money_fulltime($member_fulltime);
$z->member_fulltime($member_fulltime);
$z->get_day_of_work_fulltime($member_fulltime, $worktime);
$z->day_of_work_fulltime($member_fulltime);

$z->AllMonth_parttime($member_parttime);
$z->Money_parttime($member_parttime);
$z->member_parttime($member_parttime);
$z->get_day_of_work_parttime($member_parttime, $worktime);
$z->day_of_work_parttime($member_parttime);

print_r($member_fulltime);
print_r($member_parttime);
