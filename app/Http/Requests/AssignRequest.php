<?php

namespace App\Http\Requests;

use App\Repository\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AssignRequest extends BaseRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'user_role' => $this->extractUserType(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('user_roles')
                        ->where('user_id', $value)
                        ->whereExists(function ($query) {
                            $query->select('id')
                                ->from('roles')
                                ->where('name', $this->user_role);
                        })
                        ->exists();

                    if (!$exists) {
                        $fail("The selected user is not a $this->user_role.");
                    }
                },
            ],
        ];
    }

    private function extractUserType()
    {
        $pattern = '/api\/assign-(\w+)/';
        preg_match($pattern, $this->route()->uri, $matches);

        return $matches[1] ?? null;
    }
}
