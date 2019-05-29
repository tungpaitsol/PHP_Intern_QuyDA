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
    public $arrayAllMonth = [];
    public $money = [];
    public $day_of_work = [];

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

