<?php 
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class ResearchResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'summary' => $this->summary,
            'full_content' => $this->full_content,
            'files' => $this->files,
            // 'category' => $this->category,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
