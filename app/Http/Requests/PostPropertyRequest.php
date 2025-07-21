<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PostPropertyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'listing_type' => ['required', Rule::in(['sale', 'rent'])],
            'property_category_id' => 'required|integer|exists:property_categories,id',
            'covered_area' => 'required|numeric|min:0',
            'carpet_area' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'number_of_open_side'=> 'numeric|nullable',
            'width_of_road_facing_plot'=> 'numeric|nullable',
            'floor_allowed_for_construction'=> 'numeric|nullable',
            'is_price_negotiable' => 'required|boolean',
            'city' => ['required', 'string', 'max:255'],
            'locality' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $city = DB::table('cities')
                        ->whereRaw('LOWER(name) = ?', [strtolower($this->input('city'))])
                        ->first();

                    if (!$city) {
                        $fail('The selected city does not exist.');
                        return;
                    }

                    $locality = DB::table('localities')
                        ->whereRaw('LOWER(name) = ?', [strtolower($value)])
                        ->where('city_id', $city->id)
                        ->first();

                    if (!$locality) {
                        $fail('The selected locality does not exist in this city.');
                    }
                }
            ],
            'project_name' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (empty($value)) return;

                    $city = DB::table('cities')
                        ->whereRaw('LOWER(name) = ?', [strtolower($this->input('city'))])
                        ->first();

                    if (!$city) return;

                    $locality = DB::table('localities')
                        ->whereRaw('LOWER(name) = ?', [strtolower($this->input('locality'))])
                        ->where('city_id', $city->id)
                        ->first();

                    if (!$locality) return;

                    $project = DB::table('projects')
                        ->whereRaw('LOWER(name) = ?', [strtolower($value)])
                        ->where('locality_id', $locality->id)
                        ->first();

                    if (!$project) {
                        $fail('The selected project does not exist in this locality.');
                    }
                }
            ],
            'address' => 'required|string|max:500',
            'total_numbers' => 'required|integer|min:0',
            'additional_rooms' => 'nullable|array',
            'additional_rooms.*' => 'string|max:100',
            'overlooking' => 'nullable|array',
            'overlooking.*' => 'string|max:100',
            'directional_facing' => 'nullable|string|max:50',
            'ownership_type' => 'nullable|string|max:100',
            'more_property_details' => 'nullable|string',
            'transaction_type' => ['required', Rule::in(['new', 'resale'])],
            'availability_status' => ['required', Rule::in(['under_construction', 'ready_to_move'])],
            'possession' => 'required|date|after_or_equal:today',
            'approved_by_bank' => 'nullable|string|max:255',
            'amenities' => 'nullable|string|max:500',
            'flooring_type' => 'nullable|string|max:100',
            'landmark' => 'nullable|string|max:500',
            'feature_images'=>'required|array',
            'feature_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ];

        // Add conditional rules for non-plot properties
        if ($this->input('property_category_id') != 5 ||$this->input('property_category_id') != 11 ||$this->input('property_category_id') != 12 || $this->input('property_category_id') != 13 || $this->input('property_category_id') != 14) {
            $rules = array_merge($rules, [
                'bedrooms_count' => 'required|integer|min:0',
                'bathrooms_count' => 'required|integer|min:0',
                'balcony_count' => 'required|integer|min:0',
                'is_furnishing' => 'required|boolean',
                'floor_count' => 'integer|min:0',
                'total_floors' => 'integer|min:0',
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            // Listing type
            'listing_type.required' => 'The listing type field is required.',
            'listing_type.in' => 'The listing type must be either "sell" or "rent".',

            // Property category
            'property_category_id.required' => 'The property category field is required.',
            'property_category_id.integer' => 'The property category must be an integer.',
            'property_category_id.exists' => 'The selected property category is invalid.',

            // Area fields
            'covered_area.required' => 'The covered area field is required.',
            'covered_area.numeric' => 'The covered area must be a number.',
            'covered_area.min' => 'The covered area must be at least 0.',

            'carpet_area.required' => 'The carpet area field is required.',
            'carpet_area.numeric' => 'The carpet area must be a number.',
            'carpet_area.min' => 'The carpet area must be at least 0.',

            // Price fields
            'total_price.required' => 'The total price field is required.',
            'total_price.numeric' => 'The total price must be a number.',
            'total_price.min' => 'The total price must be at least 0.',

            'is_price_negotiable.required' => 'The price negotiable field is required.',
            'is_price_negotiable.boolean' => 'The price negotiable field must be true or false.',

            // Location fields
            'city.required' => 'The city field is required.',
            'city.string' => 'The city must be a string.',
            'city.max' => 'The city may not be greater than 255 characters.',

            'locality.required' => 'The locality field is required.',
            'locality.string' => 'The locality must be a string.',
            'locality.max' => 'The locality may not be greater than 255 characters.',

            'project_name.string' => 'The project name must be a string.',
            'project_name.max' => 'The project name may not be greater than 255 characters.',

            'address.required' => 'The address field is required.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 500 characters.',

            // Count fields
            'total_numbers.required' => 'The total numbers field is required.',
            'total_numbers.integer' => 'The total numbers must be an integer.',
            'total_numbers.min' => 'The total numbers must be at least 0.',

            'bedrooms_count.required' => 'The bedrooms count field is required.',
            'bedrooms_count.integer' => 'The bedrooms count must be an integer.',
            'bedrooms_count.min' => 'The bedrooms count must be at least 0.',

            'bathrooms_count.required' => 'The bathrooms count field is required.',
            'bathrooms_count.integer' => 'The bathrooms count must be an integer.',
            'bathrooms_count.min' => 'The bathrooms count must be at least 0.',

            'balcony_count.required' => 'The balcony count field is required.',
            'balcony_count.integer' => 'The balcony count must be an integer.',
            'balcony_count.min' => 'The balcony count must be at least 0.',

            'is_furnishing.required' => 'The furnishing field is required.',
            'is_furnishing.boolean' => 'The furnishing field must be true or false.',

            'floor_count.required' => 'The floor count field is required.',
            'floor_count.integer' => 'The floor count must be an integer.',
            'floor_count.min' => 'The floor count must be at least 0.',

            'total_floors.required' => 'The total floors field is required.',
            'total_floors.integer' => 'The total floors must be an integer.',
            'total_floors.min' => 'The total floors must be at least 0.',

            // Additional fields
            'additional_rooms.array' => 'The additional rooms must be an array.',
            'additional_rooms.*.string' => 'Each additional room must be a string.',
            'additional_rooms.*.max' => 'Each additional room may not be greater than 100 characters.',

            'overlooking.array' => 'The overlooking must be an array.',
            'overlooking.*.string' => 'Each overlooking item must be a string.',
            'overlooking.*.max' => 'Each overlooking item may not be greater than 100 characters.',

            'directional_facing.string' => 'The directional facing must be a string.',
            'directional_facing.max' => 'The directional facing may not be greater than 50 characters.',

            'ownershio_type.string' => 'The ownership type must be a string.',
            'ownershio_type.max' => 'The ownership type may not be greater than 100 characters.',

            'more_property_details.string' => 'The more property details must be a string.',

            // Transaction fields
            'transaction_type.required' => 'The transaction type field is required.',
            'transaction_type.in' => 'The transaction type must be either "new" or "resale".',

            'availability_status.required' => 'The availability status field is required.',
            'availability_status.in' => 'The availability status must be either "under_construction" or "ready_to_move".',

            // Date fields
            'possession.required' => 'The possession date field is required.',
            'possession.date' => 'The possession date must be a valid date.',
            'possession.after_or_equal' => 'The possession date must be today or in the future.',

            // Other fields
            'approved_by_bank.string' => 'The approved by bank must be a string.',
            'approved_by_bank.max' => 'The approved by bank may not be greater than 255 characters.',

            'amenities.string' => 'The amenities must be a string.',
            'amenities.max' => 'The amenities may not be greater than 500 characters.',

            'flooring_type.string' => 'The flooring type must be a string.',
            'flooring_type.max' => 'The flooring type may not be greater than 100 characters.',

            'landmark.string' => 'The landmark must be a string.',
            'landmark.max' => 'The landmark may not be greater than 500 characters.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$validator->failed()) {
                $city = DB::table('cities')
                    ->whereRaw('LOWER(name) = ?', [strtolower($this->input('city'))])
                    ->first();

                $locality = null;
                if ($city) {
                    $locality = DB::table('localities')
                        ->whereRaw('LOWER(name) = ?', [strtolower($this->input('locality'))])
                        ->where('city_id', $city->id)
                        ->first();
                }

                if ($this->filled('project_name') && $locality) {
                    $project = DB::table('projects')
                        ->whereRaw('LOWER(name) = ?', [strtolower($this->input('project_name'))])
                        ->where('locality_id', $locality->id)
                        ->first();

                    if ($project) {
                        $this->merge(['project_id' => $project->id]);
                    }
                }

                if ($city && $locality) {
                    $this->merge([
                        'city_id' => $city->id,
                        'locality_id' => $locality->id,
                    ]);
                }
            }
        });
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 422,
            'status' => false,
            'message' => 'Validation Error',
            'errors' => $validator->errors(),
            'data' => []
        ], 422));
    }
}
