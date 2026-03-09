<?php 
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ResearchResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'research_summary' => $this->research_summary,
            'research_full_text' => $this->research_full_text,
            'files' => $this->files,
            'category' => $this->category,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
