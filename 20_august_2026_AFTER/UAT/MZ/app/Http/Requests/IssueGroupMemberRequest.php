<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class IssueGroupMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'workflow_id' => 'required',
            'users' => ['required', 'array', 'min:1'],
            'users.*.id' => ['required', 'integer', 'exists:users,id'],
            'users.*.position' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (!is_array($this->users)) {
                return;
            }

            //  ONLY checked users (id exists)
            $checkedUsers = collect($this->users)
                ->filter(fn ($u) => isset($u['id']));

            if ($checkedUsers->isEmpty()) {
                $validator->errors()->add(
                    'users',
                    'At least one user must be selected'
                );
                return;
            }

            // user_id => subgroup_info_id
            $userSubgroups = DB::table('user_units')
                ->whereIn('user_id', $checkedUsers->pluck('id'))
                ->pluck('subgroup_info_id', 'user_id');

            // subgroup wise grouping
            $groupedBySubgroup = $checkedUsers->groupBy(function ($u) use ($userSubgroups) {
                return $userSubgroups[$u['id']] ?? null;
            });

            foreach ($groupedBySubgroup as $subgroupId => $users) {

                if (!$subgroupId) {
                    continue;
                }

                $positions = [];

                foreach ($users as $user) {

                    // ✅ Position required ONLY for checked users
                    if (!isset($user['position']) || $user['position'] === '') {
                        $validator->errors()->add(
                            'position',
                            'Position is required for each selected user'
                        );
                        return;
                    }

                    $positions[] = (int) $user['position'];
                }

                // duplicate position
                if (count($positions) !== count(array_unique($positions))) {
                    $validator->errors()->add(
                        'position',
                        'Position must be unique within a subgroup'
                    );
                    return;
                }

                // serial 1,2,3...
                sort($positions);
                foreach ($positions as $i => $pos) {
                    if ($pos !== $i + 1) {
                        $validator->errors()->add(
                            'position',
                            'Position must be sequential (1,2,3...)'
                        );
                        return;
                    }
                }
            }
        });
    }


    public function messages(): array
    {
        return [
            'users.required' => 'At least one user must be selected',
            'users.array' => 'Invalid user data format',
            'users.*.id.required' => 'User id is required',
            'users.*.id.exists' => 'Invalid user selected',
            'users.*.position.integer' => 'Position must be a number',
            'users.*.position.min' => 'Position must be greater than zero',
        ];
    }
}
