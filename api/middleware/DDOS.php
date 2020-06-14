<?php

    namespace Api\Middleware;

    use App\Models\BannedIp;

    trait DDOS
    {
        private $timeout = '5';//minutes
        private $lockTime = '10';//minutes

        private function ipCheck($ip)
        {
            $currentDate = new \DateTime();
            $currentDateFormat = $currentDate->format('Y-m-d H:i:s');

            $blockIpModel = new BannedIp();
            $blockIpModel->where(['ip' => $ip])->load();

            if ($blockIpModel->id) {
                $lockedTime = new \DateTime($blockIpModel->request_time);
                $lockedTime->add(new \DateInterval('PT' . $this->lockTime . 'M'));

                if ($blockIpModel->is_blocked) {
                    if ($this->compareDates($currentDate, $lockedTime)) {
                        return $this->responseDecorator(403, $lockedTime);
                    } else {
                        $blockIpModel->request_time = $currentDateFormat;
                        $blockIpModel->is_blocked = 0;
                        $blockIpModel->save();

                        return $this->responseDecorator();
                    }
                } else {
                    $time = new \DateTime($blockIpModel->request_time);
                    $time->add(new \DateInterval('PT' . $this->timeout . 'M'));

                    if ($this->compareDates(new \DateTime($blockIpModel->request_time), $time)) {
                        $blockIpModel->request_time = $currentDateFormat;
                        $blockIpModel->is_blocked = 1;
                        $blockIpModel->save();

                        return $this->responseDecorator(403, $lockedTime);
                    }
                }
            }

            $blockIpModel->ip = $ip;
            $blockIpModel->request_time = $currentDateFormat;
            $blockIpModel->is_blocked = 0;
            $blockIpModel->save();

            return $this->responseDecorator();
        }


        protected function responseDecorator($status = 200, $lockedTime = null)
        {
            if ($status === 200)
                return parent::response('Hello world', $status);
            else
                header('Retry-After:' . $lockedTime->format('D, d M Y H:i:s \G\M\T'));

            return parent::response([], $status);

        }


        /**
         * @param \DateTime $d1
         * @param \DateTime $d2
         *
         * @return bool|null
         */
        private function compareDates($date1, $date2)
        {
            if ($date1 < $date2)
                $res = true;
            elseif ($date1 == $date2)
                $res = null;
            else $res = false;

            return $res;
        }
    }