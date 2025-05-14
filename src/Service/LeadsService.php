<?php

namespace App\Service;

class LeadsService extends ApiService
{
    private const STATUS_IDS = [24374824, 142, 24374827]; 
    private const DATE_FIELD_ID = 1523889; 
    private const LEADS_COUNT = 25; 
    private const N = 3; 

    public function getLeadsByDate(int $timestamp): array
    {
        $leadsDay = [];
        $today = strtotime('today');
        
        for ($i = 0; $i < 30; $i++) {
            $dayTimestamp = $today + ($i * 24 * 60 * 60);
            $leadsDay[$dayTimestamp] = 0;
        }

        $endTimestamp = $today + (30 * 24 * 60 * 60);
        $offset = 0;
        $flag = true;

        while ($flag) {
            $response = $this->api->lead->getAll(
                null, 
                self::STATUS_IDS,
                null,
                null,
                self::LEADS_COUNT,
                $offset
            );

            if (empty($response['result'])) {
                $flag = false;
            } else {
                foreach ($response['result'] as $lead) {
                    foreach ($lead['custom_fields'] as $field) {
                        if ($field['id'] == self::DATE_FIELD_ID) {
                            $leadDate = strtotime($field['values'][0]['value']);
                            $leadDate = strtotime('today', $leadDate);
                            if (isset($leadsDay[$leadDate])) {
                                $leadsDay[$leadDate]++;
                            }
                            break;
                        }
                    }
                }
                $offset += self::LEADS_COUNT;
            }

            usleep(200000);
        }

        foreach ($leadsDay as $timestamp => $count) {
            if ($count >= self::N) {
                unset($leadsDay[$timestamp]);
            }
        }

        $this->logger->info('Завершение запроса сделок', ['leads_per_day' => $leadsDay]);

        return $leadsDay;
    }
}
