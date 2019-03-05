<?php

namespace Code16\Formoj\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = "formoj_forms";

    protected $dates = [
        "created_at", "updated_at", "published_at", "unpublished_at"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
        return $this->hasMany(Section::class)
            ->orderBy("order");
    }

    /**
     * @return bool
     */
    public function isNotPublishedYet()
    {
        return $this->published_at && $this->published_at->isFuture();
    }

    /**
     * @return bool
     */
    public function isNoMorePublished()
    {
        return $this->unpublished_at && $this->unpublished_at->isPast();
    }

    /**
     * @param $id
     * @return Field|null
     */
    public function findField($id)
    {
        if($field = Field::find($id)) {
            return in_array($field->section_id, $this->sections->pluck("id")->all())
                ? $field
                : null;
        }

        return null;
    }

    /**
     * @param $data
     * @return Answer
     */
    public function storeNewAnswer($data)
    {
        return Answer::create([
            "form_id" => $this->id,
            "content" => collect($data)

                // Map to fields
                ->map(function($value, $id) {
                    return [
                        "field" => $this->findField(substr($id, 1)),
                        "value" => $value
                    ];
                })

                // Filter out unexpected fields
                ->filter(function($fieldAndValue) {
                    return !is_null($fieldAndValue["field"])
                        && !$fieldAndValue["field"]->isTypeHeading();
                })

                // Extract value (select case)
                ->mapWithKeys(function($fieldAndValue) {
                    $value = $fieldAndValue["value"];
                    $field = $fieldAndValue["field"];

                    if($field->isTypeSelect()) {
                        if($field->fieldAttribute("multiple")) {
                            $value = collect($value)
                                ->map(function($value) use($field) {
                                    return $field->fieldAttribute("options")[$value - 1] ?? null;
                                })
                                ->filter(function($value) {
                                    return !is_null($value);
                                })
                                ->all();

                        } else {
                            $value = $field->fieldAttribute("options")[$value - 1] ?? '';
                        }
                    }

                    return [$field->label => $value];
                })
                ->all()
        ]);
    }
}