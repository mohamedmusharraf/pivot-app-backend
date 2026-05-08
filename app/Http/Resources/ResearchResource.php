<?php 
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ResearchResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'fun_facts' => $this->fun_facts,
            'summary' => $this->summary,
            'video_link' => $this->video_link,
            'video_type' => $this->video_type,
            // 'full_content' => $this->full_content,
            'files' => $this->files,
        ];
    }
}
