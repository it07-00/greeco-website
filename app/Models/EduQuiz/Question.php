<?php

namespace App\Models\EduQuiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    use SoftDeletes;

    protected $table = 'eduquiz_questions';

    protected $fillable = ['owner_id', 'prompt', 'explanation', 'image', 'seconds'];

    public function options(): HasMany
    {
        return $this->hasMany(Option::class)->orderBy('position');
    }

    public function snapshot(): array
    {
        return [
            ...$this->only('prompt', 'explanation', 'image', 'seconds'),
            'options' => $this->options->map->only(['text', 'correct'])->all(),
        ];
    }

    public function revise(array $attributes, array $options, int $editor): void
    {
        DB::transaction(function () use ($attributes, $options, $editor) {
            if ($this->exists) {
                $current = self::query()->whereKey($this->getKey())->lockForUpdate()->firstOrFail();
                $this->setRawAttributes($current->getAttributes(), true);
            }

            $this->fill($attributes)->save();
            $this->options()->delete();
            $this->options()->createMany(array_map(
                fn ($option, $position) => [...$option, 'position' => $position],
                $options,
                array_keys($options),
            ));
            $this->unsetRelation('options');

            DB::table('eduquiz_question_revisions')->insert([
                'question_id' => $this->id,
                'editor_id' => $editor,
                'content' => json_encode($this->snapshot(), JSON_THROW_ON_ERROR),
                'created_at' => now(),
            ]);
        });
    }
}
