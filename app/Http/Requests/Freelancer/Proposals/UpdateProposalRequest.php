<?php

namespace App\Http\Requests\Freelancer\Proposals;

use App\UseCases\Freelancer\Proposals\StoreProposalDto;
use App\UseCases\Freelancer\Proposals\UpdateProposalDto;
use App\ValueObjects\CoverLetter;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProposalRequest extends FormRequest
{
    public function rules()
    {
        return [
            'description'    => [
                'required',
                'string',
                'min:'.CoverLetter::MIN_LENGTH,
                'max:'.CoverLetter::MAX_LENGTH,
            ],
            'estimated_time' => [
                'required',
                'integer',
                'min:1'
            ],
        ];
    }

    public function getDto(): UpdateProposalDto
    {
        return new UpdateProposalDto($this->input('description'), $this->input('estimated_time'));
    }

    public function authorize()
    {
        return true;
    }
}
