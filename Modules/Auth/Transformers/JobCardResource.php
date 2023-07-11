<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class jobCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'dept_station' => $this->dept_station,
            'dept_time' => $this->dept_time,
            'train_num' => $this->train_num,
            'arr_station' => $this->arr_station,
            'arr_time' => $this->arr_time,
            'status' => $this->status,
            'track_time' => $this->track_time,
            'changeOver_time' => $this->changeOver_time,
            'changeOvers' => $this->changeOvers,
        ];
    }
}
